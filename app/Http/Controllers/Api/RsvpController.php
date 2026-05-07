<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Illuminate\Http\Request;

class RsvpController extends Controller
{
    /**
     * Get all RSVPs for invitation
     */
    public function index(Request $request, $invitationId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)
            ->findOrFail($invitationId);

        $rsvps = $invitation->rsvps()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'rsvps' => $rsvps,
        ]);
    }
}
