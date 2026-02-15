<?php

namespace Tests\Unit;

use App\Services\AdminStatisticsService;
use App\Models\Invitation;
use App\Models\InvitationView;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_statistics_service_calculates_total_users(): void
    {
        $initialCount = User::count();
        User::factory()->count(5)->create();

        $service = new AdminStatisticsService();
        $stats = $service->getPlatformStats();

        $this->assertEquals($initialCount + 5, $stats['total_users']);
    }

    public function test_admin_statistics_service_calculates_active_users(): void
    {
        User::factory()->count(3)->create(['is_active' => true]);
        User::factory()->count(2)->create(['is_active' => false]);

        $service = new AdminStatisticsService();
        $stats = $service->getPlatformStats();

        $expected = User::where('is_active', true)->count();
        $this->assertEquals($expected, $stats['active_users']);
    }

    public function test_admin_statistics_service_calculates_total_invitations(): void
    {
        $initialCount = Invitation::count();
        Invitation::factory()->count(7)->create();

        $service = new AdminStatisticsService();
        $stats = $service->getPlatformStats();

        $this->assertEquals($initialCount + 7, $stats['total_invitations']);
    }

    public function test_admin_statistics_service_calculates_published_invitations(): void
    {
        Invitation::factory()->count(4)->create(['status' => 'published']);
        Invitation::factory()->count(3)->create(['status' => 'draft']);

        $service = new AdminStatisticsService();
        $stats = $service->getPlatformStats();

        $this->assertEquals(4, $stats['published_invitations']);
    }

    public function test_admin_statistics_service_calculates_total_views(): void
    {
        $initialCount = InvitationView::count();
        InvitationView::factory()->count(15)->create();

        $service = new AdminStatisticsService();
        $stats = $service->getPlatformStats();

        $this->assertEquals($initialCount + 15, $stats['total_views']);
    }

    public function test_admin_statistics_service_provides_user_growth_data(): void
    {
        User::factory()->count(3)->create();

        $service = new AdminStatisticsService();
        $growth = $service->getUserGrowth(30);

        $this->assertIsArray($growth);
        $this->assertArrayHasKey('dates', $growth);
        $this->assertArrayHasKey('counts', $growth);
        $this->assertCount(30, $growth['dates']);
        $this->assertCount(30, $growth['counts']);
    }

    public function test_admin_statistics_service_provides_invitation_growth_data(): void
    {
        Invitation::factory()->count(5)->create();

        $service = new AdminStatisticsService();
        $growth = $service->getInvitationGrowth(30);

        $this->assertIsArray($growth);
        $this->assertArrayHasKey('dates', $growth);
        $this->assertArrayHasKey('counts', $growth);
        $this->assertCount(30, $growth['dates']);
        $this->assertCount(30, $growth['counts']);
    }

    public function test_admin_statistics_service_handles_zero_data(): void
    {
        User::query()->delete();
        Invitation::query()->delete();
        InvitationView::query()->delete();

        $service = new AdminStatisticsService();
        $stats = $service->getPlatformStats();

        $this->assertEquals(0, $stats['total_users']);
        $this->assertEquals(0, $stats['total_invitations']);
        $this->assertEquals(0, $stats['total_views']);
        $this->assertEquals(0, $stats['active_users']);
        $this->assertEquals(0, $stats['published_invitations']);
    }

    public function test_admin_statistics_service_growth_data_with_custom_days(): void
    {
        User::factory()->count(3)->create();

        $service = new AdminStatisticsService();
        $growth = $service->getUserGrowth(7);

        $this->assertCount(7, $growth['dates']);
        $this->assertCount(7, $growth['counts']);
    }
}

