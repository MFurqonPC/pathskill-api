<?php

namespace App\Services;

use App\Models\LearningModule;
use App\Models\User;
use App\Models\UserAssignmentProgress;
use App\Models\UserLessonProgress;
use App\Models\UserModuleProgress;

class ModuleProgressService
{
    /**
     * Hitung ulang percentage & status modul untuk 1 user, berdasarkan
     * gabungan lesson yang completed + assignment yang successful,
     * dibagi (total_lessons + total_assignments) milik modul.
     *
     * SATU-SATUNYA tempat logic ini boleh ada — dipanggil dari
     * ProgressController::completeLesson() dan
     * AssignmentReviewController::store(), supaya keduanya tidak
     * pernah drift satu sama lain.
     */
    public function recalculate(User $user, LearningModule $module): UserModuleProgress
    {
        $completedLessons = UserLessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $module->lessons()->pluck('id'))
            ->where('completed', true)
            ->count();

        $successfulAssignments = UserAssignmentProgress::where('user_id', $user->id)
            ->whereIn('assignment_id', $module->assignments()->pluck('id'))
            ->where('status', 'successful')
            ->count();

        $totalItems = $module->total_lessons + $module->total_assignments;

        $percentage = $totalItems > 0
            ? (int) round((($completedLessons + $successfulAssignments) / $totalItems) * 100)
            : 0;

        return UserModuleProgress::updateOrCreate(
            ['user_id' => $user->id, 'learning_module_id' => $module->id],
            [
                'percentage' => $percentage,
                'status' => $percentage >= 100 ? 'completed' : ($percentage > 0 ? 'in_progress' : 'not_started'),
            ]
        );
    }
}