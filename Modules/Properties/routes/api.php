<?php

use Illuminate\Support\Facades\Route;
use Modules\Properties\app\Http\Controllers\Owner\PropertiesController;
use Modules\Properties\app\Http\Controllers\Owner\UserReportController;

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
    Route::post('properties', [PropertiesController::class, 'store']);

// تحديث عقار
    Route::post('properties/{id}', [PropertiesController::class, 'update']);

// عرض تفاصيل عقار
    Route::get('properties/{id}', [PropertiesController::class, 'show']);
    Route::delete('properties/{id}', [PropertiesController::class, 'delete']);

// عرض جميع العقارات
    Route::get('properties', [PropertiesController::class, 'index']);

    Route::get('reports/renters', [UserReportController::class, 'showRentersReport']);
    Route::get('reports/installments', [UserReportController::class, 'showInstallmentsReport']);
    Route::get('reports/buyers', [UserReportController::class, 'showBuyersReport']);

    Route::get('reports/renter/{userId}/{propertyId}', [UserReportController::class, 'showRenterDetails']);
    Route::get('reports/installment/{userId}/{propertyId}', [UserReportController::class, 'showInstallmentDetails']);

    Route::get('/dashboard/analytics', [UserReportController::class, 'getDashboardAnalytics']);

});



