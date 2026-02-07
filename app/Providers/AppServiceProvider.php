<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Interfaces\PermissionInterfaces::class, \App\Repositories\PermissionRepository::class);
        $this->app->bind(\App\Interfaces\RoleInterfaces::class, \App\Repositories\RoleRepository::class);
        $this->app->bind(\App\Interfaces\UserManagementInterfaces::class, \App\Repositories\UserManagementRepository::class);
        $this->app->bind(\App\Interfaces\CategoriesInterfaces::class, \App\Repositories\CategoriesRepository::class);
        $this->app->bind(\App\Interfaces\UnitLargesInterfaces::class, \App\Repositories\UnitLargesRepository::class);
        $this->app->bind(\App\Interfaces\UnitSmallsInterfaces::class, \App\Repositories\UnitSmallsRepository::class);
        $this->app->bind(\App\Interfaces\SuppliersInterfaces::class, \App\Repositories\SuppliersRepository::class);
        $this->app->bind(\App\Interfaces\BranchesInterfaces::class, \App\Repositories\BranchesRepository::class);
        $this->app->bind(\App\Interfaces\LocationsInterfaces::class, \App\Repositories\LocationsRepository::class);
        $this->app->bind(\App\Interfaces\ProductsInterfaces::class, \App\Repositories\ProductsRepository::class);
        $this->app->bind(\App\Interfaces\PurchasesInterfaces::class, \App\Repositories\PurchasesRepository::class);
        $this->app->bind(\App\Interfaces\GoodReceiptsInterfaces::class, \App\Repositories\GoodReceiptsRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('currency', function ($expression) {
            return "Rp <?php echo number_format($expression, 0, ',', '.'); ?>,-";
        });
    }
}
