<?php

namespace Tests\Unit;

use App\Models\Invitation;
use App\Models\InvitationView;
use App\Services\InvitationViewTracker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class InvitationViewTrackerTest extends TestCase
{
    use RefreshDatabase;

    protected InvitationViewTracker $tracker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tracker = new InvitationViewTracker();
    }

    public function test_tracks_view_with_valid_data(): void
    {
        $invitation = Invitation::factory()->create();

        $request = Request::create('/', 'GET', [], [], [], [
            'REMOTE_ADDR' => '192.168.1.1',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
        ]);

        $this->tracker->trackView($invitation, $request);

        $this->assertDatabaseHas('invitation_views', [
            'invitation_id' => $invitation->id,
            'ip_address' => '192.168.1.1',
        ]);
    }

    public function test_detects_desktop_device_type(): void
    {
        $invitation = Invitation::factory()->create();

        $request = Request::create('/', 'GET', [], [], [], [
            'REMOTE_ADDR' => '192.168.1.1',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
        ]);

        $this->tracker->trackView($invitation, $request);

        $view = InvitationView::where('invitation_id', $invitation->id)->first();
        $this->assertEquals('desktop', $view->device_type);
    }

    public function test_detects_mobile_device_type(): void
    {
        $invitation = Invitation::factory()->create();

        $request = Request::create('/', 'GET', [], [], [], [
            'REMOTE_ADDR' => '192.168.1.2',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1',
        ]);

        $this->tracker->trackView($invitation, $request);

        $view = InvitationView::where('invitation_id', $invitation->id)->first();
        $this->assertEquals('mobile', $view->device_type);
    }

    public function test_detects_tablet_device_type(): void
    {
        $invitation = Invitation::factory()->create();

        $request = Request::create('/', 'GET', [], [], [], [
            'REMOTE_ADDR' => '192.168.1.3',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (iPad; CPU OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1',
        ]);

        $this->tracker->trackView($invitation, $request);

        $view = InvitationView::where('invitation_id', $invitation->id)->first();
        $this->assertEquals('tablet', $view->device_type);
    }

    public function test_detects_chrome_browser(): void
    {
        $invitation = Invitation::factory()->create();

        $request = Request::create('/', 'GET', [], [], [], [
            'REMOTE_ADDR' => '192.168.1.1',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
        ]);

        $this->tracker->trackView($invitation, $request);

        $view = InvitationView::where('invitation_id', $invitation->id)->first();
        $this->assertEquals('Chrome', $view->browser);
    }

    public function test_detects_safari_browser(): void
    {
        $invitation = Invitation::factory()->create();

        $request = Request::create('/', 'GET', [], [], [], [
            'REMOTE_ADDR' => '192.168.1.2',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Safari/605.1.15',
        ]);

        $this->tracker->trackView($invitation, $request);

        $view = InvitationView::where('invitation_id', $invitation->id)->first();
        $this->assertEquals('Safari', $view->browser);
    }

    public function test_prevents_duplicate_views_from_same_ip_within_24_hours(): void
    {
        $invitation = Invitation::factory()->create();

        $request = Request::create('/', 'GET', [], [], [], [
            'REMOTE_ADDR' => '192.168.1.1',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ]);

        // First view
        $this->tracker->trackView($invitation, $request);
        $this->assertEquals(1, InvitationView::where('invitation_id', $invitation->id)->count());

        // Second view from same IP within 24 hours - should not be tracked
        $this->tracker->trackView($invitation, $request);
        $this->assertEquals(1, InvitationView::where('invitation_id', $invitation->id)->count());
    }

    public function test_allows_view_from_same_ip_after_24_hours(): void
    {
        $invitation = Invitation::factory()->create();

        $request = Request::create('/', 'GET', [], [], [], [
            'REMOTE_ADDR' => '192.168.1.1',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ]);

        // First view
        $this->tracker->trackView($invitation, $request);
        $this->assertEquals(1, InvitationView::where('invitation_id', $invitation->id)->count());

        // Simulate 25 hours passing by updating the viewed_at timestamp
        $view = InvitationView::where('invitation_id', $invitation->id)->first();
        $view->viewed_at = now()->subHours(25);
        $view->save();

        // Second view from same IP after 24 hours - should be tracked
        $this->tracker->trackView($invitation, $request);
        $this->assertEquals(2, InvitationView::where('invitation_id', $invitation->id)->count());
    }

    public function test_allows_views_from_different_ips(): void
    {
        $invitation = Invitation::factory()->create();

        $request1 = Request::create('/', 'GET', [], [], [], [
            'REMOTE_ADDR' => '192.168.1.1',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ]);

        $request2 = Request::create('/', 'GET', [], [], [], [
            'REMOTE_ADDR' => '192.168.1.2',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ]);

        $this->tracker->trackView($invitation, $request1);
        $this->tracker->trackView($invitation, $request2);

        $this->assertEquals(2, InvitationView::where('invitation_id', $invitation->id)->count());
    }

    public function test_get_view_count_returns_correct_count(): void
    {
        $invitation = Invitation::factory()->create();

        InvitationView::factory()->count(5)->create([
            'invitation_id' => $invitation->id,
        ]);

        $count = $this->tracker->getViewCount($invitation);
        $this->assertEquals(5, $count);
    }

    public function test_get_views_by_date_returns_correct_data(): void
    {
        $invitation = Invitation::factory()->create();

        // Create views on different dates
        InvitationView::factory()->create([
            'invitation_id' => $invitation->id,
            'viewed_at' => now()->subDays(2),
        ]);

        InvitationView::factory()->count(2)->create([
            'invitation_id' => $invitation->id,
            'viewed_at' => now()->subDays(1),
        ]);

        InvitationView::factory()->count(3)->create([
            'invitation_id' => $invitation->id,
            'viewed_at' => now(),
        ]);

        $viewsByDate = $this->tracker->getViewsByDate($invitation, 30);

        $this->assertIsArray($viewsByDate);
        $this->assertCount(3, $viewsByDate);
    }

    public function test_stores_user_agent_string(): void
    {
        $invitation = Invitation::factory()->create();
        $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36';

        $request = Request::create('/', 'GET', [], [], [], [
            'REMOTE_ADDR' => '192.168.1.1',
            'HTTP_USER_AGENT' => $userAgent,
        ]);

        $this->tracker->trackView($invitation, $request);

        $this->assertDatabaseHas('invitation_views', [
            'invitation_id' => $invitation->id,
            'user_agent' => $userAgent,
        ]);
    }

    public function test_stores_viewed_at_timestamp(): void
    {
        $invitation = Invitation::factory()->create();

        $request = Request::create('/', 'GET', [], [], [], [
            'REMOTE_ADDR' => '192.168.1.1',
            'HTTP_USER_AGENT' => 'Mozilla/5.0',
        ]);

        $beforeTracking = now()->subSecond();
        $this->tracker->trackView($invitation, $request);
        $afterTracking = now()->addSecond();

        $view = InvitationView::where('invitation_id', $invitation->id)->first();
        $this->assertNotNull($view->viewed_at);
        $this->assertTrue($view->viewed_at->between($beforeTracking, $afterTracking));
    }
}
