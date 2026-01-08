<?php

namespace Webkul\Appointment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class AppointmentEmailLog extends Model
{
    protected $table = 'appointment_email_logs';

    const EMAIL_TYPE_CREATED = 'created';
    const EMAIL_TYPE_UPDATED = 'updated';
    const EMAIL_TYPE_CANCELLED = 'cancelled';
    const EMAIL_TYPE_CONFIRMED = 'confirmed';
    const EMAIL_TYPE_RESCHEDULED = 'rescheduled';
    const EMAIL_TYPE_REMINDER = 'reminder';

    protected $fillable = [
        'appointment_id',
        'email_type',
        'recipient_email',
        'recipient_type',
        'token',
        'token_expires_at',
        'sent_at',
        'opened_at',
        'clicked_at',
        'action_taken_at',
        'action_taken',
        'hours_before',
        'metadata',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'opened_at' => 'datetime',
        'clicked_at' => 'datetime',
        'action_taken_at' => 'datetime',
        'token_expires_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Relationship to Appointment
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Generate secure token
     */
    public static function generateToken(): string
    {
        return hash_hmac('sha256', uniqid() . time() . rand(), config('app.key'));
    }

    /**
     * Validate token
     */
    public function validateToken(string $token): bool
    {
        if ($this->token !== $token) {
            return false;
        }

        // Check if token expired
        if ($this->token_expires_at && $this->token_expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Check if email was opened
     */
    public function wasOpened(): bool
    {
        return !is_null($this->opened_at);
    }

    /**
     * Check if email was clicked
     */
    public function wasClicked(): bool
    {
        return !is_null($this->clicked_at);
    }

    /**
     * Check if action was taken
     */
    public function actionWasTaken(): bool
    {
        return !is_null($this->action_taken_at);
    }

    /**
     * Mark as opened
     */
    public function markAsOpened(): void
    {
        if (!$this->wasOpened()) {
            $this->opened_at = now();
            $this->save();
        }
    }

    /**
     * Mark as clicked
     */
    public function markAsClicked(): void
    {
        if (!$this->wasClicked()) {
            $this->clicked_at = now();
            $this->save();
        }
    }

    /**
     * Record action taken
     */
    public function recordAction(string $action): void
    {
        $this->action_taken = $action;
        $this->action_taken_at = now();
        $this->save();
    }

    /**
     * Scope: Only sent emails
     */
    public function scopeSent($query)
    {
        return $query->whereNotNull('sent_at');
    }

    /**
     * Scope: Only opened emails
     */
    public function scopeOpened($query)
    {
        return $query->whereNotNull('opened_at');
    }

    /**
     * Scope: By email type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('email_type', $type);
    }

    /**
     * Scope: For specific appointment
     */
    public function scopeForAppointment($query, int $appointmentId)
    {
        return $query->where('appointment_id', $appointmentId);
    }
}
