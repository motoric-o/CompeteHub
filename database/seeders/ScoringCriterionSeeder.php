<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScoringCriterionSeeder extends Seeder
{
    public function run(): void
    {
        $hackathonSemiFinal = DB::table('rounds')->where('name', 'Semi Final')->value('id');
        $uiuxRound = DB::table('rounds')->where('name', 'Babak Utama')->value('id');

        $criteria = [];

        if ($hackathonSemiFinal) {
            $criteria = array_merge($criteria, [
                [
                    'round_id' => $hackathonSemiFinal,
                    'name' => 'Innovation & Creativity',
                    'description' => 'How unique and creative is the solution?',
                    'max_score' => 100,
                    'weight' => 0.30,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'round_id' => $hackathonSemiFinal,
                    'name' => 'Technical Implementation',
                    'description' => 'Quality of code and architecture.',
                    'max_score' => 100,
                    'weight' => 0.40,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'round_id' => $hackathonSemiFinal,
                    'name' => 'Business Potential',
                    'description' => 'Market viability of the product.',
                    'max_score' => 100,
                    'weight' => 0.30,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // We don't have UI/UX rounds seeded properly yet, let's just seed to Hackathon Grand Final as an example
        $hackathonGrandFinal = DB::table('rounds')->where('name', 'Grand Final')->value('id');
        if ($hackathonGrandFinal) {
            $criteria = array_merge($criteria, [
                [
                    'round_id' => $hackathonGrandFinal,
                    'name' => 'User Experience (UX)',
                    'description' => 'Flow and usability of the design.',
                    'max_score' => 100,
                    'weight' => 0.50,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'round_id' => $hackathonGrandFinal,
                    'name' => 'User Interface (UI)',
                    'description' => 'Aesthetic and visual appeal.',
                    'max_score' => 100,
                    'weight' => 0.50,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        $dsRound = DB::table('rounds')->where('name', 'Kaggle Phase')->value('id');
        $iotRound = DB::table('rounds')->where('name', 'Proposal Submission')->value('id');
        $mobileRound = DB::table('rounds')->where('name', 'App Prototype')->value('id');

        if ($dsRound) {
            $criteria = array_merge($criteria, [
                ['round_id' => $dsRound, 'name' => 'Model Accuracy', 'description' => 'Metrics on prediction performance.', 'max_score' => 100, 'weight' => 0.50, 'created_at' => now(), 'updated_at' => now()],
                ['round_id' => $dsRound, 'name' => 'Methodology', 'description' => 'Feature engineering and model logic.', 'max_score' => 100, 'weight' => 0.30, 'created_at' => now(), 'updated_at' => now()],
                ['round_id' => $dsRound, 'name' => 'Report & Visuals', 'description' => 'Clarity of documentation.', 'max_score' => 100, 'weight' => 0.20, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        if ($iotRound) {
            $criteria = array_merge($criteria, [
                ['round_id' => $iotRound, 'name' => 'Hardware Reliability', 'description' => 'Does it work consistently?', 'max_score' => 100, 'weight' => 0.40, 'created_at' => now(), 'updated_at' => now()],
                ['round_id' => $iotRound, 'name' => 'Use Case & Value', 'description' => 'Real-world utility.', 'max_score' => 100, 'weight' => 0.40, 'created_at' => now(), 'updated_at' => now()],
                ['round_id' => $iotRound, 'name' => 'Presentation', 'description' => 'Quality of pitch and demo.', 'max_score' => 100, 'weight' => 0.20, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        if ($mobileRound) {
            $criteria = array_merge($criteria, [
                ['round_id' => $mobileRound, 'name' => 'User Interface & UX', 'description' => 'Aesthetics and usability.', 'max_score' => 100, 'weight' => 0.35, 'created_at' => now(), 'updated_at' => now()],
                ['round_id' => $mobileRound, 'name' => 'Technical Execution', 'description' => 'Code structure and api integration.', 'max_score' => 100, 'weight' => 0.35, 'created_at' => now(), 'updated_at' => now()],
                ['round_id' => $mobileRound, 'name' => 'Business Viability', 'description' => 'Market potential.', 'max_score' => 100, 'weight' => 0.30, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        if (!empty($criteria)) {
            DB::table('scoring_criteria')->insert($criteria);
        }
    }
}
