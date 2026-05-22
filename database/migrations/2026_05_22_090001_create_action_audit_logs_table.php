<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Create action_audit_logs table.
 *
 * Records every committee action (single or bulk) with before/after state snapshots.
 * Used by Feature 7 & 8 (One-Click Review Actions) for:
 * - Audit trail (who did what, when)
 * - Rollback awareness (payload_before allows manual recovery)
 * - Accountability for bulk operations
 *
 * Design decisions:
 * - `payload_before` + `payload_after` are JSON snapshots, not FK references.
 *   This ensures the audit trail survives data changes or deletions.
 * - `competition_id` is stored directly (not just derived from target) so the
 *   Command Center can quickly filter logs per competition.
 * - No cascade delete — audit logs are permanent records.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('action_audit_logs', function (Blueprint $table) {
            $table->id();

            // Who performed the action
            $table->foreignId('actor_id')
                ->constrained('users')
                ->restrictOnDelete();

            // What action was performed
            $table->string('action_type', 100); // 'approve_registration', 'bulk_validate', 'send_reminder', etc.
            $table->index('action_type');

            // What was acted upon
            $table->string('target_type', 100); // 'registration', 'payment', 'document'
            $table->unsignedBigInteger('target_id');
            $table->index(['target_type', 'target_id'], 'target_index');

            // Competition context (for Command Center filtering)
            $table->foreignId('competition_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // State snapshots for rollback awareness
            $table->json('payload_before')->nullable();
            $table->json('payload_after')->nullable();

            // Optional: batch identifier for bulk actions
            // Multiple rows from one bulk action share the same batch_id
            $table->uuid('batch_id')->nullable()->index();

            // Human-readable description for the UI
            $table->string('description', 500)->nullable();

            $table->timestamp('created_at')->useCurrent();
            // No updated_at — audit logs are immutable
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('action_audit_logs');
    }
};
