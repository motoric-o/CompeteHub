<?php

namespace Tests\Feature;

use App\Models\Competition;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\RegistrationDocument;
use App\Models\ScoringType;
use App\Models\User;
use App\Services\Dashboard\CommandCenterService;
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

    /**
     * Test CommandCenterService
     */
    public function test_command_center_service(): void
    {
        $service = new CommandCenterService();

        // 1. Free/no registration setup
        $dashboard = $service->buildCommandCenter($this->competition);
        $this->assertEquals(0, $dashboard->newRegistrationsCount);
        $this->assertEquals(0, $dashboard->pendingPaymentsCount);
        $this->assertEquals(0, $dashboard->pendingDocumentsCount);
        $this->assertEquals(0, $dashboard->rejectedCount);
        $this->assertEquals(0, $dashboard->overdueCount);
        $this->assertEquals(40, $dashboard->readinessScore); // no verified registrations

        // 2. Add some registrations
        // One verified registration
        $reg1 = Registration::create([
            'competition_id' => $this->competition->id,
            'user_id' => $this->participant->id,
            'form_data' => ['Full Name' => 'John Doe'],
            'status' => 'verified',
        ]);
        Payment::create([
            'registration_id' => $reg1->id,
            'amount' => 150000.00,
            'status' => 'paid',
        ]);

        // One registration with pending payment
        $reg2 = Registration::create([
            'competition_id' => $this->competition->id,
            'user_id' => User::factory()->create()->id,
            'form_data' => ['Full Name' => 'Jane Smith'],
            'status' => 'documents_ok',
        ]);
        Payment::create([
            'registration_id' => $reg2->id,
            'amount' => 150000.00,
            'status' => 'pending_verification',
        ]);

        // One registration with pending documents
        $reg3 = Registration::create([
            'competition_id' => $this->competition->id,
            'user_id' => User::factory()->create()->id,
            'form_data' => ['Full Name' => 'Bob Johnson'],
            'status' => 'pending',
        ]);
        RegistrationDocument::create([
            'registration_id' => $reg3->id,
            'document_type' => 'KTP Scan',
            'file_path' => 'ktp.jpg',
            'status' => 'pending',
            'uploaded_at' => now(),
        ]);

        $dashboard = $service->buildCommandCenter($this->competition);

        $this->assertEquals(3, $this->competition->registrations()->count());
        $this->assertEquals(1, $dashboard->pendingPaymentsCount);
        $this->assertEquals(1, $dashboard->pendingDocumentsCount);
        $this->assertEquals(40, $dashboard->readinessScore); // 1 out of 3 is verified (33%) + other factors = 40
    }

    /**
     * Test Committee Command Center Endpoint
     */
    public function test_committee_command_center_view(): void
    {
        $response = $this->actingAs($this->committee)
            ->get(route('committee.command-center.show', $this->competition));

        $response->assertStatus(200);
        $response->assertViewHas('dashboard');
        $response->assertSee('Command Center');
        $response->assertSee('Web Development Competition 2026');
    }
}
