<?php

namespace Webkul\GoogleAds\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\GoogleAds\Contracts\GoogleAdsServiceContract;
use Webkul\GoogleAds\Services\GoogleAdsService;

class AdminServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register service into DI container
        $this->app->bind(
            GoogleAdsServiceContract::class,
            GoogleAdsService::class
        );
    }

    public function boot(): void
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');

        // Load translations
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'googleads');

        // Load breadcrumbs config
        if (file_exists($breadcrumbs = __DIR__ . '/../Config/breadcrumbs.php')) {
            require $breadcrumbs;
        }

        // Load menu config
        if (file_exists($menu = __DIR__ . '/../Config/menu.php')) {
            $this->mergeConfigFrom($menu, 'menu.admin');
        }
    }
}
