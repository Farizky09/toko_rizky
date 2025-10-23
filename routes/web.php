<?php

use App\Http\Controllers\Backend\UserManagement\PermissionController;
use App\Http\Controllers\Backend\UserManagement\RoleController;
use App\Http\Controllers\Backend\UserManagementController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::prefix('admin')->name('admin.')->middleware(['role:admin'])->group(function () {
        Route::prefix('permission')->name('permission.')->group(function () {
            Route::get('/', [PermissionController::class, 'index'])->middleware('permission:read_permission')->name('permission.index');
            Route::get('/create', [PermissionController::class, 'create'])->middleware('permission:create_permission')->name('permission.create');
            Route::post('/store', [PermissionController::class, 'store'])->middleware('permission:create_permission')->name('permission.store');
            Route::get('/show/{id}', [PermissionController::class, 'show'])->middleware('permission:read_permission')->name('permission.show');
            Route::get('/edit/{id}', [PermissionController::class, 'edit'])->middleware('permission:update_permission')->name('permission.edit');
            Route::put('/update/{id}', [PermissionController::class, 'update'])->middleware('permission:update_permission')->name('permission.update');
            Route::delete('/delete/{id}', [PermissionController::class, 'delete'])->middleware('permission:delete_permission')->name('permission.delete');
        });
        Route::prefix('role')->name('role.')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->middleware('permission:read_role')->name('role.index');
            Route::get('/create', [RoleController::class, 'create'])->middleware('permission:create_role')->name('role.create');
            Route::post('/store', [RoleController::class, 'store'])->middleware('permission:create_role')->name('role.store');
            Route::get('/show/{id}', [RoleController::class, 'show'])->middleware('permission:read_role')->name('role.show');
            Route::get('/edit/{id}', [RoleController::class, 'edit'])->middleware('permission:update_role')->name('role.edit');
            Route::put('/update/{id}', [RoleController::class, 'update'])->middleware('permission:update_role')->name('role.update');
            Route::delete('/delete/{id}', [RoleController::class, 'delete'])->middleware('permission:delete_role')->name('role.delete');
        });
        Route::prefix('user-management')->name('user-management.')->group(function () {
            Route::get('/', [UserManagementController::class, 'index'])->middleware('permission:read_user_management')->name('user_management.index');
            Route::get('/create', [UserManagementController::class, 'create'])->middleware('permission:create_user_management')->name('user_management.create');
            Route::post('/store', [UserManagementController::class, 'store'])->middleware('permission:create_user_management')->name('user_management.store');
            Route::get('/show/{id}', [UserManagementController::class, 'show'])->middleware('permission:read_user_management')->name('user_management.show');
            Route::get('/edit/{id}', [UserManagementController::class, 'edit'])->middleware('permission:update_user_management')->name('user_management.edit');
            Route::put('/update/{id}', [UserManagementController::class, 'update'])->middleware('permission:update_user_management')->name('user_management.update');
            Route::delete('/delete/{id}', [UserManagementController::class, 'delete'])->middleware('permission:delete_user_management')->name('user_management.delete');
        });
    });
    Route::prefix('owner')->name('owner.')->middleware(['role:owner|admin'])->group(function () {});
    Route::prefix('cashier')->name('cashier.')->middleware(['role:cashier|admin'])->group(function () {});
    Route::prefix('inventory_staff')->name('inventory_staff.')->middleware(['role:inventory_staff|admin'])->group(function () {});
});

require __DIR__ . '/auth.php';
