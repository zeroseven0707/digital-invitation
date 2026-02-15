<?php

namespace Tests\Feature;

use App\Models\Gallery;
use App\Models\Guest;
use App\Models\Invitation;
use App\Models\Template;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PreviewPublishPropertyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Property 25: Preview Shows Complete Invitation
     * For any invitation with data, the preview should display all filled sections
     * (couple info, event details, location, gallery, guest list) using the selected template.
     *
     * @property undangan-digital-laravel Property 25: Preview Shows Complete Invitation
     * Validates: Requirements 3.7, 6.1, 6.2, 7.3
     */
    public function test_property_preview_shows_complete_invitation(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $template = Template::factory()->create();

        for ($i = 0; $i < 50; $i++) {
            // Create invitation with complete random data
            $invitation = Invitation::factory()->create([
                'user_id' => $user->id,
                'template_id' => $template->id,
                'status' => 'draft',
                'bride_name' => fake()->firstName('female'),
                'groom_name' => fake()->firstName('male'),
                'bride_father_name' => fake()->name('male'),
                'bride_mother_name' => fake()->name('female'),
                'groom_father_name' => fake()->name('male'),
                'groom_mother_name' => fake()->name('female'),
                'akad_date' => fake()->dateTimeBetween('now', '+2 years'),
                'akad_time_start' => fake()->time('H:i'),
                'akad_time_end' => fake()->time('H:i'),
                'akad_location' => fake()->address(),
                'reception_date' => fake()->dateTimeBetween('now', '+2 years'),
                'reception_time_start' => fake()->time('H:i'),
                'reception_time_end' => fake()->time('H:i'),
                'reception_location' => fake()->address(),
                'full_address' => fake()->address(),
                'google_maps_url' => 'https://maps.google.com/?q=' . fake()->latitude() . ',' . fake()->longitude(),
                'music_url' => fake()->url(),
            ]);

            // Add random number of gallery photos (1-5)
            $photoCount = fake()->numberBetween(1, 5);
            for ($j = 0; $j < $photoCount; $j++) {
                Gallery::factory()->create([
                    'invitation_id' => $invitation->id,
                    'photo_path' => 'invitations/' . $invitation->id . '/gallery/photo_' . $j . '.jpg',
                    'order' => $j,
                ]);
            }

            // Add random number of guests (2-10)
            $guestCount = fake()->numberBetween(2, 10);
            $categories = ['family', 'friend', 'colleague'];
            for ($j = 0; $j < $guestCount; $j++) {
                Guest::factory()->create([
                    'invitation_id' => $invitation->id,
                    'name' => fake()->name(),
                    'category' => fake()->randomElement($categories),
                ]);
            }

            // Access preview
            $response = $this->actingAs($user)->get(route('invitations.preview', $invitation->id));

            // Verify preview loads successfully
            $response->assertStatus(200);
            $response->assertViewIs('invitations.preview');
            $response->assertViewHas('invitation');
            $response->assertViewHas('renderedTemplate');

            // Verify all couple info sections are present in rendered template (use escaped version for HTML)
            $response->assertSee(e($invitation->bride_name), false);
            $response->assertSee(e($invitation->groom_name), false);
            $response->assertSee(e($invitation->bride_father_name), false);
            $response->assertSee(e($invitation->bride_mother_name), false);
            $response->assertSee(e($invitation->groom_father_name), false);
            $response->assertSee(e($invitation->groom_mother_name), false);

            // Verify event details are present (use escaped version for HTML)
            $response->assertSee(e($invitation->akad_location), false);
            $response->assertSee(e($invitation->reception_location), false);
            $response->assertSee(e($invitation->full_address), false);

            // Verify location data is present
            $response->assertSee($invitation->google_maps_url, false);

            // Verify gallery photos are referenced (at least the first one)
            $firstGallery = $invitation->galleries()->first();
            if ($firstGallery) {
                $response->assertSee($firstGallery->photo_path, false);
            }

            // Verify guests are present (at least the first one, use escaped version)
            $firstGuest = $invitation->guests()->first();
            if ($firstGuest) {
                $response->assertSee(e($firstGuest->name), false);
            }

            // Verify preview header elements
            $response->assertSee('Preview Mode', false);
            $response->assertSee('Kembali ke Edit', false);
            $response->assertSee('Publikasikan', false);

            // Cleanup
            $invitation->galleries()->delete();
            $invitation->guests()->delete();
            $invitation->delete();
        }
    }

    /**
     * Property 26: Public View Matches Preview
     * For any published invitation, the public view (via unique URL) should
     * display the same content as the preview.
     *
     * @property undangan-digital-laravel Property 26: Public View Matches Preview
     * Validates: Requirements 6.2, 7.1
     */
    public function test_property_public_view_matches_preview(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $template = Template::factory()->create();

        for ($i = 0; $i < 50; $i++) {
            // Create published invitation with complete data
            $invitation = Invitation::factory()->create([
                'user_id' => $user->id,
                'template_id' => $template->id,
                'status' => 'published',
                'unique_url' => fake()->unique()->regexify('[a-zA-Z0-9]{32}'),
                'bride_name' => fake()->firstName('female'),
                'groom_name' => fake()->firstName('male'),
                'bride_father_name' => fake()->name('male'),
                'bride_mother_name' => fake()->name('female'),
                'groom_father_name' => fake()->name('male'),
                'groom_mother_name' => fake()->name('female'),
                'akad_date' => fake()->dateTimeBetween('now', '+2 years'),
                'akad_time_start' => fake()->time('H:i'),
                'akad_time_end' => fake()->time('H:i'),
                'akad_location' => fake()->address(),
                'reception_date' => fake()->dateTimeBetween('now', '+2 years'),
                'reception_time_start' => fake()->time('H:i'),
                'reception_time_end' => fake()->time('H:i'),
                'reception_location' => fake()->address(),
                'full_address' => fake()->address(),
                'google_maps_url' => 'https://maps.google.com/?q=' . fake()->latitude() . ',' . fake()->longitude(),
                'music_url' => fake()->url(),
            ]);

            // Add gallery photos
            $photoCount = fake()->numberBetween(1, 3);
            for ($j = 0; $j < $photoCount; $j++) {
                Gallery::factory()->create([
                    'invitation_id' => $invitation->id,
                    'photo_path' => 'invitations/' . $invitation->id . '/gallery/photo_' . $j . '.jpg',
                    'order' => $j,
                ]);
            }

            // Add guests
            $guestCount = fake()->numberBetween(2, 5);
            $categories = ['family', 'friend', 'colleague'];
            for ($j = 0; $j < $guestCount; $j++) {
                Guest::factory()->create([
                    'invitation_id' => $invitation->id,
                    'name' => fake()->name(),
                    'category' => fake()->randomElement($categories),
                ]);
            }

            // Get preview response
            $previewResponse = $this->actingAs($user)->get(route('invitations.preview', $invitation->id));
            $previewResponse->assertStatus(200);

            // Get public view response
            $publicResponse = $this->get(route('public.invitation', $invitation->unique_url));
            $publicResponse->assertStatus(200);

            // Both should display the same core content
            // Verify couple info in both (use escaped version for HTML)
            $previewResponse->assertSee(e($invitation->bride_name), false);
            $publicResponse->assertSee(e($invitation->bride_name), false);

            $previewResponse->assertSee(e($invitation->groom_name), false);
            $publicResponse->assertSee(e($invitation->groom_name), false);

            // Verify event details in both (use escaped version for HTML)
            $previewResponse->assertSee(e($invitation->akad_location), false);
            $publicResponse->assertSee(e($invitation->akad_location), false);

            $previewResponse->assertSee(e($invitation->reception_location), false);
            $publicResponse->assertSee(e($invitation->reception_location), false);

            // Verify location data in both
            $previewResponse->assertSee($invitation->google_maps_url, false);
            $publicResponse->assertSee($invitation->google_maps_url, false);

            // Verify gallery in both
            $firstGallery = $invitation->galleries()->first();
            if ($firstGallery) {
                $previewResponse->assertSee($firstGallery->photo_path, false);
                $publicResponse->assertSee($firstGallery->photo_path, false);
            }

            // Verify guests in both (use escaped version)
            $firstGuest = $invitation->guests()->first();
            if ($firstGuest) {
                $previewResponse->assertSee(e($firstGuest->name), false);
                $publicResponse->assertSee(e($firstGuest->name), false);
            }

            // Cleanup
            $invitation->galleries()->delete();
            $invitation->guests()->delete();
            $invitation->delete();
        }
    }

    /**
     * Property 27: Invalid URL Returns 404
     * For any non-existent or unpublished unique URL, accessing it should return a 404 error.
     *
     * @property undangan-digital-laravel Property 27: Invalid URL Returns 404
     * Validates: Requirements 7.2
     */
    public function test_property_invalid_url_returns_404(): void
    {
        // Disable rate limiting for this test
        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);

        $user = User::factory()->create();
        $template = Template::factory()->create();

        for ($i = 0; $i < 50; $i++) {
            // Test 1: Non-existent URL (random string that doesn't exist in database)
            $nonExistentUrl = fake()->unique()->regexify('[a-zA-Z0-9]{32}');
            $response = $this->get(route('public.invitation', $nonExistentUrl));
            $response->assertStatus(404);

            // Test 2: Unpublished invitation (draft status)
            $draftInvitation = Invitation::factory()->create([
                'user_id' => $user->id,
                'template_id' => $template->id,
                'status' => 'draft',
                'unique_url' => null, // Draft invitations don't have unique URLs
            ]);

            // Trying to access with a made-up URL should return 404
            $fakeUrl = fake()->regexify('[a-zA-Z0-9]{32}');
            $response = $this->get(route('public.invitation', $fakeUrl));
            $response->assertStatus(404);

            // Test 3: Unpublished invitation (was published, then unpublished)
            $unpublishedInvitation = Invitation::factory()->create([
                'user_id' => $user->id,
                'template_id' => $template->id,
                'status' => 'unpublished',
                'unique_url' => fake()->unique()->regexify('[a-zA-Z0-9]{32}'),
            ]);

            // Accessing unpublished invitation should return 404
            $response = $this->get(route('public.invitation', $unpublishedInvitation->unique_url));
            $response->assertStatus(404);

            // Cleanup
            $draftInvitation->delete();
            $unpublishedInvitation->delete();
        }
    }
}
