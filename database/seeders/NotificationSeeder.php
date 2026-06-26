<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $budi    = DB::table('users')->where('email', 'budi@gmail.com')->value('id');
        $siti    = DB::table('users')->where('email', 'siti@gmail.com')->value('id');
        $andi    = DB::table('users')->where('email', 'andi@gmail.com')->value('id');
        $reza    = DB::table('users')->where('email', 'reza@gmail.com')->value('id');
        $dian    = DB::table('users')->where('email', 'dian@gmail.com')->value('id');
        $eko     = DB::table('users')->where('email', 'eko@gmail.com')->value('id');
        $fitri   = DB::table('users')->where('email', 'fitri@gmail.com')->value('id');
        $gunawan = DB::table('users')->where('email', 'gunawan@gmail.com')->value('id');
        $zaki    = DB::table('users')->where('email', 'zaki@gmail.com')->value('id');
        $lestari = DB::table('users')->where('email', 'lestari@gmail.com')->value('id');
        $rian    = DB::table('users')->where('email', 'rian@gmail.com')->value('id');

        DB::table('notifications')->insert([
            // Budi
            [
                'user_id'    => $budi,
                'type'       => 'registration_approved',
                'title'      => 'Pendaftaran Diterima',
                'body'       => 'Pendaftaran Tim Alpha untuk Hackathon Nasional 2025 telah disetujui.',
                'channel'    => 'in_app',
                'is_read'    => true,
                'sent_at'    => now(),
                'created_at' => now(),
            ],
            [
                'user_id'    => $budi,
                'type'       => 'score_update',
                'title'      => 'Nilai Baru',
                'body'       => 'Submisi Tim Alpha pada babak Penyisihan telah dinilai. Skor: 87.50',
                'channel'    => 'email',
                'is_read'    => false,
                'sent_at'    => now(),
                'created_at' => now(),
            ],
            // Andi
            [
                'user_id'    => $andi,
                'type'       => 'registration_approved',
                'title'      => 'Pendaftaran Diterima',
                'body'       => 'Pendaftaran Tim Beta untuk Hackathon Nasional 2025 telah disetujui.',
                'channel'    => 'in_app',
                'is_read'    => false,
                'sent_at'    => now(),
                'created_at' => now(),
            ],
            // Reza
            [
                'user_id'    => $reza,
                'type'       => 'registration_rejected',
                'title'      => 'Pendaftaran Ditolak',
                'body'       => 'Pendaftaran Anda untuk Competitive Programming Cup ditolak. Alasan: Akun dalam status suspended.',
                'channel'    => 'email',
                'is_read'    => false,
                'sent_at'    => now(),
                'created_at' => now(),
            ],
            // Siti
            [
                'user_id'    => $siti,
                'type'       => 'document_reminder',
                'title'      => 'Dokumen Belum Lengkap',
                'body'       => 'Mohon lengkapi dokumen pendaftaran Anda untuk Competitive Programming Cup.',
                'channel'    => 'push',
                'is_read'    => false,
                'sent_at'    => null,
                'created_at' => now(),
            ],
            // Dian
            [
                'user_id'    => $dian,
                'type'       => 'registration_approved',
                'title'      => 'Pendaftaran Diterima',
                'body'       => 'Pendaftaran Anda untuk Data Science Challenge 2026 telah disetujui.',
                'channel'    => 'in_app',
                'is_read'    => true,
                'sent_at'    => now(),
                'created_at' => now(),
            ],
            [
                'user_id'    => $dian,
                'type'       => 'score_update',
                'title'      => 'Nilai Baru Terbit',
                'body'       => 'Submisi Anda di babak Kaggle Phase telah dinilai. Skor: 94.00',
                'channel'    => 'email',
                'is_read'    => false,
                'sent_at'    => now(),
                'created_at' => now(),
            ],
            // Eko
            [
                'user_id'    => $eko,
                'type'       => 'score_update',
                'title'      => 'Nilai Baru Terbit',
                'body'       => 'Submisi Anda di babak Kaggle Phase telah dinilai. Skor: 89.50',
                'channel'    => 'in_app',
                'is_read'    => false,
                'sent_at'    => now(),
                'created_at' => now(),
            ],
            // Fitri
            [
                'user_id'    => $fitri,
                'type'       => 'registration_approved',
                'title'      => 'Pendaftaran Diterima',
                'body'       => 'Pendaftaran Anda untuk Data Science Challenge 2026 telah disetujui.',
                'channel'    => 'in_app',
                'is_read'    => false,
                'sent_at'    => now(),
                'created_at' => now(),
            ],
            // Gunawan
            [
                'user_id'    => $gunawan,
                'type'       => 'score_update',
                'title'      => 'Nilai Baru',
                'body'       => 'Submisi Anda pada babak Online CTF Quals telah dinilai. Skor: 92.00',
                'channel'    => 'in_app',
                'is_read'    => false,
                'sent_at'    => now(),
                'created_at' => now(),
            ],
            // Zaki
            [
                'user_id'    => $zaki,
                'type'       => 'registration_approved',
                'title'      => 'Pendaftaran Diterima',
                'body'       => 'Pendaftaran Tim Canvas untuk UI/UX Design Competition telah disetujui.',
                'channel'    => 'in_app',
                'is_read'    => true,
                'sent_at'    => now(),
                'created_at' => now(),
            ],
            // Lestari
            [
                'user_id'    => $lestari,
                'type'       => 'score_update',
                'title'      => 'Evaluasi Selesai',
                'body'       => 'Submisi Tim SmartHome untuk IoT Innovation Cup 2025 telah dinilai. Skor: 93.00',
                'channel'    => 'email',
                'is_read'    => true,
                'sent_at'    => now(),
                'created_at' => now(),
            ],
            // Rian
            [
                'user_id'    => $rian,
                'type'       => 'registration_approved',
                'title'      => 'Pendaftaran Mobile App',
                'body'       => 'Pendaftaran Tim TechWeaver untuk Mobile App Dev Arena disetujui.',
                'channel'    => 'in_app',
                'is_read'    => false,
                'sent_at'    => now(),
                'created_at' => now(),
            ],
        ]);
    }
}
