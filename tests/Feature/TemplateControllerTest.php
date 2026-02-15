<?php

namespace Tests\Feature;

use App\Models\Template;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TemplateControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\TemplateSeeder::class);
    }

    public function test_authenticated_user_can_view_templates_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('templates.index'));

        $response->assertStatus(200);
        $response->assertViewIs('templates.index');
        $response->assertViewHas('templates');
    }

    public function test_guest_cannot_view_templates_index(): void
    {
        $response = $this->get(route('templates.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_templates_index_displays_active_templates(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('templates.index'));

        $templates = Template::where('is_active', true)->get();

        $response->assertStatus(200);

        foreach ($templates as $template) {
            $response->assertSee($template->name);
        }
    }

    public function test_authenticated_user_can_view_template_preview(): void
    {
        $user = User::factory()->create();
        $template = Template::where('is_active', true)->first();

        $response = $this->actingAs($user)->get(route('templates.show', $template->id));

        $response->assertStatus(200);
        $response->assertViewIs('templates.show');
        $response->assertViewHas('template');
        $response->assertSee($template->name);
    }

    public function test_guest_cannot_view_template_preview(): void
    {
        $template = Template::where('is_active', true)->first();

        $response = $this->get(route('templates.show', $template->id));

        $response->assertRedirect(route('login'));
    }

    public function test_viewing_nonexistent_template_returns_404(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('templates.show', 99999));

        $response->assertStatus(404);
    }

    public function test_inactive_template_returns_404(): void
    {
        $user = User::factory()->create();

        // Create an inactive template
        $template = Template::factory()->create(['is_active' => false]);

        $response = $this->actingAs($user)->get(route('templates.show', $template->id));

        $response->assertStatus(404);
    }
}
