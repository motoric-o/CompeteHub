<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Committee\Concerns\CommitteeAuthorization;
use App\Models\Competition;
use App\Services\Notification\NotificationLogService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * NotificationLogController — Feature 6: Auto Notification Log UI.
 *
 * Displays static notification history for a competition.
 * Blade-only (no real-time). Supports filter by event_type and status.
 */
class NotificationLogController extends Controller
{
    use CommitteeAuthorization;

    public function __construct(
        private NotificationLogService $logService,
    ) {}

    /**
     * Show notification log for a competition.
     * GET /committee/competitions/{competition}/notification-log
     */
    public function index(Request $request, Competition $competition): View
    {
        $this->authorizeCommittee($competition);

        $logs = $this->logService->getForCompetition(
            competitionId: $competition->id,
            eventType:     $request->input('event_type'),
            status:        $request->input('status'),
            perPage:       25,
        );

        $summary    = $this->logService->getSummaryForCompetition($competition->id);
        $eventTypes = NotificationLogService::getEventTypes();

        return view('committee.notification-log.index', compact(
            'competition',
            'logs',
            'summary',
            'eventTypes',
        ));
    }
}
