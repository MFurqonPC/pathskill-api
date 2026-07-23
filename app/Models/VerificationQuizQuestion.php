<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificationQuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'career_id', 'question_text', 'code_snippet', 'options',
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
     * Representasi soal TANPA jawaban benar — WAJIB dipakai untuk
     * response API yang dikirim ke client sebelum user menjawab,
     * supaya kunci jawaban nggak bocor lewat Network tab browser.
     */
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
