<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Competition extends Model
{
    protected $fillable = [
        'user_id', 'name', 'description', 'type', 'scoring_type',
        'registration_fee', 'quota', 'banner_url',
        'start_date', 'end_date', 'registration_start', 'registration_end', 'status',
    ];

    protected function casts(): array
    {
        return [
            'registration_fee' => 'decimal:2',
            'start_date'       => 'date',
            'end_date'         => 'date',
            'registration_start' => 'datetime',
            'registration_end'   => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rounds(): HasMany
    {
        return $this->hasMany(Round::class);
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function formTemplates(): HasMany
    {
        return $this->hasMany(FormTemplate::class);
    }

    public function juryAssignments(): HasMany
    {
        return $this->hasMany(JuryAssignment::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function leaderboardEntries(): HasMany
    {
        return $this->hasMany(LeaderboardEntry::class);
    }
}
