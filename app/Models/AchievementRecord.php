<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AchievementRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_profile_id',
        'competition_id',
        'team_id',
        'achievement_title',
        'rank_position',
        'level',
        'verification_status',
        'verified_by_id',
        'verified_at',
        'proof_document_path',
        'official_publication_url',
        'is_public_published',
        'notes',
    ];

    protected $casts = [
        'rank_position' => 'integer',
        'is_public_published' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_profile_id');
    }

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(CompetitionTeam::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_id');
    }

    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->verification_status) {
            'verified' => 'bg-emerald-900/60 text-emerald-300 border border-emerald-500/40',
            'rejected' => 'bg-rose-900/60 text-rose-300 border border-rose-500/40',
            default => 'bg-amber-900/60 text-amber-300 border border-amber-500/40',
        };
    }
}
