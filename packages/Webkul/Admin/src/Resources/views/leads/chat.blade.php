<x-admin::layouts>
    <x-slot:title>
        WhatsApp Chat
    </x-slot>

    {{-- Custom CSS for chat message text visibility --}}
    <style>
        /* ===== OUTGOING MESSAGES (Blue background) ===== */
        /* Light mode: white text on blue background */
        .chat-message-outgoing,
        .chat-message-outgoing span,
        .chat-message-outgoing p {
            color: #ffffff !important;
        }
        /* Dark mode: still white text on blue background */
        html.dark .chat-message-outgoing,
        html.dark .chat-message-outgoing span,
        html.dark .chat-message-outgoing p {
            color: #ffffff !important;
        }
        
        /* ===== INCOMING MESSAGES (Gray background) ===== */
        /* Light mode: dark text on light gray background */
        .chat-message-incoming,
        .chat-message-incoming span,
        .chat-message-incoming p {
            color: #1f2937 !important;
        }
        /* Dark mode: light text on dark gray background */
        html.dark .chat-message-incoming,
        html.dark .chat-message-incoming span,
        html.dark .chat-message-incoming p {
            color: #e5e7eb !important;
        }
        
        /* Preserve emoji and icon colors */
        .chat-message-outgoing svg,
        .chat-message-incoming svg {
            color: inherit !important;
        }

        /* ===== PINNED MESSAGES BANNER (Zalo-style) ===== */
        .pinned-messages-banner {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-bottom: 2px solid #f59e0b;
            padding: 0;
            max-height: 150px;
            overflow-y: auto;
        }
        html.dark .pinned-messages-banner {
            background: linear-gradient(135deg, #451a03 0%, #78350f 100%);
            border-bottom-color: #b45309;
        }
        .pinned-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            cursor: pointer;
            transition: background 0.2s;
            border-bottom: 1px solid rgba(245, 158, 11, 0.2);
        }
        .pinned-item:last-child {
            border-bottom: none;
        }
        .pinned-item:hover {
            background: rgba(245, 158, 11, 0.15);
        }
        html.dark .pinned-item:hover {
            background: rgba(245, 158, 11, 0.1);
        }
        .pinned-item-content {
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 13px;
            color: #92400e;
        }
        html.dark .pinned-item-content {
            color: #fcd34d;
        }
        .pinned-item-unpin {
            padding: 4px;
            border-radius: 50%;
            color: #b45309;
            transition: all 0.2s;
        }
        .pinned-item-unpin:hover {
            background: rgba(180, 83, 9, 0.2);
            color: #92400e;
        }
        html.dark .pinned-item-unpin {
            color: #fbbf24;
        }
        html.dark .pinned-item-unpin:hover {
            background: rgba(251, 191, 36, 0.2);
            color: #fcd34d;
        }

        /* ===== STATUS MESSAGES ===== */
        .status-warning {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
            background: #fef3c7;
            color: #92400e;
        }
        html.dark .status-warning {
            background: #78350f;
            color: #fcd34d;
        }
        .status-success {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
            background: #dcfce7;
            color: #166534;
        }
        html.dark .status-success {
            background: #14532d;
            color: #86efac;
        }
        .status-error {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
            background: #fee2e2;
            color: #991b1b;
        }
        html.dark .status-error {
            background: #7f1d1d;
            color: #fca5a5;
        }
        .status-sending {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            color: #1f2937;
        }
        html.dark .status-sending {
            color: #e5e7eb;
        }

        /* ===== HIGHLIGHT EFFECT when scrolling to message ===== */
        @keyframes highlightPulse {
            0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7); }
            50% { box-shadow: 0 0 0 8px rgba(245, 158, 11, 0); }
            100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
        }
        .message-highlight {
            animation: highlightPulse 1s ease-out;
            background-color: rgba(245, 158, 11, 0.2) !important;
            transition: background-color 2s ease-out;
        }
        html.dark .message-highlight {
            background-color: rgba(245, 158, 11, 0.15) !important;
        }

        /* Pinned indicator on message */
        .pinned-indicator {
            display: inline-flex;
            align-items: center;
            gap: 2px;
            font-size: 10px;
            color: #f59e0b;
            margin-left: 4px;
        }
    </style>

    <!-- Content -->
    <div class="relative flex gap-4 max-lg:flex-wrap">
        <!-- Left Panel -->
        <div
            class="max-lg:min-w-full max-lg:max-w-full [&>div:last-child]:border-b-0 lg:sticky lg:top-[73px] flex min-w-[394px] max-w-[394px] flex-col self-start rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <!-- Lead Information -->
            <div class="flex w-full flex-col gap-2 border-b border-gray-200 p-4 dark:border-gray-800">
                <!-- Breadcrumb's -->
                <div class="flex items-center justify-between">
                    <x-admin::breadcrumbs name="admin.leads.chat.index" :entity="$lead" />
                </div>

                <!-- Title -->
                <h1 class="text-lg font-bold dark:text-white">
                    {{ $lead->title }}
                </h1>
            </div>

            <!-- Lead Attributes -->
            @include ('admin::leads.view.attributes')

            <!-- Contact Person -->
            @include ('admin::leads.view.person')
        </div>

        <!-- Right Panel - WhatsApp Chat (Pure Blade, no Vue component) -->
        <div class="flex w-full flex-col gap-4 rounded-lg">
            <div class="w-full rounded-md border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                {{-- Tab Header --}}
                <div class="flex gap-2 overflow-x-auto border-b border-gray-200 dark:border-gray-800">
                    <div
                        class="cursor-pointer px-3 py-2.5 text-sm font-medium border-brandColor border-b-2 text-brandColor">
                        💬 WhatsApp Chat
                    </div>
                </div>

                {{-- Chat Content - Flex container để input luôn ở dưới --}}
                <div class="bg-white dark:bg-gray-900 rounded-b-lg flex flex-col" style="height: calc(100vh - 180px);">

                    @php
                        // Lấy toàn bộ WhatsApp activities trước
                        $whatsappLogs = $lead->activities->where('type', 'whatsapp')->sortBy('created_at');
                        // Lọc các tin nhắn đã ghim
                        $pinnedMessages = $whatsappLogs->where('is_pinned', true);
                    @endphp

                    {{-- PINNED MESSAGES BANNER (Zalo-style) --}}
                    <div id="pinned-banner-container">
                        @if ($pinnedMessages->isNotEmpty())
                            <div class="pinned-messages-banner" id="pinned-banner">
                                @foreach ($pinnedMessages as $pinned)
                                    @php
                                        $pinnedIsIncoming = str_contains($pinned->title, 'đến') || str_contains(strtolower($pinned->title), 'incoming');
                                        // Lấy text content từ comment (loại bỏ media tags)
                                        $pinnedText = preg_replace('/\[MEDIA:[^\]]+\]/', '', $pinned->comment);
                                        $pinnedText = trim($pinnedText) ?: '[Media]';
                                    @endphp
                                    <div class="pinned-item" onclick="scrollToMessage({{ $pinned->id }})" data-pinned-id="{{ $pinned->id }}">
                                        <span class="text-amber-600 dark:text-amber-400">📌</span>
                                        <span class="pinned-item-content">
                                            <strong>{{ $pinnedIsIncoming ? 'Khách hàng' : ($pinned->user->name ?? 'Bạn') }}:</strong>
                                            {{ Str::limit($pinnedText, 60) }}
                                        </span>
                                        <button type="button" class="pinned-item-unpin" onclick="event.stopPropagation(); togglePin({{ $pinned->id }})" title="Bỏ ghim">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- KHU VỰC HIỂN THỊ LỊCH SỬ CHAT - flex-1 để chiếm hết không gian, input area fixed ở dưới --}}
                    <div class="chat-history flex flex-col gap-3 p-4 flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-950"
                        id="chat-scroll-area">
                        @php
                            // Debug: Log số lượng tin nhắn (whatsappLogs đã được định nghĩa ở trên)
                            \Log::info('[DEBUG Chat] Total WhatsApp activities: ' . $whatsappLogs->count());
                        @endphp

                        @if ($whatsappLogs->isEmpty())
                            <div class="text-center text-gray-400 italic mt-10">
                                Chưa có tin nhắn nào.
                                {{-- Debug info --}}
                                <div class="text-xs mt-2">
                                    (Tổng số activities: {{ $lead->activities->count() }})
                                </div>
                            </div>
                        @else
                            @foreach ($whatsappLogs as $log)
                                @php
                                    // Logic phân biệt tin nhắn ĐẾN và ĐI
                                    // SỬA: Kiểm tra cả tiếng Việt và tiếng Anh để tránh lỗi encoding
                                    $isIncoming =
                                        str_contains($log->title, 'đến') ||
                                        str_contains(strtolower($log->title), 'incoming');

                                    // Parse media content
                                    $comment = $log->comment;
                                    $mediaHtml = '';
                                    $textContent = $comment;
                                    $replyQuoteHtml = '';
                                    
                                    // Xử lý [REPLY_TO:content] tag để hiển thị reply quote đẹp
                                    // Xử lý [REPLY_TO:content] tag để hiển thị reply quote đẹp
                                    if (preg_match('/\[REPLY_TO:([^\]]+)\]/', $comment, $replyMatches)) {
                                        $contentInside = $replyMatches[1];
                                        $replyId = null;
                                        $replyOriginalContent = $contentInside;
                                        
                                        // Check for ID:Content format
                                        if (preg_match('/^(\d+):(.*)$/', $contentInside, $parts)) {
                                            $replyId = $parts[1];
                                            $replyOriginalContent = $parts[2];
                                        }

                                        // Loại bỏ tag khỏi textContent
                                        $textContent = preg_replace('/\[REPLY_TO:[^\]]+\]/', '', $textContent);
                                        $comment = preg_replace('/\[REPLY_TO:[^\]]+\]/', '', $comment);
                                        
                                        // Escape content cho onclick
                                        $escapedReplyContent = addslashes($replyOriginalContent);
                                        
                                        // Build onClick - pass ID if available
                                        $onClick = $replyId 
                                            ? "scrollToReplyOrigin($replyId, '$escapedReplyContent')"
                                            : "scrollToReplyOrigin(null, '$escapedReplyContent')";
                                        
                                        // Tạo HTML cho reply quote clickable
                                        $replyQuoteHtml = '
                                            <div onclick="' . $onClick . '" class="mb-2 p-2 bg-gray-300/50 dark:bg-gray-600/50 rounded border-l-2 border-blue-500 text-xs cursor-pointer hover:bg-gray-400/50 dark:hover:bg-gray-500/50 transition-colors">
                                                <div class="text-blue-600 dark:text-blue-400 font-medium">↩️ Trả lời</div>
                                                <div class="text-gray-600 dark:text-gray-300 truncate">' . e($replyOriginalContent) . '</div>
                                            </div>
                                        ';
                                    }

                                    // Kiểm tra xem có media không
                                    // Hỗ trợ 2 dạng: 
                                    // 1. [MEDIA:type:url] hoặc [MEDIA:type:url:filename] - media từ khách gửi
                                    // 2. [MEDIA:type:uploaded:filename] - media mình gửi
                                    if (preg_match('/\[MEDIA:(\w+):([^\]]+)\]/', $comment, $matches)) {
                                        $mediaType = $matches[1];
                                        $mediaData = $matches[2];
                                        
                                        // Parse mediaData - xử lý đặc biệt cho URL có http:// hoặc https://
                                        $isUploaded = str_starts_with($mediaData, 'uploaded:');
                                        
                                        if ($isUploaded) {
                                            // Format: uploaded:filename
                                            $mediaUrl = 'uploaded';
                                            $filename = substr($mediaData, 9); // bỏ 'uploaded:'
                                        } elseif (str_starts_with($mediaData, 'http://') || str_starts_with($mediaData, 'https://')) {
                                            // Format: http://domain/storage/path hoặc http://domain/storage/path:filename
                                            // Tìm vị trí cuối cùng của : sau phần domain (nếu có filename)
                                            $mediaUrl = $mediaData;
                                            $filename = '';
                                            
                                            // Kiểm tra xem có filename sau URL không (format: url:filename)
                                            // Filename thường không chứa / nên tìm : sau / cuối cùng
                                            $lastSlash = strrpos($mediaData, '/');
                                            if ($lastSlash !== false) {
                                                $afterSlash = substr($mediaData, $lastSlash + 1);
                                                $colonPos = strpos($afterSlash, ':');
                                                if ($colonPos !== false) {
                                                    $mediaUrl = substr($mediaData, 0, $lastSlash + 1 + $colonPos);
                                                    $filename = substr($afterSlash, $colonPos + 1);
                                                }
                                            }
                                        } elseif (str_starts_with($mediaData, '/storage/')) {
                                            // Format: /storage/path hoặc /storage/path:filename
                                            $colonPos = strpos($mediaData, ':', 9); // tìm : sau /storage/
                                            if ($colonPos !== false) {
                                                $mediaUrl = substr($mediaData, 0, $colonPos);
                                                $filename = substr($mediaData, $colonPos + 1);
                                            } else {
                                                $mediaUrl = $mediaData;
                                                $filename = '';
                                            }
                                        } else {
                                            // Format cũ: url:filename (không có http://)
                                            $parts = explode(':', $mediaData, 2);
                                            $mediaUrl = $parts[0];
                                            $filename = $parts[1] ?? '';
                                        }

                                        $textContent = trim(preg_replace('/\[MEDIA:[^\]]+\]/', '', $comment));
                                        
                                        // Normalize URL - chuyển từ absolute URL sang relative path
                                        // Ví dụ: http://base_crm1.0.app/storage/... -> /storage/...
                                        if (!$isUploaded && str_contains($mediaUrl, '/storage/')) {
                                            $mediaUrl = '/storage/' . explode('/storage/', $mediaUrl, 2)[1];
                                        }
                                        
                                        if ($isUploaded) {
                                            // Uploaded media - hiển thị dạng placeholder
                                            $displayName = $filename ?: 'File đã gửi';
                                            switch ($mediaType) {
                                                case 'image':
                                                    $mediaHtml = '<div class="flex items-center gap-2 p-2 bg-green-100 dark:bg-green-800 rounded-lg mb-2"><span>🖼️</span><span class="text-sm text-green-800 dark:text-green-100">' . e($displayName) . ' (Đã gửi)</span></div>';
                                                    break;
                                                case 'video':
                                                    $mediaHtml = '<div class="flex items-center gap-2 p-2 bg-green-100 dark:bg-green-800 rounded-lg mb-2"><span>🎬</span><span class="text-sm text-green-800 dark:text-green-100">' . e($displayName) . ' (Đã gửi)</span></div>';
                                                    break;
                                                case 'audio':
                                                    $mediaHtml = '<div class="flex items-center gap-2 p-2 bg-green-100 dark:bg-green-800 rounded-lg mb-2"><span>🎵</span><span class="text-sm text-green-800 dark:text-green-100">' . e($displayName) . ' (Đã gửi)</span></div>';
                                                    break;
                                                case 'sticker':
                                                    $mediaHtml = '<div class="flex items-center gap-2 p-2 bg-green-100 dark:bg-green-800 rounded-lg mb-2"><span>😊</span><span class="text-sm text-green-800 dark:text-green-100">Sticker (Đã gửi)</span></div>';
                                                    break;
                                                case 'document':
                                                default:
                                                    $mediaHtml = '<div class="flex items-center gap-2 p-2 bg-green-100 dark:bg-green-800 rounded-lg mb-2"><span>📎</span><span class="text-sm text-green-800 dark:text-green-100">' . e($displayName) . ' (Đã gửi)</span></div>';
                                                    break;
                                            }
                                        } else {
                                            // Downloaded media - hiển thị với URL
                                            $audioType = 'audio/mpeg';
                                            if (str_ends_with($mediaUrl, '.ogg')) {
                                                $audioType = 'audio/ogg';
                                            } elseif (str_ends_with($mediaUrl, '.m4a')) {
                                                $audioType = 'audio/mp4';
                                            }

                                            switch ($mediaType) {
                                                case 'image':
                                                case 'sticker':
                                                    $mediaHtml =
                                                        '<a href="' .
                                                        $mediaUrl .
                                                        '" target="_blank" class="block mb-2"><img src="' .
                                                        $mediaUrl .
                                                        '" alt="Hình ảnh" class="max-w-full rounded-lg max-h-48 object-cover hover:opacity-90 transition-opacity" loading="lazy" onerror="this.parentElement.innerHTML=\'🖼️ [Không tải được ảnh]\'"></a>';
                                                    break;
                                                case 'video':
                                                    $mediaHtml =
                                                        '<video controls class="max-w-full rounded-lg max-h-48 mb-2" preload="metadata"><source src="' .
                                                        $mediaUrl .
                                                        '" type="video/mp4">Video không hỗ trợ</video>';
                                                    break;
                                                case 'audio':
                                                    $mediaHtml =
                                                        '<div class="flex items-center gap-2 mb-2 p-2 bg-gray-100 dark:bg-gray-700 rounded-lg"><span class="text-lg">🎵</span><audio controls class="flex-1 h-10" preload="metadata"><source src="' .
                                                        $mediaUrl .
                                                        '" type="' .
                                                        $audioType .
                                                        '">Audio không hỗ trợ</audio></div>';
                                                    break;
                                                case 'document':
                                                    $displayName = $filename ?: 'Tài liệu';
                                                    $mediaHtml =
                                                        '<a href="' .
                                                        $mediaUrl .
                                                        '" target="_blank" download class="flex items-center gap-2 p-2 bg-gray-100 dark:bg-gray-700 rounded-lg mb-2 hover:bg-gray-200 transition-colors"><span>📎</span><span class="text-sm underline">' .
                                                        e($displayName) .
                                                        '</span></a>';
                                                    break;
                                                case 'location':
                                                    $coords = explode(',', $mediaUrl);
                                                    $lat = $coords[0] ?? '';
                                                    $lng = $coords[1] ?? '';
                                                    $mapUrl = "https://www.google.com/maps?q={$lat},{$lng}";
                                                    $mediaHtml =
                                                        '<a href="' .
                                                        $mapUrl .
                                                        '" target="_blank" class="flex items-center gap-2 p-2 bg-gray-100 dark:bg-gray-700 rounded-lg mb-2 hover:bg-gray-200 transition-colors"><span>📍</span><span class="text-sm underline">Xem vị trí</span></a>';
                                                    break;
                                            }
                                        }
                                    }

                                    $isPinned = $log->is_pinned ?? false;
                                    $isStarred = $log->is_starred ?? false;
                                @endphp

                                <div class="flex flex-col {{ $isIncoming ? 'items-start' : 'items-end' }}"
                                    data-message-id="{{ $log->id }}">
                                    <span class="text-xs text-gray-600 dark:text-gray-400 mb-1 px-1 flex items-center gap-1">
                                        @if ($isPinned)
                                            <span title="Đã ghim">📌</span>
                                        @endif
                                        @if ($isStarred)
                                            <span title="Đã gắn sao">⭐</span>
                                        @endif
                                        {{ $isIncoming ? 'Khách hàng' : $log->user->name ?? 'Bạn' }}
                                    </span>

                                    <div
                                        class="relative flex items-start gap-1 {{ $isIncoming ? '' : 'flex-row-reverse' }}">
                                        {{-- Message bubble --}}
                                        <div
                                            class="max-w-[75%] rounded-lg px-4 py-2 text-sm shadow-sm text-black dark:text-white {{ $isIncoming ? 'chat-message-incoming bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-tl-none' : 'chat-message-outgoing bg-blue-600 dark:bg-blue-500 rounded-tr-none' }}">
                                            @if ($replyQuoteHtml)
                                                {!! $replyQuoteHtml !!}
                                            @endif
                                            @if ($mediaHtml)
                                                {!! $mediaHtml !!}
                                            @endif
                                            @if (trim($textContent))
                                                <span class="whitespace-pre-wrap">{{ trim($textContent) }}</span>
                                            @endif
                                        </div>

                                        {{-- Action button with dropdown --}}
                                        <div class="flex-shrink-0 relative">
                                            <button type="button" onclick="toggleMessageMenu({{ $log->id }})"
                                                class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded hover:bg-gray-100 dark:hover:bg-gray-700"
                                                title="Tùy chọn">
                                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                </svg>
                                            </button>

                                            {{-- Dropdown menu - z-index cao, nền solid không trong suốt --}}
                                            <div id="menu-{{ $log->id }}"
                                                class="hidden absolute {{ $isIncoming ? 'left-0' : 'right-0' }} top-full mt-1 w-40 rounded-lg shadow-2xl border border-gray-200 dark:border-gray-700"
                                                style="z-index: 9999; background: #ffffff; isolation: isolate;">
                                                <div class="rounded-lg overflow-hidden" style="background: #ffffff;">
                                                    <style>
                                                        .dark #menu-{{ $log->id }},
                                                        .dark #menu-{{ $log->id }} > div { background: #1f2937 !important; }
                                                    </style>
                                                <button onclick="copyMessage({{ $log->id }})"
                                                    class="w-full px-3 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2 rounded-t-lg text-gray-700 dark:text-gray-200">
                                                    <span>📋</span> Sao chép
                                                </button>
                                                @php
                                                    // Lấy nội dung để hiển thị khi reply (giới hạn 50 ký tự)
                                                    $replyContent = preg_replace('/\[MEDIA:[^\]]+\]/', '[Media]', $textContent ?: ($comment ?? ''));
                                                    $replyContent = preg_replace('/\[REPLY_TO:[^\]]+\]/', '', $replyContent);
                                                    $replyContent = addslashes(substr(trim($replyContent), 0, 50));
                                                    if (empty($replyContent)) $replyContent = '[Media]';
                                                @endphp
                                                <button onclick="replyToMessage({{ $log->id }}, '{{ $replyContent }}', {{ $isIncoming ? 'true' : 'false' }})"
                                                    class="w-full px-3 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2 text-gray-700 dark:text-gray-200">
                                                    <span>↩️</span> Trả lời
                                                </button>
                                                <button onclick="togglePin({{ $log->id }})"
                                                    class="w-full px-3 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2">
                                                    <span>📌</span> {{ $isPinned ? 'Bỏ ghim' : 'Ghim' }}
                                                </button>
                                                <button onclick="toggleStar({{ $log->id }})"
                                                    class="w-full px-3 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2">
                                                    <span>⭐</span> {{ $isStarred ? 'Bỏ sao' : 'Gắn sao' }}
                                                </button>
                                                <button onclick="showMessageInfo({{ $log->id }})"
                                                    class="w-full px-3 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2">
                                                    <span>ℹ️</span> Thông tin
                                                </button>
                                                <button onclick="showForwardModal({{ $log->id }})"
                                                    class="w-full px-3 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2">
                                                    <span>↪️</span> Chuyển tiếp
                                                </button>
                                                <button onclick="deleteMessage({{ $log->id }})"
                                                    class="w-full px-3 py-2 text-left text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center gap-2 rounded-b-lg">
                                                    <span>🗑️</span> Xóa
                                                </button>
                                                </div></div>
                                        </div>
                                    </div>

                                    <span class="text-[10px] text-gray-500 dark:text-gray-400 mt-1 px-1">
                                        {{ $log->created_at->format('H:i d/m/Y') }}
                                    </span>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    {{-- KHU VỰC NHẬP TIN NHẮN - flex-shrink-0 để luôn ở dưới cùng --}}
                    <div class="p-4 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 flex-shrink-0">
                        <div id="whatsapp-status" class="mb-2 text-sm font-medium h-5"></div>

                        {{-- Preview file đã chọn --}}
                        <div id="file-preview"
                            class="hidden mb-3 p-3 bg-gray-100 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span id="file-name"
                                        class="text-sm text-gray-700 dark:text-gray-300 truncate max-w-[200px]"></span>
                                    <span id="file-size" class="text-xs text-gray-500"></span>
                                </div>
                                <button type="button" onclick="clearSelectedFile()"
                                    class="text-gray-500 hover:text-red-500 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <form id="whatsapp-reply-form" class="flex flex-col gap-3" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                            <input type="hidden" name="reply_to_id" id="reply-to-id" value="">
                            
                            {{-- Reply Preview - Hiện khi đang reply tin nhắn --}}
                            <div id="reply-preview" class="hidden bg-gray-100 dark:bg-gray-800 rounded-lg p-2 mb-2 flex items-start gap-2 border-l-4 border-blue-500">
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs text-blue-600 dark:text-blue-400 font-medium" id="reply-sender">Đang trả lời</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-300 truncate" id="reply-content">Nội dung tin nhắn</div>
                                </div>
                                <button type="button" onclick="cancelReply()" class="text-gray-400 hover:text-red-500 p-1">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            {{-- Input file ẩn --}}
                            <input type="file" id="whatsapp-file" name="file" class="hidden"
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv,.xml,.odt,.ods,.odp,.rtf,.tex,.zip,.rar,.7z,.tar,.gz,.jpg,.jpeg,.png,.gif,.webp,.mp4,.avi,.mov,.3gp,.mkv,.mp3,.wav,.ogg,.aac,.m4a,.amr"
                                onchange="handleFileSelect(this)">

                            <div class="relative flex items-end gap-2">
                                {{-- Nút đính kèm file --}}
                                <button type="button" onclick="document.getElementById('whatsapp-file').click()"
                                    class="p-2 text-gray-500 hover:text-blue-600 transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800"
                                    title="Đính kèm file">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                </button>

                                <textarea name="message" id="whatsapp-message" rows="1"
                                    class="flex-1 resize-none rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white"
                                    placeholder="Nhập tin nhắn hoặc đính kèm file... (Enter để gửi, Shift+Enter xuống dòng)"
                                    onkeydown="if(event.key==='Enter' && !event.shiftKey){event.preventDefault();sendWhatsApp();}"></textarea>

                                <button type="button" onclick="sendWhatsApp()"
                                    class="p-2 text-gray-500 hover:text-blue-600 transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                </button>
                            </div>
                        </form>

                        <p class="text-xs text-gray-400 mt-2">
                            📎 File: PDF, DOC, XLS, ZIP... | 🖼️ Ảnh: JPG, PNG | 🎬 Video: MP4, AVI | 🎵 Audio: MP3, WAV
                            (max 100MB)
                        </p>
                    </div>

                    <script>
                        // ENHANCED: Scroll to bottom when page loads
                        function scrollChatToBottom(force = false) {
                            var chatArea = document.getElementById("chat-scroll-area");
                            if (chatArea) {
                                // Force scroll to absolute bottom
                                chatArea.scrollTop = chatArea.scrollHeight;
                                
                                if (force) {
                                    console.log('[Chat] FORCE Scrolled to bottom:', chatArea.scrollHeight, 'scrollTop:', chatArea.scrollTop);
                                } else {
                                    console.log('[Chat] Scrolled to bottom:', chatArea.scrollHeight);
                                }
                            } else {
                                console.warn('[Chat] Element #chat-scroll-area not found!');
                            }
                        }
                        
                        // Multiple aggressive triggers to ensure scroll happens
                        // Kích hoạt ngay khi DOM ready
                        if (document.readyState === 'loading') {
                            document.addEventListener('DOMContentLoaded', function() {
                                console.log('[Chat] DOMContentLoaded - starting scroll sequence');
                                scrollChatToBottom(true);
                                // Scroll lại nhiều lần với timeout tăng dần
                                setTimeout(() => scrollChatToBottom(true), 50);
                                setTimeout(() => scrollChatToBottom(true), 150);
                                setTimeout(() => scrollChatToBottom(true), 300);
                                setTimeout(() => scrollChatToBottom(true), 600);
                                setTimeout(() => scrollChatToBottom(true), 1000);
                                setTimeout(() => scrollChatToBottom(true), 1500);
                            });
                        } else {
                            // DOM đã ready rồi, scroll ngay
                            console.log('[Chat] DOM already ready - scrolling immediately');
                            scrollChatToBottom(true);
                            setTimeout(() => scrollChatToBottom(true), 50);
                            setTimeout(() => scrollChatToBottom(true), 150);
                            setTimeout(() => scrollChatToBottom(true), 300);
                            setTimeout(() => scrollChatToBottom(true), 600);
                            setTimeout(() => scrollChatToBottom(true), 1000);
                        }
                        
                        // Scroll khi window load hoàn toàn (bao gồm images)
                        window.addEventListener('load', function() {
                            console.log('[Chat] Window loaded - final scroll');
                            scrollChatToBottom(true);
                            setTimeout(() => scrollChatToBottom(true), 100);
                            setTimeout(() => scrollChatToBottom(true), 500);
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>

    @pushOnce('scripts')
        <script>
            // Biến toàn cục để theo dõi tin nhắn cuối cùng
            window.lastMessageId = {{ $lead->activities->where('type', 'whatsapp')->max('id') ?? 0 }};
            window.leadId = {{ $lead->id }};
            window.pollingInterval = null;
            window.selectedFile = null;
            window.readReceiptSent = false; // Flag để tránh gửi Read Receipt nhiều lần

            // ==========================================
            // READ RECEIPT - Đánh dấu tin nhắn đã đọc
            // ==========================================
            window.markMessagesAsRead = function() {
                // Chỉ gửi Read Receipt 1 lần cho mỗi session chat
                if (window.readReceiptSent) {
                    console.log('[ReadReceipt] Already sent for this session, skipping');
                    return;
                }
                
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content 
                           || document.querySelector('input[name="_token"]')?.value;
                
                if (!csrf) {
                    console.error('[ReadReceipt] CSRF token not found');
                    return;
                }
                
                console.log('[ReadReceipt] Sending read receipts for lead:', window.leadId);
                
                fetch(`/admin/leads/${window.leadId}/whatsapp-mark-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.readReceiptSent = true;
                        console.log('[ReadReceipt] Success:', data.message);
                    } else {
                        console.warn('[ReadReceipt] Failed:', data.message);
                    }
                })
                .catch(error => {
                    console.error('[ReadReceipt] Error:', error);
                });
            };
            
            // Debounce function để tránh gọi API quá nhiều lần
            window.debounce = function(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            };
            
            // Debounced version của markMessagesAsRead (đợi 500ms sau action cuối)
            window.debouncedMarkAsRead = window.debounce(window.markMessagesAsRead, 500);

            // Function parse media content từ tag [MEDIA:type:url] hoặc [MEDIA:type:uploaded:filename]
            // isIncoming: true nếu là tin nhắn từ khách hàng, false nếu là tin mình gửi
            function parseMediaContent(comment, isIncoming = false) {
                if (!comment) return '';

                // Xác định class màu text dựa trên loại tin nhắn
                const textColorClass = isIncoming ? 'text-gray-900 dark:text-gray-100' : 'text-white';
                
                // Xử lý REPLY_TO tag để tạo clickable reply quote
                // Format mới: [REPLY_TO:activityId:content]
                // Format cũ (fallback): [REPLY_TO:content]
                let replyQuoteHtml = '';
                const replyRegexNew = /\[REPLY_TO:(\d+):([^\]]*)\]/;
                const replyRegexOld = /\[REPLY_TO:([^\]]+)\]/;
                
                let replyMatch = comment.match(replyRegexNew);
                let replyId = null;
                let replyContent = '';
                
                if (replyMatch) {
                    // Format mới với ID
                    replyId = replyMatch[1];
                    replyContent = replyMatch[2] || 'Tin nhắn trước đó';
                    comment = comment.replace(replyRegexNew, '').trim();
                } else {
                    // Fallback format cũ
                    replyMatch = comment.match(replyRegexOld);
                    if (replyMatch) {
                        replyContent = replyMatch[1];
                        comment = comment.replace(replyRegexOld, '').trim();
                    }
                }
                
                if (replyContent) {
                    const escapedContent = escapeHtml(replyContent).replace(/'/g, "\\'");
                    // Truyền cả ID và content vào scrollToReplyOrigin
                    const onClickHandler = replyId && replyId !== '0' 
                        ? `scrollToReplyOrigin(${replyId}, '${escapedContent}')`
                        : `scrollToReplyOrigin(null, '${escapedContent}')`;
                    
                    replyQuoteHtml = `
                        <div onclick="${onClickHandler}" class="mb-2 p-2 bg-gray-300/50 dark:bg-gray-600/50 rounded border-l-2 border-blue-500 text-xs cursor-pointer hover:bg-gray-400/50 dark:hover:bg-gray-500/50 transition-colors">
                            <div class="text-blue-600 dark:text-blue-400 font-medium">↩️ Trả lời tin nhắn</div>
                            <div class="text-gray-600 dark:text-gray-300 truncate">${escapeHtml(replyContent)}</div>
                        </div>
                    `;
                }

                // Regex match [MEDIA:type:data] where data can be url or uploaded:filename
                const mediaRegex = /\[MEDIA:(\w+):([^\]]+)\]/;
                const match = comment.match(mediaRegex);

                if (!match) {
                    // Không có media, trả về text với line breaks và màu phù hợp
                    return `${replyQuoteHtml}<span class="${textColorClass}">${escapeHtml(comment).replace(/\n/g, '<br>')}</span>`;
                }

                const mediaType = match[1];
                const mediaData = match[2];
                
                // Parse mediaData - xử lý đặc biệt cho URL có http:// hoặc https://
                let isUploaded = false;
                let mediaUrl = null;
                let filename = '';
                
                if (mediaData.startsWith('uploaded:')) {
                    // Format: uploaded:filename
                    isUploaded = true;
                    filename = mediaData.substring(9);
                } else if (mediaData.startsWith('http://') || mediaData.startsWith('https://')) {
                    // Format: http://domain/storage/path hoặc http://domain/storage/path:filename
                    mediaUrl = mediaData;
                    // Tìm : sau / cuối cùng (nếu có filename)
                    const lastSlash = mediaData.lastIndexOf('/');
                    if (lastSlash !== -1) {
                        const afterSlash = mediaData.substring(lastSlash + 1);
                        const colonPos = afterSlash.indexOf(':');
                        if (colonPos !== -1) {
                            mediaUrl = mediaData.substring(0, lastSlash + 1 + colonPos);
                            filename = afterSlash.substring(colonPos + 1);
                        }
                    }
                } else if (mediaData.startsWith('/storage/')) {
                    // Format: /storage/path hoặc /storage/path:filename
                    const colonPos = mediaData.indexOf(':', 9);
                    if (colonPos !== -1) {
                        mediaUrl = mediaData.substring(0, colonPos);
                        filename = mediaData.substring(colonPos + 1);
                    } else {
                        mediaUrl = mediaData;
                    }
                } else {
                    // Format cũ: url:filename (không có http://)
                    const parts = mediaData.split(':');
                    mediaUrl = parts[0];
                    filename = parts.slice(1).join(':') || '';
                }
                
                // Normalize URL - chuyển từ absolute URL sang relative path
                // Ví dụ: http://base_crm1.0.app/storage/... -> /storage/...
                if (!isUploaded && mediaUrl && mediaUrl.includes('/storage/')) {
                    mediaUrl = '/storage/' + mediaUrl.split('/storage/')[1];
                }

                // Lấy text content (phần không phải media tag)
                const textContent = comment.replace(mediaRegex, '').trim();

                let mediaHtml = '';

                if (isUploaded) {
                    // Uploaded media - hiển thị placeholder
                    const displayName = filename || 'File đã gửi';
                    // Sử dụng màu text phù hợp với nền
                    switch (mediaType) {
                        case 'image':
                            mediaHtml = `<div class="flex items-center gap-2 p-2 bg-green-100 dark:bg-green-800 rounded-lg mb-2"><span>🖼️</span><span class="text-sm text-green-800 dark:text-green-100">${escapeHtml(displayName)} (Đã gửi)</span></div>`;
                            break;
                        case 'video':
                            mediaHtml = `<div class="flex items-center gap-2 p-2 bg-green-100 dark:bg-green-800 rounded-lg mb-2"><span>🎬</span><span class="text-sm text-green-800 dark:text-green-100">${escapeHtml(displayName)} (Đã gửi)</span></div>`;
                            break;
                        case 'audio':
                            mediaHtml = `<div class="flex items-center gap-2 p-2 bg-green-100 dark:bg-green-800 rounded-lg mb-2"><span>🎵</span><span class="text-sm text-green-800 dark:text-green-100">${escapeHtml(displayName)} (Đã gửi)</span></div>`;
                            break;
                        case 'sticker':
                            mediaHtml = `<div class="flex items-center gap-2 p-2 bg-green-100 dark:bg-green-800 rounded-lg mb-2"><span>😊</span><span class="text-sm text-green-800 dark:text-green-100">Sticker (Đã gửi)</span></div>`;
                            break;
                        case 'document':
                        default:
                            mediaHtml = `<div class="flex items-center gap-2 p-2 bg-green-100 dark:bg-green-800 rounded-lg mb-2"><span>📎</span><span class="text-sm text-green-800 dark:text-green-100">${escapeHtml(displayName)} (Đã gửi)</span></div>`;
                            break;
                    }
                } else {
                    // Downloaded media - hiển thị với URL
                    // Xác định audio type từ extension
                    let audioType = 'audio/mpeg';
                    if (mediaUrl.endsWith('.ogg')) audioType = 'audio/ogg';
                    else if (mediaUrl.endsWith('.m4a')) audioType = 'audio/mp4';
                    else if (mediaUrl.endsWith('.wav')) audioType = 'audio/wav';
                    else if (mediaUrl.endsWith('.aac')) audioType = 'audio/aac';

                    switch (mediaType) {
                        case 'image':
                        case 'sticker':
                            mediaHtml =
                                `<a href="${mediaUrl}" target="_blank" class="block mb-2"><img src="${mediaUrl}" alt="Hình ảnh" class="max-w-full rounded-lg max-h-48 object-cover hover:opacity-90 transition-opacity" loading="lazy" onerror="this.parentElement.innerHTML='🖼️ [Không tải được ảnh]'"></a>`;
                            break;
                        case 'video':
                            mediaHtml =
                                `<video controls class="max-w-full rounded-lg max-h-48 mb-2" preload="metadata"><source src="${mediaUrl}" type="video/mp4"><source src="${mediaUrl}" type="video/webm">Video không hỗ trợ</video>`;
                            break;
                        case 'audio':
                            mediaHtml =
                                `<div class="flex items-center gap-2 mb-2 p-2 bg-gray-100 dark:bg-gray-700 rounded-lg"><span class="text-lg">🎵</span><audio controls class="flex-1 h-10" preload="metadata"><source src="${mediaUrl}" type="${audioType}">Audio không hỗ trợ</audio></div>`;
                            break;
                        case 'document':
                            const displayName = filename || 'Tài liệu';
                            mediaHtml =
                                `<a href="${mediaUrl}" target="_blank" download class="flex items-center gap-2 p-2 bg-gray-100 dark:bg-gray-700 rounded-lg mb-2 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"><svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg><span class="text-sm text-gray-800 dark:text-gray-200 underline">${escapeHtml(displayName)}</span></a>`;
                            break;
                        case 'location':
                            const coords = mediaUrl.split(',');
                            const lat = coords[0] || '';
                            const lng = coords[1] || '';
                            const mapUrl = `https://www.google.com/maps?q=${lat},${lng}`;
                            mediaHtml =
                                `<a href="${mapUrl}" target="_blank" class="flex items-center gap-2 p-2 bg-gray-100 dark:bg-gray-700 rounded-lg mb-2 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"><svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg><span class="text-sm text-gray-800 dark:text-gray-200 underline">📍 Xem vị trí</span></a>`;
                            break;
                        default:
                            return escapeHtml(comment).replace(/\n/g, '<br>');
                    }
                }

                // Kết hợp media và text
                let result = mediaHtml;
                if (textContent) {
                    // Đảm bảo text có màu sắc phù hợp với background
                    result += `<span class="whitespace-pre-wrap ${textColorClass}">${escapeHtml(textContent)}</span>`;
                }

                return result;
            }

            // Helper function escape HTML
            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }
            
            /**
             * Cuộn đến tin nhắn gốc khi click vào reply quote
             * @param {number|null} messageId - Activity ID của tin nhắn gốc (nếu có)
             * @param {string} replyContent - Nội dung tin nhắn gốc (fallback nếu không có ID)
             */
            function scrollToReplyOrigin(messageId, replyContent) {
                console.log('[Reply] Searching for original message - ID:', messageId, 'Content:', replyContent);
                
                const chatArea = document.getElementById('chat-scroll-area');
                if (!chatArea) {
                    console.error('[Reply] Chat area not found');
                    return;
                }
                
                let foundElement = null;
                
                // Ưu tiên 1: Tìm theo message ID (chính xác 100%)
                if (messageId && messageId !== 0) {
                    foundElement = chatArea.querySelector(`[data-message-id="${messageId}"]`);
                    if (foundElement) {
                        console.log('[Reply] ✅ Found by ID:', messageId);
                    }
                }
                
                // Ưu tiên 2: Fallback tìm theo text content (cho tin nhắn cũ)
                if (!foundElement && replyContent) {
                    console.log('[Reply] ID not found, searching by text...');
                    
                    // Chuẩn hóa text để so sánh
                    function normalizeText(text) {
                        return (text || '')
                            .replace(/\s+/g, ' ')
                            .replace(/[^\w\sàáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđ]/gi, '')
                            .toLowerCase()
                            .trim();
                    }
                    
                    const searchText = normalizeText(replyContent);
                    const messages = chatArea.querySelectorAll('[data-message-id]');
                    let bestMatchScore = 0;
                    
                    messages.forEach(msgElement => {
                        const msgTextElement = msgElement.querySelector('.whitespace-pre-wrap, .max-w-\\[75\\%\\]');
                        if (!msgTextElement) return;
                        
                        const clone = msgTextElement.cloneNode(true);
                        clone.querySelectorAll('[onclick*="scrollToReplyOrigin"]').forEach(el => el.remove());
                        
                        const msgText = normalizeText(clone.textContent);
                        if (!msgText) return;
                        
                        let score = 0;
                        if (msgText === searchText) score = 100;
                        else if (msgText.startsWith(searchText)) score = 80;
                        else if (msgText.includes(searchText)) score = 60;
                        
                        if (score > bestMatchScore) {
                            bestMatchScore = score;
                            foundElement = msgElement;
                        }
                    });
                    
                    if (foundElement) {
                        console.log('[Reply] Found by text match, score:', bestMatchScore);
                    }
                }
                
                // Cuộn và highlight nếu tìm thấy
                if (foundElement) {
                    console.log('[Reply] Scrolling to message ID:', foundElement.dataset.messageId);
                    
                    foundElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                    // Highlight effect
                    const bubble = foundElement.querySelector('.max-w-\\[75\\%\\]');
                    if (bubble) {
                        bubble.classList.add('ring-2', 'ring-blue-500', 'ring-offset-2');
                        bubble.style.transition = 'all 0.3s ease';
                        
                        setTimeout(() => {
                            bubble.classList.remove('ring-2', 'ring-blue-500', 'ring-offset-2');
                        }, 2000);
                    }
                } else {
                    console.warn('[Reply] Original message not found');
                    alert('Không tìm thấy tin nhắn gốc');
                }
            }
            
            // Expose globally
            window.scrollToReplyOrigin = scrollToReplyOrigin;
            
            /**
             * Generate full message bubble HTML with action menu
             */
            function generateMessageBubbleHtml(options) {
                const { msgId, isIncoming, senderName, content, timeString, rawComment } = options;
                
                const bubbleClass = isIncoming ? 'items-start' : 'items-end';
                const messageTypeClass = isIncoming ? 'chat-message-incoming' : 'chat-message-outgoing';
                const contentClass = isIncoming 
                    ? 'bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-700 rounded-tl-none' 
                    : 'bg-blue-600 dark:bg-blue-500 rounded-tr-none';
                const menuPosition = isIncoming ? 'left-0' : 'right-0';
                const flexDirection = isIncoming ? '' : 'flex-row-reverse';
                const escapedComment = escapeHtml(rawComment || '').replace(/'/g, "\\'").substring(0, 50) || '[Media]';
                
                // Màu chữ: đen (light mode) / trắng (dark mode) cho TẤT CẢ tin nhắn
                const textColorClass = 'text-black dark:text-white';
                
                return `
                <div class="flex flex-col ${bubbleClass}" data-message-id="${msgId}">
                    <span class="text-xs text-gray-600 dark:text-gray-400 mb-1 px-1">${senderName}</span>
                    <div class="relative flex items-start gap-1 ${flexDirection}">
                        <div class="max-w-[75%] rounded-lg px-4 py-2 text-sm shadow-sm ${textColorClass} ${messageTypeClass} ${contentClass}">
                            ${content}
                        </div>
                        <div class="flex-shrink-0 relative">
                            <button type="button" onclick="toggleMessageMenu(${msgId})" class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded hover:bg-gray-100 dark:hover:bg-gray-700" title="Tùy chọn">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/></svg>
                            </button>
                            <div id="menu-${msgId}" class="hidden absolute ${menuPosition} top-full mt-1 w-40 rounded-lg shadow-2xl border border-gray-200 dark:border-gray-700" style="z-index: 9999; background: #ffffff;">
                                <div class="rounded-lg overflow-hidden bg-white dark:bg-gray-800">
                                    <button onclick="copyMessage(${msgId})" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2 rounded-t-lg text-gray-700 dark:text-gray-200"><span>📋</span> Sao chép</button>
                                    <button onclick="replyToMessage(${msgId}, '${escapedComment}', ${isIncoming})" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2 text-gray-700 dark:text-gray-200"><span>↩️</span> Trả lời</button>
                                    <button onclick="togglePin(${msgId})" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2 text-gray-700 dark:text-gray-200"><span>📌</span> Ghim</button>
                                    <button onclick="toggleStar(${msgId})" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2 text-gray-700 dark:text-gray-200"><span>⭐</span> Gắn sao</button>
                                    <button onclick="showMessageInfo(${msgId})" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2 text-gray-700 dark:text-gray-200"><span>ℹ️</span> Thông tin</button>
                                    <button onclick="showForwardModal(${msgId})" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2 text-gray-700 dark:text-gray-200"><span>↪️</span> Chuyển tiếp</button>
                                    <button onclick="deleteMessage(${msgId})" class="w-full px-3 py-2 text-left text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center gap-2 rounded-b-lg"><span>🗑️</span> Xóa</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="text-[10px] text-gray-500 dark:text-gray-400 mt-1 px-1">${timeString}</span>
                </div>
                `;
            }



            // ==========================================
            // MESSAGE ACTION HANDLERS
            // ==========================================

            // Toggle dropdown menu
            window.toggleMessageMenu = function(msgId) {
                // Close all other menus
                document.querySelectorAll('[id^="menu-"]').forEach(menu => {
                    if (menu.id !== `menu-${msgId}`) {
                        menu.classList.add('hidden');
                    }
                });

                const menu = document.getElementById(`menu-${msgId}`);
                if (menu) {
                    menu.classList.toggle('hidden');
                }
            };

            // Close menus when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('[id^="menu-"]') && !e.target.closest('button[onclick^="toggleMessageMenu"]')) {
                    document.querySelectorAll('[id^="menu-"]').forEach(menu => menu.classList.add('hidden'));
                }
            });
            
            // Toast notification function
            window.showToast = function(message, duration = 3000) {
                // Remove existing toast if any
                const existingToast = document.getElementById('toast-notification');
                if (existingToast) existingToast.remove();
                
                const toast = document.createElement('div');
                toast.id = 'toast-notification';
                toast.className = 'fixed bottom-4 right-4 bg-gray-800 text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-pulse';
                toast.textContent = message;
                document.body.appendChild(toast);
                
                setTimeout(() => toast.remove(), duration);
            };
            
            // Reply to message - show reply preview
            window.replyToMessage = function(msgId, content, isIncoming) {
                toggleMessageMenu(msgId);
                
                const replyPreview = document.getElementById('reply-preview');
                const replyContent = document.getElementById('reply-content');
                const replySender = document.getElementById('reply-sender');
                const replyToIdInput = document.getElementById('reply-to-id');
                
                if (replyPreview && replyContent && replySender && replyToIdInput) {
                    replyPreview.classList.remove('hidden');
                    replyContent.textContent = content || '[Tin nhắn]';
                    replySender.textContent = isIncoming ? 'Khách hàng' : 'Bạn';
                    replyToIdInput.value = msgId;
                    
                    // Focus vào input
                    const messageInput = document.getElementById('whatsapp-message');
                    if (messageInput) messageInput.focus();
                } else {
                    showToast('❌ Không thể tìm thấy khu vực reply preview');
                }
            };
            
            // Cancel reply
            window.cancelReply = function() {
                const replyPreview = document.getElementById('reply-preview');
                const replyToIdInput = document.getElementById('reply-to-id');
                
                if (replyPreview) replyPreview.classList.add('hidden');
                if (replyToIdInput) replyToIdInput.value = '';
                window.sentWithReplyTo = null;
            };

            // Copy message to clipboard
            window.copyMessage = function(msgId) {
                const msgDiv = document.querySelector(`[data-message-id="${msgId}"]`);
                if (msgDiv) {
                    const textSpan = msgDiv.querySelector('.whitespace-pre-wrap');
                    const text = textSpan ? textSpan.textContent : '';
                    navigator.clipboard.writeText(text).then(() => {
                        showToast('✅ Đã sao chép tin nhắn');
                    }).catch(err => {
                        showToast('❌ Không thể sao chép');
                    });
                }
                toggleMessageMenu(msgId);
            };

            // Toggle pin
            window.togglePin = function(msgId) {
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
                fetch(`/admin/whatsapp/message/${msgId}/pin`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message);
                            // Reload page to update banner (simpler approach)
                            setTimeout(() => location.reload(), 300);
                        } else {
                            showToast('❌ ' + data.message);
                        }
                    });
                toggleMessageMenu(msgId);
            };

            // Scroll to message (for pinned messages banner)
            window.scrollToMessage = function(msgId) {
                const chatArea = document.getElementById('chat-scroll-area');
                const targetMsg = document.querySelector(`[data-message-id="${msgId}"]`);
                
                if (!chatArea || !targetMsg) {
                    showToast('❌ Không tìm thấy tin nhắn');
                    return;
                }
                
                // Scroll to the message
                targetMsg.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Add highlight effect
                targetMsg.classList.add('message-highlight');
                
                // Remove highlight after animation
                setTimeout(() => {
                    targetMsg.classList.remove('message-highlight');
                    // Also gradually remove background color
                    targetMsg.style.backgroundColor = '';
                }, 2500);
            };

            // Toggle star
            window.toggleStar = function(msgId) {
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
                fetch(`/admin/whatsapp/message/${msgId}/star`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message);
                            setTimeout(() => location.reload(), 500);
                        } else {
                            showToast('❌ ' + data.message);
                        }
                    });
                toggleMessageMenu(msgId);
            };

            // Show message info
            window.showMessageInfo = function(msgId) {
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
                fetch(`/admin/whatsapp/message/${msgId}/info`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            const info = data.info;
                            alert(`📩 Thông tin tin nhắn #${info.id}\n\n` +
                                `Loại: ${info.type === 'incoming' ? 'Tin đến' : 'Tin đi'}\n` +
                                `Người gửi: ${info.sender}\n` +
                                `Thời gian: ${info.created_at}\n` +
                                `Lead: ${info.lead_name}\n` +
                                `Khách hàng: ${info.person_name}\n` +
                                `Ghim: ${info.is_pinned ? 'Có' : 'Không'}\n` +
                                `Sao: ${info.is_starred ? 'Có' : 'Không'}`);
                        }
                    });
                toggleMessageMenu(msgId);
            };

            // Show forward modal
            window.showForwardModal = function(msgId) {
                const leadId = prompt('Nhập ID của Lead muốn chuyển tiếp đến:');
                if (leadId && !isNaN(leadId)) {
                    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
                    fetch(`/admin/whatsapp/message/${msgId}/forward`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                target_lead_id: parseInt(leadId)
                            })
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                showToast('✅ ' + data.message);
                            } else {
                                showToast('❌ ' + data.message);
                            }
                        });
                }
                toggleMessageMenu(msgId);
            };

            // Delete message
            window.deleteMessage = function(msgId) {
                if (confirm('Bạn có chắc muốn xóa tin nhắn này? (Chỉ xóa trong CRM, không xóa trên WhatsApp)')) {
                    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
                    
                    console.log('[Delete] Attempting to delete message:', msgId);
                    
                    fetch(`/admin/whatsapp/message/${msgId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json'
                            }
                        })
                        .then(r => {
                            console.log('[Delete] Response status:', r.status);
                            if (!r.ok) {
                                throw new Error(`HTTP ${r.status}: ${r.statusText}`);
                            }
                            return r.json();
                        })
                        .then(data => {
                            console.log('[Delete] Response data:', data);
                            if (data.success) {
                                // Remove message from DOM
                                const msgDiv = document.querySelector(`[data-message-id="${msgId}"]`);
                                if (msgDiv) msgDiv.remove();
                                showToast('✅ ' + data.message);
                            } else {
                                showToast('❌ ' + (data.message || 'Lỗi không xác định'));
                            }
                        })
                        .catch(err => {
                            console.error('[Delete] Error:', err);
                            showToast('❌ Lỗi xóa tin nhắn: ' + err.message);
                        });
                }
                toggleMessageMenu(msgId);
            };

            // Simple toast notification
            function showToast(message) {
                const toast = document.createElement('div');
                toast.className =
                    'fixed bottom-4 right-4 bg-gray-800 text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-fade-in';
                toast.textContent = message;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            }

            // Xử lý khi chọn file
            window.handleFileSelect = function(input) {
                if (input.files && input.files[0]) {
                    const file = input.files[0];
                    window.selectedFile = file;

                    // Hiển thị preview
                    const preview = document.getElementById('file-preview');
                    const fileName = document.getElementById('file-name');
                    const fileSize = document.getElementById('file-size');

                    if (preview && fileName && fileSize) {
                        fileName.textContent = file.name;
                        fileSize.textContent = formatFileSize(file.size);
                        preview.classList.remove('hidden');
                    }

                    console.log('[WhatsApp] File selected:', file.name, formatFileSize(file.size));
                }
            };

            // Xóa file đã chọn
            window.clearSelectedFile = function() {
                window.selectedFile = null;
                const fileInput = document.getElementById('whatsapp-file');
                const preview = document.getElementById('file-preview');

                if (fileInput) fileInput.value = '';
                if (preview) preview.classList.add('hidden');

                console.log('[WhatsApp] File cleared');
            };

            // Format kích thước file
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            window.sendWhatsApp = function() {
                let messageField = document.getElementById('whatsapp-message');
                let fileInput = document.getElementById('whatsapp-file');
                let message = messageField ? messageField.value : '';
                let file = fileInput && fileInput.files[0] ? fileInput.files[0] : null;
                let leadId = {{ $lead->id }};
                let csrfInput = document.querySelector('input[name="_token"]');
                let csrf = csrfInput ? csrfInput.value : document.querySelector('meta[name="csrf-token"]')?.content;
                let statusDiv = document.getElementById('whatsapp-status');

                console.log('[WhatsApp Debug] Starting send...');
                console.log('[WhatsApp Debug] Message:', message);
                console.log('[WhatsApp Debug] File:', file ? file.name : 'None');
                console.log('[WhatsApp Debug] Lead ID:', leadId);

                // Validate: phải có message hoặc file
                if (!message.trim() && !file) {
                    if (statusDiv) {
                        statusDiv.innerHTML = '<span style="display: inline-flex; align-items: center; gap: 8px;"><span style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: #f3f4f6; border-radius: 8px;"><svg style="width: 20px; height: 20px; color: #6b7280;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></span><span style="color: #6b7280; font-size: 14px;">Vui lòng nhập tin nhắn hoặc chọn file</span></span>';
                    }
                    return;
                }

                if (!csrf) {
                    if (statusDiv) statusDiv.innerHTML =
                        '<span class="status-error"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> Lỗi: Không tìm thấy CSRF token</span>';
                    console.error('[WhatsApp Debug] CSRF token not found!');
                    return;
                }

                // Hiệu ứng UX: Disable và hiện đang gửi
                if (messageField) messageField.disabled = true;
                if (statusDiv) {
                    const sendingText = file ? 'Đang tải file lên và gửi...' : 'Đang gửi...';
                    statusDiv.innerHTML =
                        `<span style="display: inline-flex; align-items: center; gap: 8px; color: #9ca3af;"><span style="display: inline-block; width: 16px; height: 16px; border: 2px solid #3b82f6; border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite;"></span> ${sendingText}</span>`;
                }

                // Tạo FormData
                const formData = new FormData();
                formData.append('message', message);
                formData.append('phone_number', document.querySelector('select[name="phone_number"]')?.value || '');
                
                // Thêm reply_to_id nếu đang reply
                const replyToIdInput = document.getElementById('reply-to-id');
                const replyToId = replyToIdInput ? replyToIdInput.value : '';
                
                // LƯU NỘI DUNG REPLY TRƯỚC KHI CANCEL
                let savedReplyContent = null;
                if (replyToId) {
                    formData.append('reply_to_id', replyToId);
                // Lưu nội dung reply content để hiển thị sau khi gửi
                    const replyContentEl = document.getElementById('reply-content');
                    savedReplyContent = replyContentEl ? replyContentEl.textContent : null;
                    console.log('[WhatsApp Debug] Reply to ID:', replyToId, 'Content:', savedReplyContent);
                }
                
                // Lưu vào biến toàn cục để sử dụng trong success handler
                window.pendingReplyContent = savedReplyContent;
                window.pendingReplyId = replyToId;

                if (file) {
                    formData.append('file', file);
                }
                
                // Ẩn reply preview sau khi lưu content
                cancelReply();

                const url = `/admin/leads/${leadId}/whatsapp-reply`;
                console.log('[WhatsApp Debug] Sending to URL:', url);

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => {
                        console.log('[WhatsApp Debug] Response status:', response.status);
                        if (!response.ok) {
                            return response.text().then(text => {
                                console.error('[WhatsApp Debug] Error response:', text);
                                throw new Error(`HTTP ${response.status}: ${text.substring(0, 200)}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('[WhatsApp Debug] Response data:', data);
                        if (messageField) {
                            messageField.disabled = false;
                            messageField.focus();
                        }

                        if (data.success) {
                            if (statusDiv) {
                                statusDiv.innerHTML = '<span style="display: inline-flex; align-items: center; gap: 8px; color: #9ca3af;"><svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Đã gửi thành công!</span>';
                                setTimeout(() => {
                                    statusDiv.innerHTML = '';
                                }, 3000);
                            }

                            // Clear form
                            if (messageField) messageField.value = '';
                            clearSelectedFile();

                            // Append tin nhắn mới vào chat
                            let chatArea = document.getElementById("chat-scroll-area");
                            if (chatArea) {
                                let now = new Date();
                                let timeString = now.getHours() + ":" + String(now.getMinutes()).padStart(2, '0') +
                                    " " + now.getDate() + "/" + (now.getMonth() + 1) + "/" + now.getFullYear();

                                // Tạo nội dung bubble với màu chữ phù hợp (trắng trên nền xanh)
                                let displayContent = '';
                                
                                // Nếu có reply, thêm reply quote vào displayContent
                                // Sử dụng pendingReplyContent đã lưu trước khi gửi
                                if (window.pendingReplyContent) {
                                    const replyContent = window.pendingReplyContent;
                                    const replyId = window.pendingReplyId || null;
                                    displayContent += `
                                        <div onclick="scrollToReplyOrigin(${replyId}, '${escapeHtml(replyContent).replace(/'/g, "\\'")}')" class="mb-2 p-2 bg-gray-200 dark:bg-gray-600 rounded border-l-2 border-blue-500 text-xs cursor-pointer hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors">
                                            <div class="text-blue-600 dark:text-blue-400 font-medium">↩️ Trả lời</div>
                                            <div class="text-gray-700 dark:text-gray-200 truncate">${escapeHtml(replyContent)}</div>
                                        </div>
                                    `;
                                    console.log('[WhatsApp Debug] Added reply quote:', replyContent);
                                    window.pendingReplyContent = null;
                                    window.pendingReplyId = null;
                                }
                                
                                if (file) {
                                    displayContent +=
                                        `<div class="flex items-center gap-1 mb-1"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg> ${escapeHtml(file.name)}</div>`;
                                    if (message.trim()) {
                                        displayContent += `<span>${escapeHtml(message).replace(/\n/g, '<br>')}</span>`;
                                    }
                                } else {
                                    displayContent += `<span>${escapeHtml(message).replace(/\n/g, '<br>')}</span>`;
                                }

                                // Sử dụng generateMessageBubbleHtml để có action menu
                                const newBubble = generateMessageBubbleHtml({
                                    msgId: data.activity_id || 0,
                                    isIncoming: false,
                                    senderName: 'Bạn (Vừa xong)',
                                    content: displayContent,
                                    timeString: timeString,
                                    rawComment: message
                                });

                                chatArea.insertAdjacentHTML('beforeend', newBubble);
                                chatArea.scrollTop = chatArea.scrollHeight;

                                if (data.activity_id) {
                                    window.lastMessageId = data.activity_id;
                                }
                            }
                        } else {
                            if (statusDiv) {
                                statusDiv.innerHTML = '<span class="status-error"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> ' + (data.message ||
                                    'Lỗi gửi tin') + '</span>';
                            }
                        }
                    })
                    .catch(error => {
                        if (messageField) messageField.disabled = false;
                        console.error('[WhatsApp Debug] Catch error:', error);
                        if (statusDiv) {
                            statusDiv.innerHTML = '<span class="status-error"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> ' + error.message + '</span>';
                        }
                    });
            };

            // Hàm polling để lấy tin nhắn mới
            window.pollNewMessages = function() {
                const chatArea = document.getElementById("chat-scroll-area");
                if (!chatArea) return;

                const csrfInput = document.querySelector('input[name="_token"]');
                const csrf = csrfInput ? csrfInput.value : document.querySelector('meta[name="csrf-token"]')?.content;

                fetch(`/admin/leads/${window.leadId}/whatsapp-new-messages?after=${window.lastMessageId}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.messages && data.messages.length > 0) {
                            console.log('[WhatsApp Poll] Found', data.messages.length, 'new messages');
                            
                            let hasNewIncoming = false;

                            data.messages.forEach(msg => {
                                // Kiểm tra xem tin nhắn đã tồn tại chưa
                                if (chatArea.querySelector(`[data-message-id="${msg.id}"]`)) {
                                    return;
                                }

                                const isIncoming = msg.title && msg.title.includes('đến');
                                const senderName = isIncoming ? 'Khách hàng' : (msg.user_name || 'Bạn');
                                
                                // Đánh dấu nếu có tin nhắn incoming mới
                                if (isIncoming) {
                                    hasNewIncoming = true;
                                }

                                // Parse media content - truyền isIncoming để xác định màu text
                                const parsedContent = parseMediaContent(msg.comment || '', isIncoming);
                                
                                // Lấy raw comment để dùng cho reply button
                                const rawComment = (msg.comment || '').replace(/\[MEDIA:[^\]]+\]/g, '[Media]').replace(/\[REPLY_TO:[^\]]+\]/g, '');

                                // Sử dụng generateMessageBubbleHtml để tạo bubble với đầy đủ action menu
                                const newBubble = generateMessageBubbleHtml({
                                    msgId: msg.id,
                                    isIncoming: isIncoming,
                                    senderName: senderName,
                                    content: parsedContent,
                                    timeString: msg.created_at,
                                    rawComment: rawComment
                                });

                                chatArea.insertAdjacentHTML('beforeend', newBubble);

                                // Cập nhật lastMessageId
                                if (msg.id > window.lastMessageId) {
                                    window.lastMessageId = msg.id;
                                }
                            });
                            
                            // Nếu có tin nhắn incoming mới, reset flag và tự động gửi read receipt
                            if (hasNewIncoming) {
                                console.log('[ReadReceipt] New incoming message detected, resetting flag');
                                window.readReceiptSent = false;
                                // Gửi read receipt sau 1 giây (đợi user có thể đọc)
                                setTimeout(function() {
                                    window.markMessagesAsRead();
                                }, 1000);
                            }

                            // Cuộn xuống nếu có tin nhắn mới
                            chatArea.scrollTop = chatArea.scrollHeight;
                        }
                    })
                    .catch(error => {
                        console.error('[WhatsApp Poll] Error:', error);
                    });
            };

            // Bắt đầu polling khi trang load
            document.addEventListener('DOMContentLoaded', function() {
                // Poll mỗi 5 giây
                window.pollingInterval = setInterval(window.pollNewMessages, 5000);
                console.log('[WhatsApp] Auto-refresh started (5s interval)');
                
                // ==========================================
                // READ RECEIPT TRIGGERS
                // ==========================================
                const messageInput = document.getElementById('whatsapp-message');
                const chatScrollArea = document.getElementById('chat-scroll-area');
                
                // Trigger Read Receipt khi user focus vào ô nhập tin nhắn
                if (messageInput) {
                    messageInput.addEventListener('focus', function() {
                        console.log('[ReadReceipt] Textarea focused - triggering read receipt');
                        window.debouncedMarkAsRead();
                    });
                    
                    // Cũng trigger khi bắt đầu gõ
                    messageInput.addEventListener('input', function() {
                        window.debouncedMarkAsRead();
                    });
                }
                
                // Trigger Read Receipt khi user click vào vùng chat
                if (chatScrollArea) {
                    chatScrollArea.addEventListener('click', function() {
                        console.log('[ReadReceipt] Chat area clicked - triggering read receipt');
                        window.debouncedMarkAsRead();
                    });
                    
                    // Trigger khi scroll trong chat (user đang đọc tin nhắn)
                    chatScrollArea.addEventListener('scroll', window.debounce(function() {
                        console.log('[ReadReceipt] Chat area scrolled - triggering read receipt');
                        window.debouncedMarkAsRead();
                    }, 1000));
                }
                
                // Cũng gửi Read Receipt ngay khi mở trang chat (sau 2 giây để đảm bảo trang đã load)
                setTimeout(function() {
                    console.log('[ReadReceipt] Initial page load - triggering read receipt');
                    window.markMessagesAsRead();
                }, 2000);
            });

            // Dừng polling khi rời trang
            window.addEventListener('beforeunload', function() {
                if (window.pollingInterval) {
                    clearInterval(window.pollingInterval);
                }
            });
        </script>
    @endPushOnce
</x-admin::layouts>