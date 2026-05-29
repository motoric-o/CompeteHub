<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ScoringType;

class ScoringTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Time Based',
            'Judge Score',
            'Community Voting',
            'Quiz Automatic',
        ];

        foreach ($types as $type) {
            ScoringType::create([
                'name' => $type
            ]);
        }
    }
}
