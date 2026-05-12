<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GuestController extends Controller
{
    // ─────────────────────────────────────────────────────────────
    //  CRUD
    // ─────────────────────────────────────────────────────────────

    public function index(Request $request, $invitationId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)
            ->findOrFail($invitationId);

        $guests = $invitation->guests()->orderBy('name')->get();

        return response()->json(['success' => true, 'guests' => $guests]);
    }

    public function store(Request $request, $invitationId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)
            ->findOrFail($invitationId);

        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'category'        => 'required|in:family,friend,colleague,other',
            'phone'           => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255',
            'address'         => 'nullable|string|max:1000',
            'pax'             => 'nullable|integer|min:1|max:100',
        ]);

        // Sync: if phone provided but no whatsapp_number, use phone as whatsapp too
        if (!empty($validated['phone']) && empty($validated['whatsapp_number'])) {
            $validated['whatsapp_number'] = $validated['phone'];
        }

        $validated['pax']       = $validated['pax'] ?? 1;
        $validated['qr_token']  = $this->generateUniqueToken();

        $guest = $invitation->guests()->create($validated);

        return response()->json(['success' => true, 'message' => 'Tamu berhasil ditambahkan', 'guest' => $guest], 201);
    }

    public function update(Request $request, $invitationId, $guestId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)->findOrFail($invitationId);
        $guest      = $invitation->guests()->findOrFail($guestId);

        $validated = $request->validate([
            'name'            => 'sometimes|required|string|max:255',
            'category'        => 'sometimes|required|in:family,friend,colleague,other',
            'phone'           => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255',
            'address'         => 'nullable|string|max:1000',
            'pax'             => 'nullable|integer|min:1|max:100',
        ]);

        // Sync phone → whatsapp_number
        if (isset($validated['phone']) && !isset($validated['whatsapp_number'])) {
            $validated['whatsapp_number'] = $validated['phone'];
        }

        $guest->update($validated);

        return response()->json(['success' => true, 'message' => 'Tamu berhasil diperbarui', 'guest' => $guest->fresh()]);
    }

    public function destroy(Request $request, $invitationId, $guestId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)->findOrFail($invitationId);
        $invitation->guests()->findOrFail($guestId)->delete();

        return response()->json(['success' => true, 'message' => 'Guest deleted successfully']);
    }

    // ─────────────────────────────────────────────────────────────
    //  QR GENERATION
    // ─────────────────────────────────────────────────────────────

    public function generateQr(Request $request, $invitationId, $guestId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)->findOrFail($invitationId);
        $guest      = $invitation->guests()->findOrFail($guestId);

        $guest->update(['qr_token' => $this->generateUniqueToken()]);

        return response()->json(['success' => true, 'message' => 'QR token generated successfully', 'guest' => $guest->fresh()]);
    }

    // ─────────────────────────────────────────────────────────────
    //  CHECK-IN
    // ─────────────────────────────────────────────────────────────

    public function checkIn(Request $request, $invitationId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)->findOrFail($invitationId);

        $request->validate(['qr_token' => 'required|string']);

        $guest = $invitation->guests()->where('qr_token', $request->qr_token)->first();

        if (!$guest) {
            return response()->json(['success' => false, 'message' => 'QR code tidak valid atau tamu tidak ditemukan.'], 404);
        }

        if ($guest->checked_in_at) {
            return response()->json([
                'success'           => false,
                'already_checked_in'=> true,
                'message'           => 'Tamu sudah check-in sebelumnya.',
                'guest'             => $guest,
                'checked_in_at'     => $guest->checked_in_at->toIso8601String(),
            ], 409);
        }

        $guest->update(['checked_in_at' => now()]);
        $guest->refresh();

        return response()->json([
            'success'       => true,
            'message'       => 'Check-in berhasil!',
            'guest'         => $guest,
            'checked_in_at' => $guest->checked_in_at->toIso8601String(),
        ]);
    }

    public function resetCheckIn(Request $request, $invitationId, $guestId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)->findOrFail($invitationId);
        $guest      = $invitation->guests()->findOrFail($guestId);

        $guest->update(['checked_in_at' => null]);

        return response()->json(['success' => true, 'message' => 'Check-in status berhasil direset.', 'guest' => $guest->fresh()]);
    }

    // ─────────────────────────────────────────────────────────────
    //  SOUVENIR SCAN
    // ─────────────────────────────────────────────────────────────

    public function souvenirScan(Request $request, $invitationId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)->findOrFail($invitationId);

        $request->validate(['qr_token' => 'required|string']);

        $guest = $invitation->guests()->where('qr_token', $request->qr_token)->first();

        if (!$guest) {
            return response()->json(['success' => false, 'message' => 'QR code tidak valid atau tamu tidak ditemukan.'], 404);
        }

        if ($guest->souvenir_taken_at) {
            return response()->json([
                'success'              => false,
                'already_taken'        => true,
                'message'              => 'Tamu sudah mengambil souvenir sebelumnya.',
                'guest'                => $guest,
                'souvenir_taken_at'    => $guest->souvenir_taken_at->toIso8601String(),
            ], 409);
        }

        $guest->update(['souvenir_taken_at' => now()]);
        $guest->refresh();

        return response()->json([
            'success'           => true,
            'message'           => 'Souvenir berhasil dicatat!',
            'guest'             => $guest,
            'souvenir_taken_at' => $guest->souvenir_taken_at->toIso8601String(),
        ]);
    }

    public function resetSouvenir(Request $request, $invitationId, $guestId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)->findOrFail($invitationId);
        $guest      = $invitation->guests()->findOrFail($guestId);

        $guest->update(['souvenir_taken_at' => null]);

        return response()->json(['success' => true, 'message' => 'Status souvenir berhasil direset.', 'guest' => $guest->fresh()]);
    }

    // ─────────────────────────────────────────────────────────────
    //  SOUVENIR SCAN KE-2 (multi souvenir)
    // ─────────────────────────────────────────────────────────────

    public function souvenirScan2(Request $request, $invitationId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)->findOrFail($invitationId);

        $request->validate(['qr_token' => 'required|string']);

        $guest = $invitation->guests()->where('qr_token', $request->qr_token)->first();

        if (!$guest) {
            return response()->json(['success' => false, 'message' => 'QR code tidak valid atau tamu tidak ditemukan.'], 404);
        }

        // Souvenir ke-2 hanya bisa jika souvenir ke-1 sudah diambil
        if (!$guest->souvenir_taken_at) {
            return response()->json([
                'success' => false,
                'message' => 'Tamu belum mengambil souvenir pertama.',
            ], 422);
        }

        if ($guest->souvenir2_taken_at) {
            return response()->json([
                'success'               => false,
                'already_taken'         => true,
                'message'               => 'Tamu sudah mengambil souvenir ke-2.',
                'guest'                 => $guest,
                'souvenir2_taken_at'    => $guest->souvenir2_taken_at->toIso8601String(),
            ], 409);
        }

        $guest->update(['souvenir2_taken_at' => now()]);
        $guest->refresh();

        return response()->json([
            'success'            => true,
            'message'            => 'Souvenir ke-2 berhasil dicatat!',
            'guest'              => $guest,
            'souvenir2_taken_at' => $guest->souvenir2_taken_at->toIso8601String(),
        ]);
    }

    public function resetSouvenir2(Request $request, $invitationId, $guestId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)->findOrFail($invitationId);
        $guest      = $invitation->guests()->findOrFail($guestId);

        $guest->update(['souvenir2_taken_at' => null]);

        return response()->json(['success' => true, 'message' => 'Status souvenir ke-2 berhasil direset.', 'guest' => $guest->fresh()]);
    }

    // ─────────────────────────────────────────────────────────────
    //  CHECK-OUT
    // ─────────────────────────────────────────────────────────────

    public function checkOut(Request $request, $invitationId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)->findOrFail($invitationId);

        $request->validate(['qr_token' => 'required|string']);

        $guest = $invitation->guests()->where('qr_token', $request->qr_token)->first();

        if (!$guest) {
            return response()->json(['success' => false, 'message' => 'QR code tidak valid atau tamu tidak ditemukan.'], 404);
        }

        // Check-out hanya bisa jika sudah check-in
        if (!$guest->checked_in_at) {
            return response()->json([
                'success' => false,
                'message' => 'Tamu belum melakukan check-in.',
            ], 422);
        }

        if ($guest->checked_out_at) {
            return response()->json([
                'success'           => false,
                'already_checked_out' => true,
                'message'           => 'Tamu sudah check-out sebelumnya.',
                'guest'             => $guest,
                'checked_out_at'    => $guest->checked_out_at->toIso8601String(),
            ], 409);
        }

        $guest->update(['checked_out_at' => now()]);
        $guest->refresh();

        return response()->json([
            'success'        => true,
            'message'        => 'Check-out berhasil!',
            'guest'          => $guest,
            'checked_out_at' => $guest->checked_out_at->toIso8601String(),
        ]);
    }

    public function resetCheckOut(Request $request, $invitationId, $guestId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)->findOrFail($invitationId);
        $guest      = $invitation->guests()->findOrFail($guestId);

        $guest->update(['checked_out_at' => null]);

        return response()->json(['success' => true, 'message' => 'Status check-out berhasil direset.', 'guest' => $guest->fresh()]);
    }

    // ─────────────────────────────────────────────────────────────
    //  ANALYTICS — check-in & souvenir stats for a given invitation
    // ─────────────────────────────────────────────────────────────

    public function scanAnalytics(Request $request, $invitationId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)->findOrFail($invitationId);

        $total              = $invitation->guests()->count();
        $checkedIn          = $invitation->guests()->whereNotNull('checked_in_at')->count();
        $checkedOut         = $invitation->guests()->whereNotNull('checked_out_at')->count();
        $souvenirTaken      = $invitation->guests()->whereNotNull('souvenir_taken_at')->count();
        $souvenir2Taken     = $invitation->guests()->whereNotNull('souvenir2_taken_at')->count();
        $notCheckedIn       = $total - $checkedIn;
        $checkedInNoSouvenir = $invitation->guests()
            ->whereNotNull('checked_in_at')
            ->whereNull('souvenir_taken_at')
            ->count();

        // Hourly distribution of check-ins
        $checkInByHour = $invitation->guests()
            ->whereNotNull('checked_in_at')
            ->selectRaw('HOUR(checked_in_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Hourly distribution of souvenir scans
        $souvenirByHour = $invitation->guests()
            ->whereNotNull('souvenir_taken_at')
            ->selectRaw('HOUR(souvenir_taken_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Hourly distribution of check-outs
        $checkOutByHour = $invitation->guests()
            ->whereNotNull('checked_out_at')
            ->selectRaw('HOUR(checked_out_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Per-category breakdown
        $categories = ['family', 'friend', 'colleague'];
        $byCategory = [];
        foreach ($categories as $cat) {
            $catTotal     = $invitation->guests()->where('category', $cat)->count();
            $catCheckedIn = $invitation->guests()->where('category', $cat)->whereNotNull('checked_in_at')->count();
            $catSouvenir  = $invitation->guests()->where('category', $cat)->whereNotNull('souvenir_taken_at')->count();
            $catCheckOut  = $invitation->guests()->where('category', $cat)->whereNotNull('checked_out_at')->count();
            $byCategory[$cat] = [
                'total'       => $catTotal,
                'checked_in'  => $catCheckedIn,
                'souvenir'    => $catSouvenir,
                'checked_out' => $catCheckOut,
            ];
        }

        // Recent activity (last 15 events, mixed check-in + souvenir + checkout)
        $recentCheckIns = $invitation->guests()
            ->whereNotNull('checked_in_at')
            ->orderByDesc('checked_in_at')
            ->take(10)
            ->get(['id', 'name', 'category', 'checked_in_at', 'souvenir_taken_at', 'checked_out_at'])
            ->map(fn($g) => [
                'id'           => $g->id,
                'name'         => $g->name,
                'category'     => $g->category,
                'event'        => 'checkin',
                'timestamp'    => $g->checked_in_at->toIso8601String(),
                'has_souvenir' => !is_null($g->souvenir_taken_at),
                'has_checkout' => !is_null($g->checked_out_at),
            ]);

        $recentSouvenirs = $invitation->guests()
            ->whereNotNull('souvenir_taken_at')
            ->orderByDesc('souvenir_taken_at')
            ->take(10)
            ->get(['id', 'name', 'category', 'checked_in_at', 'souvenir_taken_at'])
            ->map(fn($g) => [
                'id'        => $g->id,
                'name'      => $g->name,
                'category'  => $g->category,
                'event'     => 'souvenir',
                'timestamp' => $g->souvenir_taken_at->toIso8601String(),
            ]);

        $recentCheckOuts = $invitation->guests()
            ->whereNotNull('checked_out_at')
            ->orderByDesc('checked_out_at')
            ->take(10)
            ->get(['id', 'name', 'category', 'checked_out_at'])
            ->map(fn($g) => [
                'id'        => $g->id,
                'name'      => $g->name,
                'category'  => $g->category,
                'event'     => 'checkout',
                'timestamp' => $g->checked_out_at->toIso8601String(),
            ]);

        $recentActivity = $recentCheckIns
            ->concat($recentSouvenirs)
            ->concat($recentCheckOuts)
            ->sortByDesc('timestamp')
            ->take(15)
            ->values();

        return response()->json([
            'success' => true,
            'analytics' => [
                'total_guests'           => $total,
                'checked_in'             => $checkedIn,
                'checked_out'            => $checkedOut,
                'not_checked_in'         => $notCheckedIn,
                'souvenir_taken'         => $souvenirTaken,
                'souvenir2_taken'        => $souvenir2Taken,
                'checked_in_no_souvenir' => $checkedInNoSouvenir,
                'check_in_rate'          => $total > 0 ? round(($checkedIn / $total) * 100, 1) : 0,
                'souvenir_rate'          => $total > 0 ? round(($souvenirTaken / $total) * 100, 1) : 0,
                'check_out_rate'         => $total > 0 ? round(($checkedOut / $total) * 100, 1) : 0,
                'check_in_by_hour'       => $checkInByHour,
                'souvenir_by_hour'       => $souvenirByHour,
                'check_out_by_hour'      => $checkOutByHour,
                'by_category'            => $byCategory,
                'recent_activity'        => $recentActivity,
            ],
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  PUBLIC DISPLAY POLLING
    // ─────────────────────────────────────────────────────────────

    /**
     * Public polling endpoint for the display/gerbang screen.
     * Supports both check-in and souvenir events.
     * ?type=checkin|souvenir  (default: checkin)
     * ?after=ISO8601          (only return events after this timestamp)
     */
    public function latestCheckIn(Request $request, string $uniqueUrl)
    {
        $invitation = \App\Models\Invitation::where('unique_url', $uniqueUrl)
            ->where('status', 'published')
            ->firstOrFail();

        $type  = $request->query('type', 'checkin'); // 'checkin' or 'souvenir'
        $after = $request->query('after');

        $categoryLabels = ['family' => 'Keluarga', 'friend' => 'Teman', 'colleague' => 'Rekan'];

        if ($type === 'souvenir') {
            $query = $invitation->guests()->whereNotNull('souvenir_taken_at')->orderByDesc('souvenir_taken_at');
            if ($after) $query->where('souvenir_taken_at', '>', $after);
            $guest = $query->first();
            if (!$guest) return response()->json(['event' => null]);
            return response()->json([
                'event' => [
                    'type'           => 'souvenir',
                    'id'             => $guest->id,
                    'name'           => $guest->name,
                    'category'       => $guest->category,
                    'category_label' => $categoryLabels[$guest->category] ?? 'Tamu',
                    'timestamp'      => $guest->souvenir_taken_at->toIso8601String(),
                ],
            ]);
        }

        // Default: check-in
        $query = $invitation->guests()->whereNotNull('checked_in_at')->orderByDesc('checked_in_at');
        if ($after) $query->where('checked_in_at', '>', $after);
        $guest = $query->first();
        if (!$guest) return response()->json(['event' => null]);

        return response()->json([
            'event' => [
                'type'           => 'checkin',
                'id'             => $guest->id,
                'name'           => $guest->name,
                'category'       => $guest->category,
                'category_label' => $categoryLabels[$guest->category] ?? 'Tamu',
                'timestamp'      => $guest->checked_in_at->toIso8601String(),
            ],
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  HELPERS
    // ─────────────────────────────────────────────────────────────

    private function generateUniqueToken(): string
    {
        do {
            $token = Str::random(32);
        } while (Guest::where('qr_token', $token)->exists());
        return $token;
    }
}
