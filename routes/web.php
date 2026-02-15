<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminTemplateController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicInvitationController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\TemplateController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $templates = \App\Models\Template::where('is_active', true)
        ->orderBy('name')
        ->limit(9)
        ->get();

    // Get stats
    $stats = [
        'invitations' => \App\Models\Invitation::count(),
        'templates' => \App\Models\Template::where('is_active', true)->count(),
        'views' => \App\Models\InvitationView::count(),
        'rsvps' => \App\Models\Rsvp::count(),
    ];

    return view('welcome', compact('templates', 'stats'));
});

// Public invitation route (no authentication required) with rate limiting
Route::get('/i/{uniqueUrl}', [PublicInvitationController::class, 'show'])
    ->middleware('throttle:60,1')
    ->name('public.invitation');

// RSVP route (no authentication required)
Route::post('/i/{uniqueUrl}/rsvp', [App\Http\Controllers\RsvpController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('rsvp.store');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'block.admin'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Guide route
    Route::get('/guide', function () {
        return view('guide');
    })->name('guide');

    // Template routes
    Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
    Route::get('/templates/{id}', [TemplateController::class, 'show'])->name('templates.show');

    // Invitation routes
    Route::get('/invitations/create', [InvitationController::class, 'create'])->name('invitations.create');
    Route::post('/invitations', [InvitationController::class, 'store'])->name('invitations.store');
    Route::get('/invitations/{id}', [InvitationController::class, 'show'])->name('invitations.show');
    Route::get('/invitations/{id}/edit', [InvitationController::class, 'edit'])->name('invitations.edit');
    Route::put('/invitations/{invitation}', [InvitationController::class, 'update'])->name('invitations.update');
    Route::delete('/invitations/{id}', [InvitationController::class, 'destroy'])->name('invitations.destroy');
    Route::get('/invitations/{id}/preview', [InvitationController::class, 'preview'])->name('invitations.preview');
    Route::post('/invitations/{id}/publish', [InvitationController::class, 'publish'])->name('invitations.publish');
    Route::post('/invitations/{id}/unpublish', [InvitationController::class, 'unpublish'])->name('invitations.unpublish');

    // Gallery routes
    Route::post('/invitations/{invitation}/gallery', [GalleryController::class, 'store'])->name('gallery.store');
    Route::delete('/invitations/{invitation}/gallery/{photo}', [GalleryController::class, 'destroy'])->name('gallery.destroy');
    Route::post('/invitations/{invitation}/gallery/reorder', [GalleryController::class, 'reorder'])->name('gallery.reorder');

    // Guest routes
    Route::get('/invitations/{invitation}/guests', [GuestController::class, 'index'])->name('guests.index');
    Route::post('/invitations/{invitation}/guests', [GuestController::class, 'store'])->name('guests.store');
    Route::put('/invitations/{invitation}/guests/{guest}', [GuestController::class, 'update'])->name('guests.update');
    Route::delete('/invitations/{invitation}/guests/{guest}', [GuestController::class, 'destroy'])->name('guests.destroy');
    Route::get('/invitations/{invitation}/guests/export', [GuestController::class, 'export'])->name('guests.export');
    Route::post('/invitations/{invitation}/guests/import', [GuestController::class, 'import'])->name('guests.import');

    // Statistics routes
    Route::get('/invitations/{invitation}/statistics', [StatisticsController::class, 'show'])->name('statistics.show');
    Route::get('/invitations/{invitation}/statistics/views-chart', [StatisticsController::class, 'getViewsChart'])->name('statistics.views-chart');
    Route::get('/invitations/{invitation}/statistics/device-stats', [StatisticsController::class, 'getDeviceStats'])->name('statistics.device-stats');

    // RSVP routes
    Route::get('/invitations/{invitation}/rsvps', [App\Http\Controllers\RsvpController::class, 'index'])->name('rsvps.index');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Invitations management routes
    Route::get('/invitations', [AdminController::class, 'invitations'])->name('admin.invitations.index');
    Route::post('/invitations/{id}/activate-payment', [AdminController::class, 'activateInvitationPayment'])->name('admin.invitations.activate-payment');
    Route::post('/invitations/{id}/deactivate-payment', [AdminController::class, 'deactivateInvitationPayment'])->name('admin.invitations.deactivate-payment');

    // User management routes
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{id}', [AdminUserController::class, 'show'])->name('admin.users.show');
    Route::post('/users/{id}/deactivate', [AdminUserController::class, 'deactivate'])->name('admin.users.deactivate');
    Route::post('/users/{id}/activate', [AdminUserController::class, 'activate'])->name('admin.users.activate');
    Route::post('/users/{userId}/invitations/{invitationId}/activate-payment', [AdminUserController::class, 'activatePayment'])->name('admin.users.invitations.activate-payment');
    Route::post('/users/{userId}/invitations/{invitationId}/deactivate-payment', [AdminUserController::class, 'deactivatePayment'])->name('admin.users.invitations.deactivate-payment');

    // Template management routes
    Route::get('/templates', [AdminTemplateController::class, 'index'])->name('admin.templates.index');
    Route::get('/templates/create', [AdminTemplateController::class, 'create'])->name('admin.templates.create');
    Route::post('/templates', [AdminTemplateController::class, 'store'])->name('admin.templates.store');
    Route::delete('/templates/{id}', [AdminTemplateController::class, 'destroy'])->name('admin.templates.destroy');
});

require __DIR__.'/auth.php';
