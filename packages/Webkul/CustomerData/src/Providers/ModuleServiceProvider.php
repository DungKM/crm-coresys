<?php

namespace Webkul\CustomerData\Providers;

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load migration 
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        // Load routes 
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api-routes.php');
        // Load view 
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'customer-data');

        $this->publishes([__DIR__ . '/../Resources/views' => resource_path('views/vendor/customer_data'),], 'customer-data-views');
        $this->publishes([__DIR__ . '/../Config/menu.php' => config_path('customer_data.php'),], 'customer-data-config');
    }

    public function register(): void
    {
        // Register config
        $this->mergeConfigFrom( __DIR__ . '/../Config/menu.php','menu.admin');
    }
}
