<?php

namespace Tests\Feature;

use App\Models\Gallery;
use App\Models\Invitation;
use App\Models\Template;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicInvitationViewTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\TemplateSeeder::class);
    }

    /** @test */
    public function public_invitation_view_renders_with_all_data()
    {
        $user = User::factory()->create();
        $template = Template::first();

        $invitation = Invitation::factory()->published()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,

            'bride_name' => 'Sarah',
            'groom_name' => 'John',
            'akad_date' => '2026-06-15',
            'akad_time_start' => '09:00',
            'google_maps_url' => 'https://maps.google.com/?q=-6.200000,106.816666',
            'full_address' => 'Jl. Test No. 123, Jakarta',
            'music_url' => 'https://example.com/music.mp3',
        ]);

        Gallery::factory()->count(3)->create([
            'invitation_id' => $invitation->id,
        ]);

        $response = $this->get("/i/{$invitation->unique_url}");

        $response->assertStatus(200);
        $response->assertSee('Sarah');
        $response->assertSee('John');
        $response->assertSee('2026-06-15');
        $response->assertSee('Jl. Test No. 123, Jakarta');
    }

    /** @test */
    public function public_invitation_includes_countdown_timer_script()
    {
        $user = User::factory()->create();
        $template = Template::first();

        $invitation = Invitation::factory()->published()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,

            'akad_date' => '2026-06-15',
            'akad_time_start' => '09:00',
        ]);

        $response = $this->get("/i/{$invitation->unique_url}");

        $response->assertStatus(200);
        // Check for countdown elements
        $response->assertSee('countdown', false);
        $response->assertSee('days', false);
        $response->assertSee('hours', false);
        $response->assertSee('minutes', false);
        $response->assertSee('seconds', false);
        // Check for countdown script
        $response->assertSee('targetDate', false);
    }

    /** @test */
    public function public_invitation_includes_google_maps_integration()
    {
        $user = User::factory()->create();
        $template = Template::first();

        $invitation = Invitation::factory()->published()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,

            'google_maps_url' => 'https://maps.google.com/?q=-6.200000,106.816666',
            'full_address' => 'Jl. Test No. 123, Jakarta',
        ]);

        $response = $this->get("/i/{$invitation->unique_url}");

        $response->assertStatus(200);
        // Check for Google Maps iframe
        $response->assertSee('google.com/maps/embed', false);
        // Check for Google Maps link
        $response->assertSee($invitation->google_maps_url, false);
    }

    /** @test */
    public function public_invitation_includes_background_music_player()
    {
        $user = User::factory()->create();
        $template = Template::first();

        $invitation = Invitation::factory()->published()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,

            'music_url' => 'https://example.com/wedding-music.mp3',
        ]);

        $response = $this->get("/i/{$invitation->unique_url}");

        $response->assertStatus(200);
        // Check for audio element
        $response->assertSee('<audio', false);
        $response->assertSee('bgMusic', false);
        $response->assertSee($invitation->music_url, false);
        // Check for music control
        $response->assertSee('music-control', false);
        $response->assertSee('toggleMusic', false);
    }

    /** @test */
    public function public_invitation_works_without_optional_music()
    {
        $user = User::factory()->create();
        $template = Template::first();

        $invitation = Invitation::factory()->published()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,

            'music_url' => null,
        ]);

        $response = $this->get("/i/{$invitation->unique_url}");

        $response->assertStatus(200);
        // Should still render without errors
        $response->assertSee($invitation->bride_name);
    }

    /** @test */
    public function public_invitation_includes_responsive_meta_tags()
    {
        $user = User::factory()->create();
        $template = Template::first();

        $invitation = Invitation::factory()->published()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,

        ]);

        $response = $this->get("/i/{$invitation->unique_url}");

        $response->assertStatus(200);
        // Check for viewport meta tag
        $response->assertSee('viewport', false);
        $response->assertSee('width=device-width', false);
        $response->assertSee('initial-scale=1.0', false);
    }

    /** @test */
    public function public_invitation_includes_social_media_meta_tags()
    {
        $user = User::factory()->create();
        $template = Template::first();

        $invitation = Invitation::factory()->published()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,

            'bride_name' => 'Sarah',
            'groom_name' => 'John',
        ]);

        Gallery::factory()->create([
            'invitation_id' => $invitation->id,
        ]);

        $response = $this->get("/i/{$invitation->unique_url}");

        $response->assertStatus(200);
        // Check for Open Graph tags
        $response->assertSee('og:title', false);
        $response->assertSee('og:description', false);
        $response->assertSee('og:image', false);
        // Check for Twitter tags
        $response->assertSee('twitter:card', false);
        $response->assertSee('twitter:title', false);
    }

    /** @test */
    public function public_invitation_displays_gallery_photos()
    {
        $user = User::factory()->create();
        $template = Template::first();

        $invitation = Invitation::factory()->published()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,

        ]);

        $galleries = Gallery::factory()->count(5)->create([
            'invitation_id' => $invitation->id,
        ]);

        $response = $this->get("/i/{$invitation->unique_url}");

        $response->assertStatus(200);
        // Check that gallery section exists
        $response->assertSee('gallery', false);
        // Check that photos are rendered
        foreach ($galleries as $gallery) {
            $response->assertSee($gallery->photo_path, false);
        }
    }

    /** @test */
    public function public_invitation_handles_missing_google_maps_url()
    {
        $user = User::factory()->create();
        $template = Template::first();

        $invitation = Invitation::factory()->published()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,

            'google_maps_url' => null,
            'full_address' => 'Jl. Test No. 123, Jakarta',
        ]);

        $response = $this->get("/i/{$invitation->unique_url}");

        $response->assertStatus(200);
        // Should still show address
        $response->assertSee('Jl. Test No. 123, Jakarta');
    }

    /** @test */
    public function public_invitation_includes_all_event_details()
    {
        $user = User::factory()->create();
        $template = Template::first();

        $invitation = Invitation::factory()->published()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,

            'akad_date' => '2026-06-15',
            'akad_time_start' => '09:00',
            'akad_time_end' => '11:00',
            'akad_location' => 'Masjid Al-Ikhlas',
            'reception_date' => '2026-06-15',
            'reception_time_start' => '18:00',
            'reception_time_end' => '21:00',
            'reception_location' => 'Grand Ballroom Hotel',
        ]);

        $response = $this->get("/i/{$invitation->unique_url}");

        $response->assertStatus(200);
        $response->assertSee('Masjid Al-Ikhlas');
        $response->assertSee('Grand Ballroom Hotel');
        $response->assertSee('09:00');
        $response->assertSee('18:00');
    }
}
