<?php

namespace Tests\Unit;

use App\Models\Guest;
use App\Models\Invitation;
use App\Models\User;
use App\Services\GuestImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class GuestImportServiceTest extends TestCase
{
    use RefreshDatabase;

    private GuestImportService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new GuestImportService();
    }

    public function test_export_to_csv_creates_file_with_all_guests()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        $guests = Guest::factory()->count(3)->create([
            'invitation_id' => $invitation->id,
        ]);

        $filepath = $this->service->exportToCsv($invitation->id);

        $this->assertFileExists($filepath);

        $content = file_get_contents($filepath);
        $lines = explode("\n", trim($content));

        // Check header
        $this->assertStringContainsString('name,category', $lines[0]);

        // Check data rows (should have 3 guests + 1 header)
        $this->assertCount(4, $lines);

        // Verify each guest is in the CSV
        foreach ($guests as $guest) {
            $this->assertStringContainsString($guest->name, $content);
            $this->assertStringContainsString($guest->category, $content);
        }

        // Cleanup
        unlink($filepath);
    }

    public function test_export_to_csv_with_no_guests_creates_empty_csv()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        $filepath = $this->service->exportToCsv($invitation->id);

        $this->assertFileExists($filepath);

        $content = file_get_contents($filepath);
        $lines = explode("\n", trim($content));

        // Should only have header
        $this->assertCount(1, $lines);
        $this->assertStringContainsString('name,category', $lines[0]);

        // Cleanup
        unlink($filepath);
    }

    public function test_import_from_csv_creates_guests()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Create CSV content
        $csvContent = "name,category\n";
        $csvContent .= "John Doe,family\n";
        $csvContent .= "Jane Smith,friend\n";
        $csvContent .= "Bob Johnson,colleague\n";

        // Create temporary file
        $tempFile = tmpfile();
        fwrite($tempFile, $csvContent);
        $tempPath = stream_get_meta_data($tempFile)['uri'];

        $file = new UploadedFile($tempPath, 'guests.csv', 'text/csv', null, true);

        $result = $this->service->importFromCsv($file, $invitation->id);

        $this->assertEquals(3, $result['success']);
        $this->assertEquals(0, $result['failed']);
        $this->assertEmpty($result['errors']);

        // Verify guests were created
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
            'name' => 'Bob Johnson',
            'category' => 'colleague',
        ]);

        fclose($tempFile);
    }

    public function test_import_from_csv_validates_category()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Create CSV with invalid category
        $csvContent = "name,category\n";
        $csvContent .= "John Doe,family\n";
        $csvContent .= "Jane Smith,invalid_category\n";
        $csvContent .= "Bob Johnson,colleague\n";

        $tempFile = tmpfile();
        fwrite($tempFile, $csvContent);
        $tempPath = stream_get_meta_data($tempFile)['uri'];

        $file = new UploadedFile($tempPath, 'guests.csv', 'text/csv', null, true);

        $result = $this->service->importFromCsv($file, $invitation->id);

        $this->assertEquals(2, $result['success']);
        $this->assertEquals(1, $result['failed']);
        $this->assertCount(1, $result['errors']);

        // Verify valid guests were created
        $this->assertDatabaseHas('guests', [
            'invitation_id' => $invitation->id,
            'name' => 'John Doe',
        ]);

        $this->assertDatabaseHas('guests', [
            'invitation_id' => $invitation->id,
            'name' => 'Bob Johnson',
        ]);

        // Verify invalid guest was not created
        $this->assertDatabaseMissing('guests', [
            'invitation_id' => $invitation->id,
            'name' => 'Jane Smith',
        ]);

        fclose($tempFile);
    }

    public function test_import_from_csv_validates_required_fields()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Create CSV with missing name
        $csvContent = "name,category\n";
        $csvContent .= ",family\n";
        $csvContent .= "Jane Smith,friend\n";

        $tempFile = tmpfile();
        fwrite($tempFile, $csvContent);
        $tempPath = stream_get_meta_data($tempFile)['uri'];

        $file = new UploadedFile($tempPath, 'guests.csv', 'text/csv', null, true);

        $result = $this->service->importFromCsv($file, $invitation->id);

        $this->assertEquals(1, $result['success']);
        $this->assertEquals(1, $result['failed']);

        // Verify only valid guest was created
        $this->assertDatabaseHas('guests', [
            'invitation_id' => $invitation->id,
            'name' => 'Jane Smith',
        ]);

        fclose($tempFile);
    }

    public function test_import_from_csv_throws_exception_for_invalid_header()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Create CSV with wrong header
        $csvContent = "wrong,header\n";
        $csvContent .= "John Doe,family\n";

        $tempFile = tmpfile();
        fwrite($tempFile, $csvContent);
        $tempPath = stream_get_meta_data($tempFile)['uri'];

        $file = new UploadedFile($tempPath, 'guests.csv', 'text/csv', null, true);

        $this->expectException(ValidationException::class);

        $this->service->importFromCsv($file, $invitation->id);

        fclose($tempFile);
    }

    public function test_import_from_csv_skips_empty_rows()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Create CSV with empty rows
        $csvContent = "name,category\n";
        $csvContent .= "John Doe,family\n";
        $csvContent .= "\n";
        $csvContent .= "Jane Smith,friend\n";
        $csvContent .= "\n";

        $tempFile = tmpfile();
        fwrite($tempFile, $csvContent);
        $tempPath = stream_get_meta_data($tempFile)['uri'];

        $file = new UploadedFile($tempPath, 'guests.csv', 'text/csv', null, true);

        $result = $this->service->importFromCsv($file, $invitation->id);

        $this->assertEquals(2, $result['success']);
        $this->assertEquals(0, $result['failed']);

        fclose($tempFile);
    }

    public function test_csv_round_trip_preserves_data()
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['user_id' => $user->id]);

        // Create original guests
        $originalGuests = Guest::factory()->count(5)->create([
            'invitation_id' => $invitation->id,
        ])->sortBy('name')->values();

        // Export to CSV
        $csvPath = $this->service->exportToCsv($invitation->id);

        // Delete all guests
        Guest::where('invitation_id', $invitation->id)->delete();

        // Import from CSV
        $file = new UploadedFile($csvPath, 'guests.csv', 'text/csv', null, true);
        $result = $this->service->importFromCsv($file, $invitation->id);

        // Verify import was successful
        $this->assertEquals(5, $result['success']);
        $this->assertEquals(0, $result['failed']);

        // Get imported guests
        $importedGuests = Guest::where('invitation_id', $invitation->id)
            ->get()
            ->sortBy('name')
            ->values();

        // Verify data matches
        $this->assertCount($originalGuests->count(), $importedGuests);

        foreach ($originalGuests as $index => $original) {
            $this->assertEquals($original->name, $importedGuests[$index]->name);
            $this->assertEquals($original->category, $importedGuests[$index]->category);
        }

        // Cleanup
        unlink($csvPath);
    }
}
