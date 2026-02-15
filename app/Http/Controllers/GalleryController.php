<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Invitation;
use App\Services\FileUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    protected FileUploadService $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Upload photo to gallery
     *
     * @param Request $request
     * @param string $invitationId
     * @return JsonResponse
     */
    public function store(Request $request, string $invitationId): JsonResponse
    {
        // Find invitation and ensure user owns it
        $invitation = auth()->user()->invitations()->findOrFail($invitationId);

        // Validate request
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'photo.required' => 'Foto harus diupload',
            'photo.image' => 'File harus berupa gambar',
            'photo.mimes' => 'Foto harus berformat JPEG atau PNG',
            'photo.max' => 'Ukuran foto maksimal 2MB',
        ]);

        try {
            // Upload photo
            $path = $this->fileUploadService->uploadPhoto($request->file('photo'), $invitation->id);

            // Get next order number
            $maxOrder = Gallery::where('invitation_id', $invitation->id)->max('order') ?? 0;

            // Create gallery record
            $gallery = Gallery::create([
                'invitation_id' => $invitation->id,
                'photo_path' => $path,
                'order' => $maxOrder + 1,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil diupload',
                'data' => [
                    'id' => $gallery->id,
                    'photo_url' => asset('storage/' . $gallery->photo_path),
                    'order' => $gallery->order,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Delete photo from gallery
     *
     * @param string $invitationId
     * @param string $photoId
     * @return JsonResponse
     */
    public function destroy(string $invitationId, string $photoId): JsonResponse
    {
        // Find invitation and ensure user owns it
        $invitation = auth()->user()->invitations()->findOrFail($invitationId);

        // Find gallery photo
        $gallery = Gallery::where('invitation_id', $invitation->id)
            ->findOrFail($photoId);

        try {
            // Delete photo from storage
            $this->fileUploadService->deletePhoto($gallery->photo_path);

            // Delete gallery record
            $gallery->delete();

            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus foto',
            ], 500);
        }
    }

    /**
     * Reorder photos in gallery
     *
     * @param Request $request
     * @param string $invitationId
     * @return JsonResponse
     */
    public function reorder(Request $request, string $invitationId): JsonResponse
    {
        // Find invitation and ensure user owns it
        $invitation = auth()->user()->invitations()->findOrFail($invitationId);

        // Validate request
        $request->validate([
            'photos' => 'required|array',
            'photos.*.id' => 'required|exists:galleries,id',
            'photos.*.order' => 'required|integer|min:1',
        ]);

        try {
            // Update order for each photo
            foreach ($request->photos as $photo) {
                Gallery::where('id', $photo['id'])
                    ->where('invitation_id', $invitation->id)
                    ->update(['order' => $photo['order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Urutan foto berhasil diubah',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah urutan foto',
            ], 500);
        }
    }
}
