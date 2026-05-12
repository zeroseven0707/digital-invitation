<?php

namespace App\Http\Controllers;

use App\Services\TemplateService;
use Illuminate\Http\Request;

/**
 * Controller for handling template selection and preview for users.
 *
 * This controller allows authenticated users to:
 * - Browse available templates
 * - Preview templates before selecting
 */
class TemplateController extends Controller
{
    protected $templateService;

    public function __construct(TemplateService $templateService)
    {
        $this->templateService = $templateService;
    }

    /**
     * Display a listing of all active templates.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $templates = $this->templateService->getAllTemplates();

        return view('templates.index', compact('templates'));
    }

    /**
     * Display a preview of a specific template.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $template = $this->templateService->getTemplate($id);

        if (!$template) {
            abort(404, 'Template not found');
        }

        $dummyData = $this->buildDummyData();

        // Render template with dummy data
        $renderedTemplate = $this->templateService->renderTemplate($template, $dummyData);

        return view('templates.show', [
            'template' => $template,
            'renderedTemplate' => $renderedTemplate,
        ]);
    }

    /**
     * Display a listing of all active templates for public (no auth required).
     *
     * @return \Illuminate\View\View
     */
    public function publicIndex()
    {
        $templates = $this->templateService->getAllTemplates();

        return view('public.templates.index', compact('templates'));
    }

    /**
     * Display a preview of a specific template for public (no auth required).
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function publicPreview($id)
    {
        $template = $this->templateService->getTemplate($id);

        if (!$template) {
            abort(404, 'Template not found');
        }

        $dummyData = $this->buildDummyData();

        // Render template with dummy data
        $renderedTemplate = $this->templateService->renderTemplate($template, $dummyData);

        return view('public.templates.preview', [
            'template' => $template,
            'renderedTemplate' => $renderedTemplate,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  SHARED DUMMY DATA
    // ─────────────────────────────────────────────────────────────

    /**
     * Build dummy data for template preview.
     * Includes all variables needed by partials (gift-section, qr-section).
     */
    private function buildDummyData(): array
    {
        $eventDateTime = \Carbon\Carbon::now()->addMonths(3)->setTime(9, 0, 0);

        // Dummy invitation object — mimics Eloquent model properties used in partials
        $dummyInvitation = new class {
            public string $unique_url      = 'preview-template';
            public string $bride_name      = 'Sarah';
            public string $groom_name      = 'Ahmad';
            public string $akad_location   = 'Gedung Pernikahan Indah';
            public string $reception_location = 'Gedung Pernikahan Indah';
            public string $full_address    = 'Jl. Merdeka No. 123, Jakarta Pusat, DKI Jakarta 10110';
            public ?string $status         = 'published';
            public bool $is_paid           = true;
            public int $id                 = 0;

            // Mimic Eloquent collection properties
            public function __get(string $name)
            {
                if (in_array($name, ['galleries', 'guests', 'rsvps'])) {
                    return collect();
                }
                return null;
            }
        };

        // Dummy guest for QR preview — null means QR section won't render
        // (QR section only shows when a real guest token is present)
        $guestForQr = null;

        return [
            'invitation'          => $dummyInvitation,
            'guestForQr'          => $guestForQr,
            // Template variables
            'bride_name'          => 'Sarah',
            'groom_name'          => 'Ahmad',
            'bride_parents'       => 'Bapak Budi & Ibu Siti',
            'groom_parents'       => 'Bapak Joko & Ibu Dewi',
            'event_name'          => 'Akad Nikah',
            'event_date'          => $eventDateTime->format('d F Y'),
            'event_time'          => '09:00 WIB - 11:00 WIB',
            'event_location'      => 'Gedung Pernikahan Indah',
            'event_address'       => 'Jl. Merdeka No. 123, Jakarta Pusat, DKI Jakarta 10110',
            'guest_name'          => 'Bapak/Ibu/Saudara/i',
            'bride_father_name'   => 'Bapak Budi',
            'bride_mother_name'   => 'Ibu Siti',
            'groom_father_name'   => 'Bapak Joko',
            'groom_mother_name'   => 'Ibu Dewi',
            'akad_date'           => $eventDateTime->format('Y-m-d'),
            'akad_date_formatted' => $eventDateTime->format('Y-m-d H:i:s'),
            'akad_time_start'     => '09:00',
            'akad_time_end'       => '11:00',
            'akad_location'       => 'Gedung Pernikahan Indah',
            'reception_date'      => $eventDateTime->format('Y-m-d'),
            'reception_date_formatted' => $eventDateTime->format('Y-m-d H:i:s'),
            'reception_time_start'=> '18:00',
            'reception_time_end'  => '21:00',
            'reception_location'  => 'Gedung Pernikahan Indah',
            'full_address'        => 'Jl. Merdeka No. 123, Jakarta Pusat, DKI Jakarta 10110',
            'latitude'            => -6.200000,
            'longitude'           => 106.816666,
            'google_maps_url'     => 'https://maps.google.com/?q=-6.200000,106.816666',
            'music_url'           => null,
            'galleries'           => collect(),
            'guests'              => collect(),
            'rsvps'               => collect(),
            'rsvps_count'         => 0,
        ];
    }
}
