<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Competition;
use App\Models\Round;
use App\Models\ScoringType;
use App\Models\Registration;
use App\Models\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoundBracketOptionTest extends TestCase
{
    use RefreshDatabase;

    protected User $committee;
    protected User $participant;
    protected Competition $competition;
    protected ScoringType $scoringType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->committee = User::factory()->create(['role' => 'committee']);
        $this->participant = User::factory()->create(['role' => 'participant']);

        $this->competition = Competition::create([
            'user_id' => $this->committee->id,
            'name' => 'CP Cup Test',
            'type' => 'individual',
            'status' => 'open'
        ]);

        $this->scoringType = ScoringType::create(['name' => 'Judge Score']);

        Registration::create([
            'competition_id' => $this->competition->id,
            'user_id' => $this->participant->id,
            'status' => 'payment_ok'
        ]);
    }

    public function test_committee_can_create_a_non_bracket_round()
    {
        $response = $this->actingAs($this->committee)
            ->post(route('committee.rounds.store', $this->competition), [
                'scoring_type_id' => $this->scoringType->id,
                'name' => 'Babak Umum 1',
                'round_order' => 1,
                'status' => 'active',
                // is_bracket is unchecked (not sent)
            ]);

        $response->assertRedirect(route('committee.rounds.index', $this->competition));

        $round = Round::where('competition_id', $this->competition->id)->first();
        $this->assertNotNull($round);
        $this->assertEquals('Babak Umum 1', $round->name);
        $this->assertFalse($round->is_bracket);
    }

    public function test_committee_can_create_a_bracket_round()
    {
        $response = $this->actingAs($this->committee)
            ->post(route('committee.rounds.store', $this->competition), [
                'scoring_type_id' => $this->scoringType->id,
                'name' => 'Babak Bagan 1',
                'round_order' => 1,
                'status' => 'active',
                'is_bracket' => '1',
            ]);

        $response->assertRedirect(route('committee.rounds.index', $this->competition));

        $round = Round::where('competition_id', $this->competition->id)->first();
        $this->assertNotNull($round);
        $this->assertEquals('Babak Bagan 1', $round->name);
        $this->assertTrue($round->is_bracket);
    }

    public function test_committee_can_update_round_bracket_option()
    {
        $round = Round::create([
            'competition_id' => $this->competition->id,
            'scoring_type_id' => $this->scoringType->id,
            'name' => 'Babak Awal',
            'round_order' => 1,
            'status' => 'active',
            'is_bracket' => true
        ]);

        $response = $this->actingAs($this->committee)
            ->put(route('committee.rounds.update', [$this->competition, $round]), [
                'scoring_type_id' => $this->scoringType->id,
                'name' => 'Babak Awal Terupdate',
                'round_order' => 1,
                'status' => 'active',
                // is_bracket is unchecked
            ]);

        $response->assertRedirect(route('committee.rounds.index', $this->competition));

        $round->refresh();
        $this->assertEquals('Babak Awal Terupdate', $round->name);
        $this->assertFalse($round->is_bracket);
    }

    public function test_non_bracket_round_blocks_bracket_generation_and_manually_adding_brackets()
    {
        $round = Round::create([
            'competition_id' => $this->competition->id,
            'scoring_type_id' => $this->scoringType->id,
            'name' => 'Babak Umum',
            'round_order' => 1,
            'status' => 'active',
            'is_bracket' => false
        ]);

        // Try auto generate
        $responseAuto = $this->actingAs($this->committee)
            ->post(route('committee.rounds.brackets.auto-generate', [$this->competition, $round]));
        $responseAuto->assertRedirect(route('committee.rounds.show', [$this->competition, $round]));
        $responseAuto->assertSessionHas('error', 'Cannot perform bracket actions on a non-bracket round.');

        // Try manual add
        $responseManual = $this->actingAs($this->committee)
            ->post(route('committee.rounds.brackets.store', [$this->competition, $round]), [
                'participant_a' => $this->participant->id,
            ]);
        $responseManual->assertRedirect(route('committee.rounds.show', [$this->competition, $round]));
        $responseManual->assertSessionHas('error', 'Cannot perform bracket actions on a non-bracket round.');
    }

    public function test_committee_detail_view_shows_submissions_instead_of_brackets()
    {
        $round = Round::create([
            'competition_id' => $this->competition->id,
            'scoring_type_id' => $this->scoringType->id,
            'name' => 'Babak Umum',
            'round_order' => 1,
            'status' => 'active',
            'is_bracket' => false
        ]);

        $submission = Submission::create([
            'competition_id' => $this->competition->id,
            'round_id' => $round->id,
            'user_id' => $this->participant->id,
            'submission_url' => 'https://github.com/test/project',
            'final_score' => 95.0,
            'status' => 'scored'
        ]);

        $response = $this->actingAs($this->committee)
            ->get(route('committee.rounds.show', [$this->competition, $round]));

        $response->assertStatus(200);
        $response->assertSee('Detail Babak: Babak Umum');
        $response->assertSee($this->participant->name);
        $response->assertSee('Scored');
        $response->assertSee('95 pts');
        $response->assertDontSee('⚡ Auto Generate Bagan');
    }

    public function test_participant_view_displays_general_pool_notice()
    {
        $round = Round::create([
            'competition_id' => $this->competition->id,
            'scoring_type_id' => $this->scoringType->id,
            'name' => 'Babak Umum',
            'round_order' => 1,
            'status' => 'active',
            'is_bracket' => false
        ]);

        $response = $this->actingAs($this->participant)
            ->get(route('participant.my-competitions.show', $this->competition));

        $response->assertStatus(200);
        $response->assertSee('Babak Umum: Semua peserta bersaing langsung tanpa bagan tanding (Bracket).');
    }
}
