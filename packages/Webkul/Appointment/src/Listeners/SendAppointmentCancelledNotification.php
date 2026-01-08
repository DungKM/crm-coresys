<?php

namespace Webkul\Appointment\Listeners;

use Webkul\Appointment\Events\AppointmentCancelled;
use Webkul\Appointment\Mail\AppointmentCancelledMail;
use Webkul\Appointment\Models\AppointmentEmailLog;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAppointmentCancelledNotification implements ShouldQueue
{
    public function handle(AppointmentCancelled $event)
    {
        $appointment = $event->appointment;
        $reason = $event->reason;
        $cancelledBy = $event->cancelledBy;

        if (!config('appointment.email.enabled')) {
            return;
        }

        try {
            // Gửi email cho khách hàng
            if (config('appointment.email.send_to_customer') && !empty($appointment->customer_email)) {
                $emailLog = $appointment->generateEmailToken(
                    AppointmentEmailLog::EMAIL_TYPE_CANCELLED,
                    $appointment->customer_email,
                    'customer'
                );

                Mail::to($appointment->customer_email)
                    ->send(new AppointmentCancelledMail($appointment, $reason, $cancelledBy));

                Log::info('Appointment cancelled email sent to customer', [
                    'appointment_id' => $appointment->id,
                    'customer_email' => $appointment->customer_email,
                    'email_log_id' => $emailLog->id
                ]);
            }

            // Gửi email cho nhân viên
            if (config('appointment.email.send_to_assigned_user')) {
                if (!$appointment->relationLoaded('assignedUser')) {
                    $appointment->load('assignedUser');
                }

                if ($appointment->assignedUser && !empty($appointment->assignedUser->email)) {

                    $emailLog = $appointment->generateEmailToken(
                        AppointmentEmailLog::EMAIL_TYPE_CANCELLED,
                        $appointment->assignedUser->email,
                        'assigned_user'
                    );

                    // Dùng blade riêng cho nhân viên
                    $staffView = 'appointment::admin.appointments.staff-emails.cancelled';

                    Mail::to($appointment->assignedUser->email)
                        ->send(new AppointmentCancelledMail($appointment, $reason, $cancelledBy, $staffView));

                    Log::info('Appointment cancelled email sent to assigned user', [
                        'appointment_id' => $appointment->id,
                        'user_email' => $appointment->assignedUser->email,
                        'email_log_id' => $emailLog->id
                    ]);
                }
            }

        } catch (\Exception $e) {
            Log::error('Failed to send appointment cancelled email', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
