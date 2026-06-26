<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BracketSeeder extends Seeder
{
    public function run(): void
    {
        // Rounds
        $hackathonRound = DB::table('rounds')->where('name', 'Penyisihan')->value('id');
        $cpRound        = DB::table('rounds')->where('name', 'Babak Utama')->value('id');
        $ctfRound       = DB::table('rounds')->where('name', 'Quals Jeopardy')->value('id');
        $uiuxRound      = DB::table('rounds')->where('name', 'Penyisihan Portofolio')->value('id');
        $iotRound       = DB::table('rounds')->where('name', 'Proposal Submission')->value('id');
        $mobileRound    = DB::table('rounds')->where('name', 'App Prototype')->value('id');
        $ctfJrRound     = DB::table('rounds')->where('name', 'Online CTF Quals')->value('id');

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
        $teamSwift   = DB::table('teams')->where('name', 'Tim SwiftDev')->value('id');

        // Users
        $budi    = DB::table('users')->where('email', 'budi@gmail.com')->value('id');
        $siti    = DB::table('users')->where('email', 'siti@gmail.com')->value('id');
        $andi    = DB::table('users')->where('email', 'andi@gmail.com')->value('id');
        $dewi    = DB::table('users')->where('email', 'dewi@gmail.com')->value('id');
        $gunawan = DB::table('users')->where('email', 'gunawan@gmail.com')->value('id');
        $hendra  = DB::table('users')->where('email', 'hendra@gmail.com')->value('id');

        DB::table('brackets')->insert([
            // Hackathon: Tim Alpha vs Tim Beta
            [
                'round_id'         => $hackathonRound,
                'participant_a'    => $teamAlpha,
                'participant_b'    => $teamBeta,
                'participant_type' => 'team',
                'winner_id'        => null,
                'scheduled_at'     => '2026-06-01 10:00:00',
                'created_at'       => now(),
            ],
            // Hackathon: Tim Gamma vs Tim Delta
            [
                'round_id'         => $hackathonRound,
                'participant_a'    => $teamGamma,
                'participant_b'    => $teamDelta,
                'participant_type' => 'team',
                'winner_id'        => null,
                'scheduled_at'     => '2026-06-01 13:00:00',
                'created_at'       => now(),
            ],
            // CP: Budi vs Siti
            [
                'round_id'         => $cpRound,
                'participant_a'    => $budi,
                'participant_b'    => $siti,
                'participant_type' => 'user',
                'winner_id'        => null,
                'scheduled_at'     => '2026-07-10 10:00:00',
                'created_at'       => now(),
            ],
            // CP: Andi vs Dewi
            [
                'round_id'         => $cpRound,
                'participant_a'    => $andi,
                'participant_b'    => $dewi,
                'participant_type' => 'user',
                'winner_id'        => null,
                'scheduled_at'     => '2026-07-10 14:00:00',
                'created_at'       => now(),
            ],
            // UI/UX: Tim Canvas vs Tim Wireframe
            [
                'round_id'         => $uiuxRound,
                'participant_a'    => $teamCanvas,
                'participant_b'    => $teamWireframe,
                'participant_type' => 'team',
                'winner_id'        => null,
                'scheduled_at'     => '2026-08-05 10:00:00',
                'created_at'       => now(),
            ],
            // CTF: Tim CyberShield vs Tim ZeroDay
            [
                'round_id'         => $ctfRound,
                'participant_a'    => $teamCyber,
                'participant_b'    => $teamZero,
                'participant_type' => 'team',
                'winner_id'        => null,
                'scheduled_at'     => '2026-08-21 09:00:00',
                'created_at'       => now(),
            ],
            // IoT (Finished): Tim SmartHome vs Tim GreenTech -> SmartHome won
            [
                'round_id'         => $iotRound,
                'participant_a'    => $teamSmart,
                'participant_b'    => $teamGreen,
                'participant_type' => 'team',
                'winner_id'        => $teamSmart,
                'scheduled_at'     => '2025-10-02 10:00:00',
                'created_at'       => now(),
            ],
            // Mobile App: Tim TechWeaver vs Tim SwiftDev
            [
                'round_id'         => $mobileRound,
                'participant_a'    => $teamWeaver,
                'participant_b'    => $teamSwift,
                'participant_type' => 'team',
                'winner_id'        => null,
                'scheduled_at'     => '2026-07-05 10:00:00',
                'created_at'       => now(),
            ],
            // CTF Junior: Gunawan vs Hendra
            [
                'round_id'         => $ctfJrRound,
                'participant_a'    => $gunawan,
                'participant_b'    => $hendra,
                'participant_type' => 'user',
                'winner_id'        => null,
                'scheduled_at'     => '2026-05-15 10:00:00',
                'created_at'       => now(),
            ],
        ]);
    }
}
