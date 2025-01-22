<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\app\Http\Controllers\Admin\AdminController;

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

Route::middleware(['auth'])->group(function () {
    Route::get('users', [AdminController::class, 'index'])->name('admin.users.index');
    Route::get('users/{id}', [AdminController::class, 'show'])->name('admin.users.show');
    Route::post('users', [AdminController::class, 'create'])->name('admin.users.create');
    Route::put('users/{id}', [AdminController::class, 'update'])->name('admin.users.update');
    Route::delete('users/{id}', [AdminController::class, 'destroy'])->name('admin.users.destroy');

    // Role Routes
    Route::get('/roles', [AdminController::class, 'getAllRoles']);
    Route::post('/roles', [AdminController::class, 'createRole'])->name('roles.create');
    Route::put('/roles/{roleId}', [AdminController::class, 'updateRole'])->name('roles.update');
    Route::delete('/roles/{roleId}', [AdminController::class, 'deleteRole'])->name('roles.delete');

    // Permission Routes
    Route::get('/permissions', [AdminController::class, 'getAllPermissions']);
    Route::post('/permissions', [AdminController::class, 'createPermission'])->name('permissions.create');
    Route::put('/permissions/{permissionId}', [AdminController::class, 'updatePermission'])->name('permissions.update');
    Route::delete('/permissions/{permissionId}', [AdminController::class, 'deletePermission'])->name('permissions.delete');
});
