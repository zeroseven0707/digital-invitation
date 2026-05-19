<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Register a dynamic test admin route to avoid touching physical view files
        Route::middleware(['web', 'auth', 'admin'])->group(function () {
            Route::get('/admin/test-middleware-route', function () {
                return 'Admin Dashboard';
            })->name('admin.test-middleware-route');
        });
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get('/admin/test-middleware-route');

        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_user_redirected_to_dashboard_with_error(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $response = $this->actingAs($user)->get('/admin/test-middleware-route');

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error', 'You do not have permission to access this page.');
    }

    public function test_admin_user_can_access_admin_routes(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin/test-middleware-route');

        $response->assertStatus(200);
        $response->assertSee('Admin Dashboard');
    }

    public function test_admin_middleware_checks_is_admin_flag(): void
    {
        // Test with is_admin = false
        $regularUser = User::factory()->create([
            'is_admin' => false,
        ]);

        $response = $this->actingAs($regularUser)->get('/admin/test-middleware-route');
        $response->assertRedirect(route('dashboard'));

        // Test with is_admin = true
        $adminUser = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($adminUser)->get('/admin/test-middleware-route');
        $response->assertStatus(200);
        $response->assertSee('Admin Dashboard');
    }
}
