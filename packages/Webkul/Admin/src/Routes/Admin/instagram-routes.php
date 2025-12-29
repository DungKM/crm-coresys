<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Instagram\InstagramController;
use Webkul\Admin\Http\Controllers\Instagram\InstagramApiController;

Route::prefix('instagram')->group(function () {

    // 👉 UI
    Route::get('/', [InstagramController::class, 'index'])
        ->name('admin.instagram.index');
        
    // 👉 API
    Route::get('/conversations', [InstagramApiController::class, 'conversations']);
    Route::get('/messages', [InstagramApiController::class, 'messages']);
    Route::post('/send', [InstagramApiController::class, 'send']);
    Route::delete('/conversation', [InstagramApiController::class, 'delete']);
});