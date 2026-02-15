<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportGuestRequest;
use App\Models\Guest;
use App\Models\Invitation;
use App\Services\GuestImportService;
use App\Services\GuestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GuestController extends Controller
{
    protected GuestService $guestService;
    protected GuestImportService $guestImportService;

    public function __construct(GuestService $guestService, GuestImportService $guestImportService)
    {
        $this->guestService = $guestService;
        $this->guestImportService = $guestImportService;
    }

    /**
     * Display a listing of guests for an invitation.
     *
     * @param Request $request
     * @param string $invitationId
     * @return View
     */
    public function index(Request $request, string $invitationId): View
    {
        // Find invitation and ensure user owns it
        $invitation = auth()->user()->invitations()->findOrFail($invitationId);

        // Get guests query
        $query = Guest::where('invitation_id', $invitation->id);

        // Filter by category if provided
        if ($request->has('category') && $request->category !== '') {
            $query->where('category', $request->category);
        }

        // Get guests
        $guests = $query->orderBy('name')->get();

        return view('guests.index', [
            'invitation' => $invitation,
            'guests' => $guests,
            'selectedCategory' => $request->category ?? '',
        ]);
    }

    /**
     * Store a newly created guest.
     *
     * @param Request $request
     * @param string $invitationId
     * @return RedirectResponse
     */
    public function store(Request $request, string $invitationId): RedirectResponse
    {
        // Find invitation and ensure user owns it
        $invitation = auth()->user()->invitations()->findOrFail($invitationId);

        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp_number' => 'nullable',
            'category' => 'required|in:family,friend,colleague',
        ], [
            'name.required' => 'Nama tamu harus diisi',
            'name.max' => 'Nama tamu maksimal 255 karakter',
            'category.required' => 'Kategori harus dipilih',
            'category.in' => 'Kategori tidak valid',
        ]);

        try {
            // Add guest
            $this->guestService->addGuest($invitation->id, $validated);

            return redirect()
                ->route('guests.index', $invitation->id)
                ->with('success', 'Tamu berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menambahkan tamu']);
        }
    }

    /**
     * Update the specified guest.
     *
     * @param Request $request
     * @param string $invitationId
     * @param string $guestId
     * @return RedirectResponse
     */
    public function update(Request $request, string $invitationId, string $guestId): RedirectResponse
    {
        // Find invitation and ensure user owns it
        $invitation = auth()->user()->invitations()->findOrFail($invitationId);

        // Find guest and ensure it belongs to the invitation
        $guest = Guest::where('invitation_id', $invitation->id)
            ->findOrFail($guestId);

        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp_number' => 'nullable',
            'category' => 'required|in:family,friend,colleague',
        ], [
            'name.required' => 'Nama tamu harus diisi',
            'name.max' => 'Nama tamu maksimal 255 karakter',
            'category.required' => 'Kategori harus dipilih',
            'category.in' => 'Kategori tidak valid',
        ]);

        try {
            // Update guest
            $this->guestService->updateGuest($guest, $validated);

            return redirect()
                ->route('guests.index', $invitation->id)
                ->with('success', 'Data tamu berhasil diperbarui');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Gagal memperbarui data tamu']);
        }
    }

    /**
     * Remove the specified guest.
     *
     * @param string $invitationId
     * @param string $guestId
     * @return RedirectResponse
     */
    public function destroy(string $invitationId, string $guestId): RedirectResponse
    {
        // Find invitation and ensure user owns it
        $invitation = auth()->user()->invitations()->findOrFail($invitationId);

        // Find guest and ensure it belongs to the invitation
        $guest = Guest::where('invitation_id', $invitation->id)
            ->findOrFail($guestId);

        try {
            // Delete guest
            $this->guestService->deleteGuest($guest);

            return redirect()
                ->route('guests.index', $invitation->id)
                ->with('success', 'Tamu berhasil dihapus');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Gagal menghapus tamu']);
        }
    }

    /**
     * Export guests to CSV file.
     *
     * @param string $invitationId
     * @return BinaryFileResponse
     */
    public function export(string $invitationId): BinaryFileResponse
    {
        // Find invitation and ensure user owns it
        $invitation = auth()->user()->invitations()->findOrFail($invitationId);

        try {
            // Generate CSV file
            $filepath = $this->guestImportService->exportToCsv($invitation->id);

            // Return file download response
            return response()->download($filepath, 'guests.csv')->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            abort(500, 'Gagal mengexport daftar tamu');
        }
    }

    /**
     * Import guests from CSV file.
     *
     * @param ImportGuestRequest $request
     * @param string $invitationId
     * @return RedirectResponse
     */
    public function import(ImportGuestRequest $request, string $invitationId): RedirectResponse
    {
        // Find invitation and ensure user owns it
        $invitation = auth()->user()->invitations()->findOrFail($invitationId);

        try {
            // Import guests from CSV
            $result = $this->guestImportService->importFromCsv($request->file('file'), $invitation->id);

            // Prepare success message
            $message = "Import selesai: {$result['success']} tamu berhasil ditambahkan";

            if ($result['failed'] > 0) {
                $message .= ", {$result['failed']} gagal";
            }

            // Store errors in session if any
            if (!empty($result['errors'])) {
                session()->flash('import_errors', $result['errors']);
            }

            return redirect()
                ->route('guests.index', $invitation->id)
                ->with('success', $message);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['file' => 'Gagal mengimport file: ' . $e->getMessage()]);
        }
    }
}
