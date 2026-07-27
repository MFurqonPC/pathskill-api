<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Models\SkillRecommendation;
use App\Models\UserVerificationAnswer;
use App\Models\UserScenarioConfidence;
use App\Models\VerificationQuizQuestion;
use App\Services\GroqService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class SkillMapController extends Controller
{
    /**
     * Bobot komponen "current level" per skill. Self-rating dominan (50%)
     * karena selalu tersedia (wajib di Step 1) dan paling granular.
     * Confidence (20%) & quiz (30%) menguatkan/mengoreksi self-rating yang
     * subjektif — HANYA berlaku untuk skill yang punya soal/skenario yang
     * ditag secara spesifik ke skill tersebut.
     *
     * Kalau confidence/quiz untuk skill itu belum ditag, bobotnya
     * di-redistribusi proporsional ke komponen yang tersedia (lihat
     * blendSkillLevel()) — BUKAN dianggap 0 dan BUKAN dipinjam dari
     * rata-rata career, supaya tidak menyesatkan skill lain yang memang
     * belum divalidasi secara spesifik.
     */
    private const WEIGHT_SELF_RATING = 0.5;
    private const WEIGHT_CONFIDENCE = 0.2;
    private const WEIGHT_QUIZ = 0.3;

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user->career_goal_id) {
            return response()->json([
                'message' => 'User belum memilih career goal.',
                'reason' => 'no_career_goal',
                'career_goal_id' => null,
            ], 422);
        }

        if (! $user->assessment_completed_at) {
            return response()->json([
                'message' => 'Assessment untuk career ini belum diselesaikan.',
                'reason' => 'not_assessed',
                'career_goal_id' => $user->career_goal_id,
            ], 422);
        }

        $career = Career::findOrFail($user->career_goal_id);

        // ==== STEP 1: Self-Rating per skill ====
        $skills = $career->skills()
            ->with(['assessments' => fn ($q) => $q->where('user_id', $user->id)])
            ->get();

        $ratedSkills = $skills->filter(fn ($skill) => $skill->assessments->isNotEmpty());

        if ($ratedSkills->isEmpty()) {
            return response()->json([
                'message' => 'Belum ada skill yang dinilai untuk career ini.',
                'reason' => 'not_assessed',
                'career_goal_id' => $user->career_goal_id,
            ], 422);
        }

        $requiredLevel = round($ratedSkills->avg('industry_requirement'), 1);

        // ==== STEP 2: Scenario Confidence, dikelompokkan per skill_id ====
        $confidenceBySkill = UserScenarioConfidence::where('user_id', $user->id)
            ->whereHas('scenarioConfidenceItem', fn ($q) => $q->where('career_id', $career->id))
            ->with('scenarioConfidenceItem:id,skill_id')
            ->get()
            ->groupBy(fn ($c) => $c->scenarioConfidenceItem->skill_id)
            ->map(fn ($group) => $group->avg('confidence_level'));

        // ==== STEP 3: Quiz, dikelompokkan per skill_id ====
        $quizQuestions = VerificationQuizQuestion::where('career_id', $career->id)
            ->where('is_warmup', false)
            ->get(['id', 'skill_id']);

        $quizAnswers = UserVerificationAnswer::where('user_id', $user->id)
            ->whereIn('verification_quiz_question_id', $quizQuestions->pluck('id'))
            ->get();

        $questionSkillMap = $quizQuestions->pluck('skill_id', 'id');

        $quizBySkill = $quizAnswers
            ->groupBy(fn ($answer) => $questionSkillMap->get($answer->verification_quiz_question_id))
            ->map(function ($group) {
                $correct = $group->where('is_correct', true)->count();
                $total = $group->count();
                return $total > 0 ? ($correct / $total) * 5 : null;
            });

        // ==== Blend per skill + susun chart data ====
        $chartData = [];
        $blendedLevels = [];

        foreach ($skills as $skill) {
            $selfRating = $skill->assessments->first()?->rating;

            if ($selfRating === null) {
                $chartData[] = [
                    'skill_name' => $skill->skill_name,
                    'current' => null,
                    'required' => $skill->industry_requirement,
                    'is_rated' => false,
                    'is_confidence_validated' => false,
                    'is_quiz_validated' => false,
                ];
                continue;
            }

            $skillConfidence = $confidenceBySkill->get($skill->id);
            $skillQuiz = $quizBySkill->get($skill->id);

            $blended = $this->blendSkillLevel((float) $selfRating, $skillConfidence, $skillQuiz);
            $blendedLevels[] = $blended;

            $chartData[] = [
                'skill_name' => $skill->skill_name,
                'current' => round($blended, 1),
                'required' => $skill->industry_requirement,
                'is_rated' => true,
                'is_confidence_validated' => $skillConfidence !== null,
                'is_quiz_validated' => $skillQuiz !== null,
            ];
        }

        $currentLevel = round(array_sum($blendedLevels) / count($blendedLevels), 1);
        $skillGap = round(max($requiredLevel - $currentLevel, 0), 1);

        // Breakdown level career (murni informatif, transparansi ke user)
        $selfRatingAvgRaw = $ratedSkills->avg(fn ($skill) => $skill->assessments->first()->rating);
        $confidenceAvgRaw = UserScenarioConfidence::where('user_id', $user->id)
            ->whereHas('scenarioConfidenceItem', fn ($q) => $q->where('career_id', $career->id))
            ->avg('confidence_level');
        $quizScorePercentageRaw = $quizAnswers->isNotEmpty()
            ? round(($quizAnswers->where('is_correct', true)->count() / $quizAnswers->count()) * 100)
            : null;

        $recommendation = $this->buildRecommendation(
            $career,
            $ratedSkills,
            $currentLevel,
            $requiredLevel,
            $user->id
        );

        return response()->json([
            'career' => $career->only(['id', 'name', 'icon']),
            'summary' => [
                'current_level' => $currentLevel,
                'required_level' => $requiredLevel,
                'skill_gap' => $skillGap,
                'breakdown' => [
                    'self_rating' => round($selfRatingAvgRaw, 1),
                    'scenario_confidence' => $confidenceAvgRaw !== null ? round($confidenceAvgRaw, 1) : null,
                    'quiz_score_percentage' => $quizScorePercentageRaw,
                ],
            ],
            'chart_data' => $chartData,
            'recommendation' => $recommendation,
        ]);
    }

    private function blendSkillLevel(float $selfRating, ?float $confidence, ?float $quizScore): float
    {
        $components = [
            'self_rating' => ['value' => $selfRating, 'weight' => self::WEIGHT_SELF_RATING],
        ];

        if ($confidence !== null) {
            $components['confidence'] = ['value' => $confidence, 'weight' => self::WEIGHT_CONFIDENCE];
        }

        if ($quizScore !== null) {
            $components['quiz'] = ['value' => $quizScore, 'weight' => self::WEIGHT_QUIZ];
        }

        $totalWeight = array_sum(array_column($components, 'weight'));

        $blended = 0;
        foreach ($components as $component) {
            $blended += ($component['weight'] / $totalWeight) * $component['value'];
        }

        return $blended;
    }

    private function buildRecommendation(
        Career $career,
        \Illuminate\Support\Collection $ratedSkills,
        float $currentLevel,
        float $requiredLevel,
        int $userId
    ): ?array {
        $cached = SkillRecommendation::where('user_id', $userId)
            ->where('career_id', $career->id)
            ->first();

        if ($cached) {
            return [
                'foundation_summary' => $cached->foundation_summary,
                'priority_areas' => $cached->priority_areas,
                'priority_skill_names' => $cached->priority_skill_names,
                'estimated_weeks' => $cached->estimated_weeks,
            ];
        }

        $lockKey = "skill-recommendation-lock:{$userId}:{$career->id}";

        return Cache::lock($lockKey, 15)->block(5, function () use (
            $career, $ratedSkills, $currentLevel, $requiredLevel, $userId
        ) {
            $cached = SkillRecommendation::where('user_id', $userId)
                ->where('career_id', $career->id)
                ->first();

            if ($cached) {
                return [
                    'foundation_summary' => $cached->foundation_summary,
                    'priority_areas' => $cached->priority_areas,
                    'priority_skill_names' => $cached->priority_skill_names,
                    'estimated_weeks' => $cached->estimated_weeks,
                ];
            }

            $skillGaps = $ratedSkills->map(fn ($skill) => [
                'skill_name' => $skill->skill_name,
                'current' => $skill->assessments->first()->rating,
                'required' => $skill->industry_requirement,
            ])->values()->all();

            try {
                $result = app(GroqService::class)->generateSkillRecommendation(
                    $career,
                    $skillGaps,
                    $currentLevel,
                    $requiredLevel
                );

                SkillRecommendation::updateOrCreate(
                    ['user_id' => $userId, 'career_id' => $career->id],
                    [
                        'foundation_summary' => $result['foundation_summary'],
                        'priority_areas' => $result['priority_areas'],
                        'priority_skill_names' => $result['priority_skill_names'],
                        'estimated_weeks' => $result['estimated_weeks'],
                        'generated_at' => now(),
                    ]
                );

                return $result;
            } catch (Throwable $e) {
                Log::warning('Gagal generate skill recommendation dari Groq', [
                    'user_career_id' => $career->id,
                    'error' => $e->getMessage(),
                ]);

                return null;
            }
        });
    }
}