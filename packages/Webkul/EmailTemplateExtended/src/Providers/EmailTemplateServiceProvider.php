<?php

namespace Webkul\EmailTemplateExtended\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class EmailTemplateServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/Emailtemplate-routes.php');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'email_template_extended');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'email_template_extended');
        
        // Publish config
        $this->publishes([
            __DIR__ . '/../Config/email_template_extended.php' => config_path('email_template_extended.php'),
        ], 'emailtemplate-config');
        
        // Publish assets - Email Builder
        $this->publishes([
            __DIR__ . '/../Resources/public/email-builder' => public_path('vendor/emailtemplateextended/email-builder'),
        ], 'emailtemplate-assets');
        
        $this->registerEventListeners();
        $this->registerViewComposers();
        $this->registerMenu();
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        // Merge config
        $this->mergeConfigFrom(__DIR__ . '/../Config/email_template_extended.php','email_template_extended');
        // Register repositories
        $this->app->bind(
            \Webkul\EmailTemplateExtended\Contracts\EmailTemplate::class,
            \Webkul\EmailTemplateExtended\Models\EmailTemplate::class
        );
        $this->app->singleton(
            \Webkul\EmailTemplateExtended\DataGrids\EmailTemplateDataGrid::class
        );
    }

    /**
     * Register event listeners
     */
    protected function registerEventListeners(): void
    {
        // Tự động track usage khi gửi email
        Event::listen('email.sent', function ($event) {
            if (isset($event->template_id)) {
                $repository = app(\Webkul\EmailTemplateExtended\Repositories\EmailTemplateRepository::class);
                $template = $repository->find($event->template_id);
                
                if ($template) {
                    $template->incrementUsage();
                }
            }
        });

        // Auto-generate preview text => gọi từ model sang (boot)
        Event::listen('email_template.create.before', function () {});

        // Log template changes
        Event::listen('email_template.update.after', function ($template) {
            Log::info('Email template updated', [
                'template_id' => $template->id,
                'template_name' => $template->name,
                'user_id' => auth()->guard('user')->id(),
            ]);
        });
    }

    /**
     * Register view composers
     */
    protected function registerViewComposers(): void
    {
        // Share categories với tất cả views
        view()->composer('email_template_extended::*', function ($view) {
            $categories = app(\Webkul\EmailTemplateExtended\Contracts\EmailTemplate::class)::getCategories();
            $variableTypes = app(\Webkul\EmailTemplateExtended\Contracts\EmailTemplate::class)::getVariableTypes();
            
            $view->with([
                'categories' => $categories,
                'variableTypes' => $variableTypes,
            ]);
        });
    }

    /*
     * Thêm Templates vào menu Mail
     */
    protected function registerMenu(): void
    {
        config([
            'menu.admin' => array_merge(config('menu.admin', []), [
                [
                    'key'        => 'mail.templates',
                    'name'       => 'Templates',
                    'route'      => 'admin.email_templates.index',
                    'sort'       => 6, 
                    'icon-class' => 'icon-template', 
                ],
            ])
        ]);
    }
}