<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitionTeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'student_profile_id',
        'role_in_team',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(CompetitionTeam::class, 'team_id');
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_profile_id');
    }
}
