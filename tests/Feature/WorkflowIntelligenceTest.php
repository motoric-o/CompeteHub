<?php

namespace Tests\Feature;

use App\Models\Competition;
use App\Models\FormTemplate;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\RegistrationDocument;
use App\Models\ScoringType;
use App\Models\User;
use App\Services\Dashboard\CommandCenterService;
use App\Services\Registration\RegistrationPreCheckService;
use App\Services\Template\TemplateQualityAnalyzer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
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
     * Test TemplateQualityAnalyzer
     */
    public function test_template_quality_analyzer(): void
    {
        $this->competition->update(['registration_fee' => 0.00]);
        $analyzer = new TemplateQualityAnalyzer();

        // 1. Empty template
        $warnings = $analyzer->analyze([], $this->competition);
        $this->assertCount(1, $warnings);
        $this->assertEquals('empty_template', $warnings[0]->code);
        $this->assertEquals('error', $warnings[0]->severity);

        // 2. Duplicate labels
        $fields = [
            ['label' => 'Nama Lengkap', 'type' => 'text', 'required' => true],
            ['label' => 'nama lengkap', 'type' => 'text', 'required' => false],
        ];
        $warnings = $analyzer->analyze($fields, $this->competition);
        $this->assertCount(1, $warnings);
        $this->assertEquals('duplicate_field', $warnings[0]->code);
        $this->assertEquals('error', $warnings[0]->severity);

        // 3. High required field ratio
        $fields = [
            ['label' => 'Field 1', 'type' => 'text', 'required' => true],
            ['label' => 'Field 2', 'type' => 'text', 'required' => true],
            ['label' => 'Field 3', 'type' => 'text', 'required' => true],
            ['label' => 'Field 4', 'type' => 'text', 'required' => true],
            ['label' => 'Field 5', 'type' => 'text', 'required' => true],
        ];
        $warnings = $analyzer->analyze($fields, $this->competition);
        // Ratio is 100%, count > 3, should warn all_fields_required
        $this->assertTrue(collect($warnings)->contains(fn($w) => $w->code === 'all_fields_required'));

        // 4. Form too long
        $fields = [];
        for ($i = 0; $i < 20; $i++) {
            $fields[] = ['label' => "Field $i", 'type' => 'text', 'required' => false];
        }
        $warnings = $analyzer->analyze($fields, $this->competition);
        $this->assertTrue(collect($warnings)->contains(fn($w) => $w->code === 'form_too_long'));
    }

    /**
     * Test RegistrationPreCheckService
     */
    public function test_registration_pre_check_service(): void
    {
        $preCheckService = new RegistrationPreCheckService();

        // Set up template fields
        FormTemplate::create([
            'competition_id' => $this->competition->id,
            'name' => 'Registration Form Template',
            'fields' => [
                ['label' => 'Full Name', 'type' => 'text', 'required' => true],
                ['label' => 'KTP Scan', 'type' => 'file', 'required' => true],
                ['label' => 'Agree to Terms', 'type' => 'checkbox', 'required' => true],
            ],
        ]);

        // 1. Missing required text field and checkbox and payment proof
        $formData = [
            'Full Name' => '',
            'Agree to Terms' => false,
        ];
        $uploadedFiles = [];
        $paymentProof = null;

        $issues = $preCheckService->check($this->competition, $formData, $uploadedFiles, $paymentProof);

        $this->assertCount(4, $issues); // Full Name (missing), KTP Scan (missing), Agree to Terms (missing), Payment Proof (missing)
        
        $severities = collect($issues)->pluck('severity')->toArray();
        $fields = collect($issues)->pluck('field')->toArray();

        $this->assertContains('Full Name', $fields);
        $this->assertContains('KTP Scan', $fields);
        $this->assertContains('Agree to Terms', $fields);
        $this->assertContains('payment_proof', $fields);
        $this->assertEquals(['missing', 'missing', 'missing', 'missing'], $severities);

        // 2. Invalid file format
        $invalidFile = UploadedFile::fake()->create('ktp.txt', 100);
        $uploadedFiles = [
            'KTP Scan' => $invalidFile,
        ];
        $formData = [
            'Full Name' => 'John Doe',
            'Agree to Terms' => true,
        ];
        $paymentProof = UploadedFile::fake()->create('proof.pdf', 200);

        $issues = $preCheckService->check($this->competition, $formData, $uploadedFiles, $paymentProof);

        $this->assertCount(1, $issues);
        $this->assertEquals('KTP Scan', $issues[0]->field);
        $this->assertEquals('invalid', $issues[0]->severity);
        $this->assertStringContainsString('Format file \'KTP Scan\' tidak didukung', $issues[0]->message);
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

    /**
     * Test AJAX Pre-check Controller Endpoint
     */
    public function test_participant_ajax_pre_check(): void
    {
        FormTemplate::create([
            'competition_id' => $this->competition->id,
            'name' => 'Registration Form Template',
            'fields' => [
                ['label' => 'Full Name', 'type' => 'text', 'required' => true],
            ],
        ]);

        $response = $this->actingAs($this->participant)
            ->postJson(route('participant.registrations.pre-check', $this->competition), [
                'form_data' => [
                    'Full Name' => '',
                ],
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'ready',
            'issues' => [
                '*' => ['field', 'message', 'severity'],
            ],
        ]);

        $data = $response->json();
        $this->assertFalse($data['ready']);
        $this->assertCount(2, $data['issues']); // Full Name missing, payment proof missing
    }
}
