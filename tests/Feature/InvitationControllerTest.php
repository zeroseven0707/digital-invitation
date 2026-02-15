<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\Template;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_page_displays_templates(): void
    {
        $user = User::factory()->create();
        $templates = Template::factory()->count(3)->create();

        $response = $this->actingAs($user)->get(route('invitations.create'));

        $response->assertStatus(200);
        $response->assertViewIs('invitations.create');
        $response->assertViewHas('templates');

        foreach ($templates as $template) {
            $response->assertSee($template->name);
        }
    }

    public function test_store_creates_invitation_as_draft(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        $data = [
            'template_id' => $template->id,
            'bride_name' => 'Anisa',
            'bride_father_name' => 'Bapak Anisa',
            'bride_mother_name' => 'Ibu Anisa',
            'groom_name' => 'Dian',
            'groom_father_name' => 'Bapak Dian',
            'groom_mother_name' => 'Ibu Dian',
            'akad_date' => now()->addMonths(3)->format('Y-m-d'),
            'akad_time_start' => '08:00',
            'akad_time_end' => '10:00',
            'akad_location' => 'Masjid Al-Ikhlas',
            'reception_date' => now()->addMonths(3)->format('Y-m-d'),
            'reception_time_start' => '18:00',
            'reception_time_end' => '21:00',
            'reception_location' => 'Gedung Serbaguna',
            'full_address' => 'Jl. Raya No. 123, Jakarta',
            'google_maps_url' => 'https://maps.google.com/?q=-6.2088,106.8456',
            'music_url' => 'https://example.com/music.mp3',
        ];

        $response = $this->actingAs($user)->post(route('invitations.store'), $data);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('invitations', [
            'user_id' => $user->id,
            'template_id' => $template->id,
            'bride_name' => 'Anisa',
            'groom_name' => 'Dian',
            'status' => 'draft',
        ]);
    }

    public function test_store_requires_authentication(): void
    {
        $template = Template::factory()->create();

        $data = [
            'template_id' => $template->id,
            'bride_name' => 'Anisa',
            'groom_name' => 'Dian',
            'akad_date' => now()->addMonths(3)->format('Y-m-d'),
            'akad_time_start' => '08:00',
            'akad_time_end' => '10:00',
            'akad_location' => 'Masjid',
            'reception_date' => now()->addMonths(3)->format('Y-m-d'),
            'reception_time_start' => '18:00',
            'reception_time_end' => '21:00',
            'reception_location' => 'Gedung',
            'full_address' => 'Jl. Raya No. 123',
        ];

        $response = $this->post(route('invitations.store'), $data);

        $response->assertRedirect(route('login'));
    }

    public function test_store_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('invitations.store'), []);

        $response->assertSessionHasErrors([
            'template_id',
            'bride_name',
            'groom_name',
            'akad_date',
            'akad_time_start',
            'akad_time_end',
            'akad_location',
            'reception_date',
            'reception_time_start',
            'reception_time_end',
            'reception_location',
            'full_address',
        ]);
    }

    public function test_store_validates_template_exists(): void
    {
        $user = User::factory()->create();

        $data = [
            'template_id' => 999, // Non-existent template
            'bride_name' => 'Anisa',
            'groom_name' => 'Dian',
            'akad_date' => now()->addMonths(3)->format('Y-m-d'),
            'akad_time_start' => '08:00',
            'akad_time_end' => '10:00',
            'akad_location' => 'Masjid',
            'reception_date' => now()->addMonths(3)->format('Y-m-d'),
            'reception_time_start' => '18:00',
            'reception_time_end' => '21:00',
            'reception_location' => 'Gedung',
            'full_address' => 'Jl. Raya No. 123',
        ];

        $response = $this->actingAs($user)->post(route('invitations.store'), $data);

        $response->assertSessionHasErrors('template_id');
    }

    public function test_store_validates_date_not_in_past(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        $data = [
            'template_id' => $template->id,
            'bride_name' => 'Anisa',
            'groom_name' => 'Dian',
            'akad_date' => now()->subDays(1)->format('Y-m-d'), // Past date
            'akad_time_start' => '08:00',
            'akad_time_end' => '10:00',
            'akad_location' => 'Masjid',
            'reception_date' => now()->addMonths(3)->format('Y-m-d'),
            'reception_time_start' => '18:00',
            'reception_time_end' => '21:00',
            'reception_location' => 'Gedung',
            'full_address' => 'Jl. Raya No. 123',
        ];

        $response = $this->actingAs($user)->post(route('invitations.store'), $data);

        $response->assertSessionHasErrors('akad_date');
    }

    public function test_store_validates_time_end_after_start(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        $data = [
            'template_id' => $template->id,
            'bride_name' => 'Anisa',
            'groom_name' => 'Dian',
            'akad_date' => now()->addMonths(3)->format('Y-m-d'),
            'akad_time_start' => '10:00',
            'akad_time_end' => '08:00', // End before start
            'akad_location' => 'Masjid',
            'reception_date' => now()->addMonths(3)->format('Y-m-d'),
            'reception_time_start' => '18:00',
            'reception_time_end' => '21:00',
            'reception_location' => 'Gedung',
            'full_address' => 'Jl. Raya No. 123',
        ];

        $response = $this->actingAs($user)->post(route('invitations.store'), $data);

        $response->assertSessionHasErrors('akad_time_end');
    }

    public function test_show_displays_invitation_details(): void
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('invitations.show', $invitation->id));

        $response->assertStatus(200);
        $response->assertViewIs('invitations.show');
        $response->assertViewHas('invitation');
        $response->assertSee($invitation->bride_name);
        $response->assertSee($invitation->groom_name);
    }

    public function test_show_prevents_viewing_other_users_invitations(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user2->id]);

        $response = $this->actingAs($user1)->get(route('invitations.show', $invitation->id));

        $response->assertStatus(404);
    }

    public function test_edit_displays_invitation_form(): void
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('invitations.edit', $invitation->id));

        $response->assertStatus(200);
        $response->assertViewIs('invitations.edit');
        $response->assertViewHas('invitation');
        $response->assertViewHas('templates');
        $response->assertSee($invitation->bride_name);
        $response->assertSee($invitation->groom_name);
    }

    public function test_edit_prevents_editing_other_users_invitations(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user2->id]);

        $response = $this->actingAs($user1)->get(route('invitations.edit', $invitation->id));

        $response->assertStatus(404);
    }

    public function test_update_modifies_invitation(): void
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        $data = [
            'bride_name' => 'Updated Bride Name',
            'bride_father_name' => 'Updated Father',
            'bride_mother_name' => 'Updated Mother',
            'groom_name' => 'Updated Groom Name',
            'groom_father_name' => 'Updated Father',
            'groom_mother_name' => 'Updated Mother',
            'akad_date' => now()->addMonths(6)->format('Y-m-d'),
            'akad_time_start' => '09:00',
            'akad_time_end' => '11:00',
            'akad_location' => 'Updated Akad Location',
            'reception_date' => now()->addMonths(6)->format('Y-m-d'),
            'reception_time_start' => '19:00',
            'reception_time_end' => '22:00',
            'reception_location' => 'Updated Reception Location',
            'full_address' => 'Updated Full Address',
            'google_maps_url' => 'https://maps.google.com/?q=-6.2088,106.8456',
            'music_url' => 'https://example.com/updated-music.mp3',
        ];

        $response = $this->actingAs($user)->put(route('invitations.update', $invitation->id), $data);

        $response->assertRedirect(route('invitations.show', $invitation->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('invitations', [
            'id' => $invitation->id,
            'bride_name' => 'Updated Bride Name',
            'groom_name' => 'Updated Groom Name',
            'akad_location' => 'Updated Akad Location',
        ]);
    }

    public function test_update_prevents_updating_other_users_invitations(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user2->id]);

        $data = [
            'bride_name' => 'Hacker Bride',
            'groom_name' => 'Hacker Groom',
            'akad_date' => now()->addMonths(3)->format('Y-m-d'),
            'akad_time_start' => '08:00',
            'akad_time_end' => '10:00',
            'akad_location' => 'Hacker Location',
            'reception_date' => now()->addMonths(3)->format('Y-m-d'),
            'reception_time_start' => '18:00',
            'reception_time_end' => '21:00',
            'reception_location' => 'Hacker Reception',
            'full_address' => 'Hacker Address',
        ];

        $response = $this->actingAs($user1)->put(route('invitations.update', $invitation->id), $data);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('invitations', [
            'id' => $invitation->id,
            'bride_name' => 'Hacker Bride',
        ]);
    }

    public function test_update_validates_required_fields(): void
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->put(route('invitations.update', $invitation->id), []);

        $response->assertSessionHasErrors([
            'bride_name',
            'groom_name',
            'akad_date',
            'akad_time_start',
            'akad_time_end',
            'akad_location',
            'reception_date',
            'reception_time_start',
            'reception_time_end',
            'reception_location',
            'full_address',
        ]);
    }

    public function test_update_can_change_template(): void
    {
        $user = User::factory()->create();
        $template1 = Template::factory()->create();
        $template2 = Template::factory()->create();
        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'template_id' => $template1->id,
        ]);

        $data = [
            'template_id' => $template2->id,
            'bride_name' => $invitation->bride_name,
            'groom_name' => $invitation->groom_name,
            'akad_date' => now()->addMonths(3)->format('Y-m-d'),
            'akad_time_start' => $invitation->akad_time_start,
            'akad_time_end' => $invitation->akad_time_end,
            'akad_location' => $invitation->akad_location,
            'reception_date' => now()->addMonths(3)->format('Y-m-d'),
            'reception_time_start' => $invitation->reception_time_start,
            'reception_time_end' => $invitation->reception_time_end,
            'reception_location' => $invitation->reception_location,
            'full_address' => $invitation->full_address,
        ];

        $response = $this->actingAs($user)->put(route('invitations.update', $invitation->id), $data);

        $response->assertRedirect(route('invitations.show', $invitation->id));

        $this->assertDatabaseHas('invitations', [
            'id' => $invitation->id,
            'template_id' => $template2->id,
            'bride_name' => $invitation->bride_name, // Data preserved
        ]);
    }

    public function test_destroy_deletes_invitation(): void
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete(route('invitations.destroy', $invitation->id));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('invitations', [
            'id' => $invitation->id,
        ]);
    }

    public function test_destroy_prevents_deleting_other_users_invitations(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user2->id]);

        $response = $this->actingAs($user1)->delete(route('invitations.destroy', $invitation->id));

        $response->assertStatus(404);

        $this->assertDatabaseHas('invitations', [
            'id' => $invitation->id,
        ]);
    }

    public function test_destroy_requires_authentication(): void
    {
        $invitation = Invitation::factory()->create();

        $response = $this->delete(route('invitations.destroy', $invitation->id));

        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('invitations', [
            'id' => $invitation->id,
        ]);
    }

    public function test_preview_displays_invitation_with_template(): void
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Mock the TemplateService to avoid file system dependency
        $this->mock(\App\Services\TemplateService::class, function ($mock) {
            $mock->shouldReceive('renderTemplate')
                ->once()
                ->andReturn('<html><body>Mocked Template</body></html>');
        });

        $response = $this->actingAs($user)->get(route('invitations.preview', $invitation->id));

        $response->assertStatus(200);
        $response->assertViewIs('invitations.preview');
        $response->assertViewHas('invitation');
        $response->assertViewHas('renderedTemplate');
        $response->assertSee('Mocked Template', false);
    }

    public function test_preview_shows_action_buttons(): void
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'status' => 'draft',
        ]);

        // Mock the TemplateService
        $this->mock(\App\Services\TemplateService::class, function ($mock) {
            $mock->shouldReceive('renderTemplate')
                ->once()
                ->andReturn('<html><body>Template</body></html>');
        });

        $response = $this->actingAs($user)->get(route('invitations.preview', $invitation->id));

        $response->assertStatus(200);
        $response->assertSee('Kembali ke Edit');
        $response->assertSee('Publikasikan');
    }

    public function test_preview_shows_unpublish_button_for_published_invitation(): void
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'status' => 'published',
            'unique_url' => 'test-unique-url',
        ]);

        // Mock the TemplateService
        $this->mock(\App\Services\TemplateService::class, function ($mock) {
            $mock->shouldReceive('renderTemplate')
                ->once()
                ->andReturn('<html><body>Template</body></html>');
        });

        $response = $this->actingAs($user)->get(route('invitations.preview', $invitation->id));

        $response->assertStatus(200);
        $response->assertSee('Kembali ke Edit');
        $response->assertSee('Unpublish');
        $response->assertDontSee('Publikasikan');
    }

    public function test_preview_prevents_viewing_other_users_invitations(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user2->id]);

        $response = $this->actingAs($user1)->get(route('invitations.preview', $invitation->id));

        $response->assertStatus(404);
    }

    public function test_preview_requires_authentication(): void
    {
        $invitation = Invitation::factory()->create();

        $response = $this->get(route('invitations.preview', $invitation->id));

        $response->assertRedirect(route('login'));
    }

    public function test_publish_generates_unique_url_and_changes_status(): void
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'status' => 'draft',
            'unique_url' => null,
        ]);

        $response = $this->actingAs($user)->post(route('invitations.publish', $invitation->id));

        $response->assertRedirect(route('invitations.show', $invitation->id));
        $response->assertSessionHas('success');
        $response->assertSessionHas('unique_url');

        $invitation->refresh();

        $this->assertEquals('published', $invitation->status);
        $this->assertNotNull($invitation->unique_url);
        $this->assertEquals(32, strlen($invitation->unique_url));
    }

    public function test_publish_prevents_publishing_other_users_invitations(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $invitation = Invitation::factory()->create([
            'user_id' => $user2->id,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($user1)->post(route('invitations.publish', $invitation->id));

        $response->assertStatus(404);

        $invitation->refresh();
        $this->assertEquals('draft', $invitation->status);
    }

    public function test_publish_requires_authentication(): void
    {
        $invitation = Invitation::factory()->create(['status' => 'draft']);

        $response = $this->post(route('invitations.publish', $invitation->id));

        $response->assertRedirect(route('login'));

        $invitation->refresh();
        $this->assertEquals('draft', $invitation->status);
    }

    public function test_unpublish_changes_status_to_unpublished(): void
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'status' => 'published',
            'unique_url' => 'test-unique-url-12345678901234567890',
        ]);

        $response = $this->actingAs($user)->post(route('invitations.unpublish', $invitation->id));

        $response->assertRedirect(route('invitations.show', $invitation->id));
        $response->assertSessionHas('success');

        $invitation->refresh();

        $this->assertEquals('unpublished', $invitation->status);
        // unique_url should remain (not deleted)
        $this->assertNotNull($invitation->unique_url);
    }

    public function test_unpublish_prevents_unpublishing_other_users_invitations(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $invitation = Invitation::factory()->create([
            'user_id' => $user2->id,
            'status' => 'published',
        ]);

        $response = $this->actingAs($user1)->post(route('invitations.unpublish', $invitation->id));

        $response->assertStatus(404);

        $invitation->refresh();
        $this->assertEquals('published', $invitation->status);
    }

    public function test_unpublish_requires_authentication(): void
    {
        $invitation = Invitation::factory()->create(['status' => 'published']);

        $response = $this->post(route('invitations.unpublish', $invitation->id));

        $response->assertRedirect(route('login'));

        $invitation->refresh();
        $this->assertEquals('published', $invitation->status);
    }
}
