<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Round extends Model
{
    protected $fillable = [
        'competition_id',
        'scoring_type_id',
        'name',
        'round_order',
        'start_date',
        'end_date',
        'status',
        'is_bracket'
    ];

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function scoringType(): BelongsTo
    {
        return $this->belongsTo(ScoringType::class);
    }

    public function brackets()
    {
        return $this->hasMany(Bracket::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function quizQuestions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function scoringCriteria()
    {
        return $this->hasMany(ScoringCriterion::class);
    }

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date'   => 'datetime',
            'is_bracket' => 'boolean',
        ];
    }
}
