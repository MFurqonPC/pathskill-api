<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificationQuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'career_id', 'skill_id', 'question_text', 'code_snippet', 'options',
        'correct_option_index', 'explanation', 'is_warmup', 'order',
    ];

    protected $casts = [
        'options' => 'array',
        'is_warmup' => 'boolean',
        'correct_option_index' => 'integer',
    ];

    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class);
    }

    /**
     * Skill spesifik yang divalidasi soal ini. Nullable — soal lama/umum
     * yang belum ditag dianggap career-wide, tidak mengoreksi radar chart
     * skill manapun secara spesifik.
     */
    public function skill(): BelongsTo
    {
        return $this->belongsTo(CareerSkill::class, 'skill_id');
    }

    public function toSafeArray(): array
    {
        return [
            'id' => $this->id,
            'question_text' => $this->question_text,
            'code_snippet' => $this->code_snippet,
            'options' => $this->options,
        ];
    }
}