<?php

namespace Tests\Feature;

use App\Models\Gallery;
use App\Models\Invitation;
use App\Models\Template;
use App\Services\TemplateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TemplateRenderingTest extends TestCase
{
    use RefreshDatabase;

    protected TemplateService $templateService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->templateService = new TemplateService();

        // Seed templates
        $this->artisan('db:seed', ['--class' => 'TemplateSeeder']);
    }

    public function test_render_classic_elegant_template_with_invitation_data()
    {
        $template = Template::where('name', 'Classic Elegant')->first();

        $this->assertNotNull($template, 'Classic Elegant template should exist after seeding');

        $dummyInvitation = new \stdClass();
        $dummyInvitation->unique_url = 'test-invitation';

        $data = [
            'invitation' => $dummyInvitation,
            'bride_name' => 'Anisa Rahma',
            'bride_father_name' => 'Bapak Ahmad',
            'bride_mother_name' => 'Ibu Siti',
            'groom_name' => 'Dian Pratama',
            'groom_father_name' => 'Bapak Budi',
            'groom_mother_name' => 'Ibu Dewi',
            'akad_date' => '2026-03-29',
            'akad_time_start' => '08:00',
            'akad_time_end' => '10:00',
            'akad_location' => 'Masjid Al-Ikhlas',
            'reception_date' => '2026-03-29',
            'reception_time_start' => '11:00',
            'reception_time_end' => '14:00',
            'reception_location' => 'Gedung Serbaguna',
            'full_address' => 'Jl. Merdeka No. 123, Jakarta',
            'google_maps_url' => 'https://maps.google.com/?q=-6.2088,106.8456',
            'music_url' => 'https://example.com/music.mp3',
            'galleries' => [],
        ];

        $rendered = $this->templateService->renderTemplate($template, $data);

        // Verify that data is properly bound
        $this->assertStringContainsString('Anisa Rahma', $rendered);
        $this->assertStringContainsString('Dian Pratama', $rendered);
        $this->assertStringContainsString('Bapak Ahmad', $rendered);
        $this->assertStringContainsString('Ibu Siti', $rendered);
        $this->assertStringContainsString('Bapak Budi', $rendered);
        $this->assertStringContainsString('Ibu Dewi', $rendered);
        $this->assertStringContainsString('Masjid Al-Ikhlas', $rendered);
        $this->assertStringContainsString('Gedung Serbaguna', $rendered);
        $this->assertStringContainsString('Jl. Merdeka No. 123, Jakarta', $rendered);
        $this->assertStringContainsString('https://example.com/music.mp3', $rendered);
    }

    public function test_render_template_with_galleries()
    {
        $template = Template::where('name', 'Classic Elegant')->first();

        $galleries = collect([
            (object)['photo_path' => 'photos/photo1.jpg'],
            (object)['photo_path' => 'photos/photo2.jpg'],
            (object)['photo_path' => 'photos/photo3.jpg'],
        ]);

        $dummyInvitation = new \stdClass();
        $dummyInvitation->unique_url = 'test-invitation';

        $data = [
            'invitation' => $dummyInvitation,
            'bride_name' => 'Anisa',
            'groom_name' => 'Dian',
            'bride_father_name' => 'Ahmad',
            'bride_mother_name' => 'Siti',
            'groom_father_name' => 'Budi',
            'groom_mother_name' => 'Dewi',
            'akad_date' => '2026-03-29',
            'akad_time_start' => '08:00',
            'akad_time_end' => '10:00',
            'akad_location' => 'Masjid',
            'reception_date' => '2026-03-29',
            'reception_time_start' => '11:00',
            'reception_time_end' => '14:00',
            'reception_location' => 'Gedung',
            'full_address' => 'Jakarta',
            'google_maps_url' => 'https://maps.google.com',
            'music_url' => null,
            'galleries' => $galleries,
        ];

        $rendered = $this->templateService->renderTemplate($template, $data);

        // Verify galleries are rendered
        $this->assertStringContainsString('photos/photo1.jpg', $rendered);
        $this->assertStringContainsString('photos/photo2.jpg', $rendered);
        $this->assertStringContainsString('photos/photo3.jpg', $rendered);
    }

    public function test_render_template_without_optional_music()
    {
        $template = Template::where('name', 'Classic Elegant')->first();

        $dummyInvitation = new \stdClass();
        $dummyInvitation->unique_url = 'test-invitation';

        $data = [
            'invitation' => $dummyInvitation,
            'bride_name' => 'Anisa',
            'groom_name' => 'Dian',
            'bride_father_name' => 'Ahmad',
            'bride_mother_name' => 'Siti',
            'groom_father_name' => 'Budi',
            'groom_mother_name' => 'Dewi',
            'akad_date' => '2026-03-29',
            'akad_time_start' => '08:00',
            'akad_time_end' => '10:00',
            'akad_location' => 'Masjid',
            'reception_date' => '2026-03-29',
            'reception_time_start' => '11:00',
            'reception_time_end' => '14:00',
            'reception_location' => 'Gedung',
            'full_address' => 'Jakarta',
            'google_maps_url' => null,
            'music_url' => null,
            'galleries' => [],
        ];

        $rendered = $this->templateService->renderTemplate($template, $data);

        // Should render successfully without music
        $this->assertStringContainsString('Anisa', $rendered);
        $this->assertStringContainsString('Dian', $rendered);
    }

    public function test_all_seeded_templates_can_be_rendered()
    {
        $templates = $this->templateService->getAllTemplates();

        $this->assertGreaterThanOrEqual(3, $templates->count(), 'Should have at least 3 templates');

        $dummyInvitation = new \stdClass();
        $dummyInvitation->unique_url = 'test-invitation';

        $data = [
            'invitation' => $dummyInvitation,
            'bride_name' => 'Anisa',
            'groom_name' => 'Dian',
            'bride_father_name' => 'Ahmad',
            'bride_mother_name' => 'Siti',
            'groom_father_name' => 'Budi',
            'groom_mother_name' => 'Dewi',
            'akad_date' => '2026-03-29',
            'akad_time_start' => '08:00',
            'akad_time_end' => '10:00',
            'akad_location' => 'Masjid',
            'reception_date' => '2026-03-29',
            'reception_time_start' => '11:00',
            'reception_time_end' => '14:00',
            'reception_location' => 'Gedung',
            'full_address' => 'Jakarta',
            'google_maps_url' => 'https://maps.google.com',
            'music_url' => null,
            'galleries' => [],
        ];

        foreach ($templates as $template) {
            $rendered = $this->templateService->renderTemplate($template, $data);

            $this->assertNotEmpty($rendered, "Template {$template->name} should render successfully");
            $this->assertStringContainsString('Anisa', $rendered);
            $this->assertStringContainsString('Dian', $rendered);
        }
    }
}
