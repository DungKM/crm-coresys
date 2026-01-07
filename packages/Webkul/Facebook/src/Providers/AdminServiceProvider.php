<?php

namespace Webkul\Facebook\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\Facebook\Contracts\FacebookMessengerContract;
use Webkul\Facebook\Services\FacebookMessengerService;

class AdminServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            FacebookMessengerContract::class,
            FacebookMessengerService::class
        );
    }

    public function boot(): void
    {
        // nếu bạn có migrations trong package:
        // $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}