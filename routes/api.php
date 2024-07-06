<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\Auth\GoogleSocialiteController;
use App\Mail\sendEmail;
use App\Http\Controllers\SmsTwilioController;
use App\Http\Controllers\Api\AuthController;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

Route::get('auth', [GoogleSocialiteController::class, 'redirectToAuth']);
Route::get('auth/callback', [GoogleSocialiteController::class, 'handleAuthCallback']);

Route::get('sms/send', [SmsTwilioController::class, 'sendSms']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/send-email-verification', [DocumentController::class, 'sendEmailVerification']);
Route::post('/verify-email', [DocumentController::class, 'verifyEmail']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::controller(DocumentController::class)->group(function(){
        Route::get('/documents/get-by-user', 'index');
        Route::get('/documents/get-document-details/{id}', 'show');
        Route::post('/documents', 'store');
        Route::post('/documents/check-password', 'checkPassword');
        Route::post('/send-email-otp', 'sendOtp');
        Route::post('/verify-otp', 'verifyOtp');
        Route::post('/reset-password', 'resetPassword');
        Route::patch('/documents/{document_id}', 'update');
    });
});