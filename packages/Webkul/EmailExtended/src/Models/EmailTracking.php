<?php

namespace Webkul\EmailExtended\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\EmailExtended\Contracts\EmailTracking as EmailTrackingContract;

class EmailTracking extends Model implements EmailTrackingContract
{
    public $timestamps = false;
    protected $table = 'email_tracking';
    
    protected $fillable = [
        'email_id',
        'event_type',
        'ip_address',
        'user_agent',
        'location',
        'clicked_url',
        'device_type',
        'os',
        'browser',
        'metadata',
        'sg_event_id',      
        'event_time',
        'processed_at',
        'delivered_at',
        'dropped_at',
        'bounce_type',
        'bounce_reason',
        'status',
        'spam_reported_at',
        'unsubscribed_at',
        'bounced_at',
    ];

    protected $casts = [
        'location' => 'array',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'event_time' => 'datetime',
        'processed_at' => 'datetime',
        'delivered_at' => 'datetime',
        'dropped_at' => 'datetime',
        'bounced_at' => 'datetime',
        'spam_reported_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];
    
    /**
     * Lấy email sở hữu tracking này
     */
    public function email()
    {
        return $this->belongsTo(\Webkul\Email\Models\EmailProxy::modelClass(), 'email_id');
    }

    public function scopeOpened($query)
    {
        return $query->where('event_type', 'opened');
    }

    public function scopeClicked($query)
    {
        return $query->where('event_type', 'clicked');
    }

    public function scopeBounced($query)
    {
        return $query->where('event_type', 'bounced');
    }

    public function scopeDelivered($query)
    {
        return $query->where('event_type', 'delivered');
    }

    public function scopeComplained($query)
    {
        return $query->where('event_type', 'complained');
    }

    public function scopeForEmail($query, int $emailId)
    {
        return $query->where('email_id', $emailId);
    }

    public function scopeRecent($query, int $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Theo dõi sự kiện mở email
     */
    public static function trackOpen(int $emailId, ?string $sgEventId = null, array $data = []): self
    {
        $request = request();
        
        return self::create([
            'email_id' => $emailId,
            'event_type' => 'opened',
            'sg_event_id' => $sgEventId,
            'ip_address' => $data['ip'] ?? $request->ip(),
            'user_agent' => $data['useragent'] ?? $data['user_agent'] ?? $request->userAgent(),
            'location' => self::getLocationFromIp($data['ip'] ?? $request->ip()),
            'device_type' => self::getDeviceType($data['useragent'] ?? $data['user_agent'] ?? $request->userAgent()),
            'os' => self::getOS($data['useragent'] ?? $data['user_agent'] ?? $request->userAgent()),
            'browser' => self::getBrowser($data['useragent'] ?? $data['user_agent'] ?? $request->userAgent()),
            'metadata' => $data['metadata'] ?? $data,
            'status' => 'opened',
            'event_time' => isset($data['timestamp']) ? \Carbon\Carbon::createFromTimestamp($data['timestamp']) : now(),
        ]);
    }

    /**
     * Theo dõi sự kiện click link
     */
    public static function trackClick(int $emailId, ?string $sgEventId = null, ?string $url = null, array $data = []): self
    {
        $request = request();
        
        return self::create([
            'email_id' => $emailId,
            'event_type' => 'clicked',
            'sg_event_id' => $sgEventId,
            'clicked_url' => $url ?? $data['url'] ?? null,
            'ip_address' => $data['ip'] ?? $request->ip(),
            'user_agent' => $data['useragent'] ?? $data['user_agent'] ?? $request->userAgent(),
            'location' => self::getLocationFromIp($data['ip'] ?? $request->ip()),
            'device_type' => self::getDeviceType($data['useragent'] ?? $data['user_agent'] ?? $request->userAgent()),
            'os' => self::getOS($data['useragent'] ?? $data['user_agent'] ?? $request->userAgent()),
            'browser' => self::getBrowser($data['useragent'] ?? $data['user_agent'] ?? $request->userAgent()),
            'metadata' => $data['metadata'] ?? $data,
            'status' => 'clicked',
            'event_time' => isset($data['timestamp']) ? \Carbon\Carbon::createFromTimestamp($data['timestamp']) : now(),
        ]);
    }

    /**
     * Theo dõi sự kiện email bị trả về (bounce)
     */
    public static function trackBounce(int $emailId, ?string $sgEventId = null, array $data = []): self
    {
        return self::create([
            'email_id' => $emailId,
            'event_type' => 'bounced',
            'sg_event_id' => $sgEventId,
            'status' => 'bounced',
            'bounce_type' => $data['type'] ?? 'hard',
            'bounce_reason' => $data['reason'] ?? null,
            'bounced_at' => isset($data['timestamp']) ? \Carbon\Carbon::createFromTimestamp($data['timestamp']) : now(),
            'metadata' => $data,
            'event_time' => isset($data['timestamp']) ? \Carbon\Carbon::createFromTimestamp($data['timestamp']) : now(),
        ]);
    }

    /**
     * Theo dõi sự kiện email đã được gửi thành công
     */
    public static function trackDelivery(int $emailId, ?string $sgEventId = null, array $data = []): self
    {
        return self::create([
            'email_id' => $emailId,
            'event_type' => 'delivered',
            'sg_event_id' => $sgEventId,
            'status' => 'delivered',
            'delivered_at' => isset($data['timestamp']) ? \Carbon\Carbon::createFromTimestamp($data['timestamp']) : now(),
            'metadata' => $data,
            'event_time' => isset($data['timestamp']) ? \Carbon\Carbon::createFromTimestamp($data['timestamp']) : now(),
        ]);
    }

    /**
     * Theo dõi sự kiện phản hồi spam (complaint)
     */
    public static function trackComplaint(int $emailId, ?string $sgEventId = null, array $data = []): self
    {
        return self::create([
            'email_id' => $emailId,
            'event_type' => 'complained',
            'sg_event_id' => $sgEventId,
            'status' => 'complained',
            'spam_reported_at' => isset($data['timestamp']) ? \Carbon\Carbon::createFromTimestamp($data['timestamp']) : now(),
            'metadata' => $data,
            'event_time' => isset($data['timestamp']) ? \Carbon\Carbon::createFromTimestamp($data['timestamp']) : now(),
        ]);
    }

    /**
     * Theo dõi sự kiện hủy đăng ký (unsubscribe)
     */
    public static function trackUnsubscribe(int $emailId, ?string $sgEventId = null, array $data = []): self
    {
        return self::create([
            'email_id' => $emailId,
            'event_type' => 'unsubscribed',
            'sg_event_id' => $sgEventId,
            'status' => 'complained',
            'unsubscribed_at' => isset($data['timestamp']) ? \Carbon\Carbon::createFromTimestamp($data['timestamp']) : now(),
            'metadata' => $data,
            'event_time' => isset($data['timestamp']) ? \Carbon\Carbon::createFromTimestamp($data['timestamp']) : now(),
        ]);
    }

    // Track processed
    public static function trackProcessed(int $emailId, ?string $sgEventId = null, array $data = []): self
    {
        return self::create([
            'email_id' => $emailId,
            'event_type' => 'processed',
            'sg_event_id' => $sgEventId,
            'status' => 'processed',
            'processed_at' => isset($data['timestamp']) ? \Carbon\Carbon::createFromTimestamp($data['timestamp']) : now(),
            'metadata' => $data,
            'event_time' => isset($data['timestamp']) ? \Carbon\Carbon::createFromTimestamp($data['timestamp']) : now(),
        ]);
    }

    // Track dropped
    public static function trackDropped(int $emailId, ?string $sgEventId = null, array $data = []): self
    {
        return self::create([
            'email_id' => $emailId,
            'event_type' => 'dropped',
            'sg_event_id' => $sgEventId,
            'status' => 'dropped',
            'bounce_reason' => $data['reason'] ?? null,
            'dropped_at' => isset($data['timestamp']) ? \Carbon\Carbon::createFromTimestamp($data['timestamp']) : now(),
            'metadata' => $data,
            'event_time' => isset($data['timestamp']) ? \Carbon\Carbon::createFromTimestamp($data['timestamp']) : now(),
        ]);
    }

    /**
     * Lấy nhãn hiển thị cho loại sự kiện
     */
    public function getFormattedEventType(): string
    {
        return match($this->event_type) {
            'opened' => 'Mở email',
            'clicked' => 'Click link',
            'bounced' => 'Email bị trả lại',
            'complained' => 'Phản hồi spam',
            'unsubscribed' => 'Hủy đăng ký',
            'delivered' => 'Đã gửi',
            'processed' => 'Đang xử lý',
            'dropped' => 'Bị từ chối',
            default => ucfirst($this->event_type),
        };
    }

    /**
     * Lấy biểu tượng sự kiện
     */
    public function getEventIcon(): string
    {
        return match($this->event_type) {
            'opened' => 'icon-eye',
            'clicked' => 'icon-cursor-click',
            'bounced' => 'icon-exclamation',
            'complained' => 'icon-flag',
            'unsubscribed' => 'icon-user-minus',
            'delivered' => 'icon-check',
            'processed' => 'icon-refresh',
            'dropped' => 'icon-x-circle',
            default => 'icon-mail',
        };
    }

    /**
     * Lấy màu hiển thị sự kiện
     */
    public function getEventColor(): string
    {
        return match($this->event_type) {
            'opened' => 'green',
            'clicked' => 'blue',
            'bounced' => 'red',
            'complained' => 'orange',
            'unsubscribed' => 'gray',
            'delivered' => 'teal',
            'processed' => 'sky',
            'dropped' => 'red',
            default => 'gray',
        };
    }

    //  Status helpers
    public function isDelivered(): bool
    {
        return in_array($this->status, ['delivered', 'opened', 'clicked']);
    }

    public function hasIssue(): bool
    {
        return in_array($this->status, ['bounced', 'dropped', 'spam', 'complained']);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'delivered', 'opened', 'clicked' => 'green',
            'processed' => 'blue',
            'pending' => 'gray',
            'bounced', 'dropped' => 'red',
            'spam', 'complained' => 'orange',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Đang chờ',
            'processed' => 'Đang xử lý',
            'delivered' => 'Đã gửi',
            'opened' => 'Đã mở',
            'clicked' => 'Đã click',
            'bounced' => 'Bị trả lại',
            'dropped' => 'Bị từ chối',
            'spam' => 'Báo spam',
            'complained' => 'Hủy đăng ký',
            default => ucfirst($this->status ?? 'unknown'),
        };
    }

    /**
     * Lấy thông tin vị trí từ địa chỉ IP
     */
    protected static function getLocationFromIp(string $ip): ?array
    {
        // Bỏ qua localhost và IP riêng
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost']) || 
            filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return null;
        }

        try {
            // Sử dụng ip-api.com (Miễn phí: 45 request/phút)
            $response = @file_get_contents("http://ip-api.com/json/{$ip}?fields=status,country,countryCode,region,regionName,city,lat,lon,timezone");
            
            if ($response === false) {
                return null;
            }

            $data = json_decode($response, true);

            if (!$data || ($data['status'] ?? '') !== 'success') {
                return null;
            }

            return [
                'country' => $data['country'] ?? null,
                'country_code' => $data['countryCode'] ?? null,
                'city' => $data['city'] ?? null,
                'region' => $data['regionName'] ?? null,
                'latitude' => $data['lat'] ?? null,
                'longitude' => $data['lon'] ?? null,
                'timezone' => $data['timezone'] ?? null,
            ];
        } catch (\Exception $e) {
            // Ghi log lỗi nhưng không làm gián đoạn tracking
            Log::warning('Không lấy được vị trí IP: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Nhận dạng loại thiết bị từ user agent
     */
    protected static function getDeviceType(?string $userAgent): ?string
    {
        if (!$userAgent) {
            return null;
        }

        if (preg_match('/mobile/i', $userAgent)) {
            return 'mobile';
        }
        
        if (preg_match('/tablet|ipad/i', $userAgent)) {
            return 'tablet';
        }
        
        return 'desktop';
    }

    /**
     * Nhận dạng hệ điều hành từ user agent
     */
    protected static function getOS(?string $userAgent): ?string
    {
        if (!$userAgent) {
            return null;
        }

        $os_array = [
            '/windows nt 10/i'      => 'Windows 10',
            '/windows nt 11/i'      => 'Windows 11',
            '/windows nt 6.3/i'     => 'Windows 8.1',
            '/windows nt 6.2/i'     => 'Windows 8',
            '/windows nt 6.1/i'     => 'Windows 7',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i'        => 'Mac OS 9',
            '/linux/i'              => 'Linux',
            '/ubuntu/i'             => 'Ubuntu',
            '/iphone/i'             => 'iPhone',
            '/ipad/i'               => 'iPad',
            '/android/i'            => 'Android',
        ];

        foreach ($os_array as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                return $value;
            }
        }

        return null;
    }

    /**
     * Nhận dạng trình duyệt từ user agent
     */
    protected static function getBrowser(?string $userAgent): ?string
    {
        if (!$userAgent) {
            return null;
        }

        $browser_array = [
            '/msie/i'      => 'Internet Explorer',
            '/firefox/i'   => 'Firefox',
            '/safari/i'    => 'Safari',
            '/chrome/i'    => 'Chrome',
            '/edge/i'      => 'Edge',
            '/opera/i'     => 'Opera',
            '/netscape/i'  => 'Netscape',
            '/maxthon/i'   => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/mobile/i'    => 'Trình duyệt Mobile',
        ];

        foreach ($browser_array as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                return $value;
            }
        }

        return null;
    }

    /**
     * Lấy tỉ lệ mở email
     */
    public static function getOpenRate(int $emailId): float
    {
        $totalOpens = self::where('email_id', $emailId)
            ->where('event_type', 'opened')
            ->count();

        return $totalOpens > 0 ? 100 : 0;
    }

    /**
     * Lấy tỉ lệ click email
     */
    public static function getClickRate(int $emailId): float
    {
        $totalClicks = self::where('email_id', $emailId)
            ->where('event_type', 'clicked')
            ->count();

        $totalOpens = self::where('email_id', $emailId)
            ->where('event_type', 'opened')
            ->count();

        if ($totalOpens === 0) {
            return 0;
        }

        return round(($totalClicks / $totalOpens) * 100, 2);
    }

    /**
     * Lấy tất cả thống kê cho email
     */
    public static function getStatsForEmail(int $emailId): array
    {
        $events = self::where('email_id', $emailId)->get();

        return [
            'opens' => $events->where('event_type', 'opened')->count(),
            'clicks' => $events->where('event_type', 'clicked')->count(),
            'bounces' => $events->where('event_type', 'bounced')->count(),
            'complaints' => $events->where('event_type', 'complained')->count(),
            'unsubscribes' => $events->where('event_type', 'unsubscribed')->count(),
            'delivered' => $events->where('event_type', 'delivered')->count(),
            'processed' => $events->where('event_type', 'processed')->count(),
            'dropped' => $events->where('event_type', 'dropped')->count(),
            'first_opened_at' => $events->where('event_type', 'opened')->first()?->event_time,
            'last_opened_at' => $events->where('event_type', 'opened')->last()?->event_time,
            'unique_clicks' => $events->where('event_type', 'clicked')->unique('ip_address')->count(),
        ];
    }

    /**
     * Kiểm tra event đã tồn tại chưa (chống duplicate từ SendGrid)
     */
    public static function eventExists(?string $sgEventId): bool
    {
        if (!$sgEventId) {
            return false;
        }
        
        return self::where('sg_event_id', $sgEventId)->exists();
    }
}