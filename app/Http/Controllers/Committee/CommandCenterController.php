<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Committee\Concerns\CommitteeAuthorization;
use App\Models\Competition;
use App\Services\Dashboard\CommandCenterService;
use Illuminate\View\View;

/**
 * CommandCenterController — Feature 1: Competition Command Center.
 *
 * Provides the operational dashboard view for committee members.
 * All heavy lifting is done by CommandCenterService.
 * Controller only: authorize + delegate + return view.
 */
class CommandCenterController extends Controller
{
    use CommitteeAuthorization;

    public function __construct(
        private CommandCenterService $commandCenterService,
    ) {}

    /**
     * Show the command center for a specific competition.
     */
    public function show(Competition $competition): View
    {
        $this->authorizeCommittee($competition);

        $competition->load(['formTemplates', 'rounds']);

        $dashboard = $this->commandCenterService->buildCommandCenter($competition);

        return view('committee.command-center.show', compact('competition', 'dashboard'));
    }
}
