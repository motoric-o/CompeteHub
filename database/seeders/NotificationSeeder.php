<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $budi = DB::table('users')->where('email', 'budi@gmail.com')->value('id');
        $siti = DB::table('users')->where('email', 'siti@gmail.com')->value('id');
        $andi = DB::table('users')->where('email', 'andi@gmail.com')->value('id');
        $reza = DB::table('users')->where('email', 'reza@gmail.com')->value('id');

        DB::table('notifications')->insert([
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
        ]);
    }
}
