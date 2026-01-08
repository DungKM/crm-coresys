<?php

namespace Webkul\EmailExtended\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class ScheduledEmailDataGrid extends DataGrid
{
    protected $primaryColumn = 'id';
    public function prepareQueryBuilder()
    {
        $userId = auth()->guard('user')->id();

        $queryBuilder = DB::table('email_scheduled')
            ->select(
                'email_scheduled.id',
                'email_scheduled.scheduled_at',
                'email_scheduled.status',
                'email_scheduled.attempts',
                'email_scheduled.max_attempts',
                'email_scheduled.last_attempt_at',
                'email_scheduled.error_message',
                'emails.id as email_id',
                'emails.subject',
                'emails.to',
                'emails.from',
                'leads.title as lead_title',
                'persons.name as person_name'
            )
            ->leftJoin('emails', 'email_scheduled.email_id', '=', 'emails.id')
            ->leftJoin('leads', 'emails.lead_id', '=', 'leads.id')
            ->leftJoin('persons', 'emails.person_id', '=', 'persons.id')
            ->whereIn('email_scheduled.status', ['pending', 'processing', 'failed'])
            ->where('emails.user_id', $userId)
            ->orderBy('email_scheduled.scheduled_at', 'asc');
        return $queryBuilder;
    }

    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('email_extended::app.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'subject',
            'label'      => trans('email_extended::app.datagrid.subject'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'to',
            'label'      => trans('email_extended::app.datagrid.to'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => false,
            'filterable' => false,
            'closure'    => function ($row) {
                if (!$row->to) {
                    return '-';
                }
                $to = is_string($row->to) ? json_decode($row->to, true) : $row->to;
                if (is_array($to)) {
                    $emails = array_map(function($item) {
                        return is_array($item) ? ($item['email'] ?? $item) : $item;
                    }, $to);
                    return implode(', ', array_slice($emails, 0, 2)) . (count($emails) > 2 ? '...' : '');
                }
                return $to;
            },
        ]);

        $this->addColumn([
            'index'      => 'person_name',
            'label'      => trans('email_extended::app.datagrid.contact'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => false,
            'closure'    => function ($row) {
                return $row->person_name ?? $row->lead_title ?? '-';
            },
        ]);

        $this->addColumn([
            'index'      => 'scheduled_at',
            'label'      => trans('email_extended::app.datagrid.scheduled-at'),
            'type'       => 'datetime',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
                if (!$row->scheduled_at) {
                    return '-';
                }
                $scheduledAt = strtotime($row->scheduled_at);
                $now = time();
                if ($scheduledAt <= $now) {
                    return '<span class="text-danger">' . date('M d, Y h:i A', $scheduledAt) . ' (Overdue)</span>';
                }
                return date('M d, Y h:i A', $scheduledAt);
            },
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('email_extended::app.datagrid.status'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
                $badges = [
                    'pending'    => '<span class="badge badge-info">Pending</span>',
                    'processing' => '<span class="badge badge-warning">Processing</span>',
                    'sent'       => '<span class="badge badge-success">Sent</span>',
                    'cancelled'  => '<span class="badge badge-secondary">Cancelled</span>',
                    'failed'     => '<span class="badge badge-danger">Failed</span>',
                ];
                return $badges[$row->status] ?? $row->status;
            },
        ]);

        $this->addColumn([
            'index'      => 'attempts',
            'label'      => trans('email_extended::app.datagrid.attempts'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => false,
            'closure'    => function ($row) {
                $remaining = $row->max_attempts - $row->attempts;
                if ($row->attempts >= $row->max_attempts) {
                    return '<span class="text-danger">' . $row->attempts . '/' . $row->max_attempts . '</span>';
                }
                return $row->attempts . '/' . $row->max_attempts;
            },
        ]);

        $this->addColumn([
            'index'      => 'last_attempt_at',
            'label'      => trans('email_extended::app.datagrid.last-attempt'),
            'type'       => 'datetime',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => false,
            'closure'    => function ($row) {
                if (!$row->last_attempt_at) {
                    return '-';
                }
                return date('M d, Y h:i A', strtotime($row->last_attempt_at));
            },
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('email_extended::app.datagrid.view-email'),
            'method' => 'GET',
            'route'  => 'admin.mail.edit-draft',
            'icon'   => 'icon-eye',
            'index'  => 'email_id',
        ]);
        $this->addAction([
            'title'     => trans('email_extended::app.datagrid.reschedule'),
            'method'    => 'GET',
            'route'     => 'admin.mail.reschedule',
            'icon'      => 'icon-clock',
            'condition' => function ($row) {
                return in_array($row->status, ['pending', 'failed']);
            },
        ]);
        $this->addAction([
            'title'     => trans('email_extended::app.datagrid.cancel'),
            'method'    => 'DELETE',
            'route'     => 'admin.mail.cancel-scheduled',
            'icon'      => 'icon-cancel',
            'condition' => function ($row) {
                return in_array($row->status, ['pending', 'processing']);
            },
        ]);
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'title'  => trans('email_extended::app.datagrid.cancel-selected'),
            'method' => 'POST',
            'route'  => 'admin.mail.mass-cancel-scheduled',
            'icon'   => 'icon-cancel',
        ]);
        $this->addMassAction([
            'title'  => trans('email_extended::app.datagrid.delete-selected'),
            'method' => 'POST',
            'route'  => 'admin.mail.mass-delete-scheduled',
            'icon'   => 'icon-delete',
        ]);
    }
}