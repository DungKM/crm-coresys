<?php

namespace Webkul\Appointment\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Appointment\Contracts\AppointmentStatusHistory as AppointmentStatusHistoryContract;

class AppointmentStatusHistory extends Model implements AppointmentStatusHistoryContract
{
    protected $fillable = [
        'appointment_id',
        'event_type',
        'old_status',
        'new_status',
        'changes',
        'reason',
        'actor_id',
        'actor_name',
        'actor_type',
        'customer_name',
        'customer_email',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
