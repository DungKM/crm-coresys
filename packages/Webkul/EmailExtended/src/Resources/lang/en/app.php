<?php

return [
    'datagrid' => [
        'id'        => 'ID',
        'subject'   => 'Subject',
        'emails'    => 'Emails',  
        'from'      => 'From',
        'to'        => 'To',
        'date'      => 'Date',
        'count'     => 'Count',
        'status'    => 'Status',
        'actions'   => 'Actions',
        'read'      => 'Read',
        'unread'    => 'Unread',
    ],

    'compose' => [
        'title' => 'Compose Email',

        'from' => 'From',
        'to' => 'To',
        'cc' => 'CC',
        'bcc' => 'BCC',

        'subject' => 'Subject',
        'message' => 'Message',
        'attachments' => 'Attachments',

        'placeholders' => [
            'from' => 'your@email.com',
            'to' => 'recipient@email.com',
            'cc' => 'cc@email.com',
            'bcc' => 'bcc@email.com',
            'subject' => 'Email subject',
            'search_templates' => 'Search templates...',
        ],

        'actions' => [
            'schedule' => 'Schedule',
            'save_draft' => 'Save as Draft',
            'send' => 'Send Email',
        ],
    ],

    'templates' => [
        'title' => 'Email Templates',
        'uses' => 'uses',

        'empty' => [
            'title' => 'No templates found.',
            'create' => 'Create your first template',
        ],

        'manage' => 'Manage Templates',
    ],

    'alerts' => [
        'template_loaded' => 'Template loaded successfully!',
        'template_load_failed' => 'Failed to load template',
        'loading' => 'Loading...',
        'schedule_prompt' => 'Schedule email (YYYY-MM-DD HH:MM):',
    ],

    'inbox' => [
        'title' => 'Mailbox',

        'compose' => 'Compose',

        'actions' => [
            'refresh' => 'Refresh',
        ],

        'folders' => [
            'inbox'     => 'Inbox',
            'sent'      => 'Sent',
            'draft'     => 'Drafts',
            'scheduled' => 'Scheduled',
            'archive'   => 'Archived',
            'trash'     => 'Trash',
        ],

        'stats' => [
            'total'   => 'Total Threads',
            'unread'  => 'Unread',
            'starred' => 'Starred',
        ],

        'empty' => [
            'inbox' => [
                'title' => 'Inbox is empty',
                'description' => 'You have read all your emails. New emails will appear here when received.',
            ],

            'sent' => [
                'title' => 'No emails sent yet',
                'description' => 'Start sending emails. All sent emails will be stored here.',
            ],

            'draft' => [
                'title' => 'No drafts available',
                'description' => 'Create a draft to save and continue editing later.',
            ],

            'scheduled' => [
                'title' => 'No scheduled emails',
                'description' => 'Schedule emails to reach customers at the perfect time.',
            ],

            'archive' => [
                'title' => 'No archived emails',
                'description' => 'Archive important emails to find them easily later.',
            ],

            'trash' => [
                'title' => 'Trash is empty',
                'description' => 'Deleted emails will stay here for 30 days before permanent removal.',
            ],

            'default' => [
                'title' => 'No emails found',
                'description' => 'Try refreshing or checking another folder.',
            ],
        ],
    ],

    'scheduled' => [
        'title' => 'Scheduled Emails',

        'back_to_inbox' => 'Back to Inbox',

        'badge' => [
            'pending' => 'pending',
        ],

        'stats' => [
            'total'      => 'Total',
            'pending'    => 'Pending',
            'processing' => 'Processing',
            'sent'       => 'Sent',
            'failed'     => 'Failed',
        ],

        'actions' => [
            'cancel_confirm' => 'Are you sure you want to cancel this scheduled email?',
            'reschedule_prompt' => 'New schedule time (YYYY-MM-DD HH:MM):',
        ],
    ],

    'thread' => [
        'badges' => [
            'unread' => 'Unread',
            'received' => 'Received',
            'sent' => 'Sent',
        ],

        'actions' => [
            'reply' => 'Reply',
            'forward' => 'Forward',
            'archive' => 'Archive',
            'delete' => 'Delete',
            'view_tracking' => 'View Tracking',
        ],

        'tracking' => [
            'opened' => 'Opened',
            'clicked' => 'Clicked',

            'stats' => [
                'opens' => 'Opens',
                'clicks' => 'Clicks',
                'unique_opens' => 'Unique Opens',
                'first_opened' => 'First Opened',
            ],
        ],

        'labels' => [
            'to' => 'To',
            'cc' => 'CC',
        ],

        'sidebar' => [
            'thread_info' => 'Thread Information',
            'emails' => 'Emails',
            'participants' => 'Participants',
            'last_activity' => 'Last Activity',

            'linked_lead' => 'Linked Lead',
            'linked_contact' => 'Linked Contact',
        ],

        'confirmations' => [
            'delete_thread' => 'Are you sure you want to delete this thread?',
        ],
    ],
];
