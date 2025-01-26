<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Auth\App\Http\Controllers\AuthController;

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

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('auth', AuthController::class)->names('auth');
});


// Registration Route
Route::post('register', [AuthController::class, 'register']);

// Login Route
Route::post('login', [AuthController::class, 'login']);

// Send password reset link
Route::post('password/email', [AuthController::class, 'sendPasswordResetLink']);

// Reset password
Route::post('password/reset', [AuthController::class, 'resetPassword']);


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
