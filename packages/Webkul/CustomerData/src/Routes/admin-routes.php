<?php

use Illuminate\Support\Facades\Route;
use Webkul\CustomerData\Http\Controllers\Admin\CustomerDataController;

Route::group([
    'prefix' => 'admin/customer-data',
    'middleware' => ['web', 'admin'],
    'as' => 'admin.customer-data.'
], function () {
    Route::get('/', [CustomerDataController::class, 'index'])->name('index');
    Route::get('/create', [CustomerDataController::class, 'create'])->name('create');
    Route::post('/store', [CustomerDataController::class, 'store'])->name('store');

    Route::post('/mass-action', [CustomerDataController::class, 'massAction'])->name('mass-action');
    
    Route::get('/{id}', [CustomerDataController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [CustomerDataController::class, 'edit'])->name('edit');
    Route::put('/{id}', [CustomerDataController::class, 'update'])->name('update');
    Route::delete('/{id}', [CustomerDataController::class, 'destroy'])->name('destroy');
    
    Route::post('/{id}/send-verification', [CustomerDataController::class, 'sendVerificationEmail'])->name('send-verification');
    
    Route::post('/{id}/mark-spam', [CustomerDataController::class, 'markAsSpam'])->name('mark-spam');
    Route::post('/{id}/convert-to-lead', [CustomerDataController::class, 'convertToLead'])->name('convert-to-lead');
});

// Public verify route (không cần middleware admin)
Route::get('/verify/{token}', [CustomerDataController::class, 'verify'])->name('admin.customer-data.verify');
