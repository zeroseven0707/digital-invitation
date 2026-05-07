<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicInvitationController;
use App\Http\Controllers\RsvpController;

/*
|--------------------------------------------------------------------------
| Mobile API Routes
|--------------------------------------------------------------------------
|
| Routes untuk mobile application (React Native)
| Semua response dalam format JSON
|
*/

// Public invitation route
Route::get('/invitations/{uniqueUrl}', [PublicInvitationController::class, 'showMobile'])
    ->middleware('throttle:60,1')
    ->name('api.mobile.invitation.show');

// RSVP routes
Route::post('/invitations/{uniqueUrl}/rsvp', [RsvpController::class, 'storeMobile'])
    ->middleware('throttle:10,1')
    ->name('api.mobile.rsvp.store');

Route::get('/invitations/{uniqueUrl}/rsvp', [RsvpController::class, 'latestMobile'])
    ->middleware('throttle:60,1')
    ->name('api.mobile.rsvp.latest');
