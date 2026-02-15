<?php

namespace Tests\Feature;

use App\Models\Gallery;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Property-Based Tests for Gallery Management
 *
 * These tests validate universal properties that should hold true
 * for all gallery operations.
 */
class GalleryPropertyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /**
     * Property 10: Photo Upload Accepts Valid Files
     *
     * For any valid photo file (JPEG/PNG, <= 2MB), uploading to a gallery
     * should save the file to storage and create a gallery record in the database.
     *
     * **Validates: Requirements 3.4**
     */
    public function test_property_photo_upload_accepts_valid_files(): void
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Test cases with different valid file configurations
        $validFiles = [
            ['name' => 'photo1.jpg', 'width' => 800, 'height' => 600, 'size' => 500], // 500KB JPEG
            ['name' => 'photo2.png', 'width' => 1024, 'height' => 768, 'size' => 1024], // 1MB PNG
            ['name' => 'photo3.jpeg', 'width' => 1920, 'height' => 1080, 'size' => 2000], // 2MB JPEG
            ['name' => 'photo4.jpg', 'width' => 640, 'height' => 480, 'size' => 100], // 100KB small JPEG
        ];

        foreach ($validFiles as $fileConfig) {
            $file = UploadedFile::fake()->image($fileConfig['name'], $fileConfig['width'], $fileConfig['height'])
                ->size($fileConfig['size']);

            $initialCount = Gallery::where('invitation_id', $invitation->id)->count();

            $response = $this->actingAs($user)->postJson(
                route('gallery.store', $invitation->id),
                ['photo' => $file]
            );

            // Property: Upload should succeed
            $response->assertStatus(201)
                ->assertJson(['success' => true]);

            // Property: Gallery record should be created in database
            $newCount = Gallery::where('invitation_id', $invitation->id)->count();
            $this->assertEquals($initialCount + 1, $newCount);

            // Property: File should exist in storage
            $gallery = Gallery::where('invitation_id', $invitation->id)->orderBy('id', 'desc')->first();
            $this->assertNotNull($gallery);
            Storage::disk('public')->assertExists($gallery->photo_path);

            // Property: Gallery record should have correct invitation_id
            $this->assertEquals($invitation->id, $gallery->invitation_id);

            // Property: Gallery record should have valid order
            $this->assertGreaterThan(0, $gallery->order);
        }
    }

    /**
     * Property 11: Photo Upload Rejects Invalid Files
     *
     * For any invalid photo file (wrong format or size > 2MB), upload attempt
     * should fail with an error and not save anything to storage or database.
     *
     * **Validates: Requirements 3.5, 12.5**
     */
    public function test_property_photo_upload_rejects_invalid_files(): void
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Test cases with different invalid file configurations
        $invalidFiles = [
            ['file' => UploadedFile::fake()->create('document.pdf', 500, 'application/pdf'), 'reason' => 'PDF file'],
            ['file' => UploadedFile::fake()->create('document.txt', 100, 'text/plain'), 'reason' => 'Text file'],
            ['file' => UploadedFile::fake()->image('large.jpg')->size(3000), 'reason' => 'File > 2MB'],
            ['file' => UploadedFile::fake()->image('huge.png')->size(5000), 'reason' => 'File > 2MB PNG'],
        ];

        foreach ($invalidFiles as $testCase) {
            $initialCount = Gallery::where('invitation_id', $invitation->id)->count();
            $initialStorageFiles = Storage::disk('public')->allFiles();

            $response = $this->actingAs($user)->postJson(
                route('gallery.store', $invitation->id),
                ['photo' => $testCase['file']]
            );

            // Property: Upload should fail with validation error
            $response->assertStatus(422);

            // Property: No new gallery record should be created
            $newCount = Gallery::where('invitation_id', $invitation->id)->count();
            $this->assertEquals($initialCount, $newCount, "Failed for: {$testCase['reason']}");

            // Property: No new files should be added to storage
            $newStorageFiles = Storage::disk('public')->allFiles();
            $this->assertCount(count($initialStorageFiles), $newStorageFiles, "Failed for: {$testCase['reason']}");
        }
    }

    /**
     * Property 14: Photo Deletion Removes from Storage and Database
     *
     * For any photo in a gallery, deleting it should remove the file from
     * storage and the gallery record from the database.
     *
     * **Validates: Requirements 4.4**
     */
    public function test_property_photo_deletion_removes_from_storage_and_database(): void
    {
        // Test deletion with multiple separate invitations to avoid interference
        $testCases = [
            ['photoCount' => 1, 'description' => 'single photo'],
            ['photoCount' => 3, 'description' => 'multiple photos'],
            ['photoCount' => 5, 'description' => 'many photos'],
        ];

        foreach ($testCases as $testCase) {
            $user = User::factory()->create();
            $invitation = Invitation::factory()->create(['user_id' => $user->id]);

            // Create photos for this test case
            $photos = [];
            for ($i = 0; $i < $testCase['photoCount']; $i++) {
                $file = UploadedFile::fake()->image("photo{$i}.jpg");

                $response = $this->actingAs($user)->postJson(
                    route('gallery.store', $invitation->id),
                    ['photo' => $file]
                );

                $response->assertStatus(201);
                $photos[] = Gallery::where('invitation_id', $invitation->id)->orderBy('id', 'desc')->first();
            }

            // Delete one photo and verify
            $photoToDelete = $photos[0];
            $photoId = $photoToDelete->id;
            $photoPath = $photoToDelete->photo_path;

            // Property: Photo should exist before deletion
            $this->assertDatabaseHas('galleries', ['id' => $photoId]);
            Storage::disk('public')->assertExists($photoPath);

            $initialCount = Gallery::where('invitation_id', $invitation->id)->count();

            $response = $this->actingAs($user)->deleteJson(
                route('gallery.destroy', [$invitation->id, $photoId])
            );

            // Property: Deletion should succeed
            $response->assertStatus(200)
                ->assertJson(['success' => true]);

            // Property: Gallery record should be removed from database
            $this->assertDatabaseMissing('galleries', ['id' => $photoId]);

            // Property: File should be removed from storage
            Storage::disk('public')->assertMissing($photoPath);

            // Property: Photo count should decrease by 1
            $newCount = Gallery::where('invitation_id', $invitation->id)->count();
            $this->assertEquals($initialCount - 1, $newCount, "Failed for: {$testCase['description']}");

            // Property: Other photos should remain unaffected
            for ($i = 1; $i < count($photos); $i++) {
                $this->assertDatabaseHas('galleries', ['id' => $photos[$i]->id]);
                Storage::disk('public')->assertExists($photos[$i]->photo_path);
            }
        }
    }

    /**
     * Property 15: Adding Photo Preserves Existing Photos
     *
     * For any gallery with existing photos, adding a new photo should
     * increase the photo count by 1 without removing any existing photos.
     *
     * **Validates: Requirements 4.5**
     */
    public function test_property_adding_photo_preserves_existing_photos(): void
    {
        // Test with different numbers of existing photos
        $testCases = [
            ['existing' => 0, 'description' => 'empty gallery'],
            ['existing' => 1, 'description' => 'one existing photo'],
            ['existing' => 3, 'description' => 'three existing photos'],
            ['existing' => 5, 'description' => 'five existing photos'],
        ];

        foreach ($testCases as $testCase) {
            $user = User::factory()->create();
            $invitation = Invitation::factory()->create(['user_id' => $user->id]);

            // Create existing photos
            $existingPhotos = [];
            for ($i = 0; $i < $testCase['existing']; $i++) {
                $file = UploadedFile::fake()->image("existing{$i}.jpg");

                $response = $this->actingAs($user)->postJson(
                    route('gallery.store', $invitation->id),
                    ['photo' => $file]
                );

                $response->assertStatus(201);
                $existingPhotos[] = Gallery::where('invitation_id', $invitation->id)->orderBy('id', 'desc')->first();
            }

            // Property: Initial count should match expected
            $initialCount = Gallery::where('invitation_id', $invitation->id)->count();
            $this->assertEquals($testCase['existing'], $initialCount);

            // Store existing photo IDs and paths
            $existingPhotoIds = array_map(fn($p) => $p->id, $existingPhotos);
            $existingPhotoPaths = array_map(fn($p) => $p->photo_path, $existingPhotos);

            // Add new photo
            $newFile = UploadedFile::fake()->image('new_photo.jpg');
            $response = $this->actingAs($user)->postJson(
                route('gallery.store', $invitation->id),
                ['photo' => $newFile]
            );

            // Property: Upload should succeed
            $response->assertStatus(201);

            // Property: Photo count should increase by exactly 1
            $newCount = Gallery::where('invitation_id', $invitation->id)->count();
            $this->assertEquals($initialCount + 1, $newCount, "Failed for: {$testCase['description']}");

            // Property: All existing photos should still exist in database
            foreach ($existingPhotoIds as $photoId) {
                $this->assertDatabaseHas('galleries', ['id' => $photoId]);
            }

            // Property: All existing photo files should still exist in storage
            foreach ($existingPhotoPaths as $photoPath) {
                Storage::disk('public')->assertExists($photoPath);
            }

            // Property: New photo should have correct order (max + 1)
            $newPhoto = Gallery::where('invitation_id', $invitation->id)->orderBy('id', 'desc')->first();
            $expectedOrder = $testCase['existing'] + 1;
            $this->assertEquals($expectedOrder, $newPhoto->order, "Failed for: {$testCase['description']}");
        }
    }

    /**
     * Additional Property: Photo Upload Order is Sequential
     *
     * For any sequence of photo uploads, each new photo should receive
     * an order number that is one greater than the previous maximum.
     */
    public function test_property_photo_upload_order_is_sequential(): void
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        $expectedOrder = 1;

        // Upload multiple photos and verify order
        for ($i = 0; $i < 5; $i++) {
            $file = UploadedFile::fake()->image("photo{$i}.jpg");

            $response = $this->actingAs($user)->postJson(
                route('gallery.store', $invitation->id),
                ['photo' => $file]
            );

            $response->assertStatus(201);

            // Property: New photo should have expected order
            $latestPhoto = Gallery::where('invitation_id', $invitation->id)->orderBy('id', 'desc')->first();
            $this->assertEquals($expectedOrder, $latestPhoto->order);

            $expectedOrder++;
        }
    }

    /**
     * Additional Property: User Cannot Upload Photo to Other User's Invitation
     *
     * For any invitation owned by another user, upload attempts should fail
     * and not create any gallery records or storage files.
     */
    public function test_property_user_cannot_upload_photo_to_other_users_invitation(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user2->id]);

        $files = [
            UploadedFile::fake()->image('photo1.jpg'),
            UploadedFile::fake()->image('photo2.png'),
            UploadedFile::fake()->image('photo3.jpeg'),
        ];

        foreach ($files as $file) {
            $initialCount = Gallery::where('invitation_id', $invitation->id)->count();

            $response = $this->actingAs($user1)->postJson(
                route('gallery.store', $invitation->id),
                ['photo' => $file]
            );

            // Property: Upload should fail with 404 (invitation not found for this user)
            $response->assertStatus(404);

            // Property: No gallery record should be created
            $newCount = Gallery::where('invitation_id', $invitation->id)->count();
            $this->assertEquals($initialCount, $newCount);
        }
    }

    /**
     * Additional Property: User Cannot Delete Photo from Other User's Invitation
     *
     * For any photo in another user's invitation, deletion attempts should fail
     * and the photo should remain in both database and storage.
     */
    public function test_property_user_cannot_delete_photo_from_other_users_invitation(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user2->id]);

        // Create photos as user2
        $photos = [];
        for ($i = 0; $i < 3; $i++) {
            $file = UploadedFile::fake()->image("photo{$i}.jpg");

            $response = $this->actingAs($user2)->postJson(
                route('gallery.store', $invitation->id),
                ['photo' => $file]
            );

            $response->assertStatus(201);
            $photos[] = Gallery::where('invitation_id', $invitation->id)->orderBy('id', 'desc')->first();
        }

        // Try to delete as user1
        foreach ($photos as $photo) {
            $photoId = $photo->id;
            $photoPath = $photo->photo_path;

            $response = $this->actingAs($user1)->deleteJson(
                route('gallery.destroy', [$invitation->id, $photoId])
            );

            // Property: Deletion should fail with 404
            $response->assertStatus(404);

            // Property: Photo should still exist in database
            $this->assertDatabaseHas('galleries', ['id' => $photoId]);

            // Property: Photo should still exist in storage
            Storage::disk('public')->assertExists($photoPath);
        }
    }
}
