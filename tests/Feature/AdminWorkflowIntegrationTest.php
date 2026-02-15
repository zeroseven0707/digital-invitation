<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Template;
use App\Models\Invitation;
use App\Models\Guest;
use App\Models\InvitationView;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminWorkflowIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test complete admin workflow for user management
     */
    public function test_admin_can_manage_users_complete_workflow(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user1 = User::factory()->create(['is_active' => true]);
        $user2 = User::factory()->create(['is_active' => true]);

        // Create invitations for users
        Invitation::factory()->count(3)->create(['user_id' => $user1->id]);
        Invitation::factory()->count(2)->create(['user_id' => $user2->id]);

        // Step 1: Admin accesses dashboard
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Admin Dashboard');

        // Step 2: Admin views all users
        $response = $this->actingAs($admin)->get('/admin/users');
        $response->assertStatus(200);
        $response->assertSee($user1->email);
        $response->assertSee($user2->email);

        // Step 3: Admin views specific user details
        $response = $this->actingAs($admin)->get("/admin/users/{$user1->id}");
        $response->assertStatus(200);
        $response->assertSee($user1->name);
        $response->assertSee('3'); // Number of invitations

        // Step 4: Admin deactivates user
        $response = $this->actingAs($admin)->post("/admin/users/{$user1->id}/deactivate");
        $response->assertRedirect();

        $user1->refresh();
        $this->assertFalse($user1->is_active);

        // Step 5: Verify deactivated user cannot login
        $response = $this->post('/login', [
            'email' => $user1->email,
            'password' => 'password',
        ]);
        $response->assertSessionHasErrors();

        // Step 6: Admin reactivates user
        $response = $this->actingAs($admin)->post("/admin/users/{$user1->id}/activate");
        $response->assertRedirect();

        $user1->refresh();
        $this->assertTrue($user1->is_active);

        // Step 7: Verify platform statistics
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Total Users');
        $response->assertSee('Total Invitations');
    }

    /**
     * Test admin template management workflow
     */
    public function test_admin_can_manage_templates_complete_workflow(): void
    {
        Storage::fake('local');

        $admin = User::factory()->create(['is_admin' => true]);

        // Step 1: View templates list
        $template1 = Template::factory()->create(['name' => 'Template 1']);
        $template2 = Template::factory()->create(['name' => 'Template 2']);

        $response = $this->actingAs($admin)->get('/admin/templates');
        $response->assertStatus(200);
        $response->assertSee('Template 1');
        $response->assertSee('Template 2');

        // Step 2: Create new template
        $response = $this->actingAs($admin)->get('/admin/templates/create');
        $response->assertStatus(200);

        $htmlFile = UploadedFile::fake()->createWithContent('template.html', '<html><body>{{bride_name}}</body></html>');
        $cssFile = UploadedFile::fake()->createWithContent('style.css', 'body { color: red; }');
        $jsFile = UploadedFile::fake()->createWithContent('script.js', 'console.log("test");');
        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg');

        $response = $this->actingAs($admin)->post('/admin/templates', [
            'name' => 'New Template',
            'description' => 'A new template',
            'html_file' => $htmlFile,
            'css_file' => $cssFile,
            'js_file' => $jsFile,
            'thumbnail' => $thumbnail,
        ]);

        $response->assertRedirect('/admin/templates');
        $this->assertDatabaseHas('templates', [
            'name' => 'New Template',
        ]);

        // Step 3: Try to delete template without usage
        $unusedTemplate = Template::factory()->create();

        $response = $this->actingAs($admin)->delete("/admin/templates/{$unusedTemplate->id}");
        $response->assertRedirect();
        $this->assertDatabaseMissing('templates', ['id' => $unusedTemplate->id]);

        // Step 4: Try to delete template with usage (should fail)
        $usedTemplate = Template::factory()->create();
        Invitation::factory()->create(['template_id' => $usedTemplate->id]);

        $response = $this->actingAs($admin)->delete("/admin/templates/{$usedTemplate->id}");
        $response->assertRedirect();
        $this->assertDatabaseHas('templates', ['id' => $usedTemplate->id]);
    }

    /**
     * Test admin can view platform statistics
     */
    public function test_admin_can_view_platform_statistics(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        // Create test data
        $users = User::factory()->count(10)->create();
        $templates = Template::factory()->count(3)->create();

        foreach ($users as $user) {
            $invitations = Invitation::factory()->count(2)->create([
                'user_id' => $user->id,
                'template_id' => $templates->random()->id,
            ]);

            foreach ($invitations as $invitation) {
                InvitationView::factory()->count(5)->create([
                    'invitation_id' => $invitation->id,
                ]);
            }
        }

        // View dashboard with statistics
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);

        // Should see statistics
        $response->assertSee('Total Users');
        $response->assertSee('Total Invitations');
        $response->assertSee('Total Views');

        // Verify counts are correct
        $totalUsers = User::count();
        $totalInvitations = Invitation::count();
        $totalViews = InvitationView::count();

        $this->assertGreaterThan(0, $totalUsers);
        $this->assertGreaterThan(0, $totalInvitations);
        $this->assertGreaterThan(0, $totalViews);
    }

    /**
     * Test non-admin cannot access admin panel
     */
    public function test_non_admin_cannot_access_admin_panel(): void
    {
        $regularUser = User::factory()->create(['is_admin' => false]);

        // Try to access admin dashboard
        $response = $this->actingAs($regularUser)->get('/admin/dashboard');
        $response->assertStatus(403);

        // Try to access user management
        $response = $this->actingAs($regularUser)->get('/admin/users');
        $response->assertStatus(403);

        // Try to access template management
        $response = $this->actingAs($regularUser)->get('/admin/templates');
        $response->assertStatus(403);
    }

    /**
     * Test admin can monitor user activity
     */
    public function test_admin_can_monitor_user_activity(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();

        // User creates invitations
        $invitation1 = Invitation::factory()->create([
            'user_id' => $user->id,
            'status' => 'published',
        ]);

        $invitation2 = Invitation::factory()->create([
            'user_id' => $user->id,
            'status' => 'draft',
        ]);

        // Add guests
        Guest::factory()->count(10)->create(['invitation_id' => $invitation1->id]);
        Guest::factory()->count(5)->create(['invitation_id' => $invitation2->id]);

        // Add views
        InvitationView::factory()->count(50)->create(['invitation_id' => $invitation1->id]);

        // Admin views user details
        $response = $this->actingAs($admin)->get("/admin/users/{$user->id}");
        $response->assertStatus(200);

        // Should see user's invitations
        $response->assertSee($invitation1->bride_name);
        $response->assertSee($invitation2->bride_name);

        // Should see statistics
        $this->assertEquals(2, $user->invitations()->count());
        $this->assertEquals(15, Guest::whereIn('invitation_id', $user->invitations->pluck('id'))->count());
    }

    /**
     * Test admin workflow for handling inactive users
     */
    public function test_admin_workflow_for_inactive_users(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $activeUser = User::factory()->create(['is_active' => true]);
        $inactiveUser = User::factory()->create(['is_active' => false]);

        // Create invitations for both users
        Invitation::factory()->create([
            'user_id' => $activeUser->id,
            'status' => 'published',
            'unique_url' => 'active-user-invitation',
        ]);

        Invitation::factory()->create([
            'user_id' => $inactiveUser->id,
            'status' => 'published',
            'unique_url' => 'inactive-user-invitation',
        ]);

        // Admin views all users
        $response = $this->actingAs($admin)->get('/admin/users');
        $response->assertStatus(200);

        // Admin can see both active and inactive users
        $response->assertSee($activeUser->email);
        $response->assertSee($inactiveUser->email);

        // Inactive user cannot login
        $response = $this->post('/login', [
            'email' => $inactiveUser->email,
            'password' => 'password',
        ]);
        $response->assertSessionHasErrors();

        // Admin reactivates user
        $response = $this->actingAs($admin)->post("/admin/users/{$inactiveUser->id}/activate");
        $response->assertRedirect();

        $inactiveUser->refresh();
        $this->assertTrue($inactiveUser->is_active);
    }

    /**
     * Test admin can manage templates used by multiple users
     */
    public function test_admin_manages_templates_with_multiple_users(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $template = Template::factory()->create(['name' => 'Popular Template']);

        // Multiple users use the same template
        $users = User::factory()->count(5)->create();
        foreach ($users as $user) {
            Invitation::factory()->count(2)->create([
                'user_id' => $user->id,
                'template_id' => $template->id,
            ]);
        }

        // Admin tries to delete the template
        $response = $this->actingAs($admin)->delete("/admin/templates/{$template->id}");
        $response->assertRedirect();

        // Template should still exist because it's in use
        $this->assertDatabaseHas('templates', ['id' => $template->id]);

        // Verify all invitations still reference the template
        $this->assertEquals(10, Invitation::where('template_id', $template->id)->count());
    }
}
