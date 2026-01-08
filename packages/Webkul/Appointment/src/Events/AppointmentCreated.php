<?php

namespace Webkul\Appointment\Events;

use Webkul\Appointment\Models\Appointment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AppointmentCreated
{
    use Dispatchable, SerializesModels;

    public Appointment $appointment;
    public string $createdBy;

    public function __construct(
        Appointment $appointment,
        string $createdBy = 'system'
    ) {
        $this->appointment = $appointment;
        $this->createdBy   = $createdBy;

        // 🔥 Log tại đây
        Log::info('AppointmentCreated event fired', [
            'appointment_id' => $appointment->id ?? null,
            'created_by'     => $createdBy,
            'start_at'       => $appointment->start_at ?? null,
            'meeting_type'   => $appointment->meeting_type ?? null,
        ]);
    }
}
