<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVerificationAnswer extends Model
{
    use HasFactory;

    protected $table = 'user_verification_answers';

    protected $fillable = ['user_id', 'verification_quiz_question_id', 'selected_option_index', 'is_correct'];

    protected $casts = ['is_correct' => 'boolean'];
}
