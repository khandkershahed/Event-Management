<?php

use App\Http\Controllers\Frontend\Api\HomeApiController;
use App\Http\Controllers\Frontend\Api\UserApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1')->group(function () {
    Route::post('/register', [UserApiController::class, 'register']);
    Route::post('/login', [UserApiController::class, 'login']);
    Route::post('/reset-password/{token}', [UserApiController::class, 'reset']);
    Route::post('/forgot-password', [UserApiController::class, 'forgotPassword']);

    Route::get('/event-types', [HomeApiController::class, 'allEventTypes']);
    Route::get('/site-informations', [HomeApiController::class, 'siteInformations']);
    Route::get('/event-type-events/{slug}', [HomeApiController::class, 'typeWiseEvents']);
    Route::get('/events', [HomeApiController::class, 'allEvents']);
    Route::get('/event-details/{slug}', [HomeApiController::class, 'eventDetails']);
    Route::get('/search', [HomeApiController::class, 'globalSearch']);
    Route::get('/search-suggestions', [HomeApiController::class, 'searchSuggestions']);
    Route::post('/contact', [HomeApiController::class, 'contactStore']);
    Route::post('/contact/add', [HomeApiController::class, 'contactStore']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [UserApiController::class, 'logout']);
        Route::get('/profile', [UserApiController::class, 'profile']);
        Route::put('/profile', [UserApiController::class, 'updateProfile']);
        Route::post('/change-password', [UserApiController::class, 'changePassword']);
        Route::delete('/delete-account', [UserApiController::class, 'deleteAccount']);
        Route::get('/email-verification', [UserApiController::class, 'sendemailVerification']);
        Route::post('/email-verification', [UserApiController::class, 'emailVerification']);
        Route::get('/tickets', [UserApiController::class, 'tickets']);
    });
});
