<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Score extends Model
{
    const CREATED_AT = 'scored_at';

    protected $fillable = [
        'submission_id',
        'user_id',
        'score',
        'notes',
        'scored_at',
        'updated_at'
    ];

    protected function casts(): array
    {
        return [
            'score'     => 'decimal:2',
            'scored_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }

    public function user(): BelongsTo // jury
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function judge(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function criterionScores(): HasMany
    {
        return $this->hasMany(CriterionScore::class, 'score_id');
    }
}
