<?php

namespace Tests\Feature;

use App\Models\Competition;
use App\Models\ScoringType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class WorkflowIntelligenceTest extends TestCase
{
    use RefreshDatabase;

    private User $committee;
    private User $participant;
    private Competition $competition;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();

        // Setup common users and competition
        $this->committee = User::factory()->create([
            'role' => 'committee',
        ]);

        $this->participant = User::factory()->create([
            'role' => 'participant',
        ]);

        $scoringType = ScoringType::create([
            'name' => 'Judge Score',
        ]);

        $this->competition = Competition::create([
            'uuid' => 'comp-uuid-1234',
            'user_id' => $this->committee->id,
            'scoring_type_id' => $scoringType->id,
            'name' => 'Web Development Competition 2026',
            'description' => 'Test competition description',
            'type' => 'individual',
            'registration_fee' => 150000.00,
            'quota' => 50,
            'start_date' => now()->addDays(10)->toDateString(),
            'end_date' => now()->addDays(15)->toDateString(),
            'registration_start' => now()->subDays(5)->toDateTimeString(),
            'registration_end' => now()->addDays(5)->toDateTimeString(),
            'status' => 'open',
        ]);
    }

    public function test_skeleton(): void
    {
        $this->assertTrue(true);
    }
}
