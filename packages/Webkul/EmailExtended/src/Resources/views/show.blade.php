<x-admin::layouts>
    <x-slot:title>
        {{ $thread->subject }}
    </x-slot>

    <div class="flex flex-col gap-4">
        <!-- Header -->
        <div class="box-shadow flex items-center justify-between rounded-lg bg-white px-6 py-4 dark:bg-gray-900">
            <div class="flex items-center gap-4">
                <!-- Back Button -->
                <a href="{{ route('admin.mail.folder', $thread->folder) }}" class="secondary-button">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span class="ml-2">Quay lại</span>
                </a>

                <!-- Title -->
                <div class="flex items-center gap-3">
                    <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ $thread->subject ?: '(Không có tiêu đề)' }}
                    </h1>

                    <!-- Badges -->
                    @if(!$thread->is_read)
                        <span class="rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-900 dark:text-blue-300">
                            Chưa đọc
                        </span>
                    @endif

                    @if($thread->tags)
                        @foreach($thread->tags as $tag)
                            <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                {{ $tag }}
                            </span>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-2">
                <!-- Star -->
                <button
                    type="button"
                    onclick="toggleStar({{ $thread->id }})"
                    class="secondary-button"
                    title="{{ $thread->is_starred ? 'Bỏ đánh dấu' : 'Đánh dấu' }}"
                >
                    <svg class="{{ $thread->is_starred ? 'star-filled' : 'star-empty' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </button>

                <!-- Archive -->
                <button
                    type="button"
                    onclick="moveToFolder({{ $thread->id }}, 'archive')"
                    class="primary-button"
                    title="Lưu trữ"
                >
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                    <span class="ml-2">Lưu trữ</span>
                </button>

                <!-- Delete -->
                <button
                    type="button"
                    onclick="deleteThread({{ $thread->id }})"
                    class="secondary-button hover:!border-red-500 hover:!text-red-600 dark:hover:!text-red-400"
                    title="Xóa"
                >
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Thread Conversation -->
        <div class="flex gap-4">
            <!-- Email List -->
            <div class="flex-1">
                @forelse($thread->emails as $email)
                    <div class="box-shadow mb-4 rounded-lg bg-white p-6 dark:bg-gray-900">
                        <!-- Email Header -->
                        <div class="mb-4 flex items-start justify-between border-b pb-4 dark:border-gray-800">
                            <div class="flex gap-4">
                                <!-- Avatar -->
                                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-lg font-semibold text-blue-600 dark:bg-blue-900 dark:text-blue-300">
                                    {{ strtoupper(substr($email->name ?? format_email_addresses($email->from, 1), 0, 2)) }}
                                </div>

                                <!-- Sender Info -->
                                <div class="flex flex-col">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-gray-900 dark:text-white">
                                            {{ $email->name ?? format_email_addresses($email->from, 1) }}
                                        </span>
                                        
                                        <!-- Single Combined Status Badge -->
                                        @php
                                            $badgeConfig = match(true) {
                                                // Priority 1: Scheduled emails
                                                $email->status === 'scheduled' => [
                                                    'text' => 'Chờ gửi (' . \Carbon\Carbon::parse($email->scheduled_at)->format('d/m H:i') . ')',
                                                    'color' => 'bg-orange-100 text-orange-700 dark:bg-orange-900 dark:text-orange-300',
                                                    'icon' => 'icon-clock'
                                                ],
                                                
                                                // Priority 2: Draft emails
                                                $email->status === 'draft' => [
                                                    'text' => 'Nháp',
                                                    'color' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                                    'icon' => 'icon-edit'
                                                ],
                                                
                                                // Priority 3: Failed emails
                                                $email->status === 'failed' => [
                                                    'text' => 'Gửi thất bại',
                                                    'color' => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                                                    'icon' => 'icon-alert-circle'
                                                ],
                                                
                                                // Priority 4: Queued/Processing emails (outbound)
                                                $email->status === 'queued' && $email->direction === 'outbound' => [
                                                    'text' => 'Đang gửi',
                                                    'color' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300',
                                                    'icon' => 'icon-time'
                                                ],
                                                
                                                // Priority 5: Sent emails (outbound)
                                                $email->status === 'sent' && $email->direction === 'outbound' => [
                                                    'text' => 'Đã gửi',
                                                    'color' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
                                                    'icon' => 'icon-check-circle'
                                                ],
                                                
                                                // Priority 6: Received emails (inbound)
                                                $email->direction === 'inbound' => [
                                                    'text' => 'Đã nhận',
                                                    'color' => 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300',
                                                    'icon' => 'icon-mail'
                                                ],
                                                
                                                // Default fallback
                                                default => [
                                                    'text' => ucfirst($email->status ?? $email->direction ?? 'unknown'),
                                                    'color' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                                    'icon' => 'icon-info'
                                                ]
                                            };
                                        @endphp

                                        <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $badgeConfig['color'] }}">
                                            <i class="{{ $badgeConfig['icon'] }}"></i>
                                            {{ $badgeConfig['text'] }}
                                        </span>

                                        <!-- Tracking Status Icons -->
                                        @if($email->isOutbound() && isset($trackingStats[$email->id]))
                                            @if($email->wasOpened())
                                                <span class="text-green-600" title="Đã mở">
                                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                                    </svg>
                                                </span>
                                            @endif
                                            @if($email->wasClicked())
                                                <span class="text-blue-600" title="Đã click">
                                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M3 3v18h18V3H3zm8 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3z"/>
                                                    </svg>
                                                </span>
                                            @endif
                                        @endif
                                    </div>

                                    <!-- Recipients -->
                                    <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        <span>Đến: {{ format_email_addresses($email->to, 3) }}</span>
                                        
                                        @if($email->cc)
                                            <span class="ml-3">CC: {{ format_email_addresses($email->cc, 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Time -->
                            <div class="flex flex-col items-end gap-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ email_time_ago($email->created_at) }}
                                </span>
                                
                                <div class="flex gap-1">
                                    <button 
                                        type="button" 
                                        onclick="showQuickReply({{ $email->id }})"
                                        class="secondary-button text-sm"
                                        title="Trả lời nhanh"
                                    >
                                        <i class="icon-reply"></i>
                                    </button>
                                    
                                    <a 
                                        href="{{ route('admin.mail.compose') }}?forward_from={{ $email->id }}"
                                        class="secondary-button text-sm"
                                        title="Chuyển tiếp"
                                    >
                                        <i class="icon-forward"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Email Content -->
                        <div class="prose max-w-none text-gray-800 dark:prose-invert dark:text-gray-200">
                            {!! $email->rendered_content ?? $email->reply !!}
                        </div>

                        <!-- Attachments -->
                        @if($email->attachments && $email->attachments->count() > 0)
                            <div class="mt-4 border-t pt-4 dark:border-gray-800">
                                <h4 class="mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Tệp đính kèm ({{ $email->attachments->count() }})</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($email->attachments as $attachment)
                                        
                                            href="{{ route('admin.mail.download', $attachment->id) }}"
                                            class="flex items-center gap-2 rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm transition hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700"
                                            target="_blank"
                                        >
                                            <i class="icon-attachment text-lg text-gray-600 dark:text-gray-400"></i>
                                            <span class="text-gray-800 dark:text-gray-200">{{ $attachment->name }}</span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">({{ human_filesize($attachment->size) }})</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Tracking Stats -->
                        @if($email->isOutbound() && isset($trackingStats[$email->id]))
                            <div class="mt-4 border-t pt-4 dark:border-gray-800">
                                <h4 class="mb-3 text-sm font-semibold text-gray-700 dark:text-gray-300">Thống kê theo dõi</h4>
                                <div class="grid grid-cols-4 gap-3">
                                    <div class="rounded-lg border border-gray-200 bg-white p-4 text-center dark:border-gray-700 dark:bg-gray-800">
                                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                            {{ $trackingStats[$email->id]['opens'] ?? 0 }}
                                        </div>
                                        <div class="mt-1 text-xs text-gray-600 dark:text-gray-400">Lượt mở</div>
                                    </div>
                                    <div class="rounded-lg border border-gray-200 bg-white p-4 text-center dark:border-gray-700 dark:bg-gray-800">
                                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                            {{ $trackingStats[$email->id]['clicks'] ?? 0 }}
                                        </div>
                                        <div class="mt-1 text-xs text-gray-600 dark:text-gray-400">Lượt click</div>
                                    </div>
                                    <div class="rounded-lg border border-gray-200 bg-white p-4 text-center dark:border-gray-700 dark:bg-gray-800">
                                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                            {{ $trackingStats[$email->id]['unique_opens'] ?? 0 }}
                                        </div>
                                        <div class="mt-1 text-xs text-gray-600 dark:text-gray-400">Mở duy nhất</div>
                                    </div>
                                    <div class="rounded-lg border border-gray-200 bg-white p-4 text-center dark:border-gray-700 dark:bg-gray-800">
                                        <div class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $trackingStats[$email->id]['first_opened_at'] ? email_time_ago($trackingStats[$email->id]['first_opened_at']) : '-' }}
                                        </div>
                                        <div class="mt-1 text-xs text-gray-600 dark:text-gray-400">Mở lần đầu</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="box-shadow rounded-lg bg-white p-8 text-center dark:bg-gray-900">
                        <p class="text-gray-600 dark:text-gray-400">Không có email nào trong luồng này.</p>
                    </div>
                @endforelse

                <!-- Quick Reply Box -->
                @if($thread->emails && $thread->emails->isNotEmpty())
                <div id="quick-reply-box" class="box-shadow rounded-lg bg-white p-6 dark:bg-gray-900" style="display: none;">
                    <div class="mb-4 flex items-center justify-between border-b pb-3 dark:border-gray-800">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Trả lời nhanh</h3>
                        <div class="flex items-center gap-3">
                            <a 
                                href="{{ route('admin.mail.compose') }}?reply_to={{ $thread->emails->first()->id }}"
                                class="text-sm text-blue-600 hover:underline dark:text-blue-400"
                            >
                                Mở trình soạn thảo đầy đủ →
                            </a>
                            <button 
                                type="button" 
                                onclick="hideQuickReply()"
                                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                            >
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <form id="quick-reply-form" action="" method="POST">
                        @csrf
                        <input type="hidden" name="email_id" id="reply-email-id" value="">
                        
                        <!-- Reply Content -->
                        <div class="mb-6">
                            <textarea 
                                name="reply" 
                                id="quick-reply-editor"
                                rows="10" 
                                class="w-full rounded-lg border border-gray-300 p-4 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:border-blue-400"
                                placeholder="Nhập nội dung trả lời..."
                                required
                            ></textarea>
                            <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                                <span id="draft-status"></span>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end gap-3">
                            <!-- Send Now -->
                            <button 
                                type="submit" 
                                name="action" 
                                value="send"
                                id="send-button"
                                class="primary-button px-5 py-2.5"
                            >
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="inline-block">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                <span class="ml-2">Gửi ngay</span>
                            </button>
                            
                            <!-- Schedule -->
                            <button 
                                type="button"
                                onclick="toggleScheduleDropdown()"
                                class="secondary-button relative px-5 py-2.5"
                            >
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="inline-block">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="ml-2">Lên lịch</span>
                            </button>
                        </div>
                        
                        <!-- Schedule Dropdown -->
                        <div id="schedule-dropdown" class="mt-4 hidden rounded-lg border border-gray-300 bg-gray-50 p-5 dark:border-gray-700 dark:bg-gray-800">
                            <label class="mb-3 block text-sm font-medium text-gray-700 dark:text-gray-300">Chọn thời gian gửi</label>
                            <input 
                                type="datetime-local" 
                                name="scheduled_at"
                                id="scheduled-at"
                                class="mb-4 w-full rounded-lg border border-gray-300 p-3 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                            />
                            <button 
                                type="submit" 
                                name="action" 
                                value="schedule"
                                class="primary-button w-full px-5 py-2.5"
                            >
                                Xác nhận lên lịch
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Floating Reply Button -->
                <div id="floating-reply-button" class="sticky bottom-6 mt-6 flex justify-center">
                    <button 
                        type="button"
                        onclick="showQuickReply({{ $thread->emails->first()->id ?? 'null' }})"
                        class="primary-button shadow-lg px-6 py-3"
                    >
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="inline-block">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                        <span class="ml-2 font-medium">Trả lời</span>
                    </button>
                </div>
                @endif
            </div>

            <!-- Sidebar Info -->
            <div class="w-80 flex-shrink-0">
                <div class="box-shadow rounded-lg bg-white p-6 dark:bg-gray-900">
                    <!-- Thread Info -->
                    <div class="mb-4">
                        <h3 class="mb-3 text-base font-semibold text-gray-900 dark:text-white">Thông tin luồng</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Số email:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $thread->email_count }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Người tham gia:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $thread->getParticipantsCount() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Hoạt động cuối:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ email_time_ago($thread->last_email_at) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Participants -->
                    @if($thread->participants)
                        <div class="border-t pt-4 dark:border-gray-800">
                            <h3 class="mb-3 text-base font-semibold text-gray-900 dark:text-white">Người tham gia</h3>
                            <div class="space-y-2">
                                @foreach($thread->participants as $participant)
                                    <div class="flex items-center gap-2">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-xs font-semibold text-blue-600 dark:bg-blue-900 dark:text-blue-300">
                                            {{ strtoupper(substr($participant, 0, 2)) }}
                                        </div>
                                        <span class="text-sm text-gray-800 dark:text-gray-300">{{ $participant }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Lead/Person Info -->
                    @if($thread->lead)
                        <div class="mt-4 border-t pt-4 dark:border-gray-800">
                            <h3 class="mb-2 text-base font-semibold text-gray-900 dark:text-white">Lead liên kết</h3>
                            <a 
                                href="{{ route('admin.leads.view', $thread->lead_id) }}"
                                class="flex items-center gap-2 text-blue-600 hover:underline dark:text-blue-400"
                            >
                                <i class="icon-lead text-lg"></i>
                                {{ $thread->lead->title }}
                            </a>
                        </div>
                    @endif

                    @if($thread->person)
                        <div class="mt-4 border-t pt-4 dark:border-gray-800">
                            <h3 class="mb-2 text-base font-semibold text-gray-900 dark:text-white">Liên hệ</h3>
                            <a 
                                href="{{ route('admin.contacts.persons.view', $thread->person_id) }}"
                                class="flex items-center gap-2 text-blue-600 hover:underline dark:text-blue-400"
                            >
                                <i class="icon-person text-lg"></i>
                                {{ $thread->person->name }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .star-filled {
            color: #f59e0b !important;
            fill: #f59e0b !important;
        }
        
        .star-empty {
            color: #d1d5db !important;
            fill: none !important;
        }
        
        .box-shadow {
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        }
        
        /* Ensure all buttons in header have same size */
        .secondary-button {
            height: 40px !important;
            min-width: 40px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 12px !important;
        }
        
        .secondary-button i,
        .secondary-button svg {
            font-size: 18px !important;
            width: 18px !important;
            height: 18px !important;
        }
        
        .primary-button {
            height: 40px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 16px !important;
        }
        
        .primary-button svg,
        .secondary-button svg {
            flex-shrink: 0;
        }
    </style>
    @endpush

    @push('scripts')
        <script>
            let currentReplyEmailId = null;
            let draftTimeout = null;

            function showQuickReply(emailId) {
                if (!emailId) return;
                currentReplyEmailId = emailId;
            document.getElementById('reply-email-id').value = emailId;
            document.getElementById('quick-reply-form').action = `/admin/mail/${emailId}/reply`;
            
            const replyBox = document.getElementById('quick-reply-box');
            const floatingButton = document.getElementById('floating-reply-button');
            
            // Show reply box
            replyBox.style.display = 'block';
            replyBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Hide floating reply button
            if (floatingButton) {
                floatingButton.style.display = 'none';
            }
            
            document.getElementById('quick-reply-editor').focus();
        }

        function hideQuickReply() {
            const replyBox = document.getElementById('quick-reply-box');
            const floatingButton = document.getElementById('floating-reply-button');
            
            // Hide reply box
            replyBox.style.display = 'none';
            
            // Show floating reply button again
            if (floatingButton) {
                floatingButton.style.display = 'flex';
            }
            
            document.getElementById('quick-reply-editor').value = '';
            currentReplyEmailId = null;
        }

        function toggleScheduleDropdown() {
            const dropdown = document.getElementById('schedule-dropdown');
            dropdown.classList.toggle('hidden');
        }

        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('schedule-dropdown');
            const button = event.target.closest('[onclick="toggleScheduleDropdown()"]');
            
            if (!button && dropdown && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });

        document.getElementById('quick-reply-form')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = e.submitter;
            const originalText = submitButton.innerHTML;
            
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="icon-spinner animate-spin"></i> Đang gửi...';
            
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    showNotification('success', data.message || 'Gửi thành công!');
                    document.getElementById('quick-reply-editor').value = '';
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showNotification('error', data.message || 'Gửi thất bại. Vui lòng thử lại.');
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('error', 'Có lỗi xảy ra. Vui lòng thử lại.');
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        });

        function showNotification(type, message) {
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                info: 'bg-blue-500'
            };
            
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity duration-300`;
            notification.innerHTML = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }

        function toggleStar(threadId) {
            fetch(`/admin/mail/${threadId}/toggle-star`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }

        function moveToFolder(threadId, folder) {
            fetch(`/admin/mail/${threadId}/move`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ folder })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '/admin/mail';
                }
            });
        }

        function deleteThread(threadId) {
            if (confirm('Bạn có chắc chắn muốn xóa luồng này?')) {
                fetch(`/admin/mail/${threadId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = '/admin/mail';
                    }
                });
            }
        }
    </script>
@endpush
</x-admin::layouts>