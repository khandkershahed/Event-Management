<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\TermsController;
use App\Http\Controllers\Admin\VenueController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\PrivacyController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\EventSeatController;
use App\Http\Controllers\Admin\EventTypeController;
use App\Http\Controllers\Admin\PageBannerController;
use App\Http\Controllers\Admin\SeatingPlanController;
use App\Http\Controllers\Admin\SeatingSeatController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\Auth\PasswordController;
use App\Http\Controllers\Admin\EventSeatTypeController;
use App\Http\Controllers\Admin\SeatingSectionController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\EventTicketTypeController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\SeatingPlanDesignerController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;

Route::group(['middleware' => 'guest:admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::group(['middleware' => 'auth:admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

// All Controller
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {

    Route::get('/ticket/scan/{id}', [AdminController::class, 'ticketURL'])->name('ticket.scan');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [AdminProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/seatmap/save', [EventSeatController::class, 'fetchSeats'])->name('seatmap.save');
    Route::post('/event-seats/fetch', [EventSeatController::class, 'fetchSeats'])->name('event-seat.fetch');
    Route::post('/event-seat/fetch-seat-types', [EventSeatController::class, 'fetchSeatTypes'])->name('event-seat.fetch-seat-types');

    //Resource Controller
    Route::resources(
        [
            'banner'         => PageBannerController::class,
            'event-type'     => EventTypeController::class,
            'event'          => EventController::class,
            'event-seat-type' => EventSeatTypeController::class,
            'event-seat'     => EventSeatController::class,

            'blog-category'  => BlogCategoryController::class,
            'blog-post'      => BlogPostController::class,
            'venue'          => VenueController::class,
            'seating-plan'   => SeatingPlanController::class,

            'contact'        => ContactController::class,
            'subscription'   => SubscriptionController::class,
            'service'        => ServiceController::class,

            'faq'            => FaqController::class,
            'terms'          => TermsController::class,
            'privacy'        => PrivacyController::class,

            'staff'          => StaffController::class,
            'user'           => UserManagementController::class,

        ],
    );



    Route::get('/venue/{venue}/seating-plans', function ($venueId) {
        return \App\Models\SeatingPlan::where('venue_id', $venueId)->get();
    });



        /*
        |--------------------------------------------------------------------------
        | EVENT TICKET TYPES (Admin)
        |--------------------------------------------------------------------------
        */
    Route::prefix('events/{event}')->name('events.')->group(function () {

        // Manage page (Blade)
        Route::get(
            'ticket-types/manage',
            [EventTicketTypeController::class, 'manage']
        )->name('ticket-types.manage');

        // AJAX: get ticket types list
        Route::get(
            'ticket-types',
            [EventTicketTypeController::class, 'index']
        )->name('ticket-types.index');

        // AJAX: get seating sections
        Route::get(
            'ticket-types/sections',
            [EventTicketTypeController::class, 'sections']
        )->name('ticket-types.sections');

        // CREATE
        Route::post(
            'ticket-types',
            [EventTicketTypeController::class, 'store']
        )->name('ticket-types.store');

        // SHOW (for edit modal)
        Route::get(
            'ticket-types/{ticket}',
            [EventTicketTypeController::class, 'show']
        )->name('ticket-types.show');

        // UPDATE
        Route::put(
            'ticket-types/{ticket}',
            [EventTicketTypeController::class, 'update']
        )->name('ticket-types.update');

        // DELETE
        Route::delete(
            'ticket-types/{ticket}',
            [EventTicketTypeController::class, 'destroy']
        )->name('ticket-types.destroy');
    });


    Route::resource('venues', VenueController::class)->names('venue');

        /*
        |--------------------------------------------------------------------------
        | SEATING PLANS (CRUD)
        |--------------------------------------------------------------------------
        */
    Route::resource('seating-plans', SeatingPlanController::class)->names('seating-plans');

    /*
        |--------------------------------------------------------------------------
        | SEATING PLAN DESIGNER
        |--------------------------------------------------------------------------
        */
    Route::get('seating-plans/{plan}/designer', [SeatingPlanDesignerController::class, 'designer'])
        ->name('seating-plans.designer');

    Route::post('seating-plans/{plan}/designer/save', [SeatingPlanDesignerController::class, 'save'])
        ->name('seating-plans.designer.save');

    /*
        |--------------------------------------------------------------------------
        | SEATING SECTIONS (AJAX CRUD)
        |--------------------------------------------------------------------------
        */
    Route::get('seating-sections/{plan}', [SeatingSectionController::class, 'index'])
        ->name('seating-sections.index');

    Route::post('seating-sections', [SeatingSectionController::class, 'store'])
        ->name('seating-sections.store');

    Route::put('seating-sections/{section}', [SeatingSectionController::class, 'update'])
        ->name('seating-sections.update');

    Route::delete('seating-sections/{section}', [SeatingSectionController::class, 'destroy'])
        ->name('seating-sections.destroy');

    /*
        |--------------------------------------------------------------------------
        | SEATING SEATS (AJAX + PAGES)
        |--------------------------------------------------------------------------
        */
    // Seat list for a section
    Route::get('seating-sections/{section}/seats', [SeatingSeatController::class, 'index'])
        ->name('seating-seats.index');

    // Seat details page (optional)
    Route::get('seating-seats/{seat}', [SeatingSeatController::class, 'show'])
        ->name('seating-seats.show');

    // Seat CRUD (AJAX)
    Route::post('seating-seats', [SeatingSeatController::class, 'store'])
        ->name('seating-seats.store');

    Route::put('seating-seats/{seat}', [SeatingSeatController::class, 'update'])
        ->name('seating-seats.update');

    Route::delete('seating-seats/{seat}', [SeatingSeatController::class, 'destroy'])
        ->name('seating-seats.destroy');



    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'updateOrcreateSetting'])->name('settings.updateOrCreate');

    Route::post('banner/toggle-status/{id}', [PageBannerController::class, 'toggleStatus'])->name('banner.toggle-status');
    Route::post('service/toggle-status/{id}', [ServiceController::class, 'toggleStatus'])->name('service.toggle-status');


    Route::get('/notifications/read/{id}', [AdminController::class, 'markAsRead'])->name('notifications.read');
});
