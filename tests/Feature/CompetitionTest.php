<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Competition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompetitionTest extends TestCase
{
    use RefreshDatabase;

    protected User $committee;

    protected function setUp(): void
    {
        parent::setUp();

        $this->committee = User::factory()->create(['role' => 'committee']);
    }

    public function test_committee_can_create_competition_with_allowed_file_types()
    {
        $response = $this->actingAs($this->committee)
            ->post(route('committee.management.competitions.store'), [
                'name' => 'UI/UX Design Championship',
                'description' => 'A great competition for designers.',
                'type' => 'individual',
                'time_scoring_threshold' => 10.0,
                'registration_fee' => 50000,
                'quota' => 100,
                'start_date' => now()->addDays(5)->format('Y-m-d'),
                'end_date' => now()->addDays(10)->format('Y-m-d'),
                'registration_start' => now()->addDays(1)->format('Y-m-d H:i:s'),
                'registration_end' => now()->addDays(4)->format('Y-m-d H:i:s'),
                'status' => 'draft',
                'rules' => 'Do not copy others work.',
                'allowed_file_types' => ['pdf', 'zip'],
            ]);

        $response->assertRedirect(route('committee.management.competitions.index'));

        $competition = Competition::where('name', 'UI/UX Design Championship')->first();
        $this->assertNotNull($competition);
        $this->assertEquals(['pdf', 'zip'], $competition->allowed_file_types);
        $this->assertEquals('Do not copy others work.', $competition->rules);
    }

    public function test_committee_can_update_competition_with_allowed_file_types()
    {
        $competition = Competition::create([
            'user_id' => $this->committee->id,
            'name' => 'Original Competition',
            'type' => 'individual',
            'status' => 'draft',
            'allowed_file_types' => ['png'],
        ]);

        $response = $this->actingAs($this->committee)
            ->put(route('committee.management.competitions.update', $competition), [
                'name' => 'Updated Competition',
                'description' => 'Updated description.',
                'type' => 'individual',
                'time_scoring_threshold' => 12.0,
                'registration_fee' => 60000,
                'quota' => 80,
                'start_date' => now()->addDays(5)->format('Y-m-d'),
                'end_date' => now()->addDays(10)->format('Y-m-d'),
                'registration_start' => now()->addDays(1)->format('Y-m-d H:i:s'),
                'registration_end' => now()->addDays(4)->format('Y-m-d H:i:s'),
                'status' => 'draft',
                'rules' => 'Updated rules.',
                'allowed_file_types' => ['zip', 'pdf', 'mp4'],
            ]);

        $response->assertRedirect(route('committee.management.competitions.index'));

        $competition->refresh();
        $this->assertEquals('Updated Competition', $competition->name);
        $this->assertEquals(['zip', 'pdf', 'mp4'], $competition->allowed_file_types);
        $this->assertEquals('Updated rules.', $competition->rules);
    }
}
