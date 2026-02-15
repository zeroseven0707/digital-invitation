<?php

namespace App\Services;

use App\Models\Guest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class GuestImportService
{
    /**
     * Export guests to CSV file.
     *
     * @param int $invitationId
     * @return string Path to the generated CSV file
     */
    public function exportToCsv(int $invitationId): string
    {
        $guests = Guest::where('invitation_id', $invitationId)
            ->orderBy('name')
            ->get();

        // Create temporary file
        $filename = 'guests_' . $invitationId . '_' . time() . '.csv';
        $filepath = storage_path('app/temp/' . $filename);

        // Ensure temp directory exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        // Open file for writing
        $file = fopen($filepath, 'w');

        // Write CSV header
        fputcsv($file, ['name', 'category']);

        // Write guest data
        foreach ($guests as $guest) {
            fputcsv($file, [
                $guest->name,
                $guest->category,
            ]);
        }

        fclose($file);

        return $filepath;
    }

    /**
     * Import guests from CSV file.
     *
     * @param UploadedFile $file
     * @param int $invitationId
     * @return array Summary of import (success count, failed count, errors)
     */
    public function importFromCsv(UploadedFile $file, int $invitationId): array
    {
        $successCount = 0;
        $failedCount = 0;
        $errors = [];

        // Open and read CSV file
        $handle = fopen($file->getRealPath(), 'r');

        if ($handle === false) {
            throw new \RuntimeException('Failed to open CSV file');
        }

        // Read header row
        $header = fgetcsv($handle);

        // Validate header
        if (!$this->validateCsvHeader($header)) {
            fclose($handle);
            throw ValidationException::withMessages([
                'file' => ['Invalid CSV format. Expected columns: name, category']
            ]);
        }

        $lineNumber = 1; // Start from 1 (after header)

        // Read data rows
        while (($row = fgetcsv($handle)) !== false) {
            $lineNumber++;

            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            // Parse row data
            $data = [
                'name' => $row[0] ?? '',
                'category' => $row[1] ?? '',
                'invitation_id' => $invitationId,
            ];

            // Validate row data
            $validator = Validator::make($data, [
                'name' => 'required|string|max:255',
                'category' => 'required|in:' . implode(',', [
                    Guest::CATEGORY_FAMILY,
                    Guest::CATEGORY_FRIEND,
                    Guest::CATEGORY_COLLEAGUE,
                ]),
            ]);

            if ($validator->fails()) {
                $failedCount++;
                $errors[] = [
                    'line' => $lineNumber,
                    'data' => $row,
                    'errors' => $validator->errors()->all(),
                ];
                continue;
            }

            // Create guest
            try {
                Guest::create($data);
                $successCount++;
            } catch (\Exception $e) {
                $failedCount++;
                $errors[] = [
                    'line' => $lineNumber,
                    'data' => $row,
                    'errors' => ['Failed to create guest: ' . $e->getMessage()],
                ];
            }
        }

        fclose($handle);

        return [
            'success' => $successCount,
            'failed' => $failedCount,
            'errors' => $errors,
        ];
    }

    /**
     * Validate CSV header format.
     *
     * @param array|false $header
     * @return bool
     */
    private function validateCsvHeader($header): bool
    {
        if ($header === false || count($header) < 2) {
            return false;
        }

        // Check if header contains required columns (case-insensitive)
        $expectedColumns = ['name', 'category'];
        $normalizedHeader = array_map('strtolower', array_map('trim', $header));

        return $normalizedHeader[0] === $expectedColumns[0]
            && $normalizedHeader[1] === $expectedColumns[1];
    }
}
