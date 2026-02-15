<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard with invitations and statistics.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Get all invitations for the authenticated user
        $invitations = $user->invitations()
            ->with(['template', 'guests', 'views'])
            ->latest()
            ->get();

        // Calculate statistics
        $statistics = [
            'total_invitations' => $invitations->count(),
            'total_guests' => $invitations->sum(function ($invitation) {
                return $invitation->guests->count();
            }),
            'total_views' => $invitations->sum(function ($invitation) {
                return $invitation->views->count();
            }),
        ];

        return view('dashboard', [
            'invitations' => $invitations,
            'statistics' => $statistics,
        ]);
    }
}
