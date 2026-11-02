<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitionRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'competition_id',
        'student_profile_id',
        'user_id',
        'team_id',
        'registration_source',
        'registered_by_staff_id',
        'status',
        'admin_notes',
        'confirmed_at',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
    ];

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_profile_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(CompetitionTeam::class);
    }

    public function staffRegistrar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by_staff_id');
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'confirmed' => 'bg-emerald-900/60 text-emerald-300 border border-emerald-500/40',
            'competing' => 'bg-cyan-900/60 text-cyan-300 border border-cyan-500/40',
            'completed' => 'bg-blue-900/60 text-blue-300 border border-blue-500/40',
            'under_review' => 'bg-amber-900/60 text-amber-300 border border-amber-500/40',
            'rejected' => 'bg-rose-900/60 text-rose-300 border border-rose-500/40',
            'withdrawn' => 'bg-slate-800 text-slate-400 border border-slate-700',
            default => 'bg-purple-900/60 text-purple-300 border border-purple-500/40',
        };
    }
}
