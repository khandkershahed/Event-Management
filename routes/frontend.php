<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;




Route::get('/', [HomeController::class, 'home'])->name('homepage');
Route::get('/about', [HomeController::class, 'aboutUs'])->name('about');
Route::get('events', [HomeController::class, 'allEvents'])->name('all.events');
Route::get('event/{slug}', [HomeController::class, 'eventDetails'])->name('event.details');
Route::get('checkout', [HomeController::class, 'checkout'])->name('checkout');
Route::get('/fetch-events', [HomeController::class, 'fetchEvents'])->name('events.fetch');
// Route::get('/event/create', [HomeController::class, 'fetchEvents'])->name('events.fetch');


Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/contact-us', [HomeController::class, 'contactUs'])->name('contact.us');
Route::get('/help-center', [HomeController::class, 'helpCenter'])->name('help.center');
Route::get('/sell-ticket-online', [HomeController::class, 'sellTicketOnline'])->name('sell.ticket.online');
Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy.policy');
Route::get('/terms-conditions', [HomeController::class, 'termsConditions'])->name('terms.conditions');
Route::get('/blog', [HomeController::class, 'blog'])->name('blog');
Route::get('/refer-friend', [HomeController::class, 'referFriend'])->name('refer.friend');
Route::get('/event-create', [HomeController::class, 'eventCreate'])->name('event.create');
Route::get('/online-event-create', [HomeController::class, 'onlineEventCreate'])->name('online.event.create');
Route::get('/venue-event-create', [HomeController::class, 'venueEventCreate'])->name('venue.event.create');