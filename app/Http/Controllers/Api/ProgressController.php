<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Lesson;
use App\Models\UserAssignmentProgress;
use App\Models\UserLessonProgress;
use App\Services\ModuleProgressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgressController extends Controller
{
    public function __construct(private ModuleProgressService $moduleProgress) {}

    /**
     * POST /api/lessons/{lesson}/complete
     * Persentase modul sekarang dihitung via ModuleProgressService
     * (gabungan lesson + assignment successful), bukan lesson-only lagi.
     */
    public function completeLesson(Request $request, Lesson $lesson): JsonResponse
    {
        $user = $request->user();
        $module = $lesson->module;

        UserLessonProgress::updateOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lesson->id],
            ['completed' => true, 'completed_at' => now()]
        );

        $progress = $this->moduleProgress->recalculate($user, $module);

        $completedLessons = UserLessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $module->lessons()->pluck('id'))
            ->where('completed', true)
            ->count();

        return response()->json([
            'lesson_completed' => true,
            'module_percentage' => $progress->percentage,
            'module_status' => $progress->status,
            'lessons_completed' => $completedLessons,
            'total_lessons' => $module->total_lessons,
        ]);
    }

    /**
     * POST /api/assignments/{assignment}/submit
     * Tidak diubah — status masih 'submitted', BELUM ikut hitungan
     * module percentage (baru dihitung setelah mentor review jadi
     * 'successful', lihat AssignmentReviewController::store()).
     */
    public function submitAssignment(Request $request, Assignment $assignment): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'file' => ['required', 'file', 'max:10240', 'mimes:pdf,doc,docx,zip,jpg,jpeg,png'],
        ], [
            'file.required' => 'File tugas wajib diupload.',
            'file.max' => 'Ukuran file maksimal 10MB.',
            'file.mimes' => 'Format file harus pdf, doc, docx, zip, jpg, jpeg, atau png.',
        ]);

        $existing = UserAssignmentProgress::where('user_id', $user->id)
            ->where('assignment_id', $assignment->id)
            ->first();
        if ($existing?->file_path) {
            Storage::disk('public')->delete($existing->file_path);
        }

        $path = $validated['file']->store('assignments/' . $user->id, 'public');

        $progress = UserAssignmentProgress::updateOrCreate(
            ['user_id' => $user->id, 'assignment_id' => $assignment->id],
            [
                'status' => 'submitted',
                'file_path' => $path,
                'file_name' => $validated['file']->getClientOriginalName(),
                'submitted_at' => now(),
            ]
        );

        return response()->json([
            'status' => $progress->status,
            'file_name' => $progress->file_name,
            'file_url' => Storage::disk('public')->url($path),
        ]);
    }
}