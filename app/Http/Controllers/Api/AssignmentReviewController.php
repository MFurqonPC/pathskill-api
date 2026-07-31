<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\UserAssignmentProgress;
use App\Services\ModuleProgressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AssignmentReviewController extends Controller
{
    public function __construct(private ModuleProgressService $moduleProgress) {}

    /**
     * GET /api/assignments/{assignment}/review
     */
    public function show(Request $request, Assignment $assignment): JsonResponse
    {
        $user = $request->user();

        if ($user->role === 'mentor') {
            $validated = $request->validate([
                'user_id' => ['required', 'exists:users,id'],
            ]);

            $progress = UserAssignmentProgress::where('assignment_id', $assignment->id)
                ->where('user_id', $validated['user_id'])
                ->whereNotNull('submitted_at')
                ->with('reviewer:id,name')
                ->first();
        } else {
            $progress = UserAssignmentProgress::where('user_id', $user->id)
                ->where('assignment_id', $assignment->id)
                ->with('reviewer:id,name')
                ->first();
        }

        if (! $progress) {
            return response()->json([
                'message' => 'Belum ada submission untuk assignment ini.',
            ], 404);
        }

        return response()->json([
            'assignment' => [
                'id' => $assignment->id,
                'title' => $assignment->title,
                'skills' => $assignment->careerSkills()->get([
                    'career_skills.id',
                    'career_skills.skill_name as name',
                ]),
            ],
            'progress' => [
                'status' => $progress->status,
                'file_name' => $progress->file_name,
                'file_path' => $progress->file_path,
                'submitted_at' => $progress->submitted_at,
            ],
            'review' => $progress->reviewed_at ? [
                'mentor_score' => $progress->mentor_score,
                'mentor_feedback' => $progress->mentor_feedback,
                'reviewed_at' => $progress->reviewed_at,
                'reviewed_by' => $progress->reviewer ? [
                    'id' => $progress->reviewer->id,
                    'name' => $progress->reviewer->name,
                ] : null,
            ] : null,
        ]);
    }

    /**
     * POST /api/assignments/{assignment}/review
     * Middleware: role:mentor
     */
    public function store(Request $request, Assignment $assignment): JsonResponse
    {
        $validated = $request->validate([
            'user_id'         => ['required', 'exists:users,id'],
            'mentor_score'    => ['required', 'integer', 'min:0', 'max:100'],
            'mentor_feedback' => ['nullable', 'string', 'max:5000'],
            'status'          => ['required', 'in:successful,needs_revision'],
        ]);

        $progress = UserAssignmentProgress::where('assignment_id', $assignment->id)
            ->where('user_id', $validated['user_id'])
            ->firstOrFail();

        $progress->update([
            'status'          => $validated['status'],
            'mentor_score'    => $validated['mentor_score'],
            'mentor_feedback' => $validated['mentor_feedback'] ?? null,
            'reviewed_at'     => now(),
            'reviewed_by'     => $request->user()->id,
        ]);

        if ($validated['status'] === 'successful') {
            $this->moduleProgress->recalculate(
                $progress->user_id,
                $assignment->learning_module_id
            );
        }

        Cache::forget("skill-map:user:{$progress->user_id}");

        return response()->json([
            'message' => 'Review tersimpan',
            'data' => $progress,
        ]);
    }
}