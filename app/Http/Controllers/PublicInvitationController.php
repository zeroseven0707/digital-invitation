<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Services\InvitationViewTracker;
use App\Services\TemplateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicInvitationController extends Controller
{
    protected TemplateService $templateService;
    protected InvitationViewTracker $viewTracker;

    public function __construct(TemplateService $templateService, InvitationViewTracker $viewTracker)
    {
        $this->templateService = $templateService;
        $this->viewTracker = $viewTracker;
    }

    /**
     * Display the invitation by unique URL.
     *
     * @param string $uniqueUrl
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function show(string $uniqueUrl, Request $request)
    {
        // Find invitation by unique URL and ensure it's published
        $invitation = Invitation::where('unique_url', $uniqueUrl)
            ->where('status', 'published')
            ->with(['template', 'galleries', 'guests', 'rsvps'])
            ->first();

        // Return 404 if invitation not found or not published
        if (!$invitation) {
            abort(404);
        }

        // Track the view
        $this->viewTracker->trackView($invitation, $request);

        // Format dates for JavaScript countdown
        $akadDateTime = $invitation->akad_date->format('Y-m-d') . ' ' . $invitation->akad_time_start;
        $receptionDateTime = $invitation->reception_date->format('Y-m-d') . ' ' . $invitation->reception_time_start;

        // Prepare data for template rendering
        $data = [
            'invitation' => $invitation, // Pass the invitation object
            'bride_name' => $invitation->bride_name,
            'bride_father_name' => $invitation->bride_father_name,
            'bride_mother_name' => $invitation->bride_mother_name,
            'groom_name' => $invitation->groom_name,
            'groom_father_name' => $invitation->groom_father_name,
            'groom_mother_name' => $invitation->groom_mother_name,
            'akad_date' => $invitation->akad_date->format('Y-m-d'),
            'akad_date_formatted' => $akadDateTime,
            'akad_time_start' => $invitation->akad_time_start,
            'akad_time_end' => $invitation->akad_time_end,
            'akad_location' => $invitation->akad_location,
            'reception_date' => $invitation->reception_date->format('Y-m-d'),
            'reception_date_formatted' => $receptionDateTime,
            'reception_time_start' => $invitation->reception_time_start,
            'reception_time_end' => $invitation->reception_time_end,
            'reception_location' => $invitation->reception_location,
            'full_address' => $invitation->full_address,
            'latitude' => $invitation->latitude,
            'longitude' => $invitation->longitude,
            'google_maps_url' => $invitation->google_maps_url,
            'music_url' => $invitation->music_path ? Storage::disk('public')->url($invitation->music_path) : null,
            'galleries' => $invitation->galleries,
            'guests' => $invitation->guests,
            'rsvps' => $invitation->rsvps()->latest()->take(10)->get(),
            'rsvps_count' => $invitation->rsvps()->count(),
        ];

        $renderedTemplate = $this->templateService->renderTemplate($invitation->template, $data);

        return view('public.invitation', [
            'invitation' => $invitation,
            'renderedTemplate' => $renderedTemplate,
        ]);
    }
}
