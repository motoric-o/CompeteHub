<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BracketSeeder extends Seeder
{
    public function run(): void
    {
        // Round Penyisihan Hackathon
        $penyisihan = DB::table('rounds')
            ->where('name', 'Penyisihan')
            ->whereIn('competition_id', function ($q) {
                $q->select('id')->from('competitions')->where('name', 'Hackathon Nasional 2025');
            })
            ->value('id');

        $teamAlpha = DB::table('teams')->where('name', 'Tim Alpha')->value('id');
        $teamBeta  = DB::table('teams')->where('name', 'Tim Beta')->value('id');

        // Round Babak Utama CP
        $babakUtama = DB::table('rounds')
            ->where('name', 'Babak Utama')
            ->whereIn('competition_id', function ($q) {
                $q->select('id')->from('competitions')->where('name', 'Competitive Programming Cup');
            })
            ->value('id');

        $budi = DB::table('users')->where('email', 'budi@gmail.com')->value('id');
        $siti = DB::table('users')->where('email', 'siti@gmail.com')->value('id');

        DB::table('brackets')->insert([
            // Hackathon — bracket Tim Alpha vs Tim Beta (team type)
            [
                'round_id'         => $penyisihan,
                'participant_a'    => $teamAlpha,
                'participant_b'    => $teamBeta,
                'participant_type' => 'team',
                'winner_id'        => null,           // belum ada pemenang
                'scheduled_at'     => '2025-06-01 10:00:00',
                'created_at'       => now(),
            ],
            // CP — bracket Budi vs Siti (individual / user type)
            [
                'round_id'         => $babakUtama,
                'participant_a'    => $budi,
                'participant_b'    => $siti,
                'participant_type' => 'user',
                'winner_id'        => null,
                'scheduled_at'     => '2025-07-10 10:00:00',
                'created_at'       => now(),
            ],
        ]);
    }
}
