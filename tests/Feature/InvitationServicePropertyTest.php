<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\Template;
use App\Models\User;
use App\Services\InvitationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationServicePropertyTest extends TestCase
{
    use RefreshDatabase;

    protected InvitationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new InvitationService();
    }

    /**
     * Property 9: Creating Invitation Saves as Draft
     * For any valid invitation data, creating a new invitation should save it
     * to the database with status 'draft' and associate it with the creating user.
     *
     * @property undangan-digital-laravel Property 9: Creating Invitation Saves as Draft
     * Validates: Requirements 3.3, 3.6
     */
    public function test_property_creating_invitation_saves_as_draft(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        // Run test 100 times with different random data
        for ($i = 0; $i < 100; $i++) {
            $data = [
                'template_id' => $template->id,
                'bride_name' => fake()->name(),
                'groom_name' => fake()->name(),
                'bride_father_name' => fake()->name(),
                'bride_mother_name' => fake()->name(),
                'groom_father_name' => fake()->name(),
                'groom_mother_name' => fake()->name(),
                'akad_date' => fake()->dateTimeBetween('now', '+2 years')->format('Y-m-d'),
                'akad_time_start' => fake()->time('H:i'),
                'akad_time_end' => fake()->time('H:i'),
                'akad_location' => fake()->address(),
                'reception_date' => fake()->dateTimeBetween('now', '+2 years')->format('Y-m-d'),
                'reception_time_start' => fake()->time('H:i'),
                'reception_time_end' => fake()->time('H:i'),
                'reception_location' => fake()->address(),
                'full_address' => fake()->address(),
                'google_maps_url' => 'https://maps.google.com/?q=' . fake()->latitude() . ',' . fake()->longitude(),
            ];

            $invitation = $this->service->createInvitation($data, $user->id);

            // Verify property holds
            $this->assertEquals('draft', $invitation->status);
            $this->assertEquals($user->id, $invitation->user_id);
            $this->assertEquals($data['bride_name'], $invitation->bride_name);
            $this->assertEquals($data['groom_name'], $invitation->groom_name);
            $this->assertEquals($template->id, $invitation->template_id);
            $this->assertDatabaseHas('invitations', [
                'id' => $invitation->id,
                'status' => 'draft',
                'user_id' => $user->id,
            ]);

            // Cleanup for next iteration
            $invitation->delete();
        }
    }

    /**
     * Property 12: Invitation Update Persists Changes
     * For any invitation being updated with valid data, the changes should be
     * saved to the database and reflected in subsequent retrievals.
     *
     * @property undangan-digital-laravel Property 12: Invitation Update Persists Changes
     * Validates: Requirements 4.2
     */
    public function test_property_invitation_update_persists_changes(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        for ($i = 0; $i < 100; $i++) {
            // Create invitation with random data
            $invitation = Invitation::factory()->create([
                'user_id' => $user->id,
                'template_id' => $template->id,
            ]);

            // Generate new random data for update
            $updateData = [
                'bride_name' => fake()->name(),
                'groom_name' => fake()->name(),
                'akad_location' => fake()->address(),
                'reception_location' => fake()->address(),
                'full_address' => fake()->address(),
            ];

            // Update invitation
            $updated = $this->service->updateInvitation($invitation, $updateData);

            // Verify changes persisted
            $this->assertEquals($updateData['bride_name'], $updated->bride_name);
            $this->assertEquals($updateData['groom_name'], $updated->groom_name);
            $this->assertEquals($updateData['akad_location'], $updated->akad_location);
            $this->assertEquals($updateData['reception_location'], $updated->reception_location);
            $this->assertEquals($updateData['full_address'], $updated->full_address);

            // Verify in database
            $this->assertDatabaseHas('invitations', [
                'id' => $invitation->id,
                'bride_name' => $updateData['bride_name'],
                'groom_name' => $updateData['groom_name'],
            ]);

            // Verify subsequent retrieval
            $retrieved = Invitation::find($invitation->id);
            $this->assertEquals($updateData['bride_name'], $retrieved->bride_name);
            $this->assertEquals($updateData['groom_name'], $retrieved->groom_name);

            // Cleanup
            $invitation->delete();
        }
    }

    /**
     * Property 13: Template Change Preserves Invitation Data
     * For any invitation, changing the template should update the template_id
     * but preserve all other invitation data.
     *
     * @property undangan-digital-laravel Property 13: Template Change Preserves Invitation Data
     * Validates: Requirements 4.3
     */
    public function test_property_template_change_preserves_invitation_data(): void
    {
        $user = User::factory()->create();
        $template1 = Template::factory()->create();
        $template2 = Template::factory()->create();

        for ($i = 0; $i < 100; $i++) {
            // Create invitation with random data
            $invitation = Invitation::factory()->create([
                'user_id' => $user->id,
                'template_id' => $template1->id,
            ]);

            // Store original data
            $originalData = [
                'bride_name' => $invitation->bride_name,
                'groom_name' => $invitation->groom_name,
                'bride_father_name' => $invitation->bride_father_name,
                'bride_mother_name' => $invitation->bride_mother_name,
                'groom_father_name' => $invitation->groom_father_name,
                'groom_mother_name' => $invitation->groom_mother_name,
                'akad_date' => $invitation->akad_date?->format('Y-m-d'),
                'akad_time_start' => $invitation->akad_time_start,
                'akad_time_end' => $invitation->akad_time_end,
                'akad_location' => $invitation->akad_location,
                'reception_date' => $invitation->reception_date?->format('Y-m-d'),
                'reception_time_start' => $invitation->reception_time_start,
                'reception_time_end' => $invitation->reception_time_end,
                'reception_location' => $invitation->reception_location,
                'full_address' => $invitation->full_address,
                'google_maps_url' => $invitation->google_maps_url,
                'music_url' => $invitation->music_url,
                'status' => $invitation->status,
                'unique_url' => $invitation->unique_url,
            ];

            // Change template
            $updated = $this->service->changeTemplate($invitation, $template2->id);

            // Verify template changed
            $this->assertEquals($template2->id, $updated->template_id);

            // Verify all other data preserved
            $this->assertEquals($originalData['bride_name'], $updated->bride_name);
            $this->assertEquals($originalData['groom_name'], $updated->groom_name);
            $this->assertEquals($originalData['bride_father_name'], $updated->bride_father_name);
            $this->assertEquals($originalData['bride_mother_name'], $updated->bride_mother_name);
            $this->assertEquals($originalData['groom_father_name'], $updated->groom_father_name);
            $this->assertEquals($originalData['groom_mother_name'], $updated->groom_mother_name);
            $this->assertEquals($originalData['akad_date'], $updated->akad_date?->format('Y-m-d'));
            $this->assertEquals($originalData['akad_time_start'], $updated->akad_time_start);
            $this->assertEquals($originalData['akad_time_end'], $updated->akad_time_end);
            $this->assertEquals($originalData['akad_location'], $updated->akad_location);
            $this->assertEquals($originalData['reception_date'], $updated->reception_date?->format('Y-m-d'));
            $this->assertEquals($originalData['reception_time_start'], $updated->reception_time_start);
            $this->assertEquals($originalData['reception_time_end'], $updated->reception_time_end);
            $this->assertEquals($originalData['reception_location'], $updated->reception_location);
            $this->assertEquals($originalData['full_address'], $updated->full_address);
            $this->assertEquals($originalData['google_maps_url'], $updated->google_maps_url);
            $this->assertEquals($originalData['music_url'], $updated->music_url);
            $this->assertEquals($originalData['status'], $updated->status);
            $this->assertEquals($originalData['unique_url'], $updated->unique_url);

            // Cleanup
            $invitation->delete();
        }
    }

    /**
     * Property 16: Publishing Generates Unique URL
     * For any draft invitation, publishing it should generate a unique URL,
     * set status to 'published', and make the invitation accessible via that URL.
     *
     * @property undangan-digital-laravel Property 16: Publishing Generates Unique URL
     * Validates: Requirements 4.6, 6.3
     */
    public function test_property_publishing_generates_unique_url(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();
        $generatedUrls = [];

        for ($i = 0; $i < 100; $i++) {
            // Create draft invitation
            $invitation = Invitation::factory()->create([
                'user_id' => $user->id,
                'template_id' => $template->id,
                'status' => 'draft',
                'unique_url' => null,
            ]);

            // Publish invitation
            $uniqueUrl = $this->service->publishInvitation($invitation);

            // Verify unique URL generated
            $this->assertNotEmpty($uniqueUrl);
            $this->assertEquals(32, strlen($uniqueUrl));
            $this->assertNotContains($uniqueUrl, $generatedUrls, 'URL must be unique');
            $generatedUrls[] = $uniqueUrl;

            // Verify status changed to published
            $invitation->refresh();
            $this->assertEquals('published', $invitation->status);
            $this->assertEquals($uniqueUrl, $invitation->unique_url);

            // Verify in database
            $this->assertDatabaseHas('invitations', [
                'id' => $invitation->id,
                'status' => 'published',
                'unique_url' => $uniqueUrl,
            ]);

            // Verify invitation can be found by unique URL
            $found = Invitation::where('unique_url', $uniqueUrl)->first();
            $this->assertNotNull($found);
            $this->assertEquals($invitation->id, $found->id);

            // Cleanup
            $invitation->delete();
        }
    }

    /**
     * Property 17: Unpublishing Disables Access
     * For any published invitation, unpublishing it should set status to
     * 'unpublished' and make the unique URL return a 404 error.
     *
     * @property undangan-digital-laravel Property 17: Unpublishing Disables Access
     * Validates: Requirements 4.7, 6.6
     */
    public function test_property_unpublishing_disables_access(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        for ($i = 0; $i < 100; $i++) {
            // Create published invitation
            $invitation = Invitation::factory()->create([
                'user_id' => $user->id,
                'template_id' => $template->id,
                'status' => 'published',
                'unique_url' => fake()->unique()->regexify('[a-zA-Z0-9]{32}'),
            ]);

            $originalUrl = $invitation->unique_url;

            // Unpublish invitation
            $this->service->unpublishInvitation($invitation);

            // Verify status changed to unpublished
            $invitation->refresh();
            $this->assertEquals('unpublished', $invitation->status);
            $this->assertEquals($originalUrl, $invitation->unique_url); // URL preserved but status changed

            // Verify in database
            $this->assertDatabaseHas('invitations', [
                'id' => $invitation->id,
                'status' => 'unpublished',
                'unique_url' => $originalUrl,
            ]);

            // Verify invitation is not published anymore
            $found = Invitation::where('unique_url', $originalUrl)->published()->first();
            $this->assertNull($found, 'Unpublished invitation should not be found in published scope');

            // Cleanup
            $invitation->delete();
        }
    }
}

