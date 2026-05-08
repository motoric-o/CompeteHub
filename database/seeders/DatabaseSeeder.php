<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CompetitionSeeder::class,
            RoundSeeder::class,
            TeamSeeder::class,
            TeamMemberSeeder::class,
            FormTemplateSeeder::class,
            JuryAssignmentSeeder::class,
            RegistrationSeeder::class,
            RegistrationDocumentSeeder::class,
            PaymentSeeder::class,
            BracketSeeder::class,
            SubmissionSeeder::class,
            ScoreSeeder::class,
            LeaderboardSeeder::class,
            ContributionStatSeeder::class,
            NotificationSeeder::class,
        ]);
    }
}