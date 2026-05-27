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
        $uiux      = DB::table('competitions')->where('name', 'UI/UX Design Competition')->value('id');
        $ctf       = DB::table('competitions')->where('name', 'Siber Defense CTF 2026')->value('id');
        $ds        = DB::table('competitions')->where('name', 'Data Science Challenge 2026')->value('id');
        $iot       = DB::table('competitions')->where('name', 'IoT Innovation Cup 2025')->value('id');
        $mobile    = DB::table('competitions')->where('name', 'Mobile App Dev Arena')->value('id');
        $ctfJr     = DB::table('competitions')->where('name', 'Cyber Security CTF Junior')->value('id');

        $teamAlpha = DB::table('teams')->where('name', 'Tim Alpha')->value('id');
        $teamBeta  = DB::table('teams')->where('name', 'Tim Beta')->value('id');
        $teamGamma = DB::table('teams')->where('name', 'Tim Gamma')->value('id');
        $teamDelta = DB::table('teams')->where('name', 'Tim Delta')->value('id');
        $teamPixel = DB::table('teams')->where('name', 'Tim Pixel')->value('id');
        $teamCanvas = DB::table('teams')->where('name', 'Tim Canvas')->value('id');
        $teamWireframe = DB::table('teams')->where('name', 'Tim Wireframe')->value('id');
        $teamCyber = DB::table('teams')->where('name', 'Tim CyberShield')->value('id');
        $teamZero  = DB::table('teams')->where('name', 'Tim ZeroDay')->value('id');
        $teamSmart = DB::table('teams')->where('name', 'Tim SmartHome')->value('id');
        $teamGreen = DB::table('teams')->where('name', 'Tim GreenTech')->value('id');
        $teamWeaver = DB::table('teams')->where('name', 'Tim TechWeaver')->value('id');
        $teamSwift = DB::table('teams')->where('name', 'Tim SwiftDev')->value('id');

        $budi    = DB::table('users')->where('email', 'budi@gmail.com')->value('id');
        $siti    = DB::table('users')->where('email', 'siti@gmail.com')->value('id');
        $andi    = DB::table('users')->where('email', 'andi@gmail.com')->value('id');
        $dewi    = DB::table('users')->where('email', 'dewi@gmail.com')->value('id');
        $reza    = DB::table('users')->where('email', 'reza@gmail.com')->value('id');
        $dian    = DB::table('users')->where('email', 'dian@gmail.com')->value('id');
        $zaki    = DB::table('users')->where('email', 'zaki@gmail.com')->value('id');
        $eko     = DB::table('users')->where('email', 'eko@gmail.com')->value('id');
        $fitri   = DB::table('users')->where('email', 'fitri@gmail.com')->value('id');
        $gunawan = DB::table('users')->where('email', 'gunawan@gmail.com')->value('id');
        $hendra  = DB::table('users')->where('email', 'hendra@gmail.com')->value('id');
        $indah   = DB::table('users')->where('email', 'indah@gmail.com')->value('id');
        $lestari = DB::table('users')->where('email', 'lestari@gmail.com')->value('id');
        $nurul   = DB::table('users')->where('email', 'nurul@gmail.com')->value('id');
        $rian    = DB::table('users')->where('email', 'rian@gmail.com')->value('id');
        $taufik  = DB::table('users')->where('email', 'taufik@gmail.com')->value('id');

        DB::table('registrations')->insert([
            // 1. Hackathon (team)
            [
                'competition_id'   => $hackathon,
                'user_id'          => null,
                'team_id'          => $teamAlpha,
                'status'           => 'verified',
                'rejection_reason' => null,
                'payment_proof'    => 'proofs/alpha_bukti.jpg',
                'form_data'        => json_encode(['Github Profile' => 'https://github.com/budi-alpha']),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'competition_id'   => $hackathon,
                'user_id'          => null,
                'team_id'          => $teamBeta,
                'status'           => 'verified',
                'rejection_reason' => null,
                'payment_proof'    => 'proofs/alpha_bukti.jpg',
                'form_data'        => json_encode(['Github Profile' => 'https://github.com/andi-beta']),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'competition_id'   => $hackathon,
                'user_id'          => null,
                'team_id'          => $teamGamma,
                'status'           => 'verified',
                'rejection_reason' => null,
                'payment_proof'    => 'proofs/alpha_bukti.jpg',
                'form_data'        => json_encode(['Github Profile' => 'https://github.com/dian-gamma']),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'competition_id'   => $hackathon,
                'user_id'          => null,
                'team_id'          => $teamDelta,
                'status'           => 'verified',
                'rejection_reason' => null,
                'payment_proof'    => 'proofs/alpha_bukti.jpg',
                'form_data'        => json_encode(['Github Profile' => 'https://github.com/eko-delta']),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],

            // 2. CP (individual)
            [
                'competition_id'   => $cp,
                'user_id'          => $budi,
                'team_id'          => null,
                'status'           => 'verified',
                'rejection_reason' => null,
                'payment_proof'    => null,
                'form_data'        => json_encode(['University' => 'Universitas Indonesia']),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'competition_id'   => $cp,
                'user_id'          => $siti,
                'team_id'          => null,
                'status'           => 'verified',
                'rejection_reason' => null,
                'payment_proof'    => null,
                'form_data'        => json_encode(['University' => 'Institut Teknologi Bandung']),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'competition_id'   => $cp,
                'user_id'          => $reza,
                'team_id'          => null,
                'status'           => 'rejected',
                'rejection_reason' => 'Akun dalam status suspended.',
                'payment_proof'    => null,
                'form_data'        => json_encode(['University' => 'Universitas Gadjah Mada']),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'competition_id'   => $cp,
                'user_id'          => $andi,
                'team_id'          => null,
                'status'           => 'verified',
                'rejection_reason' => null,
                'payment_proof'    => null,
                'form_data'        => json_encode(['University' => 'Universitas Airlangga']),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'competition_id'   => $cp,
                'user_id'          => $dewi,
                'team_id'          => null,
                'status'           => 'verified',
                'rejection_reason' => null,
                'payment_proof'    => null,
                'form_data'        => json_encode(['University' => 'Universitas Diponegoro']),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],

            // 3. UI/UX (team)
            [
                'competition_id'   => $uiux,
                'user_id'          => null,
                'team_id'          => $teamPixel,
                'status'           => 'pending',
                'rejection_reason' => null,
                'payment_proof'    => null,
                'form_data'        => json_encode(['Link Portfolio' => 'https://behance.net/pixel']),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'competition_id'   => $uiux,
                'user_id'          => null,
                'team_id'          => $teamCanvas,
                'status'           => 'verified',
                'rejection_reason' => null,
                'payment_proof'    => 'proofs/alpha_bukti.jpg',
                'form_data'        => json_encode(['Link Portfolio' => 'https://behance.net/canvas']),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'competition_id'   => $uiux,
                'user_id'          => null,
                'team_id'          => $teamWireframe,
                'status'           => 'verified',
                'rejection_reason' => null,
                'payment_proof'    => 'proofs/alpha_bukti.jpg',
                'form_data'        => json_encode(['Link Portfolio' => 'https://behance.net/wireframe']),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],

            // 4. CTF (team)
            [
                'competition_id'   => $ctf,
                'user_id'          => null,
                'team_id'          => $teamCyber,
                'status'           => 'verified',
                'rejection_reason' => null,
                'payment_proof'    => 'proofs/alpha_bukti.jpg',
                'form_data'        => json_encode(['ID Discord' => 'cyber_shield#1337']),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'competition_id'   => $ctf,
                'user_id'          => null,
                'team_id'          => $teamZero,
                'status'           => 'verified',
                'rejection_reason' => null,
                'payment_proof'    => 'proofs/alpha_bukti.jpg',
                'form_data'        => json_encode(['ID Discord' => 'zeroday#9999']),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],

            // 5. Data Science (individual)
            [
                'competition_id'   => $ds,
                'user_id'          => $dian,
                'team_id'          => null,
                'status'           => 'verified',
                'rejection_reason' => null,
                'payment_proof'    => null,
                'form_data'        => json_encode(['Experience' => '1 year analyzing data']),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'competition_id'   => $ds,
                'user_id'          => $eko,
                'team_id'          => null,
                'status'           => 'verified',
                'rejection_reason' => null,
                'payment_proof'    => null,
                'form_data'        => json_encode(['Experience' => 'No experience, beginner']),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'competition_id'   => $ds,
                'user_id'          => $fitri,
                'team_id'          => null,
                'status'           => 'verified',
                'rejection_reason' => null,
                'payment_proof'    => null,
                'form_data'        => json_encode(['Experience' => 'Undergrad statistics student']),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],

            // 6. IoT (team)
            [
                'competition_id'   => $iot,
                'user_id'          => null,
                'team_id'          => $teamSmart,
                'status'           => 'verified',
                'rejection_reason' => null,
                'payment_proof'    => 'proofs/alpha_bukti.jpg',
                'form_data'        => json_encode(['Idea description' => 'Smart automated watering system']),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'competition_id'   => $iot,
                'user_id'          => null,
                'team_id'          => $teamGreen,
                'status'           => 'verified',
                'rejection_reason' => null,
                'payment_proof'    => 'proofs/alpha_bukti.jpg',
                'form_data'        => json_encode(['Idea description' => 'Eco-friendly solar powered sensor net']),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],

            // 7. Mobile App (team)
            [
                'competition_id'   => $mobile,
                'user_id'          => null,
                'team_id'          => $teamWeaver,
                'status'           => 'verified',
                'rejection_reason' => null,
                'payment_proof'    => 'proofs/alpha_bukti.jpg',
                'form_data'        => json_encode(['OS Target' => 'Android']),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'competition_id'   => $mobile,
                'user_id'          => null,
                'team_id'          => $teamSwift,
                'status'           => 'pending',
                'rejection_reason' => null,
                'payment_proof'    => null,
                'form_data'        => json_encode(['OS Target' => 'iOS']),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],

            // 8. Cyber Security CTF Junior (individual)
            [
                'competition_id'   => $ctfJr,
                'user_id'          => $gunawan,
                'team_id'          => null,
                'status'           => 'verified',
                'rejection_reason' => null,
                'payment_proof'    => null,
                'form_data'        => json_encode(['School Name' => 'SMK Negeri 1 Jakarta']),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'competition_id'   => $ctfJr,
                'user_id'          => $hendra,
                'team_id'          => null,
                'status'           => 'verified',
                'rejection_reason' => null,
                'payment_proof'    => null,
                'form_data'        => json_encode(['School Name' => 'SMA Negeri 3 Bandung']),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'competition_id'   => $ctfJr,
                'user_id'          => $indah,
                'team_id'          => null,
                'status'           => 'verified',
                'rejection_reason' => null,
                'payment_proof'    => null,
                'form_data'        => json_encode(['School Name' => 'SMK Taruna Bhakti']),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
        ]);
    }
}
