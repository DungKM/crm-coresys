<?php

use Illuminate\Support\Facades\Route;
use Webkul\Appointment\Http\Controllers\Api\AppointmentApiController;

Route::prefix('api/v1')
    ->middleware(['api'])
    ->group(function () {

        Route::prefix('appointments')->group(function () {

            // ==========================================
            // TẠO APPOINTMENT
            // ==========================================

            // Tạo appointment CÓ EMAIL (tạo Lead + Appointment)
            // Status: scheduled → rescheduled khi update
            Route::post('/', [AppointmentApiController::class, 'apiStore'])
                ->name('api.appointments.store');

            // Tạo appointment KHÔNG CÓ EMAIL (chỉ Appointment)
            // Status: confirmed (luôn luôn)
            Route::post('/new-customer', [AppointmentApiController::class, 'apiStoreNewCustomer'])
                ->name('api.appointments.store-new-customer');

            // ==========================================
            // CẬP NHẬT APPOINTMENT
            // ==========================================

            // Cập nhật appointment CÓ LEAD (có email)
            // Status: scheduled → rescheduled
            Route::put('/{id}', [AppointmentApiController::class, 'apiUpdate'])
                ->whereNumber('id')
                ->name('api.appointments.update');

            // Cập nhật appointment KHÔNG CÓ LEAD (không email)
            // Status: confirmed (luôn luôn)
            Route::put('/{id}/new-customer', [AppointmentApiController::class, 'apiUpdateNewCustomer'])
                ->whereNumber('id')
                ->name('api.appointments.update-new-customer');

            // ==========================================
            // CÁC OPERATIONS KHÁC
            // ==========================================

            // Lấy chi tiết appointment
            Route::get('/{id}', [AppointmentApiController::class, 'apiShow'])
                ->whereNumber('id')
                ->name('api.appointments.show');

            // Update status (cho cả 2 loại)
            Route::patch('/{id}/status', [AppointmentApiController::class, 'apiUpdateStatus'])
                ->whereNumber('id')
                ->name('api.appointments.update-status');

            // Cancel appointment (cho cả 2 loại)
            Route::post('/{id}/cancel', [AppointmentApiController::class, 'apiCancel'])
                ->whereNumber('id')
                ->name('api.appointments.cancel');

        });
    });
