<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\Rsvp;
use App\Models\Template;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RsvpDisplayTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_invitation_displays_rsvps(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'status' => 'published',
        ]);

        // Create some RSVPs
        Rsvp::factory()->count(3)->create([
            'invitation_id' => $invitation->id,
        ]);

        $response = $this->get("/i/{$invitation->unique_url}");

        $response->assertStatus(200);
        $response->assertSee('Ucapan & Doa (3)');
    }

    public function test_public_invitation_shows_guest_name_in_opening(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'status' => 'published',
        ]);

        $guestName = 'John Doe';
        $response = $this->get("/i/{$invitation->unique_url}?to=" . urlencode($guestName));

        $response->assertStatus(200);
        $response->assertSee($guestName);
    }

    public function test_rsvp_list_shows_latest_messages_first(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'status' => 'published',
        ]);

        // Create RSVPs with different timestamps
        $oldRsvp = Rsvp::factory()->create([
            'invitation_id' => $invitation->id,
            'name' => 'Old Guest',
            'created_at' => now()->subDays(5),
        ]);

        $newRsvp = Rsvp::factory()->create([
            'invitation_id' => $invitation->id,
            'name' => 'New Guest',
            'created_at' => now(),
        ]);

        $response = $this->get("/i/{$invitation->unique_url}");

        $response->assertStatus(200);

        // Check that the content appears in the correct order
        $content = $response->getContent();
        $newGuestPosition = strpos($content, 'New Guest');
        $oldGuestPosition = strpos($content, 'Old Guest');

        // New guest should appear before old guest
        $this->assertLessThan($oldGuestPosition, $newGuestPosition);
    }

    public function test_rsvp_list_limits_to_10_messages(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'status' => 'published',
        ]);

        // Create 15 RSVPs
        Rsvp::factory()->count(15)->create([
            'invitation_id' => $invitation->id,
        ]);

        $response = $this->get("/i/{$invitation->unique_url}");

        $response->assertStatus(200);

        // Should show count of all RSVPs
        $response->assertSee('Ucapan & Doa (15)');

        // But only display 10 items (check for rsvp-item class occurrences)
        $content = $response->getContent();
        $itemCount = substr_count($content, 'class="rsvp-item"');
        $this->assertEquals(10, $itemCount);
    }
}
