<?php

use Illuminate\Support\Facades\Route;
use Modules\Properties\app\Http\Controllers\user\PropertiesController;

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
        // عرض تفاصيل عقار بناءً على المعرف
        Route::get('property/{id}', [PropertiesController::class, 'show'])->name('properties.show');

        // قائمة العقارات مع خيارات البحث
        Route::get('/properties', [PropertiesController::class, 'index'])->name('properties.index');

        // توصيات العقارات
        Route::get('properties/recommend', [PropertiesController::class, 'recommendProperties'])->name('properties.recommend');
});



