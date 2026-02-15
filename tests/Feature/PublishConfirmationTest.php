<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\Template;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublishConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_invitation_shows_url_display(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();
        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'status' => 'published',
            'unique_url' => 'test-unique-url-123',
        ]);

        $response = $this->actingAs($user)->get(route('invitations.show', $invitation->id));

        $response->assertStatus(200);
        $response->assertSee('URL Undangan');
        $response->assertSee('Undangan Anda telah dipublikasikan');
        $response->assertSee(url('/i/' . $invitation->unique_url));
        $response->assertSee('Salin');
        $response->assertSee('Bagikan via WhatsApp');
        $response->assertSee('Bagikan via Email');
    }

    public function test_draft_invitation_does_not_show_url_display(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();
        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'status' => 'draft',
            'unique_url' => null,
        ]);

        $response = $this->actingAs($user)->get(route('invitations.show', $invitation->id));

        $response->assertStatus(200);
        $response->assertDontSee('URL Undangan');
        $response->assertDontSee('Bagikan via WhatsApp');
    }

    public function test_publish_shows_success_notification(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();
        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($user)->post(route('invitations.publish', $invitation->id));

        $response->assertRedirect(route('invitations.show', $invitation->id));
        $response->assertSessionHas('success', 'Undangan berhasil dipublikasikan.');
        $response->assertSessionHas('unique_url');
    }

    public function test_published_invitation_shows_special_notification(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();
        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'status' => 'draft',
        ]);

        // Publish the invitation
        $this->actingAs($user)->post(route('invitations.publish', $invitation->id));

        // Follow the redirect to see the notification
        $response = $this->actingAs($user)->get(route('invitations.show', $invitation->id));

        $response->assertStatus(200);
        $response->assertSee('Selamat!');
        $response->assertSee('Undangan Anda telah dipublikasikan');
    }

    public function test_whatsapp_share_link_is_correct(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();
        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'status' => 'published',
            'unique_url' => 'test-url-456',
        ]);

        $response = $this->actingAs($user)->get(route('invitations.show', $invitation->id));

        $expectedUrl = 'https://wa.me/?text=' . urlencode('Anda diundang ke pernikahan kami! ' . url('/i/' . $invitation->unique_url));
        $response->assertSee($expectedUrl, false);
    }

    public function test_email_share_link_is_correct(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();
        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'status' => 'published',
            'unique_url' => 'test-url-789',
            'bride_name' => 'Anisa',
            'groom_name' => 'Dian',
        ]);

        $response = $this->actingAs($user)->get(route('invitations.show', $invitation->id));

        $expectedSubject = urlencode('Undangan Pernikahan Anisa & Dian');
        $expectedBody = urlencode('Anda diundang ke pernikahan kami! Lihat undangan di: ' . url('/i/' . $invitation->unique_url));

        $response->assertSee('mailto:?subject=' . $expectedSubject, false);
        $response->assertSee($expectedBody, false);
    }

    public function test_copy_button_javascript_is_present(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();
        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'status' => 'published',
            'unique_url' => 'test-url-copy',
        ]);

        $response = $this->actingAs($user)->get(route('invitations.show', $invitation->id));

        $response->assertSee('copyToClipboard', false);
        $response->assertSee('navigator.clipboard.writeText', false);
        $response->assertSee('Tersalin!', false);
    }
}
