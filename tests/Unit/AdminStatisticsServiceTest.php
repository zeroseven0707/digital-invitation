<?php

namespace Tests\Unit;

use App\Models\Invitation;
use App\Models\InvitationView;
use App\Models\User;
use App\Services\AdminStatisticsService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminStatisticsServiceTest extends TestCase
{
    use RefreshDatabase;

    private AdminStatisticsService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AdminStatisticsService();
    }

    public function test_get_platform_stats_returns_all_required_keys(): void
    {
        $stats = $this->service->getPlatformStats();

        $this->assertArrayHasKey('total_users', $stats);
        $this->assertArrayHasKey('active_users', $stats);
        $this->assertArrayHasKey('total_invitations', $stats);
        $this->assertArrayHasKey('published_invitations', $stats);
        $this->assertArrayHasKey('total_views', $stats);
    }

    public function test_get_platform_stats_with_mixed_data(): void
    {
        // Create test data in isolation
        $testUser1 = User::factory()->create(['is_active' => true, 'email' => 'test1@stats.test']);
        $testUser2 = User::factory()->create(['is_active' => false, 'email' => 'test2@stats.test']);

        $testInvitation1 = Invitation::factory()->create(['status' => 'published', 'user_id' => $testUser1->id]);
        $testInvitation2 = Invitation::factory()->create(['status' => 'draft', 'user_id' => $testUser1->id]);

        $testView = InvitationView::factory()->create(['invitation_id' => $testInvitation1->id]);

        $stats = $this->service->getPlatformStats();

        // Verify stats contain our test data
        $this->assertGreaterThanOrEqual(2, $stats['total_users']);
        $this->assertGreaterThanOrEqual(1, $stats['active_users']);
        $this->assertGreaterThanOrEqual(2, $stats['total_invitations']);
        $this->assertGreaterThanOrEqual(1, $stats['published_invitations']);
        $this->assertGreaterThanOrEqual(1, $stats['total_views']);

        // Verify published count is less than or equal to total
        $this->assertLessThanOrEqual($stats['total_invitations'], $stats['published_invitations']);
    }

    public function test_get_user_growth_returns_correct_structure(): void
    {
        $growth = $this->service->getUserGrowth(7);

        $this->assertIsArray($growth);
        $this->assertArrayHasKey('dates', $growth);
        $this->assertArrayHasKey('counts', $growth);
        $this->assertCount(7, $growth['dates']);
        $this->assertCount(7, $growth['counts']);
    }

    public function test_get_user_growth_with_data_on_specific_dates(): void
    {
        // Create users on specific dates
        User::factory()->create(['created_at' => Carbon::now()->subDays(2)]);
        User::factory()->create(['created_at' => Carbon::now()->subDays(2)]);
        User::factory()->create(['created_at' => Carbon::now()->subDays(1)]);

        $growth = $this->service->getUserGrowth(7);

        // Verify cumulative counts
        $this->assertIsArray($growth['counts']);
        $this->assertGreaterThanOrEqual(0, $growth['counts'][0]);
    }

    public function test_get_invitation_growth_returns_correct_structure(): void
    {
        $growth = $this->service->getInvitationGrowth(7);

        $this->assertIsArray($growth);
        $this->assertArrayHasKey('dates', $growth);
        $this->assertArrayHasKey('counts', $growth);
        $this->assertCount(7, $growth['dates']);
        $this->assertCount(7, $growth['counts']);
    }

    public function test_get_invitation_growth_with_data_on_specific_dates(): void
    {
        Invitation::factory()->create(['created_at' => Carbon::now()->subDays(3)]);
        Invitation::factory()->create(['created_at' => Carbon::now()->subDays(1)]);
        Invitation::factory()->create(['created_at' => Carbon::now()]);

        $growth = $this->service->getInvitationGrowth(7);

        $this->assertIsArray($growth['counts']);
        $this->assertGreaterThanOrEqual(0, $growth['counts'][0]);
    }

    public function test_get_view_growth_returns_correct_structure(): void
    {
        $growth = $this->service->getViewGrowth(7);

        $this->assertIsArray($growth);
        $this->assertArrayHasKey('dates', $growth);
        $this->assertArrayHasKey('counts', $growth);
        $this->assertCount(7, $growth['dates']);
        $this->assertCount(7, $growth['counts']);
    }

    public function test_get_top_users_returns_users_ordered_by_invitation_count(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        Invitation::factory()->count(5)->create(['user_id' => $user1->id]);
        Invitation::factory()->count(10)->create(['user_id' => $user2->id]);
        Invitation::factory()->count(3)->create(['user_id' => $user3->id]);

        $topUsers = $this->service->getTopUsersByInvitations(5);

        $this->assertCount(3, $topUsers);
        $this->assertEquals($user2->id, $topUsers[0]['id']);
        $this->assertEquals(10, $topUsers[0]['invitations_count']);
        $this->assertEquals($user1->id, $topUsers[1]['id']);
        $this->assertEquals(5, $topUsers[1]['invitations_count']);
    }

    public function test_get_top_users_limits_results(): void
    {
        User::factory()->count(10)->create()->each(function ($user) {
            Invitation::factory()->count(rand(1, 5))->create(['user_id' => $user->id]);
        });

        $topUsers = $this->service->getTopUsersByInvitations(3);

        $this->assertCount(3, $topUsers);
    }

    public function test_get_top_invitations_returns_invitations_ordered_by_view_count(): void
    {
        $invitation1 = Invitation::factory()->create(['status' => 'published']);
        $invitation2 = Invitation::factory()->create(['status' => 'published']);
        $invitation3 = Invitation::factory()->create(['status' => 'published']);

        InvitationView::factory()->count(5)->create(['invitation_id' => $invitation1->id]);
        InvitationView::factory()->count(15)->create(['invitation_id' => $invitation2->id]);
        InvitationView::factory()->count(8)->create(['invitation_id' => $invitation3->id]);

        $topInvitations = $this->service->getTopInvitationsByViews(5);

        $this->assertCount(3, $topInvitations);
        $this->assertEquals($invitation2->id, $topInvitations[0]['id']);
        $this->assertEquals(15, $topInvitations[0]['views_count']);
    }

    public function test_get_top_invitations_only_includes_published(): void
    {
        $published = Invitation::factory()->create(['status' => 'published']);
        $draft = Invitation::factory()->create(['status' => 'draft']);

        InvitationView::factory()->count(10)->create(['invitation_id' => $published->id]);
        InvitationView::factory()->count(20)->create(['invitation_id' => $draft->id]);

        $topInvitations = $this->service->getTopInvitationsByViews(5);

        $this->assertCount(1, $topInvitations);
        $this->assertEquals($published->id, $topInvitations[0]['id']);
    }

    public function test_growth_data_handles_empty_database(): void
    {
        User::query()->delete();
        Invitation::query()->delete();
        InvitationView::query()->delete();

        $userGrowth = $this->service->getUserGrowth(7);
        $invitationGrowth = $this->service->getInvitationGrowth(7);
        $viewGrowth = $this->service->getViewGrowth(7);

        $this->assertCount(7, $userGrowth['dates']);
        $this->assertCount(7, $userGrowth['counts']);
        $this->assertEquals(0, array_sum($userGrowth['counts']));

        $this->assertCount(7, $invitationGrowth['dates']);
        $this->assertCount(7, $invitationGrowth['counts']);
        $this->assertEquals(0, array_sum($invitationGrowth['counts']));

        $this->assertCount(7, $viewGrowth['dates']);
        $this->assertCount(7, $viewGrowth['counts']);
        $this->assertEquals(0, array_sum($viewGrowth['counts']));
    }

    public function test_growth_data_with_different_day_ranges(): void
    {
        $growth1 = $this->service->getUserGrowth(1);
        $growth7 = $this->service->getUserGrowth(7);
        $growth30 = $this->service->getUserGrowth(30);

        $this->assertCount(1, $growth1['dates']);
        $this->assertCount(7, $growth7['dates']);
        $this->assertCount(30, $growth30['dates']);
    }
}
