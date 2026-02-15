<?php

namespace Tests\Feature;

use App\Models\Guest;
use App\Models\Invitation;
use App\Models\InvitationView;
use App\Models\Template;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_requires_authentication(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_dashboard_displays_user_invitations(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        $invitation1 = Invitation::factory()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'bride_name' => 'Anisa',
            'groom_name' => 'Dian',
        ]);

        $invitation2 = Invitation::factory()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'bride_name' => 'Siti',
            'groom_name' => 'Ahmad',
        ]);

        // Create invitation for another user (should not be displayed)
        $otherUser = User::factory()->create();
        Invitation::factory()->create([
            'user_id' => $otherUser->id,
            'template_id' => $template->id,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Anisa');
        $response->assertSee('Dian');
        $response->assertSee('Siti');
        $response->assertSee('Ahmad');
        $response->assertViewHas('invitations', function ($invitations) use ($user) {
            return $invitations->count() === 2 &&
                   $invitations->every(fn($inv) => $inv->user_id === $user->id);
        });
    }

    public function test_dashboard_calculates_statistics_correctly(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        // Create 2 invitations
        $invitation1 = Invitation::factory()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
        ]);

        $invitation2 = Invitation::factory()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
        ]);

        // Create guests for invitations
        Guest::factory()->count(3)->create(['invitation_id' => $invitation1->id]);
        Guest::factory()->count(2)->create(['invitation_id' => $invitation2->id]);

        // Create views for invitations
        InvitationView::factory()->count(5)->create(['invitation_id' => $invitation1->id]);
        InvitationView::factory()->count(3)->create(['invitation_id' => $invitation2->id]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('statistics', function ($statistics) {
            return $statistics['total_invitations'] === 2 &&
                   $statistics['total_guests'] === 5 &&
                   $statistics['total_views'] === 8;
        });
    }

    public function test_dashboard_shows_empty_state_when_no_invitations(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Belum ada undangan');
        $response->assertViewHas('statistics', function ($statistics) {
            return $statistics['total_invitations'] === 0 &&
                   $statistics['total_guests'] === 0 &&
                   $statistics['total_views'] === 0;
        });
    }

    public function test_dashboard_displays_invitation_status(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        $draftInvitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'status' => 'draft',
        ]);

        $publishedInvitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'status' => 'published',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Draft');
        $response->assertSee('Published');
    }

    public function test_dashboard_displays_template_name(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create(['name' => 'Classic Elegant']);

        Invitation::factory()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Classic Elegant');
    }
}
