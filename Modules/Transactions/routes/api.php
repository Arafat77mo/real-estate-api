<?php

use Illuminate\Support\Facades\Route;
use Modules\Transactions\App\Http\Controllers\TransactionsController;

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

Route::post('/process-transaction', [TransactionsController::class, 'processTransaction'])->name('transaction.process');

    Route::get('/payment/success', function () {
        return "تم الدفع بنجاح!";
    })->name('payment.success');

    Route::get('/payment/cancel', function () {
        return "تم إلغاء الدفع!";
    })->name('payment.cancel');
});
