<?php

namespace Tests\Unit;

use App\Models\Guest;
use App\Models\Invitation;
use App\Models\InvitationView;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_statistics_calculation_with_no_data(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('statistics', function ($statistics) {
            return $statistics['total_invitations'] === 0 &&
                   $statistics['total_guests'] === 0 &&
                   $statistics['total_views'] === 0;
        });
    }

    public function test_dashboard_only_shows_user_own_invitations(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Invitation::factory()->count(3)->create(['user_id' => $user1->id]);
        Invitation::factory()->count(5)->create(['user_id' => $user2->id]);

        $this->actingAs($user1);
        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('statistics', function ($statistics) {
            return $statistics['total_invitations'] === 3;
        });
    }

    public function test_dashboard_counts_guests_correctly(): void
    {
        $user = User::factory()->create();
        $invitation1 = Invitation::factory()->create(['user_id' => $user->id]);
        $invitation2 = Invitation::factory()->create(['user_id' => $user->id]);

        Guest::factory()->count(5)->create(['invitation_id' => $invitation1->id]);
        Guest::factory()->count(3)->create(['invitation_id' => $invitation2->id]);

        $this->actingAs($user);
        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('statistics', function ($statistics) {
            return $statistics['total_guests'] === 8;
        });
    }

    public function test_dashboard_counts_views_correctly(): void
    {
        $user = User::factory()->create();
        $invitation1 = Invitation::factory()->create(['user_id' => $user->id]);
        $invitation2 = Invitation::factory()->create(['user_id' => $user->id]);

        InvitationView::factory()->count(10)->create(['invitation_id' => $invitation1->id]);
        InvitationView::factory()->count(7)->create(['invitation_id' => $invitation2->id]);

        $this->actingAs($user);
        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('statistics', function ($statistics) {
            return $statistics['total_views'] === 17;
        });
    }

    public function test_dashboard_does_not_count_other_users_data(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $invitation1 = Invitation::factory()->create(['user_id' => $user1->id]);
        $invitation2 = Invitation::factory()->create(['user_id' => $user2->id]);

        Guest::factory()->count(5)->create(['invitation_id' => $invitation1->id]);
        Guest::factory()->count(10)->create(['invitation_id' => $invitation2->id]);

        InvitationView::factory()->count(3)->create(['invitation_id' => $invitation1->id]);
        InvitationView::factory()->count(20)->create(['invitation_id' => $invitation2->id]);

        $this->actingAs($user1);
        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('statistics', function ($statistics) {
            return $statistics['total_invitations'] === 1 &&
                   $statistics['total_guests'] === 5 &&
                   $statistics['total_views'] === 3;
        });
    }

    public function test_dashboard_handles_large_numbers(): void
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        Guest::factory()->count(100)->create(['invitation_id' => $invitation->id]);
        InvitationView::factory()->count(1000)->create(['invitation_id' => $invitation->id]);

        $this->actingAs($user);
        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('statistics', function ($statistics) {
            return $statistics['total_guests'] === 100 &&
                   $statistics['total_views'] === 1000;
        });
    }

    public function test_dashboard_with_mixed_invitation_statuses(): void
    {
        $user = User::factory()->create();

        Invitation::factory()->count(2)->create(['user_id' => $user->id, 'status' => 'draft']);
        Invitation::factory()->count(3)->create(['user_id' => $user->id, 'status' => 'published']);
        Invitation::factory()->count(1)->create(['user_id' => $user->id, 'status' => 'unpublished']);

        $this->actingAs($user);
        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('statistics', function ($statistics) {
            return $statistics['total_invitations'] === 6;
        });
    }

    public function test_dashboard_requires_authentication(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }
}
