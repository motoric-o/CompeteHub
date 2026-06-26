<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->integer('revision_count')->default(0)->after('status');
            $table->decimal('time_bonus', 8, 2)->nullable()->after('revision_count');
        });
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn(['revision_count', 'time_bonus']);
        });
    }
};
