<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InvitationController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\GuestController;
use App\Http\Controllers\Api\GuestImportExportController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\RsvpController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\TemplateController;

/*
|--------------------------------------------------------------------------
| API Routes for Mobile App
|--------------------------------------------------------------------------
|
| Routes untuk mobile application (dashboard client)
| Menggunakan Laravel Sanctum untuk authentication
|
*/

// Public routes (no authentication required)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Midtrans webhook — public, no auth (Midtrans calls this)
Route::post('/payment/notification', [PaymentController::class, 'notification'])
    ->withoutMiddleware(['throttle']);

// Public polling endpoint for display screen (no auth needed, rate limited)
Route::get('/display/{uniqueUrl}/latest-checkin', [GuestController::class, 'latestCheckIn'])
    ->middleware('throttle:60,1');

// Protected routes (authentication required)
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::delete('/account', [AuthController::class, 'deleteAccount']);

    // Templates
    Route::get('/templates', [TemplateController::class, 'index']);
    Route::get('/templates/{id}', [TemplateController::class, 'show']);

    // Invitations
    Route::get('/invitations', [InvitationController::class, 'index']);
    Route::post('/invitations', [InvitationController::class, 'store']);
    Route::get('/invitations/{id}', [InvitationController::class, 'show']);
    Route::put('/invitations/{id}', [InvitationController::class, 'update']);
    Route::delete('/invitations/{id}', [InvitationController::class, 'destroy']);
    Route::post('/invitations/{id}/publish', [InvitationController::class, 'publish']);
    Route::post('/invitations/{id}/unpublish', [InvitationController::class, 'unpublish']);

    // Payment
    Route::post('/invitations/{invitation}/payment/create', [PaymentController::class, 'create']);
    Route::get('/invitations/{invitation}/payment/status', [PaymentController::class, 'status']);

    // Gallery
    Route::get('/invitations/{invitation}/gallery', [GalleryController::class, 'index']);
    Route::post('/invitations/{invitation}/gallery', [GalleryController::class, 'store']);
    Route::delete('/invitations/{invitation}/gallery/{photo}', [GalleryController::class, 'destroy']);
    Route::post('/invitations/{invitation}/gallery/reorder', [GalleryController::class, 'reorder']);

    // Guests
    Route::get('/invitations/{invitation}/guests', [GuestController::class, 'index']);
    Route::post('/invitations/{invitation}/guests', [GuestController::class, 'store']);
    Route::put('/invitations/{invitation}/guests/{guest}', [GuestController::class, 'update']);
    Route::delete('/invitations/{invitation}/guests/{guest}', [GuestController::class, 'destroy']);
    Route::post('/invitations/{invitation}/guests/{guest}/generate-qr', [GuestController::class, 'generateQr']);
    Route::post('/invitations/{invitation}/guests/{guest}/reset-checkin', [GuestController::class, 'resetCheckIn']);
    Route::post('/invitations/{invitation}/guests/{guest}/reset-souvenir', [GuestController::class, 'resetSouvenir']);

    // QR Scan actions
    Route::post('/invitations/{invitation}/checkin', [GuestController::class, 'checkIn']);
    Route::post('/invitations/{invitation}/souvenir-scan', [GuestController::class, 'souvenirScan']);

    // Scan analytics
    Route::get('/invitations/{invitation}/scan-analytics', [GuestController::class, 'scanAnalytics']);

    // Import / Export
    Route::post('/invitations/{invitation}/guests/import', [GuestImportExportController::class, 'import']);
    Route::get('/invitations/{invitation}/guests/export-pdf', [GuestImportExportController::class, 'exportPdf']);

    // Template download (no invitation needed)
    Route::get('/guests/import-template', [GuestImportExportController::class, 'downloadTemplate']);

    // RSVPs
    Route::get('/invitations/{invitation}/rsvps', [RsvpController::class, 'index']);

    // Statistics
    Route::get('/invitations/{invitation}/statistics', [StatisticsController::class, 'show']);
});
