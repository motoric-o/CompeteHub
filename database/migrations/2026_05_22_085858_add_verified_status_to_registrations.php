<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

/**
 * Add 'verified' as the final approved state to registrations.status.
 *
 * The `status` column is VARCHAR(255) with a check constraint (not a native PG enum).
 * Laravel's ->enum() in PostgreSQL creates: column VARCHAR + check constraint.
 *
 * State flow after this migration:
 *   pending → account_ok → documents_ok → payment_ok → verified
 *                                                         ↑
 *                                          Committee manually marks as fully approved
 *                                          after passing the full CoR chain.
 *
 * Fixes dead-code bug: SubmissionController checked for 'verified' status
 * but it was never reachable because it was absent from the check constraint.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('registrations', function (Blueprint $table) {
                $table->enum('status', [
                    'pending',
                    'account_ok',
                    'documents_ok',
                    'payment_ok',
                    'verified',
                    'rejected'
                ])->default('pending')->change();
            });
            return;
        }

        // Find and drop the existing check constraint on the status column
        $constraints = DB::select("
            SELECT conname
            FROM pg_constraint
            WHERE conrelid = 'registrations'::regclass
              AND contype = 'c'
              AND pg_get_constraintdef(oid) LIKE '%status%'
        ");

        foreach ($constraints as $constraint) {
            DB::statement("ALTER TABLE registrations DROP CONSTRAINT \"{$constraint->conname}\"");
        }

        // Add updated check constraint with 'verified' included
        DB::statement("
            ALTER TABLE registrations
            ADD CONSTRAINT registrations_status_check
            CHECK (status IN (
                'pending',
                'account_ok',
                'documents_ok',
                'payment_ok',
                'verified',
                'rejected'
            ))
        ");
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('registrations', function (Blueprint $table) {
                $table->enum('status', [
                    'pending',
                    'account_ok',
                    'documents_ok',
                    'payment_ok',
                    'rejected'
                ])->default('pending')->change();
            });
            return;
        }

        // Drop the updated constraint
        DB::statement("ALTER TABLE registrations DROP CONSTRAINT IF EXISTS registrations_status_check");

        // Restore original constraint (without 'verified')
        // Note: any rows with status='verified' must be manually migrated first
        DB::statement("
            ALTER TABLE registrations
            ADD CONSTRAINT registrations_status_check
            CHECK (status IN (
                'pending',
                'account_ok',
                'documents_ok',
                'payment_ok',
                'rejected'
            ))
        ");
    }
};
