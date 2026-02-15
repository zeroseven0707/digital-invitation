<?php

namespace Tests\Feature;

use App\Models\Guest;
use App\Models\Invitation;
use App\Models\Template;
use App\Models\User;
use App\Services\GuestImportService;
use App\Services\GuestService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

/**
 * Property-Based Tests for Guest Management
 *
 * These tests validate universal properties that should hold true
 * for all guest management operations.
 */
class GuestPropertyTest extends TestCase
{
    use RefreshDatabase;

    protected GuestService $service;
    protected GuestImportService $importService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new GuestService();
        $this->importService = new GuestImportService();
    }

    /**
     * Property 18: Guest List Shows All Guests
     *
     * For any invitation with guests, retrieving the guest list should return
     * all guests associated with that invitation.
     *
     * Validates: Requirements 5.1
     */
    public function test_property_guest_list_shows_all_guests(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        // Test with varying numbers of guests
        $testCases = [
            ['guest_count' => 0],  // Edge case: no guests
            ['guest_count' => 1],
            ['guest_count' => 5],
            ['guest_count' => 10],
            ['guest_count' => 20],
            ['guest_count' => 50],
        ];

        foreach ($testCases as $testCase) {
            $invitation = Invitation::factory()->create([
                'user_id' => $user->id,
                'template_id' => $template->id,
            ]);

            // Create guests with random data
            $createdGuests = [];
            for ($i = 0; $i < $testCase['guest_count']; $i++) {
                $guest = Guest::factory()->create([
                    'invitation_id' => $invitation->id,
                ]);
                $createdGuests[] = $guest;
            }

            // Retrieve guest list
            $retrievedGuests = Guest::where('invitation_id', $invitation->id)->get();

            // Property: Count should match
            $this->assertEquals($testCase['guest_count'], $retrievedGuests->count());

            // Property: All created guests should be in retrieved list
            foreach ($createdGuests as $createdGuest) {
                $found = $retrievedGuests->firstWhere('id', $createdGuest->id);
                $this->assertNotNull($found);
                $this->assertEquals($createdGuest->name, $found->name);
                $this->assertEquals($createdGuest->category, $found->category);
            }

            // Property: No extra guests should be in the list
            $this->assertEquals(
                count($createdGuests),
                $retrievedGuests->count()
            );

            // Cleanup
            $invitation->delete();
        }
    }

    /**
     * Property 19: Adding Guest Increases Count
     *
     * For any invitation, adding a valid guest should increase the guest count
     * by 1 and the new guest should appear in the guest list.
     *
     * Validates: Requirements 5.2
     */
    public function test_property_adding_guest_increases_count(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        // Run test 100 times with random data
        for ($i = 0; $i < 100; $i++) {
            $invitation = Invitation::factory()->create([
                'user_id' => $user->id,
                'template_id' => $template->id,
            ]);

            // Create initial guests (random count 0-10)
            $initialCount = rand(0, 10);
            Guest::factory()->count($initialCount)->create([
                'invitation_id' => $invitation->id,
            ]);

            // Verify initial count
            $countBefore = Guest::where('invitation_id', $invitation->id)->count();
            $this->assertEquals($initialCount, $countBefore);

            // Add new guest with random data
            $newGuestData = [
                'name' => fake()->name(),
                'category' => fake()->randomElement([
                    Guest::CATEGORY_FAMILY,
                    Guest::CATEGORY_FRIEND,
                    Guest::CATEGORY_COLLEAGUE,
                ]),
            ];

            $newGuest = $this->service->addGuest($invitation->id, $newGuestData);

            // Property: Count should increase by 1
            $countAfter = Guest::where('invitation_id', $invitation->id)->count();
            $this->assertEquals($countBefore + 1, $countAfter);

            // Property: New guest should exist in database
            $this->assertDatabaseHas('guests', [
                'id' => $newGuest->id,
                'invitation_id' => $invitation->id,
                'name' => $newGuestData['name'],
                'category' => $newGuestData['category'],
            ]);

            // Property: New guest should appear in guest list
            $guestList = Guest::where('invitation_id', $invitation->id)->get();
            $found = $guestList->firstWhere('id', $newGuest->id);
            $this->assertNotNull($found);
            $this->assertEquals($newGuestData['name'], $found->name);
            $this->assertEquals($newGuestData['category'], $found->category);

            // Cleanup
            $invitation->delete();
        }
    }

    /**
     * Property 20: Guest Update Persists Changes
     *
     * For any guest being updated, the changes should be saved to the database
     * and reflected in subsequent retrievals.
     *
     * Validates: Requirements 5.3
     */
    public function test_property_guest_update_persists_changes(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        // Run test 100 times with random data
        for ($i = 0; $i < 100; $i++) {
            $invitation = Invitation::factory()->create([
                'user_id' => $user->id,
                'template_id' => $template->id,
            ]);

            // Create guest with random data
            $guest = Guest::factory()->create([
                'invitation_id' => $invitation->id,
            ]);

            $originalName = $guest->name;
            $originalCategory = $guest->category;

            // Generate new random data for update
            $updateData = [
                'name' => fake()->name(),
                'category' => fake()->randomElement([
                    Guest::CATEGORY_FAMILY,
                    Guest::CATEGORY_FRIEND,
                    Guest::CATEGORY_COLLEAGUE,
                ]),
            ];

            // Ensure at least one field changes
            while ($updateData['name'] === $originalName && $updateData['category'] === $originalCategory) {
                $updateData['name'] = fake()->name();
            }

            // Update guest
            $updated = $this->service->updateGuest($guest, $updateData);

            // Property: Changes should be reflected in returned object
            $this->assertEquals($updateData['name'], $updated->name);
            $this->assertEquals($updateData['category'], $updated->category);

            // Property: Changes should be persisted in database
            $this->assertDatabaseHas('guests', [
                'id' => $guest->id,
                'invitation_id' => $invitation->id,
                'name' => $updateData['name'],
                'category' => $updateData['category'],
            ]);

            // Property: Subsequent retrieval should reflect changes
            $retrieved = Guest::find($guest->id);
            $this->assertNotNull($retrieved);
            $this->assertEquals($updateData['name'], $retrieved->name);
            $this->assertEquals($updateData['category'], $retrieved->category);

            // Property: invitation_id should remain unchanged
            $this->assertEquals($invitation->id, $retrieved->invitation_id);

            // Cleanup
            $invitation->delete();
        }
    }

    /**
     * Property 21: Guest Deletion Removes from Database
     *
     * For any guest, deleting it should remove the guest record from the database
     * and it should not appear in subsequent guest list retrievals.
     *
     * Validates: Requirements 5.4
     */
    public function test_property_guest_deletion_removes_from_database(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        // Run test 100 times
        for ($i = 0; $i < 100; $i++) {
            $invitation = Invitation::factory()->create([
                'user_id' => $user->id,
                'template_id' => $template->id,
            ]);

            // Create multiple guests (random count 2-10)
            $guestCount = rand(2, 10);
            $guests = Guest::factory()->count($guestCount)->create([
                'invitation_id' => $invitation->id,
            ]);

            // Pick a random guest to delete
            $guestToDelete = $guests->random();
            $deletedGuestId = $guestToDelete->id;
            $deletedGuestName = $guestToDelete->name;

            // Verify guest exists before deletion
            $this->assertDatabaseHas('guests', [
                'id' => $deletedGuestId,
                'invitation_id' => $invitation->id,
            ]);

            $countBefore = Guest::where('invitation_id', $invitation->id)->count();

            // Delete guest
            $this->service->deleteGuest($guestToDelete);

            // Property: Guest should be removed from database
            $this->assertDatabaseMissing('guests', [
                'id' => $deletedGuestId,
            ]);

            // Property: Guest count should decrease by 1
            $countAfter = Guest::where('invitation_id', $invitation->id)->count();
            $this->assertEquals($countBefore - 1, $countAfter);

            // Property: Guest should not appear in subsequent retrievals
            $retrieved = Guest::find($deletedGuestId);
            $this->assertNull($retrieved);

            // Property: Guest should not appear in guest list
            $guestList = Guest::where('invitation_id', $invitation->id)->get();
            $found = $guestList->firstWhere('id', $deletedGuestId);
            $this->assertNull($found);

            // Property: Other guests should remain unaffected
            $remainingGuests = $guests->reject(fn($g) => $g->id === $deletedGuestId);
            foreach ($remainingGuests as $remainingGuest) {
                $this->assertDatabaseHas('guests', [
                    'id' => $remainingGuest->id,
                    'invitation_id' => $invitation->id,
                ]);
            }

            // Cleanup
            $invitation->delete();
        }
    }

    /**
     * Property 22: Guest Filter Returns Correct Category
     *
     * For any guest list filtered by category, all returned guests should have
     * the specified category and no guests from other categories should be included.
     *
     * Validates: Requirements 5.5
     */
    public function test_property_guest_filter_returns_correct_category(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        $categories = [
            Guest::CATEGORY_FAMILY,
            Guest::CATEGORY_FRIEND,
            Guest::CATEGORY_COLLEAGUE,
        ];

        // Run test 100 times
        for ($i = 0; $i < 100; $i++) {
            $invitation = Invitation::factory()->create([
                'user_id' => $user->id,
                'template_id' => $template->id,
            ]);

            // Create guests in each category (random count 1-10 per category)
            $guestsByCategory = [];
            foreach ($categories as $category) {
                $count = rand(1, 10);
                $guests = Guest::factory()->count($count)->create([
                    'invitation_id' => $invitation->id,
                    'category' => $category,
                ]);
                $guestsByCategory[$category] = $guests;
            }

            // Test filtering for each category
            foreach ($categories as $filterCategory) {
                $filteredGuests = $this->service->getGuestsByCategory(
                    $invitation->id,
                    $filterCategory
                );

                // Property: All returned guests should have the specified category
                foreach ($filteredGuests as $guest) {
                    $this->assertEquals($filterCategory, $guest->category);
                    $this->assertEquals($invitation->id, $guest->invitation_id);
                }

                // Property: Count should match expected count for this category
                $expectedCount = $guestsByCategory[$filterCategory]->count();
                $this->assertEquals($expectedCount, $filteredGuests->count());

                // Property: All guests of this category should be included
                foreach ($guestsByCategory[$filterCategory] as $expectedGuest) {
                    $found = $filteredGuests->firstWhere('id', $expectedGuest->id);
                    $this->assertNotNull($found, "Guest {$expectedGuest->id} should be in filtered results");
                    $this->assertEquals($expectedGuest->name, $found->name);
                }

                // Property: No guests from other categories should be included
                foreach ($categories as $otherCategory) {
                    if ($otherCategory !== $filterCategory) {
                        foreach ($guestsByCategory[$otherCategory] as $otherGuest) {
                            $found = $filteredGuests->firstWhere('id', $otherGuest->id);
                            $this->assertNull($found, "Guest {$otherGuest->id} from category {$otherCategory} should not be in {$filterCategory} results");
                        }
                    }
                }
            }

            // Cleanup
            $invitation->delete();
        }
    }

    /**
     * Property: Guest Isolation Between Invitations
     *
     * For any two invitations, guests should be isolated and filtering/retrieving
     * guests for one invitation should not return guests from another invitation.
     *
     * Validates: Requirements 5.1, 12.4
     */
    public function test_property_guest_isolation_between_invitations(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        // Run test 50 times
        for ($i = 0; $i < 50; $i++) {
            // Create two invitations
            $invitation1 = Invitation::factory()->create([
                'user_id' => $user->id,
                'template_id' => $template->id,
            ]);

            $invitation2 = Invitation::factory()->create([
                'user_id' => $user->id,
                'template_id' => $template->id,
            ]);

            // Create guests for invitation 1 (random count 5-15)
            $count1 = rand(5, 15);
            $guests1 = Guest::factory()->count($count1)->create([
                'invitation_id' => $invitation1->id,
            ]);

            // Create guests for invitation 2 (random count 5-15)
            $count2 = rand(5, 15);
            $guests2 = Guest::factory()->count($count2)->create([
                'invitation_id' => $invitation2->id,
            ]);

            // Property: Retrieving guests for invitation 1 should only return invitation 1's guests
            $retrieved1 = Guest::where('invitation_id', $invitation1->id)->get();
            $this->assertEquals($count1, $retrieved1->count());
            foreach ($retrieved1 as $guest) {
                $this->assertEquals($invitation1->id, $guest->invitation_id);
            }

            // Property: Retrieving guests for invitation 2 should only return invitation 2's guests
            $retrieved2 = Guest::where('invitation_id', $invitation2->id)->get();
            $this->assertEquals($count2, $retrieved2->count());
            foreach ($retrieved2 as $guest) {
                $this->assertEquals($invitation2->id, $guest->invitation_id);
            }

            // Property: No overlap between guest lists
            $ids1 = $retrieved1->pluck('id')->toArray();
            $ids2 = $retrieved2->pluck('id')->toArray();
            $intersection = array_intersect($ids1, $ids2);
            $this->assertEmpty($intersection, 'Guest lists should not overlap');

            // Cleanup
            $invitation1->delete();
            $invitation2->delete();
        }
    }

    /**
     * Property: Guest Count is Non-Negative
     *
     * For any invitation, the guest count should always be a non-negative integer.
     *
     * Validates: Requirements 5.1
     */
    public function test_property_guest_count_is_non_negative(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        // Test with various scenarios
        $testCases = [
            ['initial_guests' => 0, 'add' => 0, 'delete' => 0],
            ['initial_guests' => 5, 'add' => 3, 'delete' => 2],
            ['initial_guests' => 10, 'add' => 0, 'delete' => 5],
            ['initial_guests' => 3, 'add' => 7, 'delete' => 3],
            ['initial_guests' => 20, 'add' => 5, 'delete' => 20],
        ];

        foreach ($testCases as $testCase) {
            $invitation = Invitation::factory()->create([
                'user_id' => $user->id,
                'template_id' => $template->id,
            ]);

            // Create initial guests
            $guests = Guest::factory()->count($testCase['initial_guests'])->create([
                'invitation_id' => $invitation->id,
            ]);

            // Add guests
            for ($i = 0; $i < $testCase['add']; $i++) {
                $this->service->addGuest($invitation->id, [
                    'name' => fake()->name(),
                    'category' => fake()->randomElement([
                        Guest::CATEGORY_FAMILY,
                        Guest::CATEGORY_FRIEND,
                        Guest::CATEGORY_COLLEAGUE,
                    ]),
                ]);
            }

            // Delete guests
            $allGuests = Guest::where('invitation_id', $invitation->id)->get();
            $deleteCount = min($testCase['delete'], $allGuests->count());
            for ($i = 0; $i < $deleteCount; $i++) {
                $guestToDelete = $allGuests[$i];
                $this->service->deleteGuest($guestToDelete);
            }

            // Property: Count should always be non-negative
            $finalCount = Guest::where('invitation_id', $invitation->id)->count();
            $this->assertGreaterThanOrEqual(0, $finalCount);
            $this->assertIsInt($finalCount);

            // Property: Count should match expected value
            $expectedCount = $testCase['initial_guests'] + $testCase['add'] - $deleteCount;
            $this->assertEquals($expectedCount, $finalCount);

            // Cleanup
            $invitation->delete();
        }
    }

    /**
     * Property 23: CSV Export Contains All Guests
     *
     * For any invitation with guests, exporting to CSV should produce a file
     * containing all guest data (name, category).
     *
     * Validates: Requirements 5.6
     */
    public function test_property_csv_export_contains_all_guests(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        // Test with varying numbers of guests
        $testCases = [
            ['guest_count' => 0],  // Edge case: no guests
            ['guest_count' => 1],
            ['guest_count' => 5],
            ['guest_count' => 10],
            ['guest_count' => 25],
            ['guest_count' => 50],
            ['guest_count' => 100],
        ];

        foreach ($testCases as $testCase) {
            $invitation = Invitation::factory()->create([
                'user_id' => $user->id,
                'template_id' => $template->id,
            ]);

            // Create guests with random data
            $createdGuests = [];
            for ($i = 0; $i < $testCase['guest_count']; $i++) {
                $guest = Guest::factory()->create([
                    'invitation_id' => $invitation->id,
                    'name' => fake()->name(),
                    'category' => fake()->randomElement([
                        Guest::CATEGORY_FAMILY,
                        Guest::CATEGORY_FRIEND,
                        Guest::CATEGORY_COLLEAGUE,
                    ]),
                ]);
                $createdGuests[] = $guest;
            }

            // Export to CSV
            $csvPath = $this->importService->exportToCsv($invitation->id);

            // Property: CSV file should exist
            $this->assertFileExists($csvPath);

            // Read CSV file
            $handle = fopen($csvPath, 'r');
            $this->assertNotFalse($handle, 'Should be able to open CSV file');

            // Read header
            $header = fgetcsv($handle);

            // Property: CSV should have correct header
            $this->assertEquals(['name', 'category'], $header);

            // Read all data rows
            $csvRows = [];
            while (($row = fgetcsv($handle)) !== false) {
                if (!empty(array_filter($row))) {
                    $csvRows[] = $row;
                }
            }
            fclose($handle);

            // Property: CSV row count should match guest count
            $this->assertEquals($testCase['guest_count'], count($csvRows));

            // Property: All guests should be in CSV
            foreach ($createdGuests as $guest) {
                $found = false;
                foreach ($csvRows as $row) {
                    if ($row[0] === $guest->name && $row[1] === $guest->category) {
                        $found = true;
                        break;
                    }
                }
                $this->assertTrue($found, "Guest '{$guest->name}' with category '{$guest->category}' should be in CSV");
            }

            // Property: CSV should not contain extra rows
            $this->assertEquals(count($createdGuests), count($csvRows));

            // Property: All CSV rows should have valid data
            foreach ($csvRows as $row) {
                $this->assertNotEmpty($row[0], 'Name should not be empty');
                $this->assertContains($row[1], [
                    Guest::CATEGORY_FAMILY,
                    Guest::CATEGORY_FRIEND,
                    Guest::CATEGORY_COLLEAGUE,
                ], 'Category should be valid');
            }

            // Cleanup
            if (file_exists($csvPath)) {
                unlink($csvPath);
            }
            $invitation->delete();
        }
    }

    /**
     * Property 24: CSV Import Round Trip Preserves Data
     *
     * For any guest list, exporting to CSV then importing that CSV should
     * result in the same guest data (round-trip property).
     *
     * Validates: Requirements 5.7
     */
    public function test_property_csv_import_round_trip_preserves_data(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        // Run test 50 times with random data
        for ($iteration = 0; $iteration < 50; $iteration++) {
            // Create invitation for export
            $exportInvitation = Invitation::factory()->create([
                'user_id' => $user->id,
                'template_id' => $template->id,
            ]);

            // Create random number of guests (1-20)
            $guestCount = rand(1, 20);
            $originalGuests = [];

            for ($i = 0; $i < $guestCount; $i++) {
                $guest = Guest::factory()->create([
                    'invitation_id' => $exportInvitation->id,
                    'name' => fake()->name(),
                    'category' => fake()->randomElement([
                        Guest::CATEGORY_FAMILY,
                        Guest::CATEGORY_FRIEND,
                        Guest::CATEGORY_COLLEAGUE,
                    ]),
                ]);
                $originalGuests[] = [
                    'name' => $guest->name,
                    'category' => $guest->category,
                ];
            }

            // Export to CSV
            $csvPath = $this->importService->exportToCsv($exportInvitation->id);
            $this->assertFileExists($csvPath);

            // Create new invitation for import
            $importInvitation = Invitation::factory()->create([
                'user_id' => $user->id,
                'template_id' => $template->id,
            ]);

            // Create UploadedFile from exported CSV
            $uploadedFile = new UploadedFile(
                $csvPath,
                basename($csvPath),
                'text/csv',
                null,
                true
            );

            // Import CSV
            $result = $this->importService->importFromCsv($uploadedFile, $importInvitation->id);

            // Property: Import should succeed for all guests
            $this->assertEquals($guestCount, $result['success'], 'All guests should be imported successfully');
            $this->assertEquals(0, $result['failed'], 'No guests should fail to import');
            $this->assertEmpty($result['errors'], 'There should be no import errors');

            // Retrieve imported guests
            $importedGuests = Guest::where('invitation_id', $importInvitation->id)
                ->orderBy('name')
                ->get();

            // Property: Imported guest count should match original count
            $this->assertEquals($guestCount, $importedGuests->count());

            // Sort original guests by name for comparison
            usort($originalGuests, fn($a, $b) => strcmp($a['name'], $b['name']));

            // Property: All original guest data should be preserved
            foreach ($originalGuests as $index => $originalGuest) {
                $importedGuest = $importedGuests[$index];

                $this->assertEquals(
                    $originalGuest['name'],
                    $importedGuest->name,
                    "Guest name should be preserved in round trip"
                );

                $this->assertEquals(
                    $originalGuest['category'],
                    $importedGuest->category,
                    "Guest category should be preserved in round trip"
                );
            }

            // Property: No extra guests should be created
            $this->assertEquals(
                count($originalGuests),
                $importedGuests->count(),
                'No extra guests should be created during import'
            );

            // Property: Each imported guest should match exactly one original guest
            foreach ($importedGuests as $importedGuest) {
                $found = false;
                foreach ($originalGuests as $originalGuest) {
                    if ($importedGuest->name === $originalGuest['name'] &&
                        $importedGuest->category === $originalGuest['category']) {
                        $found = true;
                        break;
                    }
                }
                $this->assertTrue($found, "Imported guest '{$importedGuest->name}' should match an original guest");
            }

            // Cleanup
            if (file_exists($csvPath)) {
                unlink($csvPath);
            }
            $exportInvitation->delete();
            $importInvitation->delete();
        }
    }
}
