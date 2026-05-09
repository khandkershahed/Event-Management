<?php

use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\EventBrowseController;
use App\Http\Controllers\Frontend\EventDetailController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\OrganizerOnboardingController;
use App\Http\Controllers\Frontend\OrganizerProfileController;
use App\Http\Controllers\Frontend\SavedEventController;
use App\Http\Controllers\Frontend\SeoController;
use App\Http\Controllers\Frontend\StripeOrderController;
use App\Http\Controllers\Webhook\StripeWebhookController;
use App\Http\Controllers\Frontend\SeatMapController;
use App\Http\Controllers\Frontend\SeatSelectionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'home'])->name('homepage');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('seo.sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('seo.robots');
Route::get('/about', [HomeController::class, 'aboutUs'])->name('about');
Route::get('/events', [EventBrowseController::class, 'index'])->name('all.events');
Route::get('/organizers/{slug}', [OrganizerProfileController::class, 'show'])->name('public.organizers.show');
Route::get('/fetch-events', [EventBrowseController::class, 'fetch'])->name('events.fetch');
Route::get('/event/{slug}', [EventDetailController::class, 'show'])->name('event.details');

Route::get('/become-organizer', [OrganizerOnboardingController::class, 'become'])->name('organizer.become');

Route::middleware('auth:web')->group(function () {
    Route::get('/organizer/profile', [OrganizerOnboardingController::class, 'profile'])->name('organizer.profile');
    Route::post('/organizer/profile', [OrganizerOnboardingController::class, 'store'])->name('organizer.profile.store');
    Route::match(['put', 'patch'], '/organizer/profile', [OrganizerOnboardingController::class, 'update'])->name('organizer.profile.update');
    Route::post('/organizer/profile/submit', [OrganizerOnboardingController::class, 'submit'])->name('organizer.profile.submit');
    Route::get('/organizer/status', [OrganizerOnboardingController::class, 'status'])->name('organizer.status');
    Route::post('/organizers/{organizer}/follow', [OrganizerProfileController::class, 'follow'])->name('public.organizers.follow');
    Route::delete('/organizers/{organizer}/unfollow', [OrganizerProfileController::class, 'unfollow'])->name('public.organizers.unfollow');
    Route::post('/events/{event}/save', [SavedEventController::class, 'store'])->name('public.events.save');
    Route::delete('/events/{event}/unsave', [SavedEventController::class, 'destroy'])->name('public.events.unsave');
});

Route::get('/cart', [CartController::class, 'index'])->name('frontend.cart');
Route::get('/tickets/cart', [CartController::class, 'index'])->name('tickets.cart');
Route::post('/cart/add', [CartController::class, 'add'])->middleware('throttle:marketplace-cart')->name('frontend.cart.add');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('frontend.cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('frontend.cart.clear');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('frontend.checkout');
Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->middleware('throttle:marketplace-checkout')->name('frontend.order.process');
Route::get('/order/success/{order}', [CheckoutController::class, 'success'])->name('frontend.order.success');
Route::get('/event/{event:slug}/checkout', [CheckoutController::class, 'index'])->name('frontend.event.checkout');

Route::get('/payment/stripe/{order}', [StripeOrderController::class, 'stripe'])->name('frontend.payment.stripe');
Route::get('/payment/success/{order}', [StripeOrderController::class, 'success'])->name('frontend.payment.success');
Route::get('/payment/cancel/{order}', [StripeOrderController::class, 'cancel'])->name('frontend.payment.cancel');
Route::post('/stripe/webhook', StripeWebhookController::class)->middleware('throttle:marketplace-webhook')->name('stripe.webhook');

Route::get('/event/{event:slug}/select-seats', [SeatSelectionController::class, 'index'])->name('frontend.seats.select');
Route::post('/event/{event:slug}/seats/lock', [SeatSelectionController::class, 'lock'])->middleware('throttle:marketplace-seat-lock')->name('frontend.seats.lock');
Route::post('/event/{event:slug}/seats/unlock', [SeatSelectionController::class, 'unlock'])->name('frontend.seats.unlock');
Route::get('/event/{event}/seat-map', [SeatMapController::class, 'getStructuredMap'])->name('frontend.seat.map');
Route::get('/event/{event}/section/{section}/seats', [SeatMapController::class, 'getSectionSeats'])->name('frontend.section.seats');

Route::view('/blog', 'frontend.pages.static-placeholder', ['title' => 'Blog'])->name('blog');
Route::view('/contact-us', 'frontend.pages.static-placeholder', ['title' => 'Contact Us'])->name('contact.us');
Route::view('/faq', 'frontend.pages.static-placeholder', ['title' => 'FAQ'])->name('faq');
Route::view('/help-center', 'frontend.pages.static-placeholder', ['title' => 'Help Center'])->name('help.center');
Route::view('/privacy-policy', 'frontend.pages.static-placeholder', ['title' => 'Privacy Policy'])->name('privacy.policy');
Route::view('/terms-conditions', 'frontend.pages.static-placeholder', ['title' => 'Terms & Conditions'])->name('terms.conditions');
Route::view('/event-create', 'frontend.pages.static-placeholder', ['title' => 'Create Event'])->name('event.create');
Route::view('/online-event-create', 'frontend.pages.static-placeholder', ['title' => 'Online Event'])->name('online.event.create');
Route::view('/venue-event-create', 'frontend.pages.static-placeholder', ['title' => 'Venue Event'])->name('venue.event.create');
Route::view('/sell-ticket-online', 'frontend.pages.static-placeholder', ['title' => 'Sell Ticket Online'])->name('sell.ticket.online');
Route::view('/refer-friend', 'frontend.pages.static-placeholder', ['title' => 'Refer Friend'])->name('refer.friend');
