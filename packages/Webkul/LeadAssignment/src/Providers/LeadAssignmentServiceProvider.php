<?php

namespace Webkul\LeadAssignment\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class LeadAssignmentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'leadassignment');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'leadassignment');

        // Đăng ký event để inject style cho admin layout nếu cần
        Event::listen('admin.layout.head.after', function ($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('leadassignment::components.layouts.style');
        });

        // Load breadcrumbs nếu có
        if (file_exists($breadcrumbs = __DIR__ . '/../Config/breadcrumbs.php')) {
            require $breadcrumbs;
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        // Đăng ký menu cho admin
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/menu.php',
            'menu.admin'
        );

        // Đăng ký phân quyền cho module
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php',
            'acl'
        );
    }
}
