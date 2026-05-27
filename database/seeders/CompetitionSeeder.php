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
                'category'           => 'Web Development',
                'banner_url'         => 'banners/hackathon_banner.png',
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
                'category'           => 'Competitive Programming',
                'banner_url'         => 'banners/cp_banner.png',
                'scoring_type_id'    => $timeBasedId,
                'registration_fee'   => 0,
                'quota'              => 50,
                'start_date'         => '2026-05-10',
                'end_date'           => '2026-07-10',
                'registration_start' => '2026-05-10 00:00:00',
                'registration_end'   => '2026-07-10 23:59:59',
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
                'category'           => 'UI/UX Design',
                'banner_url'         => 'banners/uiux_banner.png',
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
            // Lomba 4: Capture The Flag (team, time_based)
            [
                'uuid'               => Str::uuid(),
                'user_id'            => $committee2,
                'name'               => 'Siber Defense CTF 2026',
                'description'        => 'Kompetisi hacking Capture The Flag tingkat nasional. Selesaikan tantangan Jeopardy-style.',
                'type'               => 'team',
                'category'           => 'Capture The Flag',
                'banner_url'         => 'banners/ctf_banner.png',
                'scoring_type_id'    => $timeBasedId,
                'registration_fee'   => 25000,
                'quota'              => 30,
                'start_date'         => '2026-08-20',
                'end_date'           => '2026-08-22',
                'registration_start' => '2026-06-01 00:00:00',
                'registration_end'   => '2026-08-15 23:59:59',
                'status'             => 'open',
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            // Lomba 5: Data Science Challenge 2026 (individual, judge_score)
            [
                'uuid'               => Str::uuid(),
                'user_id'            => $committee1,
                'name'               => 'Data Science Challenge 2026',
                'description'        => 'Analisis big data dan ciptakan model prediksi terbaik untuk menyelesaikan masalah sosial.',
                'type'               => 'individual',
                'category'           => 'Other',
                'banner_url'         => 'banners/hackathon_banner.png', // Re-use
                'scoring_type_id'    => $judgeScoreId,
                'registration_fee'   => 0,
                'quota'              => 100,
                'start_date'         => '2026-05-15',
                'end_date'           => '2026-06-15',
                'registration_start' => '2026-04-15 00:00:00',
                'registration_end'   => '2026-05-14 23:59:59',
                'status'             => 'ongoing',
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            // Lomba 6: IoT Innovation Cup 2025 (team, judge_score)
            [
                'uuid'               => Str::uuid(),
                'user_id'            => $committee2,
                'name'               => 'IoT Innovation Cup 2025',
                'description'        => 'Kompetisi membuat prototipe perangkat keras IoT cerdas.',
                'type'               => 'team',
                'category'           => 'Other',
                'banner_url'         => 'banners/cp_banner.png', // Re-use
                'scoring_type_id'    => $judgeScoreId,
                'registration_fee'   => 100000,
                'quota'              => 10,
                'start_date'         => '2025-10-01',
                'end_date'           => '2025-10-15',
                'registration_start' => '2025-09-01 00:00:00',
                'registration_end'   => '2025-09-25 23:59:59',
                'status'             => 'finished',
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            // Lomba 7: Mobile App Dev Arena (team, judge_score)
            [
                'uuid'               => Str::uuid(),
                'user_id'            => $committee1,
                'name'               => 'Mobile App Dev Arena',
                'description'        => 'Kembangkan aplikasi mobile inovatif berbasis Android/iOS yang memecahkan masalah kehidupan sehari-hari.',
                'type'               => 'team',
                'category'           => 'Web Development',
                'banner_url'         => 'banners/uiux_banner.png', // Re-use
                'scoring_type_id'    => $judgeScoreId,
                'registration_fee'   => 40000,
                'quota'              => 40,
                'start_date'         => '2026-07-01',
                'end_date'           => '2026-07-15',
                'registration_start' => '2026-05-01 00:00:00',
                'registration_end'   => '2026-06-25 23:59:59',
                'status'             => 'open',
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            // Lomba 8: Cyber Security CTF Junior (individual, time_based)
            [
                'uuid'               => Str::uuid(),
                'user_id'            => $committee2,
                'name'               => 'Cyber Security CTF Junior',
                'description'        => 'Kompetisi CTF perorangan khusus siswa SMA/SMK sederajat untuk melatih kemampuan penetrasi dan pertahanan siber.',
                'type'               => 'individual',
                'category'           => 'Capture The Flag',
                'banner_url'         => 'banners/ctf_banner.png', // Re-use
                'scoring_type_id'    => $timeBasedId,
                'registration_fee'   => 0,
                'quota'              => 150,
                'start_date'         => '2026-05-01',
                'end_date'           => '2026-06-01',
                'registration_start' => '2026-04-01 00:00:00',
                'registration_end'   => '2026-04-30 23:59:59',
                'status'             => 'ongoing',
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
        ]);
    }
}
