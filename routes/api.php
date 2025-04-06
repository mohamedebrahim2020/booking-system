<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::middleware(['auth:sanctum'])->group(function () {

	Route::post('/email/verification-notification', function (Request $request) {
		if ($request->user()->hasVerifiedEmail()) {
			return response()->json(['message' => 'Already verified']);
		}

		$request->user()->sendEmailVerificationNotification();

		return response()->json(['message' => 'Verification link sent', 'user' => $request->user()]);
	})->name('verification.send');

	Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
		$request->fulfill();

		return response()->json(['message' => 'Email successfully verified']);
	})->middleware(['signed'])->name('verification.verify');
});
Route::post('/register', AuthController::class . '@register')->name('register');
Route::post('/login', AuthController::class . '@login')->middleware('verified')->name('login');
Route::post('/logout', AuthController::class . '@logout')->middleware('auth:sanctum')->name('logout');

Route::get('/rooms', RoomController::class . '@index');

Route::apiResource('rooms', RoomController::class)->except(['index'])->middleware(['auth:sanctum', 'is_admin']);
Route::apiResource('/bookings', BookingController::class)->only(['store', 'show'])->middleware('auth:sanctum');


