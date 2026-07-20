<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\CodingExercise;
use App\Models\UserCodingSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CodingExerciseController extends Controller
{
    /**
     * GET /assignments/{assignment}/coding-exercise
     * Ambil challenge coding milik sebuah assignment, beserta submission
     * user (kalau sudah pernah submit) supaya kode terakhir bisa dilanjutkan.
     */
    public function show(Request $request, Assignment $assignment): JsonResponse
    {
        $user = $request->user();

        $exercise = $assignment->codingExercise;

        if (! $exercise) {
            return response()->json(['message' => 'Coding exercise belum tersedia untuk assignment ini.'], 404);
        }

        $submission = UserCodingSubmission::where('user_id', $user->id)
            ->where('coding_exercise_id', $exercise->id)
            ->first();

        return response()->json([
            'id' => $exercise->id,
            'assignment_id' => $exercise->assignment_id,
            'title' => $exercise->title,
            'description' => $exercise->description,
            'learning_objectives' => $exercise->learning_objectives ?? [],
            'requirements' => $exercise->requirements ?? [],
            'test_cases' => $exercise->test_cases ?? [],
            'language' => $exercise->language,
            'starter_code' => $exercise->starter_code,
            'hint' => $exercise->hint,
            'submitted_source_code' => $submission?->source_code,
            'submitted_at' => $submission?->submitted_at,
        ]);
    }

    /**
     * POST /coding-exercises/{codingExercise}/submit
     * Body: { source_code: string }
     * Cuma nyimpen source code buat direview mentor — belum ada compile,
     * run, execution engine, ataupun auto grading (di luar scope PathSkill
     * sebagai platform AI Learning Path, bukan coding assessment).
     */
    public function submit(Request $request, CodingExercise $codingExercise): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'source_code' => ['required', 'string'],
        ], [
            'source_code.required' => 'Kode belum ditulis.',
        ]);

        $submission = UserCodingSubmission::updateOrCreate(
            ['user_id' => $user->id, 'coding_exercise_id' => $codingExercise->id],
            ['source_code' => $validated['source_code'], 'submitted_at' => now()]
        );

        return response()->json([
            'submitted_at' => $submission->submitted_at,
        ]);
    }
}
