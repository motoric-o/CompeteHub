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

        if (!empty($criteria)) {
            DB::table('scoring_criteria')->insert($criteria);
        }
    }
}
