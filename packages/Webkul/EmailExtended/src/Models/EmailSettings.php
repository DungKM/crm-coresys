<?php

namespace Webkul\EmailExtended\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailSettings extends Model
{
    protected $fillable = [
        'user_id',
        'sendgrid_api_key',
        'from_email',
        'from_name',
        'gmail_address',
        'gmail_app_password',
        'signature',
        'merge_tags',
        'sendgrid_verified',
        'gmail_verified',
        'sendgrid_verified_at',
        'gmail_verified_at',
        'is_active',
        'emails_sent_count',
        'last_email_sent_at',
        'webhook_enabled',
        'webhook_signing_key',
        'webhook_events',
        'webhook_verified_at',
    ];

    protected $casts = [
        'sendgrid_api_key' => 'encrypted',
        'gmail_app_password' => 'encrypted',
        'merge_tags' => 'array',
        'sendgrid_verified' => 'boolean',
        'gmail_verified' => 'boolean',
        'is_active' => 'boolean',
        'sendgrid_verified_at' => 'datetime',
        'gmail_verified_at' => 'datetime',
        'last_email_sent_at' => 'datetime',
        'webhook_enabled' => 'boolean',
        'webhook_signing_key' => 'encrypted',  
        'webhook_events' => 'array',         
        'webhook_verified_at' => 'datetime',
    ];

    /**
     * Relationship với User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\Webkul\User\Models\User::class);
    }

    /**
     * Lấy settings của user hiện tại
     */
    public static function getCurrentUserSettings(): ?self
    {
        $userId = auth()->guard('user')->id();
        
        if (!$userId) {
            return null;
        }
        
        return static::where('user_id', $userId)
                     ->where('is_active', true)
                     ->first();
    }

    /**
     * Check xem SendGrid đã config chưa
     */
    public function hasSendgridConfigured(): bool
    {
        return !empty($this->sendgrid_api_key) 
            && !empty($this->from_email) 
            && $this->sendgrid_verified;
    }

    /**
     * Check xem Gmail đã config chưa
     */
    public function hasGmailConfigured(): bool
    {
        return !empty($this->gmail_address) 
            && !empty($this->gmail_app_password) 
            && $this->gmail_verified;
    }

    /**
     * Tăng số lượng email đã gửi
     */
    public function incrementEmailsSent(): void
    {
        $this->increment('emails_sent_count');
        $this->update(['last_email_sent_at' => now()]);
    }

    /**
     * Check xem webhook đã được config đầy đủ chưa
     */
    public function isWebhookConfigured(): bool
    {
        return $this->webhook_enabled 
               && !empty($this->webhook_events)
               && $this->webhook_verified_at !== null;
    }

    /**
     * Check xem có đang track event này không
     * 
     * @param string $event Tên event: 'open', 'click', 'delivered', etc.
     * @return bool
     */
    public function isTrackingEvent(string $event): bool
    {
        if (!$this->webhook_enabled || empty($this->webhook_events)) {
            return false;
        }

        return in_array($event, $this->webhook_events);
    }

    /**
     * Lấy danh sách tất cả webhook events có thể track
     * 
     * @return array
     */
    public static function getAvailableWebhookEvents(): array
    {
        return [
            'processed' => 'Processed - Message has been received',
            'delivered' => 'Delivered - Message has been delivered',
            'open' => 'Open - Recipient opened the email',
            'click' => 'Click - Recipient clicked a link',
            'bounce' => 'Bounce - Email bounced',
            'dropped' => 'Dropped - Email was dropped',
            'spamreport' => 'Spam Report - Marked as spam',
            'unsubscribe' => 'Unsubscribe - Recipient unsubscribed',
        ];
    }

    /**
     * Lấy status của webhook để hiển thị trên UI
     * 
     * @return string 'disabled' | 'not_verified' | 'active' | 'needs_verification'
     */
    public function getWebhookStatusAttribute(): string
    {
        if (!$this->webhook_enabled) {
            return 'disabled';
        }

        if ($this->webhook_verified_at === null) {
            return 'not_verified';
        }

        // Check nếu verify trong vòng 7 ngày gần đây
        $isRecent = $this->webhook_verified_at->isAfter(now()->subDays(7));
        
        return $isRecent ? 'active' : 'needs_verification';
    }

    /**
     * Lấy badge color cho webhook status
     * 
     * @return string CSS class
     */
    public function getWebhookBadgeColorAttribute(): string
    {
        return match($this->webhook_status) {
            'active' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
            'not_verified' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
            'needs_verification' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300',
            'disabled' => 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Lấy webhook events đã chọn dưới dạng human-readable
     * 
     * @return array
     */
    public function getSelectedWebhookEventsAttribute(): array
    {
        if (empty($this->webhook_events)) {
            return [];
        }

        $available = self::getAvailableWebhookEvents();
        $selected = [];

        foreach ($this->webhook_events as $event) {
            $selected[$event] = $available[$event] ?? ucfirst($event);
        }

        return $selected;
    }

    /**
     * Check xem có cần verify lại webhook không
     * (Nếu đã quá 30 ngày kể từ lần verify cuối)
     * 
     * @return bool
     */
    public function needsWebhookReverification(): bool
    {
        if (!$this->webhook_enabled || $this->webhook_verified_at === null) {
            return false;
        }

        return $this->webhook_verified_at->isBefore(now()->subDays(30));
    }
}