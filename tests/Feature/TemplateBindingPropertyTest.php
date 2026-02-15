<?php

namespace Tests\Feature;

use App\Models\Template;
use App\Services\TemplateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Property-Based Tests for Template Binding
 *
 * These tests validate that template data binding works correctly
 * for all types of input data.
 */
class TemplateBindingPropertyTest extends TestCase
{
    use RefreshDatabase;

    private TemplateService $templateService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->templateService = new TemplateService();
    }

    /**
     * Property 31: Template Binding Renders Data Correctly
     *
     * For any valid invitation data, the template should correctly
     * replace all placeholders with the provided data.
     *
     * Validates: Requirements 9.5, 9.6
     */
    public function test_property_template_binding_renders_data_correctly(): void
    {
        // Create a test template
        $template = Template::factory()->create();

        $testCases = [
            [
                'bride_name' => 'Sarah Johnson',
                'groom_name' => 'Michael Smith',
                'bride_father_name' => 'Robert Johnson',
                'bride_mother_name' => 'Mary Johnson',
                'groom_father_name' => 'David Smith',
                'groom_mother_name' => 'Linda Smith',
                'akad_date' => '2024-06-15',
                'akad_time_start' => '10:00',
                'akad_time_end' => '11:00',
                'akad_location' => 'Grand Mosque',
                'reception_date' => '2024-06-15',
                'reception_time_start' => '18:00',
                'reception_time_end' => '21:00',
                'reception_location' => 'Grand Ballroom',
                'full_address' => '123 Main Street, City',
                'google_maps_url' => 'https://maps.google.com/test',
                'music_url' => 'https://music.com/song.mp3',
            ],
            [
                'bride_name' => 'Emma Wilson',
                'groom_name' => 'James Brown',
                'bride_father_name' => 'Thomas Wilson',
                'bride_mother_name' => 'Patricia Wilson',
                'groom_father_name' => 'Christopher Brown',
                'groom_mother_name' => 'Jennifer Brown',
                'akad_date' => '2024-07-20',
                'akad_time_start' => '09:00',
                'akad_time_end' => '10:30',
                'akad_location' => 'City Chapel',
                'reception_date' => '2024-07-20',
                'reception_time_start' => '19:00',
                'reception_time_end' => '22:00',
                'reception_location' => 'Garden Venue',
                'full_address' => '456 Oak Avenue, Town',
                'google_maps_url' => 'https://maps.google.com/venue',
                'music_url' => 'https://music.com/wedding.mp3',
            ],
            [
                'bride_name' => 'Olivia Davis',
                'groom_name' => 'William Martinez',
                'bride_father_name' => 'Richard Davis',
                'bride_mother_name' => 'Susan Davis',
                'groom_father_name' => 'Joseph Martinez',
                'groom_mother_name' => 'Nancy Martinez',
                'akad_date' => '2024-08-10',
                'akad_time_start' => '11:00',
                'akad_time_end' => '12:00',
                'akad_location' => 'Beach Resort',
                'reception_date' => '2024-08-10',
                'reception_time_start' => '17:00',
                'reception_time_end' => '20:00',
                'reception_location' => 'Seaside Hall',
                'full_address' => '789 Beach Road, Coast',
                'google_maps_url' => 'https://maps.google.com/beach',
                'music_url' => 'https://music.com/romantic.mp3',
            ],
        ];

        foreach ($testCases as $data) {
            $rendered = $this->templateService->renderTemplate($template, $data);

            // Property: All placeholders should be replaced
            $this->assertStringNotContainsString('{{bride_name}}', $rendered);
            $this->assertStringNotContainsString('{{groom_name}}', $rendered);
            $this->assertStringNotContainsString('{{bride_father_name}}', $rendered);
            $this->assertStringNotContainsString('{{bride_mother_name}}', $rendered);
            $this->assertStringNotContainsString('{{groom_father_name}}', $rendered);
            $this->assertStringNotContainsString('{{groom_mother_name}}', $rendered);
            $this->assertStringNotContainsString('{{akad_date}}', $rendered);
            $this->assertStringNotContainsString('{{akad_time_start}}', $rendered);
            $this->assertStringNotContainsString('{{akad_time_end}}', $rendered);
            $this->assertStringNotContainsString('{{akad_location}}', $rendered);
            $this->assertStringNotContainsString('{{reception_date}}', $rendered);
            $this->assertStringNotContainsString('{{reception_time_start}}', $rendered);
            $this->assertStringNotContainsString('{{reception_time_end}}', $rendered);
            $this->assertStringNotContainsString('{{reception_location}}', $rendered);
            $this->assertStringNotContainsString('{{full_address}}', $rendered);
            $this->assertStringNotContainsString('{{google_maps_url}}', $rendered);
            $this->assertStringNotContainsString('{{music_url}}', $rendered);

            // Property: Actual data should be present in rendered output
            $this->assertStringContainsString($data['bride_name'], $rendered);
            $this->assertStringContainsString($data['groom_name'], $rendered);
            $this->assertStringContainsString($data['akad_location'], $rendered);
            $this->assertStringContainsString($data['reception_location'], $rendered);
            $this->assertStringContainsString($data['full_address'], $rendered);
        }
    }

    /**
     * Property: Template Binding Handles Special Characters
     *
     * Template binding should correctly handle special characters
     * in the data without breaking the HTML structure.
     */
    public function test_property_template_binding_handles_special_characters(): void
    {
        $template = Template::factory()->create();

        $testCases = [
            [
                'bride_name' => "Sarah O'Connor",
                'groom_name' => 'Michael "Mike" Smith',
                'akad_location' => 'St. Mary\'s Church',
                'full_address' => '123 Main St. & Oak Ave.',
            ],
            [
                'bride_name' => 'María García',
                'groom_name' => 'José Rodríguez',
                'akad_location' => 'Iglesia de Nuestra Señora',
                'full_address' => 'Calle Principal #456',
            ],
            [
                'bride_name' => 'Amélie Dubois',
                'groom_name' => 'François Martin',
                'akad_location' => 'Église Saint-Pierre',
                'full_address' => '789 Rue de la Paix',
            ],
        ];

        foreach ($testCases as $data) {
            // Add required fields
            $data = array_merge([
                'bride_father_name' => 'Father',
                'bride_mother_name' => 'Mother',
                'groom_father_name' => 'Father',
                'groom_mother_name' => 'Mother',
                'akad_date' => '2024-06-15',
                'akad_time_start' => '10:00',
                'akad_time_end' => '11:00',
                'reception_date' => '2024-06-15',
                'reception_time_start' => '18:00',
                'reception_time_end' => '21:00',
                'reception_location' => 'Hall',
                'google_maps_url' => 'https://maps.google.com',
                'music_url' => 'https://music.com/song.mp3',
            ], $data);

            $rendered = $this->templateService->renderTemplate($template, $data);

            // Property: Special characters should be preserved (HTML escaped)
            // Laravel escapes special characters for security, so we check for escaped versions
            $this->assertStringContainsString(htmlspecialchars($data['bride_name'], ENT_QUOTES, 'UTF-8'), $rendered);
            $this->assertStringContainsString(htmlspecialchars($data['groom_name'], ENT_QUOTES, 'UTF-8'), $rendered);

            // Property: HTML structure should remain valid
            $this->assertNotEmpty($rendered);
        }
    }

    /**
     * Property: Template Binding Handles Empty Optional Fields
     *
     * Template binding should gracefully handle empty optional fields
     * without breaking the template.
     */
    public function test_property_template_binding_handles_empty_optional_fields(): void
    {
        $template = Template::factory()->create();

        $testCases = [
            [
                'bride_name' => 'Sarah',
                'groom_name' => 'Michael',
                'bride_father_name' => null,
                'bride_mother_name' => null,
                'groom_father_name' => null,
                'groom_mother_name' => null,
                'google_maps_url' => null,
                'music_url' => null,
            ],
            [
                'bride_name' => 'Emma',
                'groom_name' => 'James',
                'bride_father_name' => '',
                'bride_mother_name' => '',
                'groom_father_name' => '',
                'groom_mother_name' => '',
                'google_maps_url' => '',
                'music_url' => '',
            ],
        ];

        foreach ($testCases as $data) {
            // Add required fields
            $data = array_merge([
                'akad_date' => '2024-06-15',
                'akad_time_start' => '10:00',
                'akad_time_end' => '11:00',
                'akad_location' => 'Location',
                'reception_date' => '2024-06-15',
                'reception_time_start' => '18:00',
                'reception_time_end' => '21:00',
                'reception_location' => 'Hall',
                'full_address' => 'Address',
            ], $data);

            $rendered = $this->templateService->renderTemplate($template, $data);

            // Property: Template should render without errors
            $this->assertNotEmpty($rendered);

            // Property: Required fields should be present
            $this->assertStringContainsString($data['bride_name'], $rendered);
            $this->assertStringContainsString($data['groom_name'], $rendered);
            $this->assertStringContainsString($data['akad_location'], $rendered);
        }
    }

    /**
     * Property: Template Binding is Idempotent
     *
     * Rendering the same data multiple times should produce
     * the same output.
     */
    public function test_property_template_binding_is_idempotent(): void
    {
        $template = Template::factory()->create();

        $data = [
            'bride_name' => 'Sarah',
            'groom_name' => 'Michael',
            'bride_father_name' => 'Robert',
            'bride_mother_name' => 'Mary',
            'groom_father_name' => 'David',
            'groom_mother_name' => 'Linda',
            'akad_date' => '2024-06-15',
            'akad_time_start' => '10:00',
            'akad_time_end' => '11:00',
            'akad_location' => 'Church',
            'reception_date' => '2024-06-15',
            'reception_time_start' => '18:00',
            'reception_time_end' => '21:00',
            'reception_location' => 'Hall',
            'full_address' => 'Address',
            'google_maps_url' => 'https://maps.google.com',
            'music_url' => 'https://music.com/song.mp3',
        ];

        // Render multiple times
        $rendered1 = $this->templateService->renderTemplate($template, $data);
        $rendered2 = $this->templateService->renderTemplate($template, $data);
        $rendered3 = $this->templateService->renderTemplate($template, $data);

        // Property: All renders should be identical
        $this->assertEquals($rendered1, $rendered2);
        $this->assertEquals($rendered2, $rendered3);
        $this->assertEquals($rendered1, $rendered3);
    }

    /**
     * Property: Template Binding Preserves HTML Structure
     *
     * After binding data, the HTML structure should remain valid
     * and well-formed.
     */
    public function test_property_template_binding_preserves_html_structure(): void
    {
        $template = Template::factory()->create();

        $testCases = [
            [
                'bride_name' => 'Sarah',
                'groom_name' => 'Michael',
            ],
            [
                'bride_name' => 'Emma',
                'groom_name' => 'James',
            ],
            [
                'bride_name' => 'Olivia',
                'groom_name' => 'William',
            ],
        ];

        foreach ($testCases as $data) {
            // Add required fields
            $data = array_merge([
                'bride_father_name' => 'Father',
                'bride_mother_name' => 'Mother',
                'groom_father_name' => 'Father',
                'groom_mother_name' => 'Mother',
                'akad_date' => '2024-06-15',
                'akad_time_start' => '10:00',
                'akad_time_end' => '11:00',
                'akad_location' => 'Location',
                'reception_date' => '2024-06-15',
                'reception_time_start' => '18:00',
                'reception_time_end' => '21:00',
                'reception_location' => 'Hall',
                'full_address' => 'Address',
                'google_maps_url' => 'https://maps.google.com',
                'music_url' => 'https://music.com/song.mp3',
            ], $data);

            $rendered = $this->templateService->renderTemplate($template, $data);

            // Property: HTML should be well-formed (basic check)
            $this->assertNotEmpty($rendered);

            // Property: Should not contain unclosed tags (basic validation)
            $openTags = substr_count($rendered, '<div');
            $closeTags = substr_count($rendered, '</div>');
            $this->assertEquals($openTags, $closeTags, 'HTML structure should be balanced');
        }
    }
}
