<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContributionStatSeeder extends Seeder
{
    public function run(): void
    {
        $hackathon = DB::table('competitions')->where('name', 'Hackathon Nasional 2025')->value('id');
        $teamAlpha = DB::table('teams')->where('name', 'Tim Alpha')->value('id');
        $budi = DB::table('users')->where('email', 'budi@gmail.com')->value('id');
        $siti = DB::table('users')->where('email', 'siti@gmail.com')->value('id');

        DB::table('contribution_stats')->insert([
            [
                'team_id'          => $teamAlpha,
                'user_id'          => $budi,
                'competition_id'   => $hackathon,
                'submission_count' => 1,
                'avg_score'        => 87.50,
                'contribution_pct' => 60.00,
                'last_updated'     => now(),
            ],
            [
                'team_id'          => $teamAlpha,
                'user_id'          => $siti,
                'competition_id'   => $hackathon,
                'submission_count' => 0,
                'avg_score'        => null,
                'contribution_pct' => 40.00,
                'last_updated'     => now(),
            ],
        ]);
    }
}
