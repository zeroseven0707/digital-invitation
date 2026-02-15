<?php

namespace Tests\Unit;

use App\Models\Invitation;
use App\Models\Template;
use App\Models\User;
use App\Services\InvitationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected InvitationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new InvitationService();
    }

    public function test_create_invitation_saves_as_draft(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        $data = [
            'template_id' => $template->id,
            'bride_name' => 'Anisa',
            'groom_name' => 'Dian',
            'bride_father_name' => 'Bapak Bride',
            'bride_mother_name' => 'Ibu Bride',
            'groom_father_name' => 'Bapak Groom',
            'groom_mother_name' => 'Ibu Groom',
            'akad_date' => '2026-03-29',
            'akad_time_start' => '08:00',
            'akad_time_end' => '10:00',
            'akad_location' => 'Masjid Al-Ikhlas',
            'reception_date' => '2026-03-29',
            'reception_time_start' => '11:00',
            'reception_time_end' => '14:00',
            'reception_location' => 'Gedung Serbaguna',
            'full_address' => 'Jl. Merdeka No. 123',
            'google_maps_url' => 'https://maps.google.com/?q=-6.2088,106.8456',
        ];

        $invitation = $this->service->createInvitation($data, $user->id);

        $this->assertInstanceOf(Invitation::class, $invitation);
        $this->assertEquals('draft', $invitation->status);
        $this->assertEquals($user->id, $invitation->user_id);
        $this->assertEquals($template->id, $invitation->template_id);
        $this->assertEquals('Anisa', $invitation->bride_name);
        $this->assertEquals('Dian', $invitation->groom_name);
        $this->assertDatabaseHas('invitations', [
            'user_id' => $user->id,
            'status' => 'draft',
            'bride_name' => 'Anisa',
        ]);
    }

    public function test_update_invitation_persists_changes(): void
    {
        $invitation = Invitation::factory()->create([
            'bride_name' => 'Old Name',
            'groom_name' => 'Old Groom',
        ]);

        $updateData = [
            'bride_name' => 'New Name',
            'groom_name' => 'New Groom',
            'akad_location' => 'New Location',
        ];

        $updated = $this->service->updateInvitation($invitation, $updateData);

        $this->assertEquals('New Name', $updated->bride_name);
        $this->assertEquals('New Groom', $updated->groom_name);
        $this->assertEquals('New Location', $updated->akad_location);
        $this->assertDatabaseHas('invitations', [
            'id' => $invitation->id,
            'bride_name' => 'New Name',
            'groom_name' => 'New Groom',
        ]);
    }

    public function test_publish_invitation_generates_unique_url(): void
    {
        $invitation = Invitation::factory()->create(['status' => 'draft']);

        $uniqueUrl = $this->service->publishInvitation($invitation);

        $this->assertNotEmpty($uniqueUrl);
        $this->assertEquals(32, strlen($uniqueUrl));

        $invitation->refresh();
        $this->assertEquals('published', $invitation->status);
        $this->assertEquals($uniqueUrl, $invitation->unique_url);
        $this->assertDatabaseHas('invitations', [
            'id' => $invitation->id,
            'status' => 'published',
            'unique_url' => $uniqueUrl,
        ]);
    }

    public function test_publish_generates_different_urls_for_different_invitations(): void
    {
        $invitation1 = Invitation::factory()->create(['status' => 'draft']);
        $invitation2 = Invitation::factory()->create(['status' => 'draft']);

        $url1 = $this->service->publishInvitation($invitation1);
        $url2 = $this->service->publishInvitation($invitation2);

        $this->assertNotEquals($url1, $url2);
    }

    public function test_unpublish_invitation_changes_status(): void
    {
        $invitation = Invitation::factory()->create([
            'status' => 'published',
            'unique_url' => 'test-unique-url-123',
        ]);

        $this->service->unpublishInvitation($invitation);

        $invitation->refresh();
        $this->assertEquals('unpublished', $invitation->status);
        $this->assertDatabaseHas('invitations', [
            'id' => $invitation->id,
            'status' => 'unpublished',
        ]);
    }

    public function test_change_template_preserves_invitation_data(): void
    {
        $template1 = Template::factory()->create();
        $template2 = Template::factory()->create();

        $invitation = Invitation::factory()->create([
            'template_id' => $template1->id,
            'bride_name' => 'Anisa',
            'groom_name' => 'Dian',
            'akad_date' => '2026-03-29',
            'akad_location' => 'Masjid Al-Ikhlas',
        ]);

        $updated = $this->service->changeTemplate($invitation, $template2->id);

        $this->assertEquals($template2->id, $updated->template_id);
        $this->assertEquals('Anisa', $updated->bride_name);
        $this->assertEquals('Dian', $updated->groom_name);
        $this->assertEquals('2026-03-29', $updated->akad_date->format('Y-m-d'));
        $this->assertEquals('Masjid Al-Ikhlas', $updated->akad_location);
    }

    public function test_delete_invitation_removes_from_database(): void
    {
        $invitation = Invitation::factory()->create();
        $invitationId = $invitation->id;

        $this->service->deleteInvitation($invitation);

        $this->assertDatabaseMissing('invitations', [
            'id' => $invitationId,
        ]);
    }

    public function test_create_invitation_with_minimal_data(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        $data = [
            'template_id' => $template->id,
            'bride_name' => 'A',
            'groom_name' => 'B',
        ];

        $invitation = $this->service->createInvitation($data, $user->id);

        $this->assertInstanceOf(Invitation::class, $invitation);
        $this->assertEquals('draft', $invitation->status);
        $this->assertEquals('A', $invitation->bride_name);
        $this->assertEquals('B', $invitation->groom_name);
    }

    public function test_update_invitation_with_partial_data(): void
    {
        $invitation = Invitation::factory()->create([
            'bride_name' => 'Original',
            'groom_name' => 'Original Groom',
            'akad_location' => 'Original Location',
        ]);

        $updateData = ['bride_name' => 'Updated'];

        $updated = $this->service->updateInvitation($invitation, $updateData);

        $this->assertEquals('Updated', $updated->bride_name);
        $this->assertEquals('Original Groom', $updated->groom_name);
        $this->assertEquals('Original Location', $updated->akad_location);
    }
}

