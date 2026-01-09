<?php

namespace Webkul\Appointment\Http\Controllers;

use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Appointment\Repositories\AppointmentRepository;
use Webkul\Lead\Repositories\LeadRepository;
use Webkul\Lead\Repositories\SourceRepository;
use Webkul\Product\Repositories\ProductRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Webkul\Appointment\Events\AppointmentCreated;
use Webkul\Appointment\Events\AppointmentUpdated;
use Webkul\Appointment\Events\AppointmentCancelled;
use Webkul\Appointment\Events\AppointmentRescheduled;

abstract class BaseAppointmentController extends Controller
{
    protected $appointmentRepository;
    protected $leadRepository;
    protected $sourceRepository;
    protected $productRepository;

    public function __construct(
        AppointmentRepository $appointmentRepository,
        LeadRepository $leadRepository,
        SourceRepository $sourceRepository,
        ProductRepository $productRepository
    ) {
        $this->appointmentRepository = $appointmentRepository;
        $this->leadRepository = $leadRepository;
        $this->sourceRepository = $sourceRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * Xử lý thời gian
     */
    protected function processDateTime($request): array
    {
        $timezone = $request->input('timezone', 'Asia/Ho_Chi_Minh');

        $requestedAt = null;
        if ($request->filled('requested_at')) {
            $requestedAt = Carbon::parse($request->requested_at, $timezone);
        }

        $startAt = Carbon::parse($request->start_at, $timezone);

        if ($request->filled('end_at')) {
            $endAt = Carbon::parse($request->end_at, $timezone);
            $durationMinutes = $startAt->diffInMinutes($endAt);
        } else {
            $durationMinutes = $request->input('duration_minutes', 30);
            $endAt = $startAt->copy()->addMinutes($durationMinutes);
        }

        return [
            'requested_at' => $requestedAt,
            'start_at' => $startAt,
            'end_at' => $endAt,
            'duration_minutes' => $durationMinutes,
            'timezone' => $timezone
        ];
    }

    /**
     * Validate điều kiện đặt lịch
     */
    protected function validateAppointmentConditions(array $timeData, $request, ?int $excludeAppointmentId = null): array
    {
        $requestedAt = $timeData['requested_at'];
        $startAt = $timeData['start_at'];
        $endAt = $timeData['end_at'];
        $now = \Carbon\Carbon::now($timeData['timezone']);

        // Không cho đặt lịch trong quá khứ
        if ($startAt->lte($now)) {
            return [
                'success' => false,
                'message' => 'Không thể đặt lịch hẹn trong quá khứ hoặc thời điểm hiện tại. Vui lòng chọn thời gian trong tương lai.',
                'code' => 'PAST_DATETIME',
                'http_code' => 422
            ];
        }

        // Thời gian bắt đầu phải ≥ thời gian yêu cầu (nếu có)
        if ($requestedAt && $startAt->lt($requestedAt)) {
            return [
                'success' => false,
                'message' => 'Thời gian bắt đầu phải sau hoặc bằng thời gian yêu cầu',
                'code' => 'START_BEFORE_REQUESTED',
                'http_code' => 422
            ];
        }

        // Thời gian bắt đầu phải trước thời gian kết thúc
        if ($startAt->gte($endAt)) {
            return [
                'success' => false,
                'message' => 'Thời gian bắt đầu phải trước thời gian kết thúc',
                'code' => 'INVALID_TIME_RANGE',
                'http_code' => 422
            ];
        }

        // Kiểm tra xung đột lịch của nhân viên (nếu assignment type là direct)
        if ($request->input('assignment_type') === 'direct' && $request->filled('assigned_user_id')) {
            $hasConflict = $this->checkScheduleConflict(
                $request->assigned_user_id,
                $startAt,
                $endAt,
                $excludeAppointmentId
            );

            if ($hasConflict) {
                return [
                    'success' => false,
                    'message' => 'Nhân viên đã có lịch hẹn trùng giờ',
                    'code' => 'SCHEDULE_CONFLICT',
                    'http_code' => 409
                ];
            }
        }

        return ['success' => true];
    }
    /**
     * Kiểm tra idempotency
     */
    protected function checkIdempotency($externalSource, $externalId)
    {
        if (empty($externalSource) || empty($externalId)) {
            return null;
        }

        return $this->appointmentRepository->findWhere([
            'external_source' => $externalSource,
            'external_id' => $externalId
        ])->first();
    }

    /**
     * Kiểm tra trùng lịch
     */
    protected function checkScheduleConflict($userId, $startAt, $endAt, ?int $excludeAppointmentId = null): bool
    {
        $query = $this->appointmentRepository->getModel()->newQuery()
            ->where('assigned_user_id', $userId)
            ->whereIn('status', ['scheduled', 'confirmed', 'rescheduled']);

        if ($excludeAppointmentId) {
            $query->where('id', '!=', $excludeAppointmentId);
        }

        return $query->where(function($q) use ($startAt, $endAt) {
            $q->where(function($q) use ($startAt, $endAt) {
                $q->where('start_at', '<', $endAt)
                  ->where('end_at', '>', $startAt);
            });
        })->exists();
    }

    /**
     * ✅ FIX: Tìm hoặc tạo Lead
     */
    protected function findOrCreateLead($request)
    {
        if ($request->filled('lead_id') && $request->lead_id !== 'new') {
            return $this->leadRepository->find($request->lead_id);
        }

        $email = $request->customer_email;
        $phone = $request->customer_phone;
        $name = $request->customer_name;

        try {
            $person = $email ? $this->findPersonByEmail($email) : null;

            if (!$person && $phone) {
                $person = $this->findPersonByPhone($phone);
            }

            if ($person) {
                // ✅ FIX: Truyền stdClass vào hàm update
                $this->updatePersonInfo($person, $name, $email, $phone);

                $lead = $this->leadRepository->where('person_id', $person->id)->first();

                if ($lead) {
                    return $lead;
                }

                return $this->createLeadFromPerson($person, $request);
            }

            return $this->createNewLeadWithPerson($name, $email, $phone, $request);

        } catch (\Exception $e) {
            Log::error('Failed to create/find lead: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Tìm person theo email
     */
    protected function findPersonByEmail(string $email)
    {
        if (empty($email)) {
            return null;
        }

        return DB::table('persons')
            ->whereRaw("JSON_SEARCH(emails, 'one', ?) IS NOT NULL", [$email])
            ->first();
    }

    /**
     * Tìm person theo phone
     */
    protected function findPersonByPhone(string $phone)
    {
        if (empty($phone)) {
            return null;
        }

        $normalizedPhone = preg_replace('/[^0-9]/', '', $phone);

        return DB::table('persons')
            ->whereRaw("JSON_SEARCH(contact_numbers, 'one', ?) IS NOT NULL", [$phone])
            ->orWhereRaw("JSON_SEARCH(contact_numbers, 'one', ?) IS NOT NULL", [$normalizedPhone])
            ->first();
    }

    /**
     * ✅ FIX: Update thông tin person từ stdClass
     */
    protected function updatePersonInfo($person, $name, $email, $phone)
    {
        $updates = [];

        // ✅ Parse JSON fields từ stdClass
        $existingEmails = $this->parseJsonField($person->emails ?? '');
        $existingPhones = $this->parseJsonField($person->contact_numbers ?? '');

        // ✅ Update NAME (string field)
        if ($name && trim($name) !== '') {
            // stdClass có property 'name' trực tiếp
            if (isset($person->name) && $person->name !== $name) {
                $updates['name'] = $name;
            } elseif (!isset($person->name)) {
                $updates['name'] = $name;
            }
        }

        // ✅ Update EMAIL (JSON field)
        if ($email && trim($email) !== '') {
            $emailExists = false;
            foreach ($existingEmails as $item) {
                if (isset($item['value']) && $item['value'] === $email) {
                    $emailExists = true;
                    break;
                }
            }

            if (!$emailExists) {
                $existingEmails[] = ['value' => $email, 'label' => 'work'];
                $updates['emails'] = json_encode($existingEmails);
            }
        }

        // ✅ Update PHONE (JSON field)
        if ($phone && trim($phone) !== '') {
            $phoneExists = false;
            foreach ($existingPhones as $item) {
                if (isset($item['value']) && $item['value'] === $phone) {
                    $phoneExists = true;
                    break;
                }
            }

            if (!$phoneExists) {
                $existingPhones[] = ['value' => $phone, 'label' => 'work'];
                $updates['contact_numbers'] = json_encode($existingPhones);
            }
        }

        // ✅ Execute update nếu có thay đổi
        if (!empty($updates)) {
            $updates['updated_at'] = now();

            DB::table('persons')
                ->where('id', $person->id)
                ->update($updates);

            Log::info('Person updated', [
                'person_id' => $person->id,
                'updates' => array_keys($updates)
            ]);
        }
    }

    /**
     * Parse JSON field
     */
    protected function parseJsonField($field)
    {
        if (empty($field)) {
            return [];
        }

        $decoded = is_string($field) ? json_decode($field, true) : $field;
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Extract value từ JSON field
     */
    protected function extractValue($field)
    {
        if (empty($field)) {
            return '';
        }

        $data = $this->parseJsonField($field);

        if (isset($data[0]['value'])) {
            return $data[0]['value'];
        }

        if (isset($data[0])) {
            return $data[0];
        }

        return '';
    }

    /**
     * Tạo lead từ person có sẵn
     */
    protected function createLeadFromPerson($person, $request)
    {
        $leadSourceId = $this->getValidLeadSourceId($request->input('source'));

        // ✅ Extract name từ JSON hoặc dùng string trực tiếp
        $personName = isset($person->name) ? $person->name : '';

        return $this->leadRepository->create([
            'title' => $personName ?: 'Lead từ Appointment',
            'person_id' => $person->id,
            'lead_value' => 0,
            'status' => 1,
            'lead_source_id' => $leadSourceId,
            'lead_type_id' => $this->getDefaultLeadTypeId(),
            'lead_pipeline_id' => $this->getDefaultPipelineId(),
            'lead_stage_id' => $this->getDefaultStageId(),
            'user_id' => $request->input('assigned_user_id', auth()->id()),
        ]);
    }

    /**
     * Tạo mới person và lead
     */
    protected function createNewLeadWithPerson($name, $email, $phone, $request)
    {
        $personId = DB::table('persons')->insertGetId([
            'name' => $name ?: 'Guest',
            'emails' => $email ? json_encode([['value' => $email, 'label' => 'work']]) : json_encode([]),
            'contact_numbers' => $phone ? json_encode([['value' => $phone, 'label' => 'work']]) : json_encode([]),
            'organization_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $leadSourceId = $this->getValidLeadSourceId($request->input('source'));

        return $this->leadRepository->create([
            'title' => $name ?: $email ?: $phone ?: 'Guest Lead',
            'person_id' => $personId,
            'lead_value' => 0,
            'status' => 1,
            'lead_source_id' => $leadSourceId,
            'lead_type_id' => $this->getDefaultLeadTypeId(),
            'lead_pipeline_id' => $this->getDefaultPipelineId(),
            'lead_stage_id' => $this->getDefaultStageId(),
            'user_id' => $request->input('assigned_user_id', auth()->id()),
        ]);
    }

    /**
     * Chuẩn bị dữ liệu appointment
     */
    protected function prepareAppointmentData($request, array $timeData, $lead): array
    {
        $serviceName = null;
        if ($request->filled('service_id')) {
            $product = $this->productRepository->find($request->service_id);
            $serviceName = $product ? $product->name : null;
        }

        $customerName = $this->extractValue($lead->person->name ?? '') ?: $lead->title;
        $customerEmail = $this->extractValue($lead->person->emails ?? '');
        $customerPhone = $this->extractValue($lead->person->contact_numbers ?? '');

        return [
            'lead_id' => $lead->id,
            'customer_name' => $customerName,
            'customer_phone' => $customerPhone,
            'customer_email' => $customerEmail,
            'source' => $lead->lead_source_id,
            'requested_at' => $timeData['requested_at'] ?? now(),
            'start_at' => $timeData['start_at'],
            'end_at' => $timeData['end_at'],
            'timezone' => $timeData['timezone'],
            'duration_minutes' => $timeData['duration_minutes'],
            'meeting_type' => $request->meeting_type,
            'call_phone' => $request->call_phone,
            'meeting_link' => $request->meeting_link,
            'province' => $request->province,
            'district' => $request->district,
            'ward' => $request->ward,
            'street_address' => $request->street_address,
            'service_id' => $request->service_id,
            'service_name' => $serviceName,
            'assignment_type' => $request->assignment_type ?? 'direct',
            'assigned_user_id' => $request->assigned_user_id,
            'routing_key' => $request->routing_key,
            'resource_id' => $request->resource_id,
            'organization_id' => $request->organization_id,
            'channel' => $request->input('channel', 'manual'),
            'status' => 'scheduled',
            'note' => $request->note,
            'external_source' => $request->external_source,
            'external_id' => $request->external_id,
            'utm_params' => $request->filled('utm_source')
                ? json_encode(array_filter([
                    'utm_source' => $request->utm_source,
                    'utm_campaign' => $request->utm_campaign,
                    'utm_medium' => $request->utm_medium,
                    'utm_term' => $request->utm_term,
                    'utm_content' => $request->utm_content,
                ]))
                : null,
            'created_by' => auth()->id(),
        ];
    }

    protected function getValidLeadSourceId($sourceId = null)
    {
        if ($sourceId && DB::table('lead_sources')->where('id', $sourceId)->exists()) {
            return $sourceId;
        }

        $defaultSource = DB::table('lead_sources')->first();

        if (!$defaultSource) {
            return DB::table('lead_sources')->insertGetId([
                'name' => 'Appointment',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $defaultSource->id;
    }

    protected function getDefaultLeadTypeId()
    {
        $defaultType = DB::table('lead_types')->first();

        if (!$defaultType) {
            return DB::table('lead_types')->insertGetId([
                'name' => 'General',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $defaultType->id;
    }

    protected function getDefaultPipelineId()
    {
        $pipeline = DB::table('lead_pipelines')->first();
        return $pipeline ? $pipeline->id : null;
    }

    protected function getDefaultStageId()
    {
        $stage = DB::table('lead_stages')->first();

        if (!$stage) {
            return DB::table('lead_stages')->insertGetId([
                'name' => 'New',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $stage->id;
    }

    protected function autoAssignUser($request, $lead): ?int
    {
        if ($lead && $lead->user_id) {
            return $lead->user_id;
        }

        return auth()->id();
    }

    protected function dispatchAppointmentEvents($appointment, string $eventType = 'created', array $eventData = []): void
    {
        try {
            switch ($eventType) {
                case 'created':
                    event(new AppointmentCreated($appointment));
                    break;

                case 'updated':
                    $changes = $eventData['changes'] ?? [];
                    event(new AppointmentUpdated($appointment, $changes));
                    break;

                case 'cancelled':
                    $reason = $eventData['reason'] ?? null;
                    $cancelledBy = $eventData['cancelled_by'] ?? null;
                    event(new AppointmentCancelled($appointment, $cancelledBy, $reason));
                    break;

                case 'rescheduled':
                    $oldStartAt = $eventData['old_start_at'] ?? null;
                    $oldEndAt = $eventData['old_end_at'] ?? null;
                    $reason = $eventData['reason'] ?? null;
                    event(new AppointmentRescheduled($appointment, $oldStartAt, $oldEndAt, $reason));
                    break;
            }

            Log::info('Appointment event dispatched', [
                'appointment_id' => $appointment->id,
                'event_type' => $eventType
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to dispatch appointment events: ' . $e->getMessage(), [
                'appointment_id' => $appointment->id,
                'event_type' => $eventType,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    protected function formatAppointmentResponse($appointment): array
    {
        $appointment->load(['lead.person', 'assignedUser', 'organization']);

        return [
            'appointment_id' => $appointment->id,
            'lead_id' => $appointment->lead_id,
            'customer' => [
                'name' => $appointment->customer_name,
                'phone' => $appointment->customer_phone,
                'email' => $appointment->customer_email,
            ],
            'schedule' => [
                'start_at' => $appointment->start_at->toIso8601String(),
                'end_at' => $appointment->end_at->toIso8601String(),
                'timezone' => $appointment->timezone,
                'duration_minutes' => $appointment->duration_minutes,
            ],
            'meeting' => [
                'type' => $appointment->meeting_type,
                'link' => $appointment->meeting_link,
                'call_phone' => $appointment->call_phone,
                'address' => [
                    'province' => $appointment->province,
                    'district' => $appointment->district,
                    'ward' => $appointment->ward,
                    'street_address' => $appointment->street_address,
                ],
                'service' => $appointment->service_name,
            ],
            'assignment' => [
                'type' => $appointment->assignment_type,
                'assigned_user_id' => $appointment->assigned_user_id,
                'assigned_user_name' => $appointment->assignedUser->name ?? null,
                'routing_key' => $appointment->routing_key,
                'resource_id' => $appointment->resource_id,
                'organization_id' => $appointment->organization_id,
                'organization_name' => $appointment->organization->name ?? null,
            ],
            'status' => $appointment->status,
            'note' => $appointment->note,
            'channel' => $appointment->channel,
            'created_at' => $appointment->created_at->toIso8601String(),
            'crm_url' => route('admin.appointments.edit', $appointment->id),
        ];
    }

    protected function getAvailableTimezones(): array
    {
        return [
            'Asia/Ho_Chi_Minh' => 'Việt Nam (GMT+7)',
            'Asia/Bangkok' => 'Thailand (GMT+7)',
            'Asia/Singapore' => 'Singapore (GMT+8)',
            'Asia/Jakarta' => 'Indonesia - Jakarta (GMT+7)',
            'Asia/Manila' => 'Philippines (GMT+8)',
            'Asia/Kuala_Lumpur' => 'Malaysia (GMT+8)',
            'Asia/Tokyo' => 'Japan (GMT+9)',
            'Asia/Seoul' => 'Korea (GMT+9)',
            'Asia/Shanghai' => 'China (GMT+8)',
            'Asia/Hong_Kong' => 'Hong Kong (GMT+8)',
            'America/New_York' => 'US Eastern (GMT-5)',
            'America/Los_Angeles' => 'US Pacific (GMT-8)',
            'Europe/London' => 'UK (GMT+0)',
            'Europe/Paris' => 'Central Europe (GMT+1)',
        ];
    }
}
