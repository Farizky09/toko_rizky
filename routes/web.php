<?php

use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserManagementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::prefix('admin')->middleware(['role:admin'])->group(function () {
        Route::prefix('permission')->name('permission.')->group(function () {
            Route::get('/', [PermissionController::class, 'index'])->middleware('permission:read_permission')->name('index');
            Route::get('/create', [PermissionController::class, 'create'])->middleware('permission:create_permission')->name('create');
            Route::post('/store', [PermissionController::class, 'store'])->middleware('permission:create_permission')->name('store');
            Route::get('/show/{id}', [PermissionController::class, 'show'])->middleware('permission:read_permission')->name('show');
            Route::get('/edit/{id}', [PermissionController::class, 'edit'])->middleware('permission:update_permission')->name('edit');
            Route::put('/update/{id}', [PermissionController::class, 'update'])->middleware('permission:update_permission')->name('update');
            Route::delete('/delete/{id}', [PermissionController::class, 'delete'])->middleware('permission:delete_permission')->name('delete');
        });
        Route::prefix('role')->name('role.')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->middleware('permission:read_role')->name('index');
            Route::get('/create', [RoleController::class, 'create'])->middleware('permission:create_role')->name('create');
            Route::post('/store', [RoleController::class, 'store'])->middleware('permission:create_role')->name('store');
            Route::get('/show/{id}', [RoleController::class, 'show'])->middleware('permission:read_role')->name('show');
            Route::get('/edit/{id}', [RoleController::class, 'edit'])->middleware('permission:update_role')->name('edit');
            Route::put('/update/{id}', [RoleController::class, 'update'])->middleware('permission:update_role')->name('update');
            Route::delete('/delete/{id}', [RoleController::class, 'delete'])->middleware('permission:delete_role')->name('delete');
        });
        Route::prefix('user-management')->name('user_management.')->group(function () {
            Route::get('/', [UserManagementController::class, 'index'])->middleware('permission:read_user_management')->name('index');
            Route::get('/create', [UserManagementController::class, 'create'])->middleware('permission:create_user_management')->name('create');
            Route::post('/store', [UserManagementController::class, 'store'])->middleware('permission:create_user_management')->name('store');
            Route::get('/show/{id}', [UserManagementController::class, 'show'])->middleware('permission:read_user_management')->name('show');
            Route::get('/edit/{id}', [UserManagementController::class, 'edit'])->middleware('permission:update_user_management')->name('edit');
            Route::put('/update/{id}', [UserManagementController::class, 'update'])->middleware('permission:update_user_management')->name('update');
            Route::delete('/delete/{id}', [UserManagementController::class, 'delete'])->middleware('permission:delete_user_management')->name('delete');
        });
    });
    Route::prefix('owner')->name('owner.')->middleware(['role:owner|admin'])->group(function () {});
    Route::prefix('cashier')->name('cashier.')->middleware(['role:cashier|admin'])->group(function () {});
    Route::prefix('inventory_staff')->name('inventory_staff.')->middleware(['role:inventory_staff|admin'])->group(function () {});
});

require __DIR__ . '/auth.php';
