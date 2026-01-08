<?php

namespace Webkul\Appointment\Listeners;

use Webkul\Appointment\Events\AppointmentReminder;
use Webkul\Appointment\Mail\AppointmentReminderMail;
use Webkul\Appointment\Models\AppointmentEmailLog;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAppointmentReminderNotification implements ShouldQueue
{
    public function handle(AppointmentReminder $event)
    {
        $appointment = $event->appointment;
        $hoursUntil = $event->hoursUntil;

        if (!config('appointment.email.enabled')) {
            return;
        }

        try {
            // Gửi cho khách hàng
            if (config('appointment.email.send_to_customer') && !empty($appointment->customer_email)) {
                $emailLog = $appointment->generateEmailToken(
                    AppointmentEmailLog::EMAIL_TYPE_REMINDER,
                    $appointment->customer_email,
                    'customer',
                    ['hours_until' => $hoursUntil],
                    $hoursUntil
                );

                Mail::to($appointment->customer_email)
                    ->send(new AppointmentReminderMail($appointment, $emailLog, $hoursUntil));

                // Update reminder_sent_at
                $remindersSent = $appointment->reminder_sent_at ?? [];
                $remindersSent[] = [
                    'hours' => $hoursUntil,
                    'sent_at' => now()->toDateTimeString(),
                    'email_log_id' => $emailLog->id,
                ];

                $appointment->update(['reminder_sent_at' => $remindersSent]);

                Log::info('Appointment reminder email sent to customer', [
                    'appointment_id' => $appointment->id,
                    'customer_email' => $appointment->customer_email,
                    'hours_until' => $hoursUntil,
                    'email_log_id' => $emailLog->id,
                ]);
            }

            // Gửi cho nhân viên
            if (config('appointment.email.send_to_assigned_user')) {
                if (!$appointment->relationLoaded('assignedUser')) {
                    $appointment->load('assignedUser');
                }

                if ($appointment->assignedUser && !empty($appointment->assignedUser->email)) {

                    $emailLog = $appointment->generateEmailToken(
                        AppointmentEmailLog::EMAIL_TYPE_REMINDER,
                        $appointment->assignedUser->email,
                        'assigned_user',
                        ['hours_until' => $hoursUntil]
                    );

                    // ✅ view riêng cho nhân viên
                    $staffView = 'appointment::admin.appointments.staff-emails.reminder';

                    Mail::to($appointment->assignedUser->email)
                        ->send(new AppointmentReminderMail($appointment, $emailLog, $hoursUntil, $staffView));

                    Log::info('Appointment reminder email sent to assigned user', [
                        'appointment_id' => $appointment->id,
                        'user_email' => $appointment->assignedUser->email,
                        'hours_until' => $hoursUntil,
                        'email_log_id' => $emailLog->id,
                    ]);
                }
            }


        } catch (\Exception $e) {
            Log::error('Failed to send appointment reminder email', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
