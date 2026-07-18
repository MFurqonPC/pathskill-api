<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCodingSubmission extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'coding_exercise_id', 'source_code', 'submitted_at'];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function codingExercise(): BelongsTo
    {
        return $this->belongsTo(CodingExercise::class);
    }
}
