<?php

namespace Tests\Unit;

use App\Models\Guest;
use App\Models\Invitation;
use App\Models\User;
use App\Services\GuestService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestServiceTest extends TestCase
{
    use RefreshDatabase;

    protected GuestService $guestService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->guestService = new GuestService();
    }

    public function test_add_guest_creates_guest_with_invitation_id()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        $data = [
            'name' => 'John Doe',
            'category' => Guest::CATEGORY_FAMILY,
        ];

        $guest = $this->guestService->addGuest($invitation->id, $data);

        $this->assertInstanceOf(Guest::class, $guest);
        $this->assertEquals($invitation->id, $guest->invitation_id);
        $this->assertEquals('John Doe', $guest->name);
        $this->assertEquals(Guest::CATEGORY_FAMILY, $guest->category);
        $this->assertDatabaseHas('guests', [
            'invitation_id' => $invitation->id,
            'name' => 'John Doe',
            'category' => Guest::CATEGORY_FAMILY,
        ]);
    }

    public function test_update_guest_persists_changes()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);
        $guest = Guest::factory()->create([
            'invitation_id' => $invitation->id,
            'name' => 'Original Name',
            'category' => Guest::CATEGORY_FAMILY,
        ]);

        $updateData = [
            'name' => 'Updated Name',
            'category' => Guest::CATEGORY_FRIEND,
        ];

        $updatedGuest = $this->guestService->updateGuest($guest, $updateData);

        $this->assertEquals('Updated Name', $updatedGuest->name);
        $this->assertEquals(Guest::CATEGORY_FRIEND, $updatedGuest->category);
        $this->assertDatabaseHas('guests', [
            'id' => $guest->id,
            'name' => 'Updated Name',
            'category' => Guest::CATEGORY_FRIEND,
        ]);
    }

    public function test_delete_guest_removes_from_database()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);
        $guest = Guest::factory()->create([
            'invitation_id' => $invitation->id,
        ]);

        $guestId = $guest->id;

        $this->guestService->deleteGuest($guest);

        $this->assertDatabaseMissing('guests', [
            'id' => $guestId,
        ]);
    }

    public function test_get_guests_by_category_returns_correct_guests()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Create guests with different categories
        Guest::factory()->create([
            'invitation_id' => $invitation->id,
            'name' => 'Family Guest 1',
            'category' => Guest::CATEGORY_FAMILY,
        ]);
        Guest::factory()->create([
            'invitation_id' => $invitation->id,
            'name' => 'Family Guest 2',
            'category' => Guest::CATEGORY_FAMILY,
        ]);
        Guest::factory()->create([
            'invitation_id' => $invitation->id,
            'name' => 'Friend Guest',
            'category' => Guest::CATEGORY_FRIEND,
        ]);
        Guest::factory()->create([
            'invitation_id' => $invitation->id,
            'name' => 'Colleague Guest',
            'category' => Guest::CATEGORY_COLLEAGUE,
        ]);

        $familyGuests = $this->guestService->getGuestsByCategory($invitation->id, Guest::CATEGORY_FAMILY);

        $this->assertCount(2, $familyGuests);
        $this->assertTrue($familyGuests->every(fn($guest) => $guest->category === Guest::CATEGORY_FAMILY));
        $this->assertTrue($familyGuests->contains('name', 'Family Guest 1'));
        $this->assertTrue($familyGuests->contains('name', 'Family Guest 2'));
        $this->assertFalse($familyGuests->contains('name', 'Friend Guest'));
    }

    public function test_get_guests_by_category_returns_empty_collection_when_no_matches()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        Guest::factory()->create([
            'invitation_id' => $invitation->id,
            'category' => Guest::CATEGORY_FAMILY,
        ]);

        $colleagueGuests = $this->guestService->getGuestsByCategory($invitation->id, Guest::CATEGORY_COLLEAGUE);

        $this->assertCount(0, $colleagueGuests);
    }

    public function test_get_guests_by_category_only_returns_guests_for_specified_invitation()
    {
        $user = User::factory()->create();
        $invitation1 = Invitation::factory()->create(['user_id' => $user->id]);
        $invitation2 = Invitation::factory()->create(['user_id' => $user->id]);

        Guest::factory()->create([
            'invitation_id' => $invitation1->id,
            'name' => 'Invitation 1 Guest',
            'category' => Guest::CATEGORY_FAMILY,
        ]);
        Guest::factory()->create([
            'invitation_id' => $invitation2->id,
            'name' => 'Invitation 2 Guest',
            'category' => Guest::CATEGORY_FAMILY,
        ]);

        $guests = $this->guestService->getGuestsByCategory($invitation1->id, Guest::CATEGORY_FAMILY);

        $this->assertCount(1, $guests);
        $this->assertEquals('Invitation 1 Guest', $guests->first()->name);
    }
}
