<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Database\Factories\UserFactory;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        // --- field baru untuk PathSkill ---
        'education_background',
        'interest',
        'career_goal_id',
        'assessment_completed_at',
        'experience_checklist_submitted_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'assessment_completed_at' => 'datetime',
            'experience_checklist_submitted_at' => 'datetime',
        ];
    }

     public function careerGoal(): BelongsTo
    {
        return $this->belongsTo(Career::class, 'career_goal_id');
    }
 
    public function skillAssessments(): HasMany
    {
        return $this->hasMany(UserSkillAssessment::class);
    }

    /**
     * Override notifikasi bawaan Laravel. Default-nya mengarah ke route web
     * (mis. /password/reset/{token}) yang tidak ada di setup API-only ini —
     * frontend-nya terpisah di Next.js. Notifikasi custom di bawah membuat
     * link mengarah ke FRONTEND_URL/reset-password.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}