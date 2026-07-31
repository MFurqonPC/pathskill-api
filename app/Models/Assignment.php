<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'learning_module_id', 'title', 'description', 'due_date', 'order',
        'learning_outcomes', 'skills_learned', 'prerequisites', 'tools', 'evaluation_rubrics',
    ];

    protected $casts = [
        'due_date' => 'date',
        'learning_outcomes' => 'array',
        'skills_learned' => 'array',
        'prerequisites' => 'array',
        'tools' => 'array',
        'evaluation_rubrics' => 'array',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(LearningModule::class, 'learning_module_id');
    }

    public function userProgress(): HasMany
    {
        return $this->hasMany(UserAssignmentProgress::class);
    }

    public function quiz(): HasOne
    {
        return $this->hasOne(Quiz::class);
    }

    public function codingExercise(): HasOne
    {
        return $this->hasOne(CodingExercise::class);
    }

    public function miniProject(): HasOne
    {
        return $this->hasOne(MiniProject::class);
    }

    // Skill spesifik yang dilatih assignment ini — dipakai untuk update
    // Skill Map saat assignment direview sukses oleh mentor.
    public function careerSkills(): BelongsToMany
    {
        return $this->belongsToMany(CareerSkill::class, 'assignment_career_skill');
    }
}