<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaderboardSeeder extends Seeder
{
    public function run(): void
    {
        $hackathon  = DB::table('competitions')->where('name', 'Hackathon Nasional 2025')->value('id');
        $cp         = DB::table('competitions')->where('name', 'Competitive Programming Cup')->value('id');

        $penyisihan = DB::table('rounds')->where('name', 'Penyisihan')->value('id');
        $babakUtama = DB::table('rounds')->where('name', 'Babak Utama')->value('id');

        $teamAlpha = DB::table('teams')->where('name', 'Tim Alpha')->value('id');
        $teamBeta  = DB::table('teams')->where('name', 'Tim Beta')->value('id');

        $budi = DB::table('users')->where('email', 'budi@gmail.com')->value('id');

        DB::table('leaderboard_entries')->insert([
            // Hackathon — per round (Penyisihan)
            // Tim Alpha: 87.50 + 5.00 = 92.50
            [
                'competition_id' => $hackathon,
                'round_id'       => $penyisihan,
                'user_id'        => null,
                'team_id'        => $teamAlpha,
                'total_score'    => 92.50,
                'rank'           => 1,
                'last_updated'   => now(),
            ],
            // Tim Beta: 0 + 0 = 0 (belum dinilai juri, revisi 2x)
            [
                'competition_id' => $hackathon,
                'round_id'       => $penyisihan,
                'user_id'        => null,
                'team_id'        => $teamBeta,
                'total_score'    => 0.00,
                'rank'           => 2,
                'last_updated'   => now(),
            ],
            // Hackathon — global
            [
                'competition_id' => $hackathon,
                'round_id'       => null,
                'user_id'        => null,
                'team_id'        => $teamAlpha,
                'total_score'    => 92.50,
                'rank'           => 1,
                'last_updated'   => now(),
            ],
            [
                'competition_id' => $hackathon,
                'round_id'       => null,
                'user_id'        => null,
                'team_id'        => $teamBeta,
                'total_score'    => 0.00,
                'rank'           => 2,
                'last_updated'   => now(),
            ],
            // CP — per round (Babak Utama)
            // Budi: 95.00 + 0 = 95.00
            [
                'competition_id' => $cp,
                'round_id'       => $babakUtama,
                'user_id'        => $budi,
                'team_id'        => null,
                'total_score'    => 95.00,
                'rank'           => 1,
                'last_updated'   => now(),
            ],
            // CP — global
            [
                'competition_id' => $cp,
                'round_id'       => null,
                'user_id'        => $budi,
                'team_id'        => null,
                'total_score'    => 95.00,
                'rank'           => 1,
                'last_updated'   => now(),
            ],
        ]);
    }
}
