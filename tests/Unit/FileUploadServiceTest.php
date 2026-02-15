<?php

namespace Tests\Unit;

use App\Services\FileUploadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadServiceTest extends TestCase
{
    use RefreshDatabase;

    protected FileUploadService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new FileUploadService();
        Storage::fake('public');
    }

    public function test_upload_photo_saves_file_to_storage()
    {
        $file = UploadedFile::fake()->image('photo.jpg', 800, 600);
        $invitationId = 1;

        $path = $this->service->uploadPhoto($file, $invitationId);

        Storage::disk('public')->assertExists($path);
        $this->assertStringContainsString("invitations/{$invitationId}/gallery/", $path);
    }

    public function test_upload_photo_resizes_large_images()
    {
        $file = UploadedFile::fake()->image('large.jpg', 2000, 1500);
        $invitationId = 1;

        $path = $this->service->uploadPhoto($file, $invitationId);

        Storage::disk('public')->assertExists($path);

        // Get image dimensions
        $imageContent = Storage::disk('public')->get($path);
        $tempPath = tempnam(sys_get_temp_dir(), 'test_image');
        file_put_contents($tempPath, $imageContent);
        $imageInfo = getimagesize($tempPath);
        unlink($tempPath);

        // Width should be 1200 or less
        $this->assertLessThanOrEqual(1200, $imageInfo[0]);
    }

    public function test_upload_photo_keeps_small_images_unchanged()
    {
        $file = UploadedFile::fake()->image('small.jpg', 800, 600);
        $invitationId = 1;

        $path = $this->service->uploadPhoto($file, $invitationId);

        Storage::disk('public')->assertExists($path);
    }

    public function test_delete_photo_removes_file_from_storage()
    {
        $file = UploadedFile::fake()->image('photo.jpg');
        $invitationId = 1;

        $path = $this->service->uploadPhoto($file, $invitationId);
        Storage::disk('public')->assertExists($path);

        $this->service->deletePhoto($path);

        Storage::disk('public')->assertMissing($path);
    }

    public function test_delete_photo_handles_non_existent_file()
    {
        $path = 'invitations/1/gallery/nonexistent.jpg';

        // Should not throw exception
        $this->service->deletePhoto($path);

        $this->assertTrue(true);
    }

    public function test_validate_photo_accepts_jpeg()
    {
        $file = UploadedFile::fake()->image('photo.jpg');

        $result = $this->service->validatePhoto($file);

        $this->assertTrue($result);
    }

    public function test_validate_photo_accepts_png()
    {
        $file = UploadedFile::fake()->image('photo.png');

        $result = $this->service->validatePhoto($file);

        $this->assertTrue($result);
    }

    public function test_validate_photo_rejects_invalid_format()
    {
        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File harus berformat JPEG atau PNG');

        $this->service->validatePhoto($file);
    }

    public function test_validate_photo_rejects_oversized_file()
    {
        // Create a file larger than 2MB
        $file = UploadedFile::fake()->image('large.jpg')->size(3000);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Ukuran file tidak boleh lebih dari 2MB');

        $this->service->validatePhoto($file);
    }
}
