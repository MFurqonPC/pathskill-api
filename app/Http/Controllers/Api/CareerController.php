<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Career;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    /**
     * GET /api/careers
     * List semua career, dipakai di halaman "Pilih Karier Anda Tujuan".
     */
    public function index(): JsonResponse
    {
        $careers = Career::query()
            ->orderBy('order')
            ->get(['id', 'name', 'icon', 'description', 'order']);

        return response()->json(['data' => $careers]);
    }

    /**
     * POST /api/careers/{career}/select
     * Set career_goal_id user, dipanggil setelah user pilih career
     * di halaman "Ceritakan Tentang Diri Anda".
     */
    public function select(Request $request, Career $career): JsonResponse
    {
        $validated = $request->validate([
            'education_background' => ['nullable', 'string', 'max:255'],
            'interest' => ['nullable', 'string', 'max:1000'],
        ]);

        $request->user()->update([
            'career_goal_id' => $career->id,
            'education_background' => $validated['education_background'] ?? null,
            'interest' => $validated['interest'] ?? null,
            'profile_setup_completed_at' => now(), // ← tambahan
        ]);

        return response()->json([
            'message' => 'Career goal berhasil disimpan',
            'career' => $career->only(['id', 'name', 'icon']),
        ]);
    }

    /**
     * GET /api/careers/{career}/skills
     * Detail skill per career untuk halaman Skill Assessment,
     * dikelompokkan per kategori: core, tools, soft_skills.
     * Kalau user sudah pernah rating, sertakan rating terakhirnya (null = "Not rated").
     */
    public function skills(Request $request, Career $career): JsonResponse
    {
        $userId = $request->user()->id;

        $skills = $career->skills()
            ->with(['assessments' => function ($q) use ($userId) {
                $q->where('user_id', $userId);
            }])
            ->get()
            ->map(function ($skill) {
                return [
                    'id' => $skill->id,
                    'skill_name' => $skill->skill_name,
                    'category' => $skill->category,
                    'industry_requirement' => $skill->industry_requirement,
                    'order' => $skill->order,
                    'current_rating' => $skill->assessments->first()?->rating,
                ];
            })
            ->groupBy('category');

        return response()->json([
            'career' => $career->only(['id', 'name', 'icon', 'description']),
            'skills' => [
                'core' => $skills->get('core', collect())->values(),
                'tools' => $skills->get('tools', collect())->values(),
                'soft_skills' => $skills->get('soft_skills', collect())->values(),
            ],
        ]);
    }
}
