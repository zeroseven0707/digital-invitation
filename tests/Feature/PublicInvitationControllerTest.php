<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\Template;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicInvitationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_published_invitation_by_unique_url(): void
    {
        // Arrange
        $user = User::factory()->create();
        $template = Template::factory()->create();
        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'unique_url' => 'test-unique-url-123',
            'status' => 'published',
        ]);

        // Act
        $response = $this->get(route('public.invitation', ['uniqueUrl' => 'test-unique-url-123']));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('public.invitation');
        $response->assertViewHas('invitation', function ($viewInvitation) use ($invitation) {
            return $viewInvitation->id === $invitation->id;
        });
    }

    public function test_returns_404_for_invalid_unique_url(): void
    {
        // Act
        $response = $this->get(route('public.invitation', ['uniqueUrl' => 'non-existent-url']));

        // Assert
        $response->assertStatus(404);
    }

    public function test_returns_404_for_unpublished_invitation(): void
    {
        // Arrange
        $user = User::factory()->create();
        $template = Template::factory()->create();
        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'unique_url' => 'draft-invitation-url',
            'status' => 'draft',
        ]);

        // Act
        $response = $this->get(route('public.invitation', ['uniqueUrl' => 'draft-invitation-url']));

        // Assert
        $response->assertStatus(404);
    }

    public function test_returns_404_for_unpublished_status_invitation(): void
    {
        // Arrange
        $user = User::factory()->create();
        $template = Template::factory()->create();
        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'unique_url' => 'unpublished-invitation-url',
            'status' => 'unpublished',
        ]);

        // Act
        $response = $this->get(route('public.invitation', ['uniqueUrl' => 'unpublished-invitation-url']));

        // Assert
        $response->assertStatus(404);
    }

    public function test_loads_invitation_with_relationships(): void
    {
        // Arrange
        $user = User::factory()->create();
        $template = Template::factory()->create();
        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'unique_url' => 'test-with-relations',
            'status' => 'published',
        ]);

        // Act
        $response = $this->get(route('public.invitation', ['uniqueUrl' => 'test-with-relations']));

        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('invitation', function ($viewInvitation) {
            return $viewInvitation->relationLoaded('template') &&
                   $viewInvitation->relationLoaded('galleries') &&
                   $viewInvitation->relationLoaded('guests');
        });
    }
}
