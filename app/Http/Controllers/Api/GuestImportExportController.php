<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Imports\GuestsImport;
use App\Models\Invitation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class GuestImportExportController extends Controller
{
    /**
     * Import guests from an uploaded Excel/CSV file.
     * Accepts .xlsx, .xls, .csv
     * Expected columns (case-insensitive, Indonesian or English):
     *   nama / name | kategori / category | whatsapp / telepon / phone
     */
    public function import(Request $request, int $invitationId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)
            ->findOrFail($invitationId);

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120', // 5 MB max
        ], [
            'file.required' => 'File harus diunggah.',
            'file.mimes'    => 'Format file harus .xlsx, .xls, atau .csv.',
            'file.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        $import = new GuestsImport($invitation->id);

        try {
            Excel::import($import, $request->file('file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak valid.',
                'errors'  => collect($e->failures())->map(fn($f) => "Baris {$f->row()}: " . implode(', ', $f->errors()))->values(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membaca file: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success'  => true,
            'message'  => "Import selesai: {$import->results['imported']} tamu berhasil ditambahkan" .
                          ($import->results['skipped'] > 0 ? ", {$import->results['skipped']} dilewati." : '.'),
            'imported' => $import->results['imported'],
            'skipped'  => $import->results['skipped'],
            'errors'   => $import->results['errors'],
        ]);
    }

    /**
     * Download a template Excel file so users know the expected format.
     */
    public function downloadTemplate()
    {
        $filename = 'template_tamu.csv';
        $tmpPath  = storage_path('app/temp/' . $filename);

        if (!is_dir(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $fp = fopen($tmpPath, 'w');
        // BOM for Excel UTF-8 compatibility
        fwrite($fp, "\xEF\xBB\xBF");
        fputcsv($fp, ['nama', 'kategori', 'whatsapp']);
        fputcsv($fp, ['Budi Santoso', 'family', '08123456789']);
        fputcsv($fp, ['Siti Rahayu', 'friend', '08987654321']);
        fputcsv($fp, ['Ahmad Fauzi', 'colleague', '']);
        fclose($fp);

        return response()->download($tmpPath, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Export a full PDF report for an invitation.
     * Includes: guest list with check-in/souvenir status + RSVP messages.
     */
    public function exportPdf(Request $request, int $invitationId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)
            ->with(['guests' => fn($q) => $q->orderBy('name'), 'rsvps' => fn($q) => $q->latest()])
            ->findOrFail($invitationId);

        $guests = $invitation->guests;
        $rsvps  = $invitation->rsvps;

        $stats = [
            'total'          => $guests->count(),
            'checked_in'     => $guests->whereNotNull('checked_in_at')->count(),
            'not_checked_in' => $guests->whereNull('checked_in_at')->count(),
            'souvenir'       => $guests->whereNotNull('souvenir_taken_at')->count(),
            'check_in_rate'  => $guests->count() > 0
                ? round(($guests->whereNotNull('checked_in_at')->count() / $guests->count()) * 100, 1)
                : 0,
        ];

        $pdf = Pdf::loadView('pdf.guest-report', compact('invitation', 'guests', 'rsvps', 'stats'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont'    => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => false,
                'dpi'            => 150,
            ]);

        $filename = 'laporan-tamu-' . str_replace(' ', '-', strtolower($invitation->bride_name)) . '-' . now()->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }
}
