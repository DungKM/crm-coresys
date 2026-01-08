<?php

return [

    'company' => [
        'name'    => env('APPOINTMENT_COMPANY_NAME', 'Krayin CRM'),
        'logo_url' => 'data:image/svg+xml;base64,' . base64_encode(file_get_contents(public_path('admin/build/assets/logo-Bjh7YAuF.svg'))),
        'phone'    => env('COMPANY_PHONE', '+84 123 456 789'),
        'email'    => env('COMPANY_EMAIL', 'support@krayincrm.com'),
        'address'  => env('COMPANY_ADDRESS', '123 Street, City'),
        'website'  => env('COMPANY_WEBSITE', 'http://localhost'),
        'facebook' => env('COMPANY_FACEBOOK', ''),
        'linkedin' => env('COMPANY_LINKEDIN', ''),
    ],

    'colors' => [
        'primary'   => env('APPOINTMENT_COLOR_PRIMARY', '#4f46e5'),
        'secondary' => env('APPOINTMENT_COLOR_SECONDARY', '#111827'),
        'success'   => env('APPOINTMENT_COLOR_SUCCESS', '#16a34a'),
        'danger'    => env('APPOINTMENT_COLOR_DANGER', '#dc2626'),
    ],

    'email' => [
        'enabled' => env('APPOINTMENT_EMAIL_ENABLED', false),
        'send_to_customer' => env('APPOINTMENT_EMAIL_TO_CUSTOMER', false),
        'send_to_assigned_user' => env('APPOINTMENT_EMAIL_TO_ASSIGNED_USER', false),
        'queue' => env('APPOINTMENT_EMAIL_QUEUE', false),
        'queue_connection' => env('APPOINTMENT_EMAIL_QUEUE_CONNECTION', 'sync'),
        'from' => [
            'address' => env('APPOINTMENT_EMAIL_FROM'),
            'name' => env('APPOINTMENT_EMAIL_NAME'),
        ],
        'reply_to' => [
            'address' => env('APPOINTMENT_EMAIL_REPLY_TO'),
            'name' => env('APPOINTMENT_EMAIL_REPLY_TO_NAME'),
        ],
    ],

    'reminders' => [
        'enabled' => env('APPOINTMENT_REMINDERS_ENABLED', false),
    ],
];
