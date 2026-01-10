<?php

use Illuminate\Support\Facades\Route;
use Webkul\GoogleAds\Http\Controllers\GoogleAdsController;

Route::prefix('googleads')->group(function () {
    Route::get('', [GoogleAdsController::class, 'index'])->name('admin.googleads.index');
    Route::get('test-connection', [GoogleAdsController::class, 'testConnection'])->name('admin.googleads.test-connection');
    Route::get('api/test-connection', [GoogleAdsController::class, 'testConnection'])->name('admin.googleads.api.test-connection');
});
