<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\VenueController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/otp/send', [AuthController::class, 'sendOtp']);
Route::post('/otp/verify', [AuthController::class, 'verifyOtp']);

Route::get('/venues', [VenueController::class, 'index']);
Route::get('/venues/{venue:slug}', [VenueController::class, 'show']);

Route::middleware('api.token')->group(function () {
    Route::get('/dashboard', DashboardController::class);
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel']);
    Route::post('/bookings/{booking}/payment-order', [PaymentController::class, 'order']);
    Route::post('/bookings/{booking}/payment-verify', [PaymentController::class, 'verify']);
    Route::post('/venues/{venue:slug}/reviews', [ReviewController::class, 'store']);
});
