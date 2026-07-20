<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LearningModule;
use App\Models\UserModuleProgress;
use App\Services\GroqService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class LearningPathController extends Controller
{
    public function __construct(private GroqService $groq)
    {
    }

    /**
     * POST /api/learning-path/recommend
     * Minta Groq merekomendasikan URUTAN modul yang sudah ada (manual/seeder)
     * berdasarkan skill gap user. TIDAK membuat modul/lesson/assignment baru.
     */
    public function recommend(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user->career_goal_id) {
            return response()->json(['message' => 'User belum memilih career goal.'], 422);
        }

        $career = $user->careerGoal;

        $modules = LearningModule::where('career_id', $career->id)->get(['id', 'title', 'description']);

        if ($modules->isEmpty()) {
            return response()->json([
                'message' => 'Modul untuk career ini belum tersedia. Hubungi admin untuk menambahkan modul.',
            ], 422);
        }

        $alreadyRecommended = UserModuleProgress::where('user_id', $user->id)
            ->whereIn('learning_module_id', $modules->pluck('id'))
            ->whereNotNull('recommended_order')
            ->exists();

        if ($alreadyRecommended) {
            return response()->json([
                'message' => 'Urutan learning path untuk kamu sudah pernah direkomendasikan.',
                'recommended' => false,
            ]);
        }

        $skillGaps = $career->skills()
            ->with(['assessments' => fn ($q) => $q->where('user_id', $user->id)])
            ->get()
            ->map(fn ($skill) => [
                'skill_name' => $skill->skill_name,
                'current' => $skill->assessments->first()?->rating ?? 0,
                'required' => $skill->industry_requirement,
            ])
            ->toArray();

        if (empty($skillGaps)) {
            return response()->json([
                'message' => 'Belum ada data skill assessment untuk career ini.',
            ], 422);
        }

        try {
            $result = $this->groq->recommendModuleOrder($career, $skillGaps, $modules->toArray());
        } catch (RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 502);
        }

        DB::transaction(function () use ($result, $user) {
            foreach ($result['order'] as $i => $item) {
                UserModuleProgress::updateOrCreate(
                    ['user_id' => $user->id, 'learning_module_id' => $item['module_id']],
                    ['recommended_order' => $i + 1, 'recommended_reason' => $item['reason']]
                );
            }
        });

        return response()->json([
            'message' => 'Urutan learning path berhasil direkomendasikan.',
            'recommended' => true,
        ], 201);
    }

    /**
     * GET /api/learning-path
     * List modul untuk career user + progress overview
     * (dipakai di halaman "Your Learning Path").
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user->career_goal_id) {
            return response()->json(['message' => 'User belum memilih career goal.'], 422);
        }

        $modules = LearningModule::where('career_id', $user->career_goal_id)
            ->with(['assignments', 'userProgress' => fn ($q) => $q->where('user_id', $user->id)])
            ->get()
            ->sortBy(fn ($m) => $m->userProgress->first()?->recommended_order ?? $m->order)
            ->values();

        $totalModules = $modules->count();
        $completedModules = $modules->filter(
            fn ($m) => $m->userProgress->first()?->status === 'completed'
        )->count();

        // estimasi total durasi program dari rentang due_date assignment yang sesungguhnya
        // (bukan asumsi flat 2 minggu/modul) — +1 minggu untuk mewakili waktu kerja
        // sebelum deadline assignment pertama jatuh tempo.
        $dueDates = $modules->flatMap(fn ($m) => $m->assignments->pluck('due_date'))->filter();
        $estimatedDurationWeeks = $dueDates->isNotEmpty()
            ? (int) ceil($dueDates->min()->diffInDays($dueDates->max()) / 7) + 1
            : $totalModules * 2; // fallback kalau belum ada assignment sama sekali

        return response()->json([
            'overall_progress' => [
                'completed_modules' => $completedModules,
                'total_modules' => $totalModules,
            ],
            'total_lessons' => $modules->sum('total_lessons'),
            'total_assignments' => $modules->sum('total_assignments'),
            'estimated_duration_weeks' => $estimatedDurationWeeks,
            'modules' => $modules->map(fn ($m) => [
                'id' => $m->id,
                'title' => $m->title,
                'total_lessons' => $m->total_lessons,
                'total_assignments' => $m->total_assignments,
                'ai_generated' => $m->ai_generated,
                'status' => $m->userProgress->first()?->status ?? 'not_started',
                'percentage' => $m->userProgress->first()?->percentage ?? 0,
            ]),
        ]);
    }

    /**
     * GET /api/learning-path/{module}
     * Detail 1 modul: lessons + assignments + status progress user
     * (dipakai di halaman "Advanced React Patterns" detail).
     */
    public function show(Request $request, LearningModule $module): JsonResponse
{
    dd($request->user());

    $user = $request->user();
        $module->load([
            'lessons.userProgress' => fn ($q) => $q->where('user_id', $user->id),
            'assignments.userProgress' => fn ($q) => $q->where('user_id', $user->id),
            'userProgress' => fn ($q) => $q->where('user_id', $user->id),
        ]);

        return response()->json([
            'id' => $module->id,
            'title' => $module->title,
            'description' => $module->description,
            'progress_percentage' => $module->userProgress->first()?->percentage ?? 0,
            'lessons' => $module->lessons->map(fn ($l) => [
                'id' => $l->id,
                'title' => $l->title,
                'type' => $l->type,
                'duration_minutes' => $l->duration_minutes,
                'explanation' => $l->explanation,
                'example' => $l->example,
                'function_context' => $l->function_context,
                'completed' => $l->userProgress->first()?->completed ?? false,
            ]),
            'assignments' => $module->assignments->map(fn ($a) => [
                'id' => $a->id,
                'title' => $a->title,
                'description' => $a->description,
                'due_date' => $a->due_date,
                'status' => $a->userProgress->first()?->status ?? 'pending',
                'file_name' => $a->userProgress->first()?->file_name,
                'file_url' => $a->userProgress->first()?->file_path
                    ? \Illuminate\Support\Facades\Storage::disk('public')->url($a->userProgress->first()->file_path)
                    : null,
            ]),
        ]);
    }
}