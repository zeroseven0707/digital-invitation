<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\InvitationView;
use App\Models\Template;
use App\Models\User;
use App\Services\StatisticsService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Property-Based Tests for Statistics
 *
 * These tests validate universal properties that should hold true
 * for all statistics operations.
 */
class StatisticsPropertyTest extends TestCase
{
    use RefreshDatabase;

    protected StatisticsService $statisticsService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\TemplateSeeder::class);
        $this->statisticsService = app(StatisticsService::class);
    }

    /**
     * Property 29: View Count Matches Recorded Views
     *
     * For any invitation, the total view count should equal the number
     * of view records in the database for that invitation.
     *
     * **Validates: Requirements 8.2**
     *
     * @test
     */
    public function property_view_count_matches_recorded_views(): void
    {
        // Test with multiple invitations and varying view counts
        $testCases = [
            ['view_count' => 0],
            ['view_count' => 1],
            ['view_count' => 5],
            ['view_count' => 10],
            ['view_count' => 25],
            ['view_count' => 50],
            ['view_count' => 100],
        ];

        foreach ($testCases as $index => $testCase) {
            $user = User::factory()->create();
            $template = Template::first();
            $invitation = Invitation::factory()->published()->create([
                'user_id' => $user->id,
                'template_id' => $template->id,
            ]);

            // Create the specified number of view records
            for ($i = 0; $i < $testCase['view_count']; $i++) {
                InvitationView::create([
                    'invitation_id' => $invitation->id,
                    'ip_address' => '192.168.1.' . ($i + 1),
                    'user_agent' => 'Mozilla/5.0 Test Browser ' . $i,
                    'device_type' => ['desktop', 'mobile', 'tablet'][array_rand(['desktop', 'mobile', 'tablet'])],
                    'browser' => ['Chrome', 'Firefox', 'Safari'][array_rand(['Chrome', 'Firefox', 'Safari'])],
                    'viewed_at' => now()->subDays(rand(0, 30)),
                ]);
            }

            // Property: getTotalViews should return exact count from database
            $totalViews = $this->statisticsService->getTotalViews($invitation);
            $databaseCount = InvitationView::where('invitation_id', $invitation->id)->count();

            $this->assertEquals($databaseCount, $totalViews,
                "Total views should match database count for test case $index with {$testCase['view_count']} views");

            $this->assertEquals($testCase['view_count'], $totalViews,
                "Total views should be {$testCase['view_count']} for test case $index");

            // Property: Count should be non-negative
            $this->assertGreaterThanOrEqual(0, $totalViews,
                "View count should never be negative");
        }
    }

    /**
     * Property: View Count Increases with Each New View
     *
     * For any invitation, adding a new view record should increase
     * the total view count by exactly 1.
     *
     * **Validates: Requirements 8.2**
     *
     * @test
     */
    public function property_view_count_increases_with_new_views(): void
    {
        $user = User::factory()->create();
        $template = Template::first();
        $invitation = Invitation::factory()->published()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
        ]);

        // Test adding views incrementally
        for ($i = 0; $i < 20; $i++) {
            $countBefore = $this->statisticsService->getTotalViews($invitation);

            // Add a new view
            InvitationView::create([
                'invitation_id' => $invitation->id,
                'ip_address' => '10.0.0.' . ($i + 1),
                'user_agent' => 'Mozilla/5.0 Test Browser',
                'device_type' => 'desktop',
                'browser' => 'Chrome',
                'viewed_at' => now(),
            ]);

            $countAfter = $this->statisticsService->getTotalViews($invitation);

            // Property: Count should increase by exactly 1
            $this->assertEquals($countBefore + 1, $countAfter,
                "View count should increase by 1 after adding view $i");
        }
    }

    /**
     * Property 30: Statistics Aggregation is Accurate
     *
     * For any invitation with views, the statistics (views per day,
     * device breakdown, browser breakdown) should accurately reflect
     * the view records in the database.
     *
     * **Validates: Requirements 8.2, 8.3, 8.4**
     *
     * @test
     */
    public function property_statistics_aggregation_is_accurate(): void
    {
        $testCases = [
            [
                'views' => [
                    ['device' => 'desktop', 'browser' => 'Chrome', 'days_ago' => 0],
                    ['device' => 'desktop', 'browser' => 'Chrome', 'days_ago' => 0],
                    ['device' => 'mobile', 'browser' => 'Safari', 'days_ago' => 1],
                    ['device' => 'tablet', 'browser' => 'Firefox', 'days_ago' => 2],
                ],
                'expected_device' => ['desktop' => 2, 'mobile' => 1, 'tablet' => 1],
                'expected_browser' => ['Chrome' => 2, 'Safari' => 1, 'Firefox' => 1],
            ],
            [
                'views' => [
                    ['device' => 'mobile', 'browser' => 'Safari', 'days_ago' => 0],
                    ['device' => 'mobile', 'browser' => 'Safari', 'days_ago' => 0],
                    ['device' => 'mobile', 'browser' => 'Chrome', 'days_ago' => 1],
                    ['device' => 'desktop', 'browser' => 'Chrome', 'days_ago' => 1],
                    ['device' => 'desktop', 'browser' => 'Firefox', 'days_ago' => 2],
                ],
                'expected_device' => ['desktop' => 2, 'mobile' => 3, 'tablet' => 0],
                'expected_browser' => ['Safari' => 2, 'Chrome' => 2, 'Firefox' => 1],
            ],
            [
                'views' => [
                    ['device' => 'tablet', 'browser' => 'Safari', 'days_ago' => 0],
                    ['device' => 'tablet', 'browser' => 'Safari', 'days_ago' => 1],
                    ['device' => 'tablet', 'browser' => 'Safari', 'days_ago' => 2],
                ],
                'expected_device' => ['desktop' => 0, 'mobile' => 0, 'tablet' => 3],
                'expected_browser' => ['Safari' => 3],
            ],
        ];

        foreach ($testCases as $index => $testCase) {
            $user = User::factory()->create();
            $template = Template::first();
            $invitation = Invitation::factory()->published()->create([
                'user_id' => $user->id,
                'template_id' => $template->id,
            ]);

            // Create view records according to test case
            foreach ($testCase['views'] as $viewIndex => $viewData) {
                InvitationView::create([
                    'invitation_id' => $invitation->id,
                    'ip_address' => '192.168.1.' . ($viewIndex + 1),
                    'user_agent' => 'Mozilla/5.0 Test Browser',
                    'device_type' => $viewData['device'],
                    'browser' => $viewData['browser'],
                    'viewed_at' => now()->subDays($viewData['days_ago']),
                ]);
            }

            // Property: Device breakdown should match actual distribution
            $deviceBreakdown = $this->statisticsService->getDeviceBreakdown($invitation);

            $this->assertEquals($testCase['expected_device']['desktop'], $deviceBreakdown['desktop'],
                "Desktop count should match for test case $index");
            $this->assertEquals($testCase['expected_device']['mobile'], $deviceBreakdown['mobile'],
                "Mobile count should match for test case $index");
            $this->assertEquals($testCase['expected_device']['tablet'], $deviceBreakdown['tablet'],
                "Tablet count should match for test case $index");

            // Property: Sum of device breakdown should equal total views
            $deviceSum = array_sum($deviceBreakdown);
            $totalViews = $this->statisticsService->getTotalViews($invitation);
            $this->assertEquals($totalViews, $deviceSum,
                "Sum of device breakdown should equal total views for test case $index");

            // Property: Browser breakdown should match actual distribution
            $browserBreakdown = $this->statisticsService->getBrowserBreakdown($invitation);

            foreach ($testCase['expected_browser'] as $browser => $expectedCount) {
                $this->assertEquals($expectedCount, $browserBreakdown[$browser] ?? 0,
                    "$browser count should be $expectedCount for test case $index");
            }

            // Property: Sum of browser breakdown should equal total views
            $browserSum = array_sum($browserBreakdown);
            $this->assertEquals($totalViews, $browserSum,
                "Sum of browser breakdown should equal total views for test case $index");

            // Property: All counts should be non-negative
            foreach ($deviceBreakdown as $device => $count) {
                $this->assertGreaterThanOrEqual(0, $count,
                    "Device count for $device should be non-negative");
            }
            foreach ($browserBreakdown as $browser => $count) {
                $this->assertGreaterThanOrEqual(0, $count,
                    "Browser count for $browser should be non-negative");
            }
        }
    }

    /**
     * Property: Views by Date Range Returns Correct Period
     *
     * For any invitation with views, querying views by date range should
     * only return views within that range.
     *
     * **Validates: Requirements 8.3**
     *
     * @test
     */
    public function property_views_by_date_range_returns_correct_period(): void
    {
        $user = User::factory()->create();
        $template = Template::first();
        $invitation = Invitation::factory()->published()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
        ]);

        // Create views across different dates
        $viewDates = [];
        for ($i = 0; $i < 30; $i++) {
            $date = now()->subDays($i);
            $viewDates[] = $date;

            InvitationView::create([
                'invitation_id' => $invitation->id,
                'ip_address' => '192.168.1.' . ($i + 1),
                'user_agent' => 'Mozilla/5.0 Test Browser',
                'device_type' => 'desktop',
                'browser' => 'Chrome',
                'viewed_at' => $date,
            ]);
        }

        // Test different date ranges
        $testRanges = [
            ['start' => now()->subDays(7), 'end' => now(), 'expected_days' => 8],
            ['start' => now()->subDays(14), 'end' => now()->subDays(7), 'expected_days' => 8],
            ['start' => now()->subDays(29), 'end' => now(), 'expected_days' => 30],
            ['start' => now()->subDays(5), 'end' => now()->subDays(2), 'expected_days' => 4],
        ];

        foreach ($testRanges as $index => $range) {
            $views = $this->statisticsService->getViewsByDateRange(
                $invitation,
                $range['start'],
                $range['end']
            );

            // Property: All returned views should be within the date range
            foreach ($views as $view) {
                $viewDate = Carbon::parse($view->date);
                $this->assertTrue(
                    $viewDate->between($range['start']->startOfDay(), $range['end']->endOfDay()),
                    "View date {$view->date} should be within range for test case $index"
                );
            }

            // Property: Sum of counts should match views in range
            $totalInRange = InvitationView::where('invitation_id', $invitation->id)
                ->whereBetween('viewed_at', [$range['start'], $range['end']])
                ->count();

            $sumFromStats = $views->sum('count');
            $this->assertEquals($totalInRange, $sumFromStats,
                "Sum of view counts should match database count for test case $index");
        }
    }

    /**
     * Property: Empty Invitation Has Zero Statistics
     *
     * For any invitation with no views, all statistics should return zero
     * or empty results.
     *
     * **Validates: Requirements 8.2, 8.3, 8.4**
     *
     * @test
     */
    public function property_empty_invitation_has_zero_statistics(): void
    {
        // Test with multiple invitations
        for ($i = 0; $i < 5; $i++) {
            $user = User::factory()->create();
            $template = Template::first();
            $invitation = Invitation::factory()->published()->create([
                'user_id' => $user->id,
                'template_id' => $template->id,
            ]);

            // Property: Total views should be zero
            $totalViews = $this->statisticsService->getTotalViews($invitation);
            $this->assertEquals(0, $totalViews,
                "Total views should be 0 for invitation without views (iteration $i)");

            // Property: Device breakdown should all be zero
            $deviceBreakdown = $this->statisticsService->getDeviceBreakdown($invitation);
            $this->assertEquals(0, $deviceBreakdown['desktop'],
                "Desktop count should be 0 for invitation without views");
            $this->assertEquals(0, $deviceBreakdown['mobile'],
                "Mobile count should be 0 for invitation without views");
            $this->assertEquals(0, $deviceBreakdown['tablet'],
                "Tablet count should be 0 for invitation without views");

            // Property: Browser breakdown should be empty
            $browserBreakdown = $this->statisticsService->getBrowserBreakdown($invitation);
            $this->assertEmpty($browserBreakdown,
                "Browser breakdown should be empty for invitation without views");

            // Property: Views by date range should be empty
            $views = $this->statisticsService->getViewsByDateRange(
                $invitation,
                now()->subDays(30),
                now()
            );
            $this->assertCount(0, $views,
                "Views by date range should be empty for invitation without views");
        }
    }

    /**
     * Property: Statistics Are Consistent Across Multiple Queries
     *
     * For any invitation, querying statistics multiple times should
     * return the same results (idempotency).
     *
     * **Validates: Requirements 8.2, 8.3, 8.4**
     *
     * @test
     */
    public function property_statistics_are_consistent_across_queries(): void
    {
        $user = User::factory()->create();
        $template = Template::first();
        $invitation = Invitation::factory()->published()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
        ]);

        // Create some views
        for ($i = 0; $i < 15; $i++) {
            InvitationView::create([
                'invitation_id' => $invitation->id,
                'ip_address' => '192.168.1.' . ($i + 1),
                'user_agent' => 'Mozilla/5.0 Test Browser',
                'device_type' => ['desktop', 'mobile', 'tablet'][$i % 3],
                'browser' => ['Chrome', 'Firefox', 'Safari'][$i % 3],
                'viewed_at' => now()->subDays($i % 7),
            ]);
        }

        // Query statistics multiple times
        $queries = 5;
        $totalViewsResults = [];
        $deviceBreakdownResults = [];
        $browserBreakdownResults = [];

        for ($i = 0; $i < $queries; $i++) {
            $totalViewsResults[] = $this->statisticsService->getTotalViews($invitation);
            $deviceBreakdownResults[] = $this->statisticsService->getDeviceBreakdown($invitation);
            $browserBreakdownResults[] = $this->statisticsService->getBrowserBreakdown($invitation);
        }

        // Property: All total views queries should return the same result
        $firstTotalViews = $totalViewsResults[0];
        foreach ($totalViewsResults as $index => $result) {
            $this->assertEquals($firstTotalViews, $result,
                "Total views query $index should return same result as first query");
        }

        // Property: All device breakdown queries should return the same result
        $firstDeviceBreakdown = $deviceBreakdownResults[0];
        foreach ($deviceBreakdownResults as $index => $result) {
            $this->assertEquals($firstDeviceBreakdown, $result,
                "Device breakdown query $index should return same result as first query");
        }

        // Property: All browser breakdown queries should return the same result
        $firstBrowserBreakdown = $browserBreakdownResults[0];
        foreach ($browserBreakdownResults as $index => $result) {
            $this->assertEquals($firstBrowserBreakdown, $result,
                "Browser breakdown query $index should return same result as first query");
        }
    }

    /**
     * Property: Statistics Only Include Views for Specific Invitation
     *
     * For any invitation, statistics should only include views for that
     * invitation and not views from other invitations.
     *
     * **Validates: Requirements 8.2, 8.3, 8.4**
     *
     * @test
     */
    public function property_statistics_only_include_specific_invitation_views(): void
    {
        $user = User::factory()->create();
        $template = Template::first();

        // Create multiple invitations
        $invitation1 = Invitation::factory()->published()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
        ]);
        $invitation2 = Invitation::factory()->published()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
        ]);
        $invitation3 = Invitation::factory()->published()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
        ]);

        // Create views for each invitation
        $invitation1ViewCount = 10;
        $invitation2ViewCount = 15;
        $invitation3ViewCount = 20;

        for ($i = 0; $i < $invitation1ViewCount; $i++) {
            InvitationView::create([
                'invitation_id' => $invitation1->id,
                'ip_address' => '192.168.1.' . ($i + 1),
                'user_agent' => 'Mozilla/5.0 Test Browser',
                'device_type' => 'desktop',
                'browser' => 'Chrome',
                'viewed_at' => now(),
            ]);
        }

        for ($i = 0; $i < $invitation2ViewCount; $i++) {
            InvitationView::create([
                'invitation_id' => $invitation2->id,
                'ip_address' => '10.0.0.' . ($i + 1),
                'user_agent' => 'Mozilla/5.0 Test Browser',
                'device_type' => 'mobile',
                'browser' => 'Safari',
                'viewed_at' => now(),
            ]);
        }

        for ($i = 0; $i < $invitation3ViewCount; $i++) {
            InvitationView::create([
                'invitation_id' => $invitation3->id,
                'ip_address' => '172.16.0.' . ($i + 1),
                'user_agent' => 'Mozilla/5.0 Test Browser',
                'device_type' => 'tablet',
                'browser' => 'Firefox',
                'viewed_at' => now(),
            ]);
        }

        // Property: Each invitation should have its own view count
        $views1 = $this->statisticsService->getTotalViews($invitation1);
        $views2 = $this->statisticsService->getTotalViews($invitation2);
        $views3 = $this->statisticsService->getTotalViews($invitation3);

        $this->assertEquals($invitation1ViewCount, $views1,
            "Invitation 1 should have exactly $invitation1ViewCount views");
        $this->assertEquals($invitation2ViewCount, $views2,
            "Invitation 2 should have exactly $invitation2ViewCount views");
        $this->assertEquals($invitation3ViewCount, $views3,
            "Invitation 3 should have exactly $invitation3ViewCount views");

        // Property: Device breakdown should only include views for specific invitation
        $device1 = $this->statisticsService->getDeviceBreakdown($invitation1);
        $this->assertEquals($invitation1ViewCount, $device1['desktop']);
        $this->assertEquals(0, $device1['mobile']);
        $this->assertEquals(0, $device1['tablet']);

        $device2 = $this->statisticsService->getDeviceBreakdown($invitation2);
        $this->assertEquals(0, $device2['desktop']);
        $this->assertEquals($invitation2ViewCount, $device2['mobile']);
        $this->assertEquals(0, $device2['tablet']);

        $device3 = $this->statisticsService->getDeviceBreakdown($invitation3);
        $this->assertEquals(0, $device3['desktop']);
        $this->assertEquals(0, $device3['mobile']);
        $this->assertEquals($invitation3ViewCount, $device3['tablet']);

        // Property: Browser breakdown should only include views for specific invitation
        $browser1 = $this->statisticsService->getBrowserBreakdown($invitation1);
        $this->assertEquals($invitation1ViewCount, $browser1['Chrome']);
        $this->assertArrayNotHasKey('Safari', $browser1);
        $this->assertArrayNotHasKey('Firefox', $browser1);

        $browser2 = $this->statisticsService->getBrowserBreakdown($invitation2);
        $this->assertEquals($invitation2ViewCount, $browser2['Safari']);
        $this->assertArrayNotHasKey('Chrome', $browser2);
        $this->assertArrayNotHasKey('Firefox', $browser2);

        $browser3 = $this->statisticsService->getBrowserBreakdown($invitation3);
        $this->assertEquals($invitation3ViewCount, $browser3['Firefox']);
        $this->assertArrayNotHasKey('Chrome', $browser3);
        $this->assertArrayNotHasKey('Safari', $browser3);
    }
}
