<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\SeatMapController;
use App\Http\Controllers\Frontend\TicketOrderController;




Route::get('/', [HomeController::class, 'home'])->name('homepage');
Route::get('/about', [HomeController::class, 'aboutUs'])->name('about');
Route::get('events', [HomeController::class, 'allEvents'])->name('all.events');
// Route::get('event/{slug}', [HomeController::class, 'eventDetails'])->name('event.details');
// Event details + seat map
Route::get('/event/{slug}', [TicketOrderController::class, 'showEvent'])->name('event.details');
// AJAX seat availability
Route::get('/event/{event}/seat-availability', [TicketOrderController::class, 'fetchSeatAvailability'])->name('event.seats.availability');

// Seat locking
Route::post('/event/{event}/seat/lock', [TicketOrderController::class, 'lockSeat'])->name('event.seat.lock');
Route::post('/event/{event}/seat/unlock', [TicketOrderController::class, 'unlockSeat'])->name('event.seat.unlock');

// Cart
Route::post('/event/{event}/cart/add', [TicketOrderController::class, 'addToCart'])->name('frontend.cart.add');
Route::post('/event/{event}/cart/remove', [TicketOrderController::class, 'removeFromCart'])->name('event.cart.remove');
// Cart
Route::get('/cart', [TicketOrderController::class, 'showCart'])->name('frontend.cart');
// Checkout
Route::get('/event/{event}/checkout', [TicketOrderController::class, 'checkout'])->name('frontend.checkout');
// Process order (COD for now)
Route::post('/event/{event}/order/process', [TicketOrderController::class, 'processOrder'])->name('frontend.order.process');
// Order success
Route::get('/order/success/{order}', [TicketOrderController::class, 'orderSuccess'])->name('frontend.order.success');
// Download Ticket
Route::get('/ticket/{ticket}/download', [TicketOrderController::class, 'downloadTicket'])->name('frontend.ticket.download');
Route::get('/event/{event}/select-seats', [TicketOrderController::class, 'selectSeatsPage'])->name('frontend.seats.select');
// Get structured map
Route::get('/event/{event}/seat-map',[SeatMapController::class, 'getStructuredMap'])->name('frontend.seat.map');
Route::get('/event/{event}/seat-lock',[SeatMapController::class, 'seatLock'])->name('frontend.seat.lock');
Route::get('/event/{event}/seat-unlock',[SeatMapController::class, 'seatUnLock'])->name('frontend.seat.unlock');

// Get seats for a section only
Route::get('/event/{event}/section/{section}/seats',[SeatMapController::class, 'getSectionSeats'])->name('frontend.section.seats');





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
