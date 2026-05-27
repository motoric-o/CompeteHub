<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContributionStatSeeder extends Seeder
{
    public function run(): void
    {
        // Competitions
        $hackathon = DB::table('competitions')->where('name', 'Hackathon Nasional 2025')->value('id');
        $uiux      = DB::table('competitions')->where('name', 'UI/UX Design Competition')->value('id');
        $ctf       = DB::table('competitions')->where('name', 'Siber Defense CTF 2026')->value('id');
        $iot       = DB::table('competitions')->where('name', 'IoT Innovation Cup 2025')->value('id');
        $mobile    = DB::table('competitions')->where('name', 'Mobile App Dev Arena')->value('id');

        // Teams
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

        // Users
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

        DB::table('contribution_stats')->insert([
            // Hackathon Alpha
            ['team_id' => $teamAlpha, 'user_id' => $budi, 'competition_id' => $hackathon, 'submission_count' => 1, 'avg_score' => 87.50, 'contribution_pct' => 60.00, 'last_updated' => now()],
            ['team_id' => $teamAlpha, 'user_id' => $siti, 'competition_id' => $hackathon, 'submission_count' => 0, 'avg_score' => null,  'contribution_pct' => 40.00, 'last_updated' => now()],
            // Hackathon Beta
            ['team_id' => $teamBeta,  'user_id' => $andi, 'competition_id' => $hackathon, 'submission_count' => 1, 'avg_score' => 81.00, 'contribution_pct' => 50.00, 'last_updated' => now()],
            ['team_id' => $teamBeta,  'user_id' => $dewi, 'competition_id' => $hackathon, 'submission_count' => 0, 'avg_score' => null,  'contribution_pct' => 50.00, 'last_updated' => now()],
            // Hackathon Gamma
            ['team_id' => $teamGamma, 'user_id' => $dian, 'competition_id' => $hackathon, 'submission_count' => 1, 'avg_score' => 79.50, 'contribution_pct' => 70.00, 'last_updated' => now()],
            ['team_id' => $teamGamma, 'user_id' => $zaki, 'competition_id' => $hackathon, 'submission_count' => 0, 'avg_score' => null,  'contribution_pct' => 30.00, 'last_updated' => now()],
            // Hackathon Delta
            ['team_id' => $teamDelta, 'user_id' => $eko,  'competition_id' => $hackathon, 'submission_count' => 0, 'avg_score' => null,  'contribution_pct' => 50.00, 'last_updated' => now()],
            ['team_id' => $teamDelta, 'user_id' => $fitri, 'competition_id' => $hackathon, 'submission_count' => 0, 'avg_score' => null,  'contribution_pct' => 50.00, 'last_updated' => now()],

            // UI/UX Pixel
            ['team_id' => $teamPixel, 'user_id' => $siti, 'competition_id' => $uiux,      'submission_count' => 0, 'avg_score' => null,  'contribution_pct' => 50.00, 'last_updated' => now()],
            ['team_id' => $teamPixel, 'user_id' => $nina, 'competition_id' => $uiux,      'submission_count' => 0, 'avg_score' => null,  'contribution_pct' => 50.00, 'last_updated' => now()],
            // UI/UX Canvas
            ['team_id' => $teamCanvas, 'user_id' => $zaki, 'competition_id' => $uiux,      'submission_count' => 1, 'avg_score' => 91.00, 'contribution_pct' => 80.00, 'last_updated' => now()],
            ['team_id' => $teamCanvas, 'user_id' => $dian, 'competition_id' => $uiux,      'submission_count' => 0, 'avg_score' => null,  'contribution_pct' => 20.00, 'last_updated' => now()],
            // UI/UX Wireframe
            ['team_id' => $teamWireframe, 'user_id' => $fitri, 'competition_id' => $uiux,      'submission_count' => 0, 'avg_score' => null,  'contribution_pct' => 50.00, 'last_updated' => now()],
            ['team_id' => $teamWireframe, 'user_id' => $gunawan, 'competition_id' => $uiux,      'submission_count' => 0, 'avg_score' => null,  'contribution_pct' => 50.00, 'last_updated' => now()],

            // CTF CyberShield
            ['team_id' => $teamCyber, 'user_id' => $gunawan, 'competition_id' => $ctf,       'submission_count' => 1, 'avg_score' => 350.00, 'contribution_pct' => 90.00, 'last_updated' => now()],
            ['team_id' => $teamCyber, 'user_id' => $hendra, 'competition_id' => $ctf,       'submission_count' => 0, 'avg_score' => null,   'contribution_pct' => 10.00, 'last_updated' => now()],
            // CTF ZeroDay
            ['team_id' => $teamZero,  'user_id' => $indah, 'competition_id' => $ctf,       'submission_count' => 0, 'avg_score' => null,   'contribution_pct' => 50.00, 'last_updated' => now()],
            ['team_id' => $teamZero,  'user_id' => $joko,  'competition_id' => $ctf,       'submission_count' => 0, 'avg_score' => null,   'contribution_pct' => 50.00, 'last_updated' => now()],

            // IoT SmartHome
            ['team_id' => $teamSmart, 'user_id' => $lestari, 'competition_id' => $iot,       'submission_count' => 1, 'avg_score' => 93.00, 'contribution_pct' => 60.00, 'last_updated' => now()],
            ['team_id' => $teamSmart, 'user_id' => $mulyadi, 'competition_id' => $iot,       'submission_count' => 0, 'avg_score' => null,  'contribution_pct' => 40.00, 'last_updated' => now()],
            // IoT GreenTech
            ['team_id' => $teamGreen, 'user_id' => $nurul,   'competition_id' => $iot,       'submission_count' => 1, 'avg_score' => 86.00, 'contribution_pct' => 50.00, 'last_updated' => now()],
            ['team_id' => $teamGreen, 'user_id' => $oki,     'competition_id' => $iot,       'submission_count' => 0, 'avg_score' => null,  'contribution_pct' => 50.00, 'last_updated' => now()],

            // Mobile TechWeaver
            ['team_id' => $teamWeaver, 'user_id' => $rian,  'competition_id' => $mobile,    'submission_count' => 1, 'avg_score' => 90.00, 'contribution_pct' => 75.00, 'last_updated' => now()],
            ['team_id' => $teamWeaver, 'user_id' => $sisca, 'competition_id' => $mobile,    'submission_count' => 0, 'avg_score' => null,  'contribution_pct' => 25.00, 'last_updated' => now()],
            // Mobile SwiftDev
            ['team_id' => $teamSwift,  'user_id' => $taufik, 'competition_id' => $mobile,    'submission_count' => 0, 'avg_score' => null,  'contribution_pct' => 50.00, 'last_updated' => now()],
            ['team_id' => $teamSwift,  'user_id' => $budi,   'competition_id' => $mobile,    'submission_count' => 0, 'avg_score' => null,  'contribution_pct' => 50.00, 'last_updated' => now()],
        ]);
    }
}
