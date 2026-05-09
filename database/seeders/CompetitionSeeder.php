<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CompetitionSeeder extends Seeder
{
    public function run(): void
    {
        $committee1 = DB::table('users')->where('email', 'valentino@competehub.com')->value('id');
        $committee2 = DB::table('users')->where('email', 'felicia@competehub.com')->value('id');

        $judgeScoreId = DB::table('scoring_types')->where('name', 'Judge Score')->value('id');
        $timeBasedId = DB::table('scoring_types')->where('name', 'Time Based')->value('id');

        DB::table('competitions')->insert([
            // Lomba 1: Hackathon (team, judge_score) — JudgeScoreStrategy
            [
                'uuid'               => Str::uuid(),
                'user_id'            => $committee1,
                'name'               => 'Hackathon Nasional 2025',
                'description'        => 'Kompetisi hackathon tingkat nasional untuk mahasiswa.',
                'type'               => 'team',
                'scoring_type_id'    => $judgeScoreId,
                'registration_fee'   => 50000,
                'quota'              => 20,
                'start_date'         => '2025-06-01',
                'end_date'           => '2026-06-03',
                'registration_start' => '2025-05-01 00:00:00',
                'registration_end'   => '2026-05-25 23:59:59',
                'status'             => 'open',
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            // Lomba 2: Competitive Programming (individual, time_based) — TimeBasedScoringStrategy
            [
                'uuid'               => Str::uuid(),
                'user_id'            => $committee1,
                'name'               => 'Competitive Programming Cup',
                'description'        => 'Lomba pemrograman kompetitif perorangan, skor berdasarkan kecepatan.',
                'type'               => 'individual',
                'scoring_type_id'    => $timeBasedId,
                'registration_fee'   => 0,
                'quota'              => 50,
                'start_date'         => '2025-07-10',
                'end_date'           => '2026-07-10',
                'registration_start' => '2025-06-01 00:00:00',
                'registration_end'   => '2026-07-05 23:59:59',
                'status'             => 'open',
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            // Lomba 3: UI/UX Competition (team, judge_score)
            [
                'uuid'               => Str::uuid(),
                'user_id'            => $committee2,
                'name'               => 'UI/UX Design Competition',
                'description'        => 'Kompetisi desain UI/UX untuk tim mahasiswa.',
                'type'               => 'team',
                'scoring_type_id'    => $judgeScoreId,
                'registration_fee'   => 75000,
                'quota'              => 15,
                'start_date'         => '2025-08-01',
                'end_date'           => '2026-08-15',
                'registration_start' => '2025-07-01 00:00:00',
                'registration_end'   => '2026-07-28 23:59:59',
                'status'             => 'draft',
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
        ]);
    }
}
