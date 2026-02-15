<?php

namespace App\Policies;

use App\Models\Invitation;
use App\Models\User;

class InvitationPolicy
{
    /**
     * Determine if the user can view any invitations.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can view the invitation.
     */
    public function view(User $user, Invitation $invitation): bool
    {
        return $user->id === $invitation->user_id;
    }

    /**
     * Determine if the user can create invitations.
     */
    public function create(User $user): bool
    {
        return $user->is_active;
    }

    /**
     * Determine if the user can update the invitation.
     */
    public function update(User $user, Invitation $invitation): bool
    {
        return $user->id === $invitation->user_id && $user->is_active;
    }

    /**
     * Determine if the user can delete the invitation.
     */
    public function delete(User $user, Invitation $invitation): bool
    {
        return $user->id === $invitation->user_id && $user->is_active;
    }
}
