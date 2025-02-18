<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Auth\App\Http\Controllers\AuthController;
use Modules\Auth\app\Http\Controllers\SocialAuthController;

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

Route::middleware(['setLocale'])->group(function () {


// Registration Route
Route::post('register', [AuthController::class, 'register']);

// Login Route
Route::post('login', [AuthController::class, 'login']);

// Send password reset link
Route::post('password/email', [AuthController::class, 'sendPasswordResetLink']);

// Reset password
Route::post('password/reset', [AuthController::class, 'resetPassword']);



});
Route::get('login/{provider}', [SocialAuthController::class, 'redirectToProvider']);
Route::get('auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
