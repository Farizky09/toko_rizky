<?php

use App\Http\Controllers\Backend\BranchesController;
use App\Http\Controllers\Backend\CategoriesController;
use App\Http\Controllers\Backend\LocationsController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\ProductsController;
use App\Http\Controllers\Backend\PurchasesController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\SuppliersController;
use App\Http\Controllers\Backend\UnitLargesController;
use App\Http\Controllers\Backend\UnitSmallsController;
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
        Route::prefix('user-management')->name('user-management.')->group(function () {
            Route::get('/', [UserManagementController::class, 'index'])->middleware('permission:read_user_management')->name('index');
            Route::get('/create', [UserManagementController::class, 'create'])->middleware('permission:create_user_management')->name('create');
            Route::post('/store', [UserManagementController::class, 'store'])->middleware('permission:create_user_management')->name('store');
            Route::get('/show/{id}', [UserManagementController::class, 'show'])->middleware('permission:read_user_management')->name('show');
            Route::get('/edit/{id}', [UserManagementController::class, 'edit'])->middleware('permission:update_user_management')->name('edit');
            Route::put('/update/{id}', [UserManagementController::class, 'update'])->middleware('permission:update_user_management')->name('update');
            Route::delete('/delete/{id}', [UserManagementController::class, 'delete'])->middleware('permission:delete_user_management')->name('delete');
        });
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [CategoriesController::class, 'index'])->middleware('permission:read_categories')->name('index');
            Route::get('/create', [CategoriesController::class, 'create'])->middleware('permission:create_categories')->name('create');
            Route::post('/store', [CategoriesController::class, 'store'])->middleware('permission:create_categories')->name('store');
            Route::get('/show/{id}', [CategoriesController::class, 'show'])->middleware('permission:read_categories')->name('show');
            Route::get('/edit/{id}', [CategoriesController::class, 'edit'])->middleware('permission:update_categories')->name('edit');
            Route::put('/update/{id}', [CategoriesController::class, 'update'])->middleware('permission:update_categories')->name('update');
            Route::delete('/delete/{id}', [CategoriesController::class, 'delete'])->middleware('permission:delete_categories')->name('delete');
        });
        Route::prefix('unit-larges')->name('unit-larges.')->group(function () {
            Route::get('/', [UnitLargesController::class, 'index'])->middleware('permission:read_unit_larges')->name('index');
            Route::get('/create', [UnitLargesController::class, 'create'])->middleware('permission:create_unit_larges')->name('create');
            Route::post('/store', [UnitLargesController::class, 'store'])->middleware('permission:create_unit_larges')->name('store');
            Route::get('/show/{id}', [UnitLargesController::class, 'show'])->middleware('permission:read_unit_larges')->name('show');
            Route::get('/edit/{id}', [UnitLargesController::class, 'edit'])->middleware('permission:update_unit_larges')->name('edit');
            Route::put('/update/{id}', [UnitLargesController::class, 'update'])->middleware('permission:update_unit_larges')->name('update');
            Route::delete('/delete/{id}', [UnitLargesController::class, 'delete'])->middleware('permission:delete_unit_larges')->name('delete');
        });
        Route::prefix('unit-smalls')->name('unit-smalls.')->group(function () {
            Route::get('/', [UnitSmallsController::class, 'index'])->middleware('permission:read_unit_smalls')->name('index');
            Route::get('/create', [UnitSmallsController::class, 'create'])->middleware('permission:create_unit_smalls')->name('create');
            Route::post('/store', [UnitSmallsController::class, 'store'])->middleware('permission:create_unit_smalls')->name('store');
            Route::get('/show/{id}', [UnitSmallsController::class, 'show'])->middleware('permission:read_unit_smalls')->name('show');
            Route::get('/edit/{id}', [UnitSmallsController::class, 'edit'])->middleware('permission:update_unit_smalls')->name('edit');
            Route::put('/update/{id}', [UnitSmallsController::class, 'update'])->middleware('permission:update_unit_smalls')->name('update');
            Route::delete('/delete/{id}', [UnitSmallsController::class, 'delete'])->middleware('permission:delete_unit_smalls')->name('delete');
        });

        Route::prefix('suppliers')->name('suppliers.')->group(function () {
            Route::get('/', [SuppliersController::class, 'index'])->middleware('permission:read_suppliers')->name('index');
            Route::get('/create', [SuppliersController::class, 'create'])->middleware('permission:create_suppliers')->name('create');
            Route::post('/store', [SuppliersController::class, 'store'])->middleware('permission:create_suppliers')->name('store');
            Route::get('/show/{id}', [SuppliersController::class, 'show'])->middleware('permission:read_suppliers')->name('show');
            Route::get('/edit/{id}', [SuppliersController::class, 'edit'])->middleware('permission:update_suppliers')->name('edit');
            Route::put('/update/{id}', [SuppliersController::class, 'update'])->middleware('permission:update_suppliers')->name('update');
            Route::delete('/delete/{id}', [SuppliersController::class, 'delete'])->middleware('permission:delete_suppliers')->name('delete');
        });
        Route::prefix('branches')->name('branches.')->group(function () {
            Route::get('/', [BranchesController::class, 'index'])->middleware('permission:read_branches')->name('index');
            Route::get('/create', [BranchesController::class, 'create'])->middleware('permission:create_branches')->name('create');
            Route::post('/store', [BranchesController::class, 'store'])->middleware('permission:create_branches')->name('store');
            Route::get('/show/{id}', [BranchesController::class, 'show'])->middleware('permission:read_branches')->name('show');
            Route::get('/edit/{id}', [BranchesController::class, 'edit'])->middleware('permission:update_branches')->name('edit');
            Route::put('/update/{id}', [BranchesController::class, 'update'])->middleware('permission:update_branches')->name('update');
            Route::delete('/delete/{id}', [BranchesController::class, 'delete'])->middleware('permission:delete_branches')->name('delete');
        });
        Route::prefix('locations')->name('locations.')->group(function () {
            Route::get('/', [LocationsController::class, 'index'])->middleware('permission:read_locations')->name('index');
            Route::get('/create', [LocationsController::class, 'create'])->middleware('permission:create_locations')->name('create');
            Route::post('/store', [LocationsController::class, 'store'])->middleware('permission:create_locations')->name('store');
            Route::get('/show/{id}', [LocationsController::class, 'show'])->middleware('permission:read_locations')->name('show');
            Route::get('/edit/{id}', [LocationsController::class, 'edit'])->middleware('permission:update_locations')->name('edit');
            Route::put('/update/{id}', [LocationsController::class, 'update'])->middleware('permission:update_locations')->name('update');
            Route::delete('/delete/{id}', [LocationsController::class, 'delete'])->middleware('permission:delete_locations')->name('delete');
        });
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [ProductsController::class, 'index'])->middleware('permission:read_products')->name('index');
            Route::get('/create', [ProductsController::class, 'create'])->middleware('permission:create_products')->name('create');
            Route::post('/store', [ProductsController::class, 'store'])->middleware('permission:create_products')->name('store');
            Route::get('/show/{id}', [ProductsController::class, 'show'])->middleware('permission:read_products')->name('show');
            Route::get('/edit/{id}', [ProductsController::class, 'edit'])->middleware('permission:update_products')->name('edit');
            Route::put('/update/{id}', [ProductsController::class, 'update'])->middleware('permission:update_products')->name('update');
            Route::delete('/delete/{id}', [ProductsController::class, 'delete'])->middleware('permission:delete_products')->name('delete');
        });

        Route::prefix('purchases')->name('purchases.')->group(function () {
            Route::get('/', [PurchasesController::class, 'index'])->middleware('permission:read_purchases')->name('index');
            Route::get('/create', [PurchasesController::class, 'create'])->middleware('permission:create_purchases')->name('create');
            Route::post('/store', [PurchasesController::class, 'store'])->middleware('permission:create_purchases')->name('store');
            Route::get('/show/{id}', [PurchasesController::class, 'show'])->middleware('permission:read_purchases')->name('show');
            Route::get('/edit/{id}', [PurchasesController::class, 'edit'])->middleware('permission:update_purchases')->name('edit');
            Route::put('/update/{id}', [PurchasesController::class, 'update'])->middleware('permission:update_purchases')->name('update');
            Route::delete('/delete/{id}', [PurchasesController::class, 'delete'])->middleware('permission:delete_purchases')->name('delete');
            Route::get('/show-receive-form/{id}', [PurchasesController::class, 'showReceiveForm'])->name('receive-form');
            Route::put('/process-receive/{id}', [PurchasesController::class, 'processReceive'])->middleware('permission:update_purchases')->name('receive-process');
            Route::put('/cancel/{id}', [PurchasesController::class, 'cancel'])->middleware('permission:update_purchases')->name('cancel');
        });
    });
    Route::prefix('owner')->name('owner.')->middleware(['role:owner|admin'])->group(function () {});
    Route::prefix('cashier')->name('cashier.')->middleware(['role:cashier|admin'])->group(function () {});
    Route::prefix('inventory_staff')->name('inventory_staff.')->middleware(['role:inventory_staff|admin'])->group(function () {});
});

require __DIR__ . '/auth.php';
