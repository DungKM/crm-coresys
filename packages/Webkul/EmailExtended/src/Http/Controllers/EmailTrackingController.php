<?php

namespace Webkul\EmailExtended\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Email\Repositories\EmailRepository;
use Webkul\EmailExtended\Repositories\EmailTrackingRepository;
use Webkul\EmailExtended\Models\EmailSettings;
use SendGrid\EventWebhook\EventWebhook;

class EmailTrackingController extends Controller
{
    public function __construct(
        protected EmailRepository $emailRepository,
        protected EmailTrackingRepository $emailTrackingRepository
    ) {}

    /**
     * Theo dõi việc mở email qua pixel tracking
     * Route: /track/open/{id}/{token}
     */
    public function trackOpen(Request $request, int $id, string $token)
    {
        try {
            $email = $this->emailRepository->find($id);
            
            // Xác thực token 
            if (!$email || !$email->verifyTrackingToken($token)) {
                return $this->transparentPixel();
            }
            
            // Theo dõi sự kiện mở (CÁCH CŨ - Vẫn hoạt động)
            $this->emailTrackingRepository->trackOpen($email->id);
            
            // Update email
            $email->markAsOpened();
            
            Log::debug('Pixel tracking: Email opened', [
                'email_id' => $email->id,
                'method' => 'pixel',
            ]);
            
            return $this->transparentPixel();
            
        } catch (\Exception $e) {
            Log::error('Email tracking open failed: ' . $e->getMessage());
            return $this->transparentPixel();
        }
    }

    /**
     * Theo dõi sự kiện click link
     * Route: /track/click/{id}/{token}
     */
    public function trackClick(Request $request, int $id, string $token)
    {
        try {
            $email = $this->emailRepository->find($id);
            
            // Xác thực token 
            if (!$email || !$email->verifyTrackingToken($token)) {
                abort(404);
            }
            
            // Lấy URL gốc 
            $originalUrl = base64_decode($request->get('url'));
            
            // Track click event (CÁCH CŨ - Vẫn hoạt động)
            $this->emailTrackingRepository->trackClick($email->id, $originalUrl);
            
            // Update email
            $email->markAsClicked();
            
            Log::debug('Link tracking: Link clicked', [
                'email_id' => $email->id,
                'url' => $originalUrl,
                'method' => 'link_redirect',
            ]);
            
            return redirect($originalUrl);
            
        } catch (\Exception $e) {
            Log::error('Email tracking click failed: ' . $e->getMessage());
            abort(404);
        }
    }

    /**
     * Webhook endpoint để nhận events từ SendGrid
     * Route: POST /webhooks/sendgrid/email
     * 
     * SendGrid sẽ POST một array of events:
     * [
     *   {
     *     "email": "recipient@example.com",
     *     "event": "open",
     *     "timestamp": 1234567890,
     *     "custom_args": {
     *       "email_id": "12345"  // ← Từ CRM
     *     },
     *     "sg_event_id": "abc123...",
     *     ...
     *   }
     * ]
     */
    public function webhook(Request $request)
    {
        try {
            Log::info('=== SENDGRID WEBHOOK RECEIVED ===', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Xác thực chữ ký SendGrid (Bảo mật)
            if (!$this->verifyWebhookSignature($request)) {
                Log::warning('Invalid SendGrid webhook signature');
                return response()->json(['error' => 'Invalid signature'], 403);
            }
            
            $events = $request->all();
            
            if (empty($events) || !is_array($events)) {
                Log::warning('Empty or invalid webhook payload');
                return response()->json(['message' => 'No events received'], 200);
            }

            Log::info('Webhook payload valid', ['event_count' => count($events)]);

            // Process từng event
            $processed = 0;
            $skipped = 0;
            $failed = 0;

            foreach ($events as $event) {
                try {
                    $result = $this->processWebhookEvent($event);
                    
                    if ($result === 'processed') {
                        $processed++;
                    } elseif ($result === 'skipped') {
                        $skipped++;
                    }
                    
                } catch (\Exception $e) {
                    $failed++;
                    Log::error('Failed to process webhook event', [
                        'event' => $event,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            Log::info('=== WEBHOOK PROCESSING COMPLETED ===', [
                'total' => count($events),
                'processed' => $processed,
                'skipped' => $skipped,
                'failed' => $failed,
            ]);

            return response()->json([
                'message' => 'Webhook processed successfully',
                'stats' => [
                    'total' => count($events),
                    'processed' => $processed,
                    'skipped' => $skipped,
                    'failed' => $failed,
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Webhook Processing Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            // Vẫn return 200 để SendGrid không retry
            return response()->json([
                'error' => 'Processing failed',
                'message' => $e->getMessage()
            ], 200);
        }
    }

    /**
     * Xử lý từng event riêng lẻ
     * 
     * @return string 'processed' | 'skipped'
     */
    private function processWebhookEvent(array $event): string
    {
        // Extract thông tin cơ bản
        $sgEventId = $event['sg_event_id'] ?? null;
        $emailId = data_get($event, 'custom_args.email_id') ?? data_get($event, 'email_id');
        $eventType = $event['event'] ?? null;
        $timestamp = isset($event['timestamp']) 
            ? Carbon::createFromTimestamp($event['timestamp']) 
            : now();

        Log::debug('Processing webhook event', [
            'sg_event_id' => $sgEventId,
            'email_id' => $emailId,
            'event_type' => $eventType,
        ]);

        // Check 1: Chống replay attack
        if ($sgEventId && $this->emailTrackingRepository->eventExists($sgEventId)) {
            Log::debug('Event already processed, skipping', ['sg_event_id' => $sgEventId]);
            return 'skipped';
        }

        // Check 2: Email ID có tồn tại không
        if (!$emailId) {
            Log::warning('Missing email_id in webhook event', ['event' => $event]);
            return 'skipped';
        }

        // Check 3: Tìm email
        $email = $this->emailRepository->find($emailId);
        
        if (!$email) {
            Log::warning('Email not found', ['email_id' => $emailId]);
            return 'skipped';
        }

        // Check 4: Event type hợp lệ không
        if (!in_array($eventType, [
            'processed', 'delivered', 'bounce', 'dropped',
            'open', 'click', 'spamreport', 'unsubscribe'
        ])) {
            Log::warning('Invalid event type', ['event_type' => $eventType]);
            return 'skipped';
        }

        // Check 5: User có bật webhook tracking cho event này không?
        if (!$this->shouldProcessEvent($email, $eventType)) {
            Log::debug('Event not tracked by user settings', [
                'email_id' => $emailId,
                'event_type' => $eventType,
            ]);
            return 'skipped';
        }

        // Tất cả checks passed - Xử lý event
        $metadata = Arr::only($event, [
            'ip', 'useragent', 'url', 'timestamp', 'reason', 'type'
        ]);

        Log::info('Processing webhook event type: ' . $eventType, [
            'email_id' => $email->id,
            'sg_event_id' => $sgEventId,
        ]);

        // Xử lý dựa vào event type
        $this->handleEventType($email, $eventType, $sgEventId, $metadata, $timestamp);

        return 'processed';
    }

    /**
     * Kiểm tra user có track event này không
     * (Tích hợp với EmailSettings)
     */
    private function shouldProcessEvent($email, string $eventType): bool
    {
        Log::info('Checking shouldProcessEvent', [
            'email_id' => $email->id,
            'user_id' => $email->user_id,
            'event_type' => $eventType,
        ]);
        
        $settings = EmailSettings::where('user_id', $email->user_id)
            ->where('is_active', true)
            ->first();
        
        if (!$settings) {
            Log::warning('No settings found', ['user_id' => $email->user_id]);
            return false;
        }
        
        if (!$settings->webhook_enabled) {
            Log::warning('Webhook disabled', ['user_id' => $email->user_id]);
            return false;
        }

        $result = $settings->isTrackingEvent($eventType);
        Log::info('isTrackingEvent result', [
            'event_type' => $eventType,
            'webhook_events' => $settings->webhook_events,
            'result' => $result,
        ]);
        
        return $result;
    }

    /**
     * Xử lý theo từng loại event
     */
    private function handleEventType(
        $email,
        string $eventType, 
        ?string $sgEventId,
        array $metadata,
        Carbon $timestamp
    ) {
        switch ($eventType) {
            case 'processed':
                $this->handleProcessed($email, $sgEventId, $metadata, $timestamp);
                break;

            case 'delivered':
                $this->handleDelivered($email, $sgEventId, $metadata, $timestamp);
                break;

            case 'open':
                $this->handleOpen($email, $sgEventId, $metadata, $timestamp);
                break;

            case 'click':
                $this->handleClick($email, $sgEventId, $metadata, $timestamp);
                break;

            case 'bounce':
            case 'dropped':
                $this->handleBounce($email, $sgEventId, $metadata, $timestamp);
                break;

            case 'spamreport':
            case 'unsubscribe':
                $this->handleComplaint($email, $sgEventId, $metadata, $timestamp);
                break;

            default:
                Log::warning('Unknown event type', [
                    'event_type' => $eventType,
                    'email_id' => $email->id,
                ]);
        }
    }

    /**
     * Handle: Processed
     */
    private function handleProcessed($email, ?string $sgEventId, array $metadata, Carbon $timestamp)
    {
        $this->emailTrackingRepository->trackDelivery(
            $email->id,
            $sgEventId,
            array_merge($metadata, ['event_type' => 'processed'])
        );

        Log::info('Webhook: Email processed', ['email_id' => $email->id]);
    }

    /**
     * Handle: Delivered
     */
    private function handleDelivered($email, ?string $sgEventId, array $metadata, Carbon $timestamp)
    {
        $this->emailTrackingRepository->trackDelivery(
            $email->id,
            $sgEventId,
            $metadata
        );

        Log::info('Webhook: Email delivered', ['email_id' => $email->id]);
    }

    /**
     * Handle: Open (Webhook version)
     * Note: Không conflict với pixel tracking
     */
    private function handleOpen($email, ?string $sgEventId, array $metadata, Carbon $timestamp)
    {
        // Sử dụng method có sẵn, pass thêm sg_event_id
        $this->emailTrackingRepository->trackOpen(
            $email->id,
            $sgEventId,
            $metadata
        );

        // Update email (method này idempotent - gọi nhiều lần không sao)
        $email->markAsOpened();

        Log::info('Webhook: Email opened', [
            'email_id' => $email->id,
            'method' => 'webhook',
        ]);
    }

    /**
     * Handle: Click (Webhook version)
     * Note: Không conflict với link tracking
     */
    private function handleClick($email, ?string $sgEventId, array $metadata, Carbon $timestamp)
    {
        $url = $metadata['url'] ?? null;

        $this->emailTrackingRepository->trackClick(
            $email->id,
            $sgEventId,
            $url,
            $metadata
        );

        // Update email
        $email->markAsClicked();

        Log::info('Webhook: Link clicked', [
            'email_id' => $email->id,
            'url' => $url,
            'method' => 'webhook',
        ]);
    }

    /**
     * Handle: Bounce & Dropped
     */
    private function handleBounce($email, ?string $sgEventId, array $metadata, Carbon $timestamp)
    {
        $reason = $metadata['reason'] ?? 'Unknown';
        $eventType = $metadata['event'] ?? 'bounce';

        // Nếu là dropped thì dùng trackDropped
        if ($eventType === 'dropped') {
            $this->emailTrackingRepository->trackDropped(
                $email->id,
                $sgEventId,
                $metadata
            );
        } else {
            $this->emailTrackingRepository->trackBounce(
                $email->id,
                $sgEventId,
                $metadata
            );
        }

        $email->markAsBounced($reason);

        Log::warning('Webhook: Email bounced/dropped', [
            'email_id' => $email->id,
            'type' => $eventType,
            'reason' => $reason,
        ]);
    }

    /**
     * Handle: Spam Report & Unsubscribe
     */
    private function handleComplaint($email, ?string $sgEventId, array $metadata, Carbon $timestamp)
    {
        $eventType = $metadata['event'] ?? 'spamreport';

        if ($eventType === 'unsubscribe') {
            $this->emailTrackingRepository->trackUnsubscribe(
                $email->id,
                $sgEventId,
                $metadata
            );
        } else {
            $this->emailTrackingRepository->trackComplaint(
                $email->id,
                $sgEventId,
                $metadata
            );
        }

        Log::warning('Webhook: Spam/Unsubscribe reported', [
            'email_id' => $email->id,
            'type' => $eventType,
        ]);
    }

    /**
     * Lấy số liệu thống kê theo dõi email
     * Route: admin/mail/{id}/tracking
     */
    public function stats(int $id)
    {
        $email = $this->emailRepository->findOrFail($id);
        
        // Kiểm tra quyền 
        if ($email->user_id !== auth()->guard('user')->id()) {
            abort(403, 'Unauthorized');
        }
        
        // Lấy số liệu thống kê theo dõi 
        $stats = $this->emailTrackingRepository->getStatsForEmail($email->id);
        $events = $this->emailTrackingRepository->getEventsForEmail($email->id);
        $byLocation = $this->emailTrackingRepository->getStatsByLocation($email->id);
        $byDevice = $this->emailTrackingRepository->getStatsByDevice($email->id);
        $byBrowser = $this->emailTrackingRepository->getStatsByBrowser($email->id);
        $topUrls = $this->emailTrackingRepository->getTopClickedUrls($email->id);
        
        return view('email_extended::tracking-stats', compact(
            'email',
            'stats',
            'events',
            'byLocation',
            'byDevice',
            'byBrowser',
            'topUrls'
        ));
    }

    /**
     * Hiển thị Dashboard tracking với thống kê chi tiết
     * Route: admin/mail/tracking/dashboard
     */
    public function dashboard(Request $request)
    {
        $days = $request->get('days', 7);
        $startDate = now()->subDays($days);
        
        // Lấy thống kê tổng quan
        $stats = $this->getDashboardStats($startDate);
        
        // Lấy dữ liệu cho biểu đồ
        $chartData = $this->getChartData($startDate, $days);
        
        // Lấy hoạt động gần đây (20 events)
        $recentEvents = $this->emailTrackingRepository
            ->getRecentEvents(20);
        
        return view('email_extended::dashboard', compact(
            'stats',
            'chartData',
            'recentEvents',
            'days'
        ));
    }
    
    /**
     * Lấy thống kê tổng quan cho dashboard
     */
    private function getDashboardStats($startDate): array
    {
        $userId = auth()->guard('user')->id();
        
        // Lấy tất cả email đã gửi trong khoảng thời gian
        $emails = $this->emailRepository
            ->where('user_id', $userId)
            ->where('created_at', '>=', $startDate)
            ->where('direction', 'outbound')
            ->get();
        
        $totalSent = $emails->count();
        
        // Nếu không có email, return về stats rỗng
        if ($totalSent === 0) {
            return [
                'total_sent' => 0,
                'total_delivered' => 0,
                'total_opened' => 0,
                'total_clicked' => 0,
                'total_bounced' => 0,
                'total_complaints' => 0,
                'open_rate' => '0.00',
                'click_rate' => '0.00',
            ];
        }
        
        $emailIds = $emails->pluck('id');
        
        // Đếm số email đã giao (delivered)
        $totalDelivered = \Webkul\EmailExtended\Models\EmailTracking::where('event_type', 'delivered')
            ->where('created_at', '>=', $startDate)
            ->whereIn('email_id', $emailIds)
            ->distinct('email_id')
            ->count();
        
        // Đếm số email đã mở
        $totalOpened = \Webkul\EmailExtended\Models\EmailTracking::where('event_type', 'opened')
            ->where('created_at', '>=', $startDate)
            ->whereIn('email_id', $emailIds)
            ->distinct('email_id')
            ->count();
        
        // Đếm số click
        $totalClicked = \Webkul\EmailExtended\Models\EmailTracking::where('event_type', 'clicked')
            ->where('created_at', '>=', $startDate)
            ->whereIn('email_id', $emailIds)
            ->distinct('email_id')
            ->count();
        
        // Đếm bounce
        $totalBounced = \Webkul\EmailExtended\Models\EmailTracking::where('event_type', 'bounced')
            ->where('created_at', '>=', $startDate)
            ->whereIn('email_id', $emailIds)
            ->distinct('email_id')
            ->count();
        
        // Đếm spam complaints
        $totalComplaints = \Webkul\EmailExtended\Models\EmailTracking::where('event_type', 'complained')
            ->where('created_at', '>=', $startDate)
            ->whereIn('email_id', $emailIds)
            ->distinct('email_id')
            ->count();
        
        // Tính tỷ lệ
        $openRate = $totalDelivered > 0 
            ? number_format(($totalOpened / $totalDelivered) * 100, 2) 
            : '0.00';
        
        $clickRate = $totalOpened > 0 
            ? number_format(($totalClicked / $totalOpened) * 100, 2) 
            : '0.00';
        
        return [
            'total_sent' => $totalSent,
            'total_delivered' => $totalDelivered,
            'total_opened' => $totalOpened,
            'total_clicked' => $totalClicked,
            'total_bounced' => $totalBounced,
            'total_complaints' => $totalComplaints,
            'open_rate' => $openRate,
            'click_rate' => $clickRate,
        ];
    }

    /**
     * Lấy dữ liệu cho biểu đồ (Line chart & Bar chart)
     */
    private function getChartData($startDate, $days): array
    {
        $userId = auth()->guard('user')->id();
        
        $labels = [];
        $sent = [];
        $opened = [];
        $clicked = [];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            
            // Label cho biểu đồ
            if ($days <= 7) {
                // Hiển thị tên ngày: Mon, Tue, Wed...
                $dayNames = ['Sun' => 'CN', 'Mon' => 'T2', 'Tue' => 'T3', 'Wed' => 'T4', 'Thu' => 'T5', 'Fri' => 'T6', 'Sat' => 'T7'];
                $dayKey = $date->format('D');
                $labels[] = $dayNames[$dayKey] ?? $dayKey;
            } else {
                // Hiển thị ngày/tháng: 01/12, 02/12...
                $labels[] = $date->format('d/m');
            }
            
            // Đếm số email gửi trong ngày
            $sentCount = $this->emailRepository
                ->where('user_id', $userId)
                ->whereDate('created_at', $dateStr)
                ->where('direction', 'outbound')
                ->count();
            $sent[] = $sentCount;
            
            // Lấy email IDs cho ngày này
            $emailIds = $this->emailRepository
                ->where('user_id', $userId)
                ->whereDate('created_at', $dateStr)
                ->where('direction', 'outbound')
                ->pluck('id');
            
            // Nếu không có email trong ngày này, skip
            if ($emailIds->isEmpty()) {
                $opened[] = 0;
                $clicked[] = 0;
                continue;
            }
            
            // Đếm số email mở trong ngày
            $openedCount = \Webkul\EmailExtended\Models\EmailTracking::where('event_type', 'opened')
                ->whereDate('created_at', $dateStr)
                ->whereIn('email_id', $emailIds)
                ->distinct('email_id')
                ->count();
            $opened[] = $openedCount;
            
            // Đếm số click trong ngày
            $clickedCount = \Webkul\EmailExtended\Models\EmailTracking::where('event_type', 'clicked')
                ->whereDate('created_at', $dateStr)
                ->whereIn('email_id', $emailIds)
                ->distinct('email_id')
                ->count();
            $clicked[] = $clickedCount;
        }
        
        return [
            'labels' => $labels,
            'sent' => $sent,
            'opened' => $opened,
            'clicked' => $clicked,
        ];
    }

    // HELPER METHODS

    /**
     * Trả về transparent pixel 1x1 cho tracking
     */
    protected function transparentPixel()
    {
        $pixel = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');

        return response($pixel, 200)
            ->header('Content-Type', 'image/gif')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    /**
     * Xác thực webhook signature từ SendGrid
     */
    protected function verifyWebhookSignature(Request $request): bool
    {
        // Skip verification trong local/testing
        if (app()->environment('local', 'testing')) {
            Log::debug('Skipping webhook signature verification in local/testing');
            return true;
        }

        $signature = $request->header('X-Twilio-Email-Event-Webhook-Signature');
        $timestamp = $request->header('X-Twilio-Email-Event-Webhook-Timestamp');

        if (!$signature || !$timestamp) {
            Log::warning('Missing webhook signature headers');
            return false;
        }

        // Kiểm tra timestamp để tránh replay attack (trong vòng 10 phút)
        if (abs(time() - $timestamp) > 600) {
            Log::warning('Webhook timestamp too old', ['timestamp' => $timestamp]);
            return false;
        }

        // Lấy public key từ config hoặc từ user settings
        $publicKey = $this->getWebhookPublicKey($request);

        if (empty($publicKey)) {
            Log::error('SendGrid webhook public key not configured');
            return false;
        }

        try {
            $webhook = new EventWebhook();
            $isValid = $webhook->verifySignature(
                $publicKey,
                $request->getContent(),
                $signature,
                $timestamp
            );

            if (!$isValid) {
                Log::warning('Invalid webhook signature');
            }

            return $isValid;

        } catch (\Throwable $e) {
            Log::error('SendGrid webhook signature verification failed', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Lấy webhook public key từ user settings (dựa vào payload)
     */
    private function getWebhookPublicKey(Request $request): ?string
    {
        try {
            $events = $request->all();
            
            if (empty($events) || !is_array($events)) {
                return null;
            }

            // Lấy email_id từ event đầu tiên
            $firstEvent = $events[0];
            $emailId = data_get($firstEvent, 'custom_args.email_id') 
                    ?? data_get($firstEvent, 'email_id');

            if (!$emailId) {
                Log::warning('Cannot find email_id in webhook payload for key lookup');
                return null;
            }

            // Tìm email
            $email = \Webkul\Email\Models\Email::find($emailId);
            
            if (!$email) {
                Log::warning('Email not found for webhook key lookup', ['email_id' => $emailId]);
                return null;
            }

            // Lấy settings của user này
            $settings = \Webkul\EmailExtended\Models\EmailSettings::where('user_id', $email->user_id)
                ->where('is_active', true)
                ->first();

            if (!$settings) {
                Log::debug('No settings found for user', ['user_id' => $email->user_id]);
                return null;
            }

            // Trả về webhook_signing_key của USER này
            return $settings->webhook_signing_key;

        } catch (\Exception $e) {
            Log::error('Error getting webhook public key from payload', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}