<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'response_id',
        'student_profile_id',
        'primary_category_id',
        'secondary_category_id',
        'tertiary_category_id',
        'top_recommendations',
        'dimension_scores',
        'normalized_scores',
        'scoring_config_snapshot',
    ];

    public function response(): BelongsTo
    {
        return $this->belongsTo(SurveyResponse::class, 'response_id');
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_profile_id');
    }

    public function primaryCategory(): BelongsTo
    {
        return $this->belongsTo(InterestCategory::class, 'primary_category_id');
    }

    public function secondaryCategory(): BelongsTo
    {
        return $this->belongsTo(InterestCategory::class, 'secondary_category_id');
    }

    public function tertiaryCategory(): BelongsTo
    {
        return $this->belongsTo(InterestCategory::class, 'tertiary_category_id');
    }

    public function getRecommendationsListAttribute(): array
    {
        return json_decode($this->top_recommendations, true) ?: [];
    }

    public function getDimensionsAttribute(): array
    {
        return json_decode($this->dimension_scores, true) ?: [];
    }

    public function getScoresAttribute(): array
    {
        return json_decode($this->normalized_scores, true) ?: [];
    }

    public function getWeightsSnapshotAttribute(): array
    {
        return json_decode($this->scoring_config_snapshot, true) ?: [];
    }
}
