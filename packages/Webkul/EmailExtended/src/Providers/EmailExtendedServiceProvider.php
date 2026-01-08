<?php

namespace Webkul\EmailExtended\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class EmailExtendedServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        if (file_exists(__DIR__ . '/../Routes/admin-routes.php')) {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');
        }
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'email_extended');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'email_extended');
        $this->publishes([__DIR__ . '/../Database/Migrations' => database_path('migrations'),], 'email-extended-migrations');
        $this->publishes([__DIR__ . '/../Resources/views' => resource_path('views/vendor/email_extended'),], 'email-extended-views');
        $this->publishes([__DIR__ . '/../Resources/lang' => lang_path('vendor/email_extended'),], 'email-extended-lang');
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Webkul\EmailExtended\Console\Commands\SendScheduledEmails::class,
                \Webkul\EmailExtended\Console\Commands\ProcessEmailTracking::class,
                \Webkul\EmailExtended\Console\Commands\CleanupEmailTracking::class,
                \Webkul\EmailExtended\Console\Commands\RetryFailedEmails::class,
                \Webkul\EmailExtended\Console\Commands\GenerateEmailStats::class,
                \Webkul\EmailExtended\Console\Commands\FetchGmailReplies::class, 
            ]);
        }
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            
            // Gửi email theo lịch trình mỗi phút 
            $schedule->command('email:send-scheduled')
                ->everyMinute()
                ->withoutOverlapping()
                ->runInBackground();
            
            // Theo dõi email mỗi 5p 
            $schedule->command('email:process-tracking')
                ->everyFiveMinutes()
                ->withoutOverlapping()
                ->runInBackground();
            
            // Dọn dep dữ liệu lưu trữ lúc 2h 
            $schedule->command('email:cleanup-tracking')
                ->dailyAt('02:00')
                ->withoutOverlapping();
            
            // Gửi lại email không thành công sau 10p 
            $schedule->command('email:retry-failed')
                ->everyTenMinutes()
                ->withoutOverlapping()
                ->runInBackground();
            
            // Tạo thống kê email hàng ngày lúc 3h sáng 
            $schedule->command('email:generate-stats')
                ->dailyAt('03:00')
                ->withoutOverlapping();
        });

        \Webkul\EmailExtended\Models\Email::observe(
            \Webkul\EmailExtended\Observers\EmailObserver::class
        );
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/email_extended.php','email_extended');
        if (file_exists(__DIR__ . '/../Helpers/helpers.php')) {
            require_once __DIR__ . '/../Helpers/helpers.php';
        }
        $this->app->bind(
            \Webkul\Email\Repositories\EmailRepository::class,
            \Webkul\EmailExtended\Repositories\EmailRepository::class
        );
        if (interface_exists(\Webkul\Email\Contracts\Email::class)) {
            $this->app->bind(
                \Webkul\Email\Contracts\Email::class,
                \Webkul\EmailExtended\Models\Email::class
            );
        }
        $this->app->bind(
            \Webkul\Email\Mails\Email::class,
            \Webkul\EmailExtended\Mail\Email::class
        );
        $this->registerRepositories();
        $this->registerHelpers();
        $this->registerFacades();
    }

    /**
     * Register repositories
     */
    protected function registerRepositories(): void
    {
        $this->app->singleton(
            \Webkul\EmailExtended\Repositories\EmailThreadRepository::class
        );
        $this->app->singleton(
            \Webkul\EmailExtended\Repositories\EmailTrackingRepository::class
        );
        $this->app->singleton(
            \Webkul\EmailExtended\Repositories\EmailScheduledRepository::class
        );
    }

    /**
     * Register helpers
     */
    protected function registerHelpers(): void
    {
        $this->app->singleton('email.thread.helper', function ($app) {
            return new \Webkul\EmailExtended\Helpers\EmailThreadHelper();
        });
        $this->app->singleton('email.tracking.helper', function ($app) {
            return new \Webkul\EmailExtended\Helpers\EmailTrackingHelper();
        });
        $this->app->alias('email.thread.helper', \Webkul\EmailExtended\Helpers\EmailThreadHelper::class);
        $this->app->alias('email.tracking.helper', \Webkul\EmailExtended\Helpers\EmailTrackingHelper::class);
    }

    /**
     * Register facades
     */
    protected function registerFacades(): void
    {
        // Register any facades here if needed
    }
}