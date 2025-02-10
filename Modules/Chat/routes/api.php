<?php

use Illuminate\Support\Facades\Route;
use Modules\Chat\App\Http\Controllers\ChatController;

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

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/threads', [ChatController::class, 'getUserThreads']);

    // ✅ جلب الرسائل الخاصة بمحادثة معينة
    Route::post('/messages/chatThread', [ChatController::class, 'getMessages']);

    // ✅ إرسال رسالة جديدة
    Route::post('/messages', [ChatController::class, 'sendMessage']);});
