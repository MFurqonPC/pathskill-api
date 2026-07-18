<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Models\UserQuizAnswer;
use App\Models\UserScenarioConfidence;
use App\Models\VerificationQuizQuestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SkillMapController extends Controller
{
    /**
     * Bobot untuk blend "Tingkat Saat Ini". Self-rating tetap paling besar
     * porsinya (paling granular, per-skill), scenario confidence dan quiz
     * score jadi "koreksi" — misal user kepedean di self-rating tapi jawaban
     * quiz-nya banyak salah, tingkat saat ini akan sedikit dikoreksi turun.
     *
     * Total harus 1.0. Kalau Step 2/3 belum diisi user, bobotnya otomatis
     * dialihkan ke self-rating (lihat method blendCurrentLevel()).
     */
    private const WEIGHT_SELF_RATING = 0.5;
    private const WEIGHT_CONFIDENCE = 0.2;
    private const WEIGHT_QUIZ = 0.3;

    /**
     * GET /api/skill-map
     * Hitung "Your Skill Map": Tingkat Saat Ini (blend Step 1+2+3),
     * Tingkat yang Diperlukan, Kesenjangan Keterampilan, plus data
     * per-skill untuk radar chart (radar tetap murni dari Step 1,
     * karena confidence & quiz sifatnya general, bukan per-skill).
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user->career_goal_id) {
            return response()->json([
                'message' => 'User belum memilih career goal.',
            ], 422);
        }

        $career = Career::findOrFail($user->career_goal_id);

        // ==== STEP 1: Self-Rating per skill (existing) ====
        $skills = $career->skills()
            ->with(['assessments' => fn ($q) => $q->where('user_id', $user->id)])
            ->get();

        $ratedSkills = $skills->filter(fn ($skill) => $skill->assessments->isNotEmpty());

        if ($ratedSkills->isEmpty()) {
            return response()->json([
                'message' => 'Belum ada skill yang dinilai untuk career ini.',
            ], 422);
        }

        $selfRatingAvg = $ratedSkills->avg(fn ($skill) => $skill->assessments->first()->rating);
        $requiredLevel = round($ratedSkills->avg('industry_requirement'), 1);

        // ==== STEP 2: Scenario-Based Confidence (opsional, mungkin belum diisi) ====
        $confidenceAvg = UserScenarioConfidence::where('user_id', $user->id)
            ->whereHas('scenarioConfidenceItem', fn ($q) => $q->where('career_id', $career->id))
            ->avg('confidence_level'); // null kalau belum ada

        // ==== STEP 3: Skill Verification Quiz (opsional, mungkin belum diisi) ====
        $quizQuestionIds = VerificationQuizQuestion::where('career_id', $career->id)
            ->where('is_warmup', false)
            ->pluck('id');

        $quizAnswers = UserQuizAnswer::where('user_id', $user->id)
            ->whereIn('verification_quiz_question_id', $quizQuestionIds)
            ->get();

        $quizScorePercentage = null;
        $quizScoreOn5Scale = null;
        if ($quizAnswers->isNotEmpty()) {
            $correctCount = $quizAnswers->where('is_correct', true)->count();
            $answeredCount = $quizAnswers->count();
            $quizScorePercentage = round(($correctCount / $answeredCount) * 100);
            $quizScoreOn5Scale = ($correctCount / $answeredCount) * 5; // konversi ke skala 1-5 biar sepadan sama rating skill
        }

        $currentLevel = round(
            $this->blendCurrentLevel($selfRatingAvg, $confidenceAvg, $quizScoreOn5Scale),
            1
        );

        $skillGap = round(max($requiredLevel - $currentLevel, 0), 1);

        // radar chart TETAP murni dari Step 1 (per-skill), karena confidence
        // & quiz sifatnya general/tidak terikat ke satu skill spesifik
        $chartData = $skills->map(function ($skill) {
            return [
                'skill_name' => $skill->skill_name,
                'current' => $skill->assessments->first()?->rating ?? 0,
                'required' => $skill->industry_requirement,
            ];
        });

        return response()->json([
            'career' => $career->only(['id', 'name', 'icon']),
            'summary' => [
                'current_level' => $currentLevel,
                'required_level' => $requiredLevel,
                'skill_gap' => $skillGap,
                // transparansi: tunjukkan komponen pembentuk current_level,
                // supaya user tahu ini bukan angka sihir dan bisa lihat
                // sumber tiap komponennya
                'breakdown' => [
                    'self_rating' => round($selfRatingAvg, 1),
                    'scenario_confidence' => $confidenceAvg !== null ? round($confidenceAvg, 1) : null,
                    'quiz_score_percentage' => $quizScorePercentage,
                ],
            ],
            'chart_data' => $chartData,
        ]);
    }

    /**
     * Blend 3 sumber data jadi 1 angka "Tingkat Saat Ini" (skala 1-5).
     * Kalau confidence atau quiz belum diisi user, bobotnya dialihkan
     * proporsional ke komponen yang tersedia (bukan dianggap 0) — supaya
     * user yang baru sampai Step 1 tetap dapat Skill Map yang wajar,
     * nggak dihukum karena belum sempat isi Step 2/3.
     */
    private function blendCurrentLevel(float $selfRating, ?float $confidence, ?float $quizScore): float
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
}