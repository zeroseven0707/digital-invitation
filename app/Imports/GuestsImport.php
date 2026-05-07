<?php

namespace App\Imports;

use App\Models\Guest;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class GuestsImport implements ToCollection, WithHeadingRow
{
    private int $invitationId;
    public array $results = ['imported' => 0, 'skipped' => 0, 'errors' => []];

    // Accepted category aliases
    private const CATEGORY_MAP = [
        'family'    => 'family',
        'keluarga'  => 'family',
        'friend'    => 'friend',
        'teman'     => 'friend',
        'colleague' => 'colleague',
        'rekan'     => 'colleague',
        'kolega'    => 'colleague',
    ];

    public function __construct(int $invitationId)
    {
        $this->invitationId = $invitationId;
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // +2 because row 1 is header

            $name     = trim((string) ($row['nama'] ?? $row['name'] ?? ''));
            $category = strtolower(trim((string) ($row['kategori'] ?? $row['category'] ?? 'family')));
            $phone    = trim((string) ($row['whatsapp'] ?? $row['telepon'] ?? $row['phone'] ?? ''));

            if (empty($name)) {
                $this->results['skipped']++;
                continue;
            }

            $resolvedCategory = self::CATEGORY_MAP[$category] ?? 'family';

            try {
                Guest::create([
                    'invitation_id'  => $this->invitationId,
                    'name'           => $name,
                    'category'       => $resolvedCategory,
                    'whatsapp_number'=> $phone ?: null,
                    'qr_token'       => $this->generateToken(),
                ]);
                $this->results['imported']++;
            } catch (\Exception $e) {
                $this->results['errors'][] = "Baris {$rowNum}: {$name} — {$e->getMessage()}";
                $this->results['skipped']++;
            }
        }
    }

    private function generateToken(): string
    {
        do {
            $token = Str::random(32);
        } while (Guest::where('qr_token', $token)->exists());
        return $token;
    }
}
