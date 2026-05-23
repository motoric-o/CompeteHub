<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * NotificationLog — persists every notification sent through NotificationFacade.
 *
 * Relasi morphic ke notifiable (registration, payment, document) disimpan
 * sebagai plain string + id (bukan Eloquent morphs) karena kita tidak ingin
 * FK constraint — log harus survive bahkan jika source record dihapus.
 */
class NotificationLog extends Model
{
    protected $fillable = [
        'notifiable_type',
        'notifiable_id',
        'competition_id',
        'event_type',
        'channel',
        'recipient_email',
        'subject',
        'triggered_by',
        'status',
        'failure_reason',
        'payload',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'payload'  => 'array',
            'sent_at'  => 'datetime',
        ];
    }

    // ── Relationships ──────────────────────────────────────────────

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function triggeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'triggered_by');
    }

    // ── Scopes ─────────────────────────────────────────────────────

    public function scopeForCompetition($query, int $competitionId)
    {
        return $query->where('competition_id', $competitionId);
    }

    public function scopeByEvent($query, string $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    // ── Helpers ────────────────────────────────────────────────────

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isSent(): bool
    {
        return $this->status === 'sent';
    }
}
