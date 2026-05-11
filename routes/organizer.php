<?php

use App\Http\Controllers\Organizer\AttendeeController;
use App\Http\Controllers\Organizer\CheckInController;
use App\Http\Controllers\Organizer\DashboardController;
use App\Http\Controllers\Organizer\EventCancellationController;
use App\Http\Controllers\Organizer\EventController;
use App\Http\Controllers\Organizer\EventControlPanelController;
use App\Http\Controllers\Organizer\Auth\OrganizerAuthenticatedSessionController;
use App\Http\Controllers\Organizer\Auth\OrganizerRegisteredUserController;
use App\Http\Controllers\Organizer\EventTicketController;
use App\Http\Controllers\Organizer\FinanceProfileController;
use App\Http\Controllers\Organizer\NotificationController;
use App\Http\Controllers\Organizer\PayoutController;
use App\Http\Controllers\Organizer\ReviewController;
use App\Http\Controllers\Organizer\ProfileController;
use App\Http\Controllers\Organizer\SalesReportController;
use App\Http\Controllers\Organizer\SeatingPlanController;
use App\Http\Controllers\Organizer\SeatingPlanDesignerController;
use App\Http\Controllers\Organizer\SupportTicketController;
use App\Http\Controllers\Organizer\TeamMemberController;
use App\Http\Controllers\Organizer\VenueController;
use Illuminate\Support\Facades\Route;


Route::middleware('guest:web')
    ->prefix('organizer')
    ->as('organizer.')
    ->group(function () {
        Route::get('/login', [OrganizerAuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('/login', [OrganizerAuthenticatedSessionController::class, 'store'])->name('login.store');
        Route::get('/register', [OrganizerRegisteredUserController::class, 'create'])->name('register');
        Route::post('/register', [OrganizerRegisteredUserController::class, 'store'])->name('register.store');
    });

Route::middleware(['auth:web', 'organizer.approved'])
    ->prefix('organizer')
    ->as('organizer.')
    ->group(function () {
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');
        Route::get('/support-tickets', [SupportTicketController::class, 'index'])->name('support-tickets.index');
        Route::get('/support-tickets/create', [SupportTicketController::class, 'create'])->name('support-tickets.create');
        Route::post('/support-tickets', [SupportTicketController::class, 'store'])->name('support-tickets.store');
        Route::get('/support-tickets/{supportTicket}', [SupportTicketController::class, 'show'])->name('support-tickets.show');
        Route::post('/support-tickets/{supportTicket}/reply', [SupportTicketController::class, 'reply'])->name('support-tickets.reply');

        Route::middleware('organizer.staff:operations.manage')->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
            Route::get('/approved-profile', [ProfileController::class, 'show'])->name('approved-profile');
            Route::get('/orders', [DashboardController::class, 'orders'])->name('orders.index');

            Route::get('events/{event}/control', [EventControlPanelController::class, 'show'])->name('events.control');
            Route::get('events/{event}/attendees', [AttendeeController::class, 'index'])->name('events.attendees.index');
            Route::get('events/{event}/attendees/export', [AttendeeController::class, 'export'])->name('events.attendees.export');
            Route::get('events/{event}/cancellation', [EventCancellationController::class, 'create'])->name('events.cancellation.create');
            Route::post('events/{event}/cancellation', [EventCancellationController::class, 'store'])->name('events.cancellation.store');

            Route::resource('events', EventController::class);
            Route::resource('events.ticket-types', EventTicketController::class)->parameters([
                'events' => 'event',
                'ticket-types' => 'ticket',
            ]);
            Route::post('events/{event}/submit', [EventController::class, 'submit'])->name('events.submit');
            Route::post('events/{event}/publish', [EventController::class, 'publish'])->name('events.publish');

            Route::resource('venues', VenueController::class);
            Route::get('seating-plans/{seating_plan}/designer', [SeatingPlanDesignerController::class, 'show'])->name('seating-plans.designer');
            Route::post('seating-plans/{seating_plan}/designer/save', [SeatingPlanDesignerController::class, 'save'])->name('seating-plans.designer.save');
            Route::post('seating-plans/{seating_plan}/duplicate', [SeatingPlanController::class, 'duplicate'])->name('seating-plans.duplicate');
            Route::resource('seating-plans', SeatingPlanController::class);
        });

        Route::middleware('organizer.staff:team.manage')->group(function () {
            Route::get('/team-members', [TeamMemberController::class, 'index'])->name('team-members.index');
            Route::post('/team-members', [TeamMemberController::class, 'store'])->name('team-members.store');
            Route::put('/team-members/{teamMember}', [TeamMemberController::class, 'update'])->name('team-members.update');
            Route::post('/team-members/{teamMember}/activate', [TeamMemberController::class, 'activate'])->name('team-members.activate');
            Route::post('/team-members/{teamMember}/deactivate', [TeamMemberController::class, 'deactivate'])->name('team-members.deactivate');
            Route::delete('/team-members/{teamMember}', [TeamMemberController::class, 'destroy'])->name('team-members.destroy');
        });

        Route::middleware('organizer.staff:checkin.manage')->group(function () {
            Route::get('/check-in', [CheckInController::class, 'index'])->name('check-in.index');
            Route::post('/check-in/validate', [CheckInController::class, 'validateTicket'])->middleware('throttle:marketplace-check-in')->name('check-in.validate');
        });

        Route::middleware('organizer.staff:finance.view')->group(function () {
            Route::get('/reports', [DashboardController::class, 'reports'])->name('reports.index');
            Route::get('/reports/sales', [SalesReportController::class, 'index'])->name('reports.sales');
            Route::get('/reports/events/{event}', [SalesReportController::class, 'event'])->name('reports.events.show');
            Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
            Route::get('/payouts', [PayoutController::class, 'index'])->name('payouts.index');
            Route::get('/finance-profile', [FinanceProfileController::class, 'show'])->name('finance-profile.show');
        });

        Route::middleware('organizer.staff:finance.manage')->group(function () {
            Route::put('/finance-profile', [FinanceProfileController::class, 'update'])->name('finance-profile.update');
            Route::post('/payouts', [PayoutController::class, 'store'])->name('payouts.store');
        });
    });
