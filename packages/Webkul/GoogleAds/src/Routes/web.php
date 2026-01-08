<?php

use Illuminate\Support\Facades\Route;
use Webkul\GoogleAds\Http\Controllers\GoogleAdsController;

Route::prefix('googleads')->group(function () {
    Route::get('', [GoogleAdsController::class, 'index'])->name('admin.googleads.index');
});
