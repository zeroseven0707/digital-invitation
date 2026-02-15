<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\InvitationView;
use App\Models\Template;
use App\Models\User;
use App\Services\InvitationViewTracker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * Property-Based Tests for View Tracking
 *
 * These tests validate universal properties that should hold true
 * for all view tracking operations.
 */
class ViewTrackingPropertyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\TemplateSeeder::class);
    }

    /**
     * Property 28: View Tracking Records Access
     *
     * For any published invitation accessed via unique URL, a view record
     * should be created with timestamp, IP address, and user agent information.
     *
     * **Validates: Requirements 8.1**
     *
     * @test
     */
    public function property_view_tracking_records_access(): void
    {
        $tracker = app(InvitationViewTracker::class);

        // Test with multiple different scenarios
        $testCases = [
            [
                'ip' => '192.168.1.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                'expected_device' => 'desktop',
                'expected_browser' => 'Chrome',
            ],
            [
                'ip' => '10.0.0.5',
                'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1',
                'expected_device' => 'mobile',
                'expected_browser' => 'Safari',
            ],
            [
                'ip' => '172.16.0.10',
                'user_agent' => 'Mozilla/5.0 (iPad; CPU OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1',
                'expected_device' => 'tablet',
                'expected_browser' => 'Safari',
            ],
            [
                'ip' => '203.0.113.42',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0',
                'expected_device' => 'desktop',
                'expected_browser' => 'Firefox',
            ],
            [
                'ip' => '198.51.100.23',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36',
                'expected_device' => 'desktop',
                'expected_browser' => 'Chrome',
            ],
        ];

        foreach ($testCases as $index => $testCase) {
            // Create a published invitation for each test case
            $user = User::factory()->create();
            $template = Template::first();
            $invitation = Invitation::factory()->published()->create([
                'user_id' => $user->id,
                'template_id' => $template->id,
            ]);

            // Record the time before tracking
            $beforeTracking = now()->subSecond();

            // Create a mock request with specific IP and user agent
            $request = Request::create("/i/{$invitation->unique_url}", 'GET');
            $request->server->set('REMOTE_ADDR', $testCase['ip']);
            $request->headers->set('User-Agent', $testCase['user_agent']);

            // Track the view
            $tracker->trackView($invitation, $request);

            // Record the time after tracking
            $afterTracking = now()->addSecond();

            // Property: A view record should be created
            $this->assertDatabaseHas('invitation_views', [
                'invitation_id' => $invitation->id,
                'ip_address' => $testCase['ip'],
            ]);

            // Property: View record should contain user agent information
            $view = InvitationView::where('invitation_id', $invitation->id)
                ->where('ip_address', $testCase['ip'])
                ->first();

            $this->assertNotNull($view, "View record should exist for test case $index");
            $this->assertEquals($testCase['user_agent'], $view->user_agent);

            // Property: Device type should be detected correctly
            $this->assertEquals($testCase['expected_device'], $view->device_type,
                "Device type should be {$testCase['expected_device']} for test case $index");

            // Property: Browser should be detected correctly
            $this->assertEquals($testCase['expected_browser'], $view->browser,
                "Browser should be {$testCase['expected_browser']} for test case $index");

            // Property: Timestamp should be recorded within the tracking window
            $this->assertNotNull($view->viewed_at);
            $this->assertTrue(
                $view->viewed_at->between($beforeTracking, $afterTracking),
                "Timestamp should be between $beforeTracking and $afterTracking for test case $index"
            );

            // Property: All required fields should be populated
            $this->assertNotNull($view->invitation_id);
            $this->assertNotNull($view->ip_address);
            $this->assertNotNull($view->user_agent);
            $this->assertNotNull($view->device_type);
            $this->assertNotNull($view->browser);
            $this->assertNotNull($view->viewed_at);
        }
    }

    /**
     * Property: View Tracking via HTTP Request Records Access
     *
     * For any published invitation accessed via HTTP GET request to its unique URL,
     * a view record should be created automatically.
     *
     * **Validates: Requirements 8.1**
     *
     * @test
     */
    public function property_http_request_triggers_view_tracking(): void
    {
        $testCases = [
            ['ip' => '192.168.1.100', 'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/91.0'],
            ['ip' => '10.0.0.50', 'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_6) Safari/604.1'],
            ['ip' => '172.16.0.200', 'user_agent' => 'Mozilla/5.0 (Linux; Android 11) Chrome/91.0'],
        ];

        foreach ($testCases as $index => $testCase) {
            $user = User::factory()->create();
            $template = Template::first();
            $invitation = Invitation::factory()->published()->create([
                'user_id' => $user->id,
                'template_id' => $template->id,
            ]);

            // Property: Before accessing, no view records should exist
            $this->assertEquals(0, InvitationView::where('invitation_id', $invitation->id)->count());

            // Access the invitation via HTTP request
            $response = $this->withServerVariables([
                'REMOTE_ADDR' => $testCase['ip'],
                'HTTP_USER_AGENT' => $testCase['user_agent'],
            ])->get("/i/{$invitation->unique_url}");

            // Property: Request should succeed
            $response->assertStatus(200);

            // Property: A view record should be created
            $this->assertEquals(1, InvitationView::where('invitation_id', $invitation->id)->count(),
                "Exactly one view record should be created for test case $index");

            // Property: View record should have correct data
            $view = InvitationView::where('invitation_id', $invitation->id)->first();
            $this->assertEquals($testCase['ip'], $view->ip_address);
            $this->assertEquals($testCase['user_agent'], $view->user_agent);
        }
    }

    /**
     * Property: Duplicate Views from Same IP Within 24 Hours Are Not Tracked
     *
     * For any invitation, multiple accesses from the same IP within 24 hours
     * should only create one view record.
     *
     * **Validates: Requirements 8.1**
     *
     * @test
     */
    public function property_duplicate_views_within_24_hours_not_tracked(): void
    {
        $tracker = app(InvitationViewTracker::class);

        $testCases = [
            ['ip' => '192.168.1.1', 'attempts' => 3],
            ['ip' => '10.0.0.5', 'attempts' => 5],
            ['ip' => '172.16.0.10', 'attempts' => 2],
        ];

        foreach ($testCases as $index => $testCase) {
            $user = User::factory()->create();
            $template = Template::first();
            $invitation = Invitation::factory()->published()->create([
                'user_id' => $user->id,
                'template_id' => $template->id,
            ]);

            $request = Request::create("/i/{$invitation->unique_url}", 'GET');
            $request->server->set('REMOTE_ADDR', $testCase['ip']);
            $request->headers->set('User-Agent', 'Mozilla/5.0 Test Browser');

            // Track multiple views from the same IP
            for ($i = 0; $i < $testCase['attempts']; $i++) {
                $tracker->trackView($invitation, $request);
            }

            // Property: Only one view record should exist
            $viewCount = InvitationView::where('invitation_id', $invitation->id)
                ->where('ip_address', $testCase['ip'])
                ->count();

            $this->assertEquals(1, $viewCount,
                "Only one view should be recorded despite {$testCase['attempts']} attempts for test case $index");
        }
    }

    /**
     * Property: Views from Different IPs Are All Tracked
     *
     * For any invitation, accesses from different IP addresses should
     * each create separate view records.
     *
     * **Validates: Requirements 8.1**
     *
     * @test
     */
    public function property_different_ips_create_separate_views(): void
    {
        $tracker = app(InvitationViewTracker::class);

        $user = User::factory()->create();
        $template = Template::first();
        $invitation = Invitation::factory()->published()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
        ]);

        $ipAddresses = [
            '192.168.1.1',
            '192.168.1.2',
            '10.0.0.5',
            '172.16.0.10',
            '203.0.113.42',
        ];

        foreach ($ipAddresses as $ip) {
            $request = Request::create("/i/{$invitation->unique_url}", 'GET');
            $request->server->set('REMOTE_ADDR', $ip);
            $request->headers->set('User-Agent', 'Mozilla/5.0 Test Browser');

            $tracker->trackView($invitation, $request);
        }

        // Property: Each IP should have its own view record
        $totalViews = InvitationView::where('invitation_id', $invitation->id)->count();
        $this->assertEquals(count($ipAddresses), $totalViews,
            "Each unique IP should create a separate view record");

        // Property: Each IP should be recorded exactly once
        foreach ($ipAddresses as $ip) {
            $ipViewCount = InvitationView::where('invitation_id', $invitation->id)
                ->where('ip_address', $ip)
                ->count();
            $this->assertEquals(1, $ipViewCount, "IP $ip should have exactly one view record");
        }
    }

    /**
     * Property: Unpublished Invitations Do Not Track Views
     *
     * For any unpublished invitation, accessing it should not create
     * view records (because it returns 404).
     *
     * **Validates: Requirements 8.1, 7.2**
     *
     * @test
     */
    public function property_unpublished_invitations_do_not_track_views(): void
    {
        $statuses = ['draft', 'unpublished'];

        foreach ($statuses as $status) {
            $user = User::factory()->create();
            $template = Template::first();
            $invitation = Invitation::factory()->create([
                'user_id' => $user->id,
                'template_id' => $template->id,
                'status' => $status,
                'unique_url' => \Illuminate\Support\Str::random(32),
            ]);

            // Property: Before accessing, no views should exist
            $this->assertEquals(0, InvitationView::where('invitation_id', $invitation->id)->count());

            // Try to access the unpublished invitation
            $response = $this->withServerVariables([
                'REMOTE_ADDR' => '192.168.1.1',
                'HTTP_USER_AGENT' => 'Mozilla/5.0 Test Browser',
            ])->get("/i/{$invitation->unique_url}");

            // Property: Request should return 404
            $response->assertStatus(404);

            // Property: No view record should be created
            $this->assertEquals(0, InvitationView::where('invitation_id', $invitation->id)->count(),
                "No view should be tracked for $status invitation");
        }
    }
}
