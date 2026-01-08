<?php

namespace Webkul\EmailExtended\Repositories;

use Illuminate\Support\Facades\DB;
use Webkul\Core\Eloquent\Repository;
use Webkul\EmailExtended\Models\EmailTrackingProxy;

class EmailTrackingRepository extends Repository
{
    public function model(): string
    {
        return EmailTrackingProxy::modelClass();
    }

    // Theo dõi sự kiện mở email 
    public function trackOpen(int $emailId, ?string $sgEventId = null, array $data = [])
    {
        // Chống duplicate
        if ($sgEventId && $this->eventExists($sgEventId)) {
            return null;
        }

        $request = request();
        return $this->create([
            'email_id' => $emailId,
            'event_type' => 'opened',
            'sg_event_id' => $sgEventId,  
            'event_time' => isset($data['timestamp'])   
                ? \Carbon\Carbon::createFromTimestamp($data['timestamp'])
                : now(),
            'ip_address' => $data['ip'] ?? $request->ip(),
            'user_agent' => $data['useragent'] ?? $request->userAgent(),
            'location' => $this->getLocationFromIp($data['ip'] ?? $request->ip()),
            'device_type' => $this->getDeviceType($data['useragent'] ?? $request->userAgent()),
            'os' => $this->getOS($data['useragent'] ?? $request->userAgent()),
            'browser' => $this->getBrowser($data['useragent'] ?? $request->userAgent()),
            'metadata' => $data,
        ]);
    }

    // Theo dõi sự kiện click link email 
    public function trackClick(int $emailId, ?string $sgEventId = null, ?string $url = null, array $data = [])
    {
        // Chống duplicate
        if ($sgEventId && $this->eventExists($sgEventId)) {
            return null;
        }

        $request = request();
        return $this->create([
            'email_id' => $emailId,
            'event_type' => 'clicked',
            'sg_event_id' => $sgEventId,  
            'event_time' => isset($data['timestamp'])  
                ? \Carbon\Carbon::createFromTimestamp($data['timestamp'])
                : now(),
            'clicked_url' => $url ?? $data['url'] ?? '',
            'ip_address' => $data['ip'] ?? $request->ip(),
            'user_agent' => $data['useragent'] ?? $request->userAgent(),
            'location' => $this->getLocationFromIp($data['ip'] ?? $request->ip()),
            'device_type' => $this->getDeviceType($data['useragent'] ?? $request->userAgent()),
            'os' => $this->getOS($data['useragent'] ?? $request->userAgent()),
            'browser' => $this->getBrowser($data['useragent'] ?? $request->userAgent()),
            'metadata' => $data,
        ]);
    }

    public function trackBounce(int $emailId, ?string $sgEventId = null, array $data = [])
    {
        // Chống duplicate
        if ($sgEventId && $this->eventExists($sgEventId)) {
            return null;
        }

        return $this->create([
            'email_id' => $emailId,
            'event_type' => 'bounced',
            'sg_event_id' => $sgEventId,  
            'event_time' => isset($data['timestamp'])   
                ? \Carbon\Carbon::createFromTimestamp($data['timestamp'])
                : now(),
            'metadata' => $data,
        ]);
    }

    public function trackDelivery(int $emailId, ?string $sgEventId = null, array $data = [])
    {
        // Chống duplicate
        if ($sgEventId && $this->eventExists($sgEventId)) {
            return null;
        }

        return $this->create([
            'email_id' => $emailId,
            'event_type' => 'delivered',
            'sg_event_id' => $sgEventId,  
            'event_time' => isset($data['timestamp'])   
                ? \Carbon\Carbon::createFromTimestamp($data['timestamp'])
                : now(),
            'metadata' => $data,
        ]);
    }

    // Lấy tất cả các sự kiện theo dõi cho email 
    public function getEventsForEmail(int $emailId)
    {
        return $this->model
            ->where('email_id', $emailId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // Lấy số liệu thống kê theo dõi email 
    public function getStatsForEmail(int $emailId): array
    {
        $events = $this->getEventsForEmail($emailId);
        return [
            'opens' => $events->where('event_type', 'opened')->count(),
            'clicks' => $events->where('event_type', 'clicked')->count(),
            'bounces' => $events->where('event_type', 'bounced')->count(),
            'deliveries' => $events->where('event_type', 'delivered')->count(),
            'unique_opens' => $events->where('event_type', 'opened')->unique('ip_address')->count(),
            'unique_clicks' => $events->where('event_type', 'clicked')->unique('ip_address')->count(),
            'first_opened_at' => $events->where('event_type', 'opened')->first()?->created_at,
            'last_opened_at' => $events->where('event_type', 'opened')->last()?->created_at,
            'first_clicked_at' => $events->where('event_type', 'clicked')->first()?->created_at,
            'last_clicked_at' => $events->where('event_type', 'clicked')->last()?->created_at,
        ];
    }

    // Thống kê tỷ lệ mở email
    public function getOpenRate(int $emailId): float
    {
        $opens = $this->model
            ->where('email_id', $emailId)
            ->where('event_type', 'opened')
            ->count();
        return $opens > 0 ? 100.0 : 0.0;
    }

    // Thống kê tỷ lệ nhấp chuột cho email 
    public function getClickRate(int $emailId): float
    {
        $opens = $this->model
            ->where('email_id', $emailId)
            ->where('event_type', 'opened')
            ->count();

        $clicks = $this->model
            ->where('email_id', $emailId)
            ->where('event_type', 'clicked')
            ->count();

        if ($opens === 0) {
            return 0.0;
        }

        return round(($clicks / $opens) * 100, 2);
    }

    // Lấy các URL được nhấp chuột nhiều nhất cho email
    public function getTopClickedUrls(int $emailId, int $limit = 10): array
    {
        return $this->model
            ->where('email_id', $emailId)
            ->where('event_type', 'clicked')
            ->whereNotNull('clicked_url')
            ->select('clicked_url', DB::raw('count(*) as clicks'))
            ->groupBy('clicked_url')
            ->orderByDesc('clicks')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    // Lấy số liệu thống kê theo dõi theo vị trí
    public function getStatsByLocation(int $emailId): array
    {
        $events = $this->model
            ->where('email_id', $emailId)
            ->whereNotNull('location')
            ->get();

        $locations = [];
        foreach ($events as $event) {
            $location = $event->location;
            
            if (!$location || !isset($location['country'])) {
                continue;
            }
            $key = $location['country'];
            if (!isset($locations[$key])) {
                $locations[$key] = [
                    'country' => $location['country'],
                    'city' => $location['city'] ?? null,
                    'opens' => 0,
                    'clicks' => 0,
                ];
            }
            if ($event->event_type === 'opened') {
                $locations[$key]['opens']++;
            } elseif ($event->event_type === 'clicked') {
                $locations[$key]['clicks']++;
            }
        }
        return array_values($locations);
    }

    // Lấy số liệu thống kê theo dõi theo thiết bị
    public function getStatsByDevice(int $emailId): array
    {
        return $this->model
            ->where('email_id', $emailId)
            ->whereNotNull('device_type')
            ->select('device_type', DB::raw('count(*) as count'))
            ->groupBy('device_type')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->device_type => $item->count];
            })
            ->toArray();
    }

    // Lấy số liệu thống kê theo dõi theo trình duyệt
    public function getStatsByBrowser(int $emailId): array
    {
        return $this->model
            ->where('email_id', $emailId)
            ->whereNotNull('browser')
            ->select('browser', DB::raw('count(*) as count'))
            ->groupBy('browser')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->browser => $item->count];
            })
            ->toArray();
    }

    // Kiểm tra xem email đã được mở hay chưa
    public function wasOpened(int $emailId): bool
    {
        return $this->model
            ->where('email_id', $emailId)
            ->where('event_type', 'opened')
            ->exists();
    }

    // Kiểm tra xem người dùng đã nhấp vào email chưa
    public function wasClicked(int $emailId): bool
    {
        return $this->model
            ->where('email_id', $emailId)
            ->where('event_type', 'clicked')
            ->exists();
    }

    // Lấy các sự kiện theo dõi gần đây
    public function getRecentEvents(int $limit = 50)
    {
        return $this->model
            ->with('email')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    // Lấy vị trí từ địa chỉ IP
    protected function getLocationFromIp(string $ip): ?array
    {
        // Bỏ qua localhost và các địa chỉ IP riêng
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost']) || 
            filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return null;
        }

        try {
            $response = @file_get_contents("http://ip-api.com/json/{$ip}?fields=status,country,countryCode,region,regionName,city,lat,lon");
            
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
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    // Lấy loại thiết bị từ user agent
    protected function getDeviceType(?string $userAgent): ?string
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

    // Lấy hệ điều hành từ user agent
    protected function getOS(?string $userAgent): ?string
    {
        if (!$userAgent) {
            return null;
        }

        $os_array = [
            '/windows nt 10/i' => 'Windows 10',
            '/windows nt 11/i' => 'Windows 11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/iphone/i' => 'iPhone',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
        ];
        foreach ($os_array as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                return $value;
            }
        }
        return null;
    }

    // Lấy trình duyệt từ user agent
    protected function getBrowser(?string $userAgent): ?string
    {
        if (!$userAgent) {
            return null;
        }
        $browser_array = [
            '/edg/i' => 'Edge',
            '/chrome/i' => 'Chrome',
            '/firefox/i' => 'Firefox',
            '/safari/i' => 'Safari',
            '/opera/i' => 'Opera',
        ];
        foreach ($browser_array as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                return $value;
            }
        }
        return null;
    }

    /**
     * Check if SendGrid event exists (chống duplicate)
     */
    public function eventExists(?string $sgEventId): bool
    {
        if (!$sgEventId) {
            return false;
        }
        
        return $this->model
            ->where('sg_event_id', $sgEventId)
            ->exists();
    }

    /**
     * Track complaint/spam event
     */
    public function trackComplaint(int $emailId, ?string $sgEventId = null, array $data = [])
    {
        if ($sgEventId && $this->eventExists($sgEventId)) {
            return null;
        }

        return $this->create([
            'email_id' => $emailId,
            'event_type' => 'complained',
            'sg_event_id' => $sgEventId,
            'event_time' => isset($data['timestamp']) 
                ? \Carbon\Carbon::createFromTimestamp($data['timestamp'])
                : now(),
            'metadata' => $data,
        ]);
    }

    /**
     * Track processed event
     * Khi SendGrid nhận được email và bắt đầu xử lý
     */
    public function trackProcessed(int $emailId, ?string $sgEventId = null, array $data = [])
    {
        if ($sgEventId && $this->eventExists($sgEventId)) {
            return null;
        }

        return $this->create([
            'email_id' => $emailId,
            'event_type' => 'processed',
            'sg_event_id' => $sgEventId,
            'status' => 'processed',
            'processed_at' => isset($data['timestamp']) 
                ? \Carbon\Carbon::createFromTimestamp($data['timestamp'])
                : now(),
            'event_time' => isset($data['timestamp']) 
                ? \Carbon\Carbon::createFromTimestamp($data['timestamp'])
                : now(),
            'metadata' => $data,
        ]);
    }

    /**
     * Track dropped event
     * Khi SendGrid từ chối gửi email (spam, invalid email, etc.)
     */
    public function trackDropped(int $emailId, ?string $sgEventId = null, array $data = [])
    {
        if ($sgEventId && $this->eventExists($sgEventId)) {
            return null;
        }

        return $this->create([
            'email_id' => $emailId,
            'event_type' => 'dropped',
            'sg_event_id' => $sgEventId,
            'status' => 'dropped',
            'bounce_reason' => $data['reason'] ?? null,
            'dropped_at' => isset($data['timestamp']) 
                ? \Carbon\Carbon::createFromTimestamp($data['timestamp'])
                : now(),
            'event_time' => isset($data['timestamp']) 
                ? \Carbon\Carbon::createFromTimestamp($data['timestamp'])
                : now(),
            'metadata' => $data,
        ]);
    }

    /**
     * Track unsubscribe event
     * Khi user click unsubscribe
     */
    public function trackUnsubscribe(int $emailId, ?string $sgEventId = null, array $data = [])
    {
        if ($sgEventId && $this->eventExists($sgEventId)) {
            return null;
        }

        return $this->create([
            'email_id' => $emailId,
            'event_type' => 'unsubscribed',
            'sg_event_id' => $sgEventId,
            'status' => 'complained',
            'unsubscribed_at' => isset($data['timestamp']) 
                ? \Carbon\Carbon::createFromTimestamp($data['timestamp'])
                : now(),
            'event_time' => isset($data['timestamp']) 
                ? \Carbon\Carbon::createFromTimestamp($data['timestamp'])
                : now(),
            'metadata' => $data,
        ]);
    }

    /**
     * Get events by status
     * Lấy events theo status cụ thể (delivered, opened, bounced, etc.)
     */
    public function getEventsByStatus(string $status, int $limit = 100)
    {
        return $this->model
            ->where('status', $status)
            ->with('email')
            ->orderBy('event_time', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get bounced emails
     * Lấy danh sách email bị bounce/dropped
     */
    public function getBouncedEmails(int $limit = 50)
    {
        return $this->model
            ->whereIn('status', ['bounced', 'dropped'])
            ->with('email')
            ->orderByRaw('COALESCE(bounced_at, dropped_at) DESC')
            ->limit($limit)
            ->get();
    }

    /**
     * Get spam complaints
     * Lấy danh sách email bị báo spam/unsubscribe
     */
    public function getSpamComplaints(int $limit = 50)
    {
        return $this->model
            ->whereIn('status', ['spam', 'complained'])
            ->with('email')
            ->orderByRaw('COALESCE(spam_reported_at, unsubscribed_at) DESC')
            ->limit($limit)
            ->get();
    }

    /**
     * Get delivery rate
     * Tính tỷ lệ giao thành công
     */
    public function getDeliveryRate(int $emailId): float
    {
        $total = $this->model
            ->where('email_id', $emailId)
            ->whereIn('status', ['delivered', 'opened', 'clicked', 'bounced', 'dropped'])
            ->distinct('email_id')
            ->count();

        if ($total === 0) {
            return 0;
        }

        $delivered = $this->model
            ->where('email_id', $emailId)
            ->whereIn('status', ['delivered', 'opened', 'clicked'])
            ->distinct('email_id')
            ->count();

        return round(($delivered / $total) * 100, 2);
    }

    /**
     * Get bounce rate
     * Tính tỷ lệ bounce
     */
    public function getBounceRate(int $emailId): float
    {
        $total = $this->model
            ->where('email_id', $emailId)
            ->whereIn('status', ['delivered', 'opened', 'clicked', 'bounced', 'dropped'])
            ->distinct('email_id')
            ->count();

        if ($total === 0) {
            return 0;
        }

        $bounced = $this->model
            ->where('email_id', $emailId)
            ->whereIn('status', ['bounced', 'dropped'])
            ->distinct('email_id')
            ->count();

        return round(($bounced / $total) * 100, 2);
    }

    /**
     * Get engagement summary
     * Tóm tắt đầy đủ các metrics
     */
    public function getEngagementSummary(int $emailId): array
    {
        $stats = $this->getStatsForEmail($emailId);
        
        $delivered = $stats['deliveries'] ?? 0;
        $opens = $stats['opens'] ?? 0;
        $clicks = $stats['clicks'] ?? 0;
        $bounces = $stats['bounces'] ?? 0;

        // Calculate rates
        $openRate = $delivered > 0 ? round(($opens / $delivered) * 100, 2) : 0;
        $clickRate = $opens > 0 ? round(($clicks / $opens) * 100, 2) : 0;
        $bounceRate = $this->getBounceRate($emailId);

        return [
            'delivered' => $delivered,
            'opens' => $opens,
            'unique_opens' => $stats['unique_opens'] ?? 0,
            'clicks' => $clicks,
            'unique_clicks' => $stats['unique_clicks'] ?? 0,
            'bounces' => $bounces,
            'open_rate' => $openRate,
            'click_rate' => $clickRate,
            'bounce_rate' => $bounceRate,
            'click_to_open_rate' => $openRate > 0 ? round(($clicks / $opens) * 100, 2) : 0,
            'first_opened_at' => $stats['first_opened_at']?->toDateTimeString(),
            'last_opened_at' => $stats['last_opened_at']?->toDateTimeString(),
        ];
    }

    /**
     * Clean old tracking data
     * Xóa data cũ hơn X ngày để tối ưu database
     */
    public function cleanOldData(int $days = 90): int
    {
        $date = now()->subDays($days);
        
        return $this->model
            ->where('event_time', '<', $date)
            ->delete();
    }
}