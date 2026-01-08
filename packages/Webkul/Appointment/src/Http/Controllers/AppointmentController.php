<?php

namespace Webkul\Appointment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Webkul\Appointment\Models\Appointment;
use Illuminate\Support\Facades\Log;

class AppointmentController extends BaseAppointmentController
{
    /**
     * Display index
     */
    public function index()
    {
        $stats = $this->appointmentRepository->getStats();
        return view('appointment::admin.appointments.index', compact('stats'));
    }

    public function getAppointments(Request $request)
    {
        $query = Appointment::with(['customer', 'assignedUser'])
            ->orderBy('start_at', 'desc');

        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $appointments = $query->get()->map(function($apt) {
            return [
                'id' => $apt->id,
                'customer_name' => $apt->customer->name ?? 'N/A',
                'customer_phone' => $apt->customer->phone ?? '',
                'customer_email' => $apt->customer->email ?? '',
                'start_at' => $apt->start_at,
                'end_at' => $apt->end_at,
                'meeting_type' => $apt->meeting_type,
                'service_name' => $apt->service->name ?? '',
                'assigned_user_name' => $apt->assignedUser->name ?? '',
                'note' => $apt->note,
                'status' => $apt->status,
                'created_at' => $apt->created_at,
            ];
        });

        return response()->json(['data' => $appointments]);
    }

    /**
     * Show form tạo lịch hẹn
     */
    public function add()
    {
        $leads = $this->leadRepository->with(['person'])->get();
        $users = app(\Webkul\User\Repositories\UserRepository::class)->all();
        $sources = $this->sourceRepository->all();
        $products = $this->productRepository->all();
        $timezones = $this->getAvailableTimezones();

        $organizations = DB::table('organizations')
            ->select('id', 'name', 'address')
            ->get();

        return view('appointment::admin.appointments.create', compact(
            'leads',
            'users',
            'sources',
            'products',
            'timezones',
            'organizations'
        ));
    }

    /**
     * Store appointment
     */
     public function store(Request $request)
    {
        if ($request->filled('appointment_date') && $request->filled('start_time')) {
            $request->merge([
                'start_at' => $request->appointment_date . ' ' . $request->start_time . ':00'
            ]);
        }

        $validator = $this->validateRequest($request);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            if ($request->filled('external_id') && $request->filled('external_source')) {
                $existing = $this->checkIdempotency(
                    $request->external_source,
                    $request->external_id
                );

                if ($existing) {
                    DB::commit();

                    if ($request->ajax()) {
                        return response()->json([
                            'success' => true,
                            'message' => 'Lịch hẹn đã tồn tại',
                            'data' => $this->formatAppointmentResponse($existing)
                        ]);
                    }

                    return redirect()->route('admin.appointments.index')
                        ->with('success', 'Lịch hẹn đã tồn tại');
                }
            }

            $timeData = $this->processDateTime($request);
            $validationResult = $this->validateAppointmentConditions($timeData, $request);

            if (!$validationResult['success']) {
                DB::rollBack();

                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $validationResult['message'],
                        'error_code' => $validationResult['code']
                    ], $validationResult['http_code']);
                }

                return redirect()->back()
                    ->withInput()
                    ->with('error', $validationResult['message']);
            }

            $lead = $this->findOrCreateLead($request);

            if (!$lead) {
                throw new \Exception('Không thể tạo hoặc tìm thấy khách hàng');
            }

            $appointmentData = $this->prepareAppointmentData($request, $timeData, $lead);
            $appointment = $this->appointmentRepository->create($appointmentData);

            $assignedUserId = $this->handleAssignment($request, $lead, $appointment);

            if ($assignedUserId) {
                $appointment->assigned_user_id = $assignedUserId;
                $appointment->save();
            }

            // ✅ Refresh appointment để load relationships trước khi dispatch event
            $appointment->load(['lead.person', 'assignedUser']);

            // ✅ Dispatch event - email sẽ được gửi từ listener
            event(new \Webkul\Appointment\Events\AppointmentCreated($appointment));

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tạo lịch hẹn thành công',
                    'data' => $this->formatAppointmentResponse($appointment)
                ], 201);
            }

            return redirect()->route('admin.appointments.index')
                ->with('success', 'Tạo lịch hẹn thành công');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Appointment creation error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi hệ thống: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }

    /**
     * Edit appointment
     */
    public function edit(int $id)
    {
        $appointment = $this->appointmentRepository->with(['lead.person'])->findOrFail($id);

        if (!$appointment->canEdit()) {
            return redirect()->route('admin.appointments.index')
                ->with('error', 'Không thể sửa lịch hẹn ở trạng thái: ' . $appointment->status);
        }

        $users = app(\Webkul\User\Repositories\UserRepository::class)->all();
        $sources = $this->sourceRepository->all();
        $products = $this->productRepository->all();
        $timezones = $this->getAvailableTimezones();

        $organizations = DB::table('organizations')
            ->select('id', 'name', 'address')
            ->get();

        $availableStatuses = $appointment->getAvailableStatuses();

        return view('appointment::admin.appointments.edit', compact(
            'appointment',
            'users',
            'sources',
            'products',
            'timezones',
            'organizations',
            'availableStatuses'
        ));
    }

    /**
     * Update appointment
     */
     public function update(Request $request, int $id)
        {
            $appointment = $this->appointmentRepository->findOrFail($id);

            if (!$appointment->canEdit()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể sửa lịch hẹn ở trạng thái: ' . $appointment->status
                ], 422);
            }

            $validator = $this->validateUpdateRequest($request);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            try {
                $timeData = $this->processDateTime($request);

                // ✅ CHỈ LẤY CÁC FIELD CÓ TRONG REQUEST
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

                    // Reset fields không liên quan
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

                if ($request->filled('call_phone')) {
                    $appointmentData['call_phone'] = $request->call_phone;
                }
                if ($request->filled('meeting_link')) {
                    $appointmentData['meeting_link'] = $request->meeting_link;
                }
                if ($request->filled('province')) {
                    $appointmentData['province'] = $request->province;
                }
                if ($request->filled('district')) {
                    $appointmentData['district'] = $request->district;
                }
                if ($request->filled('ward')) {
                    $appointmentData['ward'] = $request->ward;
                }
                if ($request->filled('street_address')) {
                    $appointmentData['street_address'] = $request->street_address;
                }

                // Thông tin dịch vụ
                if ($request->filled('service_id')) {
                    $appointmentData['service_id'] = $request->service_id;
                }

                // Ghi chú
                if ($request->has('note')) {
                    $appointmentData['note'] = $request->note;
                }

                $oldStartAt = $appointment->start_at;
                $oldEndAt = $appointment->end_at;

                $isTimeChanged = isset($appointmentData['start_at']) &&
                                ($oldStartAt->ne($appointmentData['start_at']) ||
                                (isset($appointmentData['end_at']) && $oldEndAt->ne($appointmentData['end_at'])));

                if ($isTimeChanged) {
                    if (!$appointment->original_start_at) {
                        $appointmentData['original_start_at'] = $oldStartAt;
                    }

                    $appointmentData['status'] = Appointment::STATUS_RESCHEDULED;
                    $appointmentData['rescheduled_by'] = auth()->id();
                    $appointmentData['rescheduled_at'] = now();
                    $appointmentData['reschedule_reason'] = $request->input('reason', 'Thay đổi thời gian');
                }

                $appointment = $this->appointmentRepository->update($appointmentData, $id);

                if ($request->filled('assigned_user_id')) {
                    $appointment->assigned_user_id = $request->assigned_user_id;
                    $appointment->save();
                }

                $appointment->load(['lead.person', 'assignedUser']);

                if ($isTimeChanged) {
                    event(new \Webkul\Appointment\Events\AppointmentRescheduled(
                        $appointment,
                        $oldStartAt,
                        $oldEndAt,
                        $request->input('reason', 'Thay đổi thời gian')
                    ));
                } else {
                    event(new \Webkul\Appointment\Events\AppointmentUpdated(
                        $appointment,
                        $appointmentData
                    ));
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Cập nhật lịch hẹn thành công',
                    'data' => $this->formatAppointmentResponse($appointment)
                ]);

            } catch (\Exception $e) {
                DB::rollBack();

                Log::error('Appointment update error: ' . $e->getMessage(), [
                    'appointment_id' => $id,
                    'request' => $request->all(),
                    'trace' => $e->getTraceAsString()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi hệ thống: ' . $e->getMessage()
                ], 500);
            }
        }

    /**
     * Show detail
     */
    public function show(int $id): JsonResponse
    {
        try {
            $appointment = $this->appointmentRepository
                ->with(['lead.person', 'assignedUser'])
                ->find($id);

            if (!$appointment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy lịch hẹn'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $this->formatAppointmentResponse($appointment)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update status
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $appointment = $this->appointmentRepository->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:scheduled,confirmed,rescheduled,cancelled,showed,no_show',
            'reason' => 'required_if:status,cancelled|nullable|string|max:1000'
        ], [
            'status.required' => 'Vui lòng chọn trạng thái',
            'reason.required_if' => 'Vui lòng nhập lý do hủy'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        if (!$appointment->canChangeStatus()) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể thay đổi trạng thái của lịch hẹn này'
            ], 422);
        }

        $availableStatuses = array_keys($appointment->getAvailableStatuses());
        if (!in_array($request->status, $availableStatuses)) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể chuyển sang trạng thái này'
            ], 422);
        }

        try {
            $updateData = ['status' => $request->status];

            if ($request->status === Appointment::STATUS_CANCELLED) {
                $updateData['cancellation_reason'] = $request->reason;
                $updateData['cancelled_by'] = auth()->id();
                $updateData['cancelled_at'] = now();
            }

            $appointment = $this->appointmentRepository->update($updateData, $id);

            if ($request->status === Appointment::STATUS_CANCELLED) {
                $this->dispatchAppointmentEvents($appointment, 'cancelled', [
                    'reason' => $request->reason,
                    'cancelled_by' => auth()->id(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công',
                'data' => $this->formatAppointmentResponse($appointment)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel appointment
     */
    public function cancel(Request $request, int $id)
    {
        $appointment = $this->appointmentRepository->findOrFail($id);

        if (!$appointment->canCancel()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể hủy lịch hẹn này'
                ], 422);
            }

            return redirect()->back()
                ->with('error', 'Không thể hủy lịch hẹn này');
        }

        $validator = Validator::make($request->all(), [
            'cancellation_reason' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $reason = $request->input('cancellation_reason', 'Khách hàng hủy không rõ lý do');

            $appointment = $this->appointmentRepository->update([
                'status' => Appointment::STATUS_CANCELLED,
                'cancellation_reason' => $reason,
                'cancelled_by' => auth()->id(),
                'cancelled_at' => now()
            ], $id);

            // ✅ Refresh appointment
            $appointment->load(['lead.person', 'assignedUser']);

            // ✅ Dispatch cancelled event
            event(new \Webkul\Appointment\Events\AppointmentCancelled(
                $appointment,
                auth()->id(),
                $reason
            ));

            Log::info('Appointment cancelled', [
                'appointment_id' => $appointment->id,
                'cancelled_by' => auth()->id(),
                'reason' => $reason
            ]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Hủy lịch hẹn thành công',
                    'data' => $this->formatAppointmentResponse($appointment)
                ]);
            }

            return redirect()->route('admin.appointments.index')
                ->with('success', 'Hủy lịch hẹn thành công');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Appointment cancellation error: ' . $e->getMessage(), [
                'appointment_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi hệ thống: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }

    /**
     * ✅ Validate update request (khác với create)
     */
    protected function validateUpdateRequest(Request $request)
    {
        $rules = [
            'start_at' => 'required|date',
            'duration_minutes' => 'nullable|integer|min:15|max:480',
            'meeting_type' => 'required|in:call,onsite,online',
            'call_phone' => 'required_if:meeting_type,call|nullable|string|max:20',
            'meeting_link' => 'required_if:meeting_type,online|nullable|url|max:500',
            'province' => 'required_if:meeting_type,onsite|nullable|string|max:100',
            'district' => 'required_if:meeting_type,onsite|nullable|string|max:100',
            'ward' => 'required_if:meeting_type,onsite|nullable|string|max:100',
            'street_address' => 'required_if:meeting_type,onsite|nullable|string|max:255',
            'service_id' => 'nullable|integer|exists:products,id',
            'note' => 'nullable|string|max:2000',
            'reason' => 'nullable|string|max:500',
        ];

        $messages = [
            'start_at.required' => 'Vui lòng chọn thời gian bắt đầu',
            'call_phone.required_if' => 'Vui lòng nhập số điện thoại',
            'meeting_link.required_if' => 'Link meeting là bắt buộc',
            'province.required_if' => 'Vui lòng nhập tỉnh/thành phố',
            'district.required_if' => 'Vui lòng nhập quận/huyện',
            'ward.required_if' => 'Vui lòng nhập phường/xã',
            'street_address.required_if' => 'Vui lòng nhập địa chỉ cụ thể',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    /**
     * Delete appointment (soft delete)
     */
    public function destroy(int $id)
    {
        try {
            $this->appointmentRepository->delete($id);

            return redirect()->route('admin.appointments.index')
                ->with('success', 'Xóa lịch hẹn thành công');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Mass delete
     */
    public function massDelete(Request $request)
    {
        $ids = explode(',', $request->input('indices'));

        try {
            foreach ($ids as $id) {
                $this->appointmentRepository->delete($id);
            }

            return response()->json([
                'success' => true,
                'message' => 'Xóa thành công ' . count($ids) . ' lịch hẹn'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mass update status
     */
    public function massUpdate(Request $request)
    {
        $ids = explode(',', $request->input('indices'));
        $status = $request->input('value');

        try {
            foreach ($ids as $id) {
                $appointment = $this->appointmentRepository->find($id);

                if ($appointment && $appointment->canChangeStatus()) {
                    $updateData = ['status' => $status];

                    if ($status === Appointment::STATUS_CANCELLED) {
                        $updateData['cancelled_by'] = auth()->id();
                        $updateData['cancelled_at'] = now();
                    }

                    $this->appointmentRepository->update($updateData, $id);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Datagrid
     */
    public function datagrid(Request $request)
    {
        if ($request->get('view_type') === 'calendar') {
            $startDate = $request->get('startDate');
            $endDate = $request->get('endDate');

            $appointments = DB::table('appointments')
                ->leftJoin('users as assigned', 'appointments.assigned_user_id', '=', 'assigned.id')
                ->select(
                    'appointments.id',
                    'appointments.customer_name',
                    'appointments.customer_phone',
                    'appointments.customer_email',
                    'appointments.start_at',
                    'appointments.end_at',
                    'appointments.meeting_type',
                    'appointments.call_phone',
                    'appointments.meeting_link',
                    'appointments.province',
                    'appointments.district',
                    'appointments.ward',
                    'appointments.street_address',
                    'appointments.service_id',
                    'appointments.service_name',
                    'appointments.status',
                    'appointments.note',
                    'assigned.name as assigned_user_name'
                )
                ->whereNull('appointments.deleted_at')
                ->whereBetween('appointments.start_at', [$startDate, $endDate])
                ->get();

            $records = $appointments->map(function($apt) {
                return [
                    'id' => $apt->id,
                    'start_at' => $apt->start_at,
                    'end_at' => $apt->end_at,
                    'customer_name' => $apt->customer_name,
                    'customer_phone' => $apt->customer_phone,
                    'customer_email' => $apt->customer_email,
                    'meeting_type' => $apt->meeting_type,
                    'call_phone' => $apt->call_phone,
                    'meeting_link' => $apt->meeting_link,
                    'province' => $apt->province,
                    'district' => $apt->district,
                    'ward' => $apt->ward,
                    'street_address' => $apt->street_address,
                    'service_id' => $apt->service_id,
                    'service_name' => $apt->service_name,
                    'assigned_user_name' => $apt->assigned_user_name,
                    'note' => $apt->note,
                    'status' => $apt->status,
                ];
            });

            return response()->json(['records' => $records]);
        }

        return app(\Webkul\Appointment\DataGrids\AppointmentDataGrid::class)->toJson();
    }

    /**
 * Get status history
 */
    public function getStatusHistory(Request $request)
    {
        try {
            $query = DB::table('appointment_status_histories')
                ->orderBy('created_at', 'desc')
                ->limit(50);

            $histories = $query->get();

            return response()->json([
                'success' => true,
                'data' => $histories
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to load status history: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tải lịch sử'
            ], 500);
        }
    }
    /**
     * Validate request
     */
    protected function validateRequest(Request $request)
    {
        $rules = [
            'customer_name' => 'required_without:lead_id|nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'lead_id' => 'required_without:customer_name|nullable|exists:leads,id',
            'requested_at' => 'nullable|date',
            'start_at' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'timezone' => 'nullable|string|timezone',
            'meeting_type' => 'required|in:call,onsite,online',
            'call_phone' => 'required_if:meeting_type,call|nullable|string|max:20',
            'meeting_link' => 'required_if:meeting_type,online|nullable|url|max:500',
            'province' => 'required_if:meeting_type,onsite|nullable|string|max:100',
            'district' => 'required_if:meeting_type,onsite|nullable|string|max:100',
            'ward' => 'required_if:meeting_type,onsite|nullable|string|max:100',
            'street_address' => 'required_if:meeting_type,onsite|nullable|string|max:255',
            'service_id' => 'nullable|integer|exists:products,id',
            'note' => 'nullable|string|max:2000',
            'assignment_type' => 'required|in:direct,routing,resource',
            'assigned_user_id' => 'required_if:assignment_type,direct|nullable|exists:users,id',
            'routing_key' => 'required_if:assignment_type,routing|nullable|string|max:255',
            'resource_id' => 'required_if:assignment_type,resource|nullable|string|max:255',
            'channel' => 'nullable|in:manual,web,app,api',
            'external_source' => 'nullable|string|max:100',
            'external_id' => 'nullable|string|max:255',
            'utm_source' => 'nullable|string|max:100',
            'utm_campaign' => 'nullable|string|max:100',
        ];

        $messages = [
            'customer_name.required_without' => 'Vui lòng chọn khách hàng hoặc nhập tên',
            'lead_id.required_without' => 'Vui lòng chọn khách hàng',
            'lead_id.exists' => 'Khách hàng không tồn tại',
            'start_at.after' => 'Thời gian hẹn phải sau thời điểm hiện tại',
            'call_phone.required_if' => 'Vui lòng nhập số điện thoại liên hệ',
            'meeting_link.required_if' => 'Link meeting là bắt buộc khi chọn Online Meeting',
            'province.required_if' => 'Vui lòng nhập tỉnh/thành phố',
            'district.required_if' => 'Vui lòng nhập quận/huyện',
            'ward.required_if' => 'Vui lòng nhập phường/xã',
            'street_address.required_if' => 'Vui lòng nhập địa chỉ cụ thể',
            'routing_key.required_if' => 'Vui lòng nhập routing key',
            'resource_id.required_if' => 'Vui lòng nhập resource ID',
            'assigned_user_id.required_if' => 'Vui lòng chọn nhân viên phụ trách',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    /**
     * Handle assignment logic
     */
    protected function handleAssignment($request, $lead, $appointment): ?int
    {
        $assignmentType = $request->input('assignment_type', 'direct');

        switch ($assignmentType) {
            case 'direct':
                return $request->input('assigned_user_id');

            case 'routing':
                return $this->handleRoutingAssignment($request);

            case 'resource':
                return $this->handleResourceAssignment($request);

            default:
                return auth()->id();
        }
    }

    /**
     * Gán theo routing key
     */
    protected function handleRoutingAssignment($request): ?int
    {
        $routingKey = $request->input('routing_key');
        // TODO: Implement routing logic
        return null;
    }

    /**
     * Gán theo resource
     */
    protected function handleResourceAssignment($request): ?int
    {
        $resourceId = $request->input('resource_id');
        // TODO: Implement resource assignment logic
        return null;
    }

    /**
     * Update time (for calendar drag/drop)
     */
    public function updateTime(Request $request)
    {
        try {
            $appointment = $this->appointmentRepository->findOrFail($request->id);

            if (!$appointment->canEdit()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể sửa lịch hẹn này'
                ], 422);
            }

            $appointment->update([
                'start_at' => $request->start_at,
                'end_at' => $request->end_at,
            ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateAttendance(Request $request, $id)
    {
        $appointment = $this->appointmentRepository->find($id);

        if (!$appointment) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy lịch hẹn'
            ], 404);
        }

        // Chỉ cho phép update khi status là confirmed
        if ($appointment->status !== 'confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ có thể cập nhật trạng thái cho lịch hẹn đã xác nhận'
            ], 400);
        }

        $newStatus = $request->input('status');

        if (!in_array($newStatus, ['showed', 'no_show'])) {
            return response()->json([
                'success' => false,
                'message' => 'Trạng thái không hợp lệ'
            ], 400);
        }

        $appointment->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công',
            'data' => $appointment
        ]);
    }
}
