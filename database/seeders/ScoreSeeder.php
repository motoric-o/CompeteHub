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
        $hassan = DB::table('users')->where('email', 'hassan@competehub.com')->value('id');
        $lina   = DB::table('users')->where('email', 'lina@competehub.com')->value('id');

        // Fetch submissions
        $subAlpha   = DB::table('submissions')->where('file_path', 'submissions/alpha_penyisihan.zip')->value('id');
        $subBeta    = DB::table('submissions')->where('file_path', 'submissions/beta_penyisihan.zip')->value('id');
        $subGamma   = DB::table('submissions')->where('file_path', 'submissions/gamma_penyisihan.zip')->value('id');
        $subBudi    = DB::table('submissions')->where('file_path', 'submissions/budi_cp_solution.cpp')->value('id');
        $subSiti    = DB::table('submissions')->where('file_path', 'submissions/siti_cp_solution.cpp')->value('id');
        $subCanvas  = DB::table('submissions')->where('file_path', 'submissions/canvas_uiux.zip')->value('id');
        $subCyber   = DB::table('submissions')->where('file_path', 'submissions/cybershield_ctf.zip')->value('id');
        $subDian    = DB::table('submissions')->where('file_path', 'submissions/dian_ds_model.ipynb')->value('id');
        $subEko     = DB::table('submissions')->where('file_path', 'submissions/eko_ds_model.ipynb')->value('id');
        $subSmart   = DB::table('submissions')->where('file_path', 'submissions/smarthome_iot.zip')->value('id');
        $subGreen   = DB::table('submissions')->where('file_path', 'submissions/greentech_iot.zip')->value('id');
        $subWeaver  = DB::table('submissions')->where('file_path', 'submissions/techweaver_mobile.zip')->value('id');
        $subGunawan = DB::table('submissions')->where('file_path', 'submissions/gunawan_ctfjr.zip')->value('id');

        DB::table('scores')->insert([
            // Alpha Hackathon
            // ['submission_id' => $subAlpha, 'user_id' => $jeryko, 'score' => 85.00, 'notes' => 'Ide kreatif, implementasi cukup baik.', 'scored_at' => now(), 'updated_at' => now()],
            ['submission_id' => $subAlpha, 'user_id' => $rico,   'score' => 90.00, 'notes' => 'Presentasi menarik, kode bersih.', 'scored_at' => now(), 'updated_at' => now()],

            // Beta Hackathon
            // ['submission_id' => $subBeta,  'user_id' => $jeryko, 'score' => 80.00, 'notes' => 'Solusi standar tapi berjalan lancar.', 'scored_at' => now(), 'updated_at' => now()],
            ['submission_id' => $subBeta,  'user_id' => $rico,   'score' => 82.00, 'notes' => 'UI rapi, tapi backend masih sederhana.', 'scored_at' => now(), 'updated_at' => now()],

            // Gamma Hackathon
            // ['submission_id' => $subGamma, 'user_id' => $jeryko, 'score' => 78.00, 'notes' => 'Fitur dasar lengkap, performa perlu optimasi.', 'scored_at' => now(), 'updated_at' => now()],
            ['submission_id' => $subGamma, 'user_id' => $rico,   'score' => 81.00, 'notes' => 'Desain modern, penulisan kode terstruktur.', 'scored_at' => now(), 'updated_at' => now()],

            // Budi CP
            ['submission_id' => $subBudi,  'user_id' => $jeryko, 'score' => 95.00, 'notes' => 'Solusi optimal, semua test case passed.', 'scored_at' => now(), 'updated_at' => now()],

            // Siti CP
            ['submission_id' => $subSiti,  'user_id' => $jeryko, 'score' => 86.00, 'notes' => 'Kompleksitas waktu cukup baik.', 'scored_at' => now(), 'updated_at' => now()],
            ['submission_id' => $subSiti,  'user_id' => $hassan, 'score' => 90.00, 'notes' => 'Kode ringkas dan efisien.', 'scored_at' => now(), 'updated_at' => now()],

            // Canvas UI/UX
            ['submission_id' => $subCanvas, 'user_id' => $rico,   'score' => 92.00, 'notes' => 'Desain premium dan user flow sangat intuitive.', 'scored_at' => now(), 'updated_at' => now()],
            ['submission_id' => $subCanvas, 'user_id' => $lina,   'score' => 90.00, 'notes' => 'Aesthetic luar biasa, typography konsisten.', 'scored_at' => now(), 'updated_at' => now()],

            // Cyber CTF
            ['submission_id' => $subCyber, 'user_id' => $jeryko, 'score' => 340.00, 'notes' => 'Berhasil memecahkan tantangan cryptografi tersulit.', 'scored_at' => now(), 'updated_at' => now()],
            ['submission_id' => $subCyber, 'user_id' => $hassan, 'score' => 360.00, 'notes' => 'Paling cepat menyelesaikan tantangan web exploitation.', 'scored_at' => now(), 'updated_at' => now()],

            // Dian DS
            ['submission_id' => $subDian,  'user_id' => $hassan, 'score' => 95.00, 'notes' => 'Akurasi model sangat tinggi, visualisasi data lengkap.', 'scored_at' => now(), 'updated_at' => now()],
            ['submission_id' => $subDian,  'user_id' => $lina,   'score' => 93.00, 'notes' => 'Analisis metodologi sangat komprehensif.', 'scored_at' => now(), 'updated_at' => now()],

            // Eko DS
            ['submission_id' => $subEko,   'user_id' => $hassan, 'score' => 90.00, 'notes' => 'Model stabil, feature engineering cerdas.', 'scored_at' => now(), 'updated_at' => now()],
            ['submission_id' => $subEko,   'user_id' => $lina,   'score' => 89.00, 'notes' => 'Metrik evaluasi dijelaskan dengan baik.', 'scored_at' => now(), 'updated_at' => now()],

            // SmartHome IoT
            ['submission_id' => $subSmart, 'user_id' => $rico,   'score' => 95.00, 'notes' => 'Integrasi hardware & cloud sangat mulus.', 'scored_at' => now(), 'updated_at' => now()],
            ['submission_id' => $subSmart, 'user_id' => $hassan, 'score' => 92.00, 'notes' => 'Alat berfungsi 100% saat demo langsung.', 'scored_at' => now(), 'updated_at' => now()],
            ['submission_id' => $subSmart, 'user_id' => $lina,   'score' => 92.00, 'notes' => 'Bermanfaat untuk efisiensi energi rumah tangga.', 'scored_at' => now(), 'updated_at' => now()],

            // GreenTech IoT
            ['submission_id' => $subGreen, 'user_id' => $rico,   'score' => 85.00, 'notes' => 'Konsep bagus, tapi sensor sering lag.', 'scored_at' => now(), 'updated_at' => now()],
            ['submission_id' => $subGreen, 'user_id' => $hassan, 'score' => 87.00, 'notes' => 'Model 3D casing rapi dan fungsional.', 'scored_at' => now(), 'updated_at' => now()],
            ['submission_id' => $subGreen, 'user_id' => $lina,   'score' => 86.00, 'notes' => 'Potensi komersial tinggi.', 'scored_at' => now(), 'updated_at' => now()],

            // TechWeaver Mobile
            ['submission_id' => $subWeaver, 'user_id' => $rico,   'score' => 88.00, 'notes' => 'Navigasi aplikasi responsif.', 'scored_at' => now(), 'updated_at' => now()],
            ['submission_id' => $subWeaver, 'user_id' => $hassan, 'score' => 92.00, 'notes' => 'Arsitektur MVVM bersih dan teruji.', 'scored_at' => now(), 'updated_at' => now()],
            ['submission_id' => $subWeaver, 'user_id' => $lina,   'score' => 90.00, 'notes' => 'Desain sesuai panduan material design.', 'scored_at' => now(), 'updated_at' => now()],

            // Gunawan CTF Jr
            ['submission_id' => $subGunawan, 'user_id' => $jeryko, 'score' => 94.00, 'notes' => 'Sangat hebat untuk anak tingkat sekolah.', 'scored_at' => now(), 'updated_at' => now()],
            ['submission_id' => $subGunawan, 'user_id' => $lina,   'score' => 90.00, 'notes' => 'Menyelesaikan tantangan reverser dengan cepat.', 'scored_at' => now(), 'updated_at' => now()],
        ]);
    }
}
