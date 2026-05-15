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

        $teamAlpha = DB::table('teams')->where('name', 'Tim Alpha')->value('id');
        $teamBeta  = DB::table('teams')->where('name', 'Tim Beta')->value('id');

        $budi = DB::table('users')->where('email', 'budi@gmail.com')->value('id');

        DB::table('submissions')->insert([
            // Tim Alpha — Hackathon Penyisihan (scored, submit pertama, time bonus 5 karena paling cepat)
            [
                'competition_id' => $hackathon,
                'round_id'       => $penyisihan,
                'user_id'        => null,
                'team_id'        => $teamAlpha,
                'file_path'      => 'submissions/alpha_penyisihan.zip',
                'file_type'      => 'application/zip',
                'file_size'      => 2048000,
                'submitted_at'   => '2025-06-01 14:30:00',
                'final_score'    => 87.50,
                'status'         => 'scored',
                'revision_count' => 0,
                'time_bonus'     => 5.00,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            // Tim Beta — Hackathon Penyisihan (submitted, 2 revisions → penalty)
            [
                'competition_id' => $hackathon,
                'round_id'       => $penyisihan,
                'user_id'        => null,
                'team_id'        => $teamBeta,
                'file_path'      => 'submissions/beta_penyisihan.zip',
                'file_type'      => 'application/zip',
                'file_size'      => 1536000,
                'submitted_at'   => '2025-06-01 16:00:00',
                'final_score'    => null,
                'status'         => 'submitted',
                'revision_count' => 2,
                'time_bonus'     => 0.00,  // Revisi = 0 bonus
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            // Budi — CP Babak Utama (scored, revisi 1x)
            [
                'competition_id' => $cp,
                'round_id'       => $babakUtama,
                'user_id'        => $budi,
                'team_id'        => null,
                'file_path'      => 'submissions/budi_cp_solution.cpp',
                'file_type'      => 'text/x-c++src',
                'file_size'      => 4500,
                'submitted_at'   => '2025-07-10 11:00:00',
                'final_score'    => 95.00,
                'status'         => 'scored',
                'revision_count' => 1,
                'time_bonus'     => 0.00,  // Revisi = 0 bonus
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}
