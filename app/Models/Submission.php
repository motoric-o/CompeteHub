<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Submission extends Model
{
    const CREATED_AT = 'submitted_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'competition_id', 'round_id', 'user_id', 'team_id',
        'file_path', 'file_type', 'file_size',
        'submitted_at', 'final_score', 'status',
        'revision_count', 'time_bonus',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at'   => 'datetime',
            'final_score'    => 'decimal:2',
            'time_bonus'     => 'decimal:2',
            'revision_count' => 'integer',
        ];
    }

    /**
     * Combined total: judge score (max 100) + time bonus (max 5).
     */
    public function getTotalScoreAttribute(): float
    {
        return ($this->final_score ?? 0) + ($this->time_bonus ?? 0);
    }

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function round(): BelongsTo
    {
        return $this->belongsTo(Round::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }
}
