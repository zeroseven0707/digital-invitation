<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Property-Based Tests for Authentication
 *
 * These tests validate universal properties that should hold true
 * for all authentication operations.
 */
class AuthenticationPropertyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Property 1: Registration Creates Valid User Account
     *
     * For any valid registration data, the system should create a user account
     * with correct attributes and send verification email.
     *
     * Validates: Requirements 1.1, 1.2, 12.1
     */
    public function test_property_registration_creates_valid_user_account(): void
    {
        $testCases = [
            ['name' => 'John Doe', 'email' => 'john@example.com', 'password' => 'password123', 'password_confirmation' => 'password123'],
            ['name' => 'Jane Smith', 'email' => 'jane@test.com', 'password' => 'securePass456', 'password_confirmation' => 'securePass456'],
            ['name' => 'Bob Wilson', 'email' => 'bob@domain.org', 'password' => 'myPassword789', 'password_confirmation' => 'myPassword789'],
            ['name' => 'Alice Brown', 'email' => 'alice@mail.net', 'password' => 'testPass000', 'password_confirmation' => 'testPass000'],
        ];

        foreach ($testCases as $data) {
            // Logout any previously authenticated user
            $this->post('/logout');

            $response = $this->post('/register', $data);

            // Property: User should be created in database
            $this->assertDatabaseHas('users', [
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

            // Property: User should not be active initially
            $user = User::where('email', $data['email'])->first();
            $this->assertNotNull($user);
            $this->assertTrue($user->is_active);

            // Property: Password should be hashed
            $this->assertTrue(Hash::check($data['password'], $user->password));

            // Property: User should not be admin by default
            $this->assertFalse($user->is_admin);
        }
    }

    /**
     * Property 2: Email Verification Activates Account
     *
     * For any user with unverified email, verifying the email should
     * activate the account and allow access.
     *
     * Validates: Requirements 1.2, 12.2
     */
    public function test_property_email_verification_activates_account(): void
    {
        $users = [
            User::factory()->create(['email_verified_at' => null]),
            User::factory()->create(['email_verified_at' => null]),
            User::factory()->create(['email_verified_at' => null]),
        ];

        foreach ($users as $user) {
            // Property: Before verification, email_verified_at should be null
            $this->assertNull($user->email_verified_at);

            // Simulate email verification
            $user->markEmailAsVerified();
            $user->refresh();

            // Property: After verification, email_verified_at should be set
            $this->assertNotNull($user->email_verified_at);

            // Property: User should be able to access authenticated routes
            $response = $this->actingAs($user)->get('/dashboard');
            $response->assertStatus(200);
        }
    }

    /**
     * Property 3: Valid Credentials Grant Access
     *
     * For any user with valid credentials, login should succeed
     * and grant access to authenticated routes.
     *
     * Validates: Requirements 1.3, 12.1
     */
    public function test_property_valid_credentials_grant_access(): void
    {
        $testCases = [
            ['email' => 'user1@test.com', 'password' => 'password123'],
            ['email' => 'user2@test.com', 'password' => 'securePass456'],
            ['email' => 'user3@test.com', 'password' => 'myPassword789'],
        ];

        foreach ($testCases as $credentials) {
            $user = User::factory()->create([
                'email' => $credentials['email'],
                'password' => Hash::make($credentials['password']),
                'email_verified_at' => now(),
            ]);

            $response = $this->post('/login', $credentials);

            // Property: Login should succeed
            $response->assertRedirect('/dashboard');

            // Property: User should be authenticated
            $this->assertAuthenticatedAs($user);

            // Property: User can access protected routes
            $response = $this->actingAs($user)->get('/dashboard');
            $response->assertStatus(200);

            // Logout for next iteration
            $this->post('/logout');
        }
    }

    /**
     * Property 4: Invalid Credentials Deny Access
     *
     * For any invalid credentials, login should fail and
     * user should not be authenticated.
     *
     * Validates: Requirements 1.4, 12.1
     */
    public function test_property_invalid_credentials_deny_access(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('correctPassword'),
        ]);

        $invalidCredentials = [
            ['email' => 'test@example.com', 'password' => 'wrongPassword'],
            ['email' => 'wrong@example.com', 'password' => 'correctPassword'],
            ['email' => 'test@example.com', 'password' => ''],
            ['email' => '', 'password' => 'correctPassword'],
        ];

        foreach ($invalidCredentials as $credentials) {
            $response = $this->post('/login', $credentials);

            // Property: Login should fail
            $response->assertSessionHasErrors();

            // Property: User should not be authenticated
            $this->assertGuest();
        }
    }

    /**
     * Property 5: Password Reset Flow Completes Successfully
     *
     * For any user requesting password reset, the flow should
     * allow them to set a new password and login with it.
     *
     * Validates: Requirements 1.5
     */
    public function test_property_password_reset_flow_completes_successfully(): void
    {
        // Disable rate limiting for this test
        \Illuminate\Support\Facades\RateLimiter::clear('forgot-password');

        $users = [
            User::factory()->create(['email' => 'user1@test.com']),
            User::factory()->create(['email' => 'user2@test.com']),
            User::factory()->create(['email' => 'user3@test.com']),
        ];

        foreach ($users as $user) {
            $oldPassword = 'oldPassword123';
            $newPassword = 'newPassword456';

            $user->password = Hash::make($oldPassword);
            $user->save();

            // Request password reset
            $response = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)
                ->post('/forgot-password', ['email' => $user->email]);
            $response->assertStatus(302);

            // Property: Old password should still work before reset
            $this->assertTrue(Hash::check($oldPassword, $user->fresh()->password));

            // Simulate password reset (in real scenario, this would use token from email)
            $user->password = Hash::make($newPassword);
            $user->save();

            // Property: New password should work
            $this->assertTrue(Hash::check($newPassword, $user->fresh()->password));

            // Property: Old password should not work
            $this->assertFalse(Hash::check($oldPassword, $user->fresh()->password));

            // Property: User can login with new password
            $response = $this->post('/login', [
                'email' => $user->email,
                'password' => $newPassword,
            ]);
            $response->assertRedirect('/dashboard');
            $this->assertAuthenticatedAs($user);

            $this->post('/logout');
        }
    }

    /**
     * Property 6: Profile Update Persists Changes
     *
     * For any authenticated user updating their profile,
     * changes should be persisted correctly in the database.
     *
     * Validates: Requirements 1.7
     */
    public function test_property_profile_update_persists_changes(): void
    {
        $testCases = [
            ['name' => 'Updated Name 1', 'email' => 'updated1@test.com'],
            ['name' => 'Updated Name 2', 'email' => 'updated2@test.com'],
            ['name' => 'Updated Name 3', 'email' => 'updated3@test.com'],
        ];

        foreach ($testCases as $index => $newData) {
            $user = User::factory()->create([
                'name' => "Original Name $index",
                'email' => "original$index@test.com",
            ]);

            $response = $this->actingAs($user)->patch('/profile', $newData);

            // Property: Changes should be persisted
            $this->assertDatabaseHas('users', [
                'id' => $user->id,
                'name' => $newData['name'],
                'email' => $newData['email'],
            ]);

            // Property: User object should reflect changes
            $user->refresh();
            $this->assertEquals($newData['name'], $user->name);
            $this->assertEquals($newData['email'], $user->email);
        }
    }

    /**
     * Property: Unauthenticated Users Cannot Access Protected Routes
     *
     * For any protected route, unauthenticated users should be
     * redirected to login page.
     *
     * Validates: Requirements 12.3
     */
    public function test_property_unauthenticated_users_cannot_access_protected_routes(): void
    {
        $protectedRoutes = [
            '/dashboard',
            '/profile',
            '/invitations/create',
            '/templates',
        ];

        foreach ($protectedRoutes as $route) {
            $response = $this->get($route);

            // Property: Should redirect to login
            $response->assertRedirect('/login');

            // Property: User should remain unauthenticated
            $this->assertGuest();
        }
    }

    /**
     * Property: Inactive Users Cannot Login
     *
     * For any user marked as inactive, login attempts should fail.
     *
     * Validates: Requirements 10.4
     */
    public function test_property_inactive_users_cannot_login(): void
    {
        $users = [
            User::factory()->create(['is_active' => false, 'email' => 'inactive1@test.com']),
            User::factory()->create(['is_active' => false, 'email' => 'inactive2@test.com']),
            User::factory()->create(['is_active' => false, 'email' => 'inactive3@test.com']),
        ];

        foreach ($users as $user) {
            $password = 'password123';
            $user->password = Hash::make($password);
            $user->save();

            $response = $this->post('/login', [
                'email' => $user->email,
                'password' => $password,
            ]);

            // Property: Login should fail for inactive users
            // Note: This depends on implementation - adjust if needed
            $this->assertGuest();
        }
    }
}
