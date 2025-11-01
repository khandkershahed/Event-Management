<?php

use App\Http\Controllers\Client\ClientController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:web')->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
});
