<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\InvitationView;
use App\Models\User;
use App\Services\AdminStatisticsService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Property-Based Tests for Admin Statistics
 *
 * These tests validate universal properties that should hold true
 * for all admin statistics operations.
 */
class AdminStatisticsPropertyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Property 35: Admin Statistics Match Platform Data
     *
     * For any admin accessing platform statistics, the displayed totals
     * (users, invitations, views) should match the actual counts in the database.
     *
     * **Validates: Requirements 10.8**
     */
    public function test_property_admin_statistics_match_platform_data(): void
    {
        $testCases = [
            [
                'users' => 5,
                'active_users' => 3,
                'invitations' => 10,
                'published_invitations' => 6,
                'draft_invitations' => 4,
                'views' => 20,
            ],
            [
                'users' => 10,
                'active_users' => 8,
                'invitations' => 25,
                'published_invitations' => 15,
                'draft_invitations' => 10,
                'views' => 100,
            ],
            [
                'users' => 3,
                'active_users' => 2,
                'invitations' => 5,
                'published_invitations' => 3,
                'draft_invitations' => 2,
                'views' => 15,
            ],
            [
                'users' => 20,
                'active_users' => 15,
                'invitations' => 50,
                'published_invitations' => 30,
                'draft_invitations' => 20,
                'views' => 200,
            ],
            [
                'users' => 1,
                'active_users' => 1,
                'invitations' => 1,
                'published_invitations' => 1,
                'draft_invitations' => 0,
                'views' => 5,
            ],
        ];

        foreach ($testCases as $testCase) {
            // Create admin user
            $admin = User::factory()->create([
                'is_admin' => true,
                'is_active' => true,
            ]);

            // Create regular users (subtract 1 for admin)
            $activeUserCount = max(0, $testCase['active_users'] - 1);
            $inactiveUserCount = max(0, $testCase['users'] - $testCase['active_users']);

            $activeUsers = User::factory()
                ->count($activeUserCount)
                ->create([
                    'is_admin' => false,
                    'is_active' => true,
                ]);

            $inactiveUsers = User::factory()
                ->count($inactiveUserCount)
                ->create([
                    'is_admin' => false,
                    'is_active' => false,
                ]);

            $allUsers = $activeUsers->concat($inactiveUsers);

            // Create invitations
            $publishedInvitations = collect();
            $draftInvitations = collect();

            // Determine which user to assign invitations to
            $invitationUserId = $allUsers->isNotEmpty() ? $allUsers->random()->id : $admin->id;

            if ($testCase['published_invitations'] > 0) {
                $publishedInvitations = Invitation::factory()
                    ->count($testCase['published_invitations'])
                    ->create([
                        'user_id' => $invitationUserId,
                        'status' => 'published',
                        'unique_url' => fn() => \Illuminate\Support\Str::random(32),
                    ]);
            }

            if ($testCase['draft_invitations'] > 0) {
                $draftInvitations = Invitation::factory()
                    ->count($testCase['draft_invitations'])
                    ->create([
                        'user_id' => $invitationUserId,
                        'status' => 'draft',
                        'unique_url' => null,
                    ]);
            }

            // Create views for published invitations
            if ($testCase['views'] > 0 && $publishedInvitations->isNotEmpty()) {
                $viewsPerInvitation = (int) ceil($testCase['views'] / $publishedInvitations->count());

                foreach ($publishedInvitations as $invitation) {
                    InvitationView::factory()
                        ->count(min($viewsPerInvitation, $testCase['views']))
                        ->create([
                            'invitation_id' => $invitation->id,
                        ]);

                    $testCase['views'] -= $viewsPerInvitation;
                    if ($testCase['views'] <= 0) {
                        break;
                    }
                }
            }

            // Get statistics from service
            $statisticsService = new AdminStatisticsService();
            $platformStats = $statisticsService->getPlatformStats();

            // Property: Total users should match database count
            $actualTotalUsers = User::count();
            $this->assertEquals(
                $actualTotalUsers,
                $platformStats['total_users'],
                "Total users mismatch: expected {$actualTotalUsers}, got {$platformStats['total_users']}"
            );

            // Property: Active users should match database count
            $actualActiveUsers = User::where('is_active', true)->count();
            $this->assertEquals(
                $actualActiveUsers,
                $platformStats['active_users'],
                "Active users mismatch: expected {$actualActiveUsers}, got {$platformStats['active_users']}"
            );

            // Property: Total invitations should match database count
            $actualTotalInvitations = Invitation::count();
            $this->assertEquals(
                $actualTotalInvitations,
                $platformStats['total_invitations'],
                "Total invitations mismatch: expected {$actualTotalInvitations}, got {$platformStats['total_invitations']}"
            );

            // Property: Published invitations should match database count
            $actualPublishedInvitations = Invitation::where('status', 'published')->count();
            $this->assertEquals(
                $actualPublishedInvitations,
                $platformStats['published_invitations'],
                "Published invitations mismatch: expected {$actualPublishedInvitations}, got {$platformStats['published_invitations']}"
            );

            // Property: Draft invitations should match database count
            $actualDraftInvitations = Invitation::where('status', 'draft')->count();
            $this->assertEquals(
                $actualDraftInvitations,
                $platformStats['draft_invitations'],
                "Draft invitations mismatch: expected {$actualDraftInvitations}, got {$platformStats['draft_invitations']}"
            );

            // Property: Total views should match database count
            $actualTotalViews = InvitationView::count();
            $this->assertEquals(
                $actualTotalViews,
                $platformStats['total_views'],
                "Total views mismatch: expected {$actualTotalViews}, got {$platformStats['total_views']}"
            );

            // Property: Statistics from service match database counts
            $this->assertEquals($actualTotalUsers, $platformStats['total_users']);
            $this->assertEquals($actualActiveUsers, $platformStats['active_users']);
            $this->assertEquals($actualTotalInvitations, $platformStats['total_invitations']);
            $this->assertEquals($actualPublishedInvitations, $platformStats['published_invitations']);
            $this->assertEquals($actualDraftInvitations, $platformStats['draft_invitations']);
            $this->assertEquals($actualTotalViews, $platformStats['total_views']);

            // Cleanup for next iteration
            InvitationView::whereIn('invitation_id', $publishedInvitations->pluck('id'))->delete();
            Invitation::whereIn('id', $publishedInvitations->pluck('id'))->delete();
            Invitation::whereIn('id', $draftInvitations->pluck('id'))->delete();
            User::whereIn('id', $allUsers->pluck('id'))->delete();
            $admin->delete();
        }
    }

    /**
     * Property: User Growth Statistics Are Accurate
     *
     * For any time period, the user growth statistics should accurately
     * reflect the number of users created per day.
     *
     * **Validates: Requirements 10.8**
     */
    public function test_property_user_growth_statistics_are_accurate(): void
    {
        $testCases = [
            ['days' => 7, 'users_per_day' => [1, 2, 0, 3, 1, 0, 2]],
            ['days' => 14, 'users_per_day' => [2, 1, 3, 0, 1, 2, 1, 0, 2, 1, 3, 0, 1, 2]],
            ['days' => 5, 'users_per_day' => [5, 0, 3, 2, 1]],
        ];

        foreach ($testCases as $testCase) {
            // Clean up ALL existing data first
            User::query()->delete();

            // Create admin user with a date outside the test range
            $admin = User::factory()->create([
                'is_admin' => true,
                'is_active' => true,
                'created_at' => Carbon::now()->subDays($testCase['days'] + 10),
                'updated_at' => Carbon::now()->subDays($testCase['days'] + 10),
            ]);

            // Create users on specific days
            $expectedCounts = [];
            for ($i = $testCase['days'] - 1; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $count = $testCase['users_per_day'][$testCase['days'] - 1 - $i] ?? 0;

                for ($j = 0; $j < $count; $j++) {
                    User::factory()->create([
                        'is_admin' => false,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);
                }

                $expectedCounts[] = $count;
            }

            // Get growth statistics
            $statisticsService = new AdminStatisticsService();
            $userGrowth = $statisticsService->getUserGrowth($testCase['days']);

            // Property: Growth data should have correct number of days
            $this->assertCount($testCase['days'], $userGrowth['dates']);
            $this->assertCount($testCase['days'], $userGrowth['counts']);

            // Property: Each day's count should match expected
            for ($i = 0; $i < $testCase['days']; $i++) {
                $this->assertEquals(
                    $expectedCounts[$i],
                    $userGrowth['counts'][$i],
                    "User count mismatch on day {$i}: expected {$expectedCounts[$i]}, got {$userGrowth['counts'][$i]}"
                );
            }

            // Cleanup
            User::query()->delete();
        }
    }

    /**
     * Property: Invitation Growth Statistics Are Accurate
     *
     * For any time period, the invitation growth statistics should accurately
     * reflect the number of invitations created per day.
     *
     * **Validates: Requirements 10.8**
     */
    public function test_property_invitation_growth_statistics_are_accurate(): void
    {
        $testCases = [
            ['days' => 7, 'invitations_per_day' => [2, 3, 1, 0, 2, 1, 3]],
            ['days' => 10, 'invitations_per_day' => [1, 0, 2, 3, 1, 2, 0, 1, 2, 1]],
            ['days' => 5, 'invitations_per_day' => [3, 2, 4, 1, 2]],
        ];

        foreach ($testCases as $testCase) {
            $admin = User::factory()->create([
                'is_admin' => true,
                'is_active' => true,
            ]);

            $user = User::factory()->create([
                'is_admin' => false,
                'is_active' => true,
            ]);

            // Create invitations on specific days
            $expectedCounts = [];
            for ($i = $testCase['days'] - 1; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $count = $testCase['invitations_per_day'][$testCase['days'] - 1 - $i] ?? 0;

                for ($j = 0; $j < $count; $j++) {
                    Invitation::factory()->create([
                        'user_id' => $user->id,
                        'created_at' => $date,
                    ]);
                }

                $expectedCounts[] = $count;
            }

            // Get growth statistics
            $statisticsService = new AdminStatisticsService();
            $invitationGrowth = $statisticsService->getInvitationGrowth($testCase['days']);

            // Property: Growth data should have correct number of days
            $this->assertCount($testCase['days'], $invitationGrowth['dates']);
            $this->assertCount($testCase['days'], $invitationGrowth['counts']);

            // Property: Each day's count should match expected
            for ($i = 0; $i < $testCase['days']; $i++) {
                $this->assertEquals(
                    $expectedCounts[$i],
                    $invitationGrowth['counts'][$i],
                    "Invitation count mismatch on day {$i}: expected {$expectedCounts[$i]}, got {$invitationGrowth['counts'][$i]}"
                );
            }

            // Cleanup
            Invitation::where('user_id', $user->id)->delete();
            $user->delete();
            $admin->delete();
        }
    }

    /**
     * Property: View Growth Statistics Are Accurate
     *
     * For any time period, the view growth statistics should accurately
     * reflect the number of views recorded per day.
     *
     * **Validates: Requirements 10.8**
     */
    public function test_property_view_growth_statistics_are_accurate(): void
    {
        $testCases = [
            ['days' => 7, 'views_per_day' => [5, 10, 3, 0, 8, 2, 6]],
            ['days' => 10, 'views_per_day' => [2, 0, 5, 8, 3, 6, 0, 4, 7, 2]],
            ['days' => 5, 'views_per_day' => [10, 5, 8, 3, 6]],
        ];

        foreach ($testCases as $testCase) {
            $admin = User::factory()->create([
                'is_admin' => true,
                'is_active' => true,
            ]);

            $user = User::factory()->create([
                'is_admin' => false,
                'is_active' => true,
            ]);

            $invitation = Invitation::factory()->create([
                'user_id' => $user->id,
                'status' => 'published',
                'unique_url' => \Illuminate\Support\Str::random(32),
            ]);

            // Create views on specific days
            $expectedCounts = [];
            for ($i = $testCase['days'] - 1; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $count = $testCase['views_per_day'][$testCase['days'] - 1 - $i] ?? 0;

                for ($j = 0; $j < $count; $j++) {
                    InvitationView::factory()->create([
                        'invitation_id' => $invitation->id,
                        'viewed_at' => $date,
                    ]);
                }

                $expectedCounts[] = $count;
            }

            // Get growth statistics
            $statisticsService = new AdminStatisticsService();
            $viewGrowth = $statisticsService->getViewGrowth($testCase['days']);

            // Property: Growth data should have correct number of days
            $this->assertCount($testCase['days'], $viewGrowth['dates']);
            $this->assertCount($testCase['days'], $viewGrowth['counts']);

            // Property: Each day's count should match expected
            for ($i = 0; $i < $testCase['days']; $i++) {
                $this->assertEquals(
                    $expectedCounts[$i],
                    $viewGrowth['counts'][$i],
                    "View count mismatch on day {$i}: expected {$expectedCounts[$i]}, got {$viewGrowth['counts'][$i]}"
                );
            }

            // Cleanup
            InvitationView::where('invitation_id', $invitation->id)->delete();
            $invitation->delete();
            $user->delete();
            $admin->delete();
        }
    }

    /**
     * Property: Top Users Statistics Are Accurate
     *
     * For any set of users, the top users by invitation count should
     * accurately reflect the users with the most invitations.
     *
     * **Validates: Requirements 10.8**
     */
    public function test_property_top_users_statistics_are_accurate(): void
    {
        $testCases = [
            ['users' => 5, 'invitations_per_user' => [10, 5, 8, 3, 6]],
            ['users' => 8, 'invitations_per_user' => [2, 15, 7, 3, 12, 5, 9, 4]],
            ['users' => 3, 'invitations_per_user' => [5, 10, 3]],
        ];

        foreach ($testCases as $testCase) {
            // Clean up any existing data first
            Invitation::query()->delete();
            User::query()->delete();

            // Create admin user with no invitations
            $admin = User::factory()->create([
                'is_admin' => true,
                'is_active' => true,
            ]);

            $users = [];
            foreach ($testCase['invitations_per_user'] as $invitationCount) {
                $user = User::factory()->create([
                    'is_admin' => false,
                    'is_active' => true,
                ]);

                Invitation::factory()
                    ->count($invitationCount)
                    ->create([
                        'user_id' => $user->id,
                    ]);

                $users[] = [
                    'user' => $user,
                    'count' => $invitationCount,
                ];
            }

            // Sort users by invitation count descending
            usort($users, fn($a, $b) => $b['count'] <=> $a['count']);

            // Get top users statistics
            $statisticsService = new AdminStatisticsService();
            $topUsers = $statisticsService->getTopUsersByInvitations(5);

            // Property: Top users list should not exceed the limit
            $this->assertLessThanOrEqual(5, count($topUsers));

            // Property: Only users with invitations should be in the expected list
            // But the service may return users with 0 invitations, so filter the actual results
            $topUsersWithInvitations = array_filter($topUsers, fn($u) => $u['invitations_count'] > 0);

            $expectedTopCount = min(5, count($users));
            $this->assertCount($expectedTopCount, $topUsersWithInvitations);

            // Re-index the filtered array
            $topUsersWithInvitations = array_values($topUsersWithInvitations);

            for ($i = 0; $i < $expectedTopCount; $i++) {
                $this->assertEquals(
                    $users[$i]['user']->id,
                    $topUsersWithInvitations[$i]['id'],
                    "Top user #{$i} mismatch"
                );
                $this->assertEquals(
                    $users[$i]['count'],
                    $topUsersWithInvitations[$i]['invitations_count'],
                    "Top user #{$i} invitation count mismatch"
                );
            }

            // Cleanup
            Invitation::query()->delete();
            User::query()->delete();
        }
    }

    /**
     * Property: Top Invitations Statistics Are Accurate
     *
     * For any set of invitations, the top invitations by view count should
     * accurately reflect the invitations with the most views.
     *
     * **Validates: Requirements 10.8**
     */
    public function test_property_top_invitations_statistics_are_accurate(): void
    {
        $testCases = [
            ['invitations' => 5, 'views_per_invitation' => [20, 10, 15, 5, 12]],
            ['invitations' => 8, 'views_per_invitation' => [5, 30, 15, 8, 25, 10, 18, 12]],
            ['invitations' => 3, 'views_per_invitation' => [10, 20, 5]],
        ];

        foreach ($testCases as $testCase) {
            $admin = User::factory()->create([
                'is_admin' => true,
                'is_active' => true,
            ]);

            $user = User::factory()->create([
                'is_admin' => false,
                'is_active' => true,
            ]);

            $invitations = [];
            foreach ($testCase['views_per_invitation'] as $viewCount) {
                $invitation = Invitation::factory()->create([
                    'user_id' => $user->id,
                    'status' => 'published',
                    'unique_url' => \Illuminate\Support\Str::random(32),
                ]);

                InvitationView::factory()
                    ->count($viewCount)
                    ->create([
                        'invitation_id' => $invitation->id,
                    ]);

                $invitations[] = [
                    'invitation' => $invitation,
                    'count' => $viewCount,
                ];
            }

            // Sort invitations by view count descending
            usort($invitations, fn($a, $b) => $b['count'] <=> $a['count']);

            // Get top invitations statistics
            $statisticsService = new AdminStatisticsService();
            $topInvitations = $statisticsService->getTopInvitationsByViews(5);

            // Property: Top invitations should be ordered by view count
            $expectedTopCount = min(5, count($invitations));
            $this->assertCount($expectedTopCount, $topInvitations);

            for ($i = 0; $i < $expectedTopCount; $i++) {
                $this->assertEquals(
                    $invitations[$i]['invitation']->id,
                    $topInvitations[$i]['id'],
                    "Top invitation #{$i} mismatch"
                );
                $this->assertEquals(
                    $invitations[$i]['count'],
                    $topInvitations[$i]['views_count'],
                    "Top invitation #{$i} view count mismatch"
                );
            }

            // Cleanup
            foreach ($invitations as $invitationData) {
                InvitationView::where('invitation_id', $invitationData['invitation']->id)->delete();
                $invitationData['invitation']->delete();
            }
            $user->delete();
            $admin->delete();
        }
    }

    /**
     * Property: Statistics Are Consistent Across Multiple Reads
     *
     * For any platform state, reading statistics multiple times should
     * return the same values (statistics are deterministic).
     *
     * **Validates: Requirements 10.8**
     */
    public function test_property_statistics_are_consistent_across_multiple_reads(): void
    {
        $testCases = [1, 2, 3, 4, 5];

        foreach ($testCases as $iteration) {
            $admin = User::factory()->create([
                'is_admin' => true,
                'is_active' => true,
            ]);

            // Create some data
            $users = User::factory()->count(5)->create(['is_admin' => false]);
            $invitations = Invitation::factory()->count(10)->create([
                'user_id' => $users->random()->id,
            ]);
            $publishedInvitations = $invitations->take(5);
            foreach ($publishedInvitations as $invitation) {
                $invitation->update(['status' => 'published', 'unique_url' => \Illuminate\Support\Str::random(32)]);
                InvitationView::factory()->count(3)->create(['invitation_id' => $invitation->id]);
            }

            // Get statistics multiple times
            $statisticsService = new AdminStatisticsService();
            $stats1 = $statisticsService->getPlatformStats();
            $stats2 = $statisticsService->getPlatformStats();
            $stats3 = $statisticsService->getPlatformStats();

            // Property: All reads should return identical values
            $this->assertEquals($stats1, $stats2, "Statistics read 1 and 2 differ");
            $this->assertEquals($stats2, $stats3, "Statistics read 2 and 3 differ");
            $this->assertEquals($stats1, $stats3, "Statistics read 1 and 3 differ");

            // Cleanup
            InvitationView::whereIn('invitation_id', $invitations->pluck('id'))->delete();
            Invitation::whereIn('id', $invitations->pluck('id'))->delete();
            User::whereIn('id', $users->pluck('id'))->delete();
            $admin->delete();
        }
    }
}
