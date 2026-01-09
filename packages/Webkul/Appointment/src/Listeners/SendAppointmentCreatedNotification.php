<?php

namespace Webkul\Appointment\Listeners;

use Webkul\Appointment\Events\AppointmentCreated;
use Webkul\Appointment\Mail\AppointmentCreatedMail;
use Webkul\Appointment\Models\AppointmentEmailLog;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendAppointmentCreatedNotification
{
    public function handle(AppointmentCreated $event)
    {
        Log::info('STEP 1: SendAppointmentCreatedNotification triggered');

        $appointment = $event->appointment;

        Log::info('STEP 2: Appointment data received', [
            'appointment_id' => $appointment->id ?? null,
            'customer_email' => $appointment->customer_email ?? null,
            'assigned_user_id' => $appointment->assigned_user_id ?? null,
        ]);

        // 🔴 Check master email config
        if (!config('appointment.email.enabled')) {
            Log::warning('STEP 3: Email disabled by config appointment.email.enabled');
            return;
        }

        Log::info('STEP 3: Email feature enabled');

        try {

            /**
             * ============================
             * SEND EMAIL TO CUSTOMER
             * ============================
             */
            Log::info('STEP 4: Checking send_to_customer config', [
                'enabled' => config('appointment.email.send_to_customer'),
            ]);

            if (config('appointment.email.send_to_customer')) {

                if (empty($appointment->customer_email)) {
                    Log::warning('STEP 4.1: Customer email is empty – skip sending');
                } else {
                    Log::info('STEP 4.2: Generating email token for customer');

                    $emailLog = $appointment->generateEmailToken(
                        AppointmentEmailLog::EMAIL_TYPE_CREATED,
                        $appointment->customer_email,
                        'customer'
                    );

                    Log::info('STEP 4.3: Email token generated', [
                        'email_log_id' => $emailLog->id,
                        'email' => $appointment->customer_email,
                    ]);

                    Log::info('STEP 4.4: Sending email to customer');

                    Mail::to($appointment->customer_email)
                        ->send(new AppointmentCreatedMail($appointment, $emailLog));

                    Log::info('STEP 4.5: Email SENT to customer ✅', [
                        'appointment_id' => $appointment->id,
                        'email' => $appointment->customer_email,
                    ]);
                }
            }

            /**
             * ============================
             * SEND EMAIL TO ASSIGNED USER
             * ============================
             */
            Log::info('STEP 5: Checking send_to_assigned_user config', [
                'enabled' => config('appointment.email.send_to_assigned_user'),
            ]);

           // Trong phần gửi mail cho assignedUser
            if (config('appointment.email.send_to_assigned_user')) {

                if (!$appointment->relationLoaded('assignedUser')) {
                    $appointment->load('assignedUser');
                }

                if ($appointment->assignedUser && !empty($appointment->assignedUser->email)) {

                    $emailLog = $appointment->generateEmailToken(
                        AppointmentEmailLog::EMAIL_TYPE_CREATED,
                        $appointment->assignedUser->email,
                        'assigned_user'
                    );

                    // ✅ Dùng view riêng cho nhân viên
                    $staffView = 'appointment::admin.appointments.staff-emails.created';

                    Mail::to($appointment->assignedUser->email)
                        ->send(new AppointmentCreatedMail($appointment, $emailLog, $staffView));

                    Log::info('STEP 5.7: Email SENT to assigned user ✅', [
                        'appointment_id' => $appointment->id,
                        'email' => $appointment->assignedUser->email,
                        'email_log_id' => $emailLog->id
                    ]);
                }
            }


            Log::info('STEP 6: SendAppointmentCreatedNotification finished successfully ✅');

        } catch (\Throwable $e) {
            Log::error('STEP ERROR: Failed to send appointment created email ❌', [
                'appointment_id' => $appointment->id ?? null,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }
}
