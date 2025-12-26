<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Facebook\FacebookController;

Route::prefix('facebook')->group(function () {
    Route::get('/', [FacebookController::class, 'index'])->name('admin.facebook.index');
});