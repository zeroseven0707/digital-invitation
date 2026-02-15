<?php

namespace App\Http\Controllers;

use App\Services\AdminStatisticsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    protected AdminStatisticsService $statisticsService;

    public function __construct(AdminStatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    /**
     * Display the admin dashboard with platform statistics.
     *
     * @return View
     */
    public function dashboard(): View
    {
        // Get stats same as welcome page
        $platformStats = [
            'totalInvitations' => \App\Models\Invitation::count(),
            'totalTemplates' => \App\Models\Template::where('is_active', true)->count(),
            'totalViews' => \App\Models\InvitationView::count(),
            'totalRsvps' => \App\Models\Rsvp::count(),
            'totalUsers' => \App\Models\User::count(),
            'paidInvitations' => \App\Models\Invitation::where('is_paid', true)->count(),
            'publishedInvitations' => \App\Models\Invitation::where('status', 'published')->count(),
        ];

        // Get growth data for charts (last 30 days)
        $userGrowthData = $this->statisticsService->getUserGrowth(30);
        $transactionGrowthData = $this->statisticsService->getTransactionGrowth(30);

        // Transform growth data to collections for view
        $userGrowth = collect($userGrowthData['dates'])->map(function ($date, $index) use ($userGrowthData) {
            return (object) [
                'date' => $date,
                'count' => $userGrowthData['counts'][$index]
            ];
        });

        $transactionGrowth = collect($transactionGrowthData['dates'])->map(function ($date, $index) use ($transactionGrowthData) {
            return (object) [
                'date' => $date,
                'count' => $transactionGrowthData['counts'][$index]
            ];
        });

        // Get top users and invitations
        $topUsers = \App\Models\User::withCount('invitations')
            ->orderBy('invitations_count', 'desc')
            ->limit(5)
            ->get();

        $topInvitations = \App\Models\Invitation::withCount('views')
            ->where('status', 'published')
            ->orderBy('views_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'platformStats',
            'userGrowth',
            'transactionGrowth',
            'topUsers',
            'topInvitations'
        ));
    }

    /**
     * Display list of all invitations with payment status.
     *
     * @param Request $request
     * @return View
     */
    public function invitations(Request $request): View
    {
        $query = \App\Models\Invitation::with(['user', 'template'])
            ->withCount(['views', 'guests']);

        // Filter by payment status
        if ($request->has('payment_status')) {
            if ($request->payment_status === 'paid') {
                $query->where('is_paid', true);
            } elseif ($request->payment_status === 'unpaid') {
                $query->where('is_paid', false);
            }
        }

        // Filter by publish status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Search by title or user name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $invitations = $query->paginate(20);

        return view('admin.invitations.index', compact('invitations'));
    }

    /**
     * Activate payment for an invitation.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activateInvitationPayment(int $id)
    {
        $invitation = \App\Models\Invitation::findOrFail($id);

        $invitation->update([
            'is_paid' => true,
            'paid_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Pembayaran undangan berhasil diaktifkan!');
    }

    /**
     * Deactivate payment for an invitation.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deactivateInvitationPayment(int $id)
    {
        $invitation = \App\Models\Invitation::findOrFail($id);

        $invitation->update([
            'is_paid' => false,
            'paid_at' => null,
        ]);

        return redirect()->back()->with('success', 'Pembayaran undangan berhasil dinonaktifkan!');
    }
}

