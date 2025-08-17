<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\BookingController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\Api\HomeApiController;
use App\Http\Controllers\Frontend\Api\UserApiController;


// Login
Route::prefix('api/v1')->group(function () {
    // 🚪 Public routes
    Route::post('/register', [UserApiController::class, 'register']);
    Route::post('/login', [UserApiController::class, 'login']);
    // 🔒 Protected routes (requires auth:sanctum)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [UserApiController::class, 'logout']);
        // 👤 User profile
        Route::get('/profile', [UserApiController::class, 'profile']);
        Route::put('/profile', [UserApiController::class, 'updateProfile']);
        // 🔑 Change password
        Route::post('/change-password', [UserApiController::class, 'changePassword']);
        // 🗑️ Optional: delete account
        Route::delete('/delete-account', [UserApiController::class, 'deleteAccount']);
    });
});

Route::prefix('api/v1')->group(function () {
    Route::get('/event-types', [HomeApiController::class, 'allEventTypes']);
    Route::get('/site-informations', [HomeApiController::class, 'siteInformations']);

    Route::get('/event-type-events/{slug}', [HomeApiController::class, 'typeWiseEvents']);

    Route::get('/events', [HomeApiController::class, 'allEvents']);
    Route::get('/event-details/{slug}', [HomeApiController::class, 'eventDetails']);
    Route::get('/search', [HomeApiController::class, 'globalSearch']);
    Route::get('/search', [HomeApiController::class, 'globalSearch']);
    Route::get('/search-suggestions', [HomeApiController::class, 'searchSuggestions']);
    Route::get('/advertisements', [HomeApiController::class, 'advertisements']);
    Route::get('/epapers', [HomeApiController::class, 'ePaper']);
    Route::get('/epaper-details/{slug}', [HomeApiController::class, 'ePaperDetails']);
    Route::post('/contact/add', [HomeApiController::class, 'contactStore']);
    // Route::post('/faqs', [HomeApiController::class, 'faqs']);
    // Route::post('/register', [UserApiController::class, 'register']);
    // Route::post('/login', [UserApiController::class, 'login']);
    // Route::post('/reset-password/{token}', [UserApiController::class, 'reset']);
    // Route::post('/forgot-password', [UserApiController::class, 'forgotPassword']);

    // Route::middleware('auth:sanctum')->group(function () {
    //     Route::post('/logout', [UserApiController::class, 'logout']);
    //     Route::get('/email-verification', [UserApiController::class, 'sendemailVerification']);
    //     Route::post('/email-verification', [UserApiController::class, 'emailVerification']);
    //     Route::post('/change-password', [UserApiController::class, 'updatePassword']);
    //     Route::get('/profile', [UserApiController::class, 'profile']);
    //     Route::put('/profile', [UserApiController::class, 'editProfile']);
    // });

    // Route::get('/register', [UserApiController::class, 'register']);
    // Route::get('/login', [UserApiController::class, 'login']);
    Route::post('/register', [UserApiController::class, 'register']);
    Route::post('/login', [UserApiController::class, 'login']);
    Route::post('/reset-password/{token}', [UserApiController::class, 'reset']);
    Route::post('/forgot-password', [UserApiController::class, 'forgotPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [UserApiController::class, 'logout']);
        Route::get('/email-verification', [UserApiController::class, 'sendemailVerification']);
        Route::post('/email-verification', [UserApiController::class, 'emailVerification']);
        Route::post('/change-password', [UserApiController::class, 'updatePassword']);
        Route::get('/profile', [UserApiController::class, 'profile']);
        Route::put('/profile', [UserApiController::class, 'editProfile']);
        Route::post('/booking/initiate', [BookingController::class, 'initiateBooking']);
        Route::get('/tickets', [UserApiController::class, 'tickets']);
    });

    // Home


    // Contact
    Route::post('/contact', [HomeApiController::class, 'contactStore']);
    // Route::post('/contact', [ContactApiController::class, 'store']);


    // Product Search
    Route::post('/search', [HomeApiController::class, 'productSearch']);
    Route::post('/search/suggestions', [HomeApiController::class, 'searchSuggestions']);

    // Terms and Privacy
    Route::get('/terms-and-conditions', [HomeApiController::class, 'termsCondition']);
    Route::get('/privacy', [HomeApiController::class, 'privacyPolicy']);

    // Wallet & FAQ
    Route::get('/wallet', [HomeApiController::class, 'wallet']);
    Route::get('/faq', [HomeApiController::class, 'frequentlyAsked']);

    // initiate booking
    // routes/api.php


});
