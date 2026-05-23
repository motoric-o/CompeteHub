<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ActionAuditLog — immutable record of every committee action.
 *
 * Digunakan oleh Feature 7 & 8 (One-Click Review Actions).
 * Tidak ada updated_at karena audit log bersifat immutable.
 *
 * `batch_id` mengelompokkan multiple rows dari satu bulk action
 * sehingga bisa ditampilkan di UI sebagai satu operasi.
 */
class ActionAuditLog extends Model
{
    // Audit logs are immutable — no updated_at
    public const UPDATED_AT = null;

    protected $fillable = [
        'actor_id',
        'action_type',
        'target_type',
        'target_id',
        'competition_id',
        'payload_before',
        'payload_after',
        'batch_id',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'payload_before' => 'array',
            'payload_after'  => 'array',
        ];
    }

    // ── Relationships ──────────────────────────────────────────────

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    // ── Scopes ─────────────────────────────────────────────────────

    public function scopeForCompetition($query, int $competitionId)
    {
        return $query->where('competition_id', $competitionId);
    }

    public function scopeByBatch($query, string $batchId)
    {
        return $query->where('batch_id', $batchId);
    }

    public function scopeByAction($query, string $actionType)
    {
        return $query->where('action_type', $actionType);
    }

    // ── Factory Helper ─────────────────────────────────────────────

    /**
     * Log a single action. Used by ReviewCommand::execute() implementations.
     */
    public static function record(
        int $actorId,
        string $actionType,
        string $targetType,
        int $targetId,
        ?int $competitionId,
        array $before,
        array $after,
        ?string $batchId = null,
        ?string $description = null,
    ): self {
        return self::create([
            'actor_id'       => $actorId,
            'action_type'    => $actionType,
            'target_type'    => $targetType,
            'target_id'      => $targetId,
            'competition_id' => $competitionId,
            'payload_before' => $before,
            'payload_after'  => $after,
            'batch_id'       => $batchId,
            'description'    => $description,
        ]);
    }
}
