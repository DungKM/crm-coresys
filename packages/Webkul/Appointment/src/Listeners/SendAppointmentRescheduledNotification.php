<?php

namespace Webkul\Appointment\Listeners;

use Webkul\Appointment\Events\AppointmentRescheduled;
use Webkul\Appointment\Mail\AppointmentRescheduledMail;
use Webkul\Appointment\Models\AppointmentEmailLog;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAppointmentRescheduledNotification implements ShouldQueue
{
    public function handle(AppointmentRescheduled $event)
    {
        $appointment = $event->appointment;
        $oldStartAt = $event->oldStartAt;
        $oldEndAt = $event->oldEndAt;

        if (!config('appointment.email.enabled')) {
            return;
        }

        try {
            // Gửi email cho khách hàng
            if (config('appointment.email.send_to_customer') && !empty($appointment->customer_email)) {
                $emailLog = $appointment->generateEmailToken(
                    AppointmentEmailLog::EMAIL_TYPE_RESCHEDULED,
                    $appointment->customer_email,
                    'customer'
                );

                Mail::to($appointment->customer_email)
                    ->send(new AppointmentRescheduledMail($appointment, $emailLog, $oldStartAt, $oldEndAt));

                Log::info('Appointment rescheduled email sent to customer', [
                    'appointment_id' => $appointment->id,
                    'customer_email' => $appointment->customer_email,
                    'old_start' => $oldStartAt,
                    'new_start' => $appointment->start_at,
                    'email_log_id' => $emailLog->id
                ]);
            }

            Log::info('Rescheduled assignedUser check', [
            'appointment_id' => $appointment->id,
            'assignedUser_loaded' => $appointment->relationLoaded('assignedUser'),
            'assigned_user_id' => $appointment->assigned_user_id,
            'assignedUser' => $appointment->assignedUser,
            'assignedUser_email' => $appointment->assignedUser->email ?? null,
        ]);
            // Gửi email cho nhân viên
            if (config('appointment.email.send_to_assigned_user')) {

            if (!$appointment->relationLoaded('assignedUser')) {
                $appointment->load('assignedUser');
            }

            if ($appointment->assignedUser && !empty($appointment->assignedUser->email)) {

                $emailLog = $appointment->generateEmailToken(
                    AppointmentEmailLog::EMAIL_TYPE_RESCHEDULED,
                    $appointment->assignedUser->email,
                    'assigned_user'
                );

                $staffView = 'appointment::admin.appointments.staff-emails.rescheduled';

                Mail::to($appointment->assignedUser->email)
                    ->send(new AppointmentRescheduledMail($appointment, $emailLog, $oldStartAt, $oldEndAt, $staffView));

                Log::info('Appointment rescheduled email sent to assigned user', [
                    'appointment_id' => $appointment->id,
                    'user_email' => $appointment->assignedUser->email,
                    'email_log_id' => $emailLog->id
                ]);
            }
        }

        } catch (\Exception $e) {
            Log::error('Failed to send appointment rescheduled email', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
