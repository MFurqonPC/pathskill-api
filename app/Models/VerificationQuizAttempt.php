<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificationQuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'career_id', 'is_warmup', 'status',
        'tab_switch_count', 'started_at', 'completed_at', 'score_percentage',
    ];

    protected $casts = [
        'is_warmup' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}