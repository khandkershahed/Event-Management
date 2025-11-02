<?php

use App\Http\Controllers\Client\ClientController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:web')->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
    // Events under dashboard
    Route::get('/my-events', [ClientController::class, 'myEvents'])->name('my.events');
    Route::get('/my-coupons', [ClientController::class, 'myCoupons'])->name('my.coupons');
    Route::get('/my-cards', [ClientController::class, 'myCards'])->name('my.cards');
    Route::get('/my-reports', [ClientController::class, 'myReports'])->name('my.reports');
    Route::get('/my-subscription', [ClientController::class, 'mySubscription'])->name('my.subscription');
    Route::get('/my-information', [ClientController::class, 'myInformation'])->name('my.information');
    Route::get('/my-team', [ClientController::class, 'myTeam'])->name('my.team');
    Route::get('/my-profile', [ClientController::class, 'myProfile'])->name('my.profile');
});
