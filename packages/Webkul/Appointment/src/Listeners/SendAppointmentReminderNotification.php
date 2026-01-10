<?php

namespace Webkul\Appointment\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Webkul\Appointment\Models\AppointmentEmailLog;
use Webkul\Appointment\Events\AppointmentReminder;
use Webkul\Appointment\Mail\AppointmentReminderMail;

class SendAppointmentReminderNotification
{
    /**
     * Handle the event.
     *
     * @param AppointmentReminder $event
     * @return void
     */
    public function handle(AppointmentReminder $event)
    {
        $appointment = $event->appointment;
        $minutesUntil = $event->minutesUntil;

        if (!config('appointment.email.enabled')) {
            return;
        }

        try {
            // Load relationships nếu chưa có
            if (!$appointment->relationLoaded('assignedUser')) {
                $appointment->load('assignedUser');
            }
            if (!$appointment->relationLoaded('service')) {
                $appointment->load('service');
            }

            // Gửi email cho khách hàng
            if (config('appointment.email.send_to_customer') && !empty($appointment->customer_email)) {
                $emailLog = $appointment->generateEmailToken(
                    AppointmentEmailLog::EMAIL_TYPE_REMINDER,
                    $appointment->customer_email,
                    'customer',
                    ['minutes_until' => $minutesUntil],
                    $minutesUntil
                );

                Mail::to($appointment->customer_email)
                    ->send(new AppointmentReminderMail(
                        $appointment,
                        $emailLog,
                        $minutesUntil
                    ));

                // Cập nhật reminder_sent_at
                $remindersSent = $appointment->reminder_sent_at ?? [];
                $remindersSent[] = [
                    'minutes' => $minutesUntil,
                    'sent_at' => now()->toDateTimeString(),
                    'email_log_id' => $emailLog->id,
                    'recipient' => 'customer',
                ];
                $appointment->update(['reminder_sent_at' => $remindersSent]);

                Log::info('Appointment reminder email sent to customer', [
                    'appointment_id' => $appointment->id,
                    'customer_email' => $appointment->customer_email,
                    'minutes_until' => $minutesUntil,
                    'email_log_id' => $emailLog->id,
                ]);
            }

            // Gửi email cho nhân viên được phân công
            if (config('appointment.email.send_to_assigned_user')) {
                if ($appointment->assignedUser && !empty($appointment->assignedUser->email)) {
                    $emailLog = $appointment->generateEmailToken(
                        AppointmentEmailLog::EMAIL_TYPE_REMINDER,
                        $appointment->assignedUser->email,
                        'assigned_user',
                        ['minutes_until' => $minutesUntil]
                    );

                    // View riêng cho nhân viên
                    $staffView = 'appointment::admin.appointments.staff-emails.reminder';

                    Mail::to($appointment->assignedUser->email)
                        ->send(new AppointmentReminderMail(
                            $appointment,
                            $emailLog,
                            $minutesUntil,
                            $staffView
                        ));

                    Log::info('Appointment reminder email sent to assigned user', [
                        'appointment_id' => $appointment->id,
                        'user_email' => $appointment->assignedUser->email,
                        'minutes_until' => $minutesUntil,
                        'email_log_id' => $emailLog->id,
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to send appointment reminder email', [
                'appointment_id' => $appointment->id,
                'minutes_until' => $minutesUntil,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
