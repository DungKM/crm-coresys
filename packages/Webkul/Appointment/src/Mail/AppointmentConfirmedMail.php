<?php

namespace Webkul\Appointment\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Webkul\Appointment\Models\Appointment;
use Webkul\Appointment\Models\AppointmentEmailLog;

class AppointmentConfirmedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $emailLog;
    public $icsUrl;
    public $cancelUrl;
    public $viewFile; // ✅ Thêm view tùy chọn

    public function __construct(Appointment $appointment, AppointmentEmailLog $emailLog, $viewFile = null)
    {
        $this->appointment = $appointment;
        $this->emailLog = $emailLog;
        $this->viewFile = $viewFile;

        $token = $emailLog->token;

        $this->icsUrl = route('appointment.public.ics', [
            'id' => $appointment->id,
            'token' => $token
        ]);

        $this->cancelUrl = route('appointment.public.cancel', [
            'id' => $appointment->id,
            'token' => $token
        ]);
    }

    public function build()
    {
        return $this->from(
                    config('appointment.email.from.address'),
                    config('appointment.email.from.name')
                )
                ->subject('Lịch hẹn đã được xác nhận - ' . $this->appointment->customer_name)
                ->view($this->viewFile ?? 'appointment::emails.confirmed') // ✅ nếu có view truyền vào thì dùng view đó
                ->with([
                    'appointment' => $this->appointment,
                    'icsUrl' => $this->icsUrl,
                    'cancelUrl' => $this->cancelUrl,
                    'company' => config('appointment.company'),
                    'colors' => config('appointment.colors'),
                ]);
    }
}
