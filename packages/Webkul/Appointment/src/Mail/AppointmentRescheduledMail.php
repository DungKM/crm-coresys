<?php

namespace Webkul\Appointment\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Webkul\Appointment\Models\Appointment;
use Webkul\Appointment\Models\AppointmentEmailLog;

class AppointmentRescheduledMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $emailLog;
    public $oldStartAt;
    public $oldEndAt;
    public $confirmUrl;
    public $cancelUrl;
    public $icsUrl;
    public $viewFile; // ✅ Thêm biến view tùy chọn

    public function __construct(
        Appointment $appointment,
        AppointmentEmailLog $emailLog,
        $oldStartAt,
        $oldEndAt,
        $viewFile = null // ✅ Nhận view tùy biến
    ) {
        $this->appointment = $appointment;
        $this->emailLog    = $emailLog;
        $this->oldStartAt  = $oldStartAt;
        $this->oldEndAt    = $oldEndAt;
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
        $company = config('appointment.company');

        return $this->from(
                    config('appointment.email.from.address'),
                    config('appointment.email.from.name')
                )
                ->subject('Lịch hẹn đã được đổi giờ - ' . $this->appointment->customer_name)
                ->view($this->viewFile ?? 'appointment::emails.rescheduled') // ✅ view riêng cho nhân viên nếu có
                ->with([
                    'appointment' => $this->appointment,
                    'oldStartAt' => $this->oldStartAt,
                    'oldEndAt' => $this->oldEndAt,
                    'confirmUrl' => $this->confirmUrl,
                    'cancelUrl' => $this->cancelUrl,
                    'icsUrl' => $this->icsUrl,
                    'company' => $company,
                    'colors' => config('appointment.colors'),
                ]);
    }
}
