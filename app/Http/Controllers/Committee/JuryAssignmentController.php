<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\JuryAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class JuryAssignmentController extends Controller
{
    public function index(Competition $competition): View
    {
        if ($competition->user_id !== auth()->id()) {
            abort(403);
        }

        $assignments = $competition->juryAssignments()->with('user')->get();
        // Get judges that are not yet assigned to this competition
        $availableJudges = User::where('role', 'judge')
            ->whereNotIn('id', $assignments->pluck('user_id'))
            ->get();

        return view('committee.competitions.juries.index', compact('competition', 'assignments', 'availableJudges'));
    }

    public function store(Request $request, Competition $competition): RedirectResponse
    {
        if ($competition->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($validated['user_id']);
        if ($user->role !== 'judge') {
            return back()->with('error', 'User tersebut bukan juri.');
        }

        // Check if already assigned
        if ($competition->juryAssignments()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Juri ini sudah ditugaskan ke kompetisi.');
        }

        $competition->juryAssignments()->create([
            'user_id' => $user->id,
            'assigned_at' => now(),
        ]);

        return redirect()->route('committee.juries.index', $competition)
            ->with('success', 'Juri berhasil ditambahkan ke kompetisi.');
    }

    public function destroy(Competition $competition, JuryAssignment $jury): RedirectResponse
    {
        if ($competition->user_id !== auth()->id() || $jury->competition_id !== $competition->id) {
            abort(403);
        }

        $jury->delete();

        return redirect()->route('committee.juries.index', $competition)
            ->with('success', 'Tugas juri berhasil dicabut.');
    }
}
