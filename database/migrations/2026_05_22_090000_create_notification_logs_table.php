<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Create notification_logs table.
 *
 * Persists every notification sent through NotificationFacade.
 * Provides workflow visibility and auditability (Feature 6 — Auto Notification Log UI).
 *
 * Design decisions:
 * - Uses morphs-style `notifiable_type` + `notifiable_id` for flexibility
 *   (can be tied to a registration, payment, document, or competition).
 * - `triggered_by` is nullable — system-triggered notifications have no actor.
 * - `payload` stores the rendered body for debugging failed deliveries.
 * - No FK constraint on `notifiable_id` intentionally — we want logs to survive
 *   even if the source record is deleted.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();

            // What triggered this notification (e.g. 'registration', 'payment', 'document')
            $table->string('notifiable_type', 100)->nullable();
            $table->unsignedBigInteger('notifiable_id')->nullable();
            $table->index(['notifiable_type', 'notifiable_id'], 'notifiable_index');

            // Scope for filtering
            $table->foreignId('competition_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Event classification (e.g. 'registration_submitted', 'payment_verified')
            $table->string('event_type', 100);
            $table->index('event_type');

            // Delivery info
            $table->string('channel', 50)->default('email'); // 'email' | 'system'
            $table->string('recipient_email', 255)->nullable();
            $table->string('subject', 255)->nullable();

            // Who triggered it (null = auto/system)
            $table->foreignId('triggered_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Delivery status
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->text('failure_reason')->nullable();

            // Full payload for debugging
            $table->json('payload')->nullable();

            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
