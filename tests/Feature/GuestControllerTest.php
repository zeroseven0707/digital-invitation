<?php

namespace Tests\Feature;

use App\Models\Guest;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_guest_list_for_their_invitation()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);
        Guest::factory()->count(3)->create(['invitation_id' => $invitation->id]);

        $response = $this->actingAs($user)->get(
            route('guests.index', $invitation->id)
        );

        $response->assertStatus(200);
        $response->assertViewIs('guests.index');
        $response->assertViewHas('guests');
        $response->assertViewHas('invitation');
    }

    public function test_user_cannot_view_guest_list_for_other_users_invitation()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->get(
            route('guests.index', $invitation->id)
        );

        $response->assertStatus(404);
    }

    public function test_guest_list_requires_authentication()
    {
        $invitation = Invitation::factory()->create();

        $response = $this->get(route('guests.index', $invitation->id));

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_add_guest_to_their_invitation()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post(
            route('guests.store', $invitation->id),
            [
                'name' => 'John Doe',
                'category' => 'family',
            ]
        );

        $response->assertRedirect(route('guests.index', $invitation->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('guests', [
            'invitation_id' => $invitation->id,
            'name' => 'John Doe',
            'category' => 'family',
        ]);
    }

    public function test_user_cannot_add_guest_to_other_users_invitation()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->post(
            route('guests.store', $invitation->id),
            [
                'name' => 'John Doe',
                'category' => 'family',
            ]
        );

        $response->assertStatus(404);
    }

    public function test_add_guest_validates_required_fields()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post(
            route('guests.store', $invitation->id),
            []
        );

        $response->assertSessionHasErrors(['name', 'category']);
    }

    public function test_add_guest_validates_category_values()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post(
            route('guests.store', $invitation->id),
            [
                'name' => 'John Doe',
                'category' => 'invalid_category',
            ]
        );

        $response->assertSessionHasErrors('category');
    }

    public function test_user_can_update_guest_in_their_invitation()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);
        $guest = Guest::factory()->create([
            'invitation_id' => $invitation->id,
            'name' => 'Old Name',
            'category' => 'friend',
        ]);

        $response = $this->actingAs($user)->put(
            route('guests.update', [$invitation->id, $guest->id]),
            [
                'name' => 'New Name',
                'category' => 'family',
            ]
        );

        $response->assertRedirect(route('guests.index', $invitation->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('guests', [
            'id' => $guest->id,
            'name' => 'New Name',
            'category' => 'family',
        ]);
    }

    public function test_user_cannot_update_guest_in_other_users_invitation()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $otherUser->id]);
        $guest = Guest::factory()->create(['invitation_id' => $invitation->id]);

        $response = $this->actingAs($user)->put(
            route('guests.update', [$invitation->id, $guest->id]),
            [
                'name' => 'New Name',
                'category' => 'family',
            ]
        );

        $response->assertStatus(404);
    }

    public function test_update_guest_validates_required_fields()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);
        $guest = Guest::factory()->create(['invitation_id' => $invitation->id]);

        $response = $this->actingAs($user)->put(
            route('guests.update', [$invitation->id, $guest->id]),
            []
        );

        $response->assertSessionHasErrors(['name', 'category']);
    }

    public function test_user_can_delete_guest_from_their_invitation()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);
        $guest = Guest::factory()->create(['invitation_id' => $invitation->id]);

        $response = $this->actingAs($user)->delete(
            route('guests.destroy', [$invitation->id, $guest->id])
        );

        $response->assertRedirect(route('guests.index', $invitation->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('guests', [
            'id' => $guest->id,
        ]);
    }

    public function test_user_cannot_delete_guest_from_other_users_invitation()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $otherUser->id]);
        $guest = Guest::factory()->create(['invitation_id' => $invitation->id]);

        $response = $this->actingAs($user)->delete(
            route('guests.destroy', [$invitation->id, $guest->id])
        );

        $response->assertStatus(404);

        $this->assertDatabaseHas('guests', [
            'id' => $guest->id,
        ]);
    }

    public function test_guest_list_can_be_filtered_by_category()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Create guests with different categories
        Guest::factory()->create(['invitation_id' => $invitation->id, 'category' => 'family', 'name' => 'Family Guest']);
        Guest::factory()->create(['invitation_id' => $invitation->id, 'category' => 'friend', 'name' => 'Friend Guest']);
        Guest::factory()->create(['invitation_id' => $invitation->id, 'category' => 'colleague', 'name' => 'Colleague Guest']);

        $response = $this->actingAs($user)->get(
            route('guests.index', ['invitation' => $invitation->id, 'category' => 'family'])
        );

        $response->assertStatus(200);
        $guests = $response->viewData('guests');

        // Should only have family guests
        $this->assertCount(1, $guests);
        $this->assertEquals('family', $guests->first()->category);
    }

    public function test_guest_list_shows_all_guests_when_no_category_filter()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Create guests with different categories
        Guest::factory()->count(2)->create(['invitation_id' => $invitation->id, 'category' => 'family']);
        Guest::factory()->count(3)->create(['invitation_id' => $invitation->id, 'category' => 'friend']);

        $response = $this->actingAs($user)->get(
            route('guests.index', $invitation->id)
        );

        $response->assertStatus(200);
        $guests = $response->viewData('guests');

        // Should have all guests
        $this->assertCount(5, $guests);
    }

    public function test_adding_guest_increases_count()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Create existing guests
        Guest::factory()->count(2)->create(['invitation_id' => $invitation->id]);

        $initialCount = Guest::where('invitation_id', $invitation->id)->count();

        $this->actingAs($user)->post(
            route('guests.store', $invitation->id),
            [
                'name' => 'New Guest',
                'category' => 'family',
            ]
        );

        $newCount = Guest::where('invitation_id', $invitation->id)->count();

        $this->assertEquals($initialCount + 1, $newCount);
    }

    public function test_guest_list_is_ordered_by_name()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Create guests with different names
        Guest::factory()->create(['invitation_id' => $invitation->id, 'name' => 'Zara']);
        Guest::factory()->create(['invitation_id' => $invitation->id, 'name' => 'Alice']);
        Guest::factory()->create(['invitation_id' => $invitation->id, 'name' => 'Mike']);

        $response = $this->actingAs($user)->get(
            route('guests.index', $invitation->id)
        );

        $guests = $response->viewData('guests');
        $names = $guests->pluck('name')->toArray();

        $this->assertEquals(['Alice', 'Mike', 'Zara'], $names);
    }

    public function test_user_can_export_guests_to_csv()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Create guests
        Guest::factory()->create([
            'invitation_id' => $invitation->id,
            'name' => 'Alice Smith',
            'category' => 'family',
        ]);
        Guest::factory()->create([
            'invitation_id' => $invitation->id,
            'name' => 'Bob Jones',
            'category' => 'friend',
        ]);

        $response = $this->actingAs($user)->get(
            route('guests.export', $invitation->id)
        );

        $response->assertStatus(200);
        $response->assertDownload('guests.csv');
    }

    public function test_user_cannot_export_guests_from_other_users_invitation()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->get(
            route('guests.export', $invitation->id)
        );

        $response->assertStatus(404);
    }

    public function test_export_requires_authentication()
    {
        $invitation = Invitation::factory()->create();

        $response = $this->get(route('guests.export', $invitation->id));

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_import_guests_from_csv()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Create CSV content
        $csvContent = "name,category\nJohn Doe,family\nJane Smith,friend\nBob Wilson,colleague";
        $csvFile = tmpfile();
        fwrite($csvFile, $csvContent);
        $csvPath = stream_get_meta_data($csvFile)['uri'];

        $uploadedFile = new \Illuminate\Http\UploadedFile(
            $csvPath,
            'guests.csv',
            'text/csv',
            null,
            true
        );

        $response = $this->actingAs($user)->post(
            route('guests.import', $invitation->id),
            ['file' => $uploadedFile]
        );

        $response->assertRedirect(route('guests.index', $invitation->id));
        $response->assertSessionHas('success');

        // Verify guests were imported
        $this->assertDatabaseHas('guests', [
            'invitation_id' => $invitation->id,
            'name' => 'John Doe',
            'category' => 'family',
        ]);
        $this->assertDatabaseHas('guests', [
            'invitation_id' => $invitation->id,
            'name' => 'Jane Smith',
            'category' => 'friend',
        ]);
        $this->assertDatabaseHas('guests', [
            'invitation_id' => $invitation->id,
            'name' => 'Bob Wilson',
            'category' => 'colleague',
        ]);

        fclose($csvFile);
    }

    public function test_user_cannot_import_guests_to_other_users_invitation()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $otherUser->id]);

        $csvContent = "name,category\nJohn Doe,family";
        $csvFile = tmpfile();
        fwrite($csvFile, $csvContent);
        $csvPath = stream_get_meta_data($csvFile)['uri'];

        $uploadedFile = new \Illuminate\Http\UploadedFile(
            $csvPath,
            'guests.csv',
            'text/csv',
            null,
            true
        );

        $response = $this->actingAs($user)->post(
            route('guests.import', $invitation->id),
            ['file' => $uploadedFile]
        );

        $response->assertStatus(403);

        fclose($csvFile);
    }

    public function test_import_requires_authentication()
    {
        $invitation = Invitation::factory()->create();

        $csvContent = "name,category\nJohn Doe,family";
        $csvFile = tmpfile();
        fwrite($csvFile, $csvContent);
        $csvPath = stream_get_meta_data($csvFile)['uri'];

        $uploadedFile = new \Illuminate\Http\UploadedFile(
            $csvPath,
            'guests.csv',
            'text/csv',
            null,
            true
        );

        $response = $this->post(
            route('guests.import', $invitation->id),
            ['file' => $uploadedFile]
        );

        $response->assertRedirect(route('login'));

        fclose($csvFile);
    }

    public function test_import_validates_file_is_required()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post(
            route('guests.import', $invitation->id),
            []
        );

        $response->assertSessionHasErrors('file');
    }

    public function test_import_validates_file_format()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Create a non-CSV file
        $txtFile = tmpfile();
        fwrite($txtFile, "This is not a CSV file");
        $txtPath = stream_get_meta_data($txtFile)['uri'];

        $uploadedFile = new \Illuminate\Http\UploadedFile(
            $txtPath,
            'test.pdf',
            'application/pdf',
            null,
            true
        );

        $response = $this->actingAs($user)->post(
            route('guests.import', $invitation->id),
            ['file' => $uploadedFile]
        );

        $response->assertSessionHasErrors('file');

        fclose($txtFile);
    }

    public function test_import_handles_invalid_csv_data()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Create CSV with invalid data
        $csvContent = "name,category\nValid Guest,family\n,invalid_category\nAnother Guest,friend";
        $csvFile = tmpfile();
        fwrite($csvFile, $csvContent);
        $csvPath = stream_get_meta_data($csvFile)['uri'];

        $uploadedFile = new \Illuminate\Http\UploadedFile(
            $csvPath,
            'guests.csv',
            'text/csv',
            null,
            true
        );

        $response = $this->actingAs($user)->post(
            route('guests.import', $invitation->id),
            ['file' => $uploadedFile]
        );

        $response->assertRedirect(route('guests.index', $invitation->id));
        $response->assertSessionHas('success');

        // Valid guests should be imported
        $this->assertDatabaseHas('guests', [
            'invitation_id' => $invitation->id,
            'name' => 'Valid Guest',
            'category' => 'family',
        ]);
        $this->assertDatabaseHas('guests', [
            'invitation_id' => $invitation->id,
            'name' => 'Another Guest',
            'category' => 'friend',
        ]);

        // Invalid guest should not be imported
        $this->assertEquals(2, Guest::where('invitation_id', $invitation->id)->count());

        fclose($csvFile);
    }

    public function test_export_import_round_trip_preserves_data()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Create original guests
        Guest::factory()->create([
            'invitation_id' => $invitation->id,
            'name' => 'Alice Johnson',
            'category' => 'family',
        ]);
        Guest::factory()->create([
            'invitation_id' => $invitation->id,
            'name' => 'Bob Smith',
            'category' => 'friend',
        ]);
        Guest::factory()->create([
            'invitation_id' => $invitation->id,
            'name' => 'Charlie Brown',
            'category' => 'colleague',
        ]);

        $originalCount = Guest::where('invitation_id', $invitation->id)->count();
        $this->assertEquals(3, $originalCount);

        // Use the service directly to export
        $guestImportService = app(\App\Services\GuestImportService::class);
        $csvPath = $guestImportService->exportToCsv($invitation->id);

        // Verify CSV file exists
        $this->assertFileExists($csvPath);

        // Delete all guests
        Guest::where('invitation_id', $invitation->id)->delete();
        $this->assertEquals(0, Guest::where('invitation_id', $invitation->id)->count());

        // Import guests back
        $uploadedFile = new \Illuminate\Http\UploadedFile(
            $csvPath,
            'guests.csv',
            'text/csv',
            null,
            true
        );

        $importResponse = $this->actingAs($user)->post(
            route('guests.import', $invitation->id),
            ['file' => $uploadedFile]
        );

        $importResponse->assertRedirect(route('guests.index', $invitation->id));

        // Verify all guests were restored
        $importedGuests = Guest::where('invitation_id', $invitation->id)
            ->orderBy('name')
            ->get();

        $this->assertCount(3, $importedGuests);

        // Verify data matches
        $this->assertEquals('Alice Johnson', $importedGuests[0]->name);
        $this->assertEquals('family', $importedGuests[0]->category);

        $this->assertEquals('Bob Smith', $importedGuests[1]->name);
        $this->assertEquals('friend', $importedGuests[1]->category);

        $this->assertEquals('Charlie Brown', $importedGuests[2]->name);
        $this->assertEquals('colleague', $importedGuests[2]->category);

        // Cleanup
        if (file_exists($csvPath)) {
            unlink($csvPath);
        }
    }
}
