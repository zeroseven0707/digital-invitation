<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Invitation;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    protected FileUploadService $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * List all gallery photos for an invitation.
     */
    public function index(Request $request, int $invitationId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)
            ->findOrFail($invitationId);

        $photos = $invitation->galleries()
            ->orderBy('order')
            ->get()
            ->map(fn($g) => $this->formatPhoto($g));

        return response()->json(['success' => true, 'photos' => $photos]);
    }

    /**
     * Upload a new photo to the gallery.
     * Accepts multipart/form-data with field "photo".
     */
    public function store(Request $request, int $invitationId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)
            ->findOrFail($invitationId);

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5 MB for mobile
        ], [
            'photo.required' => 'Foto harus diupload.',
            'photo.image'    => 'File harus berupa gambar.',
            'photo.mimes'    => 'Foto harus berformat JPEG atau PNG.',
            'photo.max'      => 'Ukuran foto maksimal 5 MB.',
        ]);

        try {
            $path     = $this->fileUploadService->uploadPhoto($request->file('photo'), $invitation->id);
            $maxOrder = Gallery::where('invitation_id', $invitation->id)->max('order') ?? 0;

            $gallery = Gallery::create([
                'invitation_id' => $invitation->id,
                'photo_path'    => $path,
                'order'         => $maxOrder + 1,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil diupload.',
                'photo'   => $this->formatPhoto($gallery),
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    /**
     * Delete a photo from the gallery.
     */
    public function destroy(Request $request, int $invitationId, int $photoId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)
            ->findOrFail($invitationId);

        $gallery = Gallery::where('invitation_id', $invitation->id)->findOrFail($photoId);

        try {
            $this->fileUploadService->deletePhoto($gallery->photo_path);
            $gallery->delete();

            return response()->json(['success' => true, 'message' => 'Foto berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus foto.'], 500);
        }
    }

    /**
     * Reorder photos.
     * Body: { "photos": [{ "id": 1, "order": 1 }, ...] }
     */
    public function reorder(Request $request, int $invitationId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)
            ->findOrFail($invitationId);

        $request->validate([
            'photos'          => 'required|array',
            'photos.*.id'     => 'required|integer',
            'photos.*.order'  => 'required|integer|min:1',
        ]);

        foreach ($request->photos as $item) {
            Gallery::where('id', $item['id'])
                ->where('invitation_id', $invitation->id)
                ->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true, 'message' => 'Urutan foto berhasil diubah.']);
    }

    // ── Helper ──────────────────────────────────────────────────────────────

    private function formatPhoto(Gallery $g): array
    {
        return [
            'id'        => $g->id,
            'photo_path'=> $g->photo_path,
            'photo_url' => Storage::disk('public')->url($g->photo_path),
            'order'     => $g->order,
        ];
    }
}
