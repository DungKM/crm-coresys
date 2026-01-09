<?php

namespace Webkul\Appointment\Services;

use Webkul\Appointment\Models\Appointment;
use Webkul\Lead\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AppointmentService
{
    protected $appointmentRepository;
    protected $leadRepository;

    public function __construct()
    {
        $this->appointmentRepository = app(\Webkul\Appointment\Repositories\AppointmentRepository::class);
        $this->leadRepository = app(\Webkul\Lead\Repositories\LeadRepository::class);
    }

    /**
     * Tạo appointment cho lead đã có sẵn hoặc khách hàng mới
     */
    public function createAppointment(array $data): array
    {
        return DB::transaction(function () use ($data) {

            Log::info('Creating appointment', ['data' => $data]);

            $lead = null;

            // Tìm hoặc tạo lead
            if (!empty($data['lead_id'])) {
                $lead = $this->leadRepository->find($data['lead_id']);
                if (!$lead) {
                    throw new \Exception('Lead not found with ID: ' . $data['lead_id']);
                }
            } elseif (!empty($data['customer_name'])) {
                // Tạo lead mới từ thông tin khách hàng
                $lead = $this->leadRepository->create([
                    'title' => 'Lead from appointment: ' . $data['customer_name'],
                    'status' => 'new',
                    'lead_value' => 0,
                    'source_id' => $data['source_id'] ?? null,
                    'created_by' => auth()->id(),
                ]);

                // Tạo person cho lead
                if ($lead) {
                    $personData = [
                        'name' => $data['customer_name'],
                        'emails' => $data['customer_email'] ? [$data['customer_email']] : [],
                        'contact_numbers' => $data['customer_phone'] ? [$data['customer_phone']] : [],
                    ];

                    $person = app(\Webkul\Contact\Repositories\PersonRepository::class)->create($personData);

                    if ($person) {
                        $lead->person()->associate($person);
                        $lead->save();
                    }
                }
            }

            if ($lead) {
                $data['lead_id'] = $lead->id;
            }

            // Tạo appointment
            $appointment = $this->appointmentRepository->create($data);

            // Load relationships
            $appointment->load(['lead.person', 'assignedUser']);

            // Dispatch event
            event(new \Webkul\Appointment\Events\AppointmentCreated($appointment));

            Log::info('Appointment created successfully', [
                'appointment_id' => $appointment->id,
                'lead_id' => $lead?->id
            ]);

            return [
                'success' => true,
                'message' => 'Appointment created successfully',
                'data' => $this->formatAppointmentResponse($appointment)
            ];
        });
    }

    /**
     * Cập nhật appointment
     */
    public function updateAppointment(int $appointmentId, array $data): array
    {
        return DB::transaction(function () use ($appointmentId, $data) {

            $appointment = $this->appointmentRepository->findOrFail($appointmentId);

            Log::info('Updating appointment', [
                'appointment_id' => $appointmentId,
                'data' => $data
            ]);

            if (!$appointment->canEdit()) {
                throw new \Exception('Cannot edit appointment in status: ' . $appointment->status);
            }

            $oldStartAt = $appointment->start_at;
            $oldEndAt = $appointment->end_at;

            $timeChanged = isset($data['start_at']) && (
                $oldStartAt != $data['start_at'] ||
                (isset($data['end_at']) && $oldEndAt != $data['end_at'])
            );

            if ($timeChanged) {
                if (!$appointment->original_start_at) {
                    $data['original_start_at'] = $oldStartAt;
                }

                if ($appointment->lead_id) {
                    $data['status'] = Appointment::STATUS_RESCHEDULED;
                    $data['rescheduled_by'] = auth()->id();
                    $data['rescheduled_at'] = now();
                    $data['reschedule_reason'] = $data['reschedule_reason'] ?? 'Appointment time changed';
                } else {
                    $data['status'] = Appointment::STATUS_CONFIRMED;
                }
            }

            $appointment = $this->appointmentRepository->update($data, $appointmentId);

            if ($timeChanged && $appointment->lead_id) {
                event(new \Webkul\Appointment\Events\AppointmentRescheduled(
                    $appointment,
                    $oldStartAt,
                    $oldEndAt,
                    $data['reschedule_reason'] ?? 'Appointment time changed'
                ));
            } else {
                event(new \Webkul\Appointment\Events\AppointmentUpdated($appointment, $data));
            }

            $appointment->load(['lead.person', 'assignedUser']);

            Log::info('Appointment updated successfully', [
                'appointment_id' => $appointment->id
            ]);

            return [
                'success' => true,
                'message' => 'Appointment updated successfully',
                'data' => $this->formatAppointmentResponse($appointment)
            ];
        });
    }

    /**
     * Cập nhật trạng thái appointment
     */
    public function updateStatus(int $appointmentId, string $status, ?string $reason = null): array
    {
        return DB::transaction(function () use ($appointmentId, $status, $reason) {

            $appointment = $this->appointmentRepository->findOrFail($appointmentId);

            Log::info('Updating appointment status', [
                'appointment_id' => $appointmentId,
                'old_status' => $appointment->status,
                'new_status' => $status
            ]);

            if (!$appointment->canChangeStatus()) {
                throw new \Exception('Cannot change status of this appointment');
            }

            $availableStatuses = array_keys($appointment->getAvailableStatuses());
            if (!in_array($status, $availableStatuses)) {
                throw new \Exception('Cannot transition to status: ' . $status);
            }

            $updateData = ['status' => $status];

            if ($status === Appointment::STATUS_CANCELLED) {
                $updateData['cancellation_reason'] = $reason ?? 'Cancelled without reason';
                $updateData['cancelled_by'] = auth()->id();
                $updateData['cancelled_at'] = now();
            }

            $appointment = $this->appointmentRepository->update($updateData, $appointmentId);
            $appointment->load(['lead.person', 'assignedUser']);

            if ($status === Appointment::STATUS_CANCELLED) {
                event(new \Webkul\Appointment\Events\AppointmentCancelled(
                    $appointment,
                    auth()->id(),
                    $reason
                ));
            }

            Log::info('Appointment status updated successfully', [
                'appointment_id' => $appointment->id,
                'new_status' => $status
            ]);

            return [
                'success' => true,
                'message' => 'Status updated successfully',
                'data' => $this->formatAppointmentResponse($appointment)
            ];
        });
    }

    /**
     * Cancel appointment
     */
    public function cancelAppointment(int $appointmentId, ?string $reason = null): array
    {
        return $this->updateStatus($appointmentId, Appointment::STATUS_CANCELLED, $reason);
    }

    /**
     * Delete appointment
     */
    public function deleteAppointment(int $appointmentId): array
    {
        try {
            $appointment = $this->appointmentRepository->find($appointmentId);

            if (!$appointment) {
                throw new \Exception('Appointment not found');
            }

            $this->appointmentRepository->delete($appointmentId);

            Log::info('Appointment deleted', ['appointment_id' => $appointmentId]);

            return [
                'success' => true,
                'message' => 'Appointment deleted successfully'
            ];

        } catch (\Exception $e) {
            Log::error('Failed to delete appointment', [
                'appointment_id' => $appointmentId,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Mass update status
     */
    public function massUpdateStatus(array $appointmentIds, string $status, ?string $reason = null): array
    {
        $results = [
            'success' => true,
            'updated' => 0,
            'failed' => 0,
            'errors' => []
        ];

        DB::beginTransaction();

        try {
            foreach ($appointmentIds as $id) {
                try {
                    $appointment = $this->appointmentRepository->find($id);

                    if (!$appointment) {
                        $results['failed']++;
                        $results['errors'][] = "Appointment #{$id}: Not found";
                        continue;
                    }

                    if (!$appointment->canChangeStatus()) {
                        $results['failed']++;
                        $results['errors'][] = "Appointment #{$id}: Cannot change status";
                        continue;
                    }

                    $updateData = ['status' => $status];

                    if ($status === Appointment::STATUS_CANCELLED) {
                        $updateData['cancellation_reason'] = $reason ?? 'Mass cancellation';
                        $updateData['cancelled_by'] = auth()->id();
                        $updateData['cancelled_at'] = now();
                    }

                    $this->appointmentRepository->update($updateData, $id);
                    $results['updated']++;

                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = "Appointment #{$id}: " . $e->getMessage();
                    Log::error('Mass update failed for appointment', [
                        'appointment_id' => $id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            DB::commit();

            $results['message'] = "Updated {$results['updated']} appointments" .
                                ($results['failed'] > 0 ? ", {$results['failed']} failed" : '');

            Log::info('Mass update completed', $results);

            return $results;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Mass update transaction failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Mass delete appointments
     */
    public function massDelete(array $appointmentIds): array
    {
        $results = [
            'success' => true,
            'deleted' => 0,
            'failed' => 0,
            'errors' => []
        ];

        DB::beginTransaction();

        try {
            foreach ($appointmentIds as $id) {
                try {
                    $appointment = $this->appointmentRepository->find($id);

                    if (!$appointment) {
                        $results['failed']++;
                        $results['errors'][] = "Appointment #{$id}: Not found";
                        continue;
                    }

                    $this->appointmentRepository->delete($id);
                    $results['deleted']++;

                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = "Appointment #{$id}: " . $e->getMessage();
                    Log::error('Mass delete failed for appointment', [
                        'appointment_id' => $id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            DB::commit();

            $results['message'] = "Deleted {$results['deleted']} appointments" .
                                ($results['failed'] > 0 ? ", {$results['failed']} failed" : '');

            Log::info('Mass delete completed', $results);

            return $results;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Mass delete transaction failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Get appointment by ID
     */
    public function getAppointment(int $appointmentId): array
    {
        try {
            $appointment = $this->appointmentRepository
                ->with(['lead.person', 'assignedUser'])
                ->find($appointmentId);

            if (!$appointment) {
                throw new \Exception('Appointment not found');
            }

            return [
                'success' => true,
                'data' => $this->formatAppointmentResponse($appointment)
            ];

        } catch (\Exception $e) {
            Log::error('Failed to get appointment', [
                'appointment_id' => $appointmentId,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Get appointments list with filters
     */
    public function getAppointments(array $filters = []): array
    {
        try {
            $query = $this->appointmentRepository->with(['lead.person', 'assignedUser']);

            // Apply filters
            if (!empty($filters['status'])) {
                $query = $query->where('status', $filters['status']);
            }

            if (!empty($filters['assigned_user_id'])) {
                $query = $query->where('assigned_user_id', $filters['assigned_user_id']);
            }

            if (!empty($filters['start_date'])) {
                $query = $query->where('start_at', '>=', $filters['start_date']);
            }

            if (!empty($filters['end_date'])) {
                $query = $query->where('start_at', '<=', $filters['end_date']);
            }

            if (!empty($filters['meeting_type'])) {
                $query = $query->where('meeting_type', $filters['meeting_type']);
            }

            $appointments = $query->orderBy('start_at', 'desc')->get();

            $data = $appointments->map(function($appointment) {
                return $this->formatAppointmentResponse($appointment);
            });

            return [
                'success' => true,
                'data' => $data,
                'total' => $appointments->count()
            ];

        } catch (\Exception $e) {
            Log::error('Failed to get appointments', [
                'filters' => $filters,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Format appointment response
     */
    public function formatAppointmentResponse(Appointment $appointment): array
    {
        return [
            'id' => $appointment->id,
            'customer_name' => $appointment->lead?->person?->name ?? $appointment->customer_name ?? 'N/A',
            'customer_phone' => $appointment->lead?->person?->contact_numbers[0] ?? $appointment->customer_phone ?? '',
            'customer_email' => $appointment->lead?->person?->emails[0] ?? $appointment->customer_email ?? '',
            'lead_id' => $appointment->lead_id,
            'lead_title' => $appointment->lead?->title ?? null,
            'start_at' => $appointment->start_at?->toIso8601String(),
            'end_at' => $appointment->end_at?->toIso8601String(),
            'original_start_at' => $appointment->original_start_at?->toIso8601String(),
            'duration_minutes' => $appointment->duration_minutes,
            'timezone' => $appointment->timezone,
            'meeting_type' => $appointment->meeting_type,
            'call_phone' => $appointment->call_phone,
            'meeting_link' => $appointment->meeting_link,
            'province' => $appointment->province,
            'district' => $appointment->district,
            'ward' => $appointment->ward,
            'street_address' => $appointment->street_address,
            'service_id' => $appointment->service_id,
            'service_name' => $appointment->service?->name ?? '',
            'assigned_user_id' => $appointment->assigned_user_id,
            'assigned_user_name' => $appointment->assignedUser?->name ?? '',
            'note' => $appointment->note,
            'status' => $appointment->status,
            'cancellation_reason' => $appointment->cancellation_reason,
            'cancelled_by' => $appointment->cancelled_by,
            'cancelled_at' => $appointment->cancelled_at?->toIso8601String(),
            'reschedule_reason' => $appointment->reschedule_reason,
            'rescheduled_by' => $appointment->rescheduled_by,
            'rescheduled_at' => $appointment->rescheduled_at?->toIso8601String(),
            'channel' => $appointment->channel,
            'external_source' => $appointment->external_source,
            'external_id' => $appointment->external_id,
            'utm_source' => $appointment->utm_source,
            'utm_campaign' => $appointment->utm_campaign,
            'created_at' => $appointment->created_at?->toIso8601String(),
            'updated_at' => $appointment->updated_at?->toIso8601String(),
        ];
    }
}
