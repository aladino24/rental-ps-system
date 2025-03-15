<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;


Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/', [BookingController::class, 'index'])->name('home');
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/booking/history', [BookingController::class, 'gethistory'])->name('booking.history');
    Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');
    Route::post('/booking/cancel/{id}', [BookingController::class, 'cancel'])->name('booking.cancel');
    Route::get('/generate-nota/{id}', [BookingController::class, 'generateNota'])->name('booking.nota');
    Route::get('/booking/{id}/checkout', [PaymentController::class, 'checkout'])->name('booking.checkout');
    Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
});
