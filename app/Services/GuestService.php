<?php

namespace App\Services;

use App\Models\Guest;
use Illuminate\Database\Eloquent\Collection;

class GuestService
{
    /**
     * Add a new guest to an invitation.
     *
     * @param int $invitationId
     * @param array $data
     * @return Guest
     */
    public function addGuest(int $invitationId, array $data): Guest
    {
        $data['invitation_id'] = $invitationId;

        return Guest::create($data);
    }

    /**
     * Update an existing guest.
     *
     * @param Guest $guest
     * @param array $data
     * @return Guest
     */
    public function updateGuest(Guest $guest, array $data): Guest
    {
        $guest->update($data);

        return $guest->fresh();
    }

    /**
     * Delete a guest.
     *
     * @param Guest $guest
     * @return void
     */
    public function deleteGuest(Guest $guest): void
    {
        $guest->delete();
    }

    /**
     * Get guests by category for a specific invitation.
     *
     * @param int $invitationId
     * @param string $category
     * @return Collection
     */
    public function getGuestsByCategory(int $invitationId, string $category): Collection
    {
        return Guest::where('invitation_id', $invitationId)
            ->where('category', $category)
            ->get();
    }
}
