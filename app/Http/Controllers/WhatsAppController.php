<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Webkul\Lead\Repositories\LeadRepository;
use Webkul\Contact\Repositories\PersonRepository;
use Webkul\Activity\Repositories\ActivityRepository;

class WhatsAppController extends Controller
{
    protected $leadRepository;
    protected $personRepository;
    protected $activityRepository;

    // Mã bảo mật webhook (phải khớp với mã bạn điền trên Facebook)
    protected $verifyToken = 'krayin_crm_secret_123'; 

    /**
     * Gộp tất cả Repository vào 1 hàm khởi tạo duy nhất
     */
    public function __construct(
        LeadRepository $leadRepository,
        PersonRepository $personRepository,
        ActivityRepository $activityRepository
    )
    {
        $this->leadRepository = $leadRepository;
        $this->personRepository = $personRepository;
        $this->activityRepository = $activityRepository;
    }

    // ==========================================
    // WEBHOOK UNIFIED (GET hoặc POST)
    // ==========================================
    public function verifyWebhookOrHandle(Request $request)
    {
        // Nếu là GET request → Xác minh webhook
        if ($request->isMethod('GET')) {
            return $this->verifyWebhook($request);
        }
        
        // Nếu là POST request → Xử lý tin nhắn đến
        return $this->handleIncomingMessage($request);
    }

    // ==========================================
    // 1. XÁC MINH WEBHOOK (GET)
    // ==========================================
    public function verifyWebhook(Request $request)
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        Log::info("[WEBHOOK] Verification request received");
        Log::info("[WEBHOOK] Mode: {$mode}");
        Log::info("[WEBHOOK] Token received: {$token}");
        Log::info("[WEBHOOK] Expected token: {$this->verifyToken}");
        Log::info("[WEBHOOK] Challenge: {$challenge}");

        if ($mode && $token) {
            if ($mode === 'subscribe' && $token === $this->verifyToken) {
                Log::info("[WEBHOOK] ✅ Verification SUCCESS - Returning challenge");
                return response($challenge, 200)->header('Content-Type', 'text/plain');
            }
        }

        Log::error("[WEBHOOK] ❌ Verification FAILED - Returning 403 Forbidden");
        return response('Forbidden', 403);
    }

    // ==========================================
    // 2. NHẬN TIN NHẮN TỪ KHÁCH (POST)
    // ==========================================
    public function handleIncomingMessage(Request $request)
    {
        // Log dữ liệu để debug
        Log::info('[WhatsApp Webhook] ========== NEW INCOMING MESSAGE ==========');
        Log::info('[WhatsApp Webhook] Received:', $request->all());

        $data = $request->all();

        // Kiểm tra statuses (tin nhắn đã gửi thành công / đã đọc)
        if (isset($data['entry'][0]['changes'][0]['value']['statuses'])) {
            Log::info('[WhatsApp Webhook] Status update received (not a message)');
            return response('EVENT_RECEIVED', 200);
        }

        // Kiểm tra xem có tin nhắn không
        if (!isset($data['entry'][0]['changes'][0]['value']['messages'][0])) {
            Log::info('[WhatsApp Webhook] No messages in payload');
            return response('EVENT_RECEIVED', 200);
        }

        $valueData = $data['entry'][0]['changes'][0]['value'];
        $messageData = $valueData['messages'][0];
        $phoneFrom = $messageData['from']; // Số điện thoại người gửi
        $messageType = $messageData['type'] ?? 'text';
        $whatsappMsgId = $messageData['id'] ?? null;
        
        Log::info("[WhatsApp Webhook] 📨 Message from: {$phoneFrom}");
        Log::info("[WhatsApp Webhook] 📨 Message type: {$messageType}");
        Log::info("[WhatsApp Webhook] 📨 Message ID: {$whatsappMsgId}");

        // Xử lý nội dung tin nhắn theo loại
        $messageContent = $this->extractMessageContent($messageData, $valueData);
        
        if (empty($messageContent)) {
            Log::warning("[WhatsApp Webhook] ⚠️ Could not extract message content");
            return response('EVENT_RECEIVED', 200);
        }
        
        Log::info("[WhatsApp Webhook] 📝 Message content: " . substr($messageContent, 0, 200));

        // Xử lý Reply - Kiểm tra xem tin nhắn có phải là reply không
        $replyToMsgId = $messageData['context']['id'] ?? null;
        $replyTag = '';
        
        if ($replyToMsgId) {
            Log::info("[WhatsApp Webhook] ↩️ This is a REPLY to message ID: {$replyToMsgId}");
            
            // Tìm tin nhắn gốc trong database
            $originalMessageContent = null;
            
            // Strategy 1: Tìm theo whatsapp_message_id trong additional
            $originalActivity = $this->activityRepository
                ->where('type', 'whatsapp')
                ->where('additional', 'like', '%' . $replyToMsgId . '%')
                ->first();
            
            if ($originalActivity) {
                Log::info("[WhatsApp Webhook] ↩️ Found original message by wamid - Activity ID: {$originalActivity->id}");
                $originalComment = preg_replace('/\[MEDIA:[^\]]+\]/', '[Media]', $originalActivity->comment);
                $originalComment = preg_replace('/\[REPLY_TO:[^\]]+\]/', '', $originalComment);
                $originalMessageContent = trim(substr($originalComment, 0, 60));
            }
            
            // Tạo reply tag với Activity ID để frontend có thể scroll chính xác
            // Format: [REPLY_TO:activityId:content]
            if ($originalActivity) {
                $replyTag = "[REPLY_TO:{$originalActivity->id}:{$originalMessageContent}]";
            } else {
                $replyTag = "[REPLY_TO:0:Tin nhắn trước đó]";
            }
                
            $messageContent = $replyTag . $messageContent;
            Log::info("[WhatsApp Webhook] ↩️ Added reply tag: " . substr($replyTag, 0, 80));
        }

        // Xử lý logic tìm khách hàng
        $person = $this->findPersonByPhone($phoneFrom);

        if ($person) {
            Log::info("[WhatsApp Webhook] ✅ Found Person ID: {$person->id} - Name: {$person->name}");

            // Tìm Lead mới nhất của khách này để gắn activity
            $lead = $person->leads()->latest()->first();
            
            if ($lead) {
                Log::info("[WhatsApp Webhook] ✅ Found Lead ID: {$lead->id} - Title: {$lead->title}");
            } else {
                Log::warning("[WhatsApp Webhook] ⚠️ Person has no leads, activity will only be attached to person");
            }
            
            // Tạo Activity lưu tin nhắn - bao gồm whatsapp_message_id trong additional
            $activity = $this->activityRepository->create([
                'title'          => 'Tin nhắn WhatsApp đến',
                'type'           => 'whatsapp',
                'comment'        => $messageContent,
                'user_id'        => $lead ? $lead->user_id : 1,
                'is_done'        => 1,
                'additional'     => $whatsappMsgId ? json_encode(['whatsapp_message_id' => $whatsappMsgId]) : null,
            ]);

            Log::info("[WhatsApp Webhook] ✅ Created Activity ID: {$activity->id} with wamid: " . ($whatsappMsgId ?? 'none'));

            // Gắn quan hệ với Lead
            if ($lead) {
                $activity->leads()->attach($lead->id);
                Log::info("[WhatsApp Webhook] ✅ Attached Activity to Lead ID: {$lead->id}");
            }
            
            // Gắn quan hệ với Person
            $activity->persons()->attach($person->id);
            Log::info("[WhatsApp Webhook] ✅ Attached Activity to Person ID: {$person->id}");

            Log::info("[WhatsApp Webhook] ========== MESSAGE SAVED SUCCESSFULLY ==========");
        } else {
            Log::error("[WhatsApp Webhook] ❌ PERSON NOT FOUND for phone: {$phoneFrom}");
            Log::error("[WhatsApp Webhook] ❌ Message content was: " . substr($messageContent, 0, 100));
            Log::error("[WhatsApp Webhook] ❌ Please add this phone number to a Person in CRM");
            // TODO: Có thể tạo Person/Lead mới tự động hoặc lưu vào bảng tạm
        }

        return response('EVENT_RECEIVED', 200);
    }

    /**
     * Trích xuất nội dung tin nhắn theo loại
     */
    protected function extractMessageContent(array $messageData, array $valueData): string
    {
        $messageType = $messageData['type'] ?? 'text';
        
        switch ($messageType) {
            case 'text':
                return $messageData['text']['body'] ?? '';
                
            case 'image':
                return $this->processMediaMessage($messageData['image'] ?? [], 'image', $valueData);
                
            case 'video':
                return $this->processMediaMessage($messageData['video'] ?? [], 'video', $valueData);
                
            case 'audio':
                return $this->processMediaMessage($messageData['audio'] ?? [], 'audio', $valueData);
                
            case 'voice': // Voice note (audio format từ WhatsApp)
                return $this->processMediaMessage($messageData['voice'] ?? [], 'audio', $valueData);
                
            case 'document':
                return $this->processMediaMessage($messageData['document'] ?? [], 'document', $valueData);
                
            case 'sticker':
                return $this->processMediaMessage($messageData['sticker'] ?? [], 'sticker', $valueData);
                
            case 'location':
                $location = $messageData['location'] ?? [];
                $lat = $location['latitude'] ?? '';
                $lng = $location['longitude'] ?? '';
                $name = $location['name'] ?? '';
                $address = $location['address'] ?? '';
                $caption = $name ? "{$name}" : '';
                if ($address) $caption .= $caption ? " - {$address}" : $address;
                return "[MEDIA:location:{$lat},{$lng}]" . ($caption ? " {$caption}" : '');
                
            case 'contacts':
                $contacts = $messageData['contacts'] ?? [];
                $contactInfo = [];
                foreach ($contacts as $contact) {
                    $name = $contact['name']['formatted_name'] ?? 'Unknown';
                    $phones = collect($contact['phones'] ?? [])->pluck('phone')->implode(', ');
                    $contactInfo[] = "{$name}: {$phones}";
                }
                return "📇 Liên hệ được chia sẻ:\n" . implode("\n", $contactInfo);
                
            case 'button':
                return $messageData['button']['text'] ?? '[Button click]';
                
            case 'interactive':
                $interactive = $messageData['interactive'] ?? [];
                if (isset($interactive['button_reply'])) {
                    return $interactive['button_reply']['title'] ?? '[Button reply]';
                }
                if (isset($interactive['list_reply'])) {
                    return $interactive['list_reply']['title'] ?? '[List selection]';
                }
                return '[Interactive response]';
                
            case 'reaction':
                $reaction = $messageData['reaction'] ?? [];
                $emoji = $reaction['emoji'] ?? '';
                return "👍 Phản ứng: {$emoji}";
                
            default:
                Log::warning("[WhatsApp] Unknown message type: {$messageType}");
                return "[Tin nhắn loại: {$messageType}]";
        }
    }

    /**
     * Xử lý tin nhắn media (image, video, audio, document, sticker)
     */
    protected function processMediaMessage(array $mediaData, string $mediaType, array $valueData): string
    {
        $mediaId = $mediaData['id'] ?? null;
        $mimeType = $mediaData['mime_type'] ?? '';
        $caption = $mediaData['caption'] ?? '';
        $filename = $mediaData['filename'] ?? '';
        
        if (!$mediaId) {
            Log::warning("[WhatsApp] No media ID found for {$mediaType}");
            return "[{$mediaType}: không thể tải]";
        }
        
        // Download media từ WhatsApp
        $mediaUrl = $this->downloadAndSaveMedia($mediaId, $mediaType, $mimeType, $filename);
        
        if ($mediaUrl) {
            $filenameTag = $filename ? ":{$filename}" : '';
            $result = "[MEDIA:{$mediaType}:{$mediaUrl}{$filenameTag}]";
            if ($caption) {
                $result .= " {$caption}";
            }
            return $result;
        }
        
        return "[{$mediaType}: không thể tải]" . ($caption ? " {$caption}" : '');
    }

    /**
     * Tải và lưu media từ WhatsApp
     */
    protected function downloadAndSaveMedia(string $mediaId, string $mediaType, string $mimeType, string $filename = ''): ?string
    {
        try {
            $accessToken = env('WHATSAPP_ACCESS_TOKEN');
            
            // Bước 1: Lấy URL download từ Media ID
            $mediaInfoUrl = "https://graph.facebook.com/v18.0/{$mediaId}";
            $infoResponse = Http::withToken($accessToken)->get($mediaInfoUrl);
            
            if (!$infoResponse->successful()) {
                Log::error("[WhatsApp] Failed to get media info: " . $infoResponse->body());
                return null;
            }
            
            $mediaInfo = $infoResponse->json();
            $downloadUrl = $mediaInfo['url'] ?? null;
            
            if (!$downloadUrl) {
                Log::error("[WhatsApp] No download URL in media info");
                return null;
            }
            
            // Bước 2: Download file
            $downloadResponse = Http::withToken($accessToken)->get($downloadUrl);
            
            if (!$downloadResponse->successful()) {
                Log::error("[WhatsApp] Failed to download media: " . $downloadResponse->status());
                return null;
            }
            
            // Bước 3: Lưu file
            $extension = $this->getExtensionFromMimeType($mimeType, $mediaType);
            $savedFilename = $filename ?: ('whatsapp_' . time() . '_' . uniqid() . '.' . $extension);
            
            // Đảm bảo filename có extension đúng
            if (!str_contains($savedFilename, '.')) {
                $savedFilename .= '.' . $extension;
            }
            
            $directory = 'whatsapp_media/' . date('Y/m');
            $fullPath = storage_path('app/public/' . $directory);
            
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true);
            }
            
            $filePath = $directory . '/' . $savedFilename;
            file_put_contents(storage_path('app/public/' . $filePath), $downloadResponse->body());
            
            Log::info("[WhatsApp] Saved media to: {$filePath}");
            
            // Trả về URL relative (không dùng asset() vì có thể bị sai domain)
            return '/storage/' . $filePath;
            
        } catch (\Exception $e) {
            Log::error("[WhatsApp] Error downloading media: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy extension file từ MIME type
     */
    protected function getExtensionFromMimeType(string $mimeType, string $mediaType): string
    {
        $mimeToExt = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'video/mp4' => 'mp4',
            'video/3gpp' => '3gp',
            'audio/ogg; codecs=opus' => 'ogg',
            'audio/ogg' => 'ogg',
            'audio/mpeg' => 'mp3',
            'audio/mp4' => 'm4a',
            'audio/amr' => 'amr',
            'application/pdf' => 'pdf',
            'application/vnd.ms-excel' => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        ];
        
        if (isset($mimeToExt[$mimeType])) {
            return $mimeToExt[$mimeType];
        }
        
        // Fallback dựa vào loại media
        $typeDefaults = [
            'image' => 'jpg',
            'video' => 'mp4',
            'audio' => 'ogg',
            'document' => 'pdf',
            'sticker' => 'webp',
        ];
        
        return $typeDefaults[$mediaType] ?? 'bin';
    }

    /**
     * Tìm Person theo số điện thoại (hỗ trợ nhiều định dạng)
     * Cải thiện: Sử dụng so sánh chính xác thay vì LIKE query
     */
    protected function findPersonByPhone(string $phoneFrom): ?object
    {
        Log::info("[findPersonByPhone] Searching for phone: {$phoneFrom}");
        
        // Chuẩn hóa số điện thoại gốc - chỉ giữ số
        $cleanPhoneFrom = preg_replace('/[^0-9]/', '', $phoneFrom);
        
        // Tạo các biến thể số điện thoại để tìm kiếm
        $phonesToSearch = [$cleanPhoneFrom];
        
        // Nếu bắt đầu bằng 84, thêm định dạng 0xxx
        if (str_starts_with($cleanPhoneFrom, '84')) {
            $phonesToSearch[] = '0' . substr($cleanPhoneFrom, 2);
        }
        // Nếu bắt đầu bằng 0, thêm định dạng 84xxx
        elseif (str_starts_with($cleanPhoneFrom, '0')) {
            $phonesToSearch[] = '84' . substr($cleanPhoneFrom, 1);
        }
        
        Log::info("[findPersonByPhone] Searching variants: " . implode(', ', $phonesToSearch));
        
        // Cách 1: Thử query LIKE trước (nhanh hơn)
        foreach ($phonesToSearch as $phone) {
            $person = $this->personRepository
                ->where('contact_numbers', 'like', '%"value":"' . $phone . '"%')
                ->orWhere('contact_numbers', 'like', '%"value": "' . $phone . '"%')
                ->first();
                
            if ($person) {
                Log::info("[findPersonByPhone] ✅ Found via LIKE query - Person ID: {$person->id}");
                return $person;
            }
        }
        
        // Cách 2: Fallback - duyệt qua tất cả persons (chậm hơn nhưng chính xác)
        Log::info("[findPersonByPhone] LIKE query failed, trying loop method...");
        
        $allPersons = $this->personRepository->all();
        
        foreach ($allPersons as $person) {
            if (empty($person->contact_numbers) || !is_array($person->contact_numbers)) {
                continue;
            }
            
            foreach ($person->contact_numbers as $contact) {
                $storedPhone = preg_replace('/[^0-9]/', '', $contact['value'] ?? '');
                
                if (empty($storedPhone)) continue;
                
                foreach ($phonesToSearch as $searchPhone) {
                    // So sánh chính xác
                    if ($storedPhone === $searchPhone) {
                        Log::info("[findPersonByPhone] ✅ Found via loop - Person ID: {$person->id}, matched phone: {$storedPhone}");
                        return $person;
                    }
                    
                    // So sánh suffix (cho trường hợp có/không mã vùng)
                    $minLen = min(strlen($storedPhone), strlen($searchPhone));
                    if ($minLen >= 9) { // Đảm bảo ít nhất 9 số để tránh false positive
                        if (str_ends_with($storedPhone, substr($searchPhone, -9)) ||
                            str_ends_with($searchPhone, substr($storedPhone, -9))) {
                            Log::info("[findPersonByPhone] ✅ Found via suffix match - Person ID: {$person->id}");
                            return $person;
                        }
                    }
                }
            }
        }
        
        Log::warning("[findPersonByPhone] ❌ No person found for phones: " . implode(', ', $phonesToSearch));
        return null;
    }

    // ==========================================
    // 3. GỬI TIN NHẮN CHO LEAD (từ CRM)
    // ==========================================
    public function sendToLead($leadId)
    {
        // 1. Tìm thông tin Lead
        $lead = $this->leadRepository->find($leadId);

        if (!$lead) {
            return back()->with('error', 'Không tìm thấy khách hàng này.');
        }

        // 2. Lấy số điện thoại
        $phoneModel = $lead->person->contact_numbers->first();
        
        if (!$phoneModel || empty($phoneModel->value)) {
            return back()->with('error', 'Khách hàng này chưa có số điện thoại.');
        }

        $rawPhone = $phoneModel->value;

        // 3. Xử lý định dạng số điện thoại
        $phone = preg_replace('/[^0-9]/', '', $rawPhone);
        if (substr($phone, 0, 1) == '0') {
            $phone = '84' . substr($phone, 1);
        }

        // 4. Gửi API
        $url = env('WHATSAPP_API_URL') . '/' . env('WHATSAPP_PHONE_NUMBER_ID') . '/messages';
        $token = env('WHATSAPP_ACCESS_TOKEN');

        $response = Http::withoutVerifying()
            ->withToken($token)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($url, [
                'messaging_product' => 'whatsapp',
                'to'                => $phone,
                'type'              => 'text',
                'text'              => [
                    'body' => 'Xin chào! Chúng tôi rất vui được hỗ trợ bạn.'
                ]
            ]);

        if ($response->successful()) {
            return back()->with('success', 'Đã gửi WhatsApp thành công tới: ' . $phone);
        }

        return back()->with('error', 'Lỗi gửi tin: ' . $response->body());
    }

    // ==========================================
    // 4. GIAO DIỆN CHAT - TỰ ĐỘNG CHUYỂN ĐẾN CUỘC TRÒ CHUYỆN GẦN NHẤT
    // ==========================================
    public function chatLatest()
    {
        try {
            // Tìm activity WhatsApp gần nhất
            $latestActivity = $this->activityRepository
                ->where('type', 'whatsapp')
                ->with(['leads'])
                ->orderBy('created_at', 'desc')
                ->first();
            
            if (!$latestActivity) {
                // Không có tin nhắn WhatsApp nào, redirect về trang leads
                return redirect()->route('admin.leads.index')
                    ->with('warning', 'Chưa có cuộc trò chuyện WhatsApp nào.');
            }
            
            // Lấy lead từ activity
            $lead = $latestActivity->leads->first();
            
            if (!$lead) {
                // Activity không gắn với lead nào, redirect về trang leads
                return redirect()->route('admin.leads.index')
                    ->with('warning', 'Không tìm thấy lead cho cuộc trò chuyện.');
            }
            
            // Redirect đến trang chat của lead này
            Log::info("[WhatsApp Chat] Auto-redirecting to latest conversation with Lead ID: {$lead->id}");
            return redirect()->route('admin.leads.chat.index', $lead->id);
            
        } catch (\Exception $e) {
            Log::error('[WhatsApp chatLatest] Error: ' . $e->getMessage());
            return redirect()->route('admin.leads.index')
                ->with('error', 'Lỗi khi tải cuộc trò chuyện: ' . $e->getMessage());
        }
    }
    
    // ==========================================
    // 5. GIAO DIỆN CHAT - LEAD CỤ THỂ
    // ==========================================
    public function chat($leadId)
    {
        $lead = $this->leadRepository->findOrFail($leadId);
        
        return view('admin::leads.chat', compact('lead'));
    }

    // ==========================================
    // 6. GỬI TIN TỪ GIAO DIỆN CHAT
    // ==========================================
    public function sendFromChat(Request $request, $leadId)
    {
        // Call the existing sendToLead function
        $this->sendToLead($request, $leadId);

        // Redirect back to the chat
        return redirect()->route('admin.leads.chat.index', $leadId);
    }

    // ==========================================
    // 7. REPLY TIN NHẮN TỪ AJAX (Tab WhatsApp Chat)
    // ==========================================
    public function reply(Request $request, $id)
    {
        try {
            $lead = $this->leadRepository->findOrFail($id);
            $person = $lead->person;

            if (!$person || !$person->contact_numbers || empty($person->contact_numbers)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy số điện thoại của khách hàng'
                ], 400);
            }

            $contactNumbers = is_array($person->contact_numbers) 
                ? collect($person->contact_numbers) 
                : $person->contact_numbers;
            $phone = $contactNumbers->first()->value ?? ($contactNumbers->first()['value'] ?? null);
            $phone = preg_replace('/[^0-9]/', '', $phone);
            if (substr($phone, 0, 1) === '0') {
                $phone = '84' . substr($phone, 1);
            }

            $message = $request->input('message', '');
            $file = $request->file('file');
            $replyToId = $request->input('reply_to_id');
            
            // Validate: phải có message hoặc file
            if (empty(trim($message)) && !$file) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng nhập tin nhắn hoặc chọn file'
                ], 400);
            }

            Log::info("[WhatsApp Reply] Phone: {$phone}, Message: " . substr($message, 0, 100) . ", Has file: " . ($file ? 'Yes' : 'No') . ", Reply to: " . ($replyToId ?? 'none'));

            // WhatsApp Service để gửi tin nhắn
            $whatsAppService = new \App\Services\WhatsAppService();
            $activityComment = $message;
            $sendResult = null;
            
            // Nếu có reply_to_id, tìm nội dung tin nhắn gốc và whatsapp_message_id
            $replyPrefix = '';
            $originalWhatsappMsgId = null;
            if ($replyToId) {
                $originalActivity = $this->activityRepository->find($replyToId);
                if ($originalActivity) {
                    // Lấy nội dung tin nhắn gốc
                    $originalComment = $originalActivity->comment;
                    $originalComment = preg_replace('/\[MEDIA:[^\]]+\]/', '[Media]', $originalComment);
                    $originalComment = preg_replace('/\[REPLY_TO:[^\]]+\]/', '', $originalComment);
                    $originalContent = trim(substr($originalComment, 0, 60));
                    
                    if (empty($originalContent)) {
                        $originalContent = '[Media]';
                    }
                    
                    $replyPrefix = "[REPLY_TO:{$originalContent}]";
                    
                    // Lấy whatsapp_message_id từ additional field
                    if ($originalActivity->additional) {
                        $additional = is_string($originalActivity->additional) 
                            ? json_decode($originalActivity->additional, true) 
                            : $originalActivity->additional;
                        $originalWhatsappMsgId = $additional['whatsapp_message_id'] ?? null;
                        Log::info("[WhatsApp Reply] Original activity additional: " . json_encode($originalActivity->additional));
                        Log::info("[WhatsApp Reply] Parsed additional: " . json_encode($additional));
                    } else {
                        Log::warning("[WhatsApp Reply] Original activity has NO additional field - cannot send reply context");
                    }
                    
                    Log::info("[WhatsApp Reply] Replying to Activity ID {$replyToId}, wamid: " . ($originalWhatsappMsgId ?? 'none'));
                }
            }

            // Nếu có file -> upload và gửi media
            if ($file) {
                $extension = strtolower($file->getClientOriginalExtension());
                $mimeType = $file->getMimeType();
                $filename = $file->getClientOriginalName();
                $filePath = $file->getPathname();
                
                Log::info("[WhatsApp Reply] Uploading file: {$filename}, MIME: {$mimeType}, Ext: {$extension}");

                // Kiểm tra extension được hỗ trợ
                if (!\App\Services\WhatsAppService::isSupportedExtension($extension)) {
                    return response()->json([
                        'success' => false,
                        'message' => "Định dạng file .{$extension} không được hỗ trợ"
                    ], 400);
                }

                // Upload media lên WhatsApp
                $uploadResult = $whatsAppService->uploadMedia($filePath, $mimeType);
                
                if (!$uploadResult['success']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Lỗi upload file: ' . $uploadResult['message']
                    ], 500);
                }

                $mediaId = $uploadResult['media_id'];
                $mediaType = \App\Services\WhatsAppService::getMediaType($extension);
                
                Log::info("[WhatsApp Reply] Uploaded media ID: {$mediaId}, Type: {$mediaType}");

                // Gửi tin nhắn media theo loại
                switch ($mediaType) {
                    case 'image':
                        $sendResult = $whatsAppService->sendImageMessage($phone, $mediaId, $message ?: null);
                        $activityComment = "[MEDIA:image:uploaded:{$filename}]" . ($message ? " {$message}" : '');
                        break;
                    case 'video':
                        $sendResult = $whatsAppService->sendVideoMessage($phone, $mediaId, $message ?: null);
                        $activityComment = "[MEDIA:video:uploaded:{$filename}]" . ($message ? " {$message}" : '');
                        break;
                    case 'audio':
                        $sendResult = $whatsAppService->sendAudioMessage($phone, $mediaId);
                        $activityComment = "[MEDIA:audio:uploaded:{$filename}]" . ($message ? " {$message}" : '');
                        break;
                    case 'sticker':
                        $sendResult = $whatsAppService->sendStickerMessage($phone, $mediaId);
                        $activityComment = "[MEDIA:sticker:uploaded:{$filename}]";
                        break;
                    case 'document':
                    default:
                        $sendResult = $whatsAppService->sendDocumentMessage($phone, $mediaId, $filename, $message ?: null);
                        $activityComment = "[MEDIA:document:uploaded:{$filename}]" . ($message ? " {$message}" : '');
                        break;
                }
            } else {
                // Chỉ có text message - truyền whatsapp_message_id của tin nhắn gốc nếu có
                $sendResult = $whatsAppService->sendTextMessage($phone, $message, $originalWhatsappMsgId);
            }

            if ($sendResult['success'] ?? false) {
                // Lấy whatsapp_message_id từ kết quả gửi
                $whatsappMsgId = $sendResult['whatsapp_message_id'] ?? null;
                
                // Thêm reply prefix vào activityComment nếu có
                if ($replyPrefix) {
                    $activityComment = $replyPrefix . $activityComment;
                }
                
                // Save activity với whatsapp_message_id để tracking reply
                $activity = $this->activityRepository->create([
                    'title' => 'Gửi WhatsApp (Thủ công)',
                    'type' => 'whatsapp',
                    'comment' => $activityComment,
                    'user_id' => auth()->guard('user')->id() ?? 1,
                    'is_done' => 1,
                    'additional' => $whatsappMsgId ? json_encode(['whatsapp_message_id' => $whatsappMsgId]) : null,
                ]);

                $activity->leads()->attach($lead->id);
                if ($person) {
                    $activity->persons()->attach($person->id);
                }

                return response()->json([
                    'success' => true,
                    'message' => $sendResult['message'] ?? 'Đã gửi tin nhắn thành công',
                    'activity_id' => $activity->id
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Lỗi gửi tin: ' . ($sendResult['message'] ?? 'Không rõ')
            ], 500);

        } catch (\Exception $e) {
            Log::error('[WhatsApp Reply] Error: ' . $e->getMessage());
            Log::error('[WhatsApp Reply] Trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ==========================================
    // API: LẤY TIN NHẮN MỚI (cho auto-refresh)
    // ==========================================
    public function getNewMessages(Request $request, $id)
    {
        try {
            $afterId = $request->query('after', 0);
            
            $lead = $this->leadRepository->find($id);
            if (!$lead) {
                return response()->json(['messages' => []]);
            }
            
            $newMessages = $lead->activities()
                ->where('type', 'whatsapp')
                ->where('id', '>', $afterId)
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function ($activity) {
                    return [
                        'id' => $activity->id,
                        'title' => $activity->title,
                        'comment' => $activity->comment,
                        'user_name' => $activity->user ? $activity->user->name : null,
                        'created_at' => $activity->created_at->format('H:i d/m/Y'),
                    ];
                });
            
            return response()->json(['messages' => $newMessages]);
            
        } catch (\Exception $e) {
            Log::error('[WhatsApp getNewMessages] Error: ' . $e->getMessage());
            return response()->json(['messages' => [], 'error' => $e->getMessage()]);
        }
    }

    // ==========================================
    // MESSAGE ACTIONS
    // ==========================================
    
    // Toggle pin/unpin message
    public function togglePin($id)
    {
        try {
            $activity = $this->activityRepository->find($id);
            
            if (!$activity) {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy tin nhắn'], 404);
            }
            
            $activity->is_pinned = !$activity->is_pinned;
            $activity->save();
            
            Log::info("[WhatsApp] Message {$id} pinned: " . ($activity->is_pinned ? 'yes' : 'no'));
            
            return response()->json([
                'success' => true,
                'is_pinned' => $activity->is_pinned,
                'message' => $activity->is_pinned ? 'Đã ghim tin nhắn' : 'Đã bỏ ghim tin nhắn'
            ]);
        } catch (\Exception $e) {
            Log::error('[WhatsApp togglePin] Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Toggle star/unstar message
    public function toggleStar($id)
    {
        try {
            $activity = $this->activityRepository->find($id);
            
            if (!$activity) {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy tin nhắn'], 404);
            }
            
            $activity->is_starred = !$activity->is_starred;
            $activity->save();
            
            Log::info("[WhatsApp] Message {$id} starred: " . ($activity->is_starred ? 'yes' : 'no'));
            
            return response()->json([
                'success' => true,
                'is_starred' => $activity->is_starred,
                'message' => $activity->is_starred ? 'Đã gắn sao tin nhắn' : 'Đã bỏ sao tin nhắn'
            ]);
        } catch (\Exception $e) {
            Log::error('[WhatsApp toggleStar] Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Delete message (CRM only)
    public function deleteMessage($id)
    {
        try {
            $activity = $this->activityRepository->find($id);
            
            if (!$activity) {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy tin nhắn'], 404);
            }
            
            // Detach relationships
            $activity->leads()->detach();
            $activity->persons()->detach();
            
            // Delete activity
            $activity->delete();
            
            Log::info("[WhatsApp] Message {$id} deleted");
            
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa tin nhắn'
            ]);
        } catch (\Exception $e) {
            Log::error('[WhatsApp deleteMessage] Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Get message info
    public function getMessageInfo($id)
    {
        try {
            $activity = $this->activityRepository->find($id);
            
            if (!$activity) {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy tin nhắn'], 404);
            }
            
            $lead = $activity->leads->first();
            $person = $activity->persons->first();
            
            return response()->json([
                'success' => true,
                'info' => [
                    'id' => $activity->id,
                    'type' => str_contains($activity->title, 'đến') ? 'incoming' : 'outgoing',
                    'sender' => $activity->user ? $activity->user->name : 'Khách hàng',
                    'created_at' => $activity->created_at->format('H:i d/m/Y'),
                    'lead_name' => $lead ? $lead->title : 'N/A',
                    'person_name' => $person ? $person->name : 'N/A',
                    'is_pinned' => $activity->is_pinned ?? false,
                    'is_starred' => $activity->is_starred ?? false
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('[WhatsApp getMessageInfo] Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Forward message to another lead
    public function forwardMessage(Request $request, $id)
    {
        try {
            $activity = $this->activityRepository->find($id);
            
            if (!$activity) {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy tin nhắn'], 404);
            }
            
            $targetLeadId = $request->input('target_lead_id');
            $targetLead = $this->leadRepository->find($targetLeadId);
            
            if (!$targetLead) {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy lead đích'], 404);
            }
            
            // Create new activity for target lead (copy of original)
            $newActivity = $this->activityRepository->create([
                'title' => 'Chuyển tiếp từ tin nhắn WhatsApp',
                'type' => 'whatsapp',
                'comment' => "[↪ Chuyển tiếp]\n" . $activity->comment,
                'user_id' => auth()->guard('user')->id() ?? 1,
                'is_done' => 1,
            ]);
            
            // Attach to target lead
            $newActivity->leads()->attach($targetLeadId);
            
            // Attach to person if exists
            if ($targetLead->person) {
                $newActivity->persons()->attach($targetLead->person->id);
            }
            
            Log::info("[WhatsApp] Message {$id} forwarded to lead {$targetLeadId}");
            
            return response()->json([
                'success' => true,
                'message' => 'Đã chuyển tiếp tin nhắn',
                'new_activity_id' => $newActivity->id
            ]);
        } catch (\Exception $e) {
            Log::error('[WhatsApp forwardMessage] Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ==========================================
    // CHECK FOR NEW MESSAGES (Global Notification)
    // ==========================================
    public function checkNewMessages(Request $request)
    {
        try {
            $lastCheckTime = $request->input('last_check');
            
            // Lấy tin nhắn WhatsApp đến mới hơn thời điểm kiểm tra cuối
            $query = $this->activityRepository
                ->where('type', 'whatsapp')
                ->where('title', 'like', '%đến%'); // Chỉ tin nhắn incoming
            
            if ($lastCheckTime) {
                $query->where('created_at', '>', $lastCheckTime);
            } else {
                // Lần đầu: lấy tin nhắn trong 10 giây gần nhất
                $query->where('created_at', '>', now()->subSeconds(10));
            }
            
            $newMessages = $query->orderBy('created_at', 'desc')->get();
            
            $messages = [];
            foreach ($newMessages as $msg) {
                $lead = $msg->leads->first();
                $messages[] = [
                    'id' => $msg->id,
                    'lead_id' => $lead ? $lead->id : null,
                    'lead_name' => $lead ? $lead->title : 'Khách hàng',
                    'preview' => substr(preg_replace('/\[MEDIA:[^\]]+\]/', '[Media]', $msg->comment), 0, 50),
                    'created_at' => $msg->created_at->toISOString()
                ];
            }
            
            return response()->json([
                'success' => true,
                'has_new' => count($messages) > 0,
                'count' => count($messages),
                'messages' => $messages,
                'server_time' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            Log::error('[WhatsApp checkNewMessages] Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ==========================================
    // MARK MESSAGES AS READ (Send Read Receipt)
    // ==========================================
    public function markAsRead(Request $request, $leadId)
    {
        try {
            $whatsAppService = app(\App\Services\WhatsAppService::class);
            
            // Lấy tất cả tin nhắn WhatsApp đến của lead này
            $incomingMessages = $this->activityRepository
                ->where('type', 'whatsapp')
                ->where('title', 'like', '%đến%') // Chỉ tin incoming
                ->whereHas('leads', function($q) use ($leadId) {
                    $q->where('leads.id', $leadId);
                })
                ->get();
            
            Log::info("[WhatsApp markAsRead] Found {$incomingMessages->count()} incoming messages for lead {$leadId}");
            
            $sentCount = 0;
            $skippedCount = 0;
            
            foreach ($incomingMessages as $msg) {
                // Parse additional để lấy whatsapp_message_id
                if (!$msg->additional) {
                    continue;
                }
                
                $additional = json_decode($msg->additional, true);
                if (!is_array($additional)) {
                    continue;
                }
                
                $wamid = $additional['whatsapp_message_id'] ?? null;
                
                // Bỏ qua nếu không có whatsapp_message_id
                if (!$wamid) {
                    continue;
                }
                
                // Kiểm tra xem đã gửi read receipt cho tin này chưa
                if (!empty($additional['read_receipt_sent'])) {
                    $skippedCount++;
                    continue;
                }
                
                // Gửi read receipt
                Log::info("[WhatsApp markAsRead] Sending read receipt for message {$msg->id} with wamid: {$wamid}");
                $result = $whatsAppService->sendReadReceipt($wamid);
                
                if ($result['success']) {
                    $sentCount++;
                    
                    // Cập nhật additional để đánh dấu đã gửi read receipt
                    $additional['read_receipt_sent'] = true;
                    $additional['read_receipt_sent_at'] = now()->toISOString();
                    
                    $msg->additional = json_encode($additional);
                    $msg->save();
                    
                    Log::info("[WhatsApp markAsRead] ✅ Read receipt sent for message {$msg->id}");
                } else {
                    Log::warning("[WhatsApp markAsRead] ❌ Failed for message {$msg->id}: " . ($result['message'] ?? 'Unknown'));
                }
            }
            
            Log::info("[WhatsApp] Read receipts: sent={$sentCount}, skipped={$skippedCount} for lead {$leadId}");
            
            return response()->json([
                'success' => true,
                'message' => "Đã đánh dấu đã đọc {$sentCount} tin nhắn" . ($skippedCount > 0 ? " ({$skippedCount} đã đọc trước)" : ""),
                'count' => $sentCount,
                'skipped' => $skippedCount
            ]);
        } catch (\Exception $e) {
            Log::error('[WhatsApp markAsRead] Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}