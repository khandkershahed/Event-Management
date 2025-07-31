<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ModeratorDashboardController;
use App\Http\Controllers\Frontend\UserDashboardController;
// use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'home'])->name('homepage');



// Route::group(['middleware' => ['auth:web', 'verified', 'check_role:user'], 'prefix' => 'user', 'as' => 'user.'], function () {
//     Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
// });

// Route::group(['middleware' => ['auth:web', 'verified', 'check_role:moderator'], 'prefix' => 'moderator', 'as' => 'moderator.'], function () {
//     Route::get('/dashboard', [ModeratorDashboardController::class, 'index'])->name('dashboard');
// });

// Route::get('/admin/dashboard', function () {
//     return view('admin.dashboard');
// })->middleware(['auth:admin', 'verified'])->name('admin.dashboard');

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
