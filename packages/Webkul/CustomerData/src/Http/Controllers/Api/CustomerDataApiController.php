<?php

namespace Webkul\CustomerData\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\CustomerData\Models\CustomerData;
use Webkul\CustomerData\Mail\VerifyCustomerDataMail;

class CustomerDataApiController extends Controller
{
    // API nhận data (đã clean từ module trước)
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email', 
            'phone' => 'nullable|string|max:50',
            'source' => 'required|string|max:100',
            'title' => 'nullable|string',
            'customer_type' => 'required|in:retail,business',
            'metadata' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Check trung email 
            $existing = CustomerData::where('email', $request->email)->first();
            
            if ($existing) {
                // Nếu đã convert thành Lead
                if ($existing->status === 'converted' && $existing->converted_to_lead_id) {
                    Log::info('Duplicate email - already converted to lead', [
                        'email' => $request->email,
                        'lead_id' => $existing->converted_to_lead_id
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Email này đã tồn tại trong hệ thống.',
                        'data' => [
                            'status' => 'existing',
                            'customer_data_id' => $existing->id,
                            'lead_id' => $existing->converted_to_lead_id,
                            'note' => 'Dữ liệu này đã được chuyển thành Lead trước đó.'
                        ]
                    ], 200);
                }
                
                // Nếu đang pending (chưa verify) -> Gửi lại email
                if ($existing->status === 'pending') {
                    // Tạo token mới nếu hết hạn
                    if (!$existing->isTokenValid()) {
                        $existing->generateVerifyToken();
                    }
                    
                    // Gửi lại email verify
                    try {
                        Mail::to($existing->email)->send(new VerifyCustomerDataMail($existing));
                        
                        Log::info('Resent verification email for existing pending data', [
                            'customer_data_id' => $existing->id,
                            'email' => $existing->email
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Failed to resend verification email', [
                            'customer_data_id' => $existing->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Đã gửi lại email xác thực!',
                        'data' => [
                            'status' => 'resent',
                            'customer_data_id' => $existing->id,
                            'email' => $existing->email
                        ]
                    ], 200);
                }

                // Nếu đang verified (chưa convert) -> Thông báo
                if ($existing->status === 'verified') {
                    return response()->json([
                        'success' => true,
                        'message' => 'Email đã được xác thực, đang chờ xử lý.',
                        'data' => [
                            'status' => 'verified',
                            'customer_data_id' => $existing->id
                        ]
                    ], 200);
                }

                // Nếu spam -> Tạo mới (cho cơ hội thứ 2)
                if ($existing->status === 'spam') {
                    Log::info('Email was spam - creating new record', [
                        'email' => $request->email,
                        'old_id' => $existing->id
                    ]);
                }
            }

            // Tạo mới dữ liệu người dùng (email chưa tồn tại hoặc status = spam)
            $customerData = CustomerData::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'source' => $request->source,
                'title' => $request->title,
                'customer_type' => $request->customer_type,
                'metadata' => $request->metadata,
                'status' => 'pending',
            ]);

            // Sinh token xác thực
            $customerData->generateVerifyToken();

            // Gửi email xác minh
            try {
                Mail::to($customerData->email)->send(new VerifyCustomerDataMail($customerData));
                
                Log::info('Verification email sent', [
                    'customer_data_id' => $customerData->id,
                    'email' => $customerData->email
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send verification email', [
                    'customer_data_id' => $customerData->id,
                    'error' => $e->getMessage()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Đăng ký thành công! Vui lòng kiểm tra email để xác nhận.',
                'data' => [
                    'id' => $customerData->id,
                    'email' => $customerData->email,
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('Customer data submission failed', [
                'error' => $e->getMessage(),
                'request' => $request->except(['metadata'])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại sau.'
            ], 500);
        }
    }

    // Real-time validation (AJAX check)
    public function validateCustomerData(Request $request)
    {
        $rules = [];
        $messages = [];

        // Chỉ validate field được gửi lên
        if ($request->has('email')) {
            // Không check unique, chỉ check format
            $rules['email'] = 'required|email';
            $messages['email.email'] = 'Email không đúng định dạng';
        }

        if ($request->has('phone')) {
            $rules['phone'] = 'nullable|string|max:50';
        }

        if ($request->has('name')) {
            $rules['name'] = 'required|string|max:255';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'valid' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Nếu check email, thông báo nếu đã tồn tại (không block)
        if ($request->has('email')) {
            $existing = CustomerData::where('email', $request->email)->first();
            if ($existing && $existing->status === 'converted') {
                return response()->json([
                    'valid' => true,
                    'warning' => 'Email này đã tồn tại trong hệ thống.',
                    'existing_status' => 'converted'
                ]);
            }
        }

        return response()->json([
            'valid' => true,
            'message' => 'Thông tin hợp lệ'
        ]);
    }

    // Webhook endpoint (nhận data từ form bên ngoài)
    public function webhook(Request $request)
    {
        try {
            Log::info('Webhook received', [
                'source' => $request->input('source', 'unknown'),
                'has_form_id' => $request->has('form_id')
            ]);

            // Map webhook data
            $mappedData = $this->mapWebhookData($request->all());

            // Validate mapped data có đủ thông tin không
            if (empty($mappedData['email'])) {
                Log::warning('Webhook missing required field: email', [
                    'payload' => $request->all()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Missing required field: email'
                ], 400);
            }

            // Submit data
            return $this->submit(new Request($mappedData));

        } catch (\Exception $e) {
            Log::error('Webhook processing failed', [
                'error' => $e->getMessage(),
                'payload' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Webhook processing failed'
            ], 500);
        }
    }

    // Map webhook data (hỗ trợ nhiều format)
    private function mapWebhookData(array $data)
    {
        // Typeform format
        if (isset($data['form_id']) && isset($data['answers'])) {
            return [
                'name' => $data['answers'][0]['text'] ?? '',
                'email' => $data['answers'][1]['email'] ?? '',
                'phone' => $data['answers'][2]['phone_number'] ?? null,
                'source' => 'typeform',
                'title' => $data['form_response']['definition']['title'] ?? null,
                'customer_type' => 'retail',
                'metadata' => $data,
            ];
        }

        // Google Form format
        if (isset($data['entry'])) {
            return [
                'name' => $data['entry']['name'] ?? '',
                'email' => $data['entry']['email'] ?? '',
                'phone' => $data['entry']['phone'] ?? null,
                'source' => 'google_form',
                'customer_type' => 'retail',
                'metadata' => $data,
            ];
        }

        // Default format (Zapier, Make.com, custom forms)
        return [
            'name' => $data['name'] ?? $data['full_name'] ?? '',
            'email' => $data['email'] ?? '',
            'phone' => $data['phone'] ?? $data['phone_number'] ?? null,
            'source' => $data['source'] ?? 'webhook',
            'title' => $data['title'] ?? $data['message'] ?? $data['content'] ?? null,
            'customer_type' => $data['customer_type'] ?? 'retail',
            'metadata' => $data,
        ];
    }
}