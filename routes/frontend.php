<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;




Route::get('/', [HomeController::class, 'home'])->name('homepage');
Route::get('events', [HomeController::class, 'allEvents'])->name('all.events');
Route::get('event/{slug}', [HomeController::class, 'eventDetails'])->name('event.details');
Route::get('checkout', [HomeController::class, 'checkout'])->name('checkout');
Route::get('/fetch-events', [HomeController::class, 'fetchEvents'])->name('events.fetch');
