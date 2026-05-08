<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        $teamAlpha = DB::table('teams')->where('name', 'Tim Alpha')->value('id');
        $teamBeta  = DB::table('teams')->where('name', 'Tim Beta')->value('id');
        $teamPixel = DB::table('teams')->where('name', 'Tim Pixel')->value('id');

        $budi  = DB::table('users')->where('email', 'budi@gmail.com')->value('id');
        $siti  = DB::table('users')->where('email', 'siti@gmail.com')->value('id');
        $andi  = DB::table('users')->where('email', 'andi@gmail.com')->value('id');
        $dewi  = DB::table('users')->where('email', 'dewi@gmail.com')->value('id');
        $nina  = DB::table('users')->where('email', 'nina@gmail.com')->value('id');

        DB::table('team_members')->insert([
            // Tim Alpha: Budi (kapten) + Siti
            ['team_id' => $teamAlpha, 'user_id' => $budi, 'joined_at' => now()],
            ['team_id' => $teamAlpha, 'user_id' => $siti, 'joined_at' => now()],
            // Tim Beta: Andi (kapten) + Dewi
            ['team_id' => $teamBeta,  'user_id' => $andi, 'joined_at' => now()],
            ['team_id' => $teamBeta,  'user_id' => $dewi, 'joined_at' => now()],
            // Tim Pixel: Siti (kapten) + Nina
            ['team_id' => $teamPixel, 'user_id' => $siti, 'joined_at' => now()],
            ['team_id' => $teamPixel, 'user_id' => $nina, 'joined_at' => now()],
        ]);
    }
}
