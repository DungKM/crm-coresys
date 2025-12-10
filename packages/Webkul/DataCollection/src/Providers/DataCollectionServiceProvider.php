<?php

namespace Webkul\DataCollection\Providers;

use Illuminate\Support\ServiceProvider;

class DataCollectionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'dataCollection');

        $this->publishes([
            base_path('packages/Webkul/Admin/src/Resources/assets/images/logo.svg')
                => public_path('images/logo.svg'),
        ], 'public');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
