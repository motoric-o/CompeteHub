<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\Round;
use App\Models\Submission;
use App\Http\Requests\Participant\StoreSubmissionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    /**
     * Helper to verify if user has an active, verified registration for this competition,
     * and check if user is allowed to submit (individual, or captain of the team).
     */
    private function verifyRegistration(Competition $competition)
    {
        $user = auth()->user();
        
        $query = Registration::where('competition_id', $competition->id);
        
        if ($competition->isTeamBased()) {
            // Find user's team for this competition
            $team = $user->teams()->where('competition_id', $competition->id)->first();
            if (!$team) {
                abort(403, 'You are not in a team for this competition.');
            }
            $query->where('team_id', $team->id);
        } else {
            $query->where('user_id', $user->id);
        }
        
        $registration = $query->first();
        
        if (!$registration || !in_array($registration->status, ['verified', 'payment_ok'])) {
            abort(403, 'Your registration is not verified yet.');
        }

        if ($competition->isTeamBased()) {
            $team = $registration->team;
            if (!$team->isCaptain($user)) {
                abort(403, 'Only the team captain can upload submissions.');
            }
            return ['registration' => $registration, 'team_id' => $team->id, 'user_id' => null];
        }

        return ['registration' => $registration, 'team_id' => null, 'user_id' => $user->id];
    }

    public function index(Competition $competition)
    {
        $data = $this->verifyRegistration($competition);
        
        $rounds = $competition->rounds()->orderBy('round_order')->get();
        
        // Get existing submissions
        $submissions = Submission::where('competition_id', $competition->id);
        if ($data['team_id']) {
            $submissions->where('team_id', $data['team_id']);
        } else {
            $submissions->where('user_id', $data['user_id']);
        }
        $submissions = $submissions->get()->keyBy('round_id');

        return view('participant.submissions.index', compact('competition', 'rounds', 'submissions'));
    }

    public function create(Competition $competition, Round $round)
    {
        if ($round->competition_id !== $competition->id) {
            abort(404);
        }

        $data = $this->verifyRegistration($competition);

        // Check if round is active
        if ($round->start_date && $round->start_date > now()) {
            return redirect()->route('participant.submissions.index', $competition)->with('error', 'This round has not started yet.');
        }
        if ($round->end_date && $round->end_date < now()) {
            return redirect()->route('participant.submissions.index', $competition)->with('error', 'The deadline for this round has passed.');
        }

        // Check for existing submission
        $submission = Submission::where('competition_id', $competition->id)
            ->where('round_id', $round->id)
            ->where(function($q) use ($data) {
                if ($data['team_id']) {
                    $q->where('team_id', $data['team_id']);
                } else {
                    $q->where('user_id', $data['user_id']);
                }
            })->first();

        return view('participant.submissions.create', compact('competition', 'round', 'submission'));
    }

    public function store(StoreSubmissionRequest $request, Competition $competition, Round $round)
    {
        if ($round->competition_id !== $competition->id) {
            abort(404);
        }

        $data = $this->verifyRegistration($competition);

        // Check if round is active
        if ($round->end_date && $round->end_date < now()) {
            return redirect()->route('participant.submissions.index', $competition)->with('error', 'The deadline for this round has passed.');
        }

        $file = $request->file('submission_file');
        $filePath = $file->store('submissions/' . $competition->id . '/' . $round->id, 'public');

        // Upsert submission
        $submission = Submission::where('competition_id', $competition->id)
            ->where('round_id', $round->id)
            ->where(function($q) use ($data) {
                if ($data['team_id']) {
                    $q->where('team_id', $data['team_id']);
                } else {
                    $q->where('user_id', $data['user_id']);
                }
            })->first();

        if ($submission) {
            // Delete old file if exists
            if ($submission->file_path) {
                Storage::disk('public')->delete($submission->file_path);
            }
            $submission->update([
                'file_path' => $filePath,
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'status' => 'submitted',
                // 'submitted_at' is handled by DB defaults, but if it's an update, maybe update timestamp?
                // The schema says submitted_at useCurrent() and DO NOT UPDATE. 
                // We leave it or update `updated_at` only.
            ]);
        } else {
            Submission::create([
                'competition_id' => $competition->id,
                'round_id' => $round->id,
                'user_id' => $data['user_id'],
                'team_id' => $data['team_id'],
                'file_path' => $filePath,
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'status' => 'submitted',
            ]);
        }

        return redirect()->route('participant.submissions.index', $competition)->with('success', 'Submission uploaded successfully!');
    }
}
