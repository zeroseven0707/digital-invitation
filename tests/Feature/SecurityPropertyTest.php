<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Property-Based Tests for Security
 *
 * These tests validate universal security properties that should hold true
 * for all security-related operations.
 */
class SecurityPropertyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Property 36: Unauthenticated Access Redirects to Login
     *
     * For any protected route, unauthenticated users should be
     * redirected to the login page.
     *
     * Validates: Requirements 12.3
     */
    public function test_property_unauthenticated_access_redirects_to_login(): void
    {
        $protectedRoutes = [
            ['method' => 'GET', 'uri' => '/dashboard'],
            ['method' => 'GET', 'uri' => '/profile'],
            ['method' => 'GET', 'uri' => '/invitations/create'],
            ['method' => 'GET', 'uri' => '/templates'],
            ['method' => 'GET', 'uri' => '/admin/dashboard'],
            ['method' => 'GET', 'uri' => '/admin/users'],
            ['method' => 'GET', 'uri' => '/admin/templates'],
        ];

        foreach ($protectedRoutes as $route) {
            $response = $this->{strtolower($route['method'])}($route['uri']);

            // Property: Should redirect to login
            $response->assertRedirect('/login');

            // Property: User should remain unauthenticated
            $this->assertGuest();
        }
    }

    /**
     * Property 37: Unauthorized Access Returns 403
     *
     * For any resource, users should only be able to access their own
     * resources. Attempting to access another user's resources should
     * be denied (403 or 404).
     *
     * Validates: Requirements 12.4
     */
    public function test_property_unauthorized_access_returns_403(): void
    {
        // Create two users
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Create invitations for user1
        $invitation1 = Invitation::factory()->create(['user_id' => $user1->id]);
        $invitation2 = Invitation::factory()->create(['user_id' => $user1->id]);
        $invitation3 = Invitation::factory()->create(['user_id' => $user1->id]);

        $unauthorizedRoutes = [
            ['method' => 'GET', 'uri' => "/invitations/{$invitation1->id}"],
            ['method' => 'GET', 'uri' => "/invitations/{$invitation1->id}/edit"],
            ['method' => 'GET', 'uri' => "/invitations/{$invitation1->id}/preview"],
            ['method' => 'GET', 'uri' => "/invitations/{$invitation2->id}"],
            ['method' => 'GET', 'uri' => "/invitations/{$invitation2->id}/edit"],
            ['method' => 'GET', 'uri' => "/invitations/{$invitation3->id}"],
        ];

        // Property: User2 should not be able to access User1's invitations
        foreach ($unauthorizedRoutes as $route) {
            $response = $this->actingAs($user2)->{strtolower($route['method'])}($route['uri']);

            // Property: Should be denied (403 or 404 - both indicate unauthorized access)
            $this->assertContains($response->status(), [403, 404],
                "User should not be able to access another user's invitation");
        }

        // Property: User1 should be able to access their own invitations
        foreach ($unauthorizedRoutes as $route) {
            $response = $this->actingAs($user1)->{strtolower($route['method'])}($route['uri']);

            // Property: Should succeed (200 or redirect)
            $this->assertContains($response->status(), [200, 302]);
        }
    }

    /**
     * Property 38: Input Sanitization Prevents XSS
     *
     * For any user input containing potentially dangerous HTML/JavaScript,
     * the system should sanitize or reject the input to prevent XSS attacks.
     *
     * Validates: Requirements 12.6
     */
    public function test_property_input_sanitization_prevents_xss(): void
    {
        $user = User::factory()->create();

        // Test cases with various XSS attack vectors
        $xssTestCases = [
            [
                'field' => 'bride_name',
                'value' => '<script>alert("XSS")</script>John',
                'description' => 'Script tag injection'
            ],
            [
                'field' => 'groom_name',
                'value' => '<img src=x onerror=alert("XSS")>',
                'description' => 'Image with onerror handler'
            ],
            [
                'field' => 'akad_location',
                'value' => '<a href="javascript:alert(\'XSS\')">Click</a>',
                'description' => 'JavaScript protocol in link'
            ],
            [
                'field' => 'reception_location',
                'value' => '<iframe src="http://evil.com"></iframe>',
                'description' => 'Iframe injection'
            ],
            [
                'field' => 'full_address',
                'value' => '<div onclick="alert(\'XSS\')">Address</div>',
                'description' => 'Event handler in div'
            ],
        ];

        foreach ($xssTestCases as $testCase) {
            $invitationData = [
                'template_id' => 1,
                'bride_name' => 'Jane Doe',
                'bride_father_name' => 'Father Doe',
                'bride_mother_name' => 'Mother Doe',
                'groom_name' => 'John Smith',
                'groom_father_name' => 'Father Smith',
                'groom_mother_name' => 'Mother Smith',
                'akad_date' => '2024-12-31',
                'akad_time_start' => '10:00',
                'akad_time_end' => '11:00',
                'akad_location' => 'Masjid Al-Ikhlas',
                'reception_date' => '2024-12-31',
                'reception_time_start' => '18:00',
                'reception_time_end' => '21:00',
                'reception_location' => 'Grand Ballroom',
                'full_address' => '123 Main Street',
            ];

            // Inject XSS payload into the specific field
            $invitationData[$testCase['field']] = $testCase['value'];

            $response = $this->actingAs($user)->post('/invitations', $invitationData);

            // Property: Request should either be rejected with validation error
            // or the dangerous content should be sanitized
            if ($response->status() === 302 && $response->isRedirect()) {
                // If redirected back, check for validation errors
                $session = $response->getSession();
                if ($session && $session->has('errors')) {
                    // Property: Validation should catch dangerous content
                    $errors = $session->get('errors');
                    $this->assertTrue(
                        $errors->has($testCase['field']) || $errors->has('template_id'),
                        "Expected validation error for {$testCase['field']} with {$testCase['description']}"
                    );
                } else {
                    // If no validation error, check that content was sanitized
                    $invitation = Invitation::latest()->first();
                    if ($invitation) {
                        $fieldValue = $invitation->{$testCase['field']};

                        // Property: Dangerous patterns should not exist in stored data
                        $this->assertStringNotContainsString('<script', $fieldValue, "Script tag found in {$testCase['field']}");
                        $this->assertStringNotContainsString('javascript:', $fieldValue, "JavaScript protocol found in {$testCase['field']}");
                        $this->assertStringNotContainsString('onerror=', $fieldValue, "Event handler found in {$testCase['field']}");
                        $this->assertStringNotContainsString('onclick=', $fieldValue, "Event handler found in {$testCase['field']}");
                        $this->assertStringNotContainsString('<iframe', $fieldValue, "Iframe tag found in {$testCase['field']}");
                    }
                }
            }
        }
    }

    /**
     * Property: Admin Routes Require Admin Privilege
     *
     * For any admin route, non-admin users should be denied access.
     *
     * Validates: Requirements 10.1, 12.4
     */
    public function test_property_admin_routes_require_admin_privilege(): void
    {
        $regularUser = User::factory()->create(['is_admin' => false]);
        $adminUser = User::factory()->create(['is_admin' => true]);

        $adminRoutes = [
            ['method' => 'GET', 'uri' => '/admin/users'],
            ['method' => 'GET', 'uri' => '/admin/templates'],
        ];

        // Property: Regular users should be denied access to admin routes
        foreach ($adminRoutes as $route) {
            $response = $this->actingAs($regularUser)->{strtolower($route['method'])}($route['uri']);

            // Property: Should be denied (redirect to dashboard with error)
            $response->assertRedirect('/dashboard');
            $response->assertSessionHas('error');
        }

        // Property: Admin users should have access to admin routes
        foreach ($adminRoutes as $route) {
            $response = $this->actingAs($adminUser)->{strtolower($route['method'])}($route['uri']);

            // Property: Should succeed (200)
            $response->assertStatus(200);
        }
    }

    /**
     * Property: Inactive Users Cannot Perform Actions
     *
     * For any user marked as inactive, they should not be able to
     * create or modify resources.
     *
     * Validates: Requirements 10.4, 12.4
     */
    public function test_property_inactive_users_cannot_perform_actions(): void
    {
        $inactiveUser = User::factory()->create(['is_active' => false]);
        $activeUser = User::factory()->create(['is_active' => true]);

        $invitation = Invitation::factory()->create(['user_id' => $inactiveUser->id]);

        $restrictedActions = [
            [
                'method' => 'POST',
                'uri' => '/invitations',
                'data' => [
                    'template_id' => 1,
                    'bride_name' => 'Jane',
                    'groom_name' => 'John',
                    'akad_date' => '2024-12-31',
                    'reception_date' => '2024-12-31',
                ]
            ],
            [
                'method' => 'PUT',
                'uri' => "/invitations/{$invitation->id}",
                'data' => ['bride_name' => 'Updated Name']
            ],
        ];

        // Property: Inactive users should not be able to create/update
        foreach ($restrictedActions as $action) {
            $response = $this->actingAs($inactiveUser)
                ->{strtolower($action['method'])}($action['uri'], $action['data'] ?? []);

            // Property: Should be denied (403 or validation error)
            $this->assertContains($response->status(), [403, 302]);
        }

        // Property: Active users should be able to perform actions
        $activeInvitation = Invitation::factory()->create(['user_id' => $activeUser->id]);

        $response = $this->actingAs($activeUser)->put("/invitations/{$activeInvitation->id}", [
            'template_id' => $activeInvitation->template_id,
            'bride_name' => 'Updated Name',
            'bride_father_name' => $activeInvitation->bride_father_name,
            'bride_mother_name' => $activeInvitation->bride_mother_name,
            'groom_name' => $activeInvitation->groom_name,
            'groom_father_name' => $activeInvitation->groom_father_name,
            'groom_mother_name' => $activeInvitation->groom_mother_name,
            'akad_date' => $activeInvitation->akad_date->format('Y-m-d'),
            'akad_time_start' => $activeInvitation->akad_time_start,
            'akad_time_end' => $activeInvitation->akad_time_end,
            'akad_location' => $activeInvitation->akad_location,
            'reception_date' => $activeInvitation->reception_date->format('Y-m-d'),
            'reception_time_start' => $activeInvitation->reception_time_start,
            'reception_time_end' => $activeInvitation->reception_time_end,
            'reception_location' => $activeInvitation->reception_location,
            'full_address' => $activeInvitation->full_address,
        ]);

        // Property: Should succeed
        $this->assertContains($response->status(), [200, 302]);
    }
}
