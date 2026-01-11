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

    Route::get('/campaigns/{id}', [GoogleAdsController::class, 'show'])
        ->name('admin.google_ads.campaigns.show');

    Route::get('/campaigns/create', [GoogleAdsController::class, 'create'])
        ->name('admin.google_ads.campaigns.create');

    Route::post('/campaigns', [GoogleAdsController::class, 'store'])
        ->name('admin.google_ads.campaigns.store');

    Route::get('/campaigns/{id}/edit', [GoogleAdsController::class, 'edit'])
        ->name('admin.google_ads.campaigns.edit');

    Route::put('/campaigns/{id}', [GoogleAdsController::class, 'update'])
        ->name('admin.google_ads.campaigns.update');

    Route::delete('/campaigns/{id}', [GoogleAdsController::class, 'destroy'])
        ->name('admin.google_ads.campaigns.destroy');
});