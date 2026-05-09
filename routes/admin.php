<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\EventApprovalController;
use App\Http\Controllers\Admin\EventCancellationController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\EventTicketTypeController;
use App\Http\Controllers\Admin\EventTypeController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\OrganizerApprovalController;
use App\Http\Controllers\Admin\OrganizerTrustBadgeController;
use App\Http\Controllers\Admin\MarketplaceReportController;
use App\Http\Controllers\Admin\ModerationFlagController;
use App\Http\Controllers\Admin\PayoutController;
use App\Http\Controllers\Admin\PayoutMethodController;
use App\Http\Controllers\Admin\PlatformCommissionController;
use App\Http\Controllers\Admin\RefundController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SeatingPlanController;
use App\Http\Controllers\Admin\SeatingPlanDesignerController;
use App\Http\Controllers\Admin\SeatingSeatController;
use App\Http\Controllers\Admin\SupportTicketController;
use App\Http\Controllers\Admin\SeatingSectionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\VenueController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest:admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::group(['middleware' => 'auth:admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('support-tickets', [SupportTicketController::class, 'index'])->name('support-tickets.index');
    Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('organizer-trust-badges', [OrganizerTrustBadgeController::class, 'index'])->name('organizer-trust-badges.index');
    Route::post('organizer-trust-badges/{organizer}', [OrganizerTrustBadgeController::class, 'store'])->name('organizer-trust-badges.store');
    Route::delete('organizer-trust-badges/{badge}', [OrganizerTrustBadgeController::class, 'destroy'])->name('organizer-trust-badges.destroy');
    Route::post('reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
    Route::post('reviews/{review}/hide', [ReviewController::class, 'hide'])->name('reviews.hide');
    Route::get('support-tickets/{supportTicket}', [SupportTicketController::class, 'show'])->name('support-tickets.show');
    Route::post('support-tickets/{supportTicket}/reply', [SupportTicketController::class, 'reply'])->name('support-tickets.reply');
    Route::put('support-tickets/{supportTicket}/status', [SupportTicketController::class, 'updateStatus'])->name('support-tickets.status');
    Route::get('moderation-flags', [ModerationFlagController::class, 'index'])->name('moderation-flags.index');
    Route::post('moderation-flags/events/{event}/flag', [ModerationFlagController::class, 'flagEvent'])->name('moderation-flags.events.flag');
    Route::post('moderation-flags/organizers/{organizer}/flag', [ModerationFlagController::class, 'flagOrganizer'])->name('moderation-flags.organizers.flag');
    Route::post('moderation-flags/{flag}/resolve', [ModerationFlagController::class, 'resolve'])->name('moderation-flags.resolve');
    Route::post('moderation-flags/{flag}/unflag', [ModerationFlagController::class, 'unflag'])->name('moderation-flags.unflag');
    Route::get('notifications/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [AdminProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('categories', CategoryController::class);
    Route::resource('event-type', EventTypeController::class);
    Route::resource('event', EventController::class);

    Route::get('event-approvals', [EventApprovalController::class, 'index'])->name('event-approvals.index');
    Route::get('event-approvals/{event}', [EventApprovalController::class, 'show'])->name('event-approvals.show');
    Route::post('event-approvals/{event}/approve', [EventApprovalController::class, 'approve'])->name('event-approvals.approve');
    Route::post('event-approvals/{event}/reject', [EventApprovalController::class, 'reject'])->name('event-approvals.reject');

    Route::get('refunds', [RefundController::class, 'index'])->name('refunds.index');
    Route::get('refunds/{refund}', [RefundController::class, 'show'])->name('refunds.show');
    Route::post('refunds/{refund}/approve', [RefundController::class, 'approve'])->name('refunds.approve');
    Route::post('refunds/{refund}/reject', [RefundController::class, 'reject'])->name('refunds.reject');

    Route::get('event-cancellations', [EventCancellationController::class, 'index'])->name('event-cancellations.index');
    Route::get('event-cancellations/{cancellation}', [EventCancellationController::class, 'show'])->name('event-cancellations.show');
    Route::post('event-cancellations/{cancellation}/approve', [EventCancellationController::class, 'approve'])->name('event-cancellations.approve');
    Route::post('event-cancellations/{cancellation}/reject', [EventCancellationController::class, 'reject'])->name('event-cancellations.reject');

    Route::resource('venue', VenueController::class);
    Route::resource('user', UserManagementController::class);

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'updateOrcreateSetting'])->name('settings.updateOrCreate');

    Route::get('platform-commission', [PlatformCommissionController::class, 'edit'])->name('platform-commission.edit');
    Route::put('platform-commission', [PlatformCommissionController::class, 'update'])->name('platform-commission.update');
    Route::get('payouts', [PayoutController::class, 'index'])->name('payouts.index');
    Route::get('payouts/{payout}', [PayoutController::class, 'show'])->name('payouts.show');
    Route::post('payouts/{payout}/approve', [PayoutController::class, 'approve'])->name('payouts.approve');
    Route::post('payouts/{payout}/reject', [PayoutController::class, 'reject'])->name('payouts.reject');
    Route::post('payouts/{payout}/mark-paid', [PayoutController::class, 'markPaid'])->name('payouts.mark-paid');

    Route::get('payout-methods', [PayoutMethodController::class, 'index'])->name('payout-methods.index');
    Route::get('payout-methods/{payoutMethod}', [PayoutMethodController::class, 'show'])->name('payout-methods.show');
    Route::post('payout-methods/{payoutMethod}/verify', [PayoutMethodController::class, 'verify'])->name('payout-methods.verify');
    Route::post('payout-methods/{payoutMethod}/reject', [PayoutMethodController::class, 'reject'])->name('payout-methods.reject');


    Route::get('marketplace-reports', [MarketplaceReportController::class, 'dashboard'])->name('marketplace-reports.dashboard');
    Route::get('marketplace-reports/sales-by-date', [MarketplaceReportController::class, 'salesByDate'])->name('marketplace-reports.sales-by-date');
    Route::get('marketplace-reports/sales-by-organizer', [MarketplaceReportController::class, 'salesByOrganizer'])->name('marketplace-reports.sales-by-organizer');
    Route::get('marketplace-reports/sales-by-event', [MarketplaceReportController::class, 'salesByEvent'])->name('marketplace-reports.sales-by-event');
    Route::get('marketplace-reports/commissions', [MarketplaceReportController::class, 'commissions'])->name('marketplace-reports.commissions');
    Route::get('marketplace-reports/payouts', [MarketplaceReportController::class, 'payouts'])->name('marketplace-reports.payouts');
    Route::get('marketplace-reports/refunds', [MarketplaceReportController::class, 'refunds'])->name('marketplace-reports.refunds');

    Route::get('organizers', [OrganizerApprovalController::class, 'index'])->name('organizers.index');
    Route::get('organizers/pending', [OrganizerApprovalController::class, 'pending'])->name('organizers.pending');
    Route::get('organizers/{organizer}', [OrganizerApprovalController::class, 'show'])->name('organizers.show');
    Route::post('organizers/{organizer}/approve', [OrganizerApprovalController::class, 'approve'])->name('organizers.approve');
    Route::post('organizers/{organizer}/reject', [OrganizerApprovalController::class, 'reject'])->name('organizers.reject');
    Route::post('organizers/{organizer}/suspend', [OrganizerApprovalController::class, 'suspend'])->name('organizers.suspend');

    Route::resource('seating-plans', SeatingPlanController::class);
    Route::get('seating-plans/{plan}/designer', [SeatingPlanDesignerController::class, 'show'])->name('seating-plans.designer');
    Route::post('seating-plans/{plan}/designer/save', [SeatingPlanDesignerController::class, 'save'])->name('seating-plans.designer.save');
    Route::get('venue/{venue}/seating-plans', function (\App\Models\Venue $venue) {
        return response()->json($venue->seatingPlans()->select('id', 'name')->get());
    })->name('venue.seating-plans');

    Route::get('seating-sections/{plan}', [SeatingSectionController::class, 'index'])->name('seating-sections.index');
    Route::post('seating-sections', [SeatingSectionController::class, 'store'])->name('seating-sections.store');
    Route::put('seating-sections/{section}', [SeatingSectionController::class, 'update'])->name('seating-sections.update');
    Route::delete('seating-sections/{section}', [SeatingSectionController::class, 'destroy'])->name('seating-sections.destroy');
    Route::get('seating-sections/{section}/seats', [SeatingSeatController::class, 'index'])->name('seating-seats.index');
    Route::post('seating-seats', [SeatingSeatController::class, 'store'])->name('seating-seats.store');
    Route::get('seating-seats/{seat}', [SeatingSeatController::class, 'show'])->name('seating-seats.show');
    Route::put('seating-seats/{seat}', [SeatingSeatController::class, 'update'])->name('seating-seats.update');
    Route::delete('seating-seats/{seat}', [SeatingSeatController::class, 'destroy'])->name('seating-seats.destroy');

    Route::get('events/{event}/ticket-types', [EventTicketTypeController::class, 'index'])->name('events.ticket-types.index');
    Route::get('events/{event}/ticket-types/manage', [EventTicketTypeController::class, 'manage'])->name('events.ticket-types.manage');
    Route::post('events/{event}/ticket-types', [EventTicketTypeController::class, 'store'])->name('events.ticket-types.store');
    Route::get('events/{event}/ticket-types/sections', [EventTicketTypeController::class, 'sections'])->name('events.ticket-types.sections');
    Route::get('events/{event}/ticket-types/{ticket}', [EventTicketTypeController::class, 'show'])->name('events.ticket-types.show');
    Route::put('events/{event}/ticket-types/{ticket}', [EventTicketTypeController::class, 'update'])->name('events.ticket-types.update');
    Route::delete('events/{event}/ticket-types/{ticket}', [EventTicketTypeController::class, 'destroy'])->name('events.ticket-types.destroy');
});
