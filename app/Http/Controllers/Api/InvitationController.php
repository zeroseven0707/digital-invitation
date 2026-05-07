<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    /**
     * Get all invitations for authenticated user
     */
    public function index(Request $request)
    {
        $invitations = Invitation::where('user_id', $request->user()->id)
            ->withCount(['views', 'guests', 'rsvps'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'invitations' => $invitations,
        ]);
    }

    /**
     * Get single invitation
     */
    public function show(Request $request, $id)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)
            ->withCount(['views', 'guests', 'rsvps'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'invitation' => $invitation,
        ]);
    }

    /**
     * Create new invitation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'template_id' => 'required|exists:templates,id',
            'bride_name' => 'required|string|max:255',
            'bride_father_name' => 'nullable|string|max:255',
            'bride_mother_name' => 'nullable|string|max:255',
            'groom_name' => 'required|string|max:255',
            'groom_father_name' => 'nullable|string|max:255',
            'groom_mother_name' => 'nullable|string|max:255',
            'akad_date' => 'required|date',
            'akad_time_start' => 'nullable|string',
            'akad_time_end' => 'nullable|string',
            'akad_location' => 'nullable|string|max:255',
            'reception_date' => 'required|date',
            'reception_time_start' => 'nullable|string',
            'reception_time_end' => 'nullable|string',
            'reception_location' => 'nullable|string|max:255',
            'full_address' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
        ]);

        // Generate unique URL
        $uniqueUrl = Str::slug($validated['bride_name'] . '-' . $validated['groom_name']) . '-' . Str::random(6);

        // Ensure uniqueness
        while (Invitation::where('unique_url', $uniqueUrl)->exists()) {
            $uniqueUrl = Str::slug($validated['bride_name'] . '-' . $validated['groom_name']) . '-' . Str::random(6);
        }

        $invitation = Invitation::create([
            'user_id' => $request->user()->id,
            'unique_url' => $uniqueUrl,
            'status' => 'draft',
            ...$validated,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Invitation created successfully',
            'invitation' => $invitation,
        ], 201);
    }

    /**
     * Update invitation
     */
    public function update(Request $request, $id)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'bride_name' => 'sometimes|required|string|max:255',
            'bride_father_name' => 'nullable|string|max:255',
            'bride_mother_name' => 'nullable|string|max:255',
            'groom_name' => 'sometimes|required|string|max:255',
            'groom_father_name' => 'nullable|string|max:255',
            'groom_mother_name' => 'nullable|string|max:255',
            'akad_date' => 'sometimes|required|date',
            'akad_time_start' => 'nullable|string',
            'akad_time_end' => 'nullable|string',
            'akad_location' => 'nullable|string|max:255',
            'reception_date' => 'sometimes|required|date',
            'reception_time_start' => 'nullable|string',
            'reception_time_end' => 'nullable|string',
            'reception_location' => 'nullable|string|max:255',
            'full_address' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
        ]);

        $invitation->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Invitation updated successfully',
            'invitation' => $invitation,
        ]);
    }

    /**
     * Delete invitation
     */
    public function destroy(Request $request, $id)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $invitation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Invitation deleted successfully',
        ]);
    }

    /**
     * Publish invitation — requires payment first
     */
    public function publish(Request $request, $id)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)
            ->findOrFail($id);

        if (!$invitation->is_paid) {
            return response()->json([
                'success'         => false,
                'requires_payment'=> true,
                'message'         => 'Undangan harus dibayar terlebih dahulu sebelum dipublikasikan.',
                'amount'          => config('services.midtrans.price', 50000),
            ], 402);
        }

        $invitation->update(['status' => 'published']);

        return response()->json([
            'success'    => true,
            'message'    => 'Invitation published successfully',
            'invitation' => $invitation,
        ]);
    }

    /**
     * Unpublish invitation
     */
    public function unpublish(Request $request, $id)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $invitation->update(['status' => 'draft']);

        return response()->json([
            'success' => true,
            'message' => 'Invitation unpublished successfully',
            'invitation' => $invitation,
        ]);
    }
}
