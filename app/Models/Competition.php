<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Competition extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'organizer',
        'description',
        'interest_category_id',
        'level',
        'format',
        'location',
        'team_type',
        'min_team_members',
        'max_team_members',
        'fee_amount',
        'prize_summary',
        'official_url',
        'guidebook_url',
        'poster_path',
        'status',
        'registration_start',
        'registration_deadline',
        'event_start',
        'event_end',
        'created_by_id',
    ];

    protected $casts = [
        'fee_amount' => 'decimal:2',
        'min_team_members' => 'integer',
        'max_team_members' => 'integer',
        'registration_start' => 'datetime',
        'registration_deadline' => 'datetime',
        'event_start' => 'datetime',
        'event_end' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(InterestCategory::class, 'interest_category_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(CompetitionRegistration::class);
    }

    public function teams(): HasMany
    {
        return $this->hasMany(CompetitionTeam::class);
    }

    public function participations(): HasMany
    {
        return $this->hasMany(ParticipationRecord::class);
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(AchievementRecord::class);
    }

    public function isOpen(): bool
    {
        return $this->status === 'open' &&
            (!$this->registration_deadline || $this->registration_deadline->isFuture());
    }

    public function getLevelBadgeClassAttribute(): string
    {
        return match($this->level) {
            'internasional' => 'bg-purple-900/60 text-purple-300 border border-purple-500/40',
            'nasional' => 'bg-cyan-900/60 text-cyan-300 border border-cyan-500/40',
            'regional' => 'bg-blue-900/60 text-blue-300 border border-blue-500/40',
            default => 'bg-slate-800 text-slate-300 border border-slate-700',
        };
    }
}
