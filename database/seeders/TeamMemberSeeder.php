<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        $teamAlpha   = DB::table('teams')->where('name', 'Tim Alpha')->value('id');
        $teamBeta    = DB::table('teams')->where('name', 'Tim Beta')->value('id');
        $teamGamma   = DB::table('teams')->where('name', 'Tim Gamma')->value('id');
        $teamDelta   = DB::table('teams')->where('name', 'Tim Delta')->value('id');
        $teamPixel   = DB::table('teams')->where('name', 'Tim Pixel')->value('id');
        $teamCanvas  = DB::table('teams')->where('name', 'Tim Canvas')->value('id');
        $teamWireframe = DB::table('teams')->where('name', 'Tim Wireframe')->value('id');
        $teamCyber   = DB::table('teams')->where('name', 'Tim CyberShield')->value('id');
        $teamZero    = DB::table('teams')->where('name', 'Tim ZeroDay')->value('id');
        $teamSmart   = DB::table('teams')->where('name', 'Tim SmartHome')->value('id');
        $teamGreen   = DB::table('teams')->where('name', 'Tim GreenTech')->value('id');
        $teamWeaver  = DB::table('teams')->where('name', 'Tim TechWeaver')->value('id');
        $teamSwift   = DB::table('teams')->where('name', 'Tim SwiftDev')->value('id');

        $budi    = DB::table('users')->where('email', 'budi@gmail.com')->value('id');
        $siti    = DB::table('users')->where('email', 'siti@gmail.com')->value('id');
        $andi    = DB::table('users')->where('email', 'andi@gmail.com')->value('id');
        $dewi    = DB::table('users')->where('email', 'dewi@gmail.com')->value('id');
        $nina    = DB::table('users')->where('email', 'nina@gmail.com')->value('id');
        $dian    = DB::table('users')->where('email', 'dian@gmail.com')->value('id');
        $zaki    = DB::table('users')->where('email', 'zaki@gmail.com')->value('id');
        $eko     = DB::table('users')->where('email', 'eko@gmail.com')->value('id');
        $fitri   = DB::table('users')->where('email', 'fitri@gmail.com')->value('id');
        $gunawan = DB::table('users')->where('email', 'gunawan@gmail.com')->value('id');
        $hendra  = DB::table('users')->where('email', 'hendra@gmail.com')->value('id');
        $indah   = DB::table('users')->where('email', 'indah@gmail.com')->value('id');
        $joko    = DB::table('users')->where('email', 'joko@gmail.com')->value('id');
        $lestari = DB::table('users')->where('email', 'lestari@gmail.com')->value('id');
        $mulyadi = DB::table('users')->where('email', 'mulyadi@gmail.com')->value('id');
        $nurul   = DB::table('users')->where('email', 'nurul@gmail.com')->value('id');
        $oki     = DB::table('users')->where('email', 'oki@gmail.com')->value('id');
        $rian    = DB::table('users')->where('email', 'rian@gmail.com')->value('id');
        $sisca   = DB::table('users')->where('email', 'sisca@gmail.com')->value('id');
        $taufik  = DB::table('users')->where('email', 'taufik@gmail.com')->value('id');

        DB::table('team_members')->insert([
            // Tim Alpha: Budi (kapten) + Siti
            ['team_id' => $teamAlpha, 'user_id' => $budi, 'joined_at' => now()],
            ['team_id' => $teamAlpha, 'user_id' => $siti, 'joined_at' => now()],
            // Tim Beta: Andi (kapten) + Dewi
            ['team_id' => $teamBeta,  'user_id' => $andi, 'joined_at' => now()],
            ['team_id' => $teamBeta,  'user_id' => $dewi, 'joined_at' => now()],
            // Tim Gamma: Dian (kapten) + Zaki
            ['team_id' => $teamGamma, 'user_id' => $dian, 'joined_at' => now()],
            ['team_id' => $teamGamma, 'user_id' => $zaki, 'joined_at' => now()],
            // Tim Delta: Eko (kapten) + Fitri
            ['team_id' => $teamDelta, 'user_id' => $eko, 'joined_at' => now()],
            ['team_id' => $teamDelta, 'user_id' => $fitri, 'joined_at' => now()],

            // Tim Pixel: Siti (kapten) + Nina
            ['team_id' => $teamPixel, 'user_id' => $siti, 'joined_at' => now()],
            ['team_id' => $teamPixel, 'user_id' => $nina, 'joined_at' => now()],
            // Tim Canvas: Zaki (kapten) + Dian
            ['team_id' => $teamCanvas, 'user_id' => $zaki, 'joined_at' => now()],
            ['team_id' => $teamCanvas, 'user_id' => $dian, 'joined_at' => now()],
            // Tim Wireframe: Fitri (kapten) + Gunawan
            ['team_id' => $teamWireframe, 'user_id' => $fitri, 'joined_at' => now()],
            ['team_id' => $teamWireframe, 'user_id' => $gunawan, 'joined_at' => now()],

            // Tim CyberShield: Gunawan (kapten) + Hendra
            ['team_id' => $teamCyber, 'user_id' => $gunawan, 'joined_at' => now()],
            ['team_id' => $teamCyber, 'user_id' => $hendra, 'joined_at' => now()],
            // Tim ZeroDay: Indah (kapten) + Joko
            ['team_id' => $teamZero,  'user_id' => $indah, 'joined_at' => now()],
            ['team_id' => $teamZero,  'user_id' => $joko, 'joined_at' => now()],

            // Tim SmartHome: Lestari (kapten) + Mulyadi
            ['team_id' => $teamSmart, 'user_id' => $lestari, 'joined_at' => now()],
            ['team_id' => $teamSmart, 'user_id' => $mulyadi, 'joined_at' => now()],
            // Tim GreenTech: Nurul (kapten) + Oki
            ['team_id' => $teamGreen, 'user_id' => $nurul, 'joined_at' => now()],
            ['team_id' => $teamGreen, 'user_id' => $oki, 'joined_at' => now()],

            // Tim TechWeaver: Rian (kapten) + Sisca
            ['team_id' => $teamWeaver, 'user_id' => $rian, 'joined_at' => now()],
            ['team_id' => $teamWeaver, 'user_id' => $sisca, 'joined_at' => now()],
            // Tim SwiftDev: Taufik (kapten) + Budi
            ['team_id' => $teamSwift,  'user_id' => $taufik, 'joined_at' => now()],
            ['team_id' => $teamSwift,  'user_id' => $budi, 'joined_at' => now()],
        ]);
    }
}
