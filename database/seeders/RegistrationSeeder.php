<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegistrationSeeder extends Seeder
{
    public function run(): void
    {
        $hackathon = DB::table('competitions')->where('name', 'Hackathon Nasional 2025')->value('id');
        $cp        = DB::table('competitions')->where('name', 'Competitive Programming Cup')->value('id');

        $teamAlpha = DB::table('teams')->where('name', 'Tim Alpha')->value('id');
        $teamBeta  = DB::table('teams')->where('name', 'Tim Beta')->value('id');

        $budi = DB::table('users')->where('email', 'budi@gmail.com')->value('id');
        $siti = DB::table('users')->where('email', 'siti@gmail.com')->value('id');
        $andi = DB::table('users')->where('email', 'andi@gmail.com')->value('id');
        $reza = DB::table('users')->where('email', 'reza@gmail.com')->value('id');

        DB::table('registrations')->insert([
            // Hackathon — registrasi TIM
            [
                'competition_id'   => $hackathon,
                'user_id'          => null,
                'team_id'          => $teamAlpha,
                'status'           => 'payment_ok',       // sudah lolos semua handler
                'rejection_reason' => null,
                'payment_proof'    => 'proofs/alpha_bukti.jpg',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'competition_id'   => $hackathon,
                'user_id'          => null,
                'team_id'          => $teamBeta,
                'status'           => 'documents_ok',     // menunggu verifikasi pembayaran
                'rejection_reason' => null,
                'payment_proof'    => null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            // CP — registrasi INDIVIDU
            [
                'competition_id'   => $cp,
                'user_id'          => $budi,
                'team_id'          => null,
                'status'           => 'payment_ok',       // gratis, langsung lolos
                'rejection_reason' => null,
                'payment_proof'    => null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'competition_id'   => $cp,
                'user_id'          => $siti,
                'team_id'          => null,
                'status'           => 'account_ok',       // lolos AccountStatusHandler
                'rejection_reason' => null,
                'payment_proof'    => null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'competition_id'   => $cp,
                'user_id'          => $reza,
                'team_id'          => null,
                'status'           => 'rejected',         // Reza suspended → ditolak AccountStatusHandler
                'rejection_reason' => 'Akun dalam status suspended.',
                'payment_proof'    => null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
        ]);
    }
}
