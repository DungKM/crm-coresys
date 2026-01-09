<?php

return [
    [
        'key'   => 'appointments',
        'name'  => 'appointment::app.acl.appointments',
        'route' => 'admin.appointments.index',
        'sort'  => 4,

        'children' => [
            [
                'key'   => 'appointments.view',
                'name'  => 'appointment::app.acl.view',
                'route' => 'admin.appointments.index',
                'sort'  => 1,
            ],
            [
                'key'   => 'appointments.create',
                'name'  => 'appointment::app.acl.create',
                'route' => 'admin.appointments.create',
                'sort'  => 2,
            ],
            [
                'key'   => 'appointments.edit',
                'name'  => 'appointment::app.acl.edit',
                'route' => 'admin.appointments.edit',
                'sort'  => 3,
            ],
            [
                'key'   => 'appointments.delete',
                'name'  => 'appointment::app.acl.delete',
                'route' => 'admin.appointments.delete',
                'sort'  => 4,
            ],
        ],
    ],
];
