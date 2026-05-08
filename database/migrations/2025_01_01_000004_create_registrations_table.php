<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Registrations — status mencerminkan tahap Chain of Responsibility
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();   // individu
            $table->foreignId('team_id')->nullable()->constrained()->cascadeOnDelete();   // tim
            $table->enum('status', [
                'pending',
                'account_ok',       // lolos AccountStatusHandler
                'documents_ok',     // lolos DocumentCompletionHandler
                'payment_ok',       // lolos PaymentVerificationHandler
                'rejected',
            ])->default('pending');
            $table->text('rejection_reason')->nullable();   // dari ValidationResult.message
            $table->string('payment_proof', 255)->nullable();
            $table->timestamps();
        });

        // Dokumen pendaftaran — diperiksa DocumentCompletionHandler (CoR tahap 2)
        Schema::create('registration_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->cascadeOnDelete();
            $table->string('document_type', 100);   // 'ktp', 'ktm', 'portfolio'
            $table->string('file_path', 255);
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamp('uploaded_at')->useCurrent();
        });

        // Payments — diperiksa PaymentVerificationHandler (CoR tahap 3)
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->unique()->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2)->default(0);
            $table->enum('status', ['unpaid', 'pending_verification', 'paid', 'free'])->default('unpaid');
            $table->string('proof_path', 255)->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('registration_documents');
        Schema::dropIfExists('registrations');
    }
};
