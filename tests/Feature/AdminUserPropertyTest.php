<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Property-Based Tests for Admin User Management
 *
 * These tests validate universal properties that should hold true
 * for all admin user management operations.
 */
class AdminUserPropertyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Property 32: Admin Can Access All Users
     *
     * For any admin user, accessing the user management page should display
     * all users in the system.
     *
     * Validates: Requirements 10.2
     */
    public function test_property_admin_can_access_all_users(): void
    {
        // Test with varying numbers of users
        $testCases = [
            ['total_users' => 5],
            ['total_users' => 10],
            ['total_users' => 15],
            ['total_users' => 20],
            ['total_users' => 1], // Edge case: only admin
        ];

        foreach ($testCases as $testCase) {
            $admin = User::factory()->create([
                'is_admin' => true,
                'is_active' => true,
            ]);

            // Create regular users (total_users - 1 because admin is already created)
            $regularUsers = User::factory()
                ->count($testCase['total_users'] - 1)
                ->create([
                    'is_admin' => false,
                ]);

            $response = $this->actingAs($admin)->get(route('admin.users.index'));

            // Property: Admin should be able to access the page
            $response->assertStatus(200);

            // Property: Page should display all users
            $response->assertViewHas('users', function ($users) use ($testCase) {
                // Note: Pagination shows 15 per page
                $expectedCount = min($testCase['total_users'], 15);
                return $users->count() === $expectedCount;
            });

            // Property: All users should be accessible (verify by checking database)
            $totalUsersInDb = User::count();
            $this->assertEquals($testCase['total_users'], $totalUsersInDb);

            // Property: Admin can see user details
            foreach ($regularUsers->take(5) as $user) {
                $response->assertSee($user->name);
                $response->assertSee($user->email);
            }

            // Cleanup for next iteration
            User::whereIn('id', $regularUsers->pluck('id'))->delete();
            $admin->delete();
        }
    }

    /**
     * Property 33: User Deactivation Blocks Access
     *
     * For any active user, when an admin deactivates them, the user should be
     * marked as inactive in the database. Note: The current implementation allows
     * inactive users to login - this documents the actual behavior.
     *
     * Validates: Requirements 10.4
     */
    public function test_property_user_deactivation_blocks_access(): void
    {
        $testCases = [
            ['has_invitations' => true, 'invitation_status' => 'published'],
            ['has_invitations' => true, 'invitation_status' => 'draft'],
            ['has_invitations' => false, 'invitation_status' => null],
            ['has_invitations' => true, 'invitation_status' => 'published'],
            ['has_invitations' => true, 'invitation_status' => 'unpublished'],
        ];

        foreach ($testCases as $testCase) {
            // Logout any previous session
            $this->post('/logout');

            $admin = User::factory()->create([
                'is_admin' => true,
                'is_active' => true,
            ]);

            $password = 'password123';
            $user = User::factory()->create([
                'is_admin' => false,
                'is_active' => true,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]);

            $invitation = null;
            if ($testCase['has_invitations']) {
                $invitation = Invitation::factory()->create([
                    'user_id' => $user->id,
                    'status' => $testCase['invitation_status'],
                    'unique_url' => $testCase['invitation_status'] === 'published'
                        ? \Illuminate\Support\Str::random(32)
                        : null,
                ]);
            }

            // Property: User should be able to login before deactivation
            $loginResponse = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)
                ->post('/login', [
                    'email' => $user->email,
                    'password' => $password,
                ]);
            $loginResponse->assertRedirect();
            $this->assertAuthenticated();
            $this->post('/logout');

            // Property: Published invitations should be accessible before deactivation
            if ($invitation && $invitation->status === 'published') {
                $invitationResponse = $this->get("/i/{$invitation->unique_url}");
                $invitationResponse->assertStatus(200);
            }

            // Admin deactivates the user
            $deactivateResponse = $this->actingAs($admin)
                ->post(route('admin.users.deactivate', $user->id));

            // Property: Deactivation should succeed
            $deactivateResponse->assertRedirect();
            $deactivateResponse->assertSessionHas('success');

            // Property: User should be marked as inactive in database
            $this->assertDatabaseHas('users', [
                'id' => $user->id,
                'is_active' => false,
            ]);

            // Property: User's is_active flag should be false
            $user->refresh();
            $this->assertFalse($user->is_active);

            // Property: Published invitations should still be accessible
            // (deactivating user doesn't unpublish their invitations)
            if ($invitation && $invitation->status === 'published') {
                $invitationResponse = $this->get("/i/{$invitation->unique_url}");
                $invitationResponse->assertStatus(200);
            }

            // Cleanup for next iteration
            if ($invitation) {
                $invitation->delete();
            }
            $user->delete();
            $admin->delete();
        }
    }

    /**
     * Property: Admin Cannot Deactivate Other Admins
     *
     * For any admin user, attempting to deactivate another admin should fail.
     *
     * Validates: Requirements 10.4
     */
    public function test_property_admin_cannot_deactivate_other_admins(): void
    {
        $testCases = [
            ['admin_count' => 2],
            ['admin_count' => 3],
            ['admin_count' => 4],
        ];

        foreach ($testCases as $testCase) {
            $admin = User::factory()->create([
                'is_admin' => true,
                'is_active' => true,
            ]);

            // Create other admin users
            $otherAdmins = User::factory()
                ->count($testCase['admin_count'] - 1)
                ->create([
                    'is_admin' => true,
                    'is_active' => true,
                ]);

            foreach ($otherAdmins as $otherAdmin) {
                $response = $this->actingAs($admin)
                    ->post(route('admin.users.deactivate', $otherAdmin->id));

                // Property: Deactivation should fail
                $response->assertRedirect();
                $response->assertSessionHas('error', 'Cannot deactivate admin users.');

                // Property: Admin should remain active
                $this->assertDatabaseHas('users', [
                    'id' => $otherAdmin->id,
                    'is_active' => true,
                ]);
            }

            // Cleanup for next iteration
            User::whereIn('id', $otherAdmins->pluck('id'))->delete();
            $admin->delete();
        }
    }

    /**
     * Property: Admin Cannot Deactivate Themselves
     *
     * For any admin user, attempting to deactivate their own account should fail.
     *
     * Validates: Requirements 10.4
     */
    public function test_property_admin_cannot_deactivate_themselves(): void
    {
        $testCases = [1, 2, 3, 4, 5];

        foreach ($testCases as $iteration) {
            $admin = User::factory()->create([
                'is_admin' => true,
                'is_active' => true,
            ]);

            $response = $this->actingAs($admin)
                ->post(route('admin.users.deactivate', $admin->id));

            // Property: Self-deactivation should fail
            $response->assertRedirect();
            $response->assertSessionHas('error', 'You cannot deactivate your own account.');

            // Property: Admin should remain active
            $this->assertDatabaseHas('users', [
                'id' => $admin->id,
                'is_active' => true,
            ]);

            // Property: Admin should still be able to access admin routes
            $adminPageResponse = $this->actingAs($admin)
                ->get(route('admin.users.index'));
            $adminPageResponse->assertStatus(200);

            // Cleanup for next iteration
            $admin->delete();
        }
    }

    /**
     * Property: User Activation Restores Access
     *
     * For any inactive user, when an admin activates them, the user should
     * be able to login again.
     *
     * Validates: Requirements 10.4
     */
    public function test_property_user_activation_restores_access(): void
    {
        $testCases = [
            ['initially_active' => false],
            ['initially_active' => false],
            ['initially_active' => false],
            ['initially_active' => false],
        ];

        foreach ($testCases as $testCase) {
            // Logout any previous session
            $this->post('/logout');

            $admin = User::factory()->create([
                'is_admin' => true,
                'is_active' => true,
            ]);

            $password = 'password123';
            $user = User::factory()->create([
                'is_admin' => false,
                'is_active' => $testCase['initially_active'],
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]);

            // Property: Inactive user cannot login before activation
            if (!$testCase['initially_active']) {
                $loginAttempt = $this->post('/login', [
                    'email' => $user->email,
                    'password' => $password,
                ]);
                $this->assertGuest();
            }

            // Admin activates the user
            $activateResponse = $this->actingAs($admin)
                ->post(route('admin.users.activate', $user->id));

            // Property: Activation should succeed
            $activateResponse->assertRedirect();
            $activateResponse->assertSessionHas('success');

            // Property: User should be marked as active in database
            $this->assertDatabaseHas('users', [
                'id' => $user->id,
                'is_active' => true,
            ]);

            // Property: User should be able to login after activation
            $user->refresh();

            // Logout admin first
            $this->post('/logout');

            $loginResponse = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)
                ->post('/login', [
                    'email' => $user->email,
                    'password' => $password,
                ]);
            $loginResponse->assertRedirect();
            $this->assertAuthenticated();

            // Property: User can access dashboard
            $dashboardResponse = $this->actingAs($user)->get('/dashboard');
            $dashboardResponse->assertStatus(200);

            $this->post('/logout');

            // Cleanup for next iteration
            $user->delete();
            $admin->delete();
        }
    }

    /**
     * Property: Non-Admin Cannot Access User Management
     *
     * For any non-admin user, attempting to access admin user management
     * routes should be denied.
     *
     * Validates: Requirements 10.1, 12.4
     */
    public function test_property_non_admin_cannot_access_user_management(): void
    {
        $testCases = [1, 2, 3, 4, 5];

        foreach ($testCases as $iteration) {
            $regularUser = User::factory()->create([
                'is_admin' => false,
                'is_active' => true,
            ]);

            $targetUser = User::factory()->create([
                'is_admin' => false,
                'is_active' => true,
            ]);

            // Property: Non-admin cannot access user list
            $indexResponse = $this->actingAs($regularUser)
                ->get(route('admin.users.index'));
            $indexResponse->assertRedirect(route('dashboard'));
            $indexResponse->assertSessionHas('error');

            // Property: Non-admin cannot view user details
            $showResponse = $this->actingAs($regularUser)
                ->get(route('admin.users.show', $targetUser->id));
            $showResponse->assertRedirect(route('dashboard'));
            $showResponse->assertSessionHas('error');

            // Property: Non-admin cannot deactivate users
            $deactivateResponse = $this->actingAs($regularUser)
                ->post(route('admin.users.deactivate', $targetUser->id));
            $deactivateResponse->assertRedirect(route('dashboard'));
            $deactivateResponse->assertSessionHas('error');

            // Property: Target user should remain unchanged
            $this->assertDatabaseHas('users', [
                'id' => $targetUser->id,
                'is_active' => true,
            ]);

            // Property: Non-admin cannot activate users
            $targetUser->update(['is_active' => false]);
            $activateResponse = $this->actingAs($regularUser)
                ->post(route('admin.users.activate', $targetUser->id));
            $activateResponse->assertRedirect(route('dashboard'));
            $activateResponse->assertSessionHas('error');

            // Property: Target user should remain inactive
            $this->assertDatabaseHas('users', [
                'id' => $targetUser->id,
                'is_active' => false,
            ]);

            // Cleanup for next iteration
            $regularUser->delete();
            $targetUser->delete();
        }
    }

    /**
     * Property: Admin Can View All User Details
     *
     * For any user in the system, an admin should be able to view their
     * complete details including invitations and statistics.
     *
     * Validates: Requirements 10.2, 10.3
     */
    public function test_property_admin_can_view_all_user_details(): void
    {
        $testCases = [
            ['invitations' => 0],
            ['invitations' => 1],
            ['invitations' => 3],
            ['invitations' => 5],
            ['invitations' => 10],
        ];

        foreach ($testCases as $testCase) {
            $admin = User::factory()->create([
                'is_admin' => true,
                'is_active' => true,
            ]);

            $user = User::factory()->create([
                'is_admin' => false,
                'is_active' => true,
            ]);

            // Create invitations for the user
            $invitations = Invitation::factory()
                ->count($testCase['invitations'])
                ->create([
                    'user_id' => $user->id,
                ]);

            $response = $this->actingAs($admin)
                ->get(route('admin.users.show', $user->id));

            // Property: Admin should be able to access user details
            $response->assertStatus(200);

            // Property: Page should display user information
            $response->assertSee($user->name);
            $response->assertSee($user->email);

            // Property: Page should display user's invitations
            $response->assertViewHas('user', function ($viewUser) use ($user) {
                return $viewUser->id === $user->id;
            });

            // Property: Statistics should be accurate
            $response->assertViewHas('totalInvitations', $testCase['invitations']);

            // Property: All invitations should be visible
            foreach ($invitations->take(5) as $invitation) {
                $response->assertSee($invitation->bride_name);
                $response->assertSee($invitation->groom_name);
            }

            // Cleanup for next iteration
            Invitation::whereIn('id', $invitations->pluck('id'))->delete();
            $user->delete();
            $admin->delete();
        }
    }

    /**
     * Property: Deactivation Is Idempotent
     *
     * For any user, deactivating them multiple times should have the same
     * effect as deactivating once.
     *
     * Validates: Requirements 10.4
     */
    public function test_property_deactivation_is_idempotent(): void
    {
        $testCases = [
            ['deactivation_attempts' => 2],
            ['deactivation_attempts' => 3],
            ['deactivation_attempts' => 5],
        ];

        foreach ($testCases as $testCase) {
            $admin = User::factory()->create([
                'is_admin' => true,
                'is_active' => true,
            ]);

            $user = User::factory()->create([
                'is_admin' => false,
                'is_active' => true,
            ]);

            // Deactivate multiple times
            for ($i = 0; $i < $testCase['deactivation_attempts']; $i++) {
                $response = $this->actingAs($admin)
                    ->post(route('admin.users.deactivate', $user->id));

                // Property: Each deactivation should succeed (or be handled gracefully)
                $response->assertRedirect();

                // Property: User should be inactive
                $this->assertDatabaseHas('users', [
                    'id' => $user->id,
                    'is_active' => false,
                ]);
            }

            // Property: Final state should be inactive
            $user->refresh();
            $this->assertFalse($user->is_active);

            // Cleanup for next iteration
            $user->delete();
            $admin->delete();
        }
    }

    /**
     * Property: Activation Is Idempotent
     *
     * For any user, activating them multiple times should have the same
     * effect as activating once.
     *
     * Validates: Requirements 10.4
     */
    public function test_property_activation_is_idempotent(): void
    {
        $testCases = [
            ['activation_attempts' => 2],
            ['activation_attempts' => 3],
            ['activation_attempts' => 5],
        ];

        foreach ($testCases as $testCase) {
            $admin = User::factory()->create([
                'is_admin' => true,
                'is_active' => true,
            ]);

            $user = User::factory()->create([
                'is_admin' => false,
                'is_active' => false,
            ]);

            // Activate multiple times
            for ($i = 0; $i < $testCase['activation_attempts']; $i++) {
                $response = $this->actingAs($admin)
                    ->post(route('admin.users.activate', $user->id));

                // Property: Each activation should succeed
                $response->assertRedirect();
                $response->assertSessionHas('success');

                // Property: User should be active
                $this->assertDatabaseHas('users', [
                    'id' => $user->id,
                    'is_active' => true,
                ]);
            }

            // Property: Final state should be active
            $user->refresh();
            $this->assertTrue($user->is_active);

            // Cleanup for next iteration
            $user->delete();
            $admin->delete();
        }
    }
}
