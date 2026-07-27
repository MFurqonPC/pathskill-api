<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LearningModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'career_id',
        'title',
        'description',
        'order',
        'total_lessons',
        'total_assignments',
        'ai_generated',
        'learning_objectives'
    ];

    protected $casts = [
        'ai_generated' => 'boolean',
        'learning_objectives' => 'array',
    ];

    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class)->orderBy('order');
    }

    public function userProgress(): HasMany
    {
        return $this->hasMany(UserModuleProgress::class);
    }
}
