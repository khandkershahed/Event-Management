<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PaymentController;
// use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Frontend\UserDashboardController;
use App\Http\Controllers\Frontend\ModeratorDashboardController;






// Route::group(['middleware' => ['auth:web', 'verified', 'check_role:user'], 'prefix' => 'user', 'as' => 'user.'], function () {
//     Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
// });

// Route::group(['middleware' => ['auth:web', 'verified', 'check_role:moderator'], 'prefix' => 'moderator', 'as' => 'moderator.'], function () {
//     Route::get('/dashboard', [ModeratorDashboardController::class, 'index'])->name('dashboard');
// });

// Route::get('/admin/dashboard', function () {
//     return view('admin.dashboard');
// })->middleware(['auth:admin', 'verified'])->name('admin.dashboard');
// Route::get('/payment/{id}', [PaymentController::class, 'showPaymentPage'])->name('payment.page');

require __DIR__ . '/frontend.php';
// web routes (Laravel Blade)
Route::get('/payment/{booking}', [PaymentController::class, 'showPaymentPage'])
    ->name('payment.page')
    ->middleware('signed'); // ensure secure signed URL

Route::post('/stripe/webhook', [PaymentController::class, 'handleStripeWebhook']);
Route::get('/payment/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
Route::get('/payment/cancel', [PaymentController::class, 'paymentCancel'])->name('payment.cancel');
Route::get('api/v1/payment/status', [PaymentController::class, 'paymentStatus']);

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/api.php';
