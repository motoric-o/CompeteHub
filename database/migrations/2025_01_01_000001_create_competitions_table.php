<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->default(\Illuminate\Support\Str::uuid());
            $table->foreignId('user_id')->constrained()->restrictOnDelete(); // committee
            $table->string('name', 200);
            $table->text('description')->nullable();
            $table->longText('rules')->nullable();
            $table->enum('type', ['individual', 'team']);
            $table->foreignId('scoring_type_id')->constrained()->restrictOnDelete();
            $table->decimal('time_scoring_threshold', 10, 2)->nullable();
            $table->decimal('registration_fee', 12, 2)->default(0);
            $table->integer('quota')->nullable();
            $table->text('banner_url')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamp('registration_start')->nullable();
            $table->timestamp('registration_end')->nullable();
            $table->enum('status', ['draft', 'open', 'ongoing', 'finished'])->default('draft');
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        Schema::create('rounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->integer('round_order')->default(1);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->enum('status', ['pending', 'active', 'finished'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rounds');
        Schema::dropIfExists('competitions');
    }
};
