<?php

namespace Webkul\Appointment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Lead\Models\Lead;
use Webkul\User\Models\User;
use Webkul\Appointment\Models\AppointmentEmailLog;
use Webkul\Appointment\Contracts\Appointment as AppointmentContract;
use Webkul\Contact\Models\Organization;
use Webkul\Appointment\Models\Service;

class Appointment extends Model implements AppointmentContract
{
    use SoftDeletes;

    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_RESCHEDULED = 'rescheduled';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_SHOWED = 'showed';
    const STATUS_NO_SHOW = 'no_show';

    protected $fillable = [
        'lead_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'source',
        'requested_at',
        'start_at',
        'end_at',
        'timezone',
        'duration_minutes',
        'meeting_type',
        'call_phone',
        'meeting_link',
        'province',
        'district',
        'ward',
        'street_address',
        'service_id',
        'service_name',
        'assignment_type',
        'assigned_user_id',
        'routing_key',
        'resource_id',
        'organization_id',
        'channel',
        'status',
        'note',
        'external_source',
        'external_id',
        'utm_params',
        'created_by',
        'original_start_at',
        'rescheduled_by',
        'rescheduled_at',
        'reschedule_reason',
        'cancellation_reason',
        'cancelled_by',
        'cancelled_at',
        'reminder_sent_at',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'original_start_at' => 'datetime',
        'rescheduled_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'utm_params' => 'array',
        'reminder_sent_at' => 'array',
    ];

    /**
     * ========================================
     * RELATIONSHIPS
     * ========================================
     */

    /**
     * Get the lead associated with the appointment
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function service()
    {
        return $this->belongsTo(\Webkul\Appointment\Models\Service::class, 'service_id');
    }
    /**
     * Get the assigned user (staff)
     *
     * ✅ FIX: Đảm bảo return User model, không phải stdClass
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    /**
     * Get the user who created this appointment
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who rescheduled this appointment
     */
    public function rescheduler()
    {
        return $this->belongsTo(User::class, 'rescheduled_by');
    }

    /**
     * Get the user who cancelled this appointment
     */
    public function canceller()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    /**
     * Get all email logs for this appointment
     */
    public function emailLogs()
    {
        return $this->hasMany(AppointmentEmailLog::class, 'appointment_id');
    }

    /**
     * ========================================
     * HELPER METHODS
     * ========================================
     */

    /**
     * Check if appointment can be edited
     */
    public function canEdit(): bool
    {
        return !in_array($this->status, [
            self::STATUS_CANCELLED,
            self::STATUS_SHOWED,
            self::STATUS_NO_SHOW
        ]);
    }

    /**
     * Check if appointment can be cancelled
     */
    public function canCancel(): bool
    {
        return !in_array($this->status, [
            self::STATUS_CANCELLED,
            self::STATUS_SHOWED,
            self::STATUS_NO_SHOW
        ]);
    }

    /**
     * Check if appointment status can be changed
     */
    public function canChangeStatus(): bool
    {
        return $this->status !== self::STATUS_CANCELLED;
    }

    /**
     * Get available statuses that this appointment can transition to
     */
    public function getAvailableStatuses(): array
    {
        $statuses = [
            self::STATUS_SCHEDULED => 'Chờ xử lý',
            self::STATUS_CONFIRMED => 'Đã xác nhận',
            self::STATUS_RESCHEDULED => 'Đã đổi lịch',
            self::STATUS_CANCELLED => 'Đã hủy',
            self::STATUS_SHOWED => 'Đã đến',
            self::STATUS_NO_SHOW => 'Không đến',
        ];

        // Remove current status and cancelled if already cancelled
        unset($statuses[$this->status]);

        if ($this->status === self::STATUS_CANCELLED) {
            return [];
        }

        return $statuses;
    }

    /**
     * Get full address for onsite meetings
     */
    public function getFullAddressAttribute(): ?string
    {
        if ($this->meeting_type !== 'onsite') {
            return null;
        }

        $parts = array_filter([
            $this->street_address,
            $this->ward,
            $this->district,
            $this->province,
        ]);

        return implode(', ', $parts);
    }

    /**
     * ========================================
     * EMAIL TOKEN GENERATION
     * ========================================
     */

    /**
     * Generate email token and create email log
     *
     * @param string $emailType
     * @param string $recipientEmail
     * @param string $recipientType (customer|assigned_user)
     * @param array $metadata
     * @param int|null $hoursUntil (for reminder emails)
     * @return AppointmentEmailLog
     */
    public function generateEmailToken(
        string $emailType,
        string $recipientEmail,
        string $recipientType = 'customer',
        array $metadata = [],
        ?int $hoursUntil = null
    ): AppointmentEmailLog {
        $token = AppointmentEmailLog::generateToken();

        $fullMetadata = array_merge($metadata, [
            'recipient_type' => $recipientType,
            'generated_at' => now()->toDateTimeString(),
        ]);

        if ($hoursUntil !== null) {
            $fullMetadata['hours_until'] = $hoursUntil;
        }

        $emailLog = AppointmentEmailLog::create([
            'appointment_id' => $this->id,
            'email_type' => $emailType,
            'recipient_email' => $recipientEmail,
            'token' => $token,
            'metadata' => $fullMetadata,
            'sent_at' => now(),
            'status' => 'sent',
        ]);

        return $emailLog;
    }

    /**
     * Get latest email log by type
     */
    public function getLatestEmailLog(string $emailType)
    {
        return $this->emailLogs()
            ->where('email_type', $emailType)
            ->latest()
            ->first();
    }

    /**
     * ========================================
     * SAFE GETTERS (Prevent stdClass errors)
     * ========================================
     */

    /**
     * ✅ Safe getter for assigned user email
     */
    public function getAssignedUserEmailAttribute(): ?string
    {
        // Force load relationship if not loaded
        if (!$this->relationLoaded('assignedUser')) {
            $this->load('assignedUser');
        }

        // Check if assigned user exists and is a User model
        if ($this->assignedUser && $this->assignedUser instanceof User) {
            return $this->assignedUser->email;
        }

        return null;
    }

    /**
     * ✅ Safe getter for assigned user name
     */
    public function getAssignedUserNameAttribute(): ?string
    {
        if (!$this->relationLoaded('assignedUser')) {
            $this->load('assignedUser');
        }

        if ($this->assignedUser && $this->assignedUser instanceof User) {
            return $this->assignedUser->name;
        }

        return null;
    }
}
