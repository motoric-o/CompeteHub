<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JuryAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $jeryko = DB::table('users')->where('email', 'jeryko@competehub.com')->value('id');
        $rico   = DB::table('users')->where('email', 'rico@competehub.com')->value('id');
        $hassan = DB::table('users')->where('email', 'hassan@competehub.com')->value('id');
        $lina   = DB::table('users')->where('email', 'lina@competehub.com')->value('id');

        $hackathon = DB::table('competitions')->where('name', 'Hackathon Nasional 2025')->value('id');
        $cp        = DB::table('competitions')->where('name', 'Competitive Programming Cup')->value('id');
        $uiux      = DB::table('competitions')->where('name', 'UI/UX Design Competition')->value('id');
        $ctf       = DB::table('competitions')->where('name', 'Siber Defense CTF 2026')->value('id');
        $ds        = DB::table('competitions')->where('name', 'Data Science Challenge 2026')->value('id');
        $iot       = DB::table('competitions')->where('name', 'IoT Innovation Cup 2025')->value('id');
        $mobile    = DB::table('competitions')->where('name', 'Mobile App Dev Arena')->value('id');
        $ctfJr     = DB::table('competitions')->where('name', 'Cyber Security CTF Junior')->value('id');

        DB::table('jury_assignments')->insert([
            // Hackathon: Jeryko, Rico
            ['user_id' => $jeryko, 'competition_id' => $hackathon, 'assigned_at' => now()],
            ['user_id' => $rico,   'competition_id' => $hackathon, 'assigned_at' => now()],

            // CP: Jeryko, Hassan
            ['user_id' => $jeryko, 'competition_id' => $cp,        'assigned_at' => now()],
            ['user_id' => $hassan, 'competition_id' => $cp,        'assigned_at' => now()],

            // UI/UX: Rico, Lina
            ['user_id' => $rico,   'competition_id' => $uiux,      'assigned_at' => now()],
            ['user_id' => $lina,   'competition_id' => $uiux,      'assigned_at' => now()],

            // CTF: Jeryko, Hassan
            ['user_id' => $jeryko, 'competition_id' => $ctf,       'assigned_at' => now()],
            ['user_id' => $hassan, 'competition_id' => $ctf,       'assigned_at' => now()],

            // Data Science: Hassan, Lina
            ['user_id' => $hassan, 'competition_id' => $ds,        'assigned_at' => now()],
            ['user_id' => $lina,   'competition_id' => $ds,        'assigned_at' => now()],

            // IoT: Rico, Hassan, Lina
            ['user_id' => $rico,   'competition_id' => $iot,       'assigned_at' => now()],
            ['user_id' => $hassan, 'competition_id' => $iot,       'assigned_at' => now()],
            ['user_id' => $lina,   'competition_id' => $iot,       'assigned_at' => now()],

            // Mobile App: Jeryko, Rico, Hassan, Lina
            ['user_id' => $jeryko, 'competition_id' => $mobile,    'assigned_at' => now()],
            ['user_id' => $rico,   'competition_id' => $mobile,    'assigned_at' => now()],
            ['user_id' => $hassan, 'competition_id' => $mobile,    'assigned_at' => now()],
            ['user_id' => $lina,   'competition_id' => $mobile,    'assigned_at' => now()],

            // Cyber CTF Junior: Jeryko, Lina
            ['user_id' => $jeryko, 'competition_id' => $ctfJr,     'assigned_at' => now()],
            ['user_id' => $lina,   'competition_id' => $ctfJr,     'assigned_at' => now()],
        ]);
    }
}
