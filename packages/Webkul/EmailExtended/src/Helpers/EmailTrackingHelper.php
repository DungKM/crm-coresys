<?php

namespace Webkul\EmailExtended\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\log;
use Illuminate\Support\Facades\Cache;

class EmailTrackingHelper
{
    public function generateTrackingPixel(int $emailId, string $token): string
    {
        $url = route('admin.emails.track.open', [
            'id' => $emailId,
            'token' => $token,
        ]);

        return sprintf(
            '<img src="%s" width="1" height="1" style="display:none;border:0;outline:0;" alt="" />',
            $url
        );
    }

    // tạo mã theo dõi 
    public function generateTrackingToken(int $emailId, $timestamp = null): string
    {
        $timestamp = $timestamp ?? now()->timestamp;
        return hash_hmac('sha256', $emailId . $timestamp, config('app.key'));
    }

    // Xác minh mã rheo dõi 
    public function verifyTrackingToken(int $emailId, string $token, $timestamp = null): bool
    {
        $expectedToken = $this->generateTrackingToken($emailId, $timestamp);
        return hash_equals($expectedToken, $token);
    }

    public function injectTrackingPixel(string $content, int $emailId, string $token): string
    {
        $pixel = $this->generateTrackingPixel($emailId, $token);
        if (stripos($content, '</body>') !== false) {
            return str_ireplace('</body>', $pixel . '</body>', $content);
        }
        return $content . $pixel;
    }

    // Thay thế các liên kết bằng URL theo dõi 
    public function injectTrackingLinks(string $content, int $emailId, string $token): string
    {
        return preg_replace_callback(
            '/<a\s+(?:[^>]*?\s+)?href="([^"]*)"/i',
            function ($matches) use ($emailId, $token) {
                $originalUrl = $matches[1];
                // bỏ qua nếu đã có url theo dõi 
                if (strpos($originalUrl, 'track/click') !== false) {
                    return $matches[0];
                }
                // bỏ qua các liên két neo 
                if (strpos($originalUrl, '#') === 0) {
                    return $matches[0];
                }
                // bỏ qua các liên kết maito 
                if (strpos($originalUrl, 'mailto:') === 0) {
                    return $matches[0];
                }
                // Tạo url theo dõi 
                $trackingUrl = $this->generateTrackingUrl($emailId, $token, $originalUrl);
                return str_replace($originalUrl, $trackingUrl, $matches[0]);
            },
            $content
        );
    }

    // tao URL theo dõi lượt nhấp chuột 
    public function generateTrackingUrl(int $emailId, string $token, string $originalUrl): string
    {
        return route('admin.emails.track.click', [
            'id' => $emailId,
            'token' => $token,
            'url' => base64_encode($originalUrl),
        ]);
    }

    // Phân tich user agent 
    public function parseUserAgent(?string $userAgent): array
    {
        if (!$userAgent) {
            return [
                'device' => 'Unknown',
                'os' => 'Unknown',
                'browser' => 'Unknown',
            ];
        }
        return [
            'device' => $this->getDeviceType($userAgent),
            'os' => $this->getOS($userAgent),
            'browser' => $this->getBrowser($userAgent),
        ];
    }

    // Lấy loại thiết bị từ user agent 
    public function getDeviceType(?string $userAgent): string
    {
        if (!$userAgent) {
            return 'Unknown';
        }
        if (preg_match('/mobile/i', $userAgent)) {
            return 'Mobile';
        }
        if (preg_match('/tablet|ipad/i', $userAgent)) {
            return 'Tablet';
        }
        return 'Desktop';
    }

    // Lấy hệ điều hành từ user agent 
    public function getOS(?string $userAgent): string
    {
        if (!$userAgent) {
            return 'Unknown';
        }
        $os_array = [
            '/windows nt 10/i' => 'Windows 10',
            '/windows nt 11/i' => 'Windows 11',
            '/windows nt 6.3/i' => 'Windows 8.1',
            '/windows nt 6.2/i' => 'Windows 8',
            '/windows nt 6.1/i' => 'Windows 7',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i' => 'Mac OS 9',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/iphone/i' => 'iPhone',
            '/ipad/i' => 'iPad',
            '/ipod/i' => 'iPod',
            '/android/i' => 'Android',
        ];
        foreach ($os_array as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                return $value;
            }
        }
        return 'Unknown';
    }

    // Lấy trình duyệt từ user agent 
    public function getBrowser(?string $userAgent): string
    {
        if (!$userAgent) {
            return 'Unknown';
        }
        $browser_array = [
            '/edg/i' => 'Edge',
            '/chrome/i' => 'Chrome',
            '/firefox/i' => 'Firefox',
            '/safari/i' => 'Safari',
            '/opera/i' => 'Opera',
            '/msie/i' => 'Internet Explorer',
            '/netscape/i' => 'Netscape',
            '/maxthon/i' => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
        ];
        foreach ($browser_array as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                return $value;
            }
        }
        return 'Unknown';
    }

    public function getLocationFromIp(string $ip): ?array
    {
        if (
            in_array($ip, ['127.0.0.1', '::1', 'localhost']) ||
            filter_var($ip,FILTER_VALIDATE_IP,FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false
        ) {
            return null;
        }
        $cacheKey = 'ip_location_' . sha1($ip);
        return Cache::remember($cacheKey, now()->addDay(), function () use ($ip) {
            try {
                $response = Http::timeout(2)->get(
                    "https://ip-api.com/json/{$ip}",
                    [
                        'fields' => 'status,country,countryCode,regionName,city,lat,lon,timezone'
                    ]
                );
                if (!$response->ok()) {
                    return null;
                }
                $data = $response->json();
                if (($data['status'] ?? null) !== 'success') {
                    return null;
                }
                return [
                    'country'       => $data['country'] ?? null,
                    'country_code'  => $data['countryCode'] ?? null,
                    'region'        => $data['regionName'] ?? null,
                    'city'          => $data['city'] ?? null,
                    'latitude'      => $data['lat'] ?? null,
                    'longitude'     => $data['lon'] ?? null,
                    'timezone'      => $data['timezone'] ?? null,
                ];
            } catch (\Throwable $e) {
                Log::warning('IP location lookup failed', [
                    'ip' => $ip,
                    'error' => $e->getMessage(),
                ]);
                return null;
            }
        });
    }

    // tính toán tỉ lệ mở email theo tỉ lệ phần trăm 
    public function calculateOpenRate(int $sent, int $opened): float
    {
        if ($sent === 0) {
            return 0.0;
        }
        return round(($opened / $sent) * 100, 2);
    }

    // tính toán tỉ lệ nhấp chuột 
    public function calculateClickRate(int $opened, int $clicked): float
    {
        if ($opened === 0) {
            return 0.0;
        }
        return round(($clicked / $opened) * 100, 2);
    }

    // tính toán tỉ lệ nhấp chuột để mở 
    public function calculateCTOR(int $opened, int $clicked): float
    {
        return $this->calculateClickRate($opened, $clicked);
    }

    public function getStatusBadge(string $status): string
    {
        $badges = [
            'opened' => '<span class="badge badge-success"><i class="icon-eye"></i> Opened</span>',
            'clicked' => '<span class="badge badge-info"><i class="icon-cursor-click"></i> Clicked</span>',
            'bounced' => '<span class="badge badge-danger"><i class="icon-exclamation"></i> Bounced</span>',
            'delivered' => '<span class="badge badge-success"><i class="icon-check"></i> Delivered</span>',
            'failed' => '<span class="badge badge-danger"><i class="icon-close"></i> Failed</span>',
            'sent' => '<span class="badge badge-primary"><i class="icon-send"></i> Sent</span>',
            'draft' => '<span class="badge badge-secondary"><i class="icon-draft"></i> Draft</span>',
            'queued' => '<span class="badge badge-warning"><i class="icon-clock"></i> Queued</span>',
        ];

        return $badges[$status] ?? '<span class="badge badge-secondary">' . ucfirst($status) . '</span>';
    }

    public function formatTrackingStats(array $stats): array
    {
        return [
            'opens' => $stats['opens'] ?? 0,
            'clicks' => $stats['clicks'] ?? 0,
            'unique_opens' => $stats['unique_opens'] ?? 0,
            'unique_clicks' => $stats['unique_clicks'] ?? 0,
            'open_rate' => $this->calculateOpenRate(1, $stats['opens'] > 0 ? 1 : 0),
            'click_rate' => $this->calculateClickRate($stats['opens'] ?? 0, $stats['clicks'] ?? 0),
            'first_opened_at' => $stats['first_opened_at'] ?? null,
            'last_opened_at' => $stats['last_opened_at'] ?? null,
        ];
    }

    public function getDeviceIcon(string $device): string
    {
        return match(strtolower($device)) {
            'mobile' => 'icon-mobile',
            'tablet' => 'icon-tablet',
            'desktop' => 'icon-desktop',
            default => 'icon-device',
        };
    }

    public function getOSIcon(string $os): string
    {
        if (stripos($os, 'windows') !== false) {
            return 'icon-windows';
        }
        if (stripos($os, 'mac') !== false || stripos($os, 'ios') !== false) {
            return 'icon-apple';
        }
        if (stripos($os, 'android') !== false) {
            return 'icon-android';
        }
        if (stripos($os, 'linux') !== false) {
            return 'icon-linux';
        }
        return 'icon-device';
    }

    public function getBrowserIcon(string $browser): string
    {
        return match(strtolower($browser)) {
            'chrome' => 'icon-chrome',
            'firefox' => 'icon-firefox',
            'safari' => 'icon-safari',
            'edge' => 'icon-edge',
            'opera' => 'icon-opera',
            default => 'icon-browser',
        };
    }

    public function getTransparentPixel(): string
    {
        return base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
    }

    // Kiểm tra xem tính năng theo dõi có được bật hay không
    public function isTrackingEnabled(): bool
    {
        return config('email_extended.tracking.enabled', true);
    }

    // Kiểm tra xem tính năng theo dõi đang mở có được bật hay không
    public function isOpenTrackingEnabled(): bool
    {
        return config('email_extended.tracking.track_opens', true);
    }

    // Kiểm tra xem tính năng theo dõi lượt nhấp chuột có được bật hay không
    public function isClickTrackingEnabled(): bool
    {
        return config('email_extended.tracking.track_clicks', true);
    }
}