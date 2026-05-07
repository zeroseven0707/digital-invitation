<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

class WelcomeGuestController extends Controller
{
    /**
     * Show the welcome page for a guest identified by their QR token.
     * Automatically marks the guest as checked-in on first visit.
     */
    public function show(string $qrToken, Request $request)
    {
        $guest = Guest::where('qr_token', $qrToken)
            ->with('invitation.galleries')
            ->first();

        if (!$guest) {
            abort(404, 'QR code tidak valid.');
        }

        $invitation = $guest->invitation;

        // Auto check-in on first scan
        $alreadyCheckedIn = !is_null($guest->checked_in_at);
        if (!$alreadyCheckedIn) {
            $guest->update(['checked_in_at' => now()]);
            $guest->refresh();
        }

        $categoryLabels = [
            'family'    => 'Keluarga',
            'friend'    => 'Teman',
            'colleague' => 'Rekan',
        ];

        return view('public.welcome-guest', [
            'guest'            => $guest,
            'invitation'       => $invitation,
            'alreadyCheckedIn' => $alreadyCheckedIn,
            'categoryLabel'    => $categoryLabels[$guest->category] ?? 'Tamu',
            'checkedInAt'      => $guest->checked_in_at,
        ]);
    }
}
