<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompetitionTeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'competition_id',
        'team_name',
        'leader_student_id',
    ];

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function leader(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'leader_student_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(CompetitionTeamMember::class, 'team_id');
    }
}
