<?php

namespace App\Services;

use App\Models\SkillRecommendation;

/**
 * Helper kecil untuk invalidasi cache rekomendasi skill map.
 * Dipanggil setiap kali ada data yang mempengaruhi "Tingkat Saat Ini"
 * berubah (rating skill, confidence, atau hasil quiz) — supaya
 * SkillMapController tahu harus generate ulang lewat Groq, bukan
 * pakai rekomendasi lama yang sudah tidak relevan.
 */
class SkillRecommendationCacheService
{
    public function invalidate(int $userId, ?int $careerId): void
    {
        if (! $careerId) {
            return;
        }

        SkillRecommendation::where('user_id', $userId)
            ->where('career_id', $careerId)
            ->delete();
    }
}