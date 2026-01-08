<?php

namespace Webkul\Appointment\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class AppointmentDataGrid extends DataGrid
{
    protected $primaryColumn = 'id';

    protected $sortOrder = 'desc';

    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('appointments')
            ->leftJoin('leads', 'appointments.lead_id', '=', 'leads.id')
            ->leftJoin('users as assigned', 'appointments.assigned_user_id', '=', 'assigned.id')
            ->leftJoin('organizations', 'appointments.organization_id', '=', 'organizations.id')
            ->select(
                'appointments.id',
                'appointments.customer_name',
                'appointments.customer_phone',
                'appointments.customer_email',
                'appointments.requested_at',
                'appointments.start_at',
                'appointments.end_at',
                'appointments.duration_minutes',
                'appointments.meeting_type',

                // ✅ THÊM CÁC TRƯỜNG NÀY
                'appointments.call_phone',
                'appointments.meeting_link',
                'appointments.province',
                'appointments.district',
                'appointments.ward',
                'appointments.street_address',
                'appointments.service_id',
                'appointments.timezone',

                'appointments.service_name',
                'appointments.status',
                'appointments.assignment_type',
                'appointments.routing_key',
                'appointments.resource_id',
                'appointments.channel',
                'appointments.note',
                'appointments.created_at',
                'leads.title as lead_title',
                'assigned.name as assigned_user_name',
                'organizations.name as organization_name'
            )
            ->whereNull('appointments.deleted_at');

        $this->addFilter('id', 'appointments.id');
        $this->addFilter('customer_name', 'appointments.customer_name');
        $this->addFilter('customer_phone', 'appointments.customer_phone');
        $this->addFilter('customer_email', 'appointments.customer_email');
        $this->addFilter('status', 'appointments.status');
        $this->addFilter('meeting_type', 'appointments.meeting_type');
        $this->addFilter('assignment_type', 'appointments.assignment_type');
        $this->addFilter('channel', 'appointments.channel');
        $this->addFilter('start_at', 'appointments.start_at');

        return $queryBuilder;
    }


    /**
     * Add columns.
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => 'ID',
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'customer_name',
            'label'      => 'Tên khách hàng',
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'customer_phone',
            'label'      => 'Số điện thoại',
            'type'       => 'string',
            'searchable' => true,
            'filterable' => false,
            'sortable'   => false,
        ]);

        $this->addColumn([
            'index'      => 'customer_email',
            'label'      => 'Email',
            'type'       => 'string',
            'searchable' => true,
            'filterable' => false,
            'sortable'   => false,
        ]);

        $this->addColumn([
            'index'      => 'requested_at',
            'label'      => 'Ngày yêu cầu',
            'type'       => 'datetime',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => fn ($row) => $row->requested_at
                ? \Carbon\Carbon::parse($row->requested_at)->format('d/m/Y H:i')
                : '-',
        ]);

        $this->addColumn([
            'index'      => 'start_at',
            'label'      => 'Thời gian bắt đầu',
            'type'       => 'datetime',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => fn ($row) => \Carbon\Carbon::parse($row->start_at)->format('d/m/Y H:i'),
        ]);

        $this->addColumn([
            'index'      => 'end_at',
            'label'      => 'Thời gian kết thúc',
            'type'       => 'datetime',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => false,
            'closure'    => fn ($row) => \Carbon\Carbon::parse($row->end_at)->format('d/m/Y H:i'),
        ]);

        $this->addColumn([
            'index'      => 'duration_minutes',
            'label'      => 'Thời lượng',
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => true,
            'closure'    => fn ($row) => $row->duration_minutes . ' phút',
        ]);

        $this->addColumn([
            'index'      => 'meeting_type',
            'label'      => 'Loại cuộc họp',
            'type'       => 'string',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($row) {
                $types = [
                    'call'   => '📞 Gọi điện',
                    'onsite' => '🏢 Gặp trực tiếp',
                    'online' => '💻 Online',
                ];
                return $types[$row->meeting_type] ?? $row->meeting_type;
            },
        ]);

        $this->addColumn([
            'index'      => 'service_name',
            'label'      => 'Dịch vụ',
            'type'       => 'string',
            'searchable' => true,
            'filterable' => false,
            'sortable'   => false,
        ]);

        $this->addColumn([
            'index'      => 'assigned_user_name',
            'label'      => 'Người phụ trách',
            'type'       => 'string',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'note',
            'label'      => 'Ghi chú',
            'type'       => 'string',
            'searchable' => true,
            'filterable' => false,
            'sortable'   => false,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => 'Trạng thái',
            'type'       => 'string',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($row) {
                $statuses = [
                    'scheduled'   => '<span class="label label-warning">Chờ xử lý</span>',
                    'confirmed'   => '<span class="label label-success">Đã xác nhận</span>',
                    'completed'   => '<span class="label label-info">Hoàn thành</span>',
                    'cancelled'   => '<span class="label label-danger">Đã hủy</span>',
                    'no_show'     => '<span class="label label-secondary">Không đến</span>',
                ];
                return $statuses[$row->status] ?? $row->status;
            },
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => 'Ngày tạo',
            'type'       => 'datetime',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => fn ($row) => \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i'),
        ]);
    }

    /**
     * Prepare actions.
     */
    public function prepareActions()
    {
        $this->addAction([
            'icon'   => 'icon-edit',
            'title'  => 'Sửa',
            'method' => 'GET',
            'url'    => fn ($row) => route('admin.appointments.edit', $row->id),
        ]);

        $this->addAction([
            'icon'   => 'icon-delete',
            'title'  => 'Xóa',
            'method' => 'DELETE',
            'url'    => fn ($row) => route('admin.appointments.delete', $row->id),
        ]);
    }

    /**
     * Prepare mass actions.
     */
    public function prepareMassActions()
    {
        $this->addMassAction([
            'icon'   => 'icon-delete',
            'title'  => 'Xóa đã chọn',
            'method' => 'POST',
            'url'    => route('admin.appointments.mass_delete'),
        ]);

        $this->addMassAction([
            'icon'    => 'icon-check',
            'title'   => 'Cập nhật trạng thái',
            'method'  => 'POST',
            'url'     => route('admin.appointments.mass_update'),
            'options' => [
                ['label' => 'Đã xác nhận', 'value' => 'confirmed'],
                ['label' => 'Đã hủy', 'value' => 'cancelled'],
                ['label' => 'Hoàn thành', 'value' => 'completed'],
                ['label' => 'Không đến', 'value' => 'no_show'],
            ],
        ]);
    }
}
