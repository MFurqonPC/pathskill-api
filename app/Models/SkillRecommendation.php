<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkillRecommendation extends Model
{
    protected $fillable = [
        'user_id',
        'career_id',
        'foundation_summary',
        'priority_areas',
        'priority_skill_names',
        'estimated_weeks',
        'generated_at',
    ];

    protected $casts = [
        'priority_skill_names' => 'array',
        'generated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class);
    }
}