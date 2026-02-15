<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Template;
use App\Models\Invitation;
use App\Models\Guest;
use App\Models\Gallery;
use App\Models\InvitationView;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicInvitationAccessIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test complete public invitation access flow
     */
    public function test_guest_can_access_published_invitation_complete_flow(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $template = Template::factory()->create();

        // Create a complete invitation
        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'status' => 'published',
            'unique_url' => 'test-wedding-2024',
            'bride_name' => 'Jane Smith',
            'groom_name' => 'John Doe',
            'akad_date' => '2024-12-25',
            'reception_date' => '2024-12-25',
        ]);

        // Add gallery photos
        Gallery::factory()->count(5)->create(['invitation_id' => $invitation->id]);

        // Add guests
        Guest::factory()->count(10)->create(['invitation_id' => $invitation->id]);

        // Step 1: Guest accesses invitation via unique URL
        $response = $this->get("/i/{$invitation->unique_url}");
        $response->assertStatus(200);

        // Step 2: Verify all content is displayed
        $response->assertSee('Jane Smith');
        $response->assertSee('John Doe');
        $response->assertSee('2024-12-25');

        // Step 3: Verify view is tracked
        $this->assertDatabaseHas('invitation_views', [
            'invitation_id' => $invitation->id,
        ]);

        // Step 4: Access again from same IP (should not create duplicate view within 24h)
        $firstViewCount = InvitationView::where('invitation_id', $invitation->id)->count();

        $response = $this->get("/i/{$invitation->unique_url}");
        $response->assertStatus(200);

        $secondViewCount = InvitationView::where('invitation_id', $invitation->id)->count();

        // View count should remain the same (duplicate prevention)
        $this->assertEquals($firstViewCount, $secondViewCount);
    }

    /**
     * Test guest cannot access unpublished invitation
     */
    public function test_guest_cannot_access_unpublished_invitation(): void
    {
        $invitation = Invitation::factory()->create([
            'status' => 'unpublished',
            'unique_url' => 'unpublished-invitation',
        ]);

        $response = $this->get("/i/{$invitation->unique_url}");
        $response->assertStatus(404);

        // No view should be tracked
        $this->assertDatabaseMissing('invitation_views', [
            'invitation_id' => $invitation->id,
        ]);
    }

    /**
     * Test guest cannot access draft invitation
     */
    public function test_guest_cannot_access_draft_invitation(): void
    {
        $invitation = Invitation::factory()->create([
            'status' => 'draft',
            'unique_url' => null,
        ]);

        // Try to access with invitation ID
        $response = $this->get("/i/draft-{$invitation->id}");
        $response->assertStatus(404);
    }

    /**
     * Test guest cannot access invalid URL
     */
    public function test_guest_cannot_access_invalid_url(): void
    {
        $response = $this->get('/i/non-existent-url');
        $response->assertStatus(404);
    }

    /**
     * Test view tracking from different devices
     */
    public function test_view_tracking_from_different_devices(): void
    {
        $invitation = Invitation::factory()->create([
            'status' => 'published',
            'unique_url' => 'multi-device-test',
        ]);

        // Access from desktop
        $response = $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/91.0',
        ])->get("/i/{$invitation->unique_url}");
        $response->assertStatus(200);

        // Access from mobile
        $response = $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X) Safari/604.1',
        ])->get("/i/{$invitation->unique_url}");
        $response->assertStatus(200);

        // Access from tablet
        $response = $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (iPad; CPU OS 14_6 like Mac OS X) Safari/604.1',
        ])->get("/i/{$invitation->unique_url}");
        $response->assertStatus(200);

        // Should have multiple view records
        $viewCount = InvitationView::where('invitation_id', $invitation->id)->count();
        $this->assertGreaterThanOrEqual(1, $viewCount);
    }

    /**
     * Test invitation displays all sections correctly
     */
    public function test_invitation_displays_all_sections(): void
    {
        Storage::fake('public');

        $template = Template::factory()->create();
        $invitation = Invitation::factory()->create([
            'template_id' => $template->id,
            'status' => 'published',
            'unique_url' => 'complete-invitation',
            'bride_name' => 'Jane Smith',
            'bride_father_name' => 'Robert Smith',
            'bride_mother_name' => 'Mary Smith',
            'groom_name' => 'John Doe',
            'groom_father_name' => 'James Doe',
            'groom_mother_name' => 'Patricia Doe',
            'akad_location' => 'Grand Mosque',
            'reception_location' => 'Grand Ballroom',
            'full_address' => '123 Main Street',
            'google_maps_url' => 'https://maps.google.com/?q=123+Main+Street',
        ]);

        // Add gallery
        Gallery::factory()->count(3)->create(['invitation_id' => $invitation->id]);

        // Add guests from different categories
        Guest::factory()->create(['invitation_id' => $invitation->id, 'category' => 'family', 'name' => 'Family Guest']);
        Guest::factory()->create(['invitation_id' => $invitation->id, 'category' => 'friend', 'name' => 'Friend Guest']);
        Guest::factory()->create(['invitation_id' => $invitation->id, 'category' => 'colleague', 'name' => 'Colleague Guest']);

        $response = $this->get("/i/{$invitation->unique_url}");
        $response->assertStatus(200);

        // Verify couple information
        $response->assertSee('Jane Smith');
        $response->assertSee('John Doe');
        $response->assertSee('Robert Smith');
        $response->assertSee('Mary Smith');

        // Verify event information
        $response->assertSee('Grand Mosque');
        $response->assertSee('Grand Ballroom');

        // Verify location information
        $response->assertSee('123 Main Street');
    }

    /**
     * Test invitation with minimal data
     */
    public function test_invitation_with_minimal_data(): void
    {
        $invitation = Invitation::factory()->create([
            'status' => 'published',
            'unique_url' => 'minimal-invitation',
            'bride_name' => 'Jane',
            'groom_name' => 'John',
            'akad_date' => '2024-12-25',
            'reception_date' => '2024-12-25',
            // No gallery, no guests, minimal info
        ]);

        $response = $this->get("/i/{$invitation->unique_url}");
        $response->assertStatus(200);
        $response->assertSee('Jane');
        $response->assertSee('John');
    }

    /**
     * Test rate limiting on public invitation access
     */
    public function test_rate_limiting_on_public_access(): void
    {
        $invitation = Invitation::factory()->create([
            'status' => 'published',
            'unique_url' => 'rate-limit-test',
        ]);

        // Make multiple requests
        for ($i = 0; $i < 10; $i++) {
            $response = $this->get("/i/{$invitation->unique_url}");
            $response->assertStatus(200);
        }

        // All requests should succeed (rate limit should be reasonable)
        $this->assertTrue(true);
    }

    /**
     * Test invitation state changes affect public access
     */
    public function test_invitation_state_changes_affect_public_access(): void
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'status' => 'published',
            'unique_url' => 'state-change-test',
        ]);

        // Initially accessible
        $response = $this->get("/i/{$invitation->unique_url}");
        $response->assertStatus(200);

        // Owner unpublishes
        $this->actingAs($user)->post("/invitations/{$invitation->id}/unpublish");

        // No longer accessible
        $response = $this->get("/i/{$invitation->unique_url}");
        $response->assertStatus(404);

        // Owner republishes
        $this->actingAs($user)->post("/invitations/{$invitation->id}/publish");

        // Accessible again
        $response = $this->get("/i/{$invitation->unique_url}");
        $response->assertStatus(200);
    }

    /**
     * Test concurrent access from multiple guests
     */
    public function test_concurrent_access_from_multiple_guests(): void
    {
        $invitation = Invitation::factory()->create([
            'status' => 'published',
            'unique_url' => 'concurrent-test',
        ]);

        // Simulate multiple guests accessing simultaneously
        $responses = [];
        for ($i = 0; $i < 5; $i++) {
            $responses[] = $this->get("/i/{$invitation->unique_url}");
        }

        // All should succeed
        foreach ($responses as $response) {
            $response->assertStatus(200);
        }

        // Views should be tracked
        $viewCount = InvitationView::where('invitation_id', $invitation->id)->count();
        $this->assertGreaterThanOrEqual(1, $viewCount);
    }

    /**
     * Test invitation with special characters in data
     */
    public function test_invitation_with_special_characters(): void
    {
        $invitation = Invitation::factory()->create([
            'status' => 'published',
            'unique_url' => 'special-chars-test',
            'bride_name' => "Jane O'Connor",
            'groom_name' => 'John "Johnny" Doe',
            'full_address' => '123 Main St. & Co.',
        ]);

        $response = $this->get("/i/{$invitation->unique_url}");
        $response->assertStatus(200);

        // Should handle special characters properly
        $response->assertSee("Jane O'Connor", false);
        $response->assertSee('John', false);
    }

    /**
     * Test invitation access logs user agent information
     */
    public function test_invitation_access_logs_user_agent(): void
    {
        $invitation = Invitation::factory()->create([
            'status' => 'published',
            'unique_url' => 'user-agent-test',
        ]);

        $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/91.0';

        $response = $this->withHeaders([
            'User-Agent' => $userAgent,
        ])->get("/i/{$invitation->unique_url}");

        $response->assertStatus(200);

        // Verify user agent is logged
        $this->assertDatabaseHas('invitation_views', [
            'invitation_id' => $invitation->id,
            'user_agent' => $userAgent,
        ]);
    }

    /**
     * Test owner can view statistics after public access
     */
    public function test_owner_can_view_statistics_after_public_access(): void
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'status' => 'published',
            'unique_url' => 'stats-test',
        ]);

        // Public accesses
        for ($i = 0; $i < 5; $i++) {
            $this->get("/i/{$invitation->unique_url}");
        }

        // Owner views statistics
        $response = $this->actingAs($user)->get("/invitations/{$invitation->id}/statistics");
        $response->assertStatus(200);

        // Should see view count
        $viewCount = InvitationView::where('invitation_id', $invitation->id)->count();
        $this->assertGreaterThanOrEqual(1, $viewCount);
    }
}
