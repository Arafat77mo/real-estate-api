<?php

use Illuminate\Support\Facades\Route;
use Modules\Properties\App\Http\Controllers\PropertiesController;

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
    Route::post('properties', [PropertiesController::class, 'store']);

// تحديث عقار
    Route::post('properties/{id}', [PropertiesController::class, 'update']);

// عرض تفاصيل عقار
    Route::get('properties/{id}', [PropertiesController::class, 'show']);

// عرض جميع العقارات
    Route::get('properties', [PropertiesController::class, 'index']);
});



