<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScoringAnomalyDemoSeeder extends Seeder
{
    public function run(): void
    {
        $submissionId = DB::table('submissions')
            ->where('file_path', 'submissions/alpha_penyisihan.zip')
            ->value('id');

        $ricoId = DB::table('users')->where('email', 'rico@competehub.com')->value('id');
        $hassanId = DB::table('users')->where('email', 'hassan@competehub.com')->value('id');

        if (! $submissionId || ! $ricoId || ! $hassanId) {
            return;
        }

        DB::table('scores')->updateOrInsert(
            [
                'submission_id' => $submissionId,
                'user_id' => $ricoId,
            ],
            [
                'score' => 35.00,
                'notes' => 'Demo anomaly detector: nilai dibuat jauh dari rata-rata untuk kebutuhan pengujian.',
                'scored_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('scores')->updateOrInsert(
            [
                'submission_id' => $submissionId,
                'user_id' => $hassanId,
            ],
            [
                'score' => 88.00,
                'notes' => 'Nilai pembanding untuk demo scoring anomaly detector.',
                'scored_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('submissions')->where('id', $submissionId)->update([
            'final_score' => 69.33,
            'status' => 'scored',
            'updated_at' => now(),
        ]);
    }
}