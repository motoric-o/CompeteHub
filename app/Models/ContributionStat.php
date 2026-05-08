<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContributionStat extends Model
{
    public $timestamps = false;

    protected $table = 'contribution_stats';

    protected $fillable = [
        'team_id', 'user_id', 'competition_id',
        'submission_count', 'avg_score', 'contribution_pct', 'last_updated',
    ];

    protected function casts(): array
    {
        return [
            'avg_score'        => 'decimal:2',
            'contribution_pct' => 'decimal:2',
            'last_updated'     => 'datetime',
        ];
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }
}
