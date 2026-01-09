<?php

namespace Webkul\Appointment\Events;

use Webkul\Appointment\Models\Appointment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppointmentUpdated
{
    use Dispatchable, SerializesModels;

    public $appointment;
    public $changes;

    /**
     * Create a new event instance.
     *
     * @param Appointment $appointment
     * @param array $changes
     */
    public function __construct(Appointment $appointment, array $changes = [])
    {
        $this->appointment = $appointment;
        $this->changes = $changes;
    }
}
