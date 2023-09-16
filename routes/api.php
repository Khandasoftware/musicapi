<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SongController;
use App\Http\Controllers\GenreController;

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
    // Authentication route (if needed)
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    // Songs and Genres resources
    Route::resource('songs', SongController::class);
    Route::resource('genres', GenreController::class);
    Route::get('/songs/by-genre/{genre}', 'App\Http\Controllers\SongController@getByGenre');
    Route::get('{any}', 'App\Http\Controllers\ApiController@index')->where('any', '.*');

});