<?php

namespace Webkul\Appointment\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Webkul\Appointment\Models\Appointment;
use Webkul\Appointment\Models\AppointmentEmailLog;

class AppointmentCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $emailLog;
    public $confirmUrl;
    public $cancelUrl;
    public $rescheduleUrl;
    public $icsUrl;
    public $viewFile; // ✅ Thêm biến view tùy chọn

    public function __construct(
        Appointment $appointment,
        AppointmentEmailLog $emailLog,
        $viewFile = null // ✅ Nhận view tùy biến
    ) {
        $this->appointment = $appointment;
        $this->emailLog    = $emailLog;
        $this->viewFile    = $viewFile;

        Log::info('MAIL __construct(): AppointmentCreatedMail instantiated', [
            'appointment_id' => $appointment->id ?? null,
            'email_log_id'   => $emailLog->id ?? null,
            'email'          => $emailLog->email ?? null,
        ]);
    }

    public function build()
    {
        $this->confirmUrl    = route('appointment.public.confirm', [
            'id'    => $this->appointment->id,
            'token' => $this->emailLog->token,
        ]);

        $this->cancelUrl     = route('appointment.public.cancel', [
            'id'    => $this->appointment->id,
            'token' => $this->emailLog->token,
        ]);

        $this->rescheduleUrl = route('appointment.public.reschedule', [
            'id'    => $this->appointment->id,
            'token' => $this->emailLog->token,
        ]);

        $this->icsUrl        = route('appointment.public.ics', [
            'id'    => $this->appointment->id,
            'token' => $this->emailLog->token,
        ]);

        $company = config('appointment.company');

        return $this->from(config('appointment.email.from'), config('appointment.email.name'))
                    ->subject("Xác nhận lịch hẹn - {$this->appointment->customer_name}")
                    ->view($this->viewFile ?? 'appointment::emails.created') // ✅ nếu có view truyền vào thì dùng view đó
                    ->with([
                        'appointment'  => $this->appointment,
                        'company'      => $company,
                        'confirmUrl'   => $this->confirmUrl,
                        'cancelUrl'    => $this->cancelUrl,
                        'rescheduleUrl'=> $this->rescheduleUrl,
                        'icsUrl'       => $this->icsUrl,
                    ]);
    }
}
