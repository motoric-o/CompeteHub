<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $registrations = DB::table('registrations')->get();

        $payments = [];

        foreach ($registrations as $reg) {
            $comp = DB::table('competitions')->where('id', $reg->competition_id)->first();
            if (!$comp) {
                continue;
            }

            if ($comp->registration_fee == 0) {
                $payments[] = [
                    'registration_id' => $reg->id,
                    'amount'          => 0,
                    'status'          => 'free',
                    'proof_path'      => null,
                    'verified_at'     => null,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];
            } else {
                $isPaid = in_array($reg->status, ['verified', 'payment_ok']);
                
                $payments[] = [
                    'registration_id' => $reg->id,
                    'amount'          => $comp->registration_fee,
                    'status'          => $isPaid ? 'paid' : 'unpaid',
                    'proof_path'      => $isPaid ? 'proofs/alpha_bukti.jpg' : null,
                    'verified_at'     => $isPaid ? now() : null,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];
            }
        }

        if (!empty($payments)) {
            DB::table('payments')->insert($payments);
        }
    }
}
