<?php

use Illuminate\Support\Facades\Route;
use Webkul\Appointment\Http\Controllers\Api\AppointmentApiController;

Route::prefix('api/v1')
    ->middleware(['api'])
    ->group(function () {

        Route::prefix('appointments')->group(function () {

            // Tạo appointment từ external system
            Route::post('/', [AppointmentApiController::class, 'apiStore'])
                ->name('api.appointments.store');

            // Lấy chi tiết appointment
            Route::get('/{id}', [AppointmentApiController::class, 'show'])
                ->whereNumber('id')
                ->name('api.appointments.show');

            // Update status
            Route::patch('/{id}/status', [AppointmentApiController::class, 'updateStatus'])
                ->whereNumber('id')
                ->name('api.appointments.update-status');

            // Cancel appointment
            Route::post('/{id}/cancel', [AppointmentApiController::class, 'cancel'])
                ->whereNumber('id')
                ->name('api.appointments.cancel');

        });
    });
