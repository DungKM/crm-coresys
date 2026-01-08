<?php

namespace Webkul\Appointment\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Webkul\Appointment\Models\Appointment;
use Webkul\Appointment\Contracts\Appointment as AppointmentContract;

class AppointmentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/appointment-route.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api-routes.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'appointment');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'appointment');

        $this->publishes([
            __DIR__ . '/../Resources/views' => resource_path('views/vendor/appointment'),
        ], 'appointment-views');

        $this->publishes([
            __DIR__ . '/../Resources/lang' => lang_path('vendor/appointment'),
        ], 'appointment-lang');
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();

        $this->mergeConfigFrom(
        dirname(__DIR__) . '/Config/breadcrumbs.php',
        'breadcrumbs'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/menu.php',
            'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php',
            'acl'
        );

    }

    /**
     * Register package config.
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/menu.php',
            'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php',
            'acl'
        );

        $this->mergeConfigFrom(
        dirname(__DIR__) . '/Config/appointment.php',
        'appointment'
        );
    }
}
