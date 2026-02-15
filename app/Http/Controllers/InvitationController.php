<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvitationRequest;
use App\Http\Requests\UpdateInvitationRequest;
use App\Models\Invitation;
use App\Services\InvitationService;
use App\Services\TemplateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InvitationController extends Controller
{
    public function __construct(
        protected InvitationService $invitationService,
        protected TemplateService $templateService
    ) {}

    /**
     * Show the form for creating a new invitation (template selection).
     */
    public function create(): View
    {
        $templates = $this->templateService->getAllTemplates();

        return view('invitations.create', compact('templates'));
    }

    /**
     * Store a newly created invitation in storage.
     */
    public function store(StoreInvitationRequest $request): RedirectResponse
    {
        $invitation = $this->invitationService->createInvitation(
            $request->validated(),
            $request->user()->id
        );

        return redirect()
            ->route('invitations.show', $invitation->id)
            ->with('success', 'Undangan berhasil dibuat sebagai draft.');
    }

    /**
     * Display the specified invitation.
     */
    public function show(string $id): View
    {
        $invitation = auth()->user()->invitations()->findOrFail($id);

        return view('invitations.show', compact('invitation'));
    }

    /**
     * Show the form for editing the specified invitation.
     */
    public function edit(string $id): View
    {
        $invitation = auth()->user()->invitations()->findOrFail($id);
        $templates = $this->templateService->getAllTemplates();

        return view('invitations.edit', compact('invitation', 'templates'));
    }

    /**
     * Update the specified invitation in storage.
     */
    public function update(UpdateInvitationRequest $request, Invitation $invitation): RedirectResponse
    {
        $this->invitationService->updateInvitation($invitation, $request->validated());

        return redirect()
            ->route('invitations.show', $invitation->id)
            ->with('success', 'Undangan berhasil diperbarui.');
    }

    /**
     * Remove the specified invitation from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $invitation = auth()->user()->invitations()->findOrFail($id);

        $this->invitationService->deleteInvitation($invitation);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Undangan berhasil dihapus.');
    }

    /**
     * Preview the invitation with template and data.
     */
    public function preview(string $id): View
    {
        $invitation = auth()->user()->invitations()->with(['template', 'galleries', 'guests', 'rsvps'])->findOrFail($id);

        // Format dates for JavaScript countdown
        $akadDateTime = $invitation->akad_date->format('Y-m-d') . ' ' . $invitation->akad_time_start;
        $receptionDateTime = $invitation->reception_date->format('Y-m-d') . ' ' . $invitation->reception_time_start;

        // Prepare data for template rendering
        $data = [
            'invitation' => $invitation,
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
            'google_maps_url' => $invitation->google_maps_url,
            'music_url' => $invitation->music_url,
            'galleries' => $invitation->galleries,
            'guests' => $invitation->guests,
            'rsvps' => $invitation->rsvps()->latest()->take(10)->get(),
            'rsvps_count' => $invitation->rsvps()->count(),
        ];

        $renderedTemplate = $this->templateService->renderTemplate($invitation->template, $data);

        return view('invitations.preview', [
            'invitation' => $invitation,
            'renderedTemplate' => $renderedTemplate,
        ]);
    }

    /**
     * Publish the invitation and generate unique URL.
     */
    public function publish(string $id): RedirectResponse
    {
        $invitation = auth()->user()->invitations()->findOrFail($id);

        $uniqueUrl = $this->invitationService->publishInvitation($invitation);

        return redirect()
            ->route('invitations.show', $invitation->id)
            ->with('success', 'Undangan berhasil dipublikasikan.')
            ->with('unique_url', $uniqueUrl);
    }

    /**
     * Unpublish the invitation.
     */
    public function unpublish(string $id): RedirectResponse
    {
        $invitation = auth()->user()->invitations()->findOrFail($id);

        $this->invitationService->unpublishInvitation($invitation);

        return redirect()
            ->route('invitations.show', $invitation->id)
            ->with('success', 'Undangan berhasil di-unpublish.');
    }
}
