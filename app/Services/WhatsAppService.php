<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $apiUrl;
    protected $phoneId;
    protected $token;

    // Danh sách templates có sẵn (để rotate)
    protected $templates = [
        'hello_world',
        // Thêm templates khác nếu bạn có
    ];

    public function __construct()
    {
        $this->apiUrl = env('WHATSAPP_API_URL');
        $this->phoneId = env('WHATSAPP_PHONE_NUMBER_ID');
        $this->token = env('WHATSAPP_ACCESS_TOKEN');
    }

    /**
     * Gửi tin nhắn text thường (không phải template)
     * Return: ['success' => true/false, 'message' => 'message', 'error_code' => code]
     */
    public function sendTextMessage($toPhone, $message = 'Xin chào!')
    {
        $url = "{$this->apiUrl}/{$this->phoneId}/messages";

        Log::info("[WhatsApp] Sending TEXT message to phone: {$toPhone}");
        Log::info("[WhatsApp] Message: {$message}");

        $maxRetries = 3;
        $retryDelay = 2;

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            Log::info("[WhatsApp] Attempt {$attempt}/{$maxRetries}");

            try {
                $response = Http::withoutVerifying()
                    ->timeout(30)
                    ->withToken($this->token)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                    ])
                    ->post($url, [
                        'messaging_product' => 'whatsapp',
                        'recipient_type'    => 'individual',
                        'to'                => $toPhone,
                        'type'              => 'text',
                        'text'              => [
                            'body' => $message
                        ]
                    ]);

                $statusCode = $response->status();
                $responseBody = $response->body();

                Log::info("[WhatsApp] API Status: {$statusCode}");

                $jsonData = json_decode($responseBody, true);

                // ✅ SUCCESS
                if ($jsonData && isset($jsonData['messages']) && is_array($jsonData['messages']) && count($jsonData['messages']) > 0) {
                    Log::info("[WhatsApp] ✅ SUCCESS - Message ID: " . ($jsonData['messages'][0]['id'] ?? 'unknown'));
                    return ['success' => true, 'message' => 'Tin nhắn đã gửi thành công'];
                }

                // ❌ ERROR
                if ($jsonData && isset($jsonData['error'])) {
                    $errorCode = $jsonData['error']['code'] ?? 'unknown';
                    $errorMessage = $jsonData['error']['message'] ?? 'Unknown error';

                    Log::error("[WhatsApp] ❌ Error Code {$errorCode}: {$errorMessage}");

                    if (in_array($statusCode, [429, 500, 502, 503, 504]) || in_array($errorCode, [131000, 131026])) {
                        if ($attempt < $maxRetries) {
                            Log::warning("[WhatsApp] Retrying in {$retryDelay} seconds...");
                            sleep($retryDelay);
                            continue;
                        }
                    }

                    return ['success' => false, 'message' => $errorMessage, 'error_code' => $errorCode];
                }

                if (strpos($responseBody, '<!doctype html>') !== false || strpos($responseBody, '<html') !== false) {
                    Log::error("[WhatsApp] ❌ Received HTML response instead of JSON!");
                    Log::error("[WhatsApp] First 500 chars: " . substr($responseBody, 0, 500));

                    if ($attempt < $maxRetries) {
                        Log::warning("[WhatsApp] Retrying in {$retryDelay} seconds...");
                        sleep($retryDelay);
                        continue;
                    }

                    return ['success' => false, 'message' => 'API trả về HTML thay vì JSON'];
                }

                if ($response->successful()) {
                    Log::info("[WhatsApp] ✅ SUCCESS (fallback) - Response: {$responseBody}");
                    return ['success' => true, 'message' => 'Tin nhắn đã gửi thành công'];
                }

                Log::error("[WhatsApp] ❌ Request failed - Status: {$statusCode}");
                return ['success' => false, 'message' => "Lỗi HTTP {$statusCode}"];

            } catch (\Exception $e) {
                Log::error("[WhatsApp] Exception on attempt {$attempt}: " . $e->getMessage());

                if ($attempt < $maxRetries) {
                    Log::warning("[WhatsApp] Retrying in {$retryDelay} seconds...");
                    sleep($retryDelay);
                    continue;
                }

                return ['success' => false, 'message' => $e->getMessage()];
            }
        }

        Log::error("[WhatsApp] ❌ Failed after {$maxRetries} attempts");
        return ['success' => false, 'message' => "Không thể gửi tin nhắn sau {$maxRetries} lần thử"];
    }

    /**
     * Gửi tin nhắn template (bị rate limit 1 lần/24h per phone)
     */
    public function sendTemplateMessage($toPhone, $templateName = 'hello_world', $languageCode = 'en_US')
    {
        $url = "{$this->apiUrl}/{$this->phoneId}/messages";

        Log::info("[WhatsApp] Sending template '{$templateName}' to phone: {$toPhone}");

        // Retry tối đa 3 lần với delay
        $maxRetries = 3;
        $retryDelay = 2; // seconds

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            Log::info("[WhatsApp] Attempt {$attempt}/{$maxRetries}");

            try {
                $response = Http::withoutVerifying()
                    ->timeout(30)
                    ->withToken($this->token)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                        'User-Agent'   => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
                    ])
                    ->post($url, [
                        'messaging_product' => 'whatsapp',
                        'to'                => $toPhone,
                        'type'              => 'template',
                        'template'          => [
                            'name'     => $templateName,
                            'language' => ['code' => $languageCode]
                        ]
                    ]);

                $statusCode = $response->status();
                $responseBody = $response->body();

                Log::info("[WhatsApp] API Status: {$statusCode}");

                // Try to parse JSON
                $jsonData = json_decode($responseBody, true);

                // ✅ SUCCESS - Valid JSON response with messages
                if ($jsonData && isset($jsonData['messages']) && is_array($jsonData['messages']) && count($jsonData['messages']) > 0) {
                    Log::info("[WhatsApp] ✅ SUCCESS - Message ID: " . ($jsonData['messages'][0]['id'] ?? 'unknown'));
                    return true;
                }

                // ❌ ERROR - JSON has error object
                if ($jsonData && isset($jsonData['error'])) {
                    $errorCode = $jsonData['error']['code'] ?? 'unknown';
                    $errorMessage = $jsonData['error']['message'] ?? 'Unknown error';
                    $errorData = $jsonData['error']['error_data']['details'] ?? '';

                    Log::error("[WhatsApp] ❌ Error Code {$errorCode}: {$errorMessage}");
                    if ($errorData) {
                        Log::error("[WhatsApp] Details: {$errorData}");
                    }

                    // Rate limit errors (429, 131000, 131026) → RETRY
                    if (in_array($statusCode, [429, 500, 502, 503, 504]) || in_array($errorCode, [131000, 131026])) {
                        if ($attempt < $maxRetries) {
                            Log::warning("[WhatsApp] Retrying in {$retryDelay} seconds...");
                            sleep($retryDelay);
                            continue;
                        }
                    }

                    // Permanent errors → STOP
                    return false;
                }

                // ❌ UNEXPECTED - Response is HTML or invalid format
                if (strpos($responseBody, '<!doctype html>') !== false || strpos($responseBody, '<html') !== false) {
                    Log::error("[WhatsApp] ❌ Received HTML response instead of JSON!");
                    Log::error("[WhatsApp] First 500 chars: " . substr($responseBody, 0, 500));

                    if ($attempt < $maxRetries) {
                        Log::warning("[WhatsApp] Retrying in {$retryDelay} seconds...");
                        sleep($retryDelay);
                        continue;
                    }

                    return false;
                }

                // ✅ Fallback - Status 200 with valid format (old behavior)
                if ($response->successful()) {
                    Log::info("[WhatsApp] ✅ SUCCESS (fallback) - Response: {$responseBody}");
                    return true;
                }

                Log::error("[WhatsApp] ❌ Request failed - Status: {$statusCode}");
                return false;

            } catch (\Exception $e) {
                Log::error("[WhatsApp] Exception on attempt {$attempt}: " . $e->getMessage());

                if ($attempt < $maxRetries) {
                    Log::warning("[WhatsApp] Retrying in {$retryDelay} seconds...");
                    sleep($retryDelay);
                    continue;
                }

                return false;
            }
        }

        Log::error("[WhatsApp] ❌ Failed after {$maxRetries} attempts");
        return false;
    }
    
    /**
     * Upload media file lên WhatsApp Cloud
     * @param string $filePath - Full path to file
     * @param string $mimeType - MIME type of file
     * @return array ['success' => bool, 'media_id' => string|null, 'message' => string]
     */
    public function uploadMedia($filePath, $mimeType)
    {
        $url = "{$this->apiUrl}/{$this->phoneId}/media";
        
        Log::info("[WhatsApp] Uploading media: {$filePath}");
        Log::info("[WhatsApp] MIME type: {$mimeType}");
        
        try {
            // Validate file exists
            if (!file_exists($filePath)) {
                Log::error("[WhatsApp] File not found: {$filePath}");
                return ['success' => false, 'media_id' => null, 'message' => 'File không tồn tại'];
            }
            
            $response = Http::withoutVerifying()
                ->timeout(120) // Longer timeout for file upload
                ->withToken($this->token)
            ->attach(
                    'file',
                    file_get_contents($filePath),
                    basename($filePath),
                    ['Content-Type' => $mimeType] // Quan trọng: phải gửi MIME type
                )
                ->post($url, [
                    'messaging_product' => 'whatsapp',
                    'type' => $mimeType,
                ]);
            
            $statusCode = $response->status();
            $responseBody = $response->body();
            $jsonData = json_decode($responseBody, true);
            
            Log::info("[WhatsApp] Upload Status: {$statusCode}");
            Log::info("[WhatsApp] Upload Response: " . substr($responseBody, 0, 500));
            
            if ($jsonData && isset($jsonData['id'])) {
                Log::info("[WhatsApp] ✅ Media uploaded - ID: {$jsonData['id']}");
                return [
                    'success' => true, 
                    'media_id' => $jsonData['id'],
                    'message' => 'Upload thành công'
                ];
            }
            
            if ($jsonData && isset($jsonData['error'])) {
                $errorMessage = $jsonData['error']['message'] ?? 'Unknown error';
                Log::error("[WhatsApp] ❌ Upload error: {$errorMessage}");
                return ['success' => false, 'media_id' => null, 'message' => $errorMessage];
            }
            
            return ['success' => false, 'media_id' => null, 'message' => 'Upload thất bại'];
            
        } catch (\Exception $e) {
            Log::error("[WhatsApp] Upload Exception: " . $e->getMessage());
            return ['success' => false, 'media_id' => null, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Gửi document message qua WhatsApp
     * @param string $toPhone - Số điện thoại người nhận (format: 84xxx)
     * @param string $mediaId - Media ID từ uploadMedia()
     * @param string $filename - Tên file hiển thị
     * @param string|null $caption - Caption đi kèm (optional)
     * @return array ['success' => bool, 'message' => string]
     */
    public function sendDocumentMessage($toPhone, $mediaId, $filename, $caption = null)
    {
        $url = "{$this->apiUrl}/{$this->phoneId}/messages";
        
        Log::info("[WhatsApp] Sending document to: {$toPhone}");
        Log::info("[WhatsApp] Media ID: {$mediaId}, Filename: {$filename}");
        
        try {
            $payload = [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $toPhone,
                'type' => 'document',
                'document' => [
                    'id' => $mediaId,
                    'filename' => $filename,
                ],
            ];
            
            // Add caption if provided
            if ($caption) {
                $payload['document']['caption'] = $caption;
            }
            
            $response = Http::withoutVerifying()
                ->timeout(30)
                ->withToken($this->token)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($url, $payload);
            
            $statusCode = $response->status();
            $responseBody = $response->body();
            $jsonData = json_decode($responseBody, true);
            
            Log::info("[WhatsApp] Document Send Status: {$statusCode}");
            
            if ($jsonData && isset($jsonData['messages']) && count($jsonData['messages']) > 0) {
                Log::info("[WhatsApp] ✅ Document sent - Message ID: " . ($jsonData['messages'][0]['id'] ?? 'unknown'));
                return ['success' => true, 'message' => 'Document đã gửi thành công'];
            }
            
            if ($jsonData && isset($jsonData['error'])) {
                $errorMessage = $jsonData['error']['message'] ?? 'Unknown error';
                Log::error("[WhatsApp] ❌ Document send error: {$errorMessage}");
                return ['success' => false, 'message' => $errorMessage];
            }
            
            return ['success' => false, 'message' => 'Gửi document thất bại'];
            
        } catch (\Exception $e) {
            Log::error("[WhatsApp] Document Exception: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Gửi image message qua WhatsApp
     * @param string $toPhone - Số điện thoại người nhận (format: 84xxx)
     * @param string $mediaId - Media ID từ uploadMedia()
     * @param string|null $caption - Caption đi kèm (optional)
     * @return array ['success' => bool, 'message' => string]
     */
    public function sendImageMessage($toPhone, $mediaId, $caption = null)
    {
        $url = "{$this->apiUrl}/{$this->phoneId}/messages";
        
        Log::info("[WhatsApp] Sending image to: {$toPhone}");
        Log::info("[WhatsApp] Media ID: {$mediaId}");
        
        try {
            $payload = [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $toPhone,
                'type' => 'image',
                'image' => [
                    'id' => $mediaId,
                ],
            ];
            
            if ($caption) {
                $payload['image']['caption'] = $caption;
            }
            
            $response = Http::withoutVerifying()
                ->timeout(30)
                ->withToken($this->token)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, $payload);
            
            $jsonData = json_decode($response->body(), true);
            
            if ($jsonData && isset($jsonData['messages']) && count($jsonData['messages']) > 0) {
                Log::info("[WhatsApp] ✅ Image sent - Message ID: " . ($jsonData['messages'][0]['id'] ?? 'unknown'));
                return ['success' => true, 'message' => 'Hình ảnh đã gửi thành công'];
            }
            
            if ($jsonData && isset($jsonData['error'])) {
                $errorMessage = $jsonData['error']['message'] ?? 'Unknown error';
                Log::error("[WhatsApp] ❌ Image send error: {$errorMessage}");
                return ['success' => false, 'message' => $errorMessage];
            }
            
            return ['success' => false, 'message' => 'Gửi hình ảnh thất bại'];
            
        } catch (\Exception $e) {
            Log::error("[WhatsApp] Image Exception: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Gửi video message qua WhatsApp
     * @param string $toPhone - Số điện thoại người nhận (format: 84xxx)
     * @param string $mediaId - Media ID từ uploadMedia()
     * @param string|null $caption - Caption đi kèm (optional)
     * @return array ['success' => bool, 'message' => string]
     */
    public function sendVideoMessage($toPhone, $mediaId, $caption = null)
    {
        $url = "{$this->apiUrl}/{$this->phoneId}/messages";
        
        Log::info("[WhatsApp] Sending video to: {$toPhone}");
        Log::info("[WhatsApp] Media ID: {$mediaId}");
        
        try {
            $payload = [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $toPhone,
                'type' => 'video',
                'video' => [
                    'id' => $mediaId,
                ],
            ];
            
            if ($caption) {
                $payload['video']['caption'] = $caption;
            }
            
            $response = Http::withoutVerifying()
                ->timeout(30)
                ->withToken($this->token)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, $payload);
            
            $jsonData = json_decode($response->body(), true);
            
            if ($jsonData && isset($jsonData['messages']) && count($jsonData['messages']) > 0) {
                Log::info("[WhatsApp] ✅ Video sent - Message ID: " . ($jsonData['messages'][0]['id'] ?? 'unknown'));
                return ['success' => true, 'message' => 'Video đã gửi thành công'];
            }
            
            if ($jsonData && isset($jsonData['error'])) {
                $errorMessage = $jsonData['error']['message'] ?? 'Unknown error';
                Log::error("[WhatsApp] ❌ Video send error: {$errorMessage}");
                return ['success' => false, 'message' => $errorMessage];
            }
            
            return ['success' => false, 'message' => 'Gửi video thất bại'];
            
        } catch (\Exception $e) {
            Log::error("[WhatsApp] Video Exception: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Gửi audio message qua WhatsApp
     * @param string $toPhone - Số điện thoại người nhận (format: 84xxx)
     * @param string $mediaId - Media ID từ uploadMedia()
     * @return array ['success' => bool, 'message' => string]
     */
    public function sendAudioMessage($toPhone, $mediaId)
    {
        $url = "{$this->apiUrl}/{$this->phoneId}/messages";
        
        Log::info("[WhatsApp] Sending audio to: {$toPhone}");
        Log::info("[WhatsApp] Media ID: {$mediaId}");
        
        try {
            $payload = [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $toPhone,
                'type' => 'audio',
                'audio' => [
                    'id' => $mediaId,
                ],
            ];
            
            $response = Http::withoutVerifying()
                ->timeout(30)
                ->withToken($this->token)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, $payload);
            
            $jsonData = json_decode($response->body(), true);
            
            if ($jsonData && isset($jsonData['messages']) && count($jsonData['messages']) > 0) {
                Log::info("[WhatsApp] ✅ Audio sent - Message ID: " . ($jsonData['messages'][0]['id'] ?? 'unknown'));
                return ['success' => true, 'message' => 'Âm thanh đã gửi thành công'];
            }
            
            if ($jsonData && isset($jsonData['error'])) {
                $errorMessage = $jsonData['error']['message'] ?? 'Unknown error';
                Log::error("[WhatsApp] ❌ Audio send error: {$errorMessage}");
                return ['success' => false, 'message' => $errorMessage];
            }
            
            return ['success' => false, 'message' => 'Gửi âm thanh thất bại'];
            
        } catch (\Exception $e) {
            Log::error("[WhatsApp] Audio Exception: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Gửi sticker message qua WhatsApp
     * @param string $toPhone - Số điện thoại người nhận (format: 84xxx)
     * @param string $mediaId - Media ID từ uploadMedia()
     * @return array ['success' => bool, 'message' => string]
     */
    public function sendStickerMessage($toPhone, $mediaId)
    {
        $url = "{$this->apiUrl}/{$this->phoneId}/messages";
        
        Log::info("[WhatsApp] Sending sticker to: {$toPhone}");
        Log::info("[WhatsApp] Media ID: {$mediaId}");
        
        try {
            $payload = [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $toPhone,
                'type' => 'sticker',
                'sticker' => [
                    'id' => $mediaId,
                ],
            ];
            
            $response = Http::withoutVerifying()
                ->timeout(30)
                ->withToken($this->token)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, $payload);
            
            $jsonData = json_decode($response->body(), true);
            
            if ($jsonData && isset($jsonData['messages']) && count($jsonData['messages']) > 0) {
                Log::info("[WhatsApp] ✅ Sticker sent - Message ID: " . ($jsonData['messages'][0]['id'] ?? 'unknown'));
                return ['success' => true, 'message' => 'Sticker đã gửi thành công'];
            }
            
            if ($jsonData && isset($jsonData['error'])) {
                $errorMessage = $jsonData['error']['message'] ?? 'Unknown error';
                Log::error("[WhatsApp] ❌ Sticker send error: {$errorMessage}");
                return ['success' => false, 'message' => $errorMessage];
            }
            
            return ['success' => false, 'message' => 'Gửi sticker thất bại'];
            
        } catch (\Exception $e) {
            Log::error("[WhatsApp] Sticker Exception: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Lấy MIME type hợp lệ cho WhatsApp từ extension
     */
    public static function getMimeType($extension)
    {
        $mimeTypes = [
            // Documents
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'txt' => 'text/plain',
            'csv' => 'text/csv',
            'xml' => 'application/xml',
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
            'odp' => 'application/vnd.oasis.opendocument.presentation',
            'rtf' => 'application/rtf',
            // Archives
            'zip' => 'application/zip',
            'rar' => 'application/vnd.rar',
            '7z' => 'application/x-7z-compressed',
            'tar' => 'application/x-tar',
            'gz' => 'application/gzip',
            // Images
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            // Videos
            'mp4' => 'video/mp4',
            'avi' => 'video/x-msvideo',
            'mov' => 'video/quicktime',
            '3gp' => 'video/3gpp',
            'mkv' => 'video/x-matroska',
            // Audio
            'mp3' => 'audio/mpeg',
            'wav' => 'audio/wav',
            'ogg' => 'audio/ogg',
            'aac' => 'audio/aac',
            'm4a' => 'audio/mp4',
            'amr' => 'audio/amr',
            // Stickers
            'webp' => 'image/webp',
        ];
        
        return $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';
    }
    
    /**
     * Kiểm tra extension có được hỗ trợ không
     */
    public static function isSupportedExtension($extension)
    {
        $supported = [
            // Documents
            'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
            'txt', 'csv', 'xml', 'odt', 'ods', 'odp', 'rtf', 'tex',
            // Archives
            'zip', 'rar', '7z', 'tar', 'gz',
            // Images
            'jpg', 'jpeg', 'png', 'gif', 'webp',
            // Videos
            'mp4', 'avi', 'mov', '3gp', 'mkv',
            // Audio
            'mp3', 'wav', 'ogg', 'aac', 'm4a', 'amr',
        ];
        
        return in_array(strtolower($extension), $supported);
    }
    
    /**
     * Xác định loại media từ extension
     * @return string 'image'|'video'|'audio'|'sticker'|'document'
     */
    public static function getMediaType($extension)
    {
        $ext = strtolower($extension);
        
        $images = ['jpg', 'jpeg', 'png', 'gif'];
        $videos = ['mp4', 'avi', 'mov', '3gp', 'mkv'];
        $audios = ['mp3', 'wav', 'ogg', 'aac', 'm4a', 'amr'];
        $stickers = ['webp'];
        
        if (in_array($ext, $images)) return 'image';
        if (in_array($ext, $videos)) return 'video';
        if (in_array($ext, $audios)) return 'audio';
        if (in_array($ext, $stickers)) return 'sticker';
        
        return 'document';
    }
    
    /**
     * Lấy URL download từ media_id
     * @param string $mediaId - Media ID từ WhatsApp
     * @return array ['success' => bool, 'url' => string|null, 'message' => string]
     */
    public function getMediaUrl($mediaId)
    {
        // WhatsApp API cần gọi endpoint riêng để lấy URL download
        $url = "https://graph.facebook.com/v18.0/{$mediaId}";
        
        Log::info("[WhatsApp] Getting media URL for ID: {$mediaId}");
        
        try {
            $response = Http::withoutVerifying()
                ->timeout(30)
                ->withToken($this->token)
                ->get($url);
            
            $jsonData = json_decode($response->body(), true);
            
            if ($jsonData && isset($jsonData['url'])) {
                Log::info("[WhatsApp] ✅ Got media URL");
                return [
                    'success' => true,
                    'url' => $jsonData['url'],
                    'mime_type' => $jsonData['mime_type'] ?? null,
                    'message' => 'Success'
                ];
            }
            
            if ($jsonData && isset($jsonData['error'])) {
                $errorMessage = $jsonData['error']['message'] ?? 'Unknown error';
                Log::error("[WhatsApp] ❌ Get media URL error: {$errorMessage}");
                return ['success' => false, 'url' => null, 'message' => $errorMessage];
            }
            
            return ['success' => false, 'url' => null, 'message' => 'Không lấy được URL media'];
            
        } catch (\Exception $e) {
            Log::error("[WhatsApp] Get media URL Exception: " . $e->getMessage());
            return ['success' => false, 'url' => null, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Download media từ WhatsApp và lưu vào server
     * @param string $mediaId - Media ID từ WhatsApp
     * @param string $mediaType - Loại media (image, video, audio, document, sticker)
     * @param string|null $filename - Tên file gốc (optional)
     * @return array ['success' => bool, 'path' => string|null, 'url' => string|null, 'message' => string]
     */
    public function downloadMedia($mediaId, $mediaType, $filename = null)
    {
        Log::info("[WhatsApp] Downloading media ID: {$mediaId}, type: {$mediaType}");
        
        try {
            // Bước 1: Lấy URL download
            $urlResult = $this->getMediaUrl($mediaId);
            
            if (!$urlResult['success']) {
                return [
                    'success' => false,
                    'path' => null,
                    'url' => null,
                    'message' => $urlResult['message']
                ];
            }
            
            $downloadUrl = $urlResult['url'];
            $mimeType = $urlResult['mime_type'] ?? null;
            
            // Bước 2: Download file
            $response = Http::withoutVerifying()
                ->timeout(120)
                ->withToken($this->token)
                ->get($downloadUrl);
            
            if (!$response->successful()) {
                Log::error("[WhatsApp] ❌ Download failed: HTTP " . $response->status());
                return [
                    'success' => false,
                    'path' => null,
                    'url' => null,
                    'message' => 'Download thất bại'
                ];
            }
            
            $fileContent = $response->body();
            
            // Bước 3: Xác định extension từ mime type
            $extension = $this->getExtensionFromMimeType($mimeType) ?? $this->getDefaultExtension($mediaType);
            
            // Bước 4: Tạo tên file và lưu
            $savePath = public_path('storage/whatsapp_media');
            if (!file_exists($savePath)) {
                mkdir($savePath, 0755, true);
            }
            
            $savedFilename = date('Ymd_His') . '_' . uniqid() . '.' . $extension;
            $fullPath = $savePath . '/' . $savedFilename;
            
            file_put_contents($fullPath, $fileContent);
            
            // URL công khai để truy cập
            $publicUrl = '/storage/whatsapp_media/' . $savedFilename;
            
            Log::info("[WhatsApp] ✅ Media downloaded and saved to: {$fullPath}");
            
            return [
                'success' => true,
                'path' => $fullPath,
                'url' => $publicUrl,
                'filename' => $savedFilename,
                'original_filename' => $filename,
                'message' => 'Download thành công'
            ];
            
        } catch (\Exception $e) {
            Log::error("[WhatsApp] Download Exception: " . $e->getMessage());
            return [
                'success' => false,
                'path' => null,
                'url' => null,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Lấy extension từ MIME type
     */
    private function getExtensionFromMimeType($mimeType)
    {
        $map = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'video/mp4' => 'mp4',
            'video/3gpp' => '3gp',
            'audio/aac' => 'aac',
            'audio/mp4' => 'm4a',
            'audio/mpeg' => 'mp3',
            'audio/amr' => 'amr',
            'audio/ogg' => 'ogg',
            'application/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/vnd.ms-excel' => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
        ];
        
        return $map[$mimeType] ?? null;
    }
    
    /**
     * Lấy extension mặc định theo loại media
     */
    private function getDefaultExtension($mediaType)
    {
        $defaults = [
            'image' => 'jpg',
            'video' => 'mp4',
            'audio' => 'ogg',
            'sticker' => 'webp',
            'document' => 'bin',
        ];
        
        return $defaults[$mediaType] ?? 'bin';
    }
}