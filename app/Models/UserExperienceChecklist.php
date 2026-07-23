<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserExperienceChecklist extends Model
{
    use HasFactory;

    protected $table = 'user_experience_checklist';

    protected $fillable = ['user_id', 'experience_checklist_item_id', 'checked'];

    protected $casts = ['checked' => 'boolean'];
}
