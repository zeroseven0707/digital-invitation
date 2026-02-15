<?php

namespace Tests\Unit;

use App\Models\Invitation;
use App\Models\InvitationView;
use App\Models\User;
use App\Services\StatisticsService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatisticsServiceTest extends TestCase
{
    use RefreshDatabase;

    private StatisticsService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new StatisticsService();
    }

    public function test_get_total_views_returns_correct_count()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Create 5 views
        InvitationView::factory()->count(5)->create(['invitation_id' => $invitation->id]);

        $totalViews = $this->service->getTotalViews($invitation);

        $this->assertEquals(5, $totalViews);
    }

    public function test_get_total_views_returns_zero_for_no_views()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        $totalViews = $this->service->getTotalViews($invitation);

        $this->assertEquals(0, $totalViews);
    }

    public function test_get_views_by_date_range_groups_by_date()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Create views on different dates
        InvitationView::factory()->create([
            'invitation_id' => $invitation->id,
            'viewed_at' => Carbon::parse('2024-01-15 10:00:00'),
        ]);
        InvitationView::factory()->create([
            'invitation_id' => $invitation->id,
            'viewed_at' => Carbon::parse('2024-01-15 14:00:00'),
        ]);
        InvitationView::factory()->create([
            'invitation_id' => $invitation->id,
            'viewed_at' => Carbon::parse('2024-01-16 10:00:00'),
        ]);

        $start = Carbon::parse('2024-01-15 00:00:00');
        $end = Carbon::parse('2024-01-16 23:59:59');

        $views = $this->service->getViewsByDateRange($invitation, $start, $end);

        $this->assertCount(2, $views);
        $this->assertEquals('2024-01-15', $views[0]->date);
        $this->assertEquals(2, $views[0]->count);
        $this->assertEquals('2024-01-16', $views[1]->date);
        $this->assertEquals(1, $views[1]->count);
    }

    public function test_get_views_by_date_range_filters_by_date()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Create views outside and inside range
        InvitationView::factory()->create([
            'invitation_id' => $invitation->id,
            'viewed_at' => Carbon::parse('2024-01-10 10:00:00'),
        ]);
        InvitationView::factory()->create([
            'invitation_id' => $invitation->id,
            'viewed_at' => Carbon::parse('2024-01-15 10:00:00'),
        ]);
        InvitationView::factory()->create([
            'invitation_id' => $invitation->id,
            'viewed_at' => Carbon::parse('2024-01-20 10:00:00'),
        ]);

        $start = Carbon::parse('2024-01-14');
        $end = Carbon::parse('2024-01-16');

        $views = $this->service->getViewsByDateRange($invitation, $start, $end);

        $this->assertCount(1, $views);
        $this->assertEquals('2024-01-15', $views[0]->date);
    }

    public function test_get_device_breakdown_returns_correct_counts()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Create views with different device types
        InvitationView::factory()->count(3)->create([
            'invitation_id' => $invitation->id,
            'device_type' => 'mobile',
        ]);
        InvitationView::factory()->count(2)->create([
            'invitation_id' => $invitation->id,
            'device_type' => 'desktop',
        ]);
        InvitationView::factory()->create([
            'invitation_id' => $invitation->id,
            'device_type' => 'tablet',
        ]);

        $breakdown = $this->service->getDeviceBreakdown($invitation);

        $this->assertEquals(2, $breakdown['desktop']);
        $this->assertEquals(3, $breakdown['mobile']);
        $this->assertEquals(1, $breakdown['tablet']);
    }

    public function test_get_device_breakdown_returns_zero_for_missing_types()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Create only mobile views
        InvitationView::factory()->count(2)->create([
            'invitation_id' => $invitation->id,
            'device_type' => 'mobile',
        ]);

        $breakdown = $this->service->getDeviceBreakdown($invitation);

        $this->assertEquals(0, $breakdown['desktop']);
        $this->assertEquals(2, $breakdown['mobile']);
        $this->assertEquals(0, $breakdown['tablet']);
    }

    public function test_get_browser_breakdown_returns_correct_counts()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Create views with different browsers
        InvitationView::factory()->count(4)->create([
            'invitation_id' => $invitation->id,
            'browser' => 'Chrome',
        ]);
        InvitationView::factory()->count(2)->create([
            'invitation_id' => $invitation->id,
            'browser' => 'Firefox',
        ]);
        InvitationView::factory()->create([
            'invitation_id' => $invitation->id,
            'browser' => 'Safari',
        ]);

        $breakdown = $this->service->getBrowserBreakdown($invitation);

        $this->assertEquals(4, $breakdown['Chrome']);
        $this->assertEquals(2, $breakdown['Firefox']);
        $this->assertEquals(1, $breakdown['Safari']);
    }

    public function test_get_browser_breakdown_orders_by_count_descending()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Create views with different browsers
        InvitationView::factory()->create([
            'invitation_id' => $invitation->id,
            'browser' => 'Safari',
        ]);
        InvitationView::factory()->count(3)->create([
            'invitation_id' => $invitation->id,
            'browser' => 'Chrome',
        ]);
        InvitationView::factory()->count(2)->create([
            'invitation_id' => $invitation->id,
            'browser' => 'Firefox',
        ]);

        $breakdown = $this->service->getBrowserBreakdown($invitation);

        $keys = array_keys($breakdown);
        $this->assertEquals('Chrome', $keys[0]);
        $this->assertEquals('Firefox', $keys[1]);
        $this->assertEquals('Safari', $keys[2]);
    }

    public function test_statistics_only_count_views_for_specific_invitation()
    {
        $user = User::factory()->create();
        $invitation1 = Invitation::factory()->create(['user_id' => $user->id]);
        $invitation2 = Invitation::factory()->create(['user_id' => $user->id]);

        // Create views for both invitations
        InvitationView::factory()->count(3)->create(['invitation_id' => $invitation1->id]);
        InvitationView::factory()->count(5)->create(['invitation_id' => $invitation2->id]);

        $totalViews1 = $this->service->getTotalViews($invitation1);
        $totalViews2 = $this->service->getTotalViews($invitation2);

        $this->assertEquals(3, $totalViews1);
        $this->assertEquals(5, $totalViews2);
    }
}
