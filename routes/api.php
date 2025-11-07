<?php

use App\Http\Controllers\Api\Auth\RegistrationController;
use App\Http\Controllers\Api\Auth\SessionController;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// authentication routes
Route::post('/register', [RegistrationController::class, 'register']);
Route::post('/login', [SessionController::class, 'login']);
Route::post('/logout', [SessionController::class, 'logout'])->middleware('auth:sanctum');

// movie routes
Route::get('/movies', [MovieController::class, 'index']);
Route::get('/movies/{movie}', [MovieController::class, 'show']);
Route::post('/movies', [MovieController::class, 'store'])->middleware('auth:sanctum');
Route::put('/movies/{movie}', [MovieController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/movies/{movie}', [MovieController::class, 'destroy'])->middleware('auth:sanctum');

// review routes scoped to movies
Route::post('/movies/{movie}/reviews', [ReviewController::class, 'store'])->middleware('auth:sanctum');
Route::get('/movies/{movie}/reviews/{review}', [ReviewController::class, 'show']);
Route::put('/movies/{movie}/reviews/{review}', [ReviewController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/movies/{movie}/reviews/{review}', [ReviewController::class, 'destroy'])->middleware('auth:sanctum');
