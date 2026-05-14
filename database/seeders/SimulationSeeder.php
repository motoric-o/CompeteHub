<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Competition;
use App\Models\FormTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SimulationSeeder extends Seeder
{
    public function run(): void
    {
        $committee = User::firstOrCreate(
            ['email' => 'committee.sim@example.com'],
            [
                'name' => 'Committee Simulation',
                'password' => Hash::make('password123'),
                'role' => 'committee',
            ]
        );
        $competition = Competition::firstOrCreate(
            ['name' => 'Grand Hackathon 2026'],
            [
                'user_id' => $committee->id,
                'type' => 'individual',
                'scoring_type' => 'judge_score',
                'description' => 'A premier coding competition for innovators. Build the future.',
                'registration_fee' => 50000,
                'quota' => 100,
                'start_date' => now()->addDays(10),
                'end_date' => now()->addDays(12),
                'registration_start' => now()->subDays(1),
                'registration_end' => now()->addDays(5),
                'status' => 'open',
            ]
        );
        FormTemplate::firstOrCreate(
            ['competition_id' => $competition->id],
            [
                'name' => 'Standard Hackathon Registration',
                'fields' => [
                    [
                        'label' => 'Github URL',
                        'type' => 'text',
                        'required' => true,
                    ],
                    [
                        'label' => 'Programming Language',
                        'type' => 'select',
                        'required' => true,
                        'options' => ['PHP', 'Python', 'JavaScript', 'Go'],
                    ],
                    [
                        'label' => 'Why do you want to join?',
                        'type' => 'textarea',
                        'required' => false,
                    ],
                    [
                        'label' => 'CV / Portfolio (PDF)',
                        'type' => 'file',
                        'required' => true,
                    ]
                ]
            ]
        );
    }
}
