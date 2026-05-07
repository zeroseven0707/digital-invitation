<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    /**
     * Get statistics for invitation
     */
    public function show(Request $request, $invitationId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)
            ->withCount(['views', 'guests', 'rsvps'])
            ->findOrFail($invitationId);

        // Get device statistics
        $mobileViews = $invitation->views()->where('device_type', 'mobile')->count();
        $desktopViews = $invitation->views()->where('device_type', 'desktop')->count();
        $tabletViews = $invitation->views()->where('device_type', 'tablet')->count();

        // Get last activity
        $lastView = $invitation->views()->orderBy('viewed_at', 'desc')->first();
        $lastRsvp = $invitation->rsvps()->latest()->first();

        $statistics = [
            'views_count' => $invitation->views_count,
            'guests_count' => $invitation->guests_count,
            'rsvps_count' => $invitation->rsvps_count,
            'shares_count' => 0, // TODO: implement share tracking
            'mobile_views' => $mobileViews,
            'desktop_views' => $desktopViews,
            'tablet_views' => $tabletViews,
            'last_viewed_at' => $lastView ? $lastView->viewed_at->diffForHumans() : null,
            'last_rsvp_at' => $lastRsvp ? $lastRsvp->created_at->diffForHumans() : null,
        ];

        return response()->json([
            'success' => true,
            'statistics' => $statistics,
        ]);
    }
}
