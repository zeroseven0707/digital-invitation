<?php

namespace App\Services;

use App\Models\Template;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

/**
 * Service for managing wedding invitation templates.
 *
 * This service handles template retrieval and rendering with dynamic data binding.
 * Templates are stored in storage/app/templates/ and use Blade syntax for data binding.
 */
class TemplateService
{
    /**
     * Get all active templates.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllTemplates()
    {
        return Template::where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get a specific template by ID.
     *
     * @param int $id
     * @return Template|null
     */
    public function getTemplate($id)
    {
        return Template::where('is_active', true)->find($id);
    }

    /**
     * Render template with invitation data.
     *
     * @param Template $template
     * @param array $data
     * @return string
     */
    public function renderTemplate(Template $template, array $data)
    {
        // Read template HTML file from public storage
        $templatePath = $template->html_path;

        // Use public disk to access storage/app/public
        if (!Storage::disk('public')->exists($templatePath)) {
            throw new \Exception("Template file not found: {$templatePath}");
        }

        $templateHtml = Storage::disk('public')->get($templatePath);

        // Create a temporary Blade view
        $tempViewName = 'temp_template_' . $template->id . '_' . time();
        $tempViewPath = resource_path('views/temp/' . $tempViewName . '.blade.php');

        // Ensure temp directory exists
        if (!file_exists(resource_path('views/temp'))) {
            mkdir(resource_path('views/temp'), 0755, true);
        }

        // ── Inject partials ──
        // Template bisa punya placeholder <!-- GIFT_SECTION --> dan <!-- QR_SECTION -->
        // Jika tidak ada placeholder, inject otomatis (gift sebelum RSVP, QR sebelum </body>)

        $qrInclude   = "\n@include('public.partials.qr-section')\n";
        $giftInclude = "\n@include('public.partials.gift-section')\n";

        // QR section
        if (str_contains($templateHtml, '<!-- QR_SECTION -->')) {
            $templateHtml = str_replace('<!-- QR_SECTION -->', $qrInclude, $templateHtml);
        } else {
            $templateHtml = str_ireplace('</body>', $qrInclude . '</body>', $templateHtml);
        }

        // Gift section
        if (str_contains($templateHtml, '<!-- GIFT_SECTION -->')) {
            $templateHtml = str_replace('<!-- GIFT_SECTION -->', $giftInclude, $templateHtml);
        } else {
            // Cari section RSVP untuk inject sebelumnya
            $rsvpMarkers = ['id="rsvp"', "id='rsvp'", 'id="rsvp-section"', "id='rsvp-section'"];
            $injected = false;
            foreach ($rsvpMarkers as $marker) {
                if (stripos($templateHtml, $marker) !== false) {
                    $pattern = '/(<(?:section|div)[^>]*' . preg_quote($marker, '/') . '[^>]*>)/i';
                    $templateHtml = preg_replace($pattern, $giftInclude . '$1', $templateHtml, 1);
                    $injected = true;
                    break;
                }
            }
            if (!$injected) {
                $templateHtml = str_ireplace('</body>', $giftInclude . '</body>', $templateHtml);
            }
        }

        // Write template content to temporary view file
        file_put_contents($tempViewPath, $templateHtml);

        try {
            // Render the Blade template with data
            // Add Laravel's default view variables
            $data['errors'] = session()->get('errors', new \Illuminate\Support\ViewErrorBag());

            $rendered = View::make('temp.' . $tempViewName, $data)->render();
        } finally {
            // Clean up temporary view file
            if (file_exists($tempViewPath)) {
                unlink($tempViewPath);
            }
        }

        return $rendered;
    }
}
