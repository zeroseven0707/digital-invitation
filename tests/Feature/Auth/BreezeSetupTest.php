<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test suite to verify Laravel Breeze is properly installed and configured
 * with email verification enabled.
 *
 * Task 3.1: Setup Laravel Breeze untuk authentication scaffolding
 * Requirements: 1.1, 1.2
 */
class BreezeSetupTest extends TestCase
{
    use RefreshDatabase;

    public function test_breeze_authentication_routes_are_registered(): void
    {
        // Test that all Breeze routes are accessible
        $routes = [
            '/register',
            '/login',
            '/forgot-password',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }
    }

    public function test_user_model_implements_must_verify_email(): void
    {
        $user = new User();

        $this->assertInstanceOf(
            \Illuminate\Contracts\Auth\MustVerifyEmail::class,
            $user,
            'User model must implement MustVerifyEmail interface'
        );
    }

    public function test_verified_middleware_is_applied_to_dashboard(): void
    {
        $unverifiedUser = User::factory()->unverified()->create();

        $response = $this->actingAs($unverifiedUser)->get('/dashboard');

        // Should redirect to email verification notice
        $response->assertRedirect('/verify-email');
    }

    public function test_new_users_have_correct_default_values(): void
    {
        $user = User::factory()->create();

        $this->assertTrue($user->is_active, 'New users should be active by default');
        $this->assertFalse($user->is_admin, 'New users should not be admin by default');
    }

    public function test_user_factory_supports_all_states(): void
    {
        // Test unverified state
        $unverifiedUser = User::factory()->unverified()->create();
        $this->assertNull($unverifiedUser->email_verified_at);

        // Test admin state
        $adminUser = User::factory()->admin()->create();
        $this->assertTrue($adminUser->is_admin);

        // Test inactive state
        $inactiveUser = User::factory()->inactive()->create();
        $this->assertFalse($inactiveUser->is_active);

        // Test combined states
        $unverifiedAdmin = User::factory()->unverified()->admin()->create();
        $this->assertNull($unverifiedAdmin->email_verified_at);
        $this->assertTrue($unverifiedAdmin->is_admin);
    }

    public function test_password_is_hashed_on_registration(): void
    {
        $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'test@example.com')->first();

        // Password should be hashed, not plain text
        $this->assertNotEquals('password123', $user->password);
        $this->assertTrue(\Hash::check('password123', $user->password));
    }

    public function test_email_verification_views_exist(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/verify-email');

        $response->assertStatus(200);
        $response->assertViewIs('auth.verify-email');
        $response->assertSee('verify your email address');
    }

    public function test_profile_management_is_available(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
    }

    public function test_password_reset_flow_is_available(): void
    {
        $user = User::factory()->create();

        // Request password reset
        $response = $this->post('/forgot-password', [
            'email' => $user->email,
        ]);

        $response->assertSessionHas('status');
    }
}
