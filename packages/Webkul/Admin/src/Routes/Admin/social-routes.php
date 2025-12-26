<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\SocialMessage\SocialMessageController;

Route::prefix('social-message')->group(function () {
    Route::get('/', [SocialMessageController::class, 'index'])->name('admin.social-message.index'); 
});