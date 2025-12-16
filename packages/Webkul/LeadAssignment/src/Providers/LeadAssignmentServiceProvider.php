<?php

namespace Webkul\LeadAssignment\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Webkul\LeadAssignment\Console\Commands\BackfillLeadAssignment;

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

        // Tự động phân bổ user_id cho lead mới tạo theo cấu hình hiện tại
        $this->registerAutoAssignmentOnLeadCreated();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();

        if ($this->app->runningInConsole()) {
            $this->commands([
                BackfillLeadAssignment::class,
                \Webkul\LeadAssignment\Console\Commands\TestLeadAssignment::class,
            ]);
        }
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

    /**
     * Listen to Lead created event and auto-assign user_id if enabled.
     */
    protected function registerAutoAssignmentOnLeadCreated(): void
    {
        // Tránh lỗi nếu model Lead không khả dụng
        if (!class_exists('Webkul\\Lead\\Models\\Lead')) {
            return;
        }

        Event::listen('eloquent.created: Webkul\\Lead\\Models\\Lead', function ($lead) {
            try {
                // Không can thiệp nếu đã có user_id (ví dụ import hoặc tạo thủ công với người phụ trách)
                if (!empty($lead->user_id)) {
                    return;
                }

                $service = app(\Webkul\LeadAssignment\Services\LeadAssignmentService::class);
                $userId = $service->assignUserId();

                if ($userId) {
                    $lead->user_id = $userId;
                    // Lưu nhanh, tránh chạy events khác
                    $lead->save();
                }
            } catch (\Throwable $e) {
                // Nuốt lỗi để không làm hỏng flow tạo lead; có thể log nếu cần
                // logger()->warning('Auto-assign failed: ' . $e->getMessage());
            }
        });
    }
}
