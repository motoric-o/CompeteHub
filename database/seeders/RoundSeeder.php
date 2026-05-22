<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoundSeeder extends Seeder
{
    public function run(): void
    {
        $hackathon = DB::table('competitions')->where('name', 'Hackathon Nasional 2025')->value('id');
        $cp        = DB::table('competitions')->where('name', 'Competitive Programming Cup')->value('id');

        $judgeScoreId = DB::table('scoring_types')->where('name', 'Judge Score')->value('id');
        $timeBasedId = DB::table('scoring_types')->where('name', 'Time Based')->value('id');
        $communityId = DB::table('scoring_types')->where('name', 'Community Voting')->value('id');

        DB::table('rounds')->insert([
            // Hackathon rounds
            ['competition_id' => $hackathon, 'scoring_type_id' => $communityId, 'name' => 'Penyisihan',  'round_order' => 1, 'status' => 'active',  'start_date' => '2026-06-01 08:00:00', 'end_date' => '2026-06-01 20:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['competition_id' => $hackathon, 'scoring_type_id' => $judgeScoreId, 'name' => 'Semi Final',  'round_order' => 2, 'status' => 'pending', 'start_date' => '2026-06-02 08:00:00', 'end_date' => '2026-06-02 20:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['competition_id' => $hackathon, 'scoring_type_id' => $judgeScoreId, 'name' => 'Grand Final', 'round_order' => 3, 'status' => 'pending', 'start_date' => '2026-06-03 08:00:00', 'end_date' => '2026-06-03 20:00:00', 'created_at' => now(), 'updated_at' => now()],
            // CP rounds
            ['competition_id' => $cp,        'scoring_type_id' => $timeBasedId, 'name' => 'Babak Utama', 'round_order' => 1, 'status' => 'pending', 'start_date' => '2026-05-10 09:00:00', 'end_date' => '2026-07-10 17:00:00', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
