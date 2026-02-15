<?php

namespace App\Policies;

use App\Models\Guest;
use App\Models\User;

class GuestPolicy
{
    /**
     * Determine if the user can view any guests.
     */
    public function viewAny(User $user, $invitation): bool
    {
        return $user->id === $invitation->user_id;
    }

    /**
     * Determine if the user can create guests.
     */
    public function create(User $user, $invitation): bool
    {
        return $user->id === $invitation->user_id && $user->is_active;
    }

    /**
     * Determine if the user can update the guest.
     */
    public function update(User $user, Guest $guest): bool
    {
        return $user->id === $guest->invitation->user_id && $user->is_active;
    }

    /**
     * Determine if the user can delete the guest.
     */
    public function delete(User $user, Guest $guest): bool
    {
        return $user->id === $guest->invitation->user_id && $user->is_active;
    }
}
