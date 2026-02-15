<?php

namespace Tests\Unit;

use App\Models\Guest;
use App\Models\Invitation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_belongs_to_invitation(): void
    {
        $invitation = Invitation::factory()->create();
        $guest = Guest::factory()->create(['invitation_id' => $invitation->id]);

        $this->assertInstanceOf(Invitation::class, $guest->invitation);
        $this->assertEquals($invitation->id, $guest->invitation->id);
    }

    public function test_guest_has_valid_category_constants(): void
    {
        $this->assertEquals('family', Guest::CATEGORY_FAMILY);
        $this->assertEquals('friend', Guest::CATEGORY_FRIEND);
        $this->assertEquals('colleague', Guest::CATEGORY_COLLEAGUE);
    }

    public function test_guest_can_be_created_with_family_category(): void
    {
        $guest = Guest::factory()->create(['category' => Guest::CATEGORY_FAMILY]);

        $this->assertEquals('family', $guest->category);
    }

    public function test_guest_can_be_created_with_friend_category(): void
    {
        $guest = Guest::factory()->create(['category' => Guest::CATEGORY_FRIEND]);

        $this->assertEquals('friend', $guest->category);
    }

    public function test_guest_can_be_created_with_colleague_category(): void
    {
        $guest = Guest::factory()->create(['category' => Guest::CATEGORY_COLLEAGUE]);

        $this->assertEquals('colleague', $guest->category);
    }

    public function test_guest_fillable_attributes(): void
    {
        $data = [
            'invitation_id' => 1,
            'name' => 'John Doe',
            'category' => 'family',
        ];

        $guest = new Guest($data);

        $this->assertEquals('John Doe', $guest->name);
        $this->assertEquals('family', $guest->category);
        $this->assertEquals(1, $guest->invitation_id);
    }

    public function test_guest_name_can_contain_special_characters(): void
    {
        $guest = Guest::factory()->create(['name' => "O'Brien-Smith Jr."]);

        $this->assertEquals("O'Brien-Smith Jr.", $guest->name);
    }

    public function test_guest_name_can_be_long(): void
    {
        $longName = str_repeat('A', 255);
        $guest = Guest::factory()->create(['name' => $longName]);

        $this->assertEquals($longName, $guest->name);
    }

    public function test_multiple_guests_can_have_same_name(): void
    {
        $invitation = Invitation::factory()->create();

        $guest1 = Guest::factory()->create([
            'invitation_id' => $invitation->id,
            'name' => 'John Doe',
        ]);

        $guest2 = Guest::factory()->create([
            'invitation_id' => $invitation->id,
            'name' => 'John Doe',
        ]);

        $this->assertEquals($guest1->name, $guest2->name);
        $this->assertNotEquals($guest1->id, $guest2->id);
    }

    public function test_guest_deletion_does_not_affect_invitation(): void
    {
        $invitation = Invitation::factory()->create();
        $guest = Guest::factory()->create(['invitation_id' => $invitation->id]);

        $guest->delete();

        $this->assertNotNull(Invitation::find($invitation->id));
    }

    public function test_guest_has_timestamps(): void
    {
        $guest = Guest::factory()->create();

        $this->assertNotNull($guest->created_at);
        $this->assertNotNull($guest->updated_at);
    }

    public function test_guest_updated_at_changes_on_update(): void
    {
        $guest = Guest::factory()->create(['name' => 'Original Name']);
        $originalUpdatedAt = $guest->updated_at;

        sleep(1);
        $guest->update(['name' => 'New Name']);

        $this->assertNotEquals($originalUpdatedAt, $guest->fresh()->updated_at);
    }
}
