<?php
return [
    /**
     * Enable/Disable email tracking
     */
    'tracking' => [
        'enabled' => env('EMAIL_TRACKING_ENABLED', true),
        'track_opens' => env('EMAIL_TRACK_OPENS', true),
        'track_clicks' => env('EMAIL_TRACK_CLICKS', true),
    ],

    /**
     * Scheduled emails settings
     */
    'scheduled' => [
        'max_attempts' => env('EMAIL_MAX_ATTEMPTS', 3),
        'retry_delay' => env('EMAIL_RETRY_DELAY', 60), // minutes
    ],

    /**
     * Thread settings
     */
    'threads' => [
        'auto_create' => env('EMAIL_AUTO_CREATE_THREADS', true),
        'group_by_subject' => env('EMAIL_GROUP_BY_SUBJECT', true),
    ],

    /**
     * Email folders
     */
    'folders' => [
        'inbox',
        'sent',
        'draft',
        'archive',
        'trash',
        'spam',
    ],

    /**
     * Email statuses
     */
    'statuses' => [
        'draft',
        'queued',
        'sent',
        'failed',
        'bounced',
        'delivered',
    ],
];