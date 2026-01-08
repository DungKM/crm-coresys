<?php

namespace Webkul\EmailExtended\Models;

use Webkul\Email\Models\Email as BaseEmail;
use Webkul\EmailExtended\Contracts\Email as EmailContract;
use Webkul\EmailExtended\Models\EmailProxy;
use Webkul\EmailExtended\Models\EmailThreadProxy;
use Illuminate\Support\Facades\Log;

class Email extends BaseEmail implements EmailContract
{
    // Tên bảng trong database
    protected $table = 'emails';

    // Các cột cho phép gán dữ liệu hàng loạt
    protected $fillable = [
        'subject',
        'source',
        'name',
        'user_type',
        'user_id',
        'is_read',
        'folders',
        'from',
        'to',
        'sender',
        'reply_to',
        'cc',
        'bcc',
        'unique_id',
        'message_id',
        'reference_ids',
        'reply',
        'person_id',
        'parent_id',
        'lead_id',
        'created_at',
        'updated_at',
        'thread_id',
        'in_reply_to',
        'direction',
        'status',
        'scheduled_at',
        'sent_at',
        'opened_at',
        'clicked_at',
        'bounced_at',
        'reply_to_email_id',
        'forward_from_email_id',
        'template_id',
        'rendered_content',
        'tracking_metadata',
        'send_metadata',
        'tracking_token',
    ];

    // Ép kiểu dữ liệu khi lấy từ database
    protected $casts = [
        'folders'           => 'array',
        'sender'            => 'array',
        'from'              => 'array',
        'to'                => 'array', 
        'reply_to'          => 'array',
        'cc'                => 'array',
        'bcc'               => 'array',
        'reference_ids'     => 'array',
        'is_read'           => 'boolean',
        'scheduled_at'      => 'datetime',
        'sent_at'           => 'datetime',
        'opened_at'         => 'datetime',
        'clicked_at'        => 'datetime',
        'bounced_at'        => 'datetime',
        'tracking_metadata' => 'array',
        'send_metadata'     => 'array',
    ];

    // Các thuộc tính ảo được append khi trả về JSON
    protected $appends = [
        'time_ago',
        'display_status',
    ];

    /**
     * Boot method - Tự động tạo tracking_token
     */
    public static function boot()
    {
        parent::boot();

        // Tự động tạo tracking_token khi tạo email mới
        static::creating(function ($email) {
            if (!$email->tracking_token && $email->direction === 'outbound') {
                $email->tracking_token = \Illuminate\Support\Str::random(32);
            }
        });
    }
    
    /**
     * Lấy thread (chuỗi hội thoại) mà email này thuộc về
     */
    public function thread()
    {
        return $this->belongsTo(EmailThreadProxy::modelClass(), 'thread_id');
    }

    /**
     * Lấy email mà email hiện tại đang trả lời
     */
    public function replyToEmail()
    {
        return $this->belongsTo(EmailProxy::modelClass(), 'reply_to_email_id');
    }

    /**
     * Lấy email gốc mà email hiện tại được forward từ đó
     */
    public function forwardFromEmail()
    {
        return $this->belongsTo(EmailProxy::modelClass(), 'forward_from_email_id');
    }

    /**
     * Lấy template email được sử dụng
     */
    public function template()
    {
        return $this->belongsTo(
            \Webkul\EmailTemplate\Models\EmailTemplateProxy::modelClass(),
            'template_id'
        );
    }

    /**
     * Lấy toàn bộ sự kiện tracking của email
     */
    public function tracking()
    {
        return $this->hasMany(EmailTrackingProxy::modelClass(), 'email_id');
    }

    /**
     * Lấy thông tin lịch gửi email
     */
    public function scheduled()
    {
        return $this->hasOne(EmailScheduledProxy::modelClass(), 'email_id');
    }

    /**
     * Scope lọc email đến (inbound)
     */
    public function scopeInbound($query)
    {
        return $query->where('direction', 'inbound');
    }

    /**
     * Scope lọc email gửi đi (outbound)
     */
    public function scopeOutbound($query)
    {
        return $query->where('direction', 'outbound');
    }

    /**
     * Scope lọc email nháp
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope lọc email đã gửi
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope lọc email đã lên lịch gửi
     */
    public function scopeScheduled($query)
    {
        return $query->whereNotNull('scheduled_at')
            ->where('status', 'queued');
    }

    /**
     * Scope lọc email đã được mở
     */
    public function scopeOpened($query)
    {
        return $query->whereNotNull('opened_at');
    }

    /**
     * Scope lọc email chưa được mở
     */
    public function scopeNotOpened($query)
    {
        return $query->whereNull('opened_at')
            ->where('status', 'sent')
            ->where('direction', 'outbound');
    }

    /**
     * Kiểm tra email có phải email đến hay không
     */
    public function isInbound(): bool
    {
        return $this->direction === 'inbound';
    }

    /**
     * Kiểm tra email có phải email gửi đi hay không
     */
    public function isOutbound(): bool
    {
        return $this->direction === 'outbound';
    }

    /**
     * Kiểm tra email có phải là bản nháp
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Kiểm tra email đã gửi hay chưa
     */
    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    /**
     * Kiểm tra email có đang được lên lịch gửi
     */
    public function isScheduled(): bool
    {
        return $this->scheduled_at && $this->status === 'queued';
    }

    /**
     * Kiểm tra email đã được mở hay chưa
     */
    public function wasOpened(): bool
    {
        return !is_null($this->opened_at);
    }

    /**
     * Kiểm tra email đã được click hay chưa
     */
    public function wasClicked(): bool
    {
        return !is_null($this->clicked_at);
    }

    /**
     * Kiểm tra email có bị bounce hay không
     */
    public function wasBounced(): bool
    {
        return !is_null($this->bounced_at);
    }
    
    /**
     * Đánh dấu email đã được mở
     */
    public function markAsOpened(): void
    {
        if (!$this->opened_at) {
            $this->update(['opened_at' => now()]);
        }
    }

    /**
     * Đánh dấu email đã được click
     */
    public function markAsClicked(): void
    {
        if (!$this->clicked_at) {
            $this->update(['clicked_at' => now()]);
        }
    }

    /**
     * Đánh dấu email đã gửi thành công
     */
    public function markAsSent(): void
    {
        $this->update([
            'status'  => 'sent',
            'sent_at' => now(),
        ]);
    }

    /**
     * Đánh dấu email gửi thất bại
     */
    public function markAsFailed(?string $error = null): void
    {
        $this->update([
            'status' => 'failed',
            'send_metadata' => array_merge($this->send_metadata ?? [], [
                'error'     => $error,
                'failed_at'=> now()->toIso8601String(),
            ]),
        ]);
    }

    /**
     * Đánh dấu email bị bounce
     */
    public function markAsBounced(?string $reason = null): void
    {
        $this->update([
            'status'     => 'bounced',
            'bounced_at'=> now(),
            'send_metadata' => array_merge($this->send_metadata ?? [], [
                'bounce_reason' => $reason,
            ]),
        ]);
    }

    /**
     * Sinh message_id duy nhất cho email
     */
    public static function generateMessageId(): string
    {
        // Lấy domain từ MAIL_FROM_ADDRESS thay vì APP_URL
        $fromEmail = config('mail.from.address', 'noreply@example.com');
        $domain = 'example.com';
        
        if (strpos($fromEmail, '@') !== false) {
            $domain = explode('@', $fromEmail)[1];
        }
        
        return sprintf(
            '%s.%s@%s',
            uniqid(),
            time(),
            $domain
        );
    }

    /**
     * Lấy URL tracking pixel (theo dõi mở email)
     */
    public function getTrackingPixelUrl(): string
    {
        return route('admin.emails.track.open', [
            'id'    => $this->id,
            'token' => $this->generateTrackingToken(),
        ]);
    }

    /**
     * Lấy URL tracking click (theo dõi click link)
     */
    public function getTrackingClickUrl(string $originalUrl): string
    {
        return route('admin.emails.track.click', [
            'id'    => $this->id,
            'token' => $this->generateTrackingToken(),
            'url'   => base64_encode($originalUrl),
        ]);
    }

    /**
     * Sinh token tracking bảo mật
     */
    public function generateTrackingToken(): string
    {
        return hash_hmac(
            'sha256',
            $this->id . $this->created_at,
            config('app.key')
        );
    }

    /**
     * Xác thực token tracking
     */
    public function verifyTrackingToken(string $token): bool
    {
        return hash_equals($this->generateTrackingToken(), $token);
    }

    /**
     * Chèn pixel tracking vào nội dung email
     */
    public function injectTrackingPixel(string $content): string
    {
        if ($this->isOutbound() && !$this->isDraft()) {
            $pixel = sprintf(
                '<img src="%s" width="1" height="1" style="display:none;" alt="" />',
                $this->getTrackingPixelUrl()
            );

            if (stripos($content, '</body>') !== false) {
                $content = str_ireplace('</body>', $pixel . '</body>', $content);
            } else {
                $content .= $pixel;
            }
        }

        return $content;
    }

    /**
     * Thay thế các link trong email bằng link tracking
     */
    public function injectTrackingLinks(string $content): string
    {
        if ($this->isOutbound() && !$this->isDraft()) {
            return preg_replace_callback(
                '/<a\s+(?:[^>]*?\s+)?href="([^"]*)"/i',
                function ($matches) {
                    $originalUrl = $matches[1];

                    if (
                        strpos($originalUrl, 'track/click') !== false ||
                        strpos($originalUrl, '#') === 0
                    ) {
                        return $matches[0];
                    }

                    $trackingUrl = $this->getTrackingClickUrl($originalUrl);
                    return str_replace($originalUrl, $trackingUrl, $matches[0]);
                },
                $content
            );
        }

        return $content;
    }

    /**
     * Lấy hoặc tạo mới thread cho email
     */
    public function getOrCreateThread()
    {
        // Nếu email đã có thread thì cập nhật thông tin thread
        if ($this->thread_id) {
            $this->load('thread');

            if ($this->thread) {
                $this->thread->update([
                    'last_email_at' => now(),
                ]);

                $this->thread->increment('email_count');

                Log::info('Using existing thread', [
                    'thread_id' => $this->thread->id,
                    'email_id'  => $this->id,
                ]);

                return $this->thread;
            }
        }

        // Nếu email là reply thì gắn vào thread của email cha
        if ($this->in_reply_to) {
            $parentEmail = self::where(
                'message_id',
                $this->in_reply_to
            )->first();

            if ($parentEmail && $parentEmail->thread) {
                $this->update(['thread_id' => $parentEmail->thread_id]);

                $parentEmail->thread->update([
                    'last_email_at' => now(),
                ]);

                $parentEmail->thread->increment('email_count');

                Log::info('Linked to parent thread', [
                    'thread_id' => $parentEmail->thread_id,
                    'email_id'  => $this->id,
                ]);

                return $parentEmail->thread;
            }
        }

        // Tạo thread mới nếu không tìm được thread phù hợp
        $thread = EmailThreadProxy::modelClass()::create([
            'subject'        => $this->subject,
            'message_id'     => $this->message_id ?? self::generateMessageId(),
            'lead_id'        => $this->lead_id,
            'person_id'      => $this->person_id,
            'user_id'        => $this->user_id,
            'last_email_at'  => now(),
            'email_count'    => 1,
            'unread_count'   => 0,
            'is_read'        => true,
            'folder'         => $this->isInbound() ? 'inbox' : 'sent',
        ]);

        $this->update(['thread_id' => $thread->id]);

        Log::info('Created new thread', [
            'thread_id' => $thread->id,
            'email_id'  => $this->id,
            'folder'    => $thread->folder,
        ]);

        return $thread;
    }

    /**
     * Lấy cấu hình hiển thị trạng thái email (badge)
     */
    public function getDisplayStatusAttribute(): array
    {
        return match (true) {
            $this->status === 'scheduled' => [
                'text'  => 'Chờ gửi (' . \Carbon\Carbon::parse($this->scheduled_at)->format('d/m H:i') . ')',
                'color' => 'bg-orange-100 text-orange-700 dark:bg-orange-900 dark:text-orange-300',
                'icon'  => 'icon-clock',
            ],

            $this->status === 'draft' => [
                'text'  => 'Nháp',
                'color' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                'icon'  => 'icon-edit',
            ],

            $this->status === 'failed' => [
                'text'  => 'Gửi thất bại',
                'color' => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                'icon'  => 'icon-alert-circle',
            ],

            $this->status === 'queued' && $this->direction === 'outbound' => [
                'text'  => 'Đang gửi',
                'color' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300',
                'icon'  => 'icon-time',
            ],

            $this->status === 'sent' && $this->direction === 'outbound' => [
                'text'  => 'Đã gửi',
                'color' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
                'icon'  => 'icon-check-circle',
            ],

            $this->direction === 'inbound' => [
                'text'  => 'Đã nhận',
                'color' => 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300',
                'icon'  => 'icon-mail',
            ],

            default => [
                'text'  => ucfirst($this->status ?? $this->direction ?? 'unknown'),
                'color' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                'icon'  => 'icon-info',
            ],
        };
    }
}
