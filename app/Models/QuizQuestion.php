<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizQuestion extends Model
{
    protected $fillable = [
        'round_id',
        'question_text',
        'question_type',
        'options',
        'correct_answer',
        'points',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'points'  => 'integer',
        ];
    }

    public function round(): BelongsTo
    {
        return $this->belongsTo(Round::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class, 'question_id');
    }
}
