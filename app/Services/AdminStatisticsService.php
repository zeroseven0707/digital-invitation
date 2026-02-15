<?php

namespace App\Services;

use App\Models\Guest;
use App\Models\Invitation;
use App\Models\InvitationView;
use App\Models\User;
use Carbon\Carbon;

class AdminStatisticsService
{
    /**
     * Get platform-wide statistics
     */
    public function getPlatformStats(): array
    {
        return [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_invitations' => Invitation::count(),
            'published_invitations' => Invitation::where('status', 'published')->count(),
            'draft_invitations' => Invitation::where('status', 'draft')->count(),
            'total_views' => InvitationView::count(),
            'total_guests' => Guest::count(),
        ];
    }

    /**
     * Get user growth data for the last N days
     */
    public function getUserGrowth(int $days = 30): array
    {
        $startDate = Carbon::now()->subDays($days)->startOfDay();

        $users = User::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Fill in missing dates with zero counts
        $dates = [];
        $counts = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dates[] = $date;

            $userCount = $users->firstWhere('date', $date);
            $counts[] = $userCount ? $userCount->count : 0;
        }

        return [
            'dates' => $dates,
            'counts' => $counts,
        ];
    }

    /**
     * Get invitation growth data for the last N days
     */
    public function getInvitationGrowth(int $days = 30): array
    {
        $startDate = Carbon::now()->subDays($days)->startOfDay();

        $invitations = Invitation::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Fill in missing dates with zero counts
        $dates = [];
        $counts = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dates[] = $date;

            $invitationCount = $invitations->firstWhere('date', $date);
            $counts[] = $invitationCount ? $invitationCount->count : 0;
        }

        return [
            'dates' => $dates,
            'counts' => $counts,
        ];
    }

    /**
     * Get view growth data for the last N days
     */
    public function getViewGrowth(int $days = 30): array
    {
        $startDate = Carbon::now()->subDays($days)->startOfDay();

        $views = InvitationView::where('viewed_at', '>=', $startDate)
            ->selectRaw('DATE(viewed_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Fill in missing dates with zero counts
        $dates = [];
        $counts = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dates[] = $date;

            $viewCount = $views->firstWhere('date', $date);
            $counts[] = $viewCount ? $viewCount->count : 0;
        }

        return [
            'dates' => $dates,
            'counts' => $counts,
        ];
    }

    /**
     * Get transaction (paid invitations) growth data for the last N days
     */
    public function getTransactionGrowth(int $days = 30): array
    {
        $startDate = Carbon::now()->subDays($days)->startOfDay();

        $transactions = Invitation::where('is_paid', true)
            ->where('paid_at', '>=', $startDate)
            ->selectRaw('DATE(paid_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Fill in missing dates with zero counts
        $dates = [];
        $counts = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dates[] = $date;

            $transactionCount = $transactions->firstWhere('date', $date);
            $counts[] = $transactionCount ? $transactionCount->count : 0;
        }

        return [
            'dates' => $dates,
            'counts' => $counts,
        ];
    }

    /**
     * Get top users by invitation count
     */
    public function getTopUsersByInvitations(int $limit = 10): array
    {
        return User::withCount('invitations')
            ->orderBy('invitations_count', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'invitations_count' => $user->invitations_count,
                ];
            })
            ->toArray();
    }

    /**
     * Get top invitations by view count
     */
    public function getTopInvitationsByViews(int $limit = 10): array
    {
        return Invitation::withCount('views')
            ->with('user:id,name,email')
            ->where('status', 'published')
            ->orderBy('views_count', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($invitation) {
                return [
                    'id' => $invitation->id,
                    'couple' => "{$invitation->bride_name} & {$invitation->groom_name}",
                    'user' => $invitation->user->name,
                    'views_count' => $invitation->views_count,
                ];
            })
            ->toArray();
    }
}
