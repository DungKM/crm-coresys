<?php 
if (!function_exists('email_thread_helper')){
    function email_thread_helper(): \Webkul\EmailExtended\Helpers\EmailThreadHelper
    {
        return app('email.thread.helper');
    }
}

if(!function_exists('email_tracking_helper')){
    function email_tracking_helper(): \Webkul\EmailExtended\Helpers\EmailTrackingHelper
    {
        return app('email_tracking_helper');
    }
}

// Định dạng email hiển thị 
if (!function_exists('format_email_addresses')) {
    function format_email_addresses($addresses, int $limit = 2): string
    {
        return email_thread_helper()->formatEmailAddresses($addresses, $limit);
    }
}

// Chuẩn hóa tiêu đề của email 
if (!function_exists('normalize_email_subject')) {
    function normalize_email_subject(string $subject): string
    {
        return email_thread_helper()->normalizeSubject($subject);
    }
}

if (!function_exists('generate_tracking_pixel')) {
    function generate_tracking_pixel(int $emailId, string $token): string
    {
        return email_tracking_helper()->generateTrackingPixel($emailId, $token);
    }
}

// CHèn pixel theo dõi và liên kết vào email 
if (!function_exists('inject_email_tracking')) {
    function inject_email_tracking(string $content, int $emailId, string $token): string
    {
        $helper = email_tracking_helper();
        if ($helper->isOpenTrackingEnabled()) {
            $content = $helper->injectTrackingPixel($content, $emailId, $token);
        }
        if ($helper->isClickTrackingEnabled()) {
            $content = $helper->injectTrackingLinks($content, $emailId, $token);
        }
        return $content;
    }
}

if (!function_exists('get_tracking_status_badge')) {
    function get_tracking_status_badge(string $status): string
    {
        return email_tracking_helper()->getStatusBadge($status);
    }
}

if (!function_exists('calculate_email_open_rate')) {
    function calculate_email_open_rate(int $sent, int $opened): float
    {
        return email_tracking_helper()->calculateOpenRate($sent, $opened);
    }
}

if (!function_exists('calculate_email_click_rate')) {
    function calculate_email_click_rate(int $opened, int $clicked): float
    {
        return email_tracking_helper()->calculateClickRate($opened, $clicked);
    }
}

if (!function_exists('get_device_icon')) {
    function get_device_icon(string $device): string
    {
        return email_tracking_helper()->getDeviceIcon($device);
    }
}

if (!function_exists('parse_user_agent')) {
    function parse_user_agent(?string $userAgent): array
    {
        return email_tracking_helper()->parseUserAgent($userAgent);
    }
}

if (!function_exists('email_time_ago')) {
    function email_time_ago($datetime): string
    {
        return email_thread_helper()->timeAgo($datetime);
    }
}

if (!function_exists('get_email_preview')) {
    function get_email_preview($email, int $length = 100): string
    {
        return email_thread_helper()->getEmailPreview($email, $length);
    }
}

if (!function_exists('get_folder_icon')) {
    function get_folder_icon(string $folder): string
    {
        return email_thread_helper()->getFolderIcon($folder);
    }
}

if (!function_exists('get_folder_label')) {
    function get_folder_label(string $folder): string
    {
        return email_thread_helper()->getFolderLabel($folder);
    }
}
