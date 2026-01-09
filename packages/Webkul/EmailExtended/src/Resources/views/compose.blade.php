<x-admin::layouts>
    <x-slot:title>
        @lang('email_extended::app.compose.title')
    </x-slot>

    @php
        // Xử lý Forward Email
        $forwardEmail = null;
        if(request('forward_from')) {
            $forwardEmail = \Webkul\Email\Models\Email::find(request('forward_from'));
        }
        
        // Xử lý Reply Email
        $replyEmail = null;
        if(request('reply_to')) {
            $replyEmail = \Webkul\Email\Models\Email::find(request('reply_to'));
        }
    @endphp

    <div class="flex gap-4 p-4" style="height: calc(100vh - 180px);">
        <!-- Khu vực soạn email chính -->
        <div class="flex-1 flex flex-col bg-white rounded-lg border border-gray-200 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            
            <!-- Header với các nút action -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <!-- Nút quay lại -->
                    <a href="{{ route('admin.mail.index') }}" 
                       class="flex items-center justify-center w-9 h-9 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                        @if($forwardEmail)
                            Chuyển tiếp Email
                        @elseif($replyEmail)
                            Trả lời Email
                        @else
                            @lang('email_extended::app.compose.title')
                        @endif
                    </h1>
                    
                    <!-- Badge hiển thị loại action -->
                    @if($forwardEmail)
                        <span class="px-3 py-1 text-xs font-medium bg-purple-100 text-purple-700 rounded-full dark:bg-purple-900/30 dark:text-purple-300">
                            Forward
                        </span>
                    @elseif($replyEmail)
                        <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full dark:bg-green-900/30 dark:text-green-300">
                            Reply
                        </span>
                    @endif
                </div>
                
                <div class="flex items-center gap-2">
                    <!-- Nút đóng -->
                    <a href="{{ route('admin.mail.index') }}" 
                       class="flex items-center justify-center w-9 h-9 text-gray-600 hover:bg-gray-100 rounded-lg transition-all dark:text-gray-300 dark:hover:bg-gray-800" 
                       title="Đóng">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Form nội dung email -->
            <x-admin::form
                id="email-compose-form"
                action="{{ isset($email) ? route('admin.mail.update', $email->id) : route('admin.mail.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="flex-1 flex flex-col min-h-0"
                @submit="handleVueFormSubmit"
            >
                @if(isset($email))
                    @method('PUT')
                @endif
                
                <!-- Các trường thông tin email -->
                <div class="px-6 py-4 space-y-3 border-b border-gray-100 dark:border-gray-800 flex-shrink-0">
                    
                    <!-- Trường Từ (From) - Email hệ thống (Khóa) -->
                    <div class="flex items-center gap-3">
                        <label class="w-20 text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Từ:
                        </label>
                        <input
                            type="email"
                            name="from_display"
                            value="{{ config('mail.from.address') }}"
                            class="flex-1 px-4 py-2.5 text-sm text-gray-600 bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400"
                            readonly
                            title="Email hệ thống (tự động)"
                        />
                        <input type="hidden" name="from" value="{{ config('mail.from.address') }}">
                    </div>
                    
                    <!-- Trường Reply-To (Email Sales - Khóa) -->
                    <div class="flex items-center gap-3">
                        <label class="w-20 text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                            </svg>
                            Reply:
                        </label>
                        <input
                            type="email"
                            value="{{ auth()->guard('user')->user()->email ?? config('mail.from.address') }}"
                            class="flex-1 px-4 py-2.5 text-sm text-gray-600 bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400"
                            readonly
                            title="Email nhận phản hồi (tự động từ tài khoản)"
                        />
                    </div>

                    <!-- Trường Đến (To) -->
                    <div class="flex items-center gap-3">
                        <label class="w-20 text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                            Đến:
                        </label>
                        <input
                            type="text"
                            name="to"
                            id="to-field"
                            value="{{ old('to', isset($email) ? format_email_addresses($email->to) : ($replyEmail ? (function() use ($replyEmail) {
                                // Extract email cho Reply
                                if ($replyEmail->direction === 'outbound') {
                                    $to = $replyEmail->to;
                                } else {
                                    $to = $replyEmail->from;
                                }
                                
                                if (is_string($to)) {
                                    $decoded = json_decode($to, true);
                                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                        $to = $decoded;
                                    } else {
                                        return filter_var($to, FILTER_VALIDATE_EMAIL) ? $to : '';
                                    }
                                }
                                
                                if (is_array($to)) {
                                    if (isset($to['email'])) {
                                        return $to['email'];
                                    }
                                    if (isset($to[0])) {
                                        if (is_array($to[0]) && isset($to[0]['email'])) {
                                            return $to[0]['email'];
                                        }
                                        if (is_string($to[0]) && filter_var($to[0], FILTER_VALIDATE_EMAIL)) {
                                            return $to[0];
                                        }
                                    }
                                }
                                
                                return '';
                            })() : request('to'))) }}"
                            class="flex-1 px-4 py-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                            placeholder="recipient@email.com"
                            required
                        />
                        <!-- Nút hiển thị CC/BCC -->
                        <button type="button" 
                                onclick="toggleCcBcc()" 
                                class="px-3 py-2 text-sm font-medium text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg dark:text-blue-400 dark:hover:bg-blue-900/20 whitespace-nowrap">
                            CC/BCC
                        </button>
                    </div>

                    <!-- Trường CC & BCC (ẩn mặc định) -->
                    <div id="ccBccFields" class="hidden space-y-3">
                        <!-- Trường CC -->
                        <div class="flex items-center gap-3">
                            <label class="w-20 text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                CC:
                            </label>
                            <input
                                type="text"
                                name="cc"
                                value="{{ old('cc', isset($email) ? format_email_addresses($email->cc) : '') }}"
                                class="flex-1 px-4 py-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                                placeholder="cc@email.com"
                            />
                        </div>
                        <!-- Trường BCC -->
                        <div class="flex items-center gap-3">
                            <label class="w-20 text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/>
                                </svg>
                                BCC:
                            </label>
                            <input
                                type="text"
                                name="bcc"
                                value="{{ old('bcc', isset($email) ? format_email_addresses($email->bcc) : '') }}"
                                class="flex-1 px-4 py-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                                placeholder="bcc@email.com"
                            />
                        </div>
                    </div>

                    <!-- Trường Chủ đề (Subject) -->
                    <div class="flex items-center gap-3">
                        <label class="w-20 text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            Chủ đề:
                        </label>
                        <input
                            type="text"
                            name="subject"
                            id="subject"
                            value="{{ old('subject', $email->subject ?? ($forwardEmail ? 'Fwd: ' . $forwardEmail->subject : ($replyEmail ? 'Re: ' . preg_replace('/^Re: /i', '', $replyEmail->subject) : request('subject')))) }}"
                            class="flex-1 px-4 py-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                            placeholder="Tiêu đề email"
                            required
                        />
                    </div>
                </div>

                <!-- Khu vực soạn nội dung (TinyMCE Editor) -->
                <div class="flex-1 px-6 py-4 overflow-y-auto min-h-0">
                    <!-- Thanh công cụ phía trên editor -->
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Nội dung email</span>
                        
                        <!-- Nút phóng to -->
                        <button 
                            type="button"
                            onclick="openFullscreenEditor()"
                            class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors dark:text-gray-400 dark:hover:text-blue-400 dark:hover:bg-blue-900/20"
                            title="Mở chế độ toàn màn hình"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                            </svg>
                            <span>Phóng to editor</span>
                        </button>
                    </div>
                    
                    <!-- Editor chính -->
                    <x-admin::form.control-group.control
                        type="textarea"
                        name="reply"
                        id="reply"
                        :value="old('reply', $email->reply ?? '')"
                        :tinymce="true"
                        :prompt="true"
                    />
                </div>

                <!-- Modal Full Screen Editor -->
                <div id="fullscreen-editor-modal" class="hidden fixed inset-0 z-[99999] bg-black/50 backdrop-blur-sm">
                    <div class="w-full h-full flex flex-col bg-white dark:bg-gray-900">
                        
                        <!-- Header Modal -->
                        <div class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200 dark:bg-gray-900 dark:border-gray-700 flex-shrink-0">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Soạn nội dung email</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Chế độ toàn màn hình</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <!-- Nút Lưu & Đóng -->
                                <button 
                                    type="button"
                                    onclick="closeFullscreenEditor()"
                                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Lưu & Đóng
                                </button>
                                
                                <!-- Nút đóng X -->
                                <button 
                                    type="button"
                                    onclick="closeFullscreenEditor()"
                                    class="flex items-center justify-center w-10 h-10 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors dark:text-gray-300 dark:hover:bg-gray-800"
                                    title="Đóng (ESC)"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Nội dung Editor Full Screen -->
                        <div class="flex-1 p-6 overflow-hidden">
                            <textarea id="fullscreen-editor-content" class="w-full h-full"></textarea>
                        </div>
                        
                        <!-- Footer với tips -->
                        <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 dark:bg-gray-800 dark:border-gray-700 flex-shrink-0">
                            <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Nhấn ESC để đóng
                                </span>
                                <span>•</span>
                                <span>Nội dung sẽ được lưu tự động khi đóng</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Khu vực đính kèm tệp -->
                <div class="px-6 py-3 border-t border-gray-100 dark:border-gray-800 flex-shrink-0">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Tệp đính kèm</span>
                    </div>
                    <x-admin::attachments allow-multiple="true" />
                </div>

                <!-- Các trường ẩn -->
                <input type="hidden" name="template_id" id="template_id" value="">
                <input type="hidden" name="lead_id" value="{{ request('lead_id') ?? ($email->lead_id ?? '') }}">
                <input type="hidden" name="person_id" value="{{ request('person_id') ?? ($email->person_id ?? '') }}">
                
                @if($forwardEmail)
                    <input type="hidden" name="forward_from_email_id" value="{{ $forwardEmail->id }}">
                @endif
                
                @if($replyEmail)
                    <input type="hidden" name="reply_to_email_id" value="{{ $replyEmail->id }}">
                    <input type="hidden" name="thread_id" value="{{ $replyEmail->thread_id }}">
                @endif

                <!-- Thanh action ở dưới cùng -->
                <div class="flex items-center justify-between px-6 py-4 bg-gray-50 border-t border-gray-200 dark:bg-gray-800 dark:border-gray-700 flex-shrink-0">
                    <div class="flex items-center gap-3">
                        <!-- Nút GỬI EMAIL -->
                        <button
                            type="button"
                            onclick="submitVueForm('send')"
                            id="btn-send"
                            class="px-6 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            style="background-color: #2299DC !important;"
                        >
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                Gửi Email
                            </span>
                        </button>

                        <!-- Nút LƯU NHÁP -->
                        <button
                            type="button"
                            onclick="submitVueForm('draft')"
                            id="btn-draft"
                            class="px-6 py-2.5 text-gray-700 bg-white border border-gray-300 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700"
                        >
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                                </svg>
                                Lưu nháp
                            </span>
                        </button>
                        
                        <!-- Nút LÊN LỊCH -->
                        <button
                            type="button"
                            onclick="showScheduleModal()"
                            id="btn-schedule"
                            class="px-6 py-2.5 text-gray-700 bg-white border border-gray-300 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700"
                        >
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Lên lịch
                            </span>
                        </button>
                    </div>

                    <!-- Nút XÓA/HỦY -->
                    <button
                        type="button"
                        onclick="if(confirm('Hủy soạn email này?')) window.location.href='{{ route('admin.mail.index') }}'"
                        class="w-10 h-10 text-gray-600 hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors dark:text-gray-400 dark:hover:bg-red-900/20"
                    >
                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </x-admin::form>
        </div>

        <!-- Sidebar bên phải - Danh sách mẫu email -->
        <div class="w-80 flex flex-col gap-4 flex-shrink-0">
            
            <!-- Box Mẫu Email -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm dark:border-gray-700 dark:bg-gray-900 p-5 flex flex-col" style="max-height: calc(100vh - 280px);">
                <div class="flex items-center gap-2 mb-4 flex-shrink-0">
                    <h3 class="font-semibold text-base text-gray-900 dark:text-white">Mẫu Email</h3>
                </div>
            
                <!-- Ô tìm kiếm mẫu -->
                <div class="mb-4 flex-shrink-0">
                    <div class="relative">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input
                            type="text"
                            id="template-search"
                            class="w-full pl-9 pr-4 py-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                            placeholder="Tìm kiếm mẫu..."
                            onkeyup="filterTemplates()"
                        />
                    </div>
                </div>
                
                <!-- Danh sách các mẫu email -->
                <div id="template-list" class="space-y-2 overflow-y-auto flex-1 min-h-0">
                    @forelse($templates ?? [] as $template)
                        <div 
                            class="template-item cursor-pointer rounded-lg border border-gray-200 p-3 hover:border-blue-500 hover:shadow-md dark:border-gray-700 dark:hover:border-blue-500"
                            data-template-id="{{ $template->id }}"
                            data-template-name="{{ strtolower($template->name) }}"
                            onclick="loadTemplate({{ $template->id }})"
                        >
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-sm text-gray-900 dark:text-white truncate">
                                        {{ $template->name }}
                                    </div>
                                    @if($template->description)
                                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400 line-clamp-2">
                                            {{ $template->description }}
                                        </div>
                                    @endif
                                </div>
                                <span class="ml-2 px-2.5 py-1 rounded-full bg-blue-50 text-xs font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 flex-shrink-0">
                                    {{ $template->usage_count ?? 0 }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-8 px-4">
                            <div class="flex items-center justify-center w-14 h-14 mb-3 bg-gray-100 rounded-full dark:bg-gray-800">
                                <svg class="w-7 h-7 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <a href="{{ route('admin.email_templates.create') }}" 
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm"
                            style="background-color: #2299DC !important;">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Tạo mẫu đầu tiên
                            </a>
                        </div>
                    @endforelse
                </div>

                @if(count($templates ?? []) > 0)
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex-shrink-0">
                        <a
                            href="{{ route('admin.email_templates.index') }}"
                            target="_blank"
                            class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg dark:text-blue-400 dark:hover:bg-blue-900/20"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Quản lý mẫu email</span>
                        </a>
                    </div>
                @endif
            </div>

            <!-- Box Mẹo Nhỏ -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border border-blue-200 shadow-sm dark:from-blue-900/20 dark:to-indigo-900/20 dark:border-blue-800 p-5 flex-shrink-0">
                <div class="flex items-center gap-2 mb-3">
                    <div class="flex items-center justify-center w-7 h-7 bg-blue-100 rounded-lg dark:bg-blue-900/50">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h4 class="font-semibold text-sm text-blue-900 dark:text-blue-300">Mẹo nhỏ</h4>
                </div>
                <ul class="space-y-2 text-xs text-blue-800 dark:text-blue-300">
                    <li class="flex items-start gap-2">
                        <svg class="w-3 h-3 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Sử dụng mẫu email tiết kiệm thời gian</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-3 h-3 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Lên lịch gửi vào thời điểm phù hợp</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-3 h-3 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Lưu nháp để tiếp tục sau</span>
                    </li>
                </ul>
            </div>
        </div>

        <div id="schedule-modal" class="fixed z-[9999] hidden" onclick="event.stopPropagation()">
            <div class="w-[380px] rounded-xl bg-white shadow-2xl border-2 border-gray-200 dark:bg-gray-900 dark:border-gray-700">
                <!-- Header -->
                <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3 dark:border-gray-700">
                    <div class="flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30">
                            <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Lên lịch gửi</h3>
                    </div>
                    <button onclick="closeScheduleModal()" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-4 space-y-3 max-h-[400px] overflow-y-auto">
                    <!-- Chọn ngày -->
                    <div>
                        <label class="mb-1.5 flex items-center gap-1.5 text-xs font-medium text-gray-700 dark:text-gray-300">
                            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Chọn ngày gửi
                        </label>
                        <input 
                            type="date" 
                            id="schedule-date" 
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        >
                    </div>

                    <!-- Chọn giờ -->
                    <div>
                        <label class="mb-1.5 flex items-center gap-1.5 text-xs font-medium text-gray-700 dark:text-gray-300">
                            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Chọn giờ gửi
                        </label>
                        <input 
                            type="time" 
                            id="schedule-time" 
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        >
                    </div>

                    <!-- Quick select buttons -->
                    <div>
                        <label class="mb-1.5 flex items-center gap-1.5 text-xs font-medium text-gray-700 dark:text-gray-300">
                            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Chọn nhanh
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" onclick="setQuickSchedule('tomorrow', '09:00')" class="flex items-center justify-center gap-1.5 rounded-lg border border-gray-300 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                Sáng mai 9h
                            </button>
                            <button type="button" onclick="setQuickSchedule('tomorrow', '14:00')" class="flex items-center justify-center gap-1.5 rounded-lg border border-gray-300 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                Chiều mai 14h
                            </button>
                            <button type="button" onclick="setQuickSchedule('nextMonday', '09:00')" class="flex items-center justify-center gap-1.5 rounded-lg border border-gray-300 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Thứ 2 tuần sau
                            </button>
                            <button type="button" onclick="setQuickSchedule('nextWeek', '09:00')" class="flex items-center justify-center gap-1.5 rounded-lg border border-gray-300 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                7 ngày sau
                            </button>
                        </div>
                    </div>

                    <!-- Preview scheduled time -->
                    <div class="rounded-lg bg-blue-50 p-3 dark:bg-blue-900/20">
                        <div class="flex items-start gap-2">
                            <svg class="h-4 w-4 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-xs font-medium text-blue-900 dark:text-blue-300">Email sẽ được gửi vào:</p>
                                <p id="schedule-preview" class="mt-1 text-xs text-blue-700 dark:text-blue-400 font-semibold">
                                    Vui lòng chọn ngày và giờ
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex gap-2 border-t border-gray-200 px-4 py-3 dark:border-gray-700">
                    <button 
                        type="button"
                        onclick="confirmSchedule()" 
                        class="flex-1 flex items-center justify-center gap-1.5 rounded-lg px-4 py-2 text-sm font-medium text-white transition-colors hover:opacity-90"
                        style="background-color: #2299DC !important;"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Xác nhận
                    </button>
                    <button 
                        type="button"
                        onclick="closeScheduleModal()" 
                        class="flex-1 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800"
                    >
                        Hủy
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    (function() {
        'use strict';
        
        // Biến global
        let isSubmitting = false;
        let fullscreenTinyMCEInstance = null;

        @if($forwardEmail)
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Loading Forward Email...');
            
            const subjectField = document.getElementById('subject');
            if (subjectField) {
                const originalSubject = '{{ addslashes($forwardEmail->subject) }}';
                if (!originalSubject.match(/^Fwd:/i)) {
                    subjectField.value = 'Fwd: ' + originalSubject;
                } else {
                    subjectField.value = originalSubject;
                }
            }

            const waitForTinyMCE = setInterval(function() {
                if (window.tinymce && tinymce.get('reply')) {
                    clearInterval(waitForTinyMCE);
                    
                    const forwardContent = `
                        <br><br>
                        <div style="border-left: 3px solid #2299DC; padding-left: 15px; margin: 20px 0; color: #666;">
                            <p style="margin: 0; font-weight: bold; color: #333;">Forwarded message</p>
                            <p style="margin: 5px 0;"><strong>From:</strong> {{ is_array($forwardEmail->from) ? ($forwardEmail->from['email'] ?? $forwardEmail->from[0]['email'] ?? '') : $forwardEmail->from }}</p>
                            <p style="margin: 5px 0;"><strong>Date:</strong> {{ $forwardEmail->created_at->format('D, M d, Y \a\t h:i A') }}</p>
                            <p style="margin: 5px 0;"><strong>Subject:</strong> {{ addslashes($forwardEmail->subject) }}</p>
                            <br>
                            {!! addslashes($forwardEmail->rendered_content ?? $forwardEmail->reply) !!}
                        </div>
                    `;
                    
                    tinymce.get('reply').setContent(forwardContent);
                    console.log('Forward email content loaded');
                }
            }, 100);
            setTimeout(() => clearInterval(waitForTinyMCE), 5000);
        });
        @endif

        @if($replyEmail)
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Loading Reply Email...');
            
            const subjectField = document.getElementById('subject');
            if (subjectField) {
                const originalSubject = '{{ addslashes($replyEmail->subject) }}';
                if (!originalSubject.match(/^Re:/i)) {
                    subjectField.value = 'Re: ' + originalSubject;
                } else {
                    subjectField.value = originalSubject;
                }
            }
            
            const waitForTinyMCE = setInterval(function() {
                if (window.tinymce && tinymce.get('reply')) {
                    clearInterval(waitForTinyMCE);
                    
                    const replyContent = `
                        <br><br>
                        <div style="border-left: 3px solid #22C55E; padding-left: 15px; margin: 20px 0; color: #666;">
                            <p style="margin: 0; color: #666;">On {{ $replyEmail->created_at->format('D, M d, Y \a\t h:i A') }}, {{ is_array($replyEmail->from) ? ($replyEmail->from['email'] ?? $replyEmail->from[0]['email'] ?? '') : $replyEmail->from }} wrote:</p>
                            <br>
                            <blockquote style="margin: 0; padding-left: 10px; color: #666;">
                                {!! addslashes($replyEmail->rendered_content ?? $replyEmail->reply) !!}
                            </blockquote>
                        </div>
                    `;
                    
                    tinymce.get('reply').setContent(replyContent);
                    tinymce.get('reply').focus();
                    console.log('Reply email content loaded');
                }
            }, 100);
            
            setTimeout(() => clearInterval(waitForTinyMCE), 5000);
        });
        @endif
        
        window.submitVueForm = function(action) {
            if (isSubmitting) return;
            
            const vForm = document.getElementById('email-compose-form');
            if (!vForm) {
                alert('Lỗi: Không tìm thấy form!');
                return;
            }
            
            isSubmitting = true;
            toggleButtons(true);
            
            const formData = new FormData(vForm);
            
            const leadId = formData.get('lead_id');
            const personId = formData.get('person_id');
            
            if (!leadId || leadId === '' || leadId === 'null') {
                formData.delete('lead_id');
            }
            
            if (!personId || personId === '' || personId === 'null') {
                formData.delete('person_id');
            }
            
            formData.set('action', action);
            
            if (window.tinymce && tinymce.get('reply')) {
                const content = tinymce.get('reply').getContent();
                formData.set('reply', content);
            }
            
            const to = formData.get('to');
            const subject = formData.get('subject');
            
            if (!to || !subject) {
                alert('Vui lòng điền đầy đủ:\n• Người nhận (Đến)\n• Chủ đề');
                isSubmitting = false;
                toggleButtons(false);
                return;
            }
            
            let url = vForm.getAttribute('action') || '/admin/mail/store';
            
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            })
            .then(response => {
                return response.json().then(data => ({
                    ok: response.ok,
                    status: response.status,
                    data: data
                })).catch(() => ({
                    ok: response.ok,
                    status: response.status,
                    data: null
                }));
            })
            .then(result => {
                if (result.ok || (result.data && result.data.success)) {
                    const message = result.data?.message || 'Thành công!';
                    alert(message);
                    
                    const redirect = result.data?.redirect || '{{ route("admin.mail.index") }}';
                    window.location.href = redirect;
                } else {
                    if (result.status === 422 && result.data?.errors) {
                        const errors = Object.entries(result.data.errors)
                            .map(([k, v]) => `• ${k}: ${Array.isArray(v) ? v.join(', ') : v}`)
                            .join('\n');
                        alert('Lỗi validation:\n\n' + errors);
                    } else if (result.status === 500) {
                        const msg = result.data?.message || '';
                        if (msg.includes('foreign key constraint')) {
                            alert('Lỗi database: Không thể gửi email do ràng buộc dữ liệu.\n\nVui lòng liên hệ quản trị viên.');
                        } else {
                            alert('Lỗi server:\n\n' + msg.substring(0, 200));
                        }
                    } else {
                        alert('Lỗi: ' + (result.data?.message || 'Vui lòng thử lại'));
                    }
                }
            })
            .catch(error => {
                alert('Lỗi kết nối: ' + error.message);
            })
            .finally(() => {
                isSubmitting = false;
                toggleButtons(false);
            });
        };
        
        function toggleButtons(disabled) {
            ['btn-send', 'btn-draft', 'btn-schedule'].forEach(id => {
                const btn = document.getElementById(id);
                if (btn) {
                    btn.disabled = disabled;
                    btn.style.opacity = disabled ? '0.5' : '1';
                    btn.style.cursor = disabled ? 'not-allowed' : 'pointer';
                }
            });
        }
        
        window.toggleCcBcc = function() {
            document.getElementById('ccBccFields')?.classList.toggle('hidden');
        };

        window.loadTemplate = function(templateId) {
            const btn = event.currentTarget;
            const originalHTML = btn.innerHTML;
            
            btn.innerHTML = '<svg class="w-4 h-4 animate-spin mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            btn.style.pointerEvents = 'none';
            
            fetch(`/admin/mail/compose/from-template/${templateId}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('subject').value = data.subject || '';
                    document.getElementById('template_id').value = data.template_id || templateId;
                    
                    if (window.tinymce && tinymce.get('reply')) {
                        tinymce.get('reply').setContent(data.content || '');
                    }
                    
                    btn.classList.add('bg-green-50', 'border-green-500');
                    setTimeout(() => btn.classList.remove('bg-green-50', 'border-green-500'), 1500);
                })
                .catch(() => alert('Không thể tải mẫu email'))
                .finally(() => {
                    btn.innerHTML = originalHTML;
                    btn.style.pointerEvents = '';
                });
        };

        window.filterTemplates = function() {
            const search = (document.getElementById('template-search')?.value || '').toLowerCase();
            document.querySelectorAll('.template-item').forEach(item => {
                const name = (item.dataset.templateName || '').toLowerCase();
                item.style.display = name.includes(search) ? '' : 'none';
            });
        };

        window.openFullscreenEditor = function() {
            const modal = document.getElementById('fullscreen-editor-modal');
            
            // Lấy nội dung hiện tại từ editor chính
            let currentContent = '';
            if (window.tinymce && tinymce.get('reply')) {
                currentContent = tinymce.get('reply').getContent();
            }
            
            // Hiển thị modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Khởi tạo TinyMCE trong modal
            setTimeout(() => {
                tinymce.init({
                    selector: '#fullscreen-editor-content',
                    height: '100%',
                    menubar: true,
                    plugins: [
                        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                        'insertdatetime', 'media', 'table', 'help', 'wordcount'
                    ],
                    toolbar: 'undo redo | blocks fontsize | bold italic underline strikethrough | ' +
                            'forecolor backcolor | alignleft aligncenter alignright alignjustify | ' +
                            'bullist numlist | outdent indent | link image | removeformat code',
                    toolbar_mode: 'sliding',
                    content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; line-height: 1.6; }',
                    skin: document.documentElement.classList.contains('dark') ? 'oxide-dark' : 'oxide',
                    content_css: document.documentElement.classList.contains('dark') ? 'dark' : 'default',
                    init_instance_callback: function(editor) {
                        fullscreenTinyMCEInstance = editor;
                        editor.setContent(currentContent);
                        editor.focus();
                    }
                });
            }, 100);
        };

        window.closeFullscreenEditor = function() {
            const modal = document.getElementById('fullscreen-editor-modal');
            
            // Lấy nội dung từ editor fullscreen
            if (fullscreenTinyMCEInstance) {
                const content = fullscreenTinyMCEInstance.getContent();
                
                // Cập nhật lại vào editor chính
                if (tinymce.get('reply')) {
                    tinymce.get('reply').setContent(content);
                }
                
                // Remove editor instance
                fullscreenTinyMCEInstance.remove();
                fullscreenTinyMCEInstance = null;
            }
            
            // Ẩn modal
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        };

        window.showScheduleModal = function() {
            const vForm = document.getElementById('email-compose-form');
            const formData = new FormData(vForm);
            const to = formData.get('to');
            const subject = formData.get('subject');
            
            if (!to || !subject) {
                alert('Vui lòng điền đầy đủ:\n• Người nhận (Đến)\n• Chủ đề');
                return;
            }

            const now = new Date();
            const tomorrow = new Date(now);
            tomorrow.setDate(tomorrow.getDate() + 1);
            tomorrow.setHours(9, 0, 0, 0);
            
            const dateStr = tomorrow.toISOString().split('T')[0];
            const timeStr = '09:00';
            
            document.getElementById('schedule-date').value = dateStr;
            document.getElementById('schedule-time').value = timeStr;
            document.getElementById('schedule-date').min = now.toISOString().split('T')[0];
            
            updateSchedulePreview();

            const scheduleBtn = document.getElementById('btn-schedule');
            const btnRect = scheduleBtn.getBoundingClientRect();
            const modal = document.getElementById('schedule-modal');
            const modalInner = modal.querySelector('div');

            modal.style.visibility = 'hidden';
            modal.classList.remove('hidden');
            const modalHeight = modalInner.offsetHeight;
            modal.classList.add('hidden');
            modal.style.visibility = '';

            const spaceBelow = window.innerHeight - btnRect.bottom - 20; 
            const spaceAbove = btnRect.top - 20; 

            modal.style.position = 'fixed';
            modal.style.left = `${Math.max(20, btnRect.left)}px`; 

            if (modalHeight <= spaceBelow) {
                modal.style.top = `${btnRect.bottom + 8}px`;
                modal.style.bottom = 'auto';
            } else if (modalHeight <= spaceAbove) {
                modal.style.bottom = `${window.innerHeight - btnRect.top + 8}px`;
                modal.style.top = 'auto';
            } else {
                const centerTop = Math.max(20, (window.innerHeight - modalHeight) / 2);
                modal.style.top = `${centerTop}px`;
                modal.style.bottom = 'auto';
            }
            
            modal.classList.remove('hidden');
            
            document.getElementById('schedule-date').addEventListener('change', updateSchedulePreview);
            document.getElementById('schedule-time').addEventListener('change', updateSchedulePreview);
            
            setTimeout(() => {
                document.addEventListener('click', handleClickOutsideSchedule);
            }, 10);
        };

        function handleClickOutsideSchedule(event) {
            const modal = document.getElementById('schedule-modal');
            const scheduleBtn = document.getElementById('btn-schedule');
            
            if (!modal.contains(event.target) && !scheduleBtn.contains(event.target)) {
                closeScheduleModal();
            }
        }

        window.closeScheduleModal = function() {
            const modal = document.getElementById('schedule-modal');
            modal.classList.add('hidden');
            document.removeEventListener('click', handleClickOutsideSchedule);
        };
        
        window.setQuickSchedule = function(type, time) {
            const now = new Date();
            let targetDate = new Date(now);
            
            switch(type) {
                case 'tomorrow':
                    targetDate.setDate(targetDate.getDate() + 1);
                    break;
                case 'nextMonday':
                    const daysUntilMonday = (8 - targetDate.getDay()) % 7 || 7;
                    targetDate.setDate(targetDate.getDate() + daysUntilMonday);
                    break;
                case 'nextWeek':
                    targetDate.setDate(targetDate.getDate() + 7);
                    break;
            }
            
            const dateStr = targetDate.toISOString().split('T')[0];
            document.getElementById('schedule-date').value = dateStr;
            document.getElementById('schedule-time').value = time;
            
            updateSchedulePreview();
        };

        window.updateSchedulePreview = function() {
            const date = document.getElementById('schedule-date').value;
            const time = document.getElementById('schedule-time').value;
            
            if (!date || !time) {
                document.getElementById('schedule-preview').textContent = 'Vui lòng chọn ngày và giờ';
                return;
            }
            
            const datetime = new Date(`${date}T${time}`);
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            
            const formatted = datetime.toLocaleDateString('vi-VN', options);
            document.getElementById('schedule-preview').textContent = formatted;
        };

        window.confirmSchedule = function() {
            const date = document.getElementById('schedule-date').value;
            const time = document.getElementById('schedule-time').value;
            
            if (!date || !time) {
                alert('Vui lòng chọn ngày và giờ gửi');
                return;
            }
            
            const scheduledAt = `${date} ${time}:00`;
            
            const scheduledDate = new Date(`${date}T${time}`);
            const now = new Date();
            
            if (scheduledDate <= now) {
                alert('Thời gian gửi phải sau thời điểm hiện tại');
                return;
            }
            
            closeScheduleModal();
            
            const vForm = document.getElementById('email-compose-form');
            const formData = new FormData(vForm);
            formData.set('action', 'schedule');
            formData.set('scheduled_at', scheduledAt);
            
            if (window.tinymce && tinymce.get('reply')) {
                const content = tinymce.get('reply').getContent();
                formData.set('reply', content);
            }
            
            isSubmitting = true;
            toggleButtons(true);
            
            let url = vForm.getAttribute('action') || '/admin/mail/store';
            
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message || 'Đã lên lịch gửi email thành công!');
                    window.location.href = data.redirect || '{{ route("admin.mail.folder", "scheduled") }}';
                } else {
                    alert('Lỗi: ' + (data.message || 'Không thể lên lịch email'));
                }
            })
            .catch(error => {
                alert('Lỗi kết nối: ' + error.message);
            })
            .finally(() => {
                isSubmitting = false;
                toggleButtons(false);
            });
        };

        document.addEventListener('keydown', function(e) {
            // ESC để đóng modals
            if (e.key === 'Escape') {
                const scheduleModal = document.getElementById('schedule-modal');
                const fullscreenModal = document.getElementById('fullscreen-editor-modal');
                
                if (scheduleModal && !scheduleModal.classList.contains('hidden')) {
                    closeScheduleModal();
                } else if (fullscreenModal && !fullscreenModal.classList.contains('hidden')) {
                    closeFullscreenEditor();
                }
            }
        });
    })();
    </script>

    <style>
    #schedule-modal {
        z-index: 99999 !important;
        animation: fadeIn 0.15s ease-out;
    }

    #schedule-modal > div {
        position: relative;
        z-index: 99999 !important;
    }

    #schedule-modal.hidden {
        display: none !important;
    }

    #schedule-modal .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }

    #schedule-modal .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    #schedule-modal .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #cbd5e0;
        border-radius: 3px;
    }

    #schedule-modal .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #a0aec0;
    }

    .dark #schedule-modal .overflow-y-auto::-webkit-scrollbar-track {
        background: #374151;
    }

    .dark #schedule-modal .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #6b7280;
    }

    .dark #schedule-modal .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }

    #fullscreen-editor-modal {
        animation: fadeIn 0.2s ease-out;
        z-index: 99999 !important;
    }

    #fullscreen-editor-modal.hidden {
        display: none !important;
    }

    #fullscreen-editor-content {
        min-height: 400px;
    }

    #fullscreen-editor-modal .tox-tinymce {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        height: 100% !important;
    }

    .dark #fullscreen-editor-modal .tox-tinymce {
        border-color: #374151;
    }

    #fullscreen-editor-modal .tox-editor-container {
        height: 100% !important;
    }

    #fullscreen-editor-modal .tox-sidebar-wrap {
        height: 100% !important;
    }

    .tox-tinymce-aux {
        z-index: 999999 !important;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    input[type="date"]::-webkit-calendar-picker-indicator,
    input[type="time"]::-webkit-calendar-picker-indicator {
        cursor: pointer;
        filter: invert(0.5);
    }

    .dark input[type="date"]::-webkit-calendar-picker-indicator,
    .dark input[type="time"]::-webkit-calendar-picker-indicator {
        filter: invert(0.8);
    }

    button:hover {
        transition: all 0.2s ease;
    }

    .cursor-pointer:hover {
        opacity: 0.95;
    }
    </style>
@endpush
</x-admin::layouts>