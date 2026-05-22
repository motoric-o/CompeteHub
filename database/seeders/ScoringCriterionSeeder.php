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

        if (!empty($criteria)) {
            DB::table('scoring_criteria')->insert($criteria);
        }
    }
}
