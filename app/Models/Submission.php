<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Submission extends Model
{
    protected $fillable = [
        'competition_id', 'round_id', 'user_id', 'team_id',
        'file_path', 'file_type', 'file_size',
        'submitted_at', 'final_score', 'status',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'final_score'  => 'decimal:2',
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

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }
}
