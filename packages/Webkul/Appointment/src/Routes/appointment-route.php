<?php

use Illuminate\Support\Facades\Route;
use Webkul\Appointment\Http\Controllers\AppointmentController;

Route::prefix('admin')
    ->middleware(['web', 'user'])
    ->group(function () {

        Route::prefix('appointments')->group(function () {

            // ===== ROUTE TĨNH (ĐẶT TRƯỚC) =====
            Route::get('/', [AppointmentController::class, 'index'])
                ->name('admin.appointments.index');

            Route::get('/list', [AppointmentController::class, 'getAppointments'])
                ->name('admin.appointments.list');

            Route::get('/create', [AppointmentController::class, 'add'])
                ->name('admin.appointments.create');

            Route::post('/store', [AppointmentController::class, 'store'])
                ->name('admin.appointments.store');

            Route::get('/edit/{id}', [AppointmentController::class, 'edit'])
                ->name('admin.appointments.edit');

            Route::get('/export', [AppointmentController::class, 'export'])
                ->name('admin.appointments.export');

            Route::get('/datagrid', [AppointmentController::class, 'datagrid'])
                ->name('admin.appointments.datagrid');

            Route::get('/get-lead-by-email', [AppointmentController::class, 'getLeadByEmail'])
                ->name('admin.appointments.get-lead-by-email');
            Route::get('/datagrid', [AppointmentController::class, 'datagrid'])
                ->name('admin.appointments.datagrid');

            Route::post('/update-time', [AppointmentController::class, 'updateTime'])->name('admin.appointments.update-time');
            Route::post('/{id}/update', [AppointmentController::class, 'update'])->whereNumber('id')->name('admin.appointments.update');
            Route::post('/{id}/cancel',[AppointmentController::class, 'cancel'])->name('admin.appointments.cancel');

            Route::get('/status-history', [AppointmentController::class, 'getStatusHistory'])
                ->name('admin.appointments.status-history');

            Route::post('/{id}/attendance', [AppointmentController::class, 'updateAttendance'])
                ->name('admin.appointments.attendance');

            // ===== ROUTE ĐỘNG (LUÔN ĐỂ CUỐI) =====
            Route::get('/{id}', [AppointmentController::class, 'show'])
                ->whereNumber('id');

            Route::patch('/{id}/status', [AppointmentController::class, 'updateStatus'])
                ->whereNumber('id');
        });
    });
