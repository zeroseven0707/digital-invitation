<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Display a listing of all users with search and filter capabilities.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $query = User::query()->withCount('invitations');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by role
        if ($request->filled('role')) {
            $role = $request->input('role');
            if ($role === 'admin') {
                $query->where('is_admin', true);
            } elseif ($role === 'user') {
                $query->where('is_admin', false);
            }
        }

        $users = $query->latest()->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display the specified user with their invitations list.
     *
     * @param string $id
     * @return View
     */
    public function show(string $id): View
    {
        $user = User::with(['invitations' => function ($query) {
            $query->latest();
        }])->findOrFail($id);

        // Get user statistics
        $totalInvitations = $user->invitations->count();
        $publishedInvitations = $user->invitations->where('status', 'published')->count();
        $draftInvitations = $user->invitations->where('status', 'draft')->count();

        // Get total views across all user's invitations
        $totalViews = $user->invitations->sum(function ($invitation) {
            return $invitation->views->count();
        });

        return view('admin.users.show', compact(
            'user',
            'totalInvitations',
            'publishedInvitations',
            'draftInvitations',
            'totalViews'
        ));
    }

    /**
     * Deactivate a user (set is_active = false).
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function deactivate(string $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        // Prevent self-deactivation (check this first)
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'You cannot deactivate your own account.');
        }

        // Prevent deactivating admin users
        if ($user->is_admin) {
            return redirect()->back()
                ->with('error', 'Cannot deactivate admin users.');
        }

        $user->update(['is_active' => false]);

        return redirect()->back()
            ->with('success', "User {$user->name} has been deactivated successfully.");
    }

    /**
     * Activate a user (set is_active = true).
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function activate(string $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $user->update(['is_active' => true]);

        return redirect()->back()
            ->with('success', "User {$user->name} has been activated successfully.");
    }

    /**
     * Activate payment for an invitation.
     *
     * @param string $userId
     * @param string $invitationId
     * @return RedirectResponse
     */
    public function activatePayment(string $userId, string $invitationId): RedirectResponse
    {
        $user = User::findOrFail($userId);
        $invitation = $user->invitations()->findOrFail($invitationId);

        $invitation->update([
            'is_paid' => true,
            'paid_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', "Pembayaran untuk undangan {$invitation->bride_name} & {$invitation->groom_name} telah diaktifkan.");
    }

    /**
     * Deactivate payment for an invitation.
     *
     * @param string $userId
     * @param string $invitationId
     * @return RedirectResponse
     */
    public function deactivatePayment(string $userId, string $invitationId): RedirectResponse
    {
        $user = User::findOrFail($userId);
        $invitation = $user->invitations()->findOrFail($invitationId);

        $invitation->update([
            'is_paid' => false,
            'paid_at' => null,
        ]);

        // If invitation is published, unpublish it
        if ($invitation->status === 'published') {
            $invitation->update(['status' => 'draft']);
        }

        return redirect()->back()
            ->with('success', "Pembayaran untuk undangan {$invitation->bride_name} & {$invitation->groom_name} telah dinonaktifkan.");
    }
}
