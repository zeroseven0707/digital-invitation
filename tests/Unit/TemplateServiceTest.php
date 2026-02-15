<?php

namespace Tests\Unit;

use App\Models\Template;
use App\Services\TemplateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TemplateServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TemplateService $templateService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->templateService = new TemplateService();
    }

    public function test_get_all_templates_returns_only_active_templates()
    {
        // Create active and inactive templates
        Template::factory()->create(['is_active' => true, 'name' => 'Active Template 1']);
        Template::factory()->create(['is_active' => true, 'name' => 'Active Template 2']);
        Template::factory()->create(['is_active' => false, 'name' => 'Inactive Template']);

        $templates = $this->templateService->getAllTemplates();

        $this->assertCount(2, $templates);
        $this->assertTrue($templates->every(fn($t) => $t->is_active === true));
    }

    public function test_get_template_returns_template_by_id()
    {
        $template = Template::factory()->create(['is_active' => true]);

        $result = $this->templateService->getTemplate($template->id);

        $this->assertNotNull($result);
        $this->assertEquals($template->id, $result->id);
    }

    public function test_get_template_returns_null_for_inactive_template()
    {
        $template = Template::factory()->create(['is_active' => false]);

        $result = $this->templateService->getTemplate($template->id);

        $this->assertNull($result);
    }

    public function test_render_template_binds_data_correctly()
    {
        Storage::fake('local');

        // Create a simple template HTML with Blade syntax
        $templateHtml = '<h1>{{ $bride_name }} & {{ $groom_name }}</h1><p>Date: {{ $akad_date }}</p>';
        Storage::put('templates/test/template.html', $templateHtml);

        $template = Template::factory()->create([
            'is_active' => true,
            'html_path' => 'templates/test/template.html',
        ]);

        $data = [
            'bride_name' => 'Anisa',
            'groom_name' => 'Dian',
            'akad_date' => '2026-03-29',
        ];

        $rendered = $this->templateService->renderTemplate($template, $data);

        $this->assertStringContainsString('Anisa & Dian', $rendered);
        $this->assertStringContainsString('Date: 2026-03-29', $rendered);
    }

    public function test_render_template_throws_exception_for_missing_file()
    {
        Storage::fake('local');

        $template = Template::factory()->create([
            'is_active' => true,
            'html_path' => 'templates/nonexistent/template.html',
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Template file not found');

        $this->templateService->renderTemplate($template, []);
    }

    public function test_render_template_handles_blade_directives()
    {
        Storage::fake('local');

        // Template with conditional Blade directive
        $templateHtml = '@if($music_url)<audio src="{{ $music_url }}"></audio>@endif<p>No music</p>';
        Storage::put('templates/test/template.html', $templateHtml);

        $template = Template::factory()->create([
            'is_active' => true,
            'html_path' => 'templates/test/template.html',
        ]);

        // Test with music_url
        $dataWithMusic = ['music_url' => 'https://example.com/music.mp3'];
        $renderedWithMusic = $this->templateService->renderTemplate($template, $dataWithMusic);
        $this->assertStringContainsString('<audio src="https://example.com/music.mp3"></audio>', $renderedWithMusic);

        // Test without music_url
        $dataWithoutMusic = ['music_url' => null];
        $renderedWithoutMusic = $this->templateService->renderTemplate($template, $dataWithoutMusic);
        $this->assertStringNotContainsString('<audio', $renderedWithoutMusic);
    }
}
