<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\workflow\WorkflowController;

Route::prefix('workflow')->group(function () {
    Route::get('/', [WorkflowController::class, 'index'])
        ->name('admin.workflow.index');

    Route::get('/dashboard', [WorkflowController::class, 'dashboard'])
        ->name('admin.workflow.dashboard.index');

    Route::get('/connectkey', [WorkflowController::class, 'connectKey'])
        ->name('admin.workflow.connectkey.index');
});