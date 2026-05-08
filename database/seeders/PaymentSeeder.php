<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $registrations = DB::table('registrations')->orderBy('id')->pluck('id');

        // Reg 1 = Tim Alpha (Hackathon, payment_ok) → sudah bayar
        $regAlpha = $registrations[0];
        // Reg 2 = Tim Beta (Hackathon, documents_ok) → belum bayar
        $regBeta  = $registrations[1];
        // Reg 3 = Budi (CP, payment_ok) → gratis
        $regBudi  = $registrations[2];
        // Reg 4 = Siti (CP, account_ok) → belum bayar
        $regSiti  = $registrations[3];

        DB::table('payments')->insert([
            [
                'registration_id' => $regAlpha,
                'amount'          => 50000,
                'status'          => 'paid',
                'proof_path'      => 'proofs/alpha_bukti.jpg',
                'verified_at'     => now(),
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'registration_id' => $regBeta,
                'amount'          => 50000,
                'status'          => 'unpaid',
                'proof_path'      => null,
                'verified_at'     => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'registration_id' => $regBudi,
                'amount'          => 0,
                'status'          => 'free',    // CP gratis
                'proof_path'      => null,
                'verified_at'     => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'registration_id' => $regSiti,
                'amount'          => 0,
                'status'          => 'unpaid',
                'proof_path'      => null,
                'verified_at'     => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ]);
    }
}
