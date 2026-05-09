<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaderboardEntry extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'competition_id', 'round_id', 'user_id', 'team_id',
        'total_score', 'rank', 'last_updated',
    ];

    protected function casts(): array
    {
        return [
            'total_score'  => 'decimal:2',
            'last_updated' => 'datetime',
        ];
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
}
