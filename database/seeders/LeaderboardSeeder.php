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

        // Competitions
        $hackathon = DB::table('competitions')->where('name', 'Hackathon Nasional 2025')->value('id');
        $cp        = DB::table('competitions')->where('name', 'Competitive Programming Cup')->value('id');
        $uiux      = DB::table('competitions')->where('name', 'UI/UX Design Competition')->value('id');
        $ctf       = DB::table('competitions')->where('name', 'Siber Defense CTF 2026')->value('id');
        $ds        = DB::table('competitions')->where('name', 'Data Science Challenge 2026')->value('id');
        $iot       = DB::table('competitions')->where('name', 'IoT Innovation Cup 2025')->value('id');
        $mobile    = DB::table('competitions')->where('name', 'Mobile App Dev Arena')->value('id');
        $ctfJr     = DB::table('competitions')->where('name', 'Cyber Security CTF Junior')->value('id');

        // Rounds
        $penyisihan = DB::table('rounds')->where('name', 'Penyisihan')->where('competition_id', $hackathon)->value('id');
        $babakUtama = DB::table('rounds')->where('name', 'Babak Utama')->where('competition_id', $cp)->value('id');
        $uiuxRound  = DB::table('rounds')->where('name', 'Penyisihan Portofolio')->where('competition_id', $uiux)->value('id');
        $ctfRound   = DB::table('rounds')->where('name', 'Quals Jeopardy')->where('competition_id', $ctf)->value('id');
        $dsRound    = DB::table('rounds')->where('name', 'Kaggle Phase')->where('competition_id', $ds)->value('id');
        $iotRound   = DB::table('rounds')->where('name', 'Proposal Submission')->where('competition_id', $iot)->value('id');
        $mobileRound = DB::table('rounds')->where('name', 'App Prototype')->where('competition_id', $mobile)->value('id');
        $ctfJrRound = DB::table('rounds')->where('name', 'Online CTF Quals')->where('competition_id', $ctfJr)->value('id');

        // Teams
        $teamAlpha   = DB::table('teams')->where('name', 'Tim Alpha')->value('id');
        $teamBeta    = DB::table('teams')->where('name', 'Tim Beta')->value('id');
        $teamGamma   = DB::table('teams')->where('name', 'Tim Gamma')->value('id');
        $teamDelta   = DB::table('teams')->where('name', 'Tim Delta')->value('id');
        $teamCanvas  = DB::table('teams')->where('name', 'Tim Canvas')->value('id');
        $teamWireframe = DB::table('teams')->where('name', 'Tim Wireframe')->value('id');
        $teamCyber   = DB::table('teams')->where('name', 'Tim CyberShield')->value('id');
        $teamZero    = DB::table('teams')->where('name', 'Tim ZeroDay')->value('id');
        $teamSmart   = DB::table('teams')->where('name', 'Tim SmartHome')->value('id');
        $teamGreen   = DB::table('teams')->where('name', 'Tim GreenTech')->value('id');
        $teamWeaver  = DB::table('teams')->where('name', 'Tim TechWeaver')->value('id');

        // Users
        $budi    = DB::table('users')->where('email', 'budi@gmail.com')->value('id');
        $siti    = DB::table('users')->where('email', 'siti@gmail.com')->value('id');
        $andi    = DB::table('users')->where('email', 'andi@gmail.com')->value('id');
        $dian    = DB::table('users')->where('email', 'dian@gmail.com')->value('id');
        $eko     = DB::table('users')->where('email', 'eko@gmail.com')->value('id');
        $fitri   = DB::table('users')->where('email', 'fitri@gmail.com')->value('id');
        $gunawan = DB::table('users')->where('email', 'gunawan@gmail.com')->value('id');
        $hendra  = DB::table('users')->where('email', 'hendra@gmail.com')->value('id');

        DB::table('leaderboard_entries')->insert([
            // 1. Hackathon Penyisihan (Round)
            ['competition_id' => $hackathon, 'round_id' => $penyisihan, 'user_id' => null, 'team_id' => $teamAlpha, 'total_score' => 92.50, 'rank' => 1, 'previous_rank' => 1, 'last_updated' => now()],
            ['competition_id' => $hackathon, 'round_id' => $penyisihan, 'user_id' => null, 'team_id' => $teamBeta,  'total_score' => 81.00, 'rank' => 2, 'previous_rank' => 3, 'last_updated' => now()],
            ['competition_id' => $hackathon, 'round_id' => $penyisihan, 'user_id' => null, 'team_id' => $teamGamma, 'total_score' => 80.50, 'rank' => 3, 'previous_rank' => 2, 'last_updated' => now()],
            ['competition_id' => $hackathon, 'round_id' => $penyisihan, 'user_id' => null, 'team_id' => $teamDelta, 'total_score' => 0.00,  'rank' => 4, 'previous_rank' => 4, 'last_updated' => now()],
            // Hackathon Global
            ['competition_id' => $hackathon, 'round_id' => null,        'user_id' => null, 'team_id' => $teamAlpha, 'total_score' => 92.50, 'rank' => 1, 'previous_rank' => 1, 'last_updated' => now()],
            ['competition_id' => $hackathon, 'round_id' => null,        'user_id' => null, 'team_id' => $teamBeta,  'total_score' => 81.00, 'rank' => 2, 'previous_rank' => 3, 'last_updated' => now()],
            ['competition_id' => $hackathon, 'round_id' => null,        'user_id' => null, 'team_id' => $teamGamma, 'total_score' => 80.50, 'rank' => 3, 'previous_rank' => 2, 'last_updated' => now()],
            ['competition_id' => $hackathon, 'round_id' => null,        'user_id' => null, 'team_id' => $teamDelta, 'total_score' => 0.00,  'rank' => 4, 'previous_rank' => 4, 'last_updated' => now()],

            // 2. CP Babak Utama (Round)
            ['competition_id' => $cp, 'round_id' => $babakUtama, 'user_id' => $budi, 'team_id' => null, 'total_score' => 95.00, 'rank' => 1, 'previous_rank' => 1, 'last_updated' => now()],
            ['competition_id' => $cp, 'round_id' => $babakUtama, 'user_id' => $siti, 'team_id' => null, 'total_score' => 92.00, 'rank' => 2, 'previous_rank' => 2, 'last_updated' => now()],
            ['competition_id' => $cp, 'round_id' => $babakUtama, 'user_id' => $andi, 'team_id' => null, 'total_score' => 0.00,  'rank' => 3, 'previous_rank' => 3, 'last_updated' => now()],
            // CP Global
            ['competition_id' => $cp, 'round_id' => null,        'user_id' => $budi, 'team_id' => null, 'total_score' => 95.00, 'rank' => 1, 'previous_rank' => 1, 'last_updated' => now()],
            ['competition_id' => $cp, 'round_id' => null,        'user_id' => $siti, 'team_id' => null, 'total_score' => 92.00, 'rank' => 2, 'previous_rank' => 2, 'last_updated' => now()],
            ['competition_id' => $cp, 'round_id' => null,        'user_id' => $andi, 'team_id' => null, 'total_score' => 0.00,  'rank' => 3, 'previous_rank' => 3, 'last_updated' => now()],

            // 3. UI/UX (Round)
            ['competition_id' => $uiux, 'round_id' => $uiuxRound, 'user_id' => null, 'team_id' => $teamCanvas, 'total_score' => 91.00, 'rank' => 1, 'previous_rank' => 1, 'last_updated' => now()],
            ['competition_id' => $uiux, 'round_id' => $uiuxRound, 'user_id' => null, 'team_id' => $teamWireframe, 'total_score' => 0.00, 'rank' => 2, 'previous_rank' => 2, 'last_updated' => now()],
            // UI/UX Global
            ['competition_id' => $uiux, 'round_id' => null,        'user_id' => null, 'team_id' => $teamCanvas, 'total_score' => 91.00, 'rank' => 1, 'previous_rank' => 1, 'last_updated' => now()],
            ['competition_id' => $uiux, 'round_id' => null,        'user_id' => null, 'team_id' => $teamWireframe, 'total_score' => 0.00, 'rank' => 2, 'previous_rank' => 2, 'last_updated' => now()],

            // 4. CTF (Round)
            ['competition_id' => $ctf, 'round_id' => $ctfRound, 'user_id' => null, 'team_id' => $teamCyber, 'total_score' => 360.00, 'rank' => 1, 'previous_rank' => 1, 'last_updated' => now()],
            ['competition_id' => $ctf, 'round_id' => $ctfRound, 'user_id' => null, 'team_id' => $teamZero,  'total_score' => 0.00,   'rank' => 2, 'previous_rank' => 2, 'last_updated' => now()],
            // CTF Global
            ['competition_id' => $ctf, 'round_id' => null,        'user_id' => null, 'team_id' => $teamCyber, 'total_score' => 360.00, 'rank' => 1, 'previous_rank' => 1, 'last_updated' => now()],
            ['competition_id' => $ctf, 'round_id' => null,        'user_id' => null, 'team_id' => $teamZero,  'total_score' => 0.00,   'rank' => 2, 'previous_rank' => 2, 'last_updated' => now()],

            // 5. Data Science (Round)
            ['competition_id' => $ds, 'round_id' => $dsRound, 'user_id' => $dian, 'team_id' => null, 'total_score' => 94.00, 'rank' => 1, 'previous_rank' => 2, 'last_updated' => now()],
            ['competition_id' => $ds, 'round_id' => $dsRound, 'user_id' => $eko,  'team_id' => null, 'total_score' => 89.50, 'rank' => 2, 'previous_rank' => 1, 'last_updated' => now()],
            ['competition_id' => $ds, 'round_id' => $dsRound, 'user_id' => $fitri, 'team_id' => null, 'total_score' => 0.00,  'rank' => 3, 'previous_rank' => 3, 'last_updated' => now()],
            // DS Global
            ['competition_id' => $ds, 'round_id' => null,        'user_id' => $dian, 'team_id' => null, 'total_score' => 94.00, 'rank' => 1, 'previous_rank' => 2, 'last_updated' => now()],
            ['competition_id' => $ds, 'round_id' => null,        'user_id' => $eko,  'team_id' => null, 'total_score' => 89.50, 'rank' => 2, 'previous_rank' => 1, 'last_updated' => now()],
            ['competition_id' => $ds, 'round_id' => null,        'user_id' => $fitri, 'team_id' => null, 'total_score' => 0.00,  'rank' => 3, 'previous_rank' => 3, 'last_updated' => now()],

            // 6. IoT (Round)
            ['competition_id' => $iot, 'round_id' => $iotRound, 'user_id' => null, 'team_id' => $teamSmart, 'total_score' => 93.00, 'rank' => 1, 'previous_rank' => 1, 'last_updated' => now()],
            ['competition_id' => $iot, 'round_id' => $iotRound, 'user_id' => null, 'team_id' => $teamGreen, 'total_score' => 86.00, 'rank' => 2, 'previous_rank' => 2, 'last_updated' => now()],
            // IoT Global
            ['competition_id' => $iot, 'round_id' => null,        'user_id' => null, 'team_id' => $teamSmart, 'total_score' => 93.00, 'rank' => 1, 'previous_rank' => 1, 'last_updated' => now()],
            ['competition_id' => $iot, 'round_id' => null,        'user_id' => null, 'team_id' => $teamGreen, 'total_score' => 86.00, 'rank' => 2, 'previous_rank' => 2, 'last_updated' => now()],

            // 7. Mobile App (Round)
            ['competition_id' => $mobile, 'round_id' => $mobileRound, 'user_id' => null, 'team_id' => $teamWeaver, 'total_score' => 92.00, 'rank' => 1, 'previous_rank' => 1, 'last_updated' => now()],
            // Mobile Global
            ['competition_id' => $mobile, 'round_id' => null,         'user_id' => null, 'team_id' => $teamWeaver, 'total_score' => 92.00, 'rank' => 1, 'previous_rank' => 1, 'last_updated' => now()],

            // 8. Cyber Security CTF Junior (Round)
            ['competition_id' => $ctfJr, 'round_id' => $ctfJrRound, 'user_id' => $gunawan, 'team_id' => null, 'total_score' => 97.00, 'rank' => 1, 'previous_rank' => 1, 'last_updated' => now()],
            ['competition_id' => $ctfJr, 'round_id' => $ctfJrRound, 'user_id' => $hendra,  'team_id' => null, 'total_score' => 0.00,  'rank' => 2, 'previous_rank' => 2, 'last_updated' => now()],
            // CTF Jr Global
            ['competition_id' => $ctfJr, 'round_id' => null,         'user_id' => $gunawan, 'team_id' => null, 'total_score' => 97.00, 'rank' => 1, 'previous_rank' => 1, 'last_updated' => now()],
            ['competition_id' => $ctfJr, 'round_id' => null,         'user_id' => $hendra,  'team_id' => null, 'total_score' => 0.00,  'rank' => 2, 'previous_rank' => 2, 'last_updated' => now()],
        ]);
    }
}
