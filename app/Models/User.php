<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
        'role',
        'status',
        'avatar_url',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ── Relationships ──────────────────────────────────────

    /**
     * Tim-tim yang dikaptenin oleh user ini.
     */
    public function captainedTeams(): HasMany
    {
        return $this->hasMany(Team::class, 'user_id');
    }

    /**
     * Tim-tim yang diikuti user ini (sebagai anggota).
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_members')
                    ->withPivot('joined_at');
    }

    /**
     * Kompetisi yang dibuat user ini (sebagai panitia).
     */
    public function competitions(): HasMany
    {
        return $this->hasMany(Competition::class, 'user_id');
    }

    // ── Role Helpers ───────────────────────────────────────

    public function isCommittee(): bool
    {
        return $this->role === 'committee';
    }

    public function isJudge(): bool
    {
        return $this->role === 'judge';
    }

    public function isParticipant(): bool
    {
        return $this->role === 'participant';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
