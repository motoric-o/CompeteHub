<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Jury assignments — diverifikasi ScoreProxy (Proxy Pattern)
        Schema::create('jury_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->timestamp('assigned_at')->useCurrent();
            $table->unique(['user_id', 'competition_id']);
        });

        // Brackets — bagan pertandingan per babak
        Schema::create('brackets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('round_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('participant_a')->nullable();
            $table->unsignedBigInteger('participant_b')->nullable();
            $table->enum('participant_type', ['user', 'team']);
            $table->unsignedBigInteger('winner_id')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // Submissions — file submisi peserta
        // submitted_at IMMUTABLE (dijaga trigger di DB + kita tidak expose update di Laravel)
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->foreignId('round_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();   // individu
            $table->foreignId('team_id')->nullable()->constrained()->cascadeOnDelete();   // tim
            $table->string('file_path', 255);
            $table->string('file_type', 50)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->timestamp('submitted_at')->useCurrent();   // JANGAN pernah diupdate!
            $table->decimal('final_score', 8, 2)->nullable();
            $table->enum('status', ['submitted', 'under_review', 'scored'])->default('submitted');
            $table->timestamps();
        });

        // Scores — nilai individual tiap juri per submisi
        // JudgeScoreStrategy menghitung avg dari tabel ini
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();   // juri
            $table->decimal('score', 6, 2);
            $table->text('notes')->nullable();
            $table->timestamp('scored_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->unique(['submission_id', 'user_id']);   // 1 juri 1x per submisi
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scores');
        Schema::dropIfExists('submissions');
        Schema::dropIfExists('brackets');
        Schema::dropIfExists('jury_assignments');
    }
};
