<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_user_redirected_to_dashboard_with_error(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error', 'You do not have permission to access this page.');
    }

    public function test_admin_user_can_access_admin_routes(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        // Create a simple test view for admin dashboard
        $viewPath = resource_path('views/admin');
        if (!file_exists($viewPath)) {
            mkdir($viewPath, 0755, true);
        }
        file_put_contents(resource_path('views/admin/dashboard.blade.php'), '<h1>Admin Dashboard</h1>');

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Admin Dashboard');

        // Cleanup
        unlink(resource_path('views/admin/dashboard.blade.php'));
        if (is_dir($viewPath) && count(scandir($viewPath)) == 2) {
            rmdir($viewPath);
        }
    }

    public function test_admin_middleware_checks_is_admin_flag(): void
    {
        // Create a simple test view for admin dashboard
        $viewPath = resource_path('views/admin');
        if (!file_exists($viewPath)) {
            mkdir($viewPath, 0755, true);
        }
        file_put_contents(resource_path('views/admin/dashboard.blade.php'), '<h1>Admin Dashboard</h1>');

        // Test with is_admin = false
        $regularUser = User::factory()->create([
            'is_admin' => false,
        ]);

        $response = $this->actingAs($regularUser)->get('/admin/dashboard');
        $response->assertRedirect(route('dashboard'));

        // Test with is_admin = true
        $adminUser = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($adminUser)->get('/admin/dashboard');
        $response->assertStatus(200);

        // Cleanup
        unlink(resource_path('views/admin/dashboard.blade.php'));
        if (is_dir($viewPath) && count(scandir($viewPath)) == 2) {
            rmdir($viewPath);
        }
    }
}
