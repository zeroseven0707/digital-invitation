<?php

namespace App\Services;

use App\Models\Invitation;
use App\Models\InvitationView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent;

class InvitationViewTracker
{
    /**
     * Track a view for the given invitation.
     * Prevents duplicate views from the same IP within 24 hours.
     *
     * @param Invitation $invitation
     * @param Request $request
     * @return void
     */
    public function trackView(Invitation $invitation, Request $request): void
    {
        $ipAddress = $request->ip();

        // Check if this IP has viewed this invitation in the last 24 hours
        $existingView = InvitationView::where('invitation_id', $invitation->id)
            ->where('ip_address', $ipAddress)
            ->where('viewed_at', '>=', now()->subHours(24))
            ->first();

        // If already viewed within 24 hours, don't track again
        if ($existingView) {
            return;
        }

        // Parse user agent to detect device type and browser
        $userAgent = $request->userAgent();
        $agent = new Agent();
        $agent->setUserAgent($userAgent);

        $deviceType = $this->detectDeviceType($agent);
        $browser = $this->detectBrowser($agent);

        // Create new view record
        try {
            InvitationView::create([
                'invitation_id' => $invitation->id,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'device_type' => $deviceType,
                'browser' => $browser,
                'viewed_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Log error but don't fail the request
            Log::error('Failed to track invitation view', [
                'invitation_id' => $invitation->id,
                'ip_address' => $ipAddress,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Detect device type from user agent.
     *
     * @param Agent $agent
     * @return string
     */
    protected function detectDeviceType(Agent $agent): string
    {
        if ($agent->isDesktop()) {
            return 'desktop';
        }

        if ($agent->isTablet()) {
            return 'tablet';
        }

        if ($agent->isMobile()) {
            return 'mobile';
        }

        return 'unknown';
    }

    /**
     * Detect browser from user agent.
     *
     * @param Agent $agent
     * @return string
     */
    protected function detectBrowser(Agent $agent): string
    {
        $browser = $agent->browser();

        if ($browser) {
            return $browser;
        }

        return 'unknown';
    }

    /**
     * Get total view count for an invitation.
     *
     * @param Invitation $invitation
     * @return int
     */
    public function getViewCount(Invitation $invitation): int
    {
        return InvitationView::where('invitation_id', $invitation->id)->count();
    }

    /**
     * Get views by date for the given number of days.
     *
     * @param Invitation $invitation
     * @param int $days
     * @return array
     */
    public function getViewsByDate(Invitation $invitation, int $days = 30): array
    {
        $views = InvitationView::where('invitation_id', $invitation->id)
            ->where('viewed_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(viewed_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $views->pluck('count', 'date')->toArray();
    }
}
