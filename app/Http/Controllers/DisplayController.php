<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;

class DisplayController extends Controller
{
    /**
     * Show the gerbang/display screen for a given invitation.
     */
    public function show(string $uniqueUrl)
    {
        $invitation = Invitation::where('unique_url', $uniqueUrl)
            ->where('status', 'published')
            ->with('galleries')
            ->firstOrFail();

        $checkedInCount = $invitation->guests()->whereNotNull('checked_in_at')->count();
        $totalGuests    = $invitation->guests()->count();

        return view('public.display', compact('invitation', 'checkedInCount', 'totalGuests'));
    }

    /**
     * SSE stream endpoint — DISABLED for php artisan serve (single-threaded).
     * Use polling fallback instead. Enable this only with nginx/apache + php-fpm.
     */
    public function stream(string $uniqueUrl)
    {
        // Redirect to display page — SSE not supported in single-threaded dev server
        return redirect()->route('display.show', $uniqueUrl);
    }
}
