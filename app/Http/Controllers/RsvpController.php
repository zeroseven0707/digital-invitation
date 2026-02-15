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

        return redirect()->back()->with('success', 'Terima kasih! RSVP Anda telah diterima.');
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
