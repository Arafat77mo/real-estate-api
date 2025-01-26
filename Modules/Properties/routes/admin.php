<?php

use Illuminate\Support\Facades\Route;
use Modules\Properties\app\Http\Controllers\Admin\PropertiesController;

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


    Route::get('properties/{id}', [PropertiesController::class, 'show']);
// عرض جميع العقارات
    Route::get('properties', [PropertiesController::class, 'index']);
});



