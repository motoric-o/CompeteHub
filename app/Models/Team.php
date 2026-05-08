<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Team extends Model
{
    protected $fillable = [
        'competition_id',
        'user_id',
        'name',
        'invite_code',
        'logo_url',
    ];

    // ── Boot ───────────────────────────────────────────────

    protected static function booted(): void
    {
        // Auto-generate invite_code saat membuat tim baru
        static::creating(function (Team $team) {
            if (empty($team->invite_code)) {
                $team->invite_code = self::generateUniqueInviteCode();
            }
        });
    }

    // ── Relationships ──────────────────────────────────────

    /**
     * Kompetisi yang diikuti tim ini.
     */
    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    /**
     * Kapten tim (pembuat tim).
     */
    public function captain(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Semua anggota tim (termasuk kapten) via pivot team_members.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_members')
                    ->withPivot('joined_at');
    }

    /**
     * Record team_members (untuk akses langsung ke model pivot).
     */
    public function teamMembers(): HasMany
    {
        return $this->hasMany(TeamMember::class);
    }

    // ── Helper Methods ─────────────────────────────────────

    /**
     * Apakah user tertentu adalah kapten tim ini?
     */
    public function isCaptain(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    /**
     * Apakah user tertentu sudah menjadi anggota tim ini?
     */
    public function hasMember(User $user): bool
    {
        return $this->members()->where('users.id', $user->id)->exists();
    }

    /**
     * Generate kode undangan unik 8 karakter uppercase.
     */
    public static function generateUniqueInviteCode(): string
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (self::where('invite_code', $code)->exists());

        return $code;
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }
}
