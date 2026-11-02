<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SurveyResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'user_id',
        'student_profile_id',
        'respondent_name',
        'respondent_nim',
        'respondent_angkatan',
        'respondent_email',
        'access_token',
        'consent_agreed',
        'is_verified',
        'ip_hash',
    ];

    protected $casts = [
        'consent_agreed' => 'boolean',
        'is_verified' => 'boolean',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(SurveyCampaign::class, 'campaign_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_profile_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(SurveyAnswer::class, 'response_id');
    }

    public function result(): HasOne
    {
        return $this->hasOne(AssessmentResult::class, 'response_id');
    }
}
