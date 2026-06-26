<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoundSeeder extends Seeder
{
    public function run(): void
    {
        $hackathon = DB::table('competitions')->where('name', 'Hackathon Nasional 2025')->value('id');
        $cp        = DB::table('competitions')->where('name', 'Competitive Programming Cup')->value('id');
        $ctf       = DB::table('competitions')->where('name', 'Siber Defense CTF 2026')->value('id');
        $uiux      = DB::table('competitions')->where('name', 'UI/UX Design Competition')->value('id');
        $ds        = DB::table('competitions')->where('name', 'Data Science Challenge 2026')->value('id');
        $iot       = DB::table('competitions')->where('name', 'IoT Innovation Cup 2025')->value('id');
        $mobile    = DB::table('competitions')->where('name', 'Mobile App Dev Arena')->value('id');
        $ctfJr     = DB::table('competitions')->where('name', 'Cyber Security CTF Junior')->value('id');

        DB::table('rounds')->insert([
            // Hackathon rounds
            ['competition_id' => $hackathon, 'name' => 'Penyisihan',  'round_order' => 1, 'status' => 'active',  'start_date' => '2026-06-01 08:00:00', 'end_date' => '2026-06-01 20:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['competition_id' => $hackathon, 'name' => 'Semi Final',  'round_order' => 2, 'status' => 'pending', 'start_date' => '2026-06-02 08:00:00', 'end_date' => '2026-06-02 20:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['competition_id' => $hackathon, 'name' => 'Grand Final', 'round_order' => 3, 'status' => 'pending', 'start_date' => '2026-06-03 08:00:00', 'end_date' => '2026-06-03 20:00:00', 'created_at' => now(), 'updated_at' => now()],
            // CP rounds
            ['competition_id' => $cp,        'name' => 'Babak Utama', 'round_order' => 1, 'status' => 'active', 'start_date' => '2026-05-10 09:00:00', 'end_date' => '2026-07-10 17:00:00', 'created_at' => now(), 'updated_at' => now()],
            // CTF rounds
            ['competition_id' => $ctf,       'name' => 'Quals Jeopardy', 'round_order' => 1, 'status' => 'active', 'start_date' => '2026-08-20 09:00:00', 'end_date' => '2026-08-22 17:00:00', 'created_at' => now(), 'updated_at' => now()],
            // UI/UX rounds
            ['competition_id' => $uiux,      'name' => 'Penyisihan Portofolio', 'round_order' => 1, 'status' => 'active', 'start_date' => '2025-08-01 08:00:00', 'end_date' => '2026-08-10 20:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['competition_id' => $uiux,      'name' => 'Final Pitching', 'round_order' => 2, 'status' => 'pending', 'start_date' => '2026-08-11 08:00:00', 'end_date' => '2026-08-15 20:00:00', 'created_at' => now(), 'updated_at' => now()],
            // Data Science rounds
            ['competition_id' => $ds,        'name' => 'Kaggle Phase', 'round_order' => 1, 'status' => 'active', 'start_date' => '2026-05-15 08:00:00', 'end_date' => '2026-06-10 20:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['competition_id' => $ds,        'name' => 'Model Presentation', 'round_order' => 2, 'status' => 'pending', 'start_date' => '2026-06-11 08:00:00', 'end_date' => '2026-06-15 20:00:00', 'created_at' => now(), 'updated_at' => now()],
            // IoT rounds (finished)
            ['competition_id' => $iot,       'name' => 'Proposal Submission', 'round_order' => 1, 'status' => 'finished', 'start_date' => '2025-10-01 08:00:00', 'end_date' => '2025-10-05 20:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['competition_id' => $iot,       'name' => 'Hardware Demo', 'round_order' => 2, 'status' => 'finished', 'start_date' => '2025-10-06 08:00:00', 'end_date' => '2025-10-15 20:00:00', 'created_at' => now(), 'updated_at' => now()],
            // Mobile App rounds
            ['competition_id' => $mobile,    'name' => 'App Prototype', 'round_order' => 1, 'status' => 'active', 'start_date' => '2026-07-01 08:00:00', 'end_date' => '2026-07-10 20:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['competition_id' => $mobile,    'name' => 'Main Demo', 'round_order' => 2, 'status' => 'pending', 'start_date' => '2026-07-11 08:00:00', 'end_date' => '2026-07-20 20:00:00', 'created_at' => now(), 'updated_at' => now()],
            // Cyber Security CTF Junior rounds
            ['competition_id' => $ctfJr,     'name' => 'Online CTF Quals', 'round_order' => 1, 'status' => 'active', 'start_date' => '2026-05-01 08:00:00', 'end_date' => '2026-05-20 20:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['competition_id' => $ctfJr,     'name' => 'Final CTF Attack-Defense', 'round_order' => 2, 'status' => 'pending', 'start_date' => '2026-05-21 08:00:00', 'end_date' => '2026-06-01 20:00:00', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
