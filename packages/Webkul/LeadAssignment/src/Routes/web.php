<?php

use Illuminate\Support\Facades\Route;
use Webkul\LeadAssignment\Http\Controllers\LeadAssignmentController;

Route::group([
    'prefix' => config('app.admin_path', 'admin') . '/settings/lead-assignment',
    'middleware' => ['web', 'auth'],
], function () {
    Route::get('/', [LeadAssignmentController::class, 'index'])->name('admin.settings.lead_assignment.index');
    Route::post('/', [LeadAssignmentController::class, 'store'])->name('admin.settings.lead_assignment.store');
});
