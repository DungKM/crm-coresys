<?php

namespace Webkul\Appointment\Events;

use Webkul\Appointment\Models\Appointment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppointmentReminder
{
    use Dispatchable, SerializesModels;

    public Appointment $appointment;
    public int $hoursUntil;

    public function __construct(Appointment $appointment, int $hoursUntil)
    {
        $this->appointment = $appointment;
        $this->hoursUntil  = $hoursUntil;
    }
}
