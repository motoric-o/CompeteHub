<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTeamRequest;
use App\Http\Requests\JoinTeamRequest;
use App\Models\Competition;
use App\Models\Team;
use App\Models\User;
use App\Services\TeamService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * TeamController — Controller untuk fitur Manajemen Tim (F-07).
 *
 * Controller ini tipis (thin controller): hanya menerima request,
 * meneruskan ke TeamService, dan mengembalikan response/view.
 * Seluruh business logic ada di TeamService (SRP).
 */
class TeamController extends Controller
{
    public function __construct(
        private TeamService $teamService
    ) {}

    /**
     * Tampilkan daftar tim milik user yang sedang login.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Tim yang dikaptenin
        $captainedTeams = Team::where('user_id', $user->id)
            ->with(['competition', 'members'])
            ->get();

        // Tim yang diikuti sebagai anggota (bukan kapten)
        $memberTeams = $user->teams()
            ->where('teams.user_id', '!=', $user->id)
            ->with(['competition', 'captain', 'members'])
            ->get();

        // Kompetisi bertipe team yang bisa didaftari
        $availableCompetitions = Competition::where('type', 'team')
            ->where('status', 'open')
            ->get();

        return view('teams.index', compact(
            'user',
            'captainedTeams',
            'memberTeams',
            'availableCompetitions'
        ));
    }

    /**
     * Tampilkan form pembuatan tim.
     */
    public function create(Request $request): View
    {
        $competitions = Competition::where('type', 'team')
            ->where('status', 'open')
            ->get()
            ->filter(fn($comp) => $comp->isRegistrationOpen());

        $selectedCompetitionId = $request->query('competition_id');

        return view('teams.create', compact('competitions', 'selectedCompetitionId'));
    }

    /**
     * Simpan tim baru.
     */
    public function store(CreateTeamRequest $request): RedirectResponse
    {
        $user = $request->user();
        $competition = Competition::findOrFail($request->validated('competition_id'));

        try {
            $team = $this->teamService->createTeam(
                $user,
                $competition,
                $request->validated('name')
            );

            return redirect()
                ->route('teams.show', $team)
                ->with('success', "Tim \"{$team->name}\" berhasil dibuat! Kode undangan: {$team->invite_code}");
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Tampilkan detail tim dan daftar anggota.
     */
    public function show(Team $team): View
    {
        $team->load(['competition', 'captain', 'members']);

        return view('teams.show', compact('team'));
    }

    /**
     * Join tim via invite code.
     */
    public function join(JoinTeamRequest $request): RedirectResponse
    {
        $user = $request->user();

        try {
            $team = $this->teamService->joinByInviteCode(
                $user,
                strtoupper($request->validated('invite_code'))
            );

            return redirect()
                ->route('teams.show', $team)
                ->with('success', "Berhasil bergabung ke tim \"{$team->name}\"!");
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Keluarkan anggota dari tim (hanya kapten).
     */
    public function kick(Request $request, Team $team, User $member): RedirectResponse
    {
        $captain = $request->user();

        try {
            $this->teamService->kickMember($captain, $team, $member);

            return redirect()
                ->route('teams.show', $team)
                ->with('success', "{$member->name} telah dikeluarkan dari tim.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Keluar dari tim secara sukarela.
     */
    public function leave(Request $request, Team $team): RedirectResponse
    {
        $user = $request->user();

        try {
            $this->teamService->leaveTeam($user, $team);

            return redirect()
                ->route('teams.index')
                ->with('success', "Anda telah keluar dari tim \"{$team->name}\".");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Regenerate kode undangan (hanya kapten).
     */
    public function regenerateCode(Request $request, Team $team): RedirectResponse
    {
        $captain = $request->user();

        try {
            $newCode = $this->teamService->regenerateInviteCode($captain, $team);

            return redirect()
                ->route('teams.show', $team)
                ->with('success', "Kode undangan baru: {$newCode}");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
