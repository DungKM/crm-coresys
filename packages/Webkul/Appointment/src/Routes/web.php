<?php

use Illuminate\Support\Facades\Route;
use Webkul\Appointment\Http\Controllers\PublicAppointmentController;

/*
|--------------------------------------------------------------------------
| Public Appointment Routes (No Auth Required)
|--------------------------------------------------------------------------
| These routes handle email actions: confirm, cancel, reschedule, ICS download
*/

Route::middleware('web')
    ->prefix('appointments/public')
    ->name('appointment.public.')
    ->group(function () {

        Route::get('/{id}/confirm/{token}', [PublicAppointmentController::class, 'confirm'])
            ->name('confirm');

        Route::get('/{id}/cancel/{token}', [PublicAppointmentController::class, 'showCancelForm'])
            ->name('cancel');

        Route::post('/{id}/cancel/{token}', [PublicAppointmentController::class, 'cancel'])
            ->name('cancel.process');

        Route::get('/{id}/reschedule/{token}', [PublicAppointmentController::class, 'showRescheduleForm'])
            ->name('reschedule');

        Route::post('/{id}/reschedule/{token}', [PublicAppointmentController::class, 'reschedule'])
            ->name('reschedule.process');

        Route::get('/{id}/ics/{token}', [PublicAppointmentController::class, 'downloadICS'])
            ->name('ics');

        Route::get('/{id}/track/{token}', [PublicAppointmentController::class, 'trackEmailOpened'])
            ->name('track');
    });
