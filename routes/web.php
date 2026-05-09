<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return redirect()->route('user.dashboard');
})->middleware('auth:web')->name('dashboard');

require __DIR__ . '/frontend.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/client.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/organizer.php';
require __DIR__ . '/api.php';
