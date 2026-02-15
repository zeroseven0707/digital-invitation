<?php

namespace Tests\Feature;

use App\Models\Gallery;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GalleryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_user_can_upload_photo_to_their_invitation()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);
        $file = UploadedFile::fake()->image('photo.jpg', 800, 600);

        $response = $this->actingAs($user)->postJson(
            route('gallery.store', $invitation->id),
            ['photo' => $file]
        );

        $response->assertStatus(201)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('galleries', [
            'invitation_id' => $invitation->id,
        ]);
    }

    public function test_user_cannot_upload_photo_to_other_users_invitation()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $otherUser->id]);
        $file = UploadedFile::fake()->image('photo.jpg');

        $response = $this->actingAs($user)->postJson(
            route('gallery.store', $invitation->id),
            ['photo' => $file]
        );

        $response->assertStatus(404);
    }

    public function test_upload_photo_requires_authentication()
    {
        $invitation = Invitation::factory()->create();
        $file = UploadedFile::fake()->image('photo.jpg');

        $response = $this->postJson(
            route('gallery.store', $invitation->id),
            ['photo' => $file]
        );

        $response->assertStatus(401);
    }

    public function test_upload_photo_validates_file_type()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);
        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->actingAs($user)->postJson(
            route('gallery.store', $invitation->id),
            ['photo' => $file]
        );

        $response->assertStatus(422)
            ->assertJsonValidationErrors('photo');
    }

    public function test_upload_photo_validates_file_size()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);
        $file = UploadedFile::fake()->image('large.jpg')->size(3000);

        $response = $this->actingAs($user)->postJson(
            route('gallery.store', $invitation->id),
            ['photo' => $file]
        );

        $response->assertStatus(422)
            ->assertJsonValidationErrors('photo');
    }

    public function test_upload_photo_sets_correct_order()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Create existing photos
        Gallery::factory()->create(['invitation_id' => $invitation->id, 'order' => 1]);
        Gallery::factory()->create(['invitation_id' => $invitation->id, 'order' => 2]);

        $file = UploadedFile::fake()->image('photo.jpg');

        $response = $this->actingAs($user)->postJson(
            route('gallery.store', $invitation->id),
            ['photo' => $file]
        );

        $response->assertStatus(201);

        $this->assertDatabaseHas('galleries', [
            'invitation_id' => $invitation->id,
            'order' => 3,
        ]);
    }

    public function test_user_can_delete_photo_from_their_invitation()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);
        $gallery = Gallery::factory()->create(['invitation_id' => $invitation->id]);

        $response = $this->actingAs($user)->deleteJson(
            route('gallery.destroy', [$invitation->id, $gallery->id])
        );

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('galleries', [
            'id' => $gallery->id,
        ]);
    }

    public function test_user_cannot_delete_photo_from_other_users_invitation()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $otherUser->id]);
        $gallery = Gallery::factory()->create(['invitation_id' => $invitation->id]);

        $response = $this->actingAs($user)->deleteJson(
            route('gallery.destroy', [$invitation->id, $gallery->id])
        );

        $response->assertStatus(404);

        $this->assertDatabaseHas('galleries', [
            'id' => $gallery->id,
        ]);
    }

    public function test_user_can_reorder_photos()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        $photo1 = Gallery::factory()->create(['invitation_id' => $invitation->id, 'order' => 1]);
        $photo2 = Gallery::factory()->create(['invitation_id' => $invitation->id, 'order' => 2]);
        $photo3 = Gallery::factory()->create(['invitation_id' => $invitation->id, 'order' => 3]);

        $response = $this->actingAs($user)->postJson(
            route('gallery.reorder', $invitation->id),
            [
                'photos' => [
                    ['id' => $photo3->id, 'order' => 1],
                    ['id' => $photo1->id, 'order' => 2],
                    ['id' => $photo2->id, 'order' => 3],
                ]
            ]
        );

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('galleries', ['id' => $photo3->id, 'order' => 1]);
        $this->assertDatabaseHas('galleries', ['id' => $photo1->id, 'order' => 2]);
        $this->assertDatabaseHas('galleries', ['id' => $photo2->id, 'order' => 3]);
    }

    public function test_reorder_validates_photos_array()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->postJson(
            route('gallery.reorder', $invitation->id),
            ['photos' => 'invalid']
        );

        $response->assertStatus(422)
            ->assertJsonValidationErrors('photos');
    }

    public function test_adding_photo_preserves_existing_photos()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Create existing photos
        $existingPhoto1 = Gallery::factory()->create(['invitation_id' => $invitation->id, 'order' => 1]);
        $existingPhoto2 = Gallery::factory()->create(['invitation_id' => $invitation->id, 'order' => 2]);

        $file = UploadedFile::fake()->image('new-photo.jpg');

        $response = $this->actingAs($user)->postJson(
            route('gallery.store', $invitation->id),
            ['photo' => $file]
        );

        $response->assertStatus(201);

        // Verify existing photos still exist
        $this->assertDatabaseHas('galleries', ['id' => $existingPhoto1->id]);
        $this->assertDatabaseHas('galleries', ['id' => $existingPhoto2->id]);

        // Verify total count
        $this->assertEquals(3, Gallery::where('invitation_id', $invitation->id)->count());
    }
}
