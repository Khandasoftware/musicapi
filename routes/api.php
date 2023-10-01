<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\StripeWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// API versioning prefix
Route::prefix('v1')->group(function () {
    // Authentication routes (Laravel Sanctum)
    Route::middleware('auth:sanctum')->group(function () {
        // Get authenticated user information
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::group([ 'prefix' =>'artist', 'as'=>'artist.'], function () { 
            Route::apiResource('songs', SongController::class);
            Route::apiResource('genres', GenreController::class);
            Route::get('orders', [OrderController::class,'index']);
            Route::get('orders/{order}', [OrderController::class,'show']);
            Route::get('/songs/by-genre/{genre}', [SongController::class, 'getByGenre']);
        });

        //Protected resource routes
        // Route::apiResource('songs', SongController::class);
        //
        Route::post('/purchase/{song}', [SongController::class,'purchase'] );
        Route::post('/confirm', [SongController::class,'confirmPayment'] );
    });

    Route::get('/songs', [SongController::class,'index'] );
    Route::get('/songs/{song}', [SongController::class,'index'] );
    Route::get('genres', [GenreController::class,'index'] );
    Route::get('genres/{genre}', [GenreController::class,'show'] );
    Route::get('/songs/by-genre/{genre}', [SongController::class, 'getByGenre']);
    // Public routes
    Route::post('/register', [RegisterController::class,'register'] ); // User registration
    Route::post('/login', [LoginController::class,'login'] ); // User login
    

    // Catch-all route for handling unspecified routes
   // Route::any('{any}', [ ApiController::class, 'index' ] )->where('any', '.*'); 
    Route::get('/logs', [LogController::class, 'show'] );


});

