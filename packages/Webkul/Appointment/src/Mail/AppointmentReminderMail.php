<?php

namespace Webkul\Appointment\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Webkul\Appointment\Models\Appointment;
use Webkul\Appointment\Models\AppointmentEmailLog;

class AppointmentReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $emailLog;
    public $hoursUntil;
    public $confirmUrl;
    public $cancelUrl;
    public $icsUrl;
    public $viewFile; // ✅ thêm view tùy chọn

    public function __construct(
        Appointment $appointment,
        AppointmentEmailLog $emailLog,
        int $hoursUntil,
        ?string $viewFile = null // ✅ nhận view riêng nếu muốn
    ) {
        $this->appointment = $appointment;
        $this->emailLog    = $emailLog;
        $this->hoursUntil  = $hoursUntil;
        $this->viewFile    = $viewFile;

        $token = $emailLog->token;

        $this->confirmUrl = route('appointment.public.confirm', [
            'id' => $appointment->id,
            'token' => $token
        ]);

        $this->cancelUrl = route('appointment.public.cancel', [
            'id' => $appointment->id,
            'token' => $token
        ]);

        $this->icsUrl = route('appointment.public.ics', [
            'id' => $appointment->id,
            'token' => $token
        ]);
    }

    public function build()
    {
        $timeText = $this->hoursUntil >= 24
            ? round($this->hoursUntil / 24) . ' ngày'
            : $this->hoursUntil . ' giờ';

        $company = config('appointment.company');

        return $this->from(
                    config('appointment.email.from.address'),
                    config('appointment.email.from.name')
                )
                ->subject('Nhắc nhở: Lịch hẹn sau ' . $timeText)
                ->view($this->viewFile ?? 'appointment::emails.reminder') // ✅ dùng view riêng nếu có
                ->with([
                    'appointment' => $this->appointment,
                    'hoursUntil' => $this->hoursUntil,
                    'timeText' => $timeText,
                    'confirmUrl' => $this->confirmUrl,
                    'cancelUrl' => $this->cancelUrl,
                    'icsUrl' => $this->icsUrl,
                    'company' => $company,
                    'colors' => config('appointment.colors'),
                ]);
    }
}
