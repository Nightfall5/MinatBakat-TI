<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'nim',
        'angkatan',
        'email',
        'phone',
        'instagram_handle',
        'portfolio_url',
        'github_url',
        'bio',
        'is_verified',
        'consent_data_processing',
        'consent_achievement_public',
        'created_by_admin_id',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'consent_data_processing' => 'boolean',
        'consent_achievement_public' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skills(): HasMany
    {
        return $this->hasMany(StudentSkill::class);
    }

    public function interests(): HasMany
    {
        return $this->hasMany(StudentInterest::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(CompetitionRegistration::class);
    }

    public function participations(): HasMany
    {
        return $this->hasMany(ParticipationRecord::class);
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(AchievementRecord::class);
    }

    public function surveyResponses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class);
    }
}
