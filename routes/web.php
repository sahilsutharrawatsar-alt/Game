<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\VendorAuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Vendor;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\VenueController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/venues', [VenueController::class, 'index'])->name('venues.index');
Route::get('/venues/{venue:slug}', [VenueController::class, 'show'])->name('venues.show');
Route::get('/live/home', [HomeController::class, 'live'])->name('live.home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/login/otp', [AuthController::class, 'sendOtp'])->name('login.otp.send');
    Route::post('/login/otp/verify', [AuthController::class, 'verifyOtp'])->name('login.otp.verify');
    Route::get('/auth/google', [AuthController::class, 'redirectGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'callbackGoogle'])->name('auth.google.callback');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

    Route::get('/vendor/login', [VendorAuthController::class, 'showLogin'])->name('vendor.login');
    Route::post('/vendor/login', [VendorAuthController::class, 'login'])->name('vendor.login.submit');
    Route::get('/vendor/register', [VendorAuthController::class, 'showRegister'])->name('vendor.register');
    Route::post('/vendor/register', [VendorAuthController::class, 'register'])->name('vendor.register.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::post('/venues/{venue:slug}/favorite', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::post('/venues/{venue:slug}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::post('/bookings/{booking}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/bookings/{booking}/invoice', [BookingController::class, 'invoice'])->name('bookings.invoice');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', Admin\DashboardController::class)->name('dashboard');
    Route::resource('venues', Admin\VenueController::class)->except(['show']);
    Route::resource('bookings', Admin\BookingController::class)->only(['index', 'update']);
    Route::resource('users', Admin\UserController::class)->only(['index', 'update']);
    Route::resource('reviews', Admin\ReviewController::class)->only(['index', 'update']);
    Route::resource('sports', Admin\SportController::class)->only(['index', 'store', 'update']);
    Route::resource('coupons', Admin\CouponController::class)->only(['index', 'store']);
    Route::resource('slots', Admin\SlotController::class)->only(['index', 'store']);
    Route::resource('offers', Admin\OfferController::class)->only(['index', 'store']);
    Route::resource('notifications', Admin\NotificationController::class)->only(['index', 'store']);
    Route::resource('payments', Admin\PaymentController::class)->only(['index']);
    Route::resource('vendors', Admin\VendorController::class)->only(['index', 'update']);
    Route::resource('banners', Admin\BannerController::class)->only(['index', 'store', 'update']);
});

Route::prefix('vendor')->name('vendor.')->middleware(['auth', 'vendor'])->group(function () {
    Route::get('/', Vendor\DashboardController::class)->name('dashboard');
    Route::resource('venues', Vendor\VenueController::class)->except(['show']);
    Route::resource('slots', Vendor\SlotController::class)->only(['index', 'store', 'update']);
    Route::resource('bookings', Vendor\BookingController::class)->only(['index', 'update']);
    Route::resource('offers', Vendor\OfferController::class)->only(['index', 'store']);
    Route::resource('coupons', Vendor\CouponController::class)->only(['index', 'store']);
    Route::resource('reviews', Vendor\ReviewController::class)->only(['index']);
    Route::resource('earnings', Vendor\EarningController::class)->only(['index']);
});
