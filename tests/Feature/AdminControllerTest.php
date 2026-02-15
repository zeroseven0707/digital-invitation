<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\InvitationView;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        // Create regular user
        $this->regularUser = User::factory()->create([
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);
    }

    public function test_admin_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    public function test_non_admin_cannot_access_dashboard(): void
    {
        $response = $this->actingAs($this->regularUser)->get(route('admin.dashboard'));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error', 'You do not have permission to access this page.');
    }

    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_dashboard_displays_correct_statistics(): void
    {
        // Create test data
        User::factory()->count(5)->create();
        Invitation::factory()->count(10)->create();

        // Create some views for 3 invitations
        $invitations = Invitation::take(3)->get();
        foreach ($invitations as $invitation) {
            InvitationView::factory()->count(2)->create([
                'invitation_id' => $invitation->id,
            ]);
        }

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);

        // Verify the view has the correct data
        $response->assertViewHas('platformStats');
        $response->assertViewHas('userGrowth');
        $response->assertViewHas('invitationGrowth');
        $response->assertViewHas('viewGrowth');
        $response->assertViewHas('topUsers');
        $response->assertViewHas('topInvitations');

        // Verify actual counts
        $platformStats = $response->viewData('platformStats');
        $this->assertEquals(User::count(), $platformStats['totalUsers']);
        $this->assertEquals(Invitation::count(), $platformStats['totalInvitations']);
        $this->assertEquals(InvitationView::count(), $platformStats['totalViews']);
    }

    public function test_dashboard_displays_recent_activity(): void
    {
        // Create old data (more than 30 days ago)
        User::factory()->create(['created_at' => now()->subDays(40)]);
        Invitation::factory()->create(['created_at' => now()->subDays(40)]);

        // Create recent data (within 30 days)
        $recentUsers = User::factory()->count(3)->create(['created_at' => now()->subDays(10)]);
        $recentInvitations = Invitation::factory()->count(5)->create(['created_at' => now()->subDays(10)]);

        // Create recent views
        foreach ($recentInvitations->take(2) as $invitation) {
            InvitationView::factory()->count(3)->create([
                'invitation_id' => $invitation->id,
                'viewed_at' => now()->subDays(5),
            ]);
        }

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);

        // Verify growth data is present
        $response->assertViewHas('userGrowth');
        $response->assertViewHas('invitationGrowth');
        $response->assertViewHas('viewGrowth');

        $userGrowth = $response->viewData('userGrowth');
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $userGrowth);
        $this->assertGreaterThan(0, $userGrowth->count());
    }

    public function test_dashboard_displays_published_invitations_count(): void
    {
        // Create invitations with different statuses
        Invitation::factory()->count(3)->create(['status' => 'published']);
        Invitation::factory()->count(2)->create(['status' => 'draft']);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);

        $platformStats = $response->viewData('platformStats');
        $expectedPublished = Invitation::where('status', 'published')->count();
        $this->assertEquals($expectedPublished, $platformStats['publishedInvitations']);
    }

    public function test_dashboard_shows_all_required_statistics(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);

        // Verify all required view variables are present
        $response->assertViewHas('platformStats');
        $response->assertViewHas('userGrowth');
        $response->assertViewHas('invitationGrowth');
        $response->assertViewHas('viewGrowth');
        $response->assertViewHas('topUsers');
        $response->assertViewHas('topInvitations');
    }
}

