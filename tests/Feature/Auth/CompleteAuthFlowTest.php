<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class CompleteAuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_registration_and_verification_flow(): void
    {
        Notification::fake();

        // Step 1: Register a new user
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // User should be authenticated and redirected to dashboard
        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));

        // Step 2: Verify user was created with correct attributes
        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('John Doe', $user->name);
        $this->assertTrue($user->is_active);
        $this->assertFalse($user->is_admin);
        $this->assertNull($user->email_verified_at);

        // Step 3: Verify email notification was sent
        Notification::assertSentTo($user, VerifyEmail::class);

        // Step 4: Try to access dashboard without verification
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertRedirect('/verify-email');

        // Step 5: Verify email
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);
        $response->assertRedirect();
        $this->assertTrue($response->isRedirect(route('dashboard')));

        // Step 6: Verify user can now access dashboard
        $user->refresh();
        $this->assertNotNull($user->email_verified_at);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard');
    }

    public function test_user_can_resend_verification_email(): void
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)
            ->post('/email/verification-notification');

        $response->assertRedirect();
        $response->assertSessionHas('status', 'verification-link-sent');

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_verified_user_cannot_access_verification_notice(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/verify-email');

        // Should redirect to dashboard since already verified
        $response->assertRedirect(route('dashboard'));
    }
}
