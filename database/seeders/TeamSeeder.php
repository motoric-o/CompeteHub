<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $hackathon = DB::table('competitions')->where('name', 'Hackathon Nasional 2025')->value('id');
        $uiux      = DB::table('competitions')->where('name', 'UI/UX Design Competition')->value('id');
        $ctf       = DB::table('competitions')->where('name', 'Siber Defense CTF 2026')->value('id');
        $iot       = DB::table('competitions')->where('name', 'IoT Innovation Cup 2025')->value('id');
        $mobile    = DB::table('competitions')->where('name', 'Mobile App Dev Arena')->value('id');

        $budi    = DB::table('users')->where('email', 'budi@gmail.com')->value('id');
        $siti    = DB::table('users')->where('email', 'siti@gmail.com')->value('id');
        $andi    = DB::table('users')->where('email', 'andi@gmail.com')->value('id');
        $dian    = DB::table('users')->where('email', 'dian@gmail.com')->value('id');
        $eko     = DB::table('users')->where('email', 'eko@gmail.com')->value('id');
        $zaki    = DB::table('users')->where('email', 'zaki@gmail.com')->value('id');
        $fitri   = DB::table('users')->where('email', 'fitri@gmail.com')->value('id');
        $gunawan = DB::table('users')->where('email', 'gunawan@gmail.com')->value('id');
        $indah   = DB::table('users')->where('email', 'indah@gmail.com')->value('id');
        $lestari = DB::table('users')->where('email', 'lestari@gmail.com')->value('id');
        $nurul   = DB::table('users')->where('email', 'nurul@gmail.com')->value('id');
        $rian    = DB::table('users')->where('email', 'rian@gmail.com')->value('id');
        $taufik  = DB::table('users')->where('email', 'taufik@gmail.com')->value('id');

        DB::table('teams')->insert([
            // Hackathon
            [
                'competition_id' => $hackathon,
                'user_id'        => $budi,   // kapten
                'name'           => 'Tim Alpha',
                'invite_code'    => Str::upper(Str::random(8)),
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'competition_id' => $hackathon,
                'user_id'        => $andi,   // kapten
                'name'           => 'Tim Beta',
                'invite_code'    => Str::upper(Str::random(8)),
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'competition_id' => $hackathon,
                'user_id'        => $dian,   // kapten
                'name'           => 'Tim Gamma',
                'invite_code'    => Str::upper(Str::random(8)),
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'competition_id' => $hackathon,
                'user_id'        => $eko,   // kapten
                'name'           => 'Tim Delta',
                'invite_code'    => Str::upper(Str::random(8)),
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            // UI/UX
            [
                'competition_id' => $uiux,
                'user_id'        => $siti,   // kapten
                'name'           => 'Tim Pixel',
                'invite_code'    => Str::upper(Str::random(8)),
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'competition_id' => $uiux,
                'user_id'        => $zaki,   // kapten
                'name'           => 'Tim Canvas',
                'invite_code'    => Str::upper(Str::random(8)),
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'competition_id' => $uiux,
                'user_id'        => $fitri,   // kapten
                'name'           => 'Tim Wireframe',
                'invite_code'    => Str::upper(Str::random(8)),
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            // CTF
            [
                'competition_id' => $ctf,
                'user_id'        => $gunawan,   // kapten
                'name'           => 'Tim CyberShield',
                'invite_code'    => Str::upper(Str::random(8)),
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'competition_id' => $ctf,
                'user_id'        => $indah,   // kapten
                'name'           => 'Tim ZeroDay',
                'invite_code'    => Str::upper(Str::random(8)),
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            // IoT
            [
                'competition_id' => $iot,
                'user_id'        => $lestari,   // kapten
                'name'           => 'Tim SmartHome',
                'invite_code'    => Str::upper(Str::random(8)),
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'competition_id' => $iot,
                'user_id'        => $nurul,   // kapten
                'name'           => 'Tim GreenTech',
                'invite_code'    => Str::upper(Str::random(8)),
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            // Mobile App
            [
                'competition_id' => $mobile,
                'user_id'        => $rian,   // kapten
                'name'           => 'Tim TechWeaver',
                'invite_code'    => Str::upper(Str::random(8)),
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'competition_id' => $mobile,
                'user_id'        => $taufik,   // kapten
                'name'           => 'Tim SwiftDev',
                'invite_code'    => Str::upper(Str::random(8)),
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}
