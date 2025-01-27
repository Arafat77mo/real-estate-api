<?php

use Illuminate\Support\Facades\Route;
use Modules\Notification\App\Http\Controllers\NotificationController;

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
    Route::get('notifications/unread', [NotificationController::class, 'getUnreadNotifications']);
    Route::get('notifications', [NotificationController::class, 'getNotifications']);
    Route::post('notifications/{notificationId}/read', [NotificationController::class, 'markAsRead']);
    Route::post('notifications/read', [NotificationController::class, 'markAllAsRead']);
    Route::delete('notifications/{notificationId}', [NotificationController::class, 'deleteNotification']);
    Route::delete('notifications', [NotificationController::class, 'deleteAllNotifications']);
});
