<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CodingExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'title',
        'description',
        'learning_objectives',
        'requirements',
        'test_cases',
        'language',
        'starter_code',
        'hint',
    ];

    protected $casts = [
        'learning_objectives' => 'array',
        'requirements' => 'array',
        'test_cases' => 'array',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function userSubmissions(): HasMany
    {
        return $this->hasMany(UserCodingSubmission::class);
    }
}
