<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\workflow\WorkflowController;

Route::prefix('workflow')->group(function () {
    Route::get('/', [WorkflowController::class, 'dashboard'])
        ->name('admin.workflow.index');

    Route::get('/dashboard', [WorkflowController::class, 'dashboard'])
        ->name('admin.workflow.dashboard.index');

    Route::get('/connectkey', [WorkflowController::class, 'connectKey'])
        ->name('admin.workflow.connectkey.index');
        
    Route::get('/contentlibrary', [WorkflowController::class, 'contentLibrary'])
        ->name('admin.workflow.contentlibrary.index');
        
    Route::get('/automation', [WorkflowController::class, 'automation'])
        ->name('admin.workflow.automation.index');
        
    Route::get('/history', [WorkflowController::class, 'history'])
        ->name('admin.workflow.history.index');
});