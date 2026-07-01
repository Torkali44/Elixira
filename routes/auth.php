<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\ForgotPasswordOtpController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailOtpController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [ForgotPasswordOtpController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [ForgotPasswordOtpController::class, 'sendOtp'])
        ->middleware('throttle:6,1')
        ->name('password.email');

    Route::get('forgot-password/verify', [ForgotPasswordOtpController::class, 'showVerify'])
        ->name('password.verify');

    Route::post('forgot-password/verify', [ForgotPasswordOtpController::class, 'verifyOtp'])
        ->middleware('throttle:6,1')
        ->name('password.verify.submit');

    Route::get('reset-password', [ForgotPasswordOtpController::class, 'showResetForm'])
        ->name('password.reset.form');

    Route::post('reset-password', [ForgotPasswordOtpController::class, 'resetPassword'])
        ->name('password.reset.submit');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::post('verify-email', [VerifyEmailOtpController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
