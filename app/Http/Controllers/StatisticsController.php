<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Services\StatisticsService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StatisticsController extends Controller
{
    protected StatisticsService $statisticsService;

    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    /**
     * Display statistics page for an invitation.
     *
     * @param string $invitationId
     * @return View
     */
    public function show(string $invitationId): View
    {
        $invitation = Invitation::findOrFail($invitationId);

        // Authorization: users can only view statistics for their own invitations
        if ($invitation->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $totalViews = $this->statisticsService->getTotalViews($invitation);

        return view('statistics.show', compact('invitation', 'totalViews'));
    }

    /**
     * API endpoint that returns JSON data for views chart (views per day for last 30 days).
     *
     * @param string $invitationId
     * @return JsonResponse
     */
    public function getViewsChart(string $invitationId): JsonResponse
    {
        $invitation = Invitation::findOrFail($invitationId);

        // Authorization: users can only view statistics for their own invitations
        if ($invitation->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $endDate = Carbon::today();
        $startDate = Carbon::today()->subDays(29); // Last 30 days including today

        $viewsData = $this->statisticsService->getViewsByDateRange($invitation, $startDate, $endDate);

        // Create a complete date range with zero counts for missing dates
        $dateRange = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $dateRange[$currentDate->format('Y-m-d')] = 0;
            $currentDate->addDay();
        }

        // Fill in actual view counts
        foreach ($viewsData as $view) {
            $dateRange[$view->date] = $view->count;
        }

        // Format for chart
        $labels = array_keys($dateRange);
        $data = array_values($dateRange);

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }

    /**
     * API endpoint that returns JSON data for device breakdown.
     *
     * @param string $invitationId
     * @return JsonResponse
     */
    public function getDeviceStats(string $invitationId): JsonResponse
    {
        $invitation = Invitation::findOrFail($invitationId);

        // Authorization: users can only view statistics for their own invitations
        if ($invitation->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $deviceBreakdown = $this->statisticsService->getDeviceBreakdown($invitation);
        $browserBreakdown = $this->statisticsService->getBrowserBreakdown($invitation);

        return response()->json([
            'devices' => $deviceBreakdown,
            'browsers' => $browserBreakdown,
        ]);
    }
}
