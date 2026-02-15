<?php

namespace Tests\Unit;

use App\Http\Middleware\AdminMiddleware;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Tests\TestCase;

class AdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_middleware_redirects_unauthenticated_users(): void
    {
        $middleware = new AdminMiddleware();
        $request = Request::create('/admin/dashboard', 'GET');

        $response = $middleware->handle($request, function () {
            return response('Success');
        });

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('login'), $response->getTargetUrl());
    }

    public function test_middleware_redirects_non_admin_users(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $this->actingAs($user);

        $middleware = new AdminMiddleware();
        $request = Request::create('/admin/dashboard', 'GET');

        $response = $middleware->handle($request, function () {
            return response('Success');
        });

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('dashboard'), $response->getTargetUrl());
        $this->assertEquals('You do not have permission to access this page.', session('error'));
    }

    public function test_middleware_allows_admin_users(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($admin);

        $middleware = new AdminMiddleware();
        $request = Request::create('/admin/dashboard', 'GET');

        $response = $middleware->handle($request, function () {
            return response('Success');
        });

        $this->assertEquals('Success', $response->getContent());
    }
}
