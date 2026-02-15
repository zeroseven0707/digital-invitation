<?php

namespace App\Services;

use App\Models\Invitation;
use App\Models\InvitationView;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StatisticsService
{
    /**
     * Get total view count for an invitation.
     *
     * @param Invitation $invitation
     * @return int
     */
    public function getTotalViews(Invitation $invitation): int
    {
        return InvitationView::where('invitation_id', $invitation->id)->count();
    }

    /**
     * Get views grouped by date within a date range.
     *
     * @param Invitation $invitation
     * @param Carbon $start
     * @param Carbon $end
     * @return Collection
     */
    public function getViewsByDateRange(Invitation $invitation, Carbon $start, Carbon $end): Collection
    {
        return InvitationView::where('invitation_id', $invitation->id)
            ->whereBetween('viewed_at', [$start, $end])
            ->select(DB::raw('DATE(viewed_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Get breakdown of views by device type (desktop, mobile, tablet).
     *
     * @param Invitation $invitation
     * @return array
     */
    public function getDeviceBreakdown(Invitation $invitation): array
    {
        $breakdown = InvitationView::where('invitation_id', $invitation->id)
            ->select('device_type', DB::raw('COUNT(*) as count'))
            ->groupBy('device_type')
            ->get()
            ->pluck('count', 'device_type')
            ->toArray();

        return [
            'desktop' => $breakdown['desktop'] ?? 0,
            'mobile' => $breakdown['mobile'] ?? 0,
            'tablet' => $breakdown['tablet'] ?? 0,
        ];
    }

    /**
     * Get breakdown of views by browser.
     *
     * @param Invitation $invitation
     * @return array
     */
    public function getBrowserBreakdown(Invitation $invitation): array
    {
        return InvitationView::where('invitation_id', $invitation->id)
            ->select('browser', DB::raw('COUNT(*) as count'))
            ->groupBy('browser')
            ->orderByDesc('count')
            ->get()
            ->pluck('count', 'browser')
            ->toArray();
    }
}
