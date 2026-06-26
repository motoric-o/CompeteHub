<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubmissionDeadlineDemoSeeder extends Seeder
{
    public function run(): void
    {
        $competitionId = DB::table('competitions')
            ->where('name', 'Hackathon Nasional 2025')
            ->value('id');

        if (! $competitionId) {
            return;
        }

        DB::table('rounds')
            ->where('competition_id', $competitionId)
            ->where('name', 'Penyisihan')
            ->update([
                'status' => 'active',
                'start_date' => now()->subDay(),
                'end_date' => now()->addDays(2),
                'updated_at' => now(),
            ]);

        DB::table('rounds')
            ->where('competition_id', $competitionId)
            ->where('name', 'Semi Final')
            ->update([
                'status' => 'active',
                'start_date' => now()->subHour(),
                'end_date' => now()->addHours(2),
                'updated_at' => now(),
            ]);

        DB::table('rounds')
            ->where('competition_id', $competitionId)
            ->where('name', 'Grand Final')
            ->update([
                'status' => 'active',
                'start_date' => now()->subHour(),
                'end_date' => now()->addHours(20),
                'updated_at' => now(),
            ]);

        DB::table('competitions')
            ->where('id', $competitionId)
            ->update([
                'status' => 'open',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addDays(2)->toDateString(),
                'updated_at' => now(),
            ]);

        $teamAlphaId = DB::table('teams')->where('name', 'Tim Alpha')->value('id');

        if (! $teamAlphaId) {
            return;
        }

        $registrationId = DB::table('registrations')
            ->where('competition_id', $competitionId)
            ->where('team_id', $teamAlphaId)
            ->value('id');

        if (! $registrationId) {
            return;
        }

        DB::table('registrations')
            ->where('id', $registrationId)
            ->update([
                'status' => 'verified',
                'updated_at' => now(),
            ]);

        DB::table('payments')
            ->where('registration_id', $registrationId)
            ->update([
                'status' => 'paid',
                'verified_at' => now(),
                'updated_at' => now(),
            ]);
    }
}