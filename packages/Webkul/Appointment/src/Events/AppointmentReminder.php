<?php

namespace Webkul\Appointment\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Webkul\Appointment\Models\Appointment;

class AppointmentReminder
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $appointment;
    public $minutesUntil;

    /**
     * Create a new event instance.
     *
     * @param Appointment $appointment
     * @param int $minutesUntil Số phút còn lại đến cuộc hẹn
     */
    public function __construct(Appointment $appointment, int $minutesUntil)
    {
        $this->appointment = $appointment;
        $this->minutesUntil = $minutesUntil;
    }
}
