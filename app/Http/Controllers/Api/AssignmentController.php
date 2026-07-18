<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\UserCodingSubmission;
use App\Models\UserQuizAnswer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function show(Request $request, Assignment $assignment): JsonResponse
    {
        $user = $request->user();

        $assignment->loadMissing([
            'module',
            'userProgress' => fn ($q) => $q->where('user_id', $user->id),
            'quiz.questions',
            'codingExercise',
            'miniProject',
        ]);

        $progress = $assignment->userProgress->first();
        $quiz = $assignment->quiz;
        $codingExercise = $assignment->codingExercise;
        $miniProject = $assignment->miniProject;

        $quizCompleted = false;
        if ($quiz && $quiz->questions->isNotEmpty()) {
            $answeredCount = UserQuizAnswer::where('user_id', $user->id)
                ->whereIn('quiz_question_id', $quiz->questions->pluck('id'))
                ->count();
            $quizCompleted = $answeredCount === $quiz->questions->count();
        }

        $codingExerciseCompleted = false;
        if ($codingExercise) {
            $codingExerciseCompleted = UserCodingSubmission::where('user_id', $user->id)
                ->where('coding_exercise_id', $codingExercise->id)
                ->exists();
        }

        return response()->json([
            'id' => $assignment->id,
            'learning_module_id' => $assignment->learning_module_id,
            'module_title' => $assignment->module->title,
            'title' => $assignment->title,
            'description' => $assignment->description,
            'due_date' => $assignment->due_date,
            'learning_outcomes' => $assignment->learning_outcomes ?? [],
            'skills_learned' => $assignment->skills_learned ?? [],
            'prerequisites' => $assignment->prerequisites ?? [],
            'tools' => $assignment->tools ?? [],
            'evaluation_rubrics' => $assignment->evaluation_rubrics ?? [],
            'has_quiz' => (bool) $quiz,
            'quiz_completed' => $quizCompleted,
            'has_coding_exercise' => (bool) $codingExercise,
            'coding_exercise_completed' => $codingExerciseCompleted,
            // Mini Project tidak punya submission tersendiri — brief-nya cuma
            // dibaca sekali sebelum Pengumpulan, jadi statusnya "selesai" ikut
            // status pengumpulan tugas (submitted/successful), bukan flag terpisah.
            'has_mini_project' => (bool) $miniProject,
            'status' => $progress?->status ?? 'pending',
            'file_name' => $progress?->file_name,
            'file_url' => $progress?->file_path
                ? Storage::disk('public')->url($progress->file_path)
                : null,
        ]);
    }
}