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
            $table->string('file_path', 255)->nullable();
            $table->string('submission_url', 500)->nullable();
            $table->string('file_type', 50)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->timestamp('submitted_at')->useCurrent();   // JANGAN pernah diupdate!
            $table->decimal('final_score', 8, 2)->nullable();
            $table->enum('status', ['submitted', 'under_review', 'scored'])->default('submitted');
            $table->timestamps();
        });

        // Quiz Questions
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('round_id')->constrained()->cascadeOnDelete();
            $table->text('question_text');
            $table->enum('question_type', ['multiple_choice', 'essay'])->default('multiple_choice');
            $table->json('options')->nullable();
            $table->string('correct_answer')->nullable();
            $table->integer('points')->default(10);
            $table->timestamps();
        });

        // Quiz Answers
        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('quiz_questions')->cascadeOnDelete();
            $table->text('answer_text')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->decimal('score', 8, 2)->default(0);
            $table->timestamps();
            $table->unique(['submission_id', 'question_id']);
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

        // Submission Votes — tabel untuk community voting
        Schema::create('submission_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['submission_id', 'user_id']); // 1 user 1 vote per submission
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submission_votes');
        Schema::dropIfExists('scores');
        Schema::dropIfExists('quiz_answers');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('submissions');
        Schema::dropIfExists('brackets');
        Schema::dropIfExists('jury_assignments');
    }
};
