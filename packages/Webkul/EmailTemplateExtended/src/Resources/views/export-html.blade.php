<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $emailTemplate->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Arial', 'Helvetica', sans-serif;
            line-height: 1.6;
            background-color: #f0f2f5;
            padding: 20px;
        }
        
        .email-wrapper {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        /* Print/Save Controls */
        .controls-bar {
            position: sticky;
            top: 0;
            background: #1f2937;
            color: white;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
        }
        
        .controls-bar h2 {
            font-size: 16px;
            font-weight: 600;
        }
        
        .controls-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background: #3b82f6;
            color: white;
        }
        
        .btn-primary:hover {
            background: #2563eb;
        }
        
        .btn-secondary {
            background: #6b7280;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #4b5563;
        }
        
        /* Email Header */
        .email-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .email-from {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 16px;
        }
        
        .avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            font-weight: bold;
            flex-shrink: 0;
        }
        
        .sender-info {
            flex: 1;
        }
        
        .sender-name {
            font-size: 15px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 2px;
        }
        
        .sender-email {
            font-size: 13px;
            color: #6b7280;
        }
        
        .email-meta {
            margin-top: 8px;
            font-size: 13px;
            color: #6b7280;
        }
        
        /* Recipients Section */
        .email-recipients {
            padding: 12px 24px;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
        }
        
        .recipient-row {
            display: flex;
            margin-bottom: 6px;
        }
        
        .recipient-row:last-child {
            margin-bottom: 0;
        }
        
        .recipient-label {
            width: 80px;
            color: #6b7280;
            font-weight: 500;
        }
        
        .recipient-value {
            color: #1f2937;
            flex: 1;
        }
        
        /* Subject */
        .email-subject {
            padding: 20px 24px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .subject-text {
            font-size: 20px;
            font-weight: 600;
            color: #1f2937;
            line-height: 1.4;
        }
        
        /* Preview Text */
        .preview-text {
            padding: 12px 24px;
            background: #fef3c7;
            border-bottom: 1px solid #fde68a;
            font-size: 13px;
            color: #92400e;
        }
        
        /* Email Body */
        .email-body {
            padding: 24px;
            color: #374151;
            font-size: 14px;
        }
        
        .email-body p {
            margin-bottom: 16px;
            line-height: 1.6;
        }
        
        .email-body h1, .email-body h2, .email-body h3 {
            margin-top: 24px;
            margin-bottom: 12px;
            color: #1f2937;
        }
        
        .email-body h1 {
            font-size: 24px;
        }
        
        .email-body h2 {
            font-size: 20px;
        }
        
        .email-body h3 {
            font-size: 16px;
        }
        
        .email-body ul, .email-body ol {
            margin-left: 24px;
            margin-bottom: 16px;
        }
        
        .email-body li {
            margin-bottom: 8px;
        }
        
        .email-body a {
            color: #3b82f6;
            text-decoration: none;
        }
        
        .email-body strong {
            font-weight: 600;
            color: #1f2937;
        }
        
        .email-body blockquote {
            border-left: 4px solid #e5e7eb;
            padding-left: 16px;
            margin: 16px 0;
            color: #6b7280;
        }
        
        /* Template Details */
        .template-details {
            padding: 20px 24px;
            background: #f9fafb;
            border-top: 2px solid #e5e7eb;
        }
        
        .details-title {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
        
        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        
        .detail-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: #6b7280;
            letter-spacing: 0.5px;
        }
        
        .detail-value {
            font-size: 13px;
            color: #1f2937;
            font-weight: 500;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-active {
            background: #dcfce7;
            color: #166534;
        }
        
        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .tags-list {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }
        
        .tag-item {
            background: #e0e7ff;
            color: #3730a3;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        
        /* Variables Section */
        .variables-section {
            padding: 20px 24px;
            background: #eff6ff;
            border-top: 1px solid #dbeafe;
        }
        
        .variables-title {
            font-size: 14px;
            font-weight: 600;
            color: #1e40af;
            margin-bottom: 12px;
        }
        
        .variables-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }
        
        .variable-item {
            background: white;
            border: 1px solid #bfdbfe;
            padding: 8px 12px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            color: #1e40af;
        }
        
        /* Sample Data Section */
        .sample-data-section {
            padding: 20px 24px;
            background: #fef9e7;
            border-top: 1px solid #fde68a;
        }
        
        .sample-title {
            font-size: 14px;
            font-weight: 600;
            color: #92400e;
            margin-bottom: 12px;
        }
        
        .sample-item {
            background: white;
            border: 1px solid #fde68a;
            padding: 10px 12px;
            border-radius: 6px;
            margin-bottom: 8px;
            font-size: 12px;
        }
        
        .sample-key {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            color: #92400e;
            margin-bottom: 4px;
        }
        
        .sample-value {
            color: #451a03;
        }
        
        /* Export Info */
        .export-info {
            padding: 12px 24px;
            background: #f3f4f6;
            border-top: 1px solid #d1d5db;
            text-align: center;
            font-size: 11px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <!-- Controls Bar -->
    <div class="controls-bar no-print">
        <h2>Email Template Export - {{ $emailTemplate->name }}</h2>
        <div class="controls-buttons">
            <button class="btn btn-primary" onclick="window.print()">In / Lưu PDF</button>
            <button class="btn btn-secondary" onclick="window.close()">Đóng</button>
        </div>
    </div>

    <div class="email-wrapper">
        
        <!-- EMAIL HEADER -->
        <div class="email-header">
            <div class="email-from">
                <div class="avatar">
                    {{ strtoupper(substr($emailTemplate->name, 0, 1)) }}
                </div>
                <div class="sender-info">
                    <div class="sender-name">
                        @if(isset($emailTemplate->metadata['from_name']))
                            {{ $emailTemplate->metadata['from_name'] }}
                        @else
                            Your Company Name
                        @endif
                    </div>
                    <div class="sender-email">
                        &lt;@if(isset($emailTemplate->metadata['from_email'])){{ $emailTemplate->metadata['from_email'] }}@else noreply@company.com @endif&gt;
                    </div>
                    <div class="email-meta">
                        Sent: {{ now()->format('l, F j, Y \a\t g:i A') }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- RECIPIENTS -->
        <div class="email-recipients">
            <div class="recipient-row">
                <span class="recipient-label">To:</span>
                <span class="recipient-value">customer@example.com</span>
            </div>
            @if(isset($emailTemplate->metadata['reply_to']))
            <div class="recipient-row">
                <span class="recipient-label">Reply-To:</span>
                <span class="recipient-value">{{ $emailTemplate->metadata['reply_to'] }}</span>
            </div>
            @endif
            @if(isset($emailTemplate->metadata['cc_email']) && $emailTemplate->metadata['cc_email'])
            <div class="recipient-row">
                <span class="recipient-label">CC:</span>
                <span class="recipient-value">{{ $emailTemplate->metadata['cc_email'] }}</span>
            </div>
            @endif
        </div>
        
        <!-- SUBJECT -->
        <div class="email-subject">
            <div class="subject-text">{{ $renderedSubject }}</div>
        </div>
        
        <!-- PREVIEW TEXT -->
        @if($emailTemplate->preview_text)
        <div class="preview-text">
            <strong>Preview:</strong> {{ $emailTemplate->preview_text }}
        </div>
        @endif
        
        <!-- EMAIL BODY -->
        <div class="email-body">
            {!! $renderedContent !!}
        </div>
        
        <!-- TEMPLATE DETAILS -->
        <div class="template-details">
            <div class="details-title">Thông tin Template</div>
            
            <div class="details-grid">
                <div class="detail-item">
                    <span class="detail-label">Tên Template</span>
                    <span class="detail-value">{{ $emailTemplate->name }}</span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">Danh mục</span>
                    <span class="detail-value">{{ $emailTemplate->category_label }}</span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">Ngôn ngữ</span>
                    <span class="detail-value">{{ strtoupper($emailTemplate->locale) }}</span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">Trạng thái</span>
                    <span class="status-badge {{ $emailTemplate->is_active ? 'status-active' : 'status-inactive' }}">
                        {{ $emailTemplate->is_active ? 'Đang hoạt động' : 'Không hoạt động' }}
                    </span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">Ngày tạo</span>
                    <span class="detail-value">{{ $emailTemplate->created_at->format('d/m/Y H:i') }}</span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">Cập nhật lần cuối</span>
                    <span class="detail-value">{{ $emailTemplate->updated_at->format('d/m/Y H:i') }}</span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">Số lần sử dụng</span>
                    <span class="detail-value">{{ $emailTemplate->usage_count }} lần</span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">Sử dụng lần cuối</span>
                    <span class="detail-value">{{ $emailTemplate->last_used_at ? $emailTemplate->last_used_at->format('d/m/Y H:i') : 'Chưa sử dụng' }}</span>
                </div>
                
                @if($emailTemplate->tags && count($emailTemplate->tags) > 0)
                <div class="detail-item" style="grid-column: span 2;">
                    <span class="detail-label">Thẻ Tags</span>
                    <div class="tags-list">
                        @foreach($emailTemplate->tags as $tag)
                            @if($tag)
                                <span class="tag-item">{{ $tag }}</span>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- VARIABLES -->
        @if($emailTemplate->getAllUsedVariables() && count($emailTemplate->getAllUsedVariables()) > 0)
        <div class="variables-section">
            <div class="variables-title">Biến được sử dụng trong Template</div>
            <div class="variables-grid">
                @foreach($emailTemplate->getAllUsedVariables() as $variable)
                    <div class="variable-item">@{{ '{{' }} {{ $variable }} @{{ '}}' }}</div>
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- SAMPLE DATA -->
        @if(!empty($sampleData))
        <div class="sample-data-section">
            <div class="sample-title">Dữ liệu mẫu được sử dụng</div>
            @foreach($sampleData as $key => $value)
                <div class="sample-item">
                    <div class="sample-key">@{{ '{{' }} {{ $key }} @{{ '}}' }}</div>
                    <div class="sample-value">
                        @if(is_array($value) || is_object($value))
                            {{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
                        @else
                            {{ $value }}
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        @endif
        
        <!-- EXPORT INFO -->
        <div class="export-info">
            Email template được xuất từ hệ thống Email Template System vào {{ now()->format('d/m/Y H:i:s') }}
            <br>
            Template ID: #{{ $emailTemplate->id }} | Tạo bởi User ID: {{ $emailTemplate->user_id ?? 'System' }}
        </div>
        
    </div>
</body>
</html>