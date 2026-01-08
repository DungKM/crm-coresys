<?php

namespace Webkul\Appointment\Http\Controllers\Api;

use Illuminate\Http\Request;
use Webkul\Appointment\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AppointmentApiController extends AppointmentController
{
    /**
     * API: Tạo appointment từ external system
     */
    public function apiStore(Request $request)
    {
        try {
            // Log request để debug
            Log::info('API Store Request', [
                'data' => $request->all(),
                'headers' => $request->headers->all()
            ]);

            // Force channel từ source
            $channel = $this->detectChannel($request);
            $request->merge(['channel' => $channel]);

            // Validate external tracking BẮT BUỘC cho API
            $validator = \Validator::make($request->all(), [
                'external_source' => 'required|string|max:100',
                'external_id' => 'required|string|max:255',
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'nullable|email',
                'customer_phone' => 'nullable|string|max:20',

                // Thời gian
                'start_at' => 'required|date|after:now',
                'duration_minutes' => 'required|integer|min:15|max:480',

                // Meeting type
                'meeting_type' => 'required|in:call,onsite,online',
            ], [
                'external_source.required' => 'External source is required',
                'external_id.required' => 'External ID is required',
                'customer_name.required' => 'Customer name is required',
                'start_at.required' => 'Start time is required',
                'start_at.after' => 'Start time must be in the future',
                'meeting_type.required' => 'Meeting type is required',
            ]);

            if ($validator->fails()) {
                Log::warning('API Validation Failed', [
                    'errors' => $validator->errors()->toArray()
                ]);

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

            // Gọi store() của parent - logic tái sử dụng
            // parent::store() sẽ return redirect hoặc json tùy request->ajax()
            // Force request là AJAX để nhận JSON response
            $request->headers->set('X-Requested-With', 'XMLHttpRequest');

            return parent::store($request);

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
     * Detect channel từ request
     */
    protected function detectChannel($request): string
    {
        $source = strtolower($request->header('X-Source', ''));

        if (str_contains($source, 'web')) return 'web';
        if (str_contains($source, 'app')) return 'app';

        return 'api';
    }

    /**
     * API: Get appointment by ID
     */
    public function apiShow($id)
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
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * API: Update appointment status
     */
    public function apiUpdateStatus(Request $request, $id)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'status' => 'required|in:scheduled,confirmed,rescheduled,cancelled,showed,no_show',
                'reason' => 'required_if:status,cancelled|nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Force request là AJAX
            $request->headers->set('X-Requested-With', 'XMLHttpRequest');

            return parent::updateStatus($request, $id);

        } catch (\Exception $e) {
            Log::error('API Update Status Error', [
                'appointment_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }
}
