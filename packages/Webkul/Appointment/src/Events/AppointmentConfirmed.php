<?php

namespace Webkul\Appointment\Events;

use Webkul\Appointment\Models\Appointment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppointmentConfirmed
{
    use Dispatchable, SerializesModels;

    public $appointment;
    public $confirmedBy;

    public function __construct(Appointment $appointment, string $confirmedBy = 'email')
    {
        $this->appointment = $appointment;
        $this->confirmedBy = $confirmedBy;
    }
}
