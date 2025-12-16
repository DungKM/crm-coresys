<?php

namespace App\Listeners;

use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;

class SendWhatsAppWelcome
{
    protected $whatsAppService;

    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    public function handle($lead)
    {
        Log::info("=== [WHATSAPP] Event triggered for Lead ID: {$lead->id} ===");

        try {
            // 1. Refresh dữ liệu lead
            Log::info("Refreshing lead data...");
            $lead->refresh();
            
            $person = $lead->person;

            if (!$person) {
                Log::warning("Lead ID {$lead->id}: NO PERSON RECORD");
                return;
            }

            Log::info("Person found: {$person->id}");

            // 2. Lấy danh sách sđt an toàn
            $contactNumbers = collect($person->contact_numbers);
            
            if ($contactNumbers->isEmpty()) {
                Log::warning("Lead ID {$lead->id}: NO CONTACT NUMBERS");
                return;
            }

            Log::info("Contact numbers count: " . $contactNumbers->count());

            // 3. Lấy số đầu tiên và xử lý lấy value
            $firstNumber = $contactNumbers->first();
            $rawPhone = is_array($firstNumber) ? ($firstNumber['value'] ?? '') : ($firstNumber->value ?? '');

            if (empty($rawPhone)) {
                Log::warning("Lead ID {$lead->id}: PHONE VALUE IS EMPTY");
                return;
            }

            Log::info("Raw phone: {$rawPhone}");

            // 4. Chuẩn hóa số điện thoại (033 -> 8433)
            $phone = preg_replace('/[^0-9]/', '', $rawPhone);
            if (substr($phone, 0, 1) == '0') {
                $phone = '84' . substr($phone, 1);
            }

            Log::info("Formatted phone: {$phone}");

            // 5. Gửi tin nhắn TEXT (không bị rate limit 1 lần/24h)
            Log::info("Sending WhatsApp TEXT message...");
            $message = "Xin chào! Chúng tôi rất vui được hỗ trợ bạn.";
            $result = $this->whatsAppService->sendTextMessage($phone, $message);

            if ($result['success'] ?? false) {
                Log::info("=== [WHATSAPP] ✅ SEND SUCCESS for Lead ID {$lead->id} ===");
            } else {
                $errorMsg = $result['message'] ?? 'Unknown error';
                Log::error("=== [WHATSAPP] ❌ SEND FAILED for Lead ID {$lead->id}: {$errorMsg} ===");
            }

        } catch (\Exception $e) {
            Log::error("=== [WHATSAPP] EXCEPTION for Lead ID {$lead->id} ===");
            Log::error("Exception: " . $e->getMessage());
            Log::error("Trace: " . $e->getTraceAsString());
        }
    }
}