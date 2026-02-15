<?php

namespace App\Services;

use App\Models\Invitation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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

        // Handle music file upload
        if (isset($data['music_file']) && $data['music_file']) {
            $data['music_path'] = $this->uploadMusicFile($data['music_file'], $userId);
            unset($data['music_file']);
        }

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
        // Handle music file removal
        if (isset($data['remove_music']) && $data['remove_music']) {
            if ($invitation->music_path) {
                Storage::disk('public')->delete($invitation->music_path);
                $data['music_path'] = null;
            }
            unset($data['remove_music']);
        }

        // Handle new music file upload
        if (isset($data['music_file']) && $data['music_file']) {
            // Delete old music file if exists
            if ($invitation->music_path) {
                Storage::disk('public')->delete($invitation->music_path);
            }

            $data['music_path'] = $this->uploadMusicFile($data['music_file'], $invitation->user_id);
            unset($data['music_file']);
        }

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
        // Delete music file if exists
        if ($invitation->music_path) {
            Storage::disk('public')->delete($invitation->music_path);
        }

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

    /**
     * Upload music file for invitation.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param int $userId
     * @return string Path to uploaded file
     */
    protected function uploadMusicFile($file, int $userId): string
    {
        $filename = time() . '_' . Str::random(10) . '.mp3';
        $path = "invitations/user_{$userId}/music/{$filename}";

        Storage::disk('public')->put($path, file_get_contents($file));

        return $path;
    }
}

