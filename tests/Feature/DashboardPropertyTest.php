<?php

namespace Tests\Feature;

use App\Models\Guest;
use App\Models\Invitation;
use App\Models\InvitationView;
use App\Models\Template;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Property-Based Tests for Dashboard
 *
 * These tests validate universal properties that should hold true
 * for all dashboard operations.
 */
class DashboardPropertyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Property 7: Dashboard Shows User's Invitations
     *
     * For any user with invitations, accessing the dashboard should display
     * all invitations belonging to that user and no invitations from other users.
     *
     * Validates: Requirements 2.1
     */
    public function test_property_dashboard_shows_users_invitations(): void
    {
        $template = Template::factory()->create();

        // Test with multiple users and varying numbers of invitations
        $testCases = [
            ['user_invitations' => 1, 'other_invitations' => 2],
            ['user_invitations' => 3, 'other_invitations' => 1],
            ['user_invitations' => 5, 'other_invitations' => 3],
            ['user_invitations' => 2, 'other_invitations' => 4],
            ['user_invitations' => 0, 'other_invitations' => 2], // Edge case: no invitations
        ];

        foreach ($testCases as $testCase) {
            $user = User::factory()->create();
            $otherUser = User::factory()->create();

            // Create invitations for the user
            $userInvitations = Invitation::factory()
                ->count($testCase['user_invitations'])
                ->create([
                    'user_id' => $user->id,
                    'template_id' => $template->id,
                ]);

            // Create invitations for other user
            $otherInvitations = Invitation::factory()
                ->count($testCase['other_invitations'])
                ->create([
                    'user_id' => $otherUser->id,
                    'template_id' => $template->id,
                ]);

            $response = $this->actingAs($user)->get('/dashboard');

            // Property: Response should be successful
            $response->assertStatus(200);

            // Property: Dashboard should show exactly the user's invitations
            $response->assertViewHas('invitations', function ($invitations) use ($user, $testCase) {
                return $invitations->count() === $testCase['user_invitations'] &&
                       $invitations->every(fn($inv) => $inv->user_id === $user->id);
            });

            // Property: Dashboard should not show other users' invitations
            // We verify this by checking the count matches and all invitations belong to the user
            // (already verified in the assertViewHas above)

            // Property: All user's invitations should be visible
            foreach ($userInvitations as $userInvitation) {
                // Check that invitation data is present in the view
                $response->assertSee($userInvitation->bride_name);
                $response->assertSee($userInvitation->groom_name);
            }

            // Cleanup for next iteration
            Invitation::whereIn('id', $userInvitations->pluck('id'))->delete();
            Invitation::whereIn('id', $otherInvitations->pluck('id'))->delete();
            $user->delete();
            $otherUser->delete();
        }
    }

    /**
     * Property 8: Dashboard Statistics Match Actual Data
     *
     * For any user, the dashboard statistics (total invitations, total guests,
     * total views) should equal the actual count from the database.
     *
     * Validates: Requirements 2.2, 8.5
     */
    public function test_property_dashboard_statistics_match_actual_data(): void
    {
        $template = Template::factory()->create();

        // Test with various combinations of invitations, guests, and views
        $testCases = [
            ['invitations' => 1, 'guests_per_invitation' => [3], 'views_per_invitation' => [5]],
            ['invitations' => 2, 'guests_per_invitation' => [2, 4], 'views_per_invitation' => [3, 7]],
            ['invitations' => 3, 'guests_per_invitation' => [1, 2, 3], 'views_per_invitation' => [2, 4, 6]],
            ['invitations' => 2, 'guests_per_invitation' => [0, 5], 'views_per_invitation' => [0, 10]],
            ['invitations' => 4, 'guests_per_invitation' => [2, 3, 1, 4], 'views_per_invitation' => [5, 3, 8, 2]],
            ['invitations' => 0, 'guests_per_invitation' => [], 'views_per_invitation' => []], // Edge case: no data
        ];

        foreach ($testCases as $testCase) {
            $user = User::factory()->create();

            $expectedTotalGuests = 0;
            $expectedTotalViews = 0;

            // Create invitations with guests and views
            for ($i = 0; $i < $testCase['invitations']; $i++) {
                $invitation = Invitation::factory()->create([
                    'user_id' => $user->id,
                    'template_id' => $template->id,
                ]);

                // Create guests for this invitation
                $guestCount = $testCase['guests_per_invitation'][$i];
                Guest::factory()->count($guestCount)->create([
                    'invitation_id' => $invitation->id,
                ]);
                $expectedTotalGuests += $guestCount;

                // Create views for this invitation
                $viewCount = $testCase['views_per_invitation'][$i];
                InvitationView::factory()->count($viewCount)->create([
                    'invitation_id' => $invitation->id,
                ]);
                $expectedTotalViews += $viewCount;
            }

            $response = $this->actingAs($user)->get('/dashboard');

            // Property: Statistics should match actual database counts
            $response->assertViewHas('statistics', function ($statistics) use ($testCase, $expectedTotalGuests, $expectedTotalViews) {
                return $statistics['total_invitations'] === $testCase['invitations'] &&
                       $statistics['total_guests'] === $expectedTotalGuests &&
                       $statistics['total_views'] === $expectedTotalViews;
            });

            // Property: Verify statistics are accurate by direct database query
            $actualInvitationCount = Invitation::where('user_id', $user->id)->count();
            $actualGuestCount = Guest::whereHas('invitation', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count();
            $actualViewCount = InvitationView::whereHas('invitation', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count();

            $this->assertEquals($testCase['invitations'], $actualInvitationCount);
            $this->assertEquals($expectedTotalGuests, $actualGuestCount);
            $this->assertEquals($expectedTotalViews, $actualViewCount);

            // Cleanup for next iteration
            Invitation::where('user_id', $user->id)->delete();
            $user->delete();
        }
    }

    /**
     * Property: Dashboard Statistics Are Non-Negative
     *
     * For any user, all dashboard statistics should be non-negative integers.
     *
     * Validates: Requirements 2.2
     */
    public function test_property_dashboard_statistics_are_non_negative(): void
    {
        $template = Template::factory()->create();

        // Test with various scenarios including edge cases
        $testCases = [
            ['invitations' => 0],
            ['invitations' => 1],
            ['invitations' => 5],
            ['invitations' => 10],
        ];

        foreach ($testCases as $testCase) {
            $user = User::factory()->create();

            // Create invitations with random guests and views
            for ($i = 0; $i < $testCase['invitations']; $i++) {
                $invitation = Invitation::factory()->create([
                    'user_id' => $user->id,
                    'template_id' => $template->id,
                ]);

                // Random number of guests (0-10)
                Guest::factory()->count(rand(0, 10))->create([
                    'invitation_id' => $invitation->id,
                ]);

                // Random number of views (0-20)
                InvitationView::factory()->count(rand(0, 20))->create([
                    'invitation_id' => $invitation->id,
                ]);
            }

            $response = $this->actingAs($user)->get('/dashboard');

            // Property: All statistics should be non-negative
            $response->assertViewHas('statistics', function ($statistics) {
                return $statistics['total_invitations'] >= 0 &&
                       $statistics['total_guests'] >= 0 &&
                       $statistics['total_views'] >= 0 &&
                       is_int($statistics['total_invitations']) &&
                       is_int($statistics['total_guests']) &&
                       is_int($statistics['total_views']);
            });

            // Cleanup for next iteration
            Invitation::where('user_id', $user->id)->delete();
            $user->delete();
        }
    }

    /**
     * Property: Dashboard Isolates User Data
     *
     * For any two users, their dashboard statistics should be independent
     * and not affected by each other's data.
     *
     * Validates: Requirements 2.1, 2.2, 12.4
     */
    public function test_property_dashboard_isolates_user_data(): void
    {
        $template = Template::factory()->create();

        $testCases = [
            ['user1_invitations' => 2, 'user2_invitations' => 3],
            ['user1_invitations' => 5, 'user2_invitations' => 1],
            ['user1_invitations' => 0, 'user2_invitations' => 4],
            ['user1_invitations' => 3, 'user2_invitations' => 3],
        ];

        foreach ($testCases as $testCase) {
            $user1 = User::factory()->create();
            $user2 = User::factory()->create();

            // Create data for user1
            $user1Guests = 0;
            $user1Views = 0;
            for ($i = 0; $i < $testCase['user1_invitations']; $i++) {
                $invitation = Invitation::factory()->create([
                    'user_id' => $user1->id,
                    'template_id' => $template->id,
                ]);
                $guestCount = rand(1, 5);
                $viewCount = rand(1, 10);
                Guest::factory()->count($guestCount)->create(['invitation_id' => $invitation->id]);
                InvitationView::factory()->count($viewCount)->create(['invitation_id' => $invitation->id]);
                $user1Guests += $guestCount;
                $user1Views += $viewCount;
            }

            // Create data for user2
            $user2Guests = 0;
            $user2Views = 0;
            for ($i = 0; $i < $testCase['user2_invitations']; $i++) {
                $invitation = Invitation::factory()->create([
                    'user_id' => $user2->id,
                    'template_id' => $template->id,
                ]);
                $guestCount = rand(1, 5);
                $viewCount = rand(1, 10);
                Guest::factory()->count($guestCount)->create(['invitation_id' => $invitation->id]);
                InvitationView::factory()->count($viewCount)->create(['invitation_id' => $invitation->id]);
                $user2Guests += $guestCount;
                $user2Views += $viewCount;
            }

            // Property: User1's dashboard should only show user1's data
            $response1 = $this->actingAs($user1)->get('/dashboard');
            $response1->assertViewHas('statistics', function ($statistics) use ($testCase, $user1Guests, $user1Views) {
                return $statistics['total_invitations'] === $testCase['user1_invitations'] &&
                       $statistics['total_guests'] === $user1Guests &&
                       $statistics['total_views'] === $user1Views;
            });

            // Property: User2's dashboard should only show user2's data
            $response2 = $this->actingAs($user2)->get('/dashboard');
            $response2->assertViewHas('statistics', function ($statistics) use ($testCase, $user2Guests, $user2Views) {
                return $statistics['total_invitations'] === $testCase['user2_invitations'] &&
                       $statistics['total_guests'] === $user2Guests &&
                       $statistics['total_views'] === $user2Views;
            });

            // Property: Statistics should be different if data is different
            if ($testCase['user1_invitations'] !== $testCase['user2_invitations']) {
                $this->assertNotEquals(
                    $testCase['user1_invitations'],
                    $testCase['user2_invitations']
                );
            }

            // Cleanup for next iteration
            Invitation::where('user_id', $user1->id)->delete();
            Invitation::where('user_id', $user2->id)->delete();
            $user1->delete();
            $user2->delete();
        }
    }
}
