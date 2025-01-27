<?php

use Illuminate\Support\Facades\Route;
use Modules\Favorite\App\Http\Controllers\FavoriteController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::middleware(['auth:sanctum','setLocale'])->group(function () {
    Route::apiResource('favorite', FavoriteController::class)->names('favorite');

Route::post('/favorites/toggle', [FavoriteController::class, 'toggleFavorite'])->name('favorites.toggle');

// Get all user's favorites
Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
});
