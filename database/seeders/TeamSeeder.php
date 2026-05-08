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

        $budi  = DB::table('users')->where('email', 'budi@gmail.com')->value('id');
        $siti  = DB::table('users')->where('email', 'siti@gmail.com')->value('id');
        $andi  = DB::table('users')->where('email', 'andi@gmail.com')->value('id');
        $dewi  = DB::table('users')->where('email', 'dewi@gmail.com')->value('id');

        DB::table('teams')->insert([
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
                'competition_id' => $uiux,
                'user_id'        => $siti,   // kapten
                'name'           => 'Tim Pixel',
                'invite_code'    => Str::upper(Str::random(8)),
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}
