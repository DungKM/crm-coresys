<?php

use Illuminate\Support\Facades\Route;
use Webkul\LeadAssignment\Http\Controllers\LeadAssignmentController;

Route::prefix('leadassignment')->group(function () {
    Route::get('', [LeadAssignmentController::class, 'index'])->name('admin.leadassignment.index');
});
