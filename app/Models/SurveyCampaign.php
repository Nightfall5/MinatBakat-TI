<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveyCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'academic_year',
        'is_active',
        'start_date',
        'end_date',
        'require_login',
        'qr_code_token',
        'created_by_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'require_login' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(SurveyQuestion::class, 'campaign_id')->orderBy('order_index');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class, 'campaign_id');
    }

    public function isActiveNow(): bool
    {
        if (!$this->is_active) return false;
        $now = now();
        if ($this->start_date && $this->start_date->isFuture()) return false;
        if ($this->end_date && $this->end_date->isPast()) return false;
        return true;
    }
}
