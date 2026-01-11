<?php

return [
    // Cài dặt mặc định 
    'defaults' => [
        'locale' => 'vi',
        'category' => 'general',
        'is_active' => true,
    ],

    // Features
    'features' => [
        'variables' => true,
        'tags' => true,
        'clone' => true,
        'export_import' => true,
        'usage_tracking' => true,
        'preview' => true,
        'multi_language' => true,
    ],

    // Danh sách danh mục có sẵn 
    'categories' => [
        'sales' => [
            'label' => 'Sales (Bán hàng)',
            'icon' => 'icon-sales',
            'color' => '#3b82f6',
        ],
        'marketing' => [
            'label' => 'Marketing (Tiếp thị)',
            'icon' => 'icon-marketing',
            'color' => '#8b5cf6',
        ],
        'support' => [
            'label' => 'Support (Hỗ trợ)',
            'icon' => 'icon-support',
            'color' => '#10b981',
        ],
        'customer_care' => [
            'label' => 'Customer Care (CSKH)',
            'icon' => 'icon-customer',
            'color' => '#f59e0b',
        ],
        'workflow' => [
            'label' => 'Workflow Automation',
            'icon' => 'icon-workflow',
            'color' => '#06b6d4',
        ],
        'transactional' => [
            'label' => 'Transactional (Hệ thống)',
            'icon' => 'icon-transaction',
            'color' => '#6366f1',
        ],
        'notification' => [
            'label' => 'Notification (Thông báo)',
            'icon' => 'icon-bell',
            'color' => '#ec4899',
        ],
        'internal' => [
            'label' => 'Internal (Nội bộ)',
            'icon' => 'icon-internal',
            'color' => '#64748b',
        ],
        'billing' => [
            'label' => 'Billing / Order (Hóa đơn & Đơn hàng)',
            'icon' => 'icon-billing',
            'color' => '#eab308',
        ],
        'reporting' => [
            'label' => 'Reporting (Báo cáo)',
            'icon' => 'icon-report',
            'color' => '#14b8a6',
        ],
        'general' => [
            'label' => 'General (Chung)',
            'icon' => 'icon-general',
            'color' => '#6b7280',
        ],
    ],

    // Các loại biến được sử dụng trong template
    'variable_types' => [
        'text' => [
            'label' => 'Text',
            'validation' => 'string',
            'default_sample' => 'CoreSys',
        ],
        'email' => [
            'label' => 'Email',
            'validation' => 'email',
            'default_sample' => 'example@coresys.com',
        ],
        'number' => [
            'label' => 'Number',
            'validation' => 'numeric',
            'default_sample' => 1000,
        ],
        'date' => [
            'label' => 'Date',
            'validation' => 'date',
            'default_sample' => '12/12/2025',
        ],
        'datetime' => [
            'label' => 'DateTime',
            'validation' => 'date',
            'default_sample' => '12/12/2025 14:30',
        ],
        'url' => [
            'label' => 'URL',
            'validation' => 'url',
            'default_sample' => 'https://coresys.com',
        ],
        'phone' => [
            'label' => 'Phone',
            'validation' => 'string',
            'default_sample' => '0123456789',
        ],
        'boolean' => [
            'label' => 'Boolean',
            'validation' => 'boolean',
            'default_sample' => true,
        ],
    ],

    // Ngôn ngữ hỗ trợ 
    'locales' => [
        'vi' => 'Tiếng Việt',
        'en' => 'English',
    ],

    // preview settings 
    'preview' => [
        'max_length' => 200, // độ dài tối đa của text 
        'auto_generate' => true, // Tự động tạo bản xem trước từ nội dung 
    ],

    // Theo dõi tần suất sử dụng 
    'usage_tracking' => [
        'enabled' => true,
        'track_on_send' => true, // tự động tăng khi gửi email 
    ],

    // Chuẩn hóa dữ liệu 
    'validation' => [
        'name_max_length' => 255,
        'subject_max_length' => 500,
        'tag_max_length' => 50,
        'variable_name_max_length' => 100,
    ],

    // Quyền hạn 
    'permissions' => [
        'create' => 'email_templates.create',
        'edit' => 'email_templates.edit',
        'delete' => 'email_templates.delete',
        'clone' => 'email_templates.clone',
        'export' => 'email_templates.export',
        'import' => 'email_templates.import',
    ],
];