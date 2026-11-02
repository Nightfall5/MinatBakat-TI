<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'option_text',
        'category_weights',
        'target_branch',
        'order_index',
    ];

    protected $casts = [
        'order_index' => 'integer',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestion::class, 'question_id');
    }

    public function getWeightsArrayAttribute(): array
    {
        if (empty($this->category_weights)) return [];
        $decoded = json_decode($this->category_weights, true);
        return is_array($decoded) ? $decoded : [];
    }
}
