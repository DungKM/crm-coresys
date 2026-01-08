<?php

return [
    'datagrid' => [
        'id'        => 'ID',
        'emails'    => 'Email',  
        'subject'   => 'Tiêu đề',
        'from'      => 'Người gửi',
        'to'        => 'Người nhận',
        'date'      => 'Thời gian',
        'count'     => 'Số lượng',
        'status'    => 'Trạng thái',
        'actions'   => 'Hành động',
        'read'      => 'Đọc',
        'unread'    => 'Chưa đọc',
    ],

    'compose' => [
        'title' => 'Soạn Email',

        'from' => 'Người gửi',
        'to' => 'Người nhận',
        'cc' => 'CC',
        'bcc' => 'BCC',

        'subject' => 'Tiêu đề',
        'message' => 'Nội dung',
        'attachments' => 'Tệp đính kèm',

        'placeholders' => [
            'from' => 'email@cuaban.com',
            'to' => 'nguoinhan@email.com',
            'cc' => 'cc@email.com',
            'bcc' => 'bcc@email.com',
            'subject' => 'Tiêu đề email',
            'search_templates' => 'Tìm kiếm mẫu email...',
        ],

        'actions' => [
            'schedule' => 'Lên lịch gửi',
            'save_draft' => 'Lưu nháp',
            'send' => 'Gửi Email',
        ],
    ],

    'templates' => [
        'title' => 'Mẫu Email',
        'uses' => 'lượt dùng',

        'empty' => [
            'title' => 'Không tìm thấy mẫu email.',
            'create' => 'Tạo mẫu email đầu tiên',
        ],

        'manage' => 'Quản lý mẫu email',
    ],

    'alerts' => [
        'template_loaded' => 'Tải mẫu email thành công!',
        'template_load_failed' => 'Không thể tải mẫu email',
        'loading' => 'Đang tải...',
        'schedule_prompt' => 'Lên lịch gửi email (YYYY-MM-DD HH:MM):',
    ],

    'inbox' => [
        'title' => 'Hộp thư',

        'compose' => 'Soạn thư',

        'actions' => [
            'refresh' => 'Làm mới',
        ],

        'folders' => [
            'inbox'     => 'Hộp thư đến',
            'sent'      => 'Đã gửi',
            'draft'     => 'Nháp',
            'scheduled' => 'Đã lên lịch',
            'archive'   => 'Lưu trữ',
            'trash'     => 'Thùng rác',
        ],

        'stats' => [
            'total'   => 'Tổng hội thoại',
            'unread'  => 'Chưa đọc',
            'starred' => 'Đánh dấu sao',
        ],

        'empty' => [
            'inbox' => [
                'title' => 'Hộp thư đến trống',
                'description' => 'Bạn đã xem hết tất cả email. Email mới sẽ xuất hiện tại đây khi được nhận.',
            ],

            'sent' => [
                'title' => 'Chưa gửi email nào',
                'description' => 'Bắt đầu gửi email của bạn. Tất cả email đã gửi sẽ được lưu tại đây.',
            ],

            'draft' => [
                'title' => 'Chưa có email nháp',
                'description' => 'Tạo email nháp để lưu và tiếp tục chỉnh sửa sau.',
            ],

            'scheduled' => [
                'title' => 'Chưa có email đã lên lịch',
                'description' => 'Lên lịch gửi email để tiếp cận khách hàng đúng thời điểm.',
            ],

            'archive' => [
                'title' => 'Chưa có email lưu trữ',
                'description' => 'Lưu trữ email quan trọng để dễ tìm kiếm và quản lý.',
            ],

            'trash' => [
                'title' => 'Thùng rác trống',
                'description' => 'Email đã xóa sẽ được giữ lại 30 ngày trước khi xóa vĩnh viễn.',
            ],

            'default' => [
                'title' => 'Không có email',
                'description' => 'Không tìm thấy email nào. Hãy thử làm mới hoặc kiểm tra thư mục khác.',
            ],
        ],
    ],

    'scheduled' => [
        'title' => 'Email đã lên lịch',

        'back_to_inbox' => 'Quay lại hộp thư',

        'badge' => [
            'pending' => 'đang chờ',
        ],

        'stats' => [
            'total'      => 'Tổng cộng',
            'pending'    => 'Đang chờ',
            'processing' => 'Đang xử lý',
            'sent'       => 'Đã gửi',
            'failed'     => 'Thất bại',
        ],

        'actions' => [
            'cancel_confirm' => 'Bạn có chắc chắn muốn hủy email đã lên lịch này không?',
            'reschedule_prompt' => 'Thời gian gửi mới (YYYY-MM-DD HH:MM):',
        ],
    ],

    'thread' => [
        'badges' => [
            'unread' => 'Chưa đọc',
            'received' => 'Đã nhận',
            'sent' => 'Đã gửi',
        ],

        'actions' => [
            'reply' => 'Trả lời',
            'forward' => 'Chuyển tiếp',
            'archive' => 'Lưu trữ',
            'delete' => 'Xóa',
            'view_tracking' => 'Xem theo dõi',
        ],

        'tracking' => [
            'opened' => 'Đã mở',
            'clicked' => 'Đã nhấp',

            'stats' => [
                'opens' => 'Lượt mở',
                'clicks' => 'Lượt nhấp',
                'unique_opens' => 'Mở duy nhất',
                'first_opened' => 'Mở lần đầu',
            ],
        ],

        'labels' => [
            'to' => 'Gửi tới',
            'cc' => 'CC',
        ],

        'sidebar' => [
            'thread_info' => 'Thông tin hội thoại',
            'emails' => 'Email',
            'participants' => 'Người tham gia',
            'last_activity' => 'Hoạt động gần nhất',

            'linked_lead' => 'Lead liên kết',
            'linked_contact' => 'Liên hệ liên kết',
        ],

        'confirmations' => [
            'delete_thread' => 'Bạn có chắc chắn muốn xóa hội thoại này không?',
        ],
    ],
];
