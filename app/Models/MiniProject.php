<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MiniProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'title',
        'brief',
        'objectives',
        'acceptance_criteria',
        'deliverables',
    ];

    protected $casts = [
        'objectives' => 'array',
        'acceptance_criteria' => 'array',
        'deliverables' => 'array',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }
}
