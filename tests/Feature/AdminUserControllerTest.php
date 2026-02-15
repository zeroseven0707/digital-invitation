<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\InvitationView;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->admin = User::factory()->create([
            'is_admin' => true,
            'is_active' => true,
        ]);

        // Create regular user
        $this->regularUser = User::factory()->create([
            'is_admin' => false,
            'is_active' => true,
        ]);
    }

    /** @test */
    public function admin_can_access_user_list()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.index');
        $response->assertViewHas('users');
    }

    /** @test */
    public function non_admin_cannot_access_user_list()
    {
        $response = $this->actingAs($this->regularUser)
            ->get(route('admin.users.index'));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
    }

    /** @test */
    public function guest_cannot_access_user_list()
    {
        $response = $this->get(route('admin.users.index'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function user_list_displays_all_users()
    {
        // Create additional users
        $users = User::factory()->count(5)->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.index'));

        $response->assertStatus(200);

        // Should see all users including admin and regular user
        foreach ($users as $user) {
            $response->assertSee($user->name);
            $response->assertSee($user->email);
        }
    }

    /** @test */
    public function user_list_can_search_by_name()
    {
        $user1 = User::factory()->create(['name' => 'John Doe']);
        $user2 = User::factory()->create(['name' => 'Jane Smith']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.index', ['search' => 'John']));

        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertDontSee('Jane Smith');
    }

    /** @test */
    public function user_list_can_search_by_email()
    {
        $user1 = User::factory()->create(['email' => 'john@example.com']);
        $user2 = User::factory()->create(['email' => 'jane@example.com']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.index', ['search' => 'john@']));

        $response->assertStatus(200);
        $response->assertSee('john@example.com');
        $response->assertDontSee('jane@example.com');
    }

    /** @test */
    public function user_list_can_filter_by_active_status()
    {
        $activeUser = User::factory()->create(['is_active' => true, 'name' => 'Active User']);
        $inactiveUser = User::factory()->create(['is_active' => false, 'name' => 'Inactive User']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.index', ['status' => 'active']));

        $response->assertStatus(200);
        $response->assertSee('Active User');
    }

    /** @test */
    public function user_list_can_filter_by_inactive_status()
    {
        $activeUser = User::factory()->create(['is_active' => true, 'name' => 'Active User']);
        $inactiveUser = User::factory()->create(['is_active' => false, 'name' => 'Inactive User']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.index', ['status' => 'inactive']));

        $response->assertStatus(200);
        $response->assertSee('Inactive User');
    }

    /** @test */
    public function user_list_can_filter_by_admin_role()
    {
        $adminUser = User::factory()->create(['is_admin' => true, 'name' => 'Admin User']);
        $regularUser = User::factory()->create(['is_admin' => false, 'name' => 'Regular User']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.index', ['role' => 'admin']));

        $response->assertStatus(200);
        $response->assertSee('Admin User');
    }

    /** @test */
    public function user_list_can_filter_by_user_role()
    {
        $adminUser = User::factory()->create(['is_admin' => true, 'name' => 'Admin User']);
        $regularUser = User::factory()->create(['is_admin' => false, 'name' => 'Regular User']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.index', ['role' => 'user']));

        $response->assertStatus(200);
        $response->assertSee('Regular User');
    }

    /** @test */
    public function admin_can_view_user_detail()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.show', $user->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.show');
        $response->assertViewHas('user');
        $response->assertSee($user->name);
        $response->assertSee($user->email);
    }

    /** @test */
    public function user_detail_displays_invitations_list()
    {
        $user = User::factory()->create();
        $invitations = Invitation::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.show', $user->id));

        $response->assertStatus(200);

        foreach ($invitations as $invitation) {
            $response->assertSee($invitation->bride_name);
            $response->assertSee($invitation->groom_name);
        }
    }

    /** @test */
    public function user_detail_displays_statistics()
    {
        $user = User::factory()->create();

        // Create invitations with different statuses
        $publishedInvitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'status' => 'published',
        ]);

        $draftInvitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'status' => 'draft',
        ]);

        // Create views for published invitation
        InvitationView::factory()->count(5)->create([
            'invitation_id' => $publishedInvitation->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.show', $user->id));

        $response->assertStatus(200);
        $response->assertViewHas('totalInvitations', 2);
        $response->assertViewHas('publishedInvitations', 1);
        $response->assertViewHas('draftInvitations', 1);
        $response->assertViewHas('totalViews', 5);
    }

    /** @test */
    public function non_admin_cannot_view_user_detail()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->regularUser)
            ->get(route('admin.users.show', $user->id));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
    }

    /** @test */
    public function admin_can_deactivate_user()
    {
        $user = User::factory()->create(['is_active' => true]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.deactivate', $user->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_active' => false,
        ]);
    }

    /** @test */
    public function admin_cannot_deactivate_admin_users()
    {
        $adminUser = User::factory()->create([
            'is_admin' => true,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.deactivate', $adminUser->id));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Cannot deactivate admin users.');

        $this->assertDatabaseHas('users', [
            'id' => $adminUser->id,
            'is_active' => true,
        ]);
    }

    /** @test */
    public function admin_cannot_deactivate_themselves()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.deactivate', $this->admin->id));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'You cannot deactivate your own account.');

        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
            'is_active' => true,
        ]);
    }

    /** @test */
    public function admin_can_activate_user()
    {
        $user = User::factory()->create(['is_active' => false]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.activate', $user->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_active' => true,
        ]);
    }

    /** @test */
    public function non_admin_cannot_deactivate_user()
    {
        $user = User::factory()->create(['is_active' => true]);

        $response = $this->actingAs($this->regularUser)
            ->post(route('admin.users.deactivate', $user->id));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_active' => true,
        ]);
    }

    /** @test */
    public function non_admin_cannot_activate_user()
    {
        $user = User::factory()->create(['is_active' => false]);

        $response = $this->actingAs($this->regularUser)
            ->post(route('admin.users.activate', $user->id));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_active' => false,
        ]);
    }

    /** @test */
    public function deactivate_returns_404_for_non_existent_user()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.deactivate', 99999));

        $response->assertStatus(404);
    }

    /** @test */
    public function activate_returns_404_for_non_existent_user()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.activate', 99999));

        $response->assertStatus(404);
    }

    /** @test */
    public function show_returns_404_for_non_existent_user()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.show', 99999));

        $response->assertStatus(404);
    }

    /** @test */
    public function user_list_is_paginated()
    {
        // Create more than 15 users to test pagination
        User::factory()->count(20)->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertViewHas('users');

        $users = $response->viewData('users');
        $this->assertEquals(15, $users->perPage());
    }

    /** @test */
    public function user_list_loads_invitations_relationship()
    {
        $user = User::factory()->create();
        Invitation::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.index'));

        $response->assertStatus(200);

        $users = $response->viewData('users');
        $this->assertTrue($users->first()->relationLoaded('invitations'));
    }
}
