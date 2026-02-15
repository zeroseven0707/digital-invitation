<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    /**
     * Upload photo, resize, and return path
     *
     * @param UploadedFile $file
     * @param int $invitationId
     * @return string
     */
    public function uploadPhoto(UploadedFile $file, int $invitationId): string
    {
        // Validate photo
        $this->validatePhoto($file);

        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = "invitations/{$invitationId}/gallery/{$filename}";

        // Resize image to max width 1200px
        $resizedImage = $this->resizeImage($file, 1200);

        // Save to storage
        Storage::disk('public')->put($path, $resizedImage);

        return $path;
    }

    /**
     * Resize image to max width while maintaining aspect ratio
     *
     * @param UploadedFile $file
     * @param int $maxWidth
     * @return string
     */
    private function resizeImage(UploadedFile $file, int $maxWidth): string
    {
        // Get image info
        $imageInfo = getimagesize($file->getRealPath());
        $width = $imageInfo[0];
        $height = $imageInfo[1];
        $mimeType = $imageInfo['mime'];

        // If image is smaller than max width, return original
        if ($width <= $maxWidth) {
            return file_get_contents($file->getRealPath());
        }

        // Calculate new dimensions
        $newWidth = $maxWidth;
        $newHeight = (int) ($height * ($maxWidth / $width));

        // Create image resource based on mime type
        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/jpg':
                $source = imagecreatefromjpeg($file->getRealPath());
                break;
            case 'image/png':
                $source = imagecreatefrompng($file->getRealPath());
                break;
            default:
                return file_get_contents($file->getRealPath());
        }

        // Create new image
        $destination = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency for PNG
        if ($mimeType === 'image/png') {
            imagealphablending($destination, false);
            imagesavealpha($destination, true);
        }

        // Resize
        imagecopyresampled($destination, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Output to string
        ob_start();
        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/jpg':
                imagejpeg($destination, null, 90);
                break;
            case 'image/png':
                imagepng($destination, null, 9);
                break;
        }
        $imageData = ob_get_clean();

        // Free memory
        imagedestroy($source);
        imagedestroy($destination);

        return $imageData;
    }

    /**
     * Delete photo from storage
     *
     * @param string $path
     * @return void
     */
    public function deletePhoto(string $path): void
    {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Validate photo file
     *
     * @param UploadedFile $file
     * @return bool
     * @throws \Exception
     */
    public function validatePhoto(UploadedFile $file): bool
    {
        // Check file type
        $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \Exception('File harus berformat JPEG atau PNG');
        }

        // Check file size (max 2MB)
        $maxSize = 2 * 1024 * 1024; // 2MB in bytes
        if ($file->getSize() > $maxSize) {
            throw new \Exception('Ukuran file tidak boleh lebih dari 2MB');
        }

        return true;
    }
}
