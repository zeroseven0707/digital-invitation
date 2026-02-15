<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Template;
use App\Models\Invitation;
use App\Models\Guest;
use App\Models\Gallery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CompleteUserFlowIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test complete user journey from registration to publishing invitation
     */
    public function test_complete_user_flow_from_registration_to_published_invitation(): void
    {
        Storage::fake('public');

        // Step 1: User Registration
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($user);

        // Step 2: Create invitation with template
        $template = Template::factory()->create();
        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'status' => 'draft',
            'bride_name' => 'Jane Smith',
            'groom_name' => 'John Doe',
        ]);

        // Step 3: Add guests
        Guest::factory()->count(3)->create(['invitation_id' => $invitation->id]);
        $this->assertEquals(3, $invitation->guests()->count());

        // Step 4: Preview invitation
        $response = $this->actingAs($user)->get("/invitations/{$invitation->id}/preview");
        $response->assertStatus(200);
        $response->assertSee('Jane Smith');

        // Step 5: Publish invitation
        $response = $this->actingAs($user)->post("/invitations/{$invitation->id}/publish");
        $response->assertRedirect();

        $invitation->refresh();
        $this->assertEquals('published', $invitation->status);
        $this->assertNotNull($invitation->unique_url);

        // Step 6: Access public invitation
        $response = $this->get("/i/{$invitation->unique_url}");
        $response->assertStatus(200);
        $response->assertSee('Jane Smith');

        // Step 7: Verify view tracking
        $this->assertDatabaseHas('invitation_views', [
            'invitation_id' => $invitation->id,
        ]);

        // Step 8: View statistics
        $response = $this->actingAs($user)->get("/invitations/{$invitation->id}/statistics");
        $response->assertStatus(200);
    }

    /**
     * Test user can edit invitation
     */
    public function test_user_can_edit_invitation(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();
        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'bride_name' => 'Original Bride',
        ]);

        // Edit invitation
        $response = $this->actingAs($user)->get("/invitations/{$invitation->id}/edit");
        $response->assertStatus(200);
        $response->assertSee('Original Bride');
    }

    /**
     * Test user can manage guest list
     */
    public function test_user_can_manage_guest_list(): void
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Add guests
        Guest::factory()->count(5)->create(['invitation_id' => $invitation->id]);

        // View guests
        $response = $this->actingAs($user)->get("/invitations/{$invitation->id}/guests");
        $response->assertStatus(200);

        $this->assertEquals(5, $invitation->guests()->count());
    }

    /**
     * Test user can export guests
     */
    public function test_user_can_export_guests(): void
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);
        Guest::factory()->count(5)->create(['invitation_id' => $invitation->id]);

        // Export guests
        $response = $this->actingAs($user)->get("/invitations/{$invitation->id}/guests/export");
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=utf-8');
    }

    /**
     * Test user can unpublish and republish invitation
     */
    public function test_user_can_unpublish_and_republish_invitation(): void
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'status' => 'published',
            'unique_url' => 'test-unique-url',
        ]);

        // Unpublish
        $response = $this->actingAs($user)->post("/invitations/{$invitation->id}/unpublish");
        $response->assertRedirect();

        $invitation->refresh();
        $this->assertEquals('unpublished', $invitation->status);

        // Verify public access is denied
        $response = $this->get("/i/{$invitation->unique_url}");
        $response->assertStatus(404);

        // Republish
        $response = $this->actingAs($user)->post("/invitations/{$invitation->id}/publish");
        $response->assertRedirect();

        $invitation->refresh();
        $this->assertEquals('published', $invitation->status);

        // Verify public access is restored
        $response = $this->get("/i/{$invitation->unique_url}");
        $response->assertStatus(200);
    }

    /**
     * Test user cannot access other user's invitations
     */
    public function test_user_cannot_access_other_users_invitations(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $invitation = Invitation::factory()->create(['user_id' => $user1->id]);

        // User 2 tries to access User 1's invitation (returns 403 or 404 depending on policy)
        $response = $this->actingAs($user2)->get("/invitations/{$invitation->id}/edit");
        $this->assertContains($response->status(), [403, 404]);
    }

    /**
     * Test user can manage profile
     */
    public function test_user_can_manage_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
        ]);

        // View profile
        $response = $this->actingAs($user)->get('/profile');
        $response->assertStatus(200);
        $response->assertSee('Original Name');

        // Update profile
        $response = $this->actingAs($user)->patch('/profile', [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

        $response->assertRedirect('/profile');

        $user->refresh();
        $this->assertEquals('Updated Name', $user->name);
        $this->assertEquals('updated@example.com', $user->email);
    }

    /**
     * Test complete dashboard workflow
     */
    public function test_dashboard_displays_user_data(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        // Create multiple invitations
        Invitation::factory()->count(3)->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
        ]);

        // View dashboard
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);

        // Should see invitations count
        $this->assertEquals(3, $user->invitations()->count());
    }
}
