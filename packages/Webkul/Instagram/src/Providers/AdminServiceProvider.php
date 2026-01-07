<?php

namespace Webkul\Instagram\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\Instagram\Contracts\InstagramMessengerContract;
use Webkul\Instagram\Services\InstagramMessengerService;

class AdminServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            InstagramMessengerContract::class,
            InstagramMessengerService::class
        );
    }
}