<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\InvitationView;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatisticsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_statistics_for_their_invitation()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);
        InvitationView::factory()->count(5)->create(['invitation_id' => $invitation->id]);

        $response = $this->actingAs($user)->get(
            route('statistics.show', $invitation->id)
        );

        $response->assertStatus(200);
        $response->assertViewIs('statistics.show');
        $response->assertViewHas('invitation');
        $response->assertViewHas('totalViews', 5);
    }

    public function test_user_cannot_view_statistics_for_other_users_invitation()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->get(
            route('statistics.show', $invitation->id)
        );

        $response->assertStatus(403);
    }

    public function test_statistics_page_requires_authentication()
    {
        $invitation = Invitation::factory()->create();

        $response = $this->get(route('statistics.show', $invitation->id));

        $response->assertRedirect(route('login'));
    }

    public function test_statistics_page_returns_404_for_nonexistent_invitation()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(
            route('statistics.show', 99999)
        );

        $response->assertStatus(404);
    }

    public function test_views_chart_returns_json_data_for_last_30_days()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Create views for different dates
        InvitationView::factory()->create([
            'invitation_id' => $invitation->id,
            'viewed_at' => Carbon::now()->subDays(5),
        ]);
        InvitationView::factory()->create([
            'invitation_id' => $invitation->id,
            'viewed_at' => Carbon::now()->subDays(5),
        ]);
        InvitationView::factory()->create([
            'invitation_id' => $invitation->id,
            'viewed_at' => Carbon::now()->subDays(10),
        ]);

        $response = $this->actingAs($user)->get(
            route('statistics.views-chart', $invitation->id)
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'labels',
            'data',
        ]);

        $data = $response->json();

        // Should have 30 data points (last 30 days)
        $this->assertCount(30, $data['labels']);
        $this->assertCount(30, $data['data']);

        // Total views should match
        $this->assertEquals(3, array_sum($data['data']));
    }

    public function test_views_chart_fills_missing_dates_with_zero()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Create only one view
        InvitationView::factory()->create([
            'invitation_id' => $invitation->id,
            'viewed_at' => Carbon::now()->subDays(5),
        ]);

        $response = $this->actingAs($user)->get(
            route('statistics.views-chart', $invitation->id)
        );

        $response->assertStatus(200);
        $data = $response->json();

        // Should have 30 data points
        $this->assertCount(30, $data['data']);

        // Most days should have 0 views
        $zeroCount = count(array_filter($data['data'], fn($v) => $v === 0));
        $this->assertEquals(29, $zeroCount);

        // One day should have 1 view
        $this->assertEquals(1, array_sum($data['data']));
    }

    public function test_views_chart_requires_authorization()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->get(
            route('statistics.views-chart', $invitation->id)
        );

        $response->assertStatus(403);
    }

    public function test_device_stats_returns_device_and_browser_breakdown()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Create views with different devices and browsers
        InvitationView::factory()->create([
            'invitation_id' => $invitation->id,
            'device_type' => 'mobile',
            'browser' => 'Chrome',
        ]);
        InvitationView::factory()->create([
            'invitation_id' => $invitation->id,
            'device_type' => 'mobile',
            'browser' => 'Safari',
        ]);
        InvitationView::factory()->create([
            'invitation_id' => $invitation->id,
            'device_type' => 'desktop',
            'browser' => 'Chrome',
        ]);

        $response = $this->actingAs($user)->get(
            route('statistics.device-stats', $invitation->id)
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'devices' => ['desktop', 'mobile', 'tablet'],
            'browsers',
        ]);

        $data = $response->json();

        // Check device breakdown
        $this->assertEquals(1, $data['devices']['desktop']);
        $this->assertEquals(2, $data['devices']['mobile']);
        $this->assertEquals(0, $data['devices']['tablet']);

        // Check browser breakdown
        $this->assertEquals(2, $data['browsers']['Chrome']);
        $this->assertEquals(1, $data['browsers']['Safari']);
    }

    public function test_device_stats_requires_authorization()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->get(
            route('statistics.device-stats', $invitation->id)
        );

        $response->assertStatus(403);
    }

    public function test_device_stats_returns_empty_breakdown_for_invitation_with_no_views()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(
            route('statistics.device-stats', $invitation->id)
        );

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertEquals(0, $data['devices']['desktop']);
        $this->assertEquals(0, $data['devices']['mobile']);
        $this->assertEquals(0, $data['devices']['tablet']);
        $this->assertEmpty($data['browsers']);
    }

    public function test_views_chart_requires_authentication()
    {
        $invitation = Invitation::factory()->create();

        $response = $this->get(route('statistics.views-chart', $invitation->id));

        $response->assertRedirect(route('login'));
    }

    public function test_device_stats_requires_authentication()
    {
        $invitation = Invitation::factory()->create();

        $response = $this->get(route('statistics.device-stats', $invitation->id));

        $response->assertRedirect(route('login'));
    }
}
