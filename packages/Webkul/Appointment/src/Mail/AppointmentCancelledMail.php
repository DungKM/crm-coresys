<?php

namespace Webkul\Appointment\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Webkul\Appointment\Models\Appointment;

class AppointmentCancelledMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $reason;
    public $cancelledBy;
    public $viewFile;

    /**
     * @param Appointment $appointment
     * @param string|null $reason
     * @param mixed|null $cancelledBy
     * @param string|null $viewFile
     */
    public function __construct(Appointment $appointment, $reason = null, $cancelledBy = null, $viewFile = null)
    {
        $this->appointment = $appointment;
        $this->reason = $reason;
        $this->cancelledBy = $cancelledBy;
        $this->viewFile = $viewFile;
    }

    public function build()
    {
        return $this->from(
                    config('appointment.email.from.address'),
                    config('appointment.email.from.name')
                )
                ->subject('Lịch hẹn đã bị hủy - ' . $this->appointment->customer_name)
                ->view($this->viewFile ?? 'appointment::emails.cancelled')
                ->with([
                    'appointment' => $this->appointment,
                    'reason' => $this->reason,
                    'cancelledBy' => $this->cancelledBy,
                    'company' => config('appointment.company'),
                    'colors' => config('appointment.colors'),
                ]);
    }
}
