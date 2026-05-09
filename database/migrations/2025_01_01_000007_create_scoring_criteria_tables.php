<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scoring_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->string('name', 200);
            $table->text('description')->nullable();
            $table->integer('max_score')->default(100);
            $table->decimal('weight', 5, 2)->default(1.0);
            $table->timestamps();
        });

        Schema::create('criterion_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('score_id')->constrained()->cascadeOnDelete();
            $table->foreignId('criterion_id')->constrained('scoring_criteria')->cascadeOnDelete();
            $table->decimal('value', 8, 2);
            $table->timestamps(); 
            $table->unique(['score_id', 'criterion_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('criterion_scores');
        Schema::dropIfExists('scoring_criteria');
    }
};
