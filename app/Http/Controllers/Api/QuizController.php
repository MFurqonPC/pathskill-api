<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\QuizQuestion;
use App\Models\UserQuizAnswer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class QuizController extends Controller
{
    /**
     * GET /assignments/{assignment}/quiz
     * Ambil quiz milik sebuah assignment beserta semua soal & opsinya
     * (tanpa expose is_correct), plus jawaban user kalau sudah pernah menjawab.
     */
    public function show(Request $request, Assignment $assignment): JsonResponse
    {
        $user = $request->user();

        $quiz = $assignment->quiz()->with(['questions.options'])->first();

        if (! $quiz) {
            return response()->json(['message' => 'Quiz belum tersedia untuk assignment ini.'], 404);
        }

        $answeredMap = UserQuizAnswer::where('user_id', $user->id)
            ->whereIn('quiz_question_id', $quiz->questions->pluck('id'))
            ->get()
            ->keyBy('quiz_question_id');

        return response()->json([
            'id' => $quiz->id,
            'assignment_id' => $quiz->assignment_id,
            'title' => $quiz->title,
            'total_questions' => $quiz->questions->count(),
            'questions' => $quiz->questions->map(function (QuizQuestion $q) use ($answeredMap) {
                $answer = $answeredMap->get($q->id);

                return [
                    'id' => $q->id,
                    'question' => $q->question,
                    'order' => $q->order,
                    'options' => $q->options->map(fn ($opt) => [
                        'id' => $opt->id,
                        'option_text' => $opt->option_text,
                        'order' => $opt->order,
                    ]),
                    'answered_option_id' => $answer?->quiz_option_id,
                    'is_correct' => $answer?->is_correct,
                    // explanation & correct_option_id cuma dikirim kalau sudah dijawab,
                    // supaya tidak bocor sebelum user mencoba.
                    'explanation' => $answer ? $q->explanation : null,
                    'correct_option_id' => $answer
                        ? $q->options->firstWhere('is_correct', true)?->id
                        : null,
                ];
            }),
        ]);
    }

    /**
     * POST /quiz-questions/{question}/answer
     * Body: { option_id: number }
     */
    public function answer(Request $request, QuizQuestion $question): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'option_id' => ['required', 'integer'],
        ]);

        $option = $question->options()->find($validated['option_id']);

        if (! $option) {
            throw ValidationException::withMessages([
                'option_id' => 'Opsi jawaban tidak ditemukan untuk soal ini.',
            ]);
        }

        $answer = UserQuizAnswer::updateOrCreate(
            ['user_id' => $user->id, 'quiz_question_id' => $question->id],
            ['quiz_option_id' => $option->id, 'is_correct' => $option->is_correct]
        );

        $correctOption = $question->options()->where('is_correct', true)->first();

        return response()->json([
            'is_correct' => $answer->is_correct,
            'correct_option_id' => $correctOption?->id,
            'explanation' => $question->explanation,
        ]);
    }
}
