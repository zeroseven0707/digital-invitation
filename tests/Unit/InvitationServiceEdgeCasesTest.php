<?php

namespace Tests\Unit;

use App\Models\Invitation;
use App\Models\Template;
use App\Models\User;
use App\Services\InvitationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationServiceEdgeCasesTest extends TestCase
{
    use RefreshDatabase;

    private InvitationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new InvitationService();
    }

    public function test_create_invitation_with_minimal_required_data(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        $data = [
            'template_id' => $template->id,
            'bride_name' => 'Jane',
            'groom_name' => 'John',
        ];

        $invitation = $this->service->createInvitation($data, $user->id);

        $this->assertInstanceOf(Invitation::class, $invitation);
        $this->assertEquals('draft', $invitation->status);
        $this->assertEquals($user->id, $invitation->user_id);
    }

    public function test_create_invitation_with_all_optional_fields(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        $data = [
            'template_id' => $template->id,
            'bride_name' => 'Jane',
            'bride_father_name' => 'Father',
            'bride_mother_name' => 'Mother',
            'groom_name' => 'John',
            'groom_father_name' => 'Father',
            'groom_mother_name' => 'Mother',
            'akad_date' => '2024-12-25',
            'akad_time_start' => '10:00',
            'akad_time_end' => '12:00',
            'akad_location' => 'Mosque',
            'reception_date' => '2024-12-26',
            'reception_time_start' => '18:00',
            'reception_time_end' => '22:00',
            'reception_location' => 'Hotel',
            'full_address' => '123 Main St',
            'google_maps_url' => 'https://maps.google.com',
            'music_url' => 'https://example.com/music.mp3',
        ];

        $invitation = $this->service->createInvitation($data, $user->id);

        $this->assertEquals('Father', $invitation->bride_father_name);
        $this->assertEquals('https://maps.google.com', $invitation->google_maps_url);
        $this->assertEquals('https://example.com/music.mp3', $invitation->music_url);
    }

    public function test_update_invitation_with_partial_data(): void
    {
        $invitation = Invitation::factory()->create([
            'bride_name' => 'Jane',
            'groom_name' => 'John',
            'akad_location' => 'Old Location',
        ]);

        $updatedInvitation = $this->service->updateInvitation($invitation, [
            'akad_location' => 'New Location',
        ]);

        $this->assertEquals('New Location', $updatedInvitation->akad_location);
        $this->assertEquals('Jane', $updatedInvitation->bride_name);
        $this->assertEquals('John', $updatedInvitation->groom_name);
    }

    public function test_publish_invitation_generates_unique_url_each_time(): void
    {
        $invitation1 = Invitation::factory()->create(['status' => 'draft']);
        $invitation2 = Invitation::factory()->create(['status' => 'draft']);

        $url1 = $this->service->publishInvitation($invitation1);
        $url2 = $this->service->publishInvitation($invitation2);

        $this->assertNotEquals($url1, $url2);
        $this->assertEquals(32, strlen($url1));
        $this->assertEquals(32, strlen($url2));
    }

    public function test_publish_already_published_invitation_generates_new_url(): void
    {
        $invitation = Invitation::factory()->create([
            'status' => 'published',
            'unique_url' => 'existing-url',
        ]);

        $url = $this->service->publishInvitation($invitation);

        // Service always generates new URL
        $this->assertNotEquals('existing-url', $url);
        $this->assertEquals(32, strlen($url));
        $invitation->refresh();
        $this->assertEquals('published', $invitation->status);
    }

    public function test_unpublish_invitation_changes_status_but_keeps_url(): void
    {
        $invitation = Invitation::factory()->create([
            'status' => 'published',
            'unique_url' => 'test-url',
        ]);

        $this->service->unpublishInvitation($invitation);

        $invitation->refresh();
        $this->assertEquals('unpublished', $invitation->status);
        $this->assertEquals('test-url', $invitation->unique_url);
    }

    public function test_change_template_preserves_all_invitation_data(): void
    {
        $oldTemplate = Template::factory()->create();
        $newTemplate = Template::factory()->create();

        $invitation = Invitation::factory()->create([
            'template_id' => $oldTemplate->id,
            'bride_name' => 'Jane',
            'groom_name' => 'John',
            'akad_location' => 'Mosque',
            'reception_location' => 'Hotel',
        ]);

        $updatedInvitation = $this->service->changeTemplate($invitation, $newTemplate->id);

        $this->assertEquals($newTemplate->id, $updatedInvitation->template_id);
        $this->assertEquals('Jane', $updatedInvitation->bride_name);
        $this->assertEquals('John', $updatedInvitation->groom_name);
        $this->assertEquals('Mosque', $updatedInvitation->akad_location);
        $this->assertEquals('Hotel', $updatedInvitation->reception_location);
    }

    public function test_delete_invitation_removes_from_database(): void
    {
        $invitation = Invitation::factory()->create();
        $invitationId = $invitation->id;

        $this->service->deleteInvitation($invitation);

        $this->assertNull(Invitation::find($invitationId));
    }

    public function test_create_invitation_with_special_characters_in_names(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        $data = [
            'template_id' => $template->id,
            'bride_name' => "O'Brien-Smith",
            'groom_name' => 'José García',
        ];

        $invitation = $this->service->createInvitation($data, $user->id);

        $this->assertEquals("O'Brien-Smith", $invitation->bride_name);
        $this->assertEquals('José García', $invitation->groom_name);
    }

    public function test_update_invitation_can_clear_optional_fields(): void
    {
        $invitation = Invitation::factory()->create([
            'music_url' => 'https://example.com/music.mp3',
            'google_maps_url' => 'https://maps.google.com',
        ]);

        $updatedInvitation = $this->service->updateInvitation($invitation, [
            'music_url' => null,
            'google_maps_url' => null,
        ]);

        $this->assertNull($updatedInvitation->music_url);
        $this->assertNull($updatedInvitation->google_maps_url);
    }

    public function test_publish_invitation_with_draft_status(): void
    {
        $invitation = Invitation::factory()->create(['status' => 'draft']);

        $url = $this->service->publishInvitation($invitation);

        $invitation->refresh();
        $this->assertEquals('published', $invitation->status);
        $this->assertNotNull($invitation->unique_url);
    }

    public function test_publish_invitation_with_unpublished_status(): void
    {
        $invitation = Invitation::factory()->create([
            'status' => 'unpublished',
            'unique_url' => 'old-url',
        ]);

        $url = $this->service->publishInvitation($invitation);

        $invitation->refresh();
        $this->assertEquals('published', $invitation->status);
        // Service generates new URL
        $this->assertNotEquals('old-url', $invitation->unique_url);
        $this->assertEquals(32, strlen($invitation->unique_url));
    }
}
