<?php

namespace Webkul\Appointment\Listeners;

use Webkul\Appointment\Events\AppointmentConfirmed;
use Webkul\Appointment\Mail\AppointmentConfirmedMail;
use Webkul\Appointment\Models\AppointmentEmailLog;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAppointmentConfirmedNotification implements ShouldQueue
{
    public function handle(AppointmentConfirmed $event)
    {
        $appointment = $event->appointment;
        $confirmedBy = $event->confirmedBy;

        if (!config('appointment.email.enabled')) {
            return;
        }

        try {
            // ===== Mail khách hàng =====
            if (config('appointment.email.send_to_customer') && $appointment->customer_email) {
                $emailLog = $appointment->generateEmailToken(
                    AppointmentEmailLog::EMAIL_TYPE_CONFIRMED,
                    $appointment->customer_email,
                    'customer'
                );

                Mail::to($appointment->customer_email)
                    ->send(new AppointmentConfirmedMail($appointment, $emailLog)); // dùng view mặc định

                Log::info('Appointment confirmed email sent to customer', [
                    'appointment_id' => $appointment->id,
                    'customer_email' => $appointment->customer_email,
                    'confirmed_by' => $confirmedBy,
                    'email_log_id' => $emailLog->id
                ]);
            }

            // ===== Mail nhân viên =====
            if (config('appointment.email.send_to_assigned_user')
                && $appointment->assignedUser
                && $appointment->assignedUser->email) {

                $emailLog = $appointment->generateEmailToken(
                    AppointmentEmailLog::EMAIL_TYPE_CONFIRMED,
                    $appointment->assignedUser->email,
                    'assigned_user'
                );

                // ✅ Dùng view riêng cho nhân viên
                $staffView = 'appointment::admin.appointments.staff-emails.confirmed';

                Mail::to($appointment->assignedUser->email)
                    ->send(new AppointmentConfirmedMail($appointment, $emailLog, $staffView));

                Log::info('Appointment confirmed email sent to assigned user', [
                    'appointment_id' => $appointment->id,
                    'user_email' => $appointment->assignedUser->email,
                    'email_log_id' => $emailLog->id
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Failed to send appointment confirmed email', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
