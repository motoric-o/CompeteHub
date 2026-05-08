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

        $hackathon = DB::table('competitions')->where('name', 'Hackathon Nasional 2025')->value('id');
        $cp        = DB::table('competitions')->where('name', 'Competitive Programming Cup')->value('id');
        $uiux      = DB::table('competitions')->where('name', 'UI/UX Design Competition')->value('id');

        DB::table('jury_assignments')->insert([
            // Jeryko juri di Hackathon & CP
            ['user_id' => $jeryko, 'competition_id' => $hackathon, 'assigned_at' => now()],
            ['user_id' => $jeryko, 'competition_id' => $cp,        'assigned_at' => now()],
            // Rico juri di Hackathon & UI/UX
            ['user_id' => $rico,   'competition_id' => $hackathon, 'assigned_at' => now()],
            ['user_id' => $rico,   'competition_id' => $uiux,      'assigned_at' => now()],
        ]);
    }
}
