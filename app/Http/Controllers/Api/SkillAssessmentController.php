<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSkillAssessmentRequest;
use App\Models\CareerSkill;
use App\Models\UserSkillAssessment;
use App\Services\SkillRecommendationCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SkillAssessmentController extends Controller
{
    /**
     * POST /api/skill-assessments
     * Submit rating untuk satu atau lebih skill sekaligus (bulk upsert).
     * Kalau career_skill_id sudah pernah dinilai user ini, rating akan di-update.
     */
    public function store(StoreSkillAssessmentRequest $request): JsonResponse
    {
        $userId = $request->user()->id;
        $ratings = $request->validated('ratings');

        DB::transaction(function () use ($userId, $ratings) {
            foreach ($ratings as $item) {
                UserSkillAssessment::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'career_skill_id' => $item['career_skill_id'],
                    ],
                    [
                        'rating' => $item['rating'],
                    ]
                );
            }
        });

        // rating berubah → rekomendasi skill map yang di-cache sudah tidak relevan
        app(SkillRecommendationCacheService::class)
            ->invalidate($userId, $request->user()->career_goal_id);

        return response()->json([
            'message' => 'Skill assessment berhasil disimpan.',
            'total_saved' => count($ratings),
        ]);
    }

    /**
     * GET /api/skill-assessments/{career}
     * Lihat kembali rating yang sudah diisi user untuk career tertentu.
     * Berguna untuk halaman assessment yang di-reload / dilanjutkan.
     */
    public function show(\Illuminate\Http\Request $request, int $careerId): JsonResponse
    {
        $userId = $request->user()->id;

        $ratings = CareerSkill::where('career_id', $careerId)
            ->with(['assessments' => fn ($q) => $q->where('user_id', $userId)])
            ->get()
            ->map(fn ($skill) => [
                'career_skill_id' => $skill->id,
                'skill_name' => $skill->skill_name,
                'rating' => $skill->assessments->first()?->rating,
            ]);

        return response()->json(['data' => $ratings]);
    }
}