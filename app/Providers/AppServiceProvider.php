<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
<<<<<<< HEAD
        //
=======
        // Thêm đoạn này vào đầu hàm boot
    \Illuminate\Support\Facades\App::setLocale('vi');
    \Carbon\Carbon::setLocale('vi');
>>>>>>> upstream/main
    }
}
