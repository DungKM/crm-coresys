<?php

namespace Webkul\Appointment\Events;

use Webkul\Appointment\Models\Appointment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppointmentCancelled
{
    use Dispatchable, SerializesModels;

    /**
     * @var Appointment
     */
    public $appointment;

    /**
     * @var string|null
     * Lý do huỷ lịch hẹn
     */
    public $reason;

    /**
     * @var string
     * Ai huỷ: 'customer', 'admin', 'system'
     */
    public $cancelledBy;

    /**
     * Create a new event instance.
     */
    public function __construct(
        Appointment $appointment,
        ?string $reason = null,
        string $cancelledBy = 'customer'
    ) {
        $this->appointment  = $appointment;
        $this->reason       = $reason;
        $this->cancelledBy  = $cancelledBy;
    }
}
