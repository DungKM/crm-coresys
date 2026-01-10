<?php

namespace Webkul\Appointment\Console\Commands;

use Illuminate\Console\Command;
use Webkul\Appointment\Models\Appointment;
use Webkul\Appointment\Events\AppointmentReminder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointment:send-reminders';
    protected $description = 'Send reminder emails for upcoming appointments';

    protected $reminderIntervals = [
        10080, // 1 tuần (phút)
        1440,  // 1 ngày
        60,    // 1 giờ
        5,     // 5 phút
    ];

    public function handle()
    {
        $now = Carbon::now();

        Log::info('[REMINDER] Command started', [
            'now' => $now->toDateTimeString(),
            'timezone' => config('app.timezone'),
        ]);

        $this->info('Checking for appointments requiring reminders...');
        $this->info('System time: ' . $now->toDateTimeString() . ' | TZ: ' . config('app.timezone'));

        $totalSent = 0;

        foreach ($this->reminderIntervals as $minutes) {
            Log::info('[REMINDER] Processing interval', [
                'minutes' => $minutes,
            ]);

            $sent = $this->sendRemindersForInterval($minutes);
            $totalSent += $sent;

            $label = $this->getIntervalLabel($minutes);
            $this->info("Sent {$sent} reminders for {$label} interval");
        }

        Log::info('[REMINDER] Command finished', [
            'total_sent' => $totalSent,
        ]);

        $this->info("Total reminders sent: {$totalSent}");

        return 0;
    }

    protected function sendRemindersForInterval(int $minutes): int
    {
        $now = Carbon::now();

        $targetTime = $now->copy()->addMinutes($minutes);
        $startRange = $targetTime->copy()->subMinutes(2);
        $endRange   = $targetTime->copy()->addMinutes(2);

        Log::info('[REMINDER] Time window calculated', [
            'interval_minutes' => $minutes,
            'now' => $now->toDateTimeString(),
            'target_time' => $targetTime->toDateTimeString(),
            'range_start' => $startRange->toDateTimeString(),
            'range_end' => $endRange->toDateTimeString(),
        ]);

        $appointments = Appointment::query()
            ->whereIn('status', ['scheduled', 'confirmed', 'rescheduled'])
            ->whereBetween('start_at', [$startRange, $endRange])
            ->whereNotNull('customer_email')
            ->get();

        Log::info('[REMINDER] Appointments fetched from DB', [
            'interval_minutes' => $minutes,
            'count' => $appointments->count(),
        ]);

        $appointments = $appointments->filter(function ($appointment) use ($minutes) {
            return !$this->hasReminderBeenSent($appointment, $minutes);
        });

        $sentCount = 0;

        foreach ($appointments as $appointment) {
            Log::info('[REMINDER] Preparing to send reminder', [
                'appointment_id' => $appointment->id,
                'start_at' => optional($appointment->start_at)->toDateTimeString(),
                'email' => $appointment->customer_email,
                'interval_minutes' => $minutes,
            ]);

            event(new AppointmentReminder($appointment, $minutes));
            $sentCount++;
        }

        return $sentCount;
    }

    protected function hasReminderBeenSent(Appointment $appointment, int $minutes): bool
    {
        $remindersSent = $appointment->reminder_sent_at ?? [];

        if (!is_array($remindersSent)) {
            return false;
        }

        foreach ($remindersSent as $reminder) {
            if (
                isset($reminder['minutes']) &&
                $reminder['minutes'] === $minutes
            ) {
                return true;
            }
        }

        return false;
    }
    protected function getIntervalLabel(int $minutes): string
    {
        if ($minutes >= 10080) return '1 tuần';
        if ($minutes >= 1440)  return '1 ngày';
        if ($minutes >= 60)    return '1 giờ';
        return '5 phút';
    }
}
