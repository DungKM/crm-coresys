<?php

return [
    'datagrid' => [
        'id'              => 'ID',
        'customer-name'   => 'Customer',
        'customer-phone'  => 'Phone',
        'start-at'        => 'Time',
        'meeting-type'    => 'Type',
        'service'         => 'Service',
        'assigned-to'     => 'Assigned To',
        'status'          => 'Status',
        'edit'            => 'Edit',
        'delete'          => 'Delete',
        'mass-delete'     => 'Delete',
        'mass-update-status' => 'Update Status',
    ],

    'statuses' => [
        'scheduled' => 'Scheduled',
        'confirmed' => 'Confirmed',
        'cancelled' => 'Cancelled',
        'completed' => 'Completed',
        'no-show'   => 'No Show',
    ],

    'meeting-types' => [
        'call'   => 'Call',
        'onsite' => 'On-site',
        'online' => 'Online',
    ],

    'layouts' => [
        'appointments' => 'Appointments',
    ],
];
