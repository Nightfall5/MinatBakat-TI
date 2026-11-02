<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InterestCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'common_skills',
        'starter_projects',
        'order_index',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order_index' => 'integer',
    ];

    public function competitions(): HasMany
    {
        return $this->hasMany(Competition::class);
    }

    public function studentInterests(): HasMany
    {
        return $this->hasMany(StudentInterest::class);
    }

    public function getSkillsArrayAttribute(): array
    {
        if (empty($this->common_skills)) return [];
        $decoded = json_decode($this->common_skills, true);
        if (is_array($decoded)) return $decoded;
        return array_map('trim', explode(',', $this->common_skills));
    }
}
