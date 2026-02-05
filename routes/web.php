<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GroundController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\OwnerRequestController;
use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\Owner\GroundManagementController;
use App\Http\Controllers\Owner\OwnerBookingController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\OwnerRequestManagementController;
use App\Http\Controllers\Admin\SportsTypeController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\GroundManagementController as AdminGroundManagementController;
use App\Http\Controllers\Admin\BookingManagementController as AdminBookingManagementController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\SystemRatingController;
use Illuminate\Support\Facades\Route;

// Google OAuth routes
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');

// Welcome page (home for all users - guests and logged in)
Route::get('/', [HomeController::class, 'welcome'])->name('welcome');

// Browse grounds page (public - no auth required for browsing and filtering)
Route::get('/browse', [GroundController::class, 'browse'])->name('grounds.browse');
Route::get('/grounds', [GroundController::class, 'index'])->name('grounds.index');
Route::get('/grounds/{ground}', [GroundController::class, 'show'])->name('grounds.show');

// Redirect old home route to welcome for backward compatibility
Route::get('/home', function () {
    return redirect()->route('welcome');
})->name('home');

// Redirect dashboard to welcome for backward compatibility
Route::get('/dashboard', function () {
    return redirect()->route('welcome');
})->name('dashboard');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Profile routes (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Bookings
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create/{ground}', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings/{ground}', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'destroy'])->name('bookings.destroy');
    Route::get('/bookings/{ground}/check-availability', [BookingController::class, 'checkAvailability'])->name('bookings.check-availability');
    
    // Wallet
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/add', [WalletController::class, 'addCoins'])->name('wallet.add');
    
    // Reviews - Available to all authenticated users
    Route::post('/reviews/{ground}', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // System Ratings - Available to all authenticated users with verified email
    Route::get('/system-ratings', [SystemRatingController::class, 'index'])->name('system-ratings.index');
    Route::post('/system-ratings', [SystemRatingController::class, 'store'])->name('system-ratings.store');
    Route::delete('/system-ratings/{systemRating}', [SystemRatingController::class, 'destroy'])->name('system-ratings.destroy');
    
    // Owner Request
    Route::get('/owner-request/create', [OwnerRequestController::class, 'create'])->name('owner-request.create');
    Route::post('/owner-request', [OwnerRequestController::class, 'store'])->name('owner-request.store');
});

// Owner routes
Route::middleware(['auth', 'owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
    
    // Ground Management
    Route::get('/grounds', [GroundManagementController::class, 'index'])->name('grounds.index');
    Route::get('/grounds/create', [GroundManagementController::class, 'create'])->name('grounds.create');
    Route::post('/grounds', [GroundManagementController::class, 'store'])->name('grounds.store');
    Route::get('/grounds/{ground}', [GroundManagementController::class, 'show'])->name('grounds.show');
    Route::get('/grounds/{ground}/edit', [GroundManagementController::class, 'edit'])->name('grounds.edit');
    Route::put('/grounds/{ground}', [GroundManagementController::class, 'update'])->name('grounds.update');
    Route::delete('/grounds/{ground}', [GroundManagementController::class, 'destroy'])->name('grounds.destroy');
    
    // Availability Management
    Route::get('/grounds/{ground}/availability', [GroundManagementController::class, 'editAvailability'])->name('grounds.availability');
    Route::put('/grounds/{ground}/availability', [GroundManagementController::class, 'updateAvailability'])->name('grounds.availability.update');
    
    // Bookings
    Route::get('/bookings', [OwnerBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [OwnerBookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [OwnerBookingController::class, 'store'])->name('bookings.store');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Owner Request Management
    Route::get('/owner-requests', [OwnerRequestManagementController::class, 'index'])->name('owner-requests.index');
    Route::get('/owner-requests/{ownerRequest}', [OwnerRequestManagementController::class, 'show'])->name('owner-requests.show');
    Route::post('/owner-requests/{ownerRequest}/approve', [OwnerRequestManagementController::class, 'approve'])->name('owner-requests.approve');
    Route::post('/owner-requests/{ownerRequest}/reject', [OwnerRequestManagementController::class, 'reject'])->name('owner-requests.reject');
    
    // Sports Type Management
    Route::get('/sports-types', [SportsTypeController::class, 'index'])->name('sports-types.index');
    Route::get('/sports-types/create', [SportsTypeController::class, 'create'])->name('sports-types.create');
    Route::post('/sports-types', [SportsTypeController::class, 'store'])->name('sports-types.store');
    Route::get('/sports-types/{sportsType}/edit', [SportsTypeController::class, 'edit'])->name('sports-types.edit');
    Route::put('/sports-types/{sportsType}', [SportsTypeController::class, 'update'])->name('sports-types.update');
    Route::delete('/sports-types/{sportsType}', [SportsTypeController::class, 'destroy'])->name('sports-types.destroy');
    
    // User Management
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/suspend', [UserManagementController::class, 'suspend'])->name('users.suspend');
    Route::post('/users/{user}/unsuspend', [UserManagementController::class, 'unsuspend'])->name('users.unsuspend');
    
    // Ground Management
    Route::get('/grounds', [AdminGroundManagementController::class, 'index'])->name('grounds.index');
    Route::get('/grounds/create', [AdminGroundManagementController::class, 'create'])->name('grounds.create');
    Route::post('/grounds', [AdminGroundManagementController::class, 'store'])->name('grounds.store');
    Route::get('/grounds/{ground}', [AdminGroundManagementController::class, 'show'])->name('grounds.show');
    Route::post('/grounds/{ground}/toggle', [AdminGroundManagementController::class, 'toggleStatus'])->name('grounds.toggle');
    
    // Booking Management
    Route::get('/bookings', [AdminBookingManagementController::class, 'index'])->name('bookings.index');
});

require __DIR__.'/auth.php';
