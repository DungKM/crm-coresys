<?php

use Illuminate\Support\Facades\Route;
use Webkul\GoogleAds\Http\Controllers\GoogleAdsController;

Route::group([
    'prefix' => config('app.admin_path', 'admin') . '/google-ads',
    'middleware' => ['web', 'auth'],
], function () {
    Route::get('/', [GoogleAdsController::class, 'index'])
        ->name('admin.google_ads.index');

    Route::get('/test-connection', [GoogleAdsController::class, 'testConnection'])
        ->name('admin.google_ads.test_connection');

    Route::get('/campaigns', [GoogleAdsController::class, 'campaigns'])
        ->name('admin.google_ads.campaigns');
});