<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParticipationRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_profile_id',
        'competition_id',
        'registration_id',
        'participation_date',
        'role_description',
        'certificate_path',
    ];

    protected $casts = [
        'participation_date' => 'date',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_profile_id');
    }

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(CompetitionRegistration::class, 'registration_id');
    }
}
