<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MusicController extends Controller
{
    /**
     * Get current music info for an invitation
     */
    public function show(Request $request, int $invitationId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)
            ->findOrFail($invitationId);

        return response()->json([
            'success'    => true,
            'has_music'  => !empty($invitation->music_path),
            'music_path' => $invitation->music_path,
            'music_url'  => $invitation->music_path
                ? Storage::disk('public')->url($invitation->music_path)
                : null,
        ]);
    }

    /**
     * Upload music file for an invitation.
     * Accepts multipart/form-data with field "music".
     * Supports: mp3, m4a, aac, ogg, wav — max 10 MB.
     */
    public function store(Request $request, int $invitationId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)
            ->findOrFail($invitationId);

        $request->validate([
            'music' => 'required|file|mimes:mp3,m4a,aac,ogg,wav|max:10240',
        ], [
            'music.required' => 'File musik harus diupload.',
            'music.file'     => 'File tidak valid.',
            'music.mimes'    => 'Format musik harus MP3, M4A, AAC, OGG, atau WAV.',
            'music.max'      => 'Ukuran file musik maksimal 10 MB.',
        ]);

        // Delete old music file if exists
        if ($invitation->music_path) {
            $this->deleteFile($invitation->music_path);
        }

        $file      = $request->file('music');
        $filename  = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path      = "invitations/{$invitationId}/music/{$filename}";

        Storage::disk('public')->put($path, file_get_contents($file->getRealPath()));

        $invitation->update(['music_path' => $path]);

        return response()->json([
            'success'    => true,
            'message'    => 'Musik berhasil diupload.',
            'music_path' => $path,
            'music_url'  => Storage::disk('public')->url($path),
        ], 201);
    }

    /**
     * Delete music from invitation
     */
    public function destroy(Request $request, int $invitationId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)
            ->findOrFail($invitationId);

        if (!$invitation->music_path) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada musik yang terpasang.',
            ], 404);
        }

        $this->deleteFile($invitation->music_path);
        $invitation->update(['music_path' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Musik berhasil dihapus.',
        ]);
    }

    // ── Helper ───────────────────────────────────────────────────────────────

    private function deleteFile(string $path): void
    {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
