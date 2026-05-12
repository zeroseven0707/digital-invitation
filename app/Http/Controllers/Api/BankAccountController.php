<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Invitation;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    // GET /api/invitations/{id}/bank-accounts
    public function index(Request $request, int $invitationId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)->findOrFail($invitationId);

        return response()->json([
            'success'       => true,
            'bank_accounts' => $invitation->bankAccounts()->get(),
            'gift_enabled'  => $invitation->gift_enabled,
        ]);
    }

    // POST /api/invitations/{id}/bank-accounts
    public function store(Request $request, int $invitationId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)->findOrFail($invitationId);

        $validated = $request->validate([
            'bank_name'      => 'required|string|max:100',
            'account_number' => 'required|string|max:30',
            'account_holder' => 'required|string|max:255',
            'owner'          => 'required|in:bride,groom,other',
            'sort_order'     => 'integer',
        ]);

        $account = $invitation->bankAccounts()->create($validated);

        return response()->json(['success' => true, 'bank_account' => $account], 201);
    }

    // PUT /api/invitations/{id}/bank-accounts/{accountId}
    public function update(Request $request, int $invitationId, int $accountId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)->findOrFail($invitationId);
        $account    = $invitation->bankAccounts()->findOrFail($accountId);

        $validated = $request->validate([
            'bank_name'      => 'sometimes|required|string|max:100',
            'account_number' => 'sometimes|required|string|max:30',
            'account_holder' => 'sometimes|required|string|max:255',
            'owner'          => 'sometimes|required|in:bride,groom,other',
            'sort_order'     => 'integer',
        ]);

        $account->update($validated);

        return response()->json(['success' => true, 'bank_account' => $account->fresh()]);
    }

    // DELETE /api/invitations/{id}/bank-accounts/{accountId}
    public function destroy(Request $request, int $invitationId, int $accountId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)->findOrFail($invitationId);
        $invitation->bankAccounts()->findOrFail($accountId)->delete();

        return response()->json(['success' => true, 'message' => 'Rekening berhasil dihapus']);
    }

    // POST /api/invitations/{id}/gift-toggle
    public function toggleGift(Request $request, int $invitationId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)->findOrFail($invitationId);

        $validated = $request->validate([
            'gift_enabled' => 'required|boolean',
        ]);

        $invitation->update(['gift_enabled' => $validated['gift_enabled']]);

        return response()->json([
            'success'      => true,
            'gift_enabled' => $invitation->gift_enabled,
            'message'      => $invitation->gift_enabled ? 'Fitur hadiah diaktifkan' : 'Fitur hadiah dinonaktifkan',
        ]);
    }

    // GET /api/public/invitations/{uniqueUrl}/bank-accounts (public)
    public function publicIndex(string $uniqueUrl)
    {
        $invitation = Invitation::where('unique_url', $uniqueUrl)
            ->where('status', 'published')
            ->firstOrFail();

        if (!$invitation->gift_enabled) {
            return response()->json(['success' => true, 'bank_accounts' => []]);
        }

        return response()->json([
            'success'       => true,
            'bank_accounts' => $invitation->bankAccounts()->get(),
        ]);
    }
}
