<?php

namespace Webkul\Appointment\Listeners;

use Webkul\Appointment\Events\AppointmentUpdated;
use Webkul\Appointment\Mail\AppointmentCreatedMail; // Tạm dùng template created
use Webkul\Appointment\Models\AppointmentEmailLog;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAppointmentUpdatedNotification implements ShouldQueue
{
    public function handle(AppointmentUpdated $event)
    {
        $appointment = $event->appointment;
        $changes = $event->changes;

        if (!config('appointment.email.enabled')) {
            return;
        }

        try {
            // Gửi email cho khách hàng
            if (config('appointment.email.send_to_customer') && $appointment->customer_email) {
                $emailLog = $appointment->generateEmailToken(
                    AppointmentEmailLog::EMAIL_TYPE_UPDATED,
                    $appointment->customer_email,
                    'customer'
                );

                // Tạm dùng AppointmentCreatedMail, sau này tạo AppointmentUpdatedMail riêng
                Mail::to($appointment->customer_email)
                    ->send(new AppointmentCreatedMail($appointment, $emailLog));

                Log::info('Appointment updated email sent to customer', [
                    'appointment_id' => $appointment->id,
                    'customer_email' => $appointment->customer_email,
                    'email_log_id' => $emailLog->id,
                    'changes' => $changes
                ]);
            }

            // Gửi email cho nhân viên được phân công
            if (config('appointment.email.send_to_assigned_user')
                && $appointment->assignedUser
                && $appointment->assignedUser->email) {

                $emailLog = $appointment->generateEmailToken(
                    AppointmentEmailLog::EMAIL_TYPE_UPDATED,
                    $appointment->assignedUser->email,
                    'assigned_user'
                );

                Mail::to($appointment->assignedUser->email)
                    ->send(new AppointmentCreatedMail($appointment, $emailLog));

                Log::info('Appointment updated email sent to assigned user', [
                    'appointment_id' => $appointment->id,
                    'user_email' => $appointment->assignedUser->email,
                    'email_log_id' => $emailLog->id
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Failed to send appointment updated email', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
