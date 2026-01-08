<?php

namespace Webkul\Appointment\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Appointment\Models\Appointment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppointmentRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return 'Webkul\Appointment\Models\Appointment';
    }

    /**
     * Lấy appointments sắp tới
     */
    public function getUpcoming($limit = 10)
    {
        return $this->model
            ->where('start_at', '>', now())
            ->whereIn('status', [
                Appointment::STATUS_SCHEDULED,
                Appointment::STATUS_CONFIRMED,
                Appointment::STATUS_RESCHEDULED
            ])
            ->with(['lead.person', 'assignedUser', 'organization'])
            ->orderBy('start_at', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Lấy appointments hôm nay
     */
    public function getToday()
    {
        return $this->model
            ->whereDate('start_at', today())
            ->whereIn('status', [
                Appointment::STATUS_SCHEDULED,
                Appointment::STATUS_CONFIRMED,
                Appointment::STATUS_RESCHEDULED
            ])
            ->with(['lead.person', 'assignedUser'])
            ->orderBy('start_at', 'asc')
            ->get();
    }

    /**
     * Lấy appointments theo user
     */
    public function getByUser($userId, $status = null)
    {
        $query = $this->model
            ->where('assigned_user_id', $userId)
            ->with(['lead.person', 'organization']);

        if ($status) {
            if (is_array($status)) {
                $query->whereIn('status', $status);
            } else {
                $query->where('status', $status);
            }
        }

        return $query->orderBy('start_at', 'desc')->get();
    }

    /**
     * Lấy appointments theo lead
     */
    public function getByLead($leadId)
    {
        return $this->model
            ->where('lead_id', $leadId)
            ->with(['assignedUser', 'organization'])
            ->orderBy('start_at', 'desc')
            ->get();
    }

    /**
     * Lấy appointments theo organization
     */
    public function getByOrganization($organizationId)
    {
        return $this->model
            ->where('organization_id', $organizationId)
            ->with(['lead.person', 'assignedUser'])
            ->orderBy('start_at', 'desc')
            ->get();
    }

    /**
     * Lấy appointments theo routing key
     */
    public function getByRoutingKey($routingKey)
    {
        return $this->model
            ->where('routing_key', $routingKey)
            ->with(['lead.person', 'assignedUser'])
            ->orderBy('start_at', 'desc')
            ->get();
    }

    /**
     * Lấy appointments theo resource
     */
    public function getByResource($resourceId)
    {
        return $this->model
            ->where('resource_id', $resourceId)
            ->with(['lead.person', 'assignedUser'])
            ->orderBy('start_at', 'desc')
            ->get();
    }

    /**
     * Lấy appointments theo date range
     */
    public function getByDateRange($startDate, $endDate, $filters = [])
    {
        $query = $this->model
            ->whereBetween('start_at', [$startDate, $endDate])
            ->with(['lead.person', 'assignedUser', 'organization']);

        // Apply filters
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['meeting_type'])) {
            $query->where('meeting_type', $filters['meeting_type']);
        }

        if (!empty($filters['assigned_user_id'])) {
            $query->where('assigned_user_id', $filters['assigned_user_id']);
        }

        if (!empty($filters['organization_id'])) {
            $query->where('organization_id', $filters['organization_id']);
        }

        if (!empty($filters['assignment_type'])) {
            $query->where('assignment_type', $filters['assignment_type']);
        }

        if (!empty($filters['routing_key'])) {
            $query->where('routing_key', $filters['routing_key']);
        }

        if (!empty($filters['channel'])) {
            $query->where('channel', $filters['channel']);
        }

        return $query->orderBy('start_at', 'asc')->get();
    }

    /**
     * Thống kê appointments
     */
    public function getStats($filters = [])
    {
        $query = $this->model->newQuery();

        // Apply date range filter if provided
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('start_at', [$filters['start_date'], $filters['end_date']]);
        }

        // Apply user filter if provided
        if (!empty($filters['user_id'])) {
            $query->where('assigned_user_id', $filters['user_id']);
        }

        // Apply organization filter
        if (!empty($filters['organization_id'])) {
            $query->where('organization_id', $filters['organization_id']);
        }

        return [
            'total' => (clone $query)->count(),
            'scheduled' => (clone $query)->where('status', Appointment::STATUS_SCHEDULED)->count(),
            'confirmed' => (clone $query)->where('status', Appointment::STATUS_CONFIRMED)->count(),
            'rescheduled' => (clone $query)->where('status', Appointment::STATUS_RESCHEDULED)->count(),
            'cancelled' => (clone $query)->where('status', Appointment::STATUS_CANCELLED)->count(),
            'showed' => (clone $query)->where('status', Appointment::STATUS_SHOWED)->count(),
            'no_show' => (clone $query)->where('status', Appointment::STATUS_NO_SHOW)->count(),
        ];
    }

    /**
     * Thống kê theo meeting type
     */
    public function getStatsByMeetingType($filters = [])
    {
        $query = $this->model->newQuery();

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('start_at', [$filters['start_date'], $filters['end_date']]);
        }

        return [
            'call' => (clone $query)->where('meeting_type', Appointment::MEETING_TYPE_CALL)->count(),
            'onsite' => (clone $query)->where('meeting_type', Appointment::MEETING_TYPE_ONSITE)->count(),
            'online' => (clone $query)->where('meeting_type', Appointment::MEETING_TYPE_ONLINE)->count(),
        ];
    }

    /**
     * Thống kê theo assignment type
     */
    public function getStatsByAssignmentType($filters = [])
    {
        $query = $this->model->newQuery();

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('start_at', [$filters['start_date'], $filters['end_date']]);
        }

        return [
            'direct' => (clone $query)->where('assignment_type', Appointment::ASSIGNMENT_TYPE_DIRECT)->count(),
            'routing' => (clone $query)->where('assignment_type', Appointment::ASSIGNMENT_TYPE_ROUTING)->count(),
            'resource' => (clone $query)->where('assignment_type', Appointment::ASSIGNMENT_TYPE_RESOURCE)->count(),
        ];
    }

    /**
     * Thống kê theo channel
     */
    public function getStatsByChannel($filters = [])
    {
        $query = $this->model->newQuery();

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('start_at', [$filters['start_date'], $filters['end_date']]);
        }

        return [
            'manual' => (clone $query)->where('channel', 'manual')->count(),
            'web' => (clone $query)->where('channel', 'web')->count(),
            'app' => (clone $query)->where('channel', 'app')->count(),
            'api' => (clone $query)->where('channel', 'api')->count(),
        ];
    }

    /**
     * Kiểm tra conflict
     */
    public function checkConflict($userId, $startAt, $endAt, $excludeId = null)
    {
        $query = $this->model
            ->where('assigned_user_id', $userId)
            ->whereIn('status', [
                Appointment::STATUS_SCHEDULED,
                Appointment::STATUS_CONFIRMED,
                Appointment::STATUS_RESCHEDULED
            ])
            ->where(function($q) use ($startAt, $endAt) {
                $q->whereBetween('start_at', [$startAt, $endAt])
                  ->orWhereBetween('end_at', [$startAt, $endAt])
                  ->orWhere(function($q) use ($startAt, $endAt) {
                      $q->where('start_at', '<=', $startAt)
                        ->where('end_at', '>=', $endAt);
                  });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Kiểm tra resource conflict
     */
    public function checkResourceConflict($resourceId, $startAt, $endAt, $excludeId = null)
    {
        $query = $this->model
            ->where('resource_id', $resourceId)
            ->whereIn('status', [
                Appointment::STATUS_SCHEDULED,
                Appointment::STATUS_CONFIRMED,
                Appointment::STATUS_RESCHEDULED
            ])
            ->where(function($q) use ($startAt, $endAt) {
                $q->whereBetween('start_at', [$startAt, $endAt])
                  ->orWhereBetween('end_at', [$startAt, $endAt])
                  ->orWhere(function($q) use ($startAt, $endAt) {
                      $q->where('start_at', '<=', $startAt)
                        ->where('end_at', '>=', $endAt);
                  });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Tìm appointments có thể reschedule
     */
    public function getReschedulable()
    {
        return $this->model
            ->whereIn('status', [
                Appointment::STATUS_SCHEDULED,
                Appointment::STATUS_CONFIRMED,
                Appointment::STATUS_RESCHEDULED
            ])
            ->where('start_at', '>', now())
            ->with(['lead.person', 'assignedUser'])
            ->orderBy('start_at', 'asc')
            ->get();
    }

    /**
     * Tìm appointments quá hạn chưa cập nhật trạng thái
     */
    public function getOverdue()
    {
        return $this->model
            ->whereIn('status', [
                Appointment::STATUS_SCHEDULED,
                Appointment::STATUS_CONFIRMED,
                Appointment::STATUS_RESCHEDULED
            ])
            ->where('end_at', '<', now())
            ->with(['lead.person', 'assignedUser'])
            ->orderBy('end_at', 'desc')
            ->get();
    }

    /**
     * Tự động cập nhật trạng thái appointments quá hạn
     */
    public function autoUpdateOverdueStatus()
    {
        return $this->model
            ->whereIn('status', [
                Appointment::STATUS_SCHEDULED,
                Appointment::STATUS_CONFIRMED,
                Appointment::STATUS_RESCHEDULED
            ])
            ->where('end_at', '<', now()->subHours(2))
            ->update(['status' => Appointment::STATUS_NO_SHOW]);
    }

    /**
     * Lấy appointments sắp diễn ra (trong vòng X phút)
     */
    public function getStartingSoon($minutes = 30)
    {
        $now = now();
        $soon = now()->addMinutes($minutes);

        return $this->model
            ->whereIn('status', [
                Appointment::STATUS_SCHEDULED,
                Appointment::STATUS_CONFIRMED,
                Appointment::STATUS_RESCHEDULED
            ])
            ->whereBetween('start_at', [$now, $soon])
            ->with(['lead.person', 'assignedUser'])
            ->orderBy('start_at', 'asc')
            ->get();
    }

    /**
     * Lấy appointments đã bị reschedule
     */
    public function getRescheduledAppointments($filters = [])
    {
        $query = $this->model
            ->where('status', Appointment::STATUS_RESCHEDULED)
            ->whereNotNull('original_start_at')
            ->with(['lead.person', 'assignedUser', 'rescheduledBy']);

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('rescheduled_at', [$filters['start_date'], $filters['end_date']]);
        }

        return $query->orderBy('rescheduled_at', 'desc')->get();
    }

    /**
     * Lấy appointments đã bị hủy
     */
    public function getCancelledAppointments($filters = [])
    {
        $query = $this->model
            ->where('status', Appointment::STATUS_CANCELLED)
            ->with(['lead.person', 'assignedUser', 'cancelledBy']);

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('cancelled_at', [$filters['start_date'], $filters['end_date']]);
        }

        return $query->orderBy('cancelled_at', 'desc')->get();
    }

    /**
     * Export appointments to array
     */
    public function exportToArray($filters = [])
    {
        $query = $this->model->with(['lead.person', 'assignedUser', 'organization']);

        // Apply filters
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('start_at', [$filters['start_date'], $filters['end_date']]);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['meeting_type'])) {
            $query->where('meeting_type', $filters['meeting_type']);
        }

        $appointments = $query->orderBy('start_at', 'desc')->get();

        return $appointments->map(function($apt) {
            $address = '';
            if ($apt->meeting_type === 'onsite') {
                $address = implode(', ', array_filter([
                    $apt->street_address,
                    $apt->ward,
                    $apt->district,
                    $apt->province
                ]));
            }

            return [
                'ID' => $apt->id,
                'Customer Name' => $apt->customer_name,
                'Customer Phone' => $apt->customer_phone,
                'Customer Email' => $apt->customer_email,
                'Requested At' => $apt->requested_at ? $apt->requested_at->format('Y-m-d H:i') : '-',
                'Start Time' => $apt->start_at->format('Y-m-d H:i'),
                'End Time' => $apt->end_at->format('Y-m-d H:i'),
                'Duration (minutes)' => $apt->duration_minutes,
                'Meeting Type' => $apt->meeting_type,
                'Call Phone' => $apt->call_phone,
                'Meeting Link' => $apt->meeting_link,
                'Address' => $address,
                'Service' => $apt->service_name,
                'Status' => $apt->status,
                'Assignment Type' => $apt->assignment_type,
                'Assigned To' => $apt->assignedUser?->name,
                'Routing Key' => $apt->routing_key,
                'Resource ID' => $apt->resource_id,
                'Organization' => $apt->organization?->name,
                'Channel' => $apt->channel,
                'Note' => $apt->note,
                'Created At' => $apt->created_at->format('Y-m-d H:i'),
            ];
        })->toArray();
    }
}
