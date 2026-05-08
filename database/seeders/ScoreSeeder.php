<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScoreSeeder extends Seeder
{
    public function run(): void
    {
        $jeryko = DB::table('users')->where('email', 'jeryko@competehub.com')->value('id');
        $rico   = DB::table('users')->where('email', 'rico@competehub.com')->value('id');

        // Submission Tim Alpha (scored) — id pertama
        $submAlpha = DB::table('submissions')->where('file_path', 'submissions/alpha_penyisihan.zip')->value('id');
        // Submission Budi CP (scored) — id ketiga
        $submBudi  = DB::table('submissions')->where('file_path', 'submissions/budi_cp_solution.cpp')->value('id');

        DB::table('scores')->insert([
            // Tim Alpha — dinilai 2 juri → avg (85+90)/2 = 87.50
            [
                'submission_id' => $submAlpha,
                'user_id'       => $jeryko,
                'score'         => 85.00,
                'notes'         => 'Ide kreatif, implementasi cukup baik.',
                'scored_at'     => now(),
                'updated_at'    => now(),
            ],
            [
                'submission_id' => $submAlpha,
                'user_id'       => $rico,
                'score'         => 90.00,
                'notes'         => 'Presentasi menarik, kode bersih.',
                'scored_at'     => now(),
                'updated_at'    => now(),
            ],
            // Budi CP — dinilai 1 juri
            [
                'submission_id' => $submBudi,
                'user_id'       => $jeryko,
                'score'         => 95.00,
                'notes'         => 'Solusi optimal, semua test case passed.',
                'scored_at'     => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}
