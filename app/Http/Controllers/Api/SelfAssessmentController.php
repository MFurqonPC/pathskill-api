<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Models\ExperienceChecklistItem;
use App\Models\ScenarioConfidenceItem;
use App\Models\UserExperienceChecklist;
use App\Models\UserVerificationAnswer;
use App\Models\UserScenarioConfidence;
use App\Models\VerificationQuizQuestion;
use App\Models\VerificationQuizAttempt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SelfAssessmentController extends Controller
{
    /**
     * GET /api/careers/{career}/self-assessment
     * Konten Step 2: Experience Checklist + Scenario Confidence + 1 Short Verification Task (warmup).
     */
    public function step2Content(Request $request, Career $career): JsonResponse
    {
        $userId = $request->user()->id;

        $checklist = ExperienceChecklistItem::where('career_id', $career->id)
            ->orderBy('order')
            ->get()
            ->map(function ($item) use ($userId) {
                $checked = UserExperienceChecklist::where('user_id', $userId)
                    ->where('experience_checklist_item_id', $item->id)
                    ->value('checked');
                return [
                    'id' => $item->id,
                    'statement' => $item->statement,
                    'checked' => (bool) $checked,
                ];
            });

        $scenarios = ScenarioConfidenceItem::where('career_id', $career->id)
            ->orderBy('order')
            ->get()
            ->map(function ($item) use ($userId) {
                $confidence = UserScenarioConfidence::where('user_id', $userId)
                    ->where('scenario_confidence_item_id', $item->id)
                    ->value('confidence_level');
                return [
                    'id' => $item->id,
                    'scenario_text' => $item->scenario_text,
                    'confidence_level' => $confidence ?? 3,
                ];
            });

        $warmupQuestion = VerificationQuizQuestion::where('career_id', $career->id)
            ->where('is_warmup', true)
            ->first();

        $warmupAttempt = null;
        $warmupAlreadyAnswered = null;

        if ($warmupQuestion) {
            // Catat sesi warmup (agar tab switch bisa dilacak)
            $warmupAttempt = VerificationQuizAttempt::firstOrCreate(
                ['user_id' => $userId, 'career_id' => $career->id, 'is_warmup' => true],
                ['status' => 'in_progress', 'started_at' => now()]
            );

            if ($warmupAttempt->isCompleted()) {
                $userAnswer = UserVerificationAnswer::where('user_id', $userId)
                    ->where('verification_quiz_question_id', $warmupQuestion->id)
                    ->first();

                if ($userAnswer) {
                    $warmupAlreadyAnswered = [
                        'selected_option_index' => $userAnswer->selected_option_index,
                        'is_correct' => $userAnswer->is_correct,
                        'correct_option_index' => $warmupQuestion->correct_option_index,
                        'explanation' => $warmupQuestion->explanation,
                    ];
                }
            }
        }

        if ($checklist->isEmpty() && $scenarios->isEmpty() && ! $warmupQuestion) {
            return response()->json([
                'message' => 'Konten self-assessment untuk career ini belum tersedia.',
            ], 404);
        }

        return response()->json([
            'checklist' => $checklist,
            'scenarios' => $scenarios,
            'warmup_question' => $warmupQuestion?->toSafeArray(),
            'warmup_completed' => $warmupAttempt?->isCompleted() ?? false,
            'warmup_previous_answer' => $warmupAlreadyAnswered,
        ]);
    }

    /**
     * POST /api/self-assessment/checklist
     */
    public function saveChecklist(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer', 'exists:experience_checklist_items,id'],
            'items.*.checked' => ['required', 'boolean'],
        ]);

        $userId = $request->user()->id;

        DB::transaction(function () use ($validated, $userId) {
            foreach ($validated['items'] as $item) {
                UserExperienceChecklist::updateOrCreate(
                    ['user_id' => $userId, 'experience_checklist_item_id' => $item['id']],
                    ['checked' => $item['checked']]
                );
            }
        });

        return response()->json(['message' => 'Checklist tersimpan.']);
    }

    /**
     * POST /api/self-assessment/confidence
     */
    public function saveConfidence(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer', 'exists:scenario_confidence_items,id'],
            'items.*.confidence_level' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        $userId = $request->user()->id;

        DB::transaction(function () use ($validated, $userId) {
            foreach ($validated['items'] as $item) {
                UserScenarioConfidence::updateOrCreate(
                    ['user_id' => $userId, 'scenario_confidence_item_id' => $item['id']],
                    ['confidence_level' => $item['confidence_level']]
                );
            }
        });

        return response()->json(['message' => 'Confidence level tersimpan.']);
    }

    /**
     * GET /api/careers/{career}/verification-quiz
     */
    public function quizQuestions(Request $request, Career $career): JsonResponse
    {
        $userId = $request->user()->id;

        // Catat sesi kuis utama
        $attempt = VerificationQuizAttempt::firstOrCreate(
            ['user_id' => $userId, 'career_id' => $career->id, 'is_warmup' => false],
            ['status' => 'in_progress', 'started_at' => now()]
        );

        if ($attempt->isCompleted()) {
            return response()->json([
                'message' => 'Quiz untuk career ini sudah pernah diselesaikan.',
                'already_completed' => true,
            ], 403);
        }

        $questions = VerificationQuizQuestion::where('career_id', $career->id)
            ->where('is_warmup', false)
            ->orderBy('order')
            ->get()
            ->map(fn ($q) => $q->toSafeArray());

        if ($questions->isEmpty()) {
            return response()->json(['message' => 'Quiz verification untuk career ini belum tersedia.'], 404);
        }

        return response()->json([
            'attempt_id' => $attempt->id,
            'questions' => $questions
        ]);
    }

    /**
     * POST /api/verification-quiz/{question}/answer
     */
    public function answerQuizQuestion(Request $request, VerificationQuizQuestion $question): JsonResponse
    {
        $userId = $request->user()->id;

        $attempt = VerificationQuizAttempt::where('user_id', $userId)
            ->where('career_id', $question->career_id)
            ->where('is_warmup', $question->is_warmup)
            ->first();

        if (! $attempt || $attempt->isCompleted()) {
            return response()->json(['message' => 'Sesi quiz sudah tidak valid atau sudah diselesaikan.'], 403);
        }

        $validated = $request->validate([
            'selected_option_index' => ['required', 'integer', 'min:0'],
        ]);

        $isCorrect = $validated['selected_option_index'] === $question->correct_option_index;

        UserVerificationAnswer::updateOrCreate(
            ['user_id' => $userId, 'verification_quiz_question_id' => $question->id],
            [
                'selected_option_index' => $validated['selected_option_index'],
                'is_correct' => $isCorrect,
            ]
        );

        // Jika ini soal warmup (1 soal), langsung selesaikan attempt-nya
        if ($question->is_warmup) {
            $attempt->update([
                'status' => 'completed',
                'completed_at' => now(),
                'score_percentage' => $isCorrect ? 100 : 0,
            ]);
        }

        return response()->json([
            'correct' => $isCorrect,
            'correct_option_index' => $question->correct_option_index,
            'explanation' => $question->explanation,
        ]);
    }

    /**
     * GET /api/careers/{career}/verification-quiz/result
     */
    public function quizResult(Request $request, Career $career): JsonResponse
    {
        $userId = $request->user()->id;

        $questionIds = VerificationQuizQuestion::where('career_id', $career->id)
            ->where('is_warmup', false)
            ->pluck('id');

        $totalQuestions = $questionIds->count();
        $answers = UserVerificationAnswer::where('user_id', $userId)
            ->whereIn('verification_quiz_question_id', $questionIds)
            ->get();

        $correctCount = $answers->where('is_correct', true)->count();
        $scorePercentage = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;

        // Kunci status attempt menjadi completed di akhir kuis
        $attempt = VerificationQuizAttempt::where('user_id', $userId)
            ->where('career_id', $career->id)
            ->where('is_warmup', false)
            ->first();

        if ($attempt && ! $attempt->isCompleted()) {
            $attempt->update([
                'status' => 'completed',
                'completed_at' => now(),
                'score_percentage' => $scorePercentage,
            ]);
        }

        return response()->json([
            'total_questions' => $totalQuestions,
            'answered' => $answers->count(),
            'correct' => $correctCount,
            'score_percentage' => $scorePercentage,
            'is_completed' => $attempt?->isCompleted() ?? false,
        ]);
    }

    /**
     * POST /api/careers/{career}/verification-quiz/log-tab-switch
     */
    public function logTabSwitch(Request $request, Career $career): JsonResponse
    {
        $isWarmup = (bool) $request->input('is_warmup', false);
        
        VerificationQuizAttempt::where('user_id', $request->user()->id)
            ->where('career_id', $career->id)
            ->where('is_warmup', $isWarmup)
            ->where('status', 'in_progress')
            ->increment('tab_switch_count');
        
        return response()->json([], 204);
    }
}