<?php

namespace Webkul\EmailExtended\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\EmailExtended\Contracts\EmailScheduled as EmailScheduledContract;

class EmailScheduled extends Model implements EmailScheduledContract
{
    protected $table = 'email_scheduled';

    protected $fillable = [
        'email_id',
        'scheduled_at',
        'status',
        'attempts',
        'max_attempts',
        'last_attempt_at',
        'error_message',
        'metadata',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'last_attempt_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Lấy email sở hữu lịch gửi này
     */
    public function email()
    {
        return $this->belongsTo(\Webkul\Email\Models\EmailProxy::modelClass(), 'email_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeDue($query)
    {
        return $query->where('status', 'pending')
            ->where('scheduled_at', '<=', now());
    }

    public function scopeRetryable($query)
    {
        return $query->where('status', 'failed')
            ->whereColumn('attempts', '<', 'max_attempts');
    }

    /**
     * Đánh dấu là đang xử lý
     */
    public function markAsProcessing(): bool
    {
        return $this->update([
            'status' => 'processing',
            'last_attempt_at' => now(),
        ]);
    }

    /**
     * Đánh dấu là đã gửi
     */
    public function markAsSent(): bool
    {
        return $this->update([
            'status' => 'sent',
            'last_attempt_at' => now(),
        ]);
    }

    /**
     * Đánh dấu là thất bại
     */
    public function markAsFailed(string $error): bool
    {
        $this->increment('attempts');
        
        return $this->update([
            'status' => ($this->attempts >= $this->max_attempts) ? 'failed' : 'pending',
            'error_message' => $error,
            'last_attempt_at' => now(),
        ]);
    }

    /**
     * Hủy email đã lên lịch
     */
    public function cancel(): bool
    {
        return $this->update([
            'status' => 'cancelled',
        ]);
    }

    /**
     * Đặt lại để thử gửi lại
     */
    public function resetForRetry(): bool
    {
        return $this->update([
            'status' => 'pending',
            'attempts' => 0,
            'error_message' => null,
        ]);
    }

    /**
     * Kiểm tra xem có thể thử gửi lại không
     */
    public function canRetry(): bool
    {
        return $this->attempts < $this->max_attempts && $this->status !== 'cancelled';
    }

    /**
     * Kiểm tra xem đã đến thời gian gửi chưa
     */
    public function isDue(): bool
    {
        return $this->status === 'pending' && $this->scheduled_at <= now();
    }

    /**
     * Kiểm tra xem đã bị hủy chưa
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Lấy số lần thử còn lại
     */
    public function getRemainingAttempts(): int
    {
        return max(0, $this->max_attempts - $this->attempts);
    }

    /**
     * Lấy thời gian còn lại đến lúc gửi
     */
    public function getTimeUntilScheduled(): string
    {
        if ($this->scheduled_at <= now()) {
            return 'Now';
        }

        return $this->scheduled_at->diffForHumans();
    }

    /**
     * Lấy nhãn trạng thái
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'Chưa gửi',
            'processing' => 'Đang xử lý',
            'sent' => 'Đã gửi',
            'cancelled' => 'Đã hủy',
            'failed' => 'Thất bại',
            default => ucfirst($this->status),
        };
    }

    /**
     * Lấy màu hiển thị trạng thái
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            'pending' => 'blue',
            'processing' => 'yellow',
            'sent' => 'green',
            'cancelled' => 'gray',
            'failed' => 'red',
            default => 'gray',
        };
    }
}
