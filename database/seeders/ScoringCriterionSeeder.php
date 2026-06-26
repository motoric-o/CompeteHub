<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScoringCriterionSeeder extends Seeder
{
    public function run(): void
    {
        $hackathonId = DB::table('competitions')->where('name', 'Hackathon Nasional 2025')->value('id');
        $uiuxId = DB::table('competitions')->where('name', 'UI/UX Design Competition')->value('id');

        $criteria = [];

        if ($hackathonId) {
            $criteria = array_merge($criteria, [
                [
                    'competition_id' => $hackathonId,
                    'name' => 'Innovation & Creativity',
                    'description' => 'How unique and creative is the solution?',
                    'max_score' => 100,
                    'weight' => 0.30,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'competition_id' => $hackathonId,
                    'name' => 'Technical Implementation',
                    'description' => 'Quality of code and architecture.',
                    'max_score' => 100,
                    'weight' => 0.40,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'competition_id' => $hackathonId,
                    'name' => 'Business Potential',
                    'description' => 'Market viability of the product.',
                    'max_score' => 100,
                    'weight' => 0.30,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        if ($uiuxId) {
            $criteria = array_merge($criteria, [
                [
                    'competition_id' => $uiuxId,
                    'name' => 'User Experience (UX)',
                    'description' => 'Flow and usability of the design.',
                    'max_score' => 100,
                    'weight' => 0.50,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'competition_id' => $uiuxId,
                    'name' => 'User Interface (UI)',
                    'description' => 'Aesthetic and visual appeal.',
                    'max_score' => 100,
                    'weight' => 0.50,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        $dsId = DB::table('competitions')->where('name', 'Data Science Challenge 2026')->value('id');
        $iotId = DB::table('competitions')->where('name', 'IoT Innovation Cup 2025')->value('id');
        $mobileId = DB::table('competitions')->where('name', 'Mobile App Dev Arena')->value('id');

        if ($dsId) {
            $criteria = array_merge($criteria, [
                ['competition_id' => $dsId, 'name' => 'Model Accuracy', 'description' => 'Metrics on prediction performance.', 'max_score' => 100, 'weight' => 0.50, 'created_at' => now(), 'updated_at' => now()],
                ['competition_id' => $dsId, 'name' => 'Methodology', 'description' => 'Feature engineering and model logic.', 'max_score' => 100, 'weight' => 0.30, 'created_at' => now(), 'updated_at' => now()],
                ['competition_id' => $dsId, 'name' => 'Report & Visuals', 'description' => 'Clarity of documentation.', 'max_score' => 100, 'weight' => 0.20, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        if ($iotId) {
            $criteria = array_merge($criteria, [
                ['competition_id' => $iotId, 'name' => 'Hardware Reliability', 'description' => 'Does it work consistently?', 'max_score' => 100, 'weight' => 0.40, 'created_at' => now(), 'updated_at' => now()],
                ['competition_id' => $iotId, 'name' => 'Use Case & Value', 'description' => 'Real-world utility.', 'max_score' => 100, 'weight' => 0.40, 'created_at' => now(), 'updated_at' => now()],
                ['competition_id' => $iotId, 'name' => 'Presentation', 'description' => 'Quality of pitch and demo.', 'max_score' => 100, 'weight' => 0.20, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        if ($mobileId) {
            $criteria = array_merge($criteria, [
                ['competition_id' => $mobileId, 'name' => 'User Interface & UX', 'description' => 'Aesthetics and usability.', 'max_score' => 100, 'weight' => 0.35, 'created_at' => now(), 'updated_at' => now()],
                ['competition_id' => $mobileId, 'name' => 'Technical Execution', 'description' => 'Code structure and api integration.', 'max_score' => 100, 'weight' => 0.35, 'created_at' => now(), 'updated_at' => now()],
                ['competition_id' => $mobileId, 'name' => 'Business Viability', 'description' => 'Market potential.', 'max_score' => 100, 'weight' => 0.30, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        if (!empty($criteria)) {
            DB::table('scoring_criteria')->insert($criteria);
        }
    }
}
