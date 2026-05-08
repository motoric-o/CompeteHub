<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Leaderboard entries — di-update LeaderboardObserver (Observer Pattern)
        Schema::create('leaderboard_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->foreignId('round_id')->nullable()->constrained()->cascadeOnDelete(); // NULL = global
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();  // individu
            $table->foreignId('team_id')->nullable()->constrained()->cascadeOnDelete();  // tim
            $table->decimal('total_score', 10, 2)->default(0);
            $table->integer('rank')->nullable();
            $table->timestamp('last_updated')->useCurrent();
        });

        // Contribution stats — statistik kontribusi anggota tim
        Schema::create('contribution_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->integer('submission_count')->default(0);
            $table->decimal('avg_score', 6, 2)->nullable();
            $table->decimal('contribution_pct', 5, 2)->nullable();
            $table->timestamp('last_updated')->useCurrent();
            $table->unique(['team_id', 'user_id', 'competition_id']);
        });

        // Notifications — log notifikasi (EmailNotifierObserver + NotificationFacade)
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type', 50);   // 'score_update', 'registration_approved', dll
            $table->string('title', 200)->nullable();
            $table->text('body')->nullable();
            $table->enum('channel', ['email', 'push', 'in_app'])->default('email');
            $table->boolean('is_read')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // Storage files — metadata semua file di storage (NotificationFacade)
        Schema::create('storage_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('bucket', 100);
            $table->string('path', 500);
            $table->string('original_name', 255)->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['bucket', 'path']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storage_files');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('contribution_stats');
        Schema::dropIfExists('leaderboard_entries');
    }
};
