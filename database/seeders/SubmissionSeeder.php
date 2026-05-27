<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubmissionSeeder extends Seeder
{
    public function run(): void
    {
        $hackathon  = DB::table('competitions')->where('name', 'Hackathon Nasional 2025')->value('id');
        $cp         = DB::table('competitions')->where('name', 'Competitive Programming Cup')->value('id');

        $penyisihan = DB::table('rounds')->where('name', 'Penyisihan')->value('id');
        $babakUtama = DB::table('rounds')->where('name', 'Babak Utama')->value('id');
        $hackathon = DB::table('competitions')->where('name', 'Hackathon Nasional 2025')->value('id');
        $cp        = DB::table('competitions')->where('name', 'Competitive Programming Cup')->value('id');
        $uiux      = DB::table('competitions')->where('name', 'UI/UX Design Competition')->value('id');
        $ctf       = DB::table('competitions')->where('name', 'Siber Defense CTF 2026')->value('id');
        $ds        = DB::table('competitions')->where('name', 'Data Science Challenge 2026')->value('id');
        $iot       = DB::table('competitions')->where('name', 'IoT Innovation Cup 2025')->value('id');
        $mobile    = DB::table('competitions')->where('name', 'Mobile App Dev Arena')->value('id');
        $ctfJr     = DB::table('competitions')->where('name', 'Cyber Security CTF Junior')->value('id');

        $penyisihan = DB::table('rounds')->where('name', 'Penyisihan')->where('competition_id', $hackathon)->value('id');
        $babakUtama = DB::table('rounds')->where('name', 'Babak Utama')->where('competition_id', $cp)->value('id');
        $uiuxRound  = DB::table('rounds')->where('name', 'Penyisihan Portofolio')->where('competition_id', $uiux)->value('id');
        $ctfRound   = DB::table('rounds')->where('name', 'Quals Jeopardy')->where('competition_id', $ctf)->value('id');
        $dsRound    = DB::table('rounds')->where('name', 'Kaggle Phase')->where('competition_id', $ds)->value('id');
        $iotRound   = DB::table('rounds')->where('name', 'Proposal Submission')->where('competition_id', $iot)->value('id');
        $mobileRound = DB::table('rounds')->where('name', 'App Prototype')->where('competition_id', $mobile)->value('id');
        $ctfJrRound = DB::table('rounds')->where('name', 'Online CTF Quals')->where('competition_id', $ctfJr)->value('id');

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

        $budi    = DB::table('users')->where('email', 'budi@gmail.com')->value('id');
        $siti    = DB::table('users')->where('email', 'siti@gmail.com')->value('id');
        $andi    = DB::table('users')->where('email', 'andi@gmail.com')->value('id');
        $dian    = DB::table('users')->where('email', 'dian@gmail.com')->value('id');
        $eko     = DB::table('users')->where('email', 'eko@gmail.com')->value('id');
        $fitri   = DB::table('users')->where('email', 'fitri@gmail.com')->value('id');
        $gunawan = DB::table('users')->where('email', 'gunawan@gmail.com')->value('id');
        $hendra  = DB::table('users')->where('email', 'hendra@gmail.com')->value('id');

        DB::table('submissions')->insert([
            // 1. Hackathon Penyisihan
            [
                'competition_id' => $hackathon,
                'round_id'       => $penyisihan,
                'user_id'        => null,
                'team_id'        => $teamAlpha,
                'file_path'      => 'submissions/alpha_penyisihan.zip',
                'file_type'      => 'application/zip',
                'file_size'      => 2048000,
                'submitted_at'   => '2026-06-01 14:30:00',
                'final_score'    => 87.50,
                'status'         => 'scored',
                'revision_count' => 0,
                'time_bonus'     => 5.00,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'competition_id' => $hackathon,
                'round_id'       => $penyisihan,
                'user_id'        => null,
                'team_id'        => $teamBeta,
                'file_path'      => 'submissions/beta_penyisihan.zip',
                'file_type'      => 'application/zip',
                'file_size'      => 1536000,
                'submitted_at'   => '2026-06-01 16:00:00',
                'final_score'    => 81.00,
                'status'         => 'scored',
                'revision_count' => 2,
                'time_bonus'     => 0.00,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'competition_id' => $hackathon,
                'round_id'       => $penyisihan,
                'user_id'        => null,
                'team_id'        => $teamGamma,
                'file_path'      => 'submissions/gamma_penyisihan.zip',
                'file_type'      => 'application/zip',
                'file_size'      => 1820000,
                'submitted_at'   => '2026-06-01 15:45:00',
                'final_score'    => 79.50,
                'status'         => 'scored',
                'revision_count' => 1,
                'time_bonus'     => 1.00,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'competition_id' => $hackathon,
                'round_id'       => $penyisihan,
                'user_id'        => null,
                'team_id'        => $teamDelta,
                'file_path'      => 'submissions/delta_penyisihan.zip',
                'file_type'      => 'application/zip',
                'file_size'      => 2100000,
                'submitted_at'   => '2026-06-01 19:15:00',
                'final_score'    => null,
                'status'         => 'submitted',
                'revision_count' => 0,
                'time_bonus'     => 0.00,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],

            // 2. CP Babak Utama
            [
                'competition_id' => $cp,
                'round_id'       => $babakUtama,
                'user_id'        => $budi,
                'team_id'        => null,
                'file_path'      => 'submissions/budi_cp_solution.cpp',
                'file_type'      => 'text/x-c++src',
                'file_size'      => 4500,
                'submitted_at'   => '2026-07-10 11:00:00',
                'final_score'    => 95.00,
                'status'         => 'scored',
                'revision_count' => 1,
                'time_bonus'     => 0.00,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'competition_id' => $cp,
                'round_id'       => $babakUtama,
                'user_id'        => $siti,
                'team_id'        => null,
                'file_path'      => 'submissions/siti_cp_solution.cpp',
                'file_type'      => 'text/x-c++src',
                'file_size'      => 3800,
                'submitted_at'   => '2026-07-10 11:20:00',
                'final_score'    => 88.00,
                'status'         => 'scored',
                'revision_count' => 0,
                'time_bonus'     => 4.00,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'competition_id' => $cp,
                'round_id'       => $babakUtama,
                'user_id'        => $andi,
                'team_id'        => null,
                'file_path'      => 'submissions/andi_cp_solution.cpp',
                'file_type'      => 'text/x-c++src',
                'file_size'      => 4100,
                'submitted_at'   => '2026-07-10 16:30:00',
                'final_score'    => null,
                'status'         => 'submitted',
                'revision_count' => 0,
                'time_bonus'     => 0.00,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],

            // 3. UI/UX Penyisihan Portofolio
            [
                'competition_id' => $uiux,
                'round_id'       => $uiuxRound,
                'user_id'        => null,
                'team_id'        => $teamCanvas,
                'file_path'      => 'submissions/canvas_uiux.zip',
                'file_type'      => 'application/zip',
                'file_size'      => 8500000,
                'submitted_at'   => '2026-08-04 14:00:00',
                'final_score'    => 91.00,
                'status'         => 'scored',
                'revision_count' => 0,
                'time_bonus'     => 0.00,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'competition_id' => $uiux,
                'round_id'       => $uiuxRound,
                'user_id'        => null,
                'team_id'        => $teamWireframe,
                'file_path'      => 'submissions/wireframe_uiux.zip',
                'file_type'      => 'application/zip',
                'file_size'      => 6200000,
                'submitted_at'   => '2026-08-05 09:30:00',
                'final_score'    => null,
                'status'         => 'submitted',
                'revision_count' => 0,
                'time_bonus'     => 0.00,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],

            // 4. CTF Quals Jeopardy
            [
                'competition_id' => $ctf,
                'round_id'       => $ctfRound,
                'user_id'        => null,
                'team_id'        => $teamCyber,
                'file_path'      => 'submissions/cybershield_ctf.zip',
                'file_type'      => 'application/zip',
                'file_size'      => 500000,
                'submitted_at'   => '2026-08-20 15:00:00',
                'final_score'    => 350.00,
                'status'         => 'scored',
                'revision_count' => 0,
                'time_bonus'     => 10.00,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'competition_id' => $ctf,
                'round_id'       => $ctfRound,
                'user_id'        => null,
                'team_id'        => $teamZero,
                'file_path'      => 'submissions/zeroday_ctf.zip',
                'file_type'      => 'application/zip',
                'file_size'      => 450000,
                'submitted_at'   => '2026-08-20 18:22:00',
                'final_score'    => null,
                'status'         => 'submitted',
                'revision_count' => 0,
                'time_bonus'     => 0.00,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],

            // 5. Data Science Kaggle Phase
            [
                'competition_id' => $ds,
                'round_id'       => $dsRound,
                'user_id'        => $dian,
                'team_id'        => null,
                'file_path'      => 'submissions/dian_ds_model.ipynb',
                'file_type'      => 'application/x-ipynb+json',
                'file_size'      => 1200000,
                'submitted_at'   => '2026-05-25 10:00:00',
                'final_score'    => 94.00,
                'status'         => 'scored',
                'revision_count' => 0,
                'time_bonus'     => 0.00,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'competition_id' => $ds,
                'round_id'       => $dsRound,
                'user_id'        => $eko,
                'team_id'        => null,
                'file_path'      => 'submissions/eko_ds_model.ipynb',
                'file_type'      => 'application/x-ipynb+json',
                'file_size'      => 1100000,
                'submitted_at'   => '2026-05-26 14:00:00',
                'final_score'    => 89.50,
                'status'         => 'scored',
                'revision_count' => 1,
                'time_bonus'     => 0.00,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'competition_id' => $ds,
                'round_id'       => $dsRound,
                'user_id'        => $fitri,
                'team_id'        => null,
                'file_path'      => 'submissions/fitri_ds_model.ipynb',
                'file_type'      => 'application/x-ipynb+json',
                'file_size'      => 980000,
                'submitted_at'   => '2026-05-26 19:30:00',
                'final_score'    => null,
                'status'         => 'submitted',
                'revision_count' => 0,
                'time_bonus'     => 0.00,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],

            // 6. IoT Proposal Submission
            [
                'competition_id' => $iot,
                'round_id'       => $iotRound,
                'user_id'        => null,
                'team_id'        => $teamSmart,
                'file_path'      => 'submissions/smarthome_iot.zip',
                'file_type'      => 'application/zip',
                'file_size'      => 10500000,
                'submitted_at'   => '2025-10-03 14:00:00',
                'final_score'    => 93.00,
                'status'         => 'scored',
                'revision_count' => 0,
                'time_bonus'     => 0.00,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'competition_id' => $iot,
                'round_id'       => $iotRound,
                'user_id'        => null,
                'team_id'        => $teamGreen,
                'file_path'      => 'submissions/greentech_iot.zip',
                'file_type'      => 'application/zip',
                'file_size'      => 9800000,
                'submitted_at'   => '2025-10-04 11:20:00',
                'final_score'    => 86.00,
                'status'         => 'scored',
                'revision_count' => 0,
                'time_bonus'     => 0.00,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],

            // 7. Mobile App Prototype
            [
                'competition_id' => $mobile,
                'round_id'       => $mobileRound,
                'user_id'        => null,
                'team_id'        => $teamWeaver,
                'file_path'      => 'submissions/techweaver_mobile.zip',
                'file_type'      => 'application/zip',
                'file_size'      => 15000000,
                'submitted_at'   => '2026-07-04 15:00:00',
                'final_score'    => 90.00,
                'status'         => 'scored',
                'revision_count' => 0,
                'time_bonus'     => 2.00,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],

            // 8. Cyber Security CTF Junior Online CTF Quals
            [
                'competition_id' => $ctfJr,
                'round_id'       => $ctfJrRound,
                'user_id'        => $gunawan,
                'team_id'        => null,
                'file_path'      => 'submissions/gunawan_ctfjr.zip',
                'file_type'      => 'application/zip',
                'file_size'      => 800000,
                'submitted_at'   => '2026-05-10 12:00:00',
                'final_score'    => 92.00,
                'status'         => 'scored',
                'revision_count' => 0,
                'time_bonus'     => 5.00,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'competition_id' => $ctfJr,
                'round_id'       => $ctfJrRound,
                'user_id'        => $hendra,
                'team_id'        => null,
                'file_path'      => 'submissions/hendra_ctfjr.zip',
                'file_type'      => 'application/zip',
                'file_size'      => 750000,
                'submitted_at'   => '2026-05-12 16:30:00',
                'final_score'    => null,
                'status'         => 'submitted',
                'revision_count' => 0,
                'time_bonus'     => 0.00,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}
