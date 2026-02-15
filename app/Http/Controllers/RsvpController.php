<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Rsvp;
use Illuminate\Http\Request;

class RsvpController extends Controller
{
    public function store(Request $request, $uniqueUrl)
    {
        $invitation = Invitation::where('unique_url', $uniqueUrl)
            ->where('status', 'published')
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'message' => 'required|string|max:500',
        ]);

        $rsvp = $invitation->rsvps()->create($validated);

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Terima kasih! Ucapan Anda telah diterima.',
                'rsvp' => [
                    'id' => $rsvp->id,
                    'name' => $rsvp->name,
                    'message' => $rsvp->message,
                    'created_at' => $rsvp->created_at->diffForHumans(),
                ]
            ]);
        }

        return redirect()->back()->with('success', 'Terima kasih! RSVP Anda telah diterima.');
    }

    /**
     * Get latest RSVPs for an invitation (AJAX endpoint)
     */
    public function latest(Request $request, $uniqueUrl)
    {
        $invitation = Invitation::where('unique_url', $uniqueUrl)
            ->where('status', 'published')
            ->firstOrFail();

        $lastId = $request->input('last_id', 0);

        $rsvps = $invitation->rsvps()
            ->when($lastId > 0, function($query) use ($lastId) {
                return $query->where('id', '>', $lastId);
            })
            ->latest()
            ->take(10)
            ->get()
            ->map(function($rsvp) {
                return [
                    'id' => $rsvp->id,
                    'name' => $rsvp->name,
                    'message' => $rsvp->message,
                    'created_at' => $rsvp->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'success' => true,
            'rsvps' => $rsvps,
            'count' => $invitation->rsvps()->count(),
        ]);
    }

    public function index(Request $request, $invitationId)
    {
        $invitation = Invitation::with('rsvps')->findOrFail($invitationId);

        // Check if user owns this invitation
        if ($invitation->user_id !== auth()->id()) {
            abort(403);
        }

        $rsvps = $invitation->rsvps()->latest()->get();

        return view('rsvps.index', compact('invitation', 'rsvps'));
    }
}
