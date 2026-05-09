<?php

use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\DiscoveryController;
use App\Http\Controllers\User\EventReviewController;
use App\Http\Controllers\User\FollowedOrganizerController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\RefundRequestController;
use App\Http\Controllers\User\SavedEventController;
use App\Http\Controllers\User\SupportTicketController;
use App\Http\Controllers\User\TicketController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:web')->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::get('/support-tickets', [SupportTicketController::class, 'index'])->name('support-tickets.index');
    Route::get('/support-tickets/create', [SupportTicketController::class, 'create'])->name('support-tickets.create');
    Route::post('/support-tickets', [SupportTicketController::class, 'store'])->name('support-tickets.store');
    Route::get('/support-tickets/{supportTicket}', [SupportTicketController::class, 'show'])->name('support-tickets.show');
    Route::post('/support-tickets/{supportTicket}/reply', [SupportTicketController::class, 'reply'])->name('support-tickets.reply');
    Route::get('/reviews', [EventReviewController::class, 'index'])->name('reviews.index');
    Route::get('/saved-events', [SavedEventController::class, 'index'])->name('saved-events.index');
    Route::get('/discover', [DiscoveryController::class, 'index'])->name('discovery.index');
    Route::get('/followed-organizers', [FollowedOrganizerController::class, 'index'])->name('followed-organizers.index');
    Route::get('/events/{event}/reviews/create', [EventReviewController::class, 'create'])->name('event-reviews.create');
    Route::post('/events/{event}/reviews', [EventReviewController::class, 'store'])->name('event-reviews.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/refund', [RefundRequestController::class, 'create'])->name('orders.refund.create');
    Route::post('/orders/{order}/refund', [RefundRequestController::class, 'store'])->name('orders.refund.store');
    Route::get('/refunds', [RefundRequestController::class, 'index'])->name('refunds.index');
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::get('/tickets/{ticket}/print', [TicketController::class, 'print'])->name('tickets.print');
    Route::get('/profile', [ClientController::class, 'myProfile'])->name('profile');

    Route::get('/my-events', [ClientController::class, 'myEvents'])->name('my.events');
    Route::get('/my-coupons', [ClientController::class, 'myCoupons'])->name('my.coupons');
    Route::get('/my-cards', [ClientController::class, 'myCards'])->name('my.cards');
    Route::get('/my-reports', [ClientController::class, 'myReports'])->name('my.reports');
    Route::get('/my-subscription', [ClientController::class, 'mySubscription'])->name('my.subscription');
    Route::get('/my-information', [ClientController::class, 'myInformation'])->name('my.information');
    Route::get('/my-team', [ClientController::class, 'myTeam'])->name('my.team');
    Route::get('/my-profile', [ClientController::class, 'myProfile'])->name('my.profile');
});
