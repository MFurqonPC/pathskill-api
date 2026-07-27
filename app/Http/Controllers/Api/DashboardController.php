<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LearningModule;
use App\Models\UserAssignmentProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * GET /api/dashboard
     * Ringkasan untuk halaman "Hi, {name}!" dashboard.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user->career_goal_id) {
            // `reason` dipakai frontend untuk menentukan CTA (Lengkapi Profil)
            // tanpa mencocokkan teks `message`.
            return response()->json([
                'message' => 'User belum memilih career goal.',
                'reason' => 'no_career_goal',
            ], 422);
        }

        // Gate: assessment 3-tahap (rating + checklist + quiz) harus lunas
        // dulu. Pakai `assessment_completed_at` sebagai satu-satunya sumber
        // kebenaran (di-set oleh SelfAssessmentController::quizResult() saat
        // ketiga tahap lengkap), konsisten dengan SkillMapController &
        // LearningPathController.
        if (! $user->assessment_completed_at) {
            return response()->json([
                'message' => 'Selesaikan Skill Assessment terlebih dahulu.',
                'reason' => 'not_assessed',
                'career_goal_id' => $user->career_goal_id,
            ], 422);
        }

        $modules = LearningModule::where('career_id', $user->career_goal_id)
            ->orderBy('order')
            ->with(['assignments', 'userProgress' => fn ($q) => $q->where('user_id', $user->id)])
            ->get();

        $totalModules = $modules->count();
        $completedModules = $modules->filter(
            fn ($m) => $m->userProgress->first()?->status === 'completed'
        )->count();

        $overallProgress = $totalModules > 0
            ? round($modules->avg(fn ($m) => $m->userProgress->first()?->percentage ?? 0))
            : 0;

        // semua assignment di modul-modul career ini
        $assignmentIds = $modules->flatMap(fn ($m) => $m->assignments->pluck('id'));

        $userAssignmentStatuses = UserAssignmentProgress::where('user_id', $user->id)
            ->whereIn('assignment_id', $assignmentIds)
            ->get()
            ->keyBy('assignment_id');

        $pendingAssignments = 0;
        $allAssignments = [];

        foreach ($modules as $module) {
            foreach ($module->assignments as $assignment) {
                $status = $userAssignmentStatuses[$assignment->id]->status ?? 'pending';
                if ($status !== 'successful') {
                    $pendingAssignments++;
                }
                $allAssignments[] = [
                    'id' => $assignment->id,
                    // Dibutuhkan frontend untuk membentuk link
                    // /learning-path/{module_id}/assignments/{id} di kartu
                    // "Tugas yang Perlu Diselesaikan" pada dashboard.
                    'module_id' => $module->id,
                    'title' => $assignment->title,
                    'module_title' => $module->title,
                    'due_date' => $assignment->due_date,
                    'status' => $status,
                ];
            }
        }

        // urutkan berdasarkan due_date terdekat, ambil 5 teratas untuk ditampilkan di dashboard
        $assignmentList = $allAssignments;
        usort($assignmentList, fn ($a, $b) => strcmp((string) $a['due_date'], (string) $b['due_date']));
        $assignmentList = array_slice($assignmentList, 0, 5);

        // weeks_remaining dihitung dari due_date assignment yang BELUM selesai paling akhir
        // (bukan asumsi flat per modul) — jadi akurat mengikuti jadwal yang benar-benar
        // tersimpan di database (baik dari seeder maupun hasil generate AI).
        $latestPendingDueDate = collect($allAssignments)
            ->filter(fn ($a) => $a['status'] !== 'successful' && $a['due_date'])
            ->map(fn ($a) => \Illuminate\Support\Carbon::parse($a['due_date']))
            ->max();

        $weeksRemaining = $latestPendingDueDate
            ? max((int) ceil(now()->diffInDays($latestPendingDueDate, false) / 7), 0)
            : 0;

        $activeModules = $modules->take(3)->map(fn ($m) => [
            'id' => $m->id,
            'title' => $m->title,
            'total_lessons' => $m->total_lessons,
            'total_assignments' => $m->total_assignments,
            'ai_generated' => $m->ai_generated,
        ]);

        return response()->json([
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'education_background' => $user->education_background,
                'career_goal' => $user->careerGoal?->name,
            ],
            'summary' => [
                'overall_progress' => $overallProgress, // persen 0-100
                'completed_modules' => $completedModules,
                'total_modules' => $totalModules,
                'pending_assignments' => $pendingAssignments,
                'weeks_remaining' => $weeksRemaining,
            ],
            'active_learning_path' => [
                'modules_completed' => $completedModules,
                'total_modules' => $totalModules,
                'progress_percentage' => $overallProgress,
                'modules' => $activeModules,
            ],
            'assignments_to_complete' => $assignmentList,
        ]);
    }
}