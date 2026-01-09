<?php

namespace Webkul\Appointment\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Webkul\Appointment\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Webkul\Appointment\Models\Appointment;

class AppointmentApiController extends AppointmentController
{
    /**
     * API: Tạo appointment CHO KHÁCH HÀNG CÓ EMAIL (tạo Lead)
     * Giống store() - tạo Lead + Appointment
     * Status: scheduled → rescheduled khi update
     */
    public function apiStore(Request $request): JsonResponse
    {
        try {
            Log::info('API Store (with Lead) Request', [
                'data' => $request->all(),
                'headers' => $request->headers->all()
            ]);

            // Tạo start_at từ date + time nếu có
            if ($request->filled('appointment_date') && $request->filled('start_time')) {
                $request->merge([
                    'start_at' => $request->appointment_date . ' ' . $request->start_time . ':00'
                ]);
            }

            // Force channel từ source
            $channel = $this->detectChannel($request);
            $request->merge(['channel' => $channel]);

            // Force request là AJAX để nhận JSON response
            $request->headers->set('X-Requested-With', 'XMLHttpRequest');

            // Validate - YÊU CẦU email hoặc lead_id
            $validator = Validator::make($request->all(), [
                'external_source' => 'required|string|max:100',
                'external_id' => 'required|string|max:255',
                'customer_name' => 'required_without:lead_id|nullable|string|max:255',
                'customer_email' => 'required_without:lead_id|nullable|email|max:255', // BẮT BUỘC khi tạo Lead
                'customer_phone' => 'nullable|string|max:20',
                'lead_id' => 'required_without:customer_email|nullable|exists:leads,id',
                'start_at' => 'required|date|after:now',
                'duration_minutes' => 'required|integer|min:15|max:480',
                'meeting_type' => 'required|in:call,onsite,online',
                'assignment_type' => 'nullable|in:direct,routing,resource',
                'assigned_user_id' => 'nullable|exists:users,id',
            ], [
                'external_source.required' => 'External source is required',
                'external_id.required' => 'External ID is required',
                'customer_name.required_without' => 'Customer name is required',
                'customer_email.required_without' => 'Customer email is required when creating new lead',
                'start_at.after' => 'Start time must be in the future',
            ]);

            if ($validator->fails()) {
                Log::warning('API Validation Failed', ['errors' => $validator->errors()->toArray()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Đảm bảo assignment_type có giá trị mặc định
            if (!$request->has('assignment_type')) {
                $request->merge(['assignment_type' => 'direct']);
            }

            // Gọi store() của parent - Sẽ tạo Lead + Appointment
            $response = parent::store($request);

            // Parse JSON response từ parent
            if ($response instanceof \Illuminate\Http\JsonResponse) {
                return $response;
            }

            // Fallback nếu không phải JSON
            return response()->json([
                'success' => true,
                'message' => 'Appointment created successfully'
            ], 201);

        } catch (\Exception $e) {
            Log::error('API Store Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error: ' . $e->getMessage(),
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * API: Tạo appointment CHO KHÁCH HÀNG MỚI KHÔNG CÓ EMAIL
     * Giống storeNewCustomer() - Chỉ tạo Appointment, status = CONFIRMED
     */
    public function apiStoreNewCustomer(Request $request): JsonResponse
    {
        try {
            Log::info('API Store New Customer (no Lead) Request', [
                'data' => $request->all(),
                'headers' => $request->headers->all()
            ]);

            // Tạo start_at từ date + time nếu có
            if ($request->filled('appointment_date') && $request->filled('start_time')) {
                $request->merge([
                    'start_at' => $request->appointment_date . ' ' . $request->start_time . ':00'
                ]);
            }

            // Force channel từ source
            $channel = $this->detectChannel($request);
            $request->merge(['channel' => $channel]);

            // Force request là AJAX
            $request->headers->set('X-Requested-With', 'XMLHttpRequest');

            // Validate - KHÔNG yêu cầu email
            $validator = Validator::make($request->all(), [
                'external_source' => 'required|string|max:100',
                'external_id' => 'required|string|max:255',
                'customer_name' => 'required|string|max:255',
                'customer_phone' => 'nullable|string|max:20',
                'customer_email' => 'nullable|email|max:255', // OPTIONAL
                'start_at' => 'required|date|after:now',
                'duration_minutes' => 'required|integer|min:15|max:480',
                'meeting_type' => 'required|in:call,onsite,online',
                'assignment_type' => 'nullable|in:direct,routing,resource',
                'assigned_user_id' => 'nullable|exists:users,id',
            ], [
                'external_source.required' => 'External source is required',
                'external_id.required' => 'External ID is required',
                'customer_name.required' => 'Customer name is required',
                'start_at.after' => 'Start time must be in the future',
            ]);

            if ($validator->fails()) {
                Log::warning('API Validation Failed', ['errors' => $validator->errors()->toArray()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Đảm bảo assignment_type có giá trị mặc định
            if (!$request->has('assignment_type')) {
                $request->merge(['assignment_type' => 'direct']);
            }

            // ✅ FORCE status = confirmed
            $request->merge(['status' => 'confirmed']);

            DB::beginTransaction();

            try {
                // Idempotency check
                if ($request->filled('external_id') && $request->filled('external_source')) {
                    Log::info('Checking idempotency', [
                        'external_source' => $request->external_source,
                        'external_id' => $request->external_id
                    ]);

                    $existing = $this->checkIdempotency($request->external_source, $request->external_id);

                    if ($existing) {
                        DB::commit();
                        Log::info('Appointment already exists', ['appointment_id' => $existing->id]);

                        return response()->json([
                            'success' => true,
                            'message' => 'Appointment already exists',
                            'data' => $this->formatAppointmentResponse($existing)
                        ], 200);
                    }
                }

                // Xử lý thời gian
                $timeData = $this->processDateTime($request);
                Log::info('Processed datetime', ['timeData' => $timeData]);

                // Validate điều kiện lịch hẹn
                $validationResult = $this->validateAppointmentConditions($timeData, $request);
                if (!$validationResult['success']) {
                    DB::rollBack();
                    Log::warning('Appointment conditions validation failed', ['validationResult' => $validationResult]);

                    return response()->json([
                        'success' => false,
                        'message' => $validationResult['message'],
                        'error_code' => $validationResult['code']
                    ], $validationResult['http_code']);
                }

                // Chuẩn bị dữ liệu appointment (KHÔNG tạo Lead)
                $appointmentData = $this->prepareAppointmentData($request, $timeData, null);

                // ✅ FORCE status = confirmed
                $appointmentData['status'] = Appointment::STATUS_CONFIRMED;

                Log::info('Prepared appointment data', ['appointmentData' => $appointmentData]);

                // Tạo appointment
                $appointment = $this->appointmentRepository->create($appointmentData);
                Log::info('Appointment created', ['appointment_id' => $appointment->id]);

                // Phân công nhân viên
                $assignedUserId = $this->handleAssignment($request, null, $appointment);
                Log::info('Assignment result', ['assigned_user_id' => $assignedUserId]);

                if ($assignedUserId) {
                    $appointment->assigned_user_id = $assignedUserId;
                    $appointment->save();
                }

                // Refresh relationships
                $appointment->load(['lead.person', 'assignedUser']);

                // Dispatch event
                event(new \Webkul\Appointment\Events\AppointmentCreated($appointment));
                Log::info('AppointmentCreated event dispatched', ['appointment_id' => $appointment->id]);

                DB::commit();
                Log::info('Transaction committed');

                return response()->json([
                    'success' => true,
                    'message' => 'Appointment created successfully (confirmed)',
                    'data' => $this->formatAppointmentResponse($appointment)
                ], 201);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('API Store New Customer Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error: ' . $e->getMessage(),
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * API: Update appointment CÓ LEAD (có email)
     * Status: scheduled → rescheduled khi update
     */
    public function apiUpdate(Request $request, $id): JsonResponse
    {
        try {
            $appointment = $this->appointmentRepository->findOrFail($id);

            if (!$appointment->canEdit()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot edit appointment in status: ' . $appointment->status
                ], 422);
            }

            // Validate CÓ lead_id
            if (!$appointment->lead_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'This appointment has no lead. Use /api/v1/appointments/{id}/new-customer endpoint instead.'
                ], 422);
            }

            // Force request là AJAX
            $request->headers->set('X-Requested-With', 'XMLHttpRequest');

            // Gọi update() của parent
            $response = parent::update($request, $id);

            // Parse JSON response
            if ($response instanceof \Illuminate\Http\JsonResponse) {
                return $response;
            }

            return response()->json([
                'success' => true,
                'message' => 'Appointment updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('API Update Error', [
                'appointment_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Update appointment KHÔNG CÓ LEAD (không email)
     * Status: LUÔN LÀ confirmed khi update
     */
    public function apiUpdateNewCustomer(Request $request, $id): JsonResponse
    {
        try {
            $appointment = $this->appointmentRepository->findOrFail($id);

            if (!$appointment->canEdit()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot edit appointment in status: ' . $appointment->status
                ], 422);
            }

            // Validate KHÔNG CÓ lead_id
            if ($appointment->lead_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'This appointment has a lead. Use /api/v1/appointments/{id} endpoint instead.'
                ], 422);
            }

            $validator = $this->validateUpdateRequest($request);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            try {
                $timeData = $this->processDateTime($request);

                $appointmentData = [];

                // Thông tin thời gian
                if ($request->filled('start_at')) {
                    $appointmentData['start_at'] = $timeData['start_at'];
                }
                if ($request->filled('end_at') || $request->filled('duration_minutes')) {
                    $appointmentData['end_at'] = $timeData['end_at'];
                }
                if ($request->filled('duration_minutes')) {
                    $appointmentData['duration_minutes'] = $request->duration_minutes;
                }

                // Thông tin lịch hẹn
                if ($request->filled('meeting_type')) {
                    $appointmentData['meeting_type'] = $request->meeting_type;

                    if ($request->meeting_type !== 'call') {
                        $appointmentData['call_phone'] = null;
                    }
                    if ($request->meeting_type !== 'online') {
                        $appointmentData['meeting_link'] = null;
                    }
                    if ($request->meeting_type !== 'onsite') {
                        $appointmentData['province'] = null;
                        $appointmentData['district'] = null;
                        $appointmentData['ward'] = null;
                        $appointmentData['street_address'] = null;
                    }
                }

                if ($request->filled('call_phone')) $appointmentData['call_phone'] = $request->call_phone;
                if ($request->filled('meeting_link')) $appointmentData['meeting_link'] = $request->meeting_link;
                if ($request->filled('province')) $appointmentData['province'] = $request->province;
                if ($request->filled('district')) $appointmentData['district'] = $request->district;
                if ($request->filled('ward')) $appointmentData['ward'] = $request->ward;
                if ($request->filled('street_address')) $appointmentData['street_address'] = $request->street_address;
                if ($request->filled('service_id')) $appointmentData['service_id'] = $request->service_id;
                if ($request->has('note')) $appointmentData['note'] = $request->note;

                // ✅ LUÔN SET status = confirmed (vì không có lead)
                $appointmentData['status'] = Appointment::STATUS_CONFIRMED;

                // Cập nhật dữ liệu
                $appointment = $this->appointmentRepository->update($appointmentData, $id);

                // Gán nhân viên nếu có
                if ($request->filled('assigned_user_id')) {
                    $appointment->assigned_user_id = $request->assigned_user_id;
                    $appointment->save();
                }

                $appointment->load(['lead.person', 'assignedUser']);

                // Dispatch event
                event(new \Webkul\Appointment\Events\AppointmentUpdated(
                    $appointment,
                    $appointmentData
                ));

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Appointment updated successfully (confirmed)',
                    'data' => $this->formatAppointmentResponse($appointment)
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('API Update New Customer Error', [
                'appointment_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get appointment by ID
     */
    public function apiShow($id): JsonResponse
    {
        try {
            $appointment = $this->appointmentRepository
                ->with(['lead.person', 'assignedUser'])
                ->find($id);

            if (!$appointment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Appointment not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $this->formatAppointmentResponse($appointment)
            ]);

        } catch (\Exception $e) {
            Log::error('API Show Error', [
                'appointment_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Update appointment status
     */
    public function apiUpdateStatus(Request $request, $id): JsonResponse
    {
        try {
            // Force request là AJAX
            $request->headers->set('X-Requested-With', 'XMLHttpRequest');

            // Gọi updateStatus() của parent
            $response = parent::updateStatus($request, $id);

            if ($response instanceof \Illuminate\Http\JsonResponse) {
                return $response;
            }

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('API Update Status Error', [
                'appointment_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Cancel appointment
     */
    public function apiCancel(Request $request, $id): JsonResponse
    {
        try {
            // Force request là AJAX
            $request->headers->set('X-Requested-With', 'XMLHttpRequest');

            // Gọi cancel() của parent
            $response = parent::cancel($request, $id);

            if ($response instanceof \Illuminate\Http\JsonResponse) {
                return $response;
            }

            return response()->json([
                'success' => true,
                'message' => 'Appointment cancelled successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('API Cancel Error', [
                'appointment_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Detect channel từ request
     */
    protected function detectChannel($request): string
    {
        $source = strtolower($request->header('X-Source', ''));

        if (str_contains($source, 'web')) return 'web';
        if (str_contains($source, 'app')) return 'app';

        return 'api';
    }
}
