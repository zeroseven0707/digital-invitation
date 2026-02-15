<?php

namespace Tests\Unit;

use App\Models\Gallery;
use App\Models\Guest;
use App\Models\Invitation;
use App\Models\InvitationView;
use App\Models\Template;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_invitation_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $invitation->user);
        $this->assertEquals($user->id, $invitation->user->id);
    }

    public function test_invitation_belongs_to_template(): void
    {
        $template = Template::factory()->create();
        $invitation = Invitation::factory()->create(['template_id' => $template->id]);

        $this->assertInstanceOf(Template::class, $invitation->template);
        $this->assertEquals($template->id, $invitation->template->id);
    }

    public function test_invitation_has_many_guests(): void
    {
        $invitation = Invitation::factory()->create();
        Guest::factory()->count(5)->create(['invitation_id' => $invitation->id]);

        $this->assertCount(5, $invitation->guests);
        $this->assertInstanceOf(Guest::class, $invitation->guests->first());
    }

    public function test_invitation_has_many_galleries(): void
    {
        $invitation = Invitation::factory()->create();
        Gallery::factory()->count(3)->create(['invitation_id' => $invitation->id]);

        $this->assertCount(3, $invitation->galleries);
        $this->assertInstanceOf(Gallery::class, $invitation->galleries->first());
    }

    public function test_invitation_has_many_views(): void
    {
        $invitation = Invitation::factory()->create();
        InvitationView::factory()->count(10)->create(['invitation_id' => $invitation->id]);

        $this->assertCount(10, $invitation->views);
        $this->assertInstanceOf(InvitationView::class, $invitation->views->first());
    }

    public function test_invitation_published_scope(): void
    {
        Invitation::factory()->count(3)->create(['status' => 'published']);
        Invitation::factory()->count(2)->create(['status' => 'draft']);
        Invitation::factory()->count(1)->create(['status' => 'unpublished']);

        $published = Invitation::published()->get();

        $this->assertCount(3, $published);
        $published->each(function ($invitation) {
            $this->assertEquals('published', $invitation->status);
        });
    }

    public function test_invitation_by_user_scope(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Invitation::factory()->count(4)->create(['user_id' => $user1->id]);
        Invitation::factory()->count(2)->create(['user_id' => $user2->id]);

        $user1Invitations = Invitation::byUser($user1->id)->get();

        $this->assertCount(4, $user1Invitations);
        $user1Invitations->each(function ($invitation) use ($user1) {
            $this->assertEquals($user1->id, $invitation->user_id);
        });
    }

    public function test_invitation_casts_dates_correctly(): void
    {
        $invitation = Invitation::factory()->create([
            'akad_date' => '2024-12-25',
            'reception_date' => '2024-12-26',
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $invitation->akad_date);
        $this->assertInstanceOf(\Carbon\Carbon::class, $invitation->reception_date);
    }

    public function test_invitation_fillable_attributes(): void
    {
        $data = [
            'user_id' => 1,
            'template_id' => 1,
            'unique_url' => 'test-url',
            'status' => 'draft',
            'bride_name' => 'Jane',
            'groom_name' => 'John',
        ];

        $invitation = new Invitation($data);

        $this->assertEquals('Jane', $invitation->bride_name);
        $this->assertEquals('John', $invitation->groom_name);
        $this->assertEquals('draft', $invitation->status);
    }

    public function test_invitation_cascade_deletes_guests(): void
    {
        $invitation = Invitation::factory()->create();
        Guest::factory()->count(5)->create(['invitation_id' => $invitation->id]);

        $this->assertCount(5, Guest::where('invitation_id', $invitation->id)->get());

        $invitation->delete();

        $this->assertCount(0, Guest::where('invitation_id', $invitation->id)->get());
    }

    public function test_invitation_cascade_deletes_galleries(): void
    {
        $invitation = Invitation::factory()->create();
        Gallery::factory()->count(3)->create(['invitation_id' => $invitation->id]);

        $this->assertCount(3, Gallery::where('invitation_id', $invitation->id)->get());

        $invitation->delete();

        $this->assertCount(0, Gallery::where('invitation_id', $invitation->id)->get());
    }

    public function test_invitation_cascade_deletes_views(): void
    {
        $invitation = Invitation::factory()->create();
        InvitationView::factory()->count(10)->create(['invitation_id' => $invitation->id]);

        $this->assertCount(10, InvitationView::where('invitation_id', $invitation->id)->get());

        $invitation->delete();

        $this->assertCount(0, InvitationView::where('invitation_id', $invitation->id)->get());
    }

    public function test_invitation_unique_url_is_unique(): void
    {
        $invitation1 = Invitation::factory()->create(['unique_url' => 'unique-url-1']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        Invitation::factory()->create(['unique_url' => 'unique-url-1']);
    }

    public function test_invitation_can_have_null_optional_fields(): void
    {
        $invitation = Invitation::factory()->create([
            'music_url' => null,
            'google_maps_url' => null,
            'bride_father_name' => null,
            'bride_mother_name' => null,
        ]);

        $this->assertNull($invitation->music_url);
        $this->assertNull($invitation->google_maps_url);
        $this->assertNull($invitation->bride_father_name);
        $this->assertNull($invitation->bride_mother_name);
    }
}
