<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
    'learning_module_id',
    'title',
    'type',
    'duration_minutes',
    'order',
    'explanation',
    'example',
    'function_context',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(LearningModule::class, 'learning_module_id');
    }

    public function userProgress(): HasMany
    {
        return $this->hasMany(UserLessonProgress::class);
    }
}