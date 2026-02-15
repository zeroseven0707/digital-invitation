<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\Rsvp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RsvpControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_submit_rsvp_for_published_invitation(): void
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->published()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->post(route('rsvp.store', $invitation->unique_url), [
            'name' => 'John Doe',
            'message' => 'Congratulations! Wishing you both a lifetime of happiness.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('rsvps', [
            'invitation_id' => $invitation->id,
            'name' => 'John Doe',
            'message' => 'Congratulations! Wishing you both a lifetime of happiness.',
        ]);
    }

    public function test_guest_cannot_submit_rsvp_for_draft_invitation(): void
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'status' => 'draft',
            'unique_url' => 'test-draft-invitation',
        ]);

        $response = $this->post(route('rsvp.store', $invitation->unique_url), [
            'name' => 'John Doe',
            'message' => 'Congratulations!',
        ]);

        $response->assertNotFound();
    }

    public function test_rsvp_requires_name_and_message(): void
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->published()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->post(route('rsvp.store', $invitation->unique_url), []);

        $response->assertSessionHasErrors(['name', 'message']);
    }

    public function test_user_can_view_rsvp_list_for_their_invitation(): void
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
        ]);

        Rsvp::factory()->count(3)->create([
            'invitation_id' => $invitation->id,
        ]);

        $response = $this->actingAs($user)->get(route('rsvps.index', $invitation->id));

        $response->assertOk();
        $response->assertViewIs('rsvps.index');
        $response->assertViewHas('rsvps');
    }

    public function test_user_cannot_view_rsvp_list_for_other_users_invitation(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $invitation = Invitation::factory()->create([
            'user_id' => $user1->id,
        ]);

        $response = $this->actingAs($user2)->get(route('rsvps.index', $invitation->id));

        $response->assertForbidden();
    }

    public function test_guest_must_be_authenticated_to_view_rsvp_list(): void
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('rsvps.index', $invitation->id));

        $response->assertRedirect(route('login'));
    }
}
