<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\Auth\GoogleSocialiteController;
use App\Mail\sendEmail;
use App\Http\Controllers\SmsTwilioController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('auth', [GoogleSocialiteController::class, 'redirectToAuth']);
Route::get('auth/callback', [GoogleSocialiteController::class, 'handleAuthCallback']);

Route::get('sms/send', [SmsTwilioController::class, 'sendSms']);