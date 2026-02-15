<?php

namespace App\Http\Controllers;

use App\Services\AdminStatisticsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    protected AdminStatisticsService $statisticsService;

    public function __construct(AdminStatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    /**
     * Display the admin dashboard with platform statistics.
     *
     * @return View
     */
    public function dashboard(): View
    {
        // Get platform statistics
        $stats = $this->statisticsService->getPlatformStats();

        // Transform to camelCase for view
        $platformStats = [
            'totalUsers' => $stats['total_users'],
            'activeUsers' => $stats['active_users'],
            'totalInvitations' => $stats['total_invitations'],
            'publishedInvitations' => $stats['published_invitations'],
            'draftInvitations' => $stats['draft_invitations'],
            'totalViews' => $stats['total_views'],
            'totalGuests' => $stats['total_guests'],
            'totalTemplates' => \App\Models\Template::count(),
        ];

        // Get growth data for charts (last 30 days)
        $userGrowthData = $this->statisticsService->getUserGrowth(30);
        $invitationGrowthData = $this->statisticsService->getInvitationGrowth(30);
        $viewGrowthData = $this->statisticsService->getViewGrowth(30);

        // Transform growth data to collections for view
        $userGrowth = collect($userGrowthData['dates'])->map(function ($date, $index) use ($userGrowthData) {
            return (object) [
                'date' => $date,
                'count' => $userGrowthData['counts'][$index]
            ];
        });

        $invitationGrowth = collect($invitationGrowthData['dates'])->map(function ($date, $index) use ($invitationGrowthData) {
            return (object) [
                'date' => $date,
                'count' => $invitationGrowthData['counts'][$index]
            ];
        });

        $viewGrowth = collect($viewGrowthData['dates'])->map(function ($date, $index) use ($viewGrowthData) {
            return (object) [
                'date' => $date,
                'count' => $viewGrowthData['counts'][$index]
            ];
        });

        // Get top users and invitations
        $topUsers = \App\Models\User::withCount('invitations')
            ->orderBy('invitations_count', 'desc')
            ->limit(5)
            ->get();

        $topInvitations = \App\Models\Invitation::withCount('views')
            ->where('status', 'published')
            ->orderBy('views_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'platformStats',
            'userGrowth',
            'invitationGrowth',
            'viewGrowth',
            'topUsers',
            'topInvitations'
        ));
    }
}

