<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Facebook\FacebookController;
use Webkul\Admin\Http\Controllers\Facebook\FacebookApiController;
Route::prefix('facebook')->group(function () {
    Route::get('/', [FacebookController::class, 'index'])->name('admin.facebook.index');
    Route::get('/conversations', [FacebookApiController::class, 'conversations']);
    Route::get('/messages', [FacebookApiController::class, 'messages']);
    Route::post('/send', [FacebookApiController::class, 'send'])
        ->name('admin.facebook.send');
    Route::delete('/conversation', [FacebookApiController::class, 'deleteConversation'])
    ->name('admin.facebook.conversation.delete');
});