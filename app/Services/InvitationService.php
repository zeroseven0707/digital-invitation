<?php

namespace App\Services;

use App\Models\Invitation;
use Illuminate\Support\Str;

class InvitationService
{
    /**
     * Create a new invitation.
     *
     * @param array $data
     * @param int $userId
     * @return Invitation
     */
    public function createInvitation(array $data, int $userId): Invitation
    {
        $data['user_id'] = $userId;
        $data['status'] = 'draft';

        return Invitation::create($data);
    }

    /**
     * Update an existing invitation.
     *
     * @param Invitation $invitation
     * @param array $data
     * @return Invitation
     */
    public function updateInvitation(Invitation $invitation, array $data): Invitation
    {
        $invitation->update($data);

        return $invitation->fresh();
    }

    /**
     * Publish an invitation and generate unique URL.
     *
     * @param Invitation $invitation
     * @return string The generated unique URL
     */
    public function publishInvitation(Invitation $invitation): string
    {
        $uniqueUrl = $this->generateUniqueUrl();

        $invitation->update([
            'unique_url' => $uniqueUrl,
            'status' => 'published',
        ]);

        return $uniqueUrl;
    }

    /**
     * Unpublish an invitation.
     *
     * @param Invitation $invitation
     * @return void
     */
    public function unpublishInvitation(Invitation $invitation): void
    {
        $invitation->update([
            'status' => 'unpublished',
        ]);
    }

    /**
     * Change the template of an invitation.
     *
     * @param Invitation $invitation
     * @param int $templateId
     * @return Invitation
     */
    public function changeTemplate(Invitation $invitation, int $templateId): Invitation
    {
        $invitation->update([
            'template_id' => $templateId,
        ]);

        return $invitation->fresh();
    }

    /**
     * Delete an invitation.
     *
     * @param Invitation $invitation
     * @return void
     */
    public function deleteInvitation(Invitation $invitation): void
    {
        $invitation->delete();
    }

    /**
     * Generate a unique URL for an invitation.
     *
     * @return string
     */
    protected function generateUniqueUrl(): string
    {
        do {
            $url = Str::random(32);
        } while (Invitation::where('unique_url', $url)->exists());

        return $url;
    }
}

