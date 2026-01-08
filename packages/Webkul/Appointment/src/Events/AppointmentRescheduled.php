<?php

namespace Webkul\Appointment\Events;

use Webkul\Appointment\Models\Appointment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppointmentRescheduled
{
    use Dispatchable, SerializesModels;

    public $appointment;
    public $oldStartAt;
    public $oldEndAt;
    public $reason;

    public function __construct(Appointment $appointment, $oldStartAt, $oldEndAt, $reason = null)
    {
        $this->appointment = $appointment;
        $this->oldStartAt = $oldStartAt;
        $this->oldEndAt = $oldEndAt;
        $this->reason = $reason;
    }
}
