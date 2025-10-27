<?php

namespace App\Providers;

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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
