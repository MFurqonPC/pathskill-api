<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuizAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'verification_quiz_question_id', 'selected_option_index', 'is_correct'];

    protected $casts = ['is_correct' => 'boolean'];
}
