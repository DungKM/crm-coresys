<?php

namespace Webkul\Appointment\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log; // ✅ cần log
use Webkul\Appointment\Models\Appointment;
use Webkul\Appointment\Models\AppointmentEmailLog;

class AppointmentReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $emailLog;
    public $minutesUntil;
    public $confirmUrl;
    public $cancelUrl;
    public $icsUrl;
    protected $viewFile;

    public function __construct(
        Appointment $appointment,
        AppointmentEmailLog $emailLog,
        int $minutesUntil,
        ?string $viewFile = null
    ) {
        $this->appointment = $appointment;
        $this->emailLog = $emailLog;
        $this->minutesUntil = $minutesUntil;
        $this->viewFile = $viewFile;

        $token = $emailLog->token;

        $this->confirmUrl = route('appointment.public.confirm', [
            'id' => $appointment->id,
            'token' => $token,
        ]);

        $this->cancelUrl = route('appointment.public.cancel', [
            'id' => $appointment->id,
            'token' => $token,
        ]);

        $this->icsUrl = route('appointment.public.ics', [
            'id' => $appointment->id,
            'token' => $token,
        ]);

        // 🔹 Log debug constructor
        Log::info('MAIL __construct(): AppointmentReminderMail instantiated', [
            'appointment_id' => $appointment->id,
            'customer_name' => $appointment->customer_name,
            'email' => $emailLog->email,
            'minutes_until' => $minutesUntil,
            'viewFile' => $viewFile,
            'confirmUrl' => $this->confirmUrl,
            'cancelUrl' => $this->cancelUrl,
            'icsUrl' => $this->icsUrl,
        ]);
    }

    protected function buildTimeText(): string
    {
        $minutes = $this->minutesUntil;

        if ($minutes < 60) {
            return $minutes . ' phút';
        }

        if ($minutes < 1440) {
            $hours = intdiv($minutes, 60);
            $remainingMinutes = $minutes % 60;
            return $remainingMinutes > 0 ? "$hours giờ $remainingMinutes phút" : "$hours giờ";
        }

        $days = intdiv($minutes, 1440);
        $remainingHours = intdiv($minutes % 1440, 60);
        return $remainingHours > 0 ? "$days ngày $remainingHours giờ" : "$days ngày";
    }

    public function build()
    {
        $timeText = $this->buildTimeText();

        // 🔹 Log trước khi build
        Log::info('MAIL build(): Building AppointmentReminderMail', [
            'appointment_id' => $this->appointment->id,
            'email' => $this->emailLog->email,
            'minutes_until' => $this->minutesUntil,
            'timeText' => $timeText,
            'viewFile' => $this->viewFile ?? 'appointment::emails.reminder',
        ]);

        return $this->from(
                config('appointment.email.from.address', 'no-reply@example.com'),
                config('appointment.email.from.name', 'Company')
            )
            ->subject('Nhắc nhở: Lịch hẹn sau ' . $timeText)
            ->view($this->viewFile ?? 'appointment::emails.reminder')
            ->with([
                'appointment' => $this->appointment,
                'minutesUntil' => $this->minutesUntil,
                'timeText' => $timeText,
                'confirmUrl' => $this->confirmUrl,
                'cancelUrl' => $this->cancelUrl,
                'icsUrl' => $this->icsUrl,
                'company' => config('appointment.company'),
                'colors' => config('appointment.colors'),
            ]);
    }
}
