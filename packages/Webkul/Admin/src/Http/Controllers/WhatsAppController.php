<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\WhatsAppMessage;
use App\Services\WhatsAppService;
use Webkul\Lead\Repositories\LeadRepository;
use Webkul\Activity\Repositories\ActivityRepository;

class WhatsAppController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected LeadRepository $leadRepository,
        protected ActivityRepository $activityRepository,
        protected WhatsAppService $whatsAppService
    ) {}

    /**
     * Reply to WhatsApp message from Lead view
     */
   public function reply($lead_id): JsonResponse
{
    try {
        $message = request('message');
        
        // --- SỬA ĐỔI QUAN TRỌNG: Cho phép phone_number null ---
        // Bỏ 'phone_number' => 'required' vì khung chat nhanh không có chỗ chọn số
        $validator = Validator::make(request()->all(), [
            'message' => 'required|string|min:1|max:4096',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $lead = $this->leadRepository->find($lead_id);

        if (!$lead) {
            return response()->json(['success' => false, 'message' => 'Lead không tìm thấy'], 404);
        }

        // --- LOGIC TỰ ĐỘNG LẤY SỐ ĐIỆN THOẠI ---
        // 1. Ưu tiên số gửi từ request (nếu có)
        $phoneNumber = request('phone_number');

        // 2. Nếu không có, tự lấy số đầu tiên của khách hàng
        if (empty($phoneNumber)) {
            if ($lead->person && !empty($lead->person->contact_numbers)) {
                $firstContact = collect($lead->person->contact_numbers)->first();
                $phoneNumber = is_array($firstContact) ? ($firstContact['value'] ?? '') : ($firstContact->value ?? '');
            }
        }

        // 3. Nếu vẫn không có số -> Báo lỗi
        if (empty($phoneNumber)) {
            return response()->json([
                'success' => false, 
                'message' => 'Khách hàng này chưa có số điện thoại. Vui lòng cập nhật hồ sơ trước.'
            ], 422);
        }
        
        // Chuẩn hóa số điện thoại (bỏ số 0 đầu, thêm 84...)
        $cleanPhone = preg_replace('/[^0-9]/', '', $phoneNumber);
        if (substr($cleanPhone, 0, 1) == '0') {
            $cleanPhone = '84' . substr($cleanPhone, 1);
        }

        Log::info("[WhatsApp] Sending to {$cleanPhone}: {$message}");

        // Gửi tin nhắn qua Service
        $result = $this->whatsAppService->sendTextMessage($cleanPhone, $message);

        if ($result['success'] ?? false) {
            // Lưu vào bảng WhatsAppMessage (Lịch sử chat)
            WhatsAppMessage::create([
                'lead_id'      => $lead->id,
                'person_id'    => $lead->person_id,
                'message'      => $message,
                'direction'    => 'outgoing',
                'phone_number' => $cleanPhone,
                'message_id'   => $result['message_id'] ?? null,
                'status'       => 'sent',
                'user_id'      => auth()->id(),
            ]);

            // Tạo Activity (Để hiện trên Timeline)
            $activity = $this->activityRepository->create([
                'title'   => 'Gửi WhatsApp (Thủ công)',
                'type'    => 'whatsapp',
                'comment' => $message,
                'user_id' => auth()->id(),
                'is_done' => 1,
            ]);

            // Gắn quan hệ cho Activity
            if ($activity) {
                if (method_exists($activity, 'leads')) {
                    $activity->leads()->syncWithoutDetaching([$lead->id]);
                }
                if (method_exists($activity, 'persons')) {
                    $activity->persons()->syncWithoutDetaching([$lead->person_id]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Tin nhắn đã gửi thành công',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Lỗi từ WhatsApp: ' . ($result['message'] ?? 'Không rõ'),
        ], 400);

    } catch (\Exception $e) {
        Log::error('[WhatsApp] Error: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()], 500);
    }
}
}
