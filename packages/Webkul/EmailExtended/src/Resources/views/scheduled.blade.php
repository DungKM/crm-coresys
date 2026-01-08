<x-admin::layouts>
    <x-slot:title>
        Email đã lên lịch
    </x-slot>

    <div class="flex gap-4">
        <!-- Sidebar -->
        <div class="w-64 flex-shrink-0">
            <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                @if (bouncer()->hasPermission('mail.compose'))
                    <a href="{{ route('admin.mail.compose') }}" class="primary-button mb-4 flex w-full items-center justify-center gap-2">
                        <i class="icon-add text-lg"></i>
                        <span>Soạn thư mới</span>
                    </a>
                @endif

                <div class="flex flex-col gap-1">
                    @foreach(['inbox', 'sent', 'draft', 'scheduled', 'archive', 'trash'] as $f)
                        <a href="{{ route('admin.mail.folder', $f) }}"
                           class="flex items-center justify-between rounded px-3 py-2.5 transition-colors hover:bg-gray-100 dark:hover:bg-gray-800 {{ $f === 'scheduled' ? 'bg-blue-50 font-semibold text-blue-600 dark:bg-gray-800' : 'text-gray-700 dark:text-gray-300' }}">
                            <div class="flex items-center gap-2.5">
                                <i class="{{ get_folder_icon($f) }} text-lg"></i>
                                <span>{{ get_folder_label($f) }}</span>
                            </div>
                            @if(isset($stats[$f]) && $stats[$f] > 0)
                                <span class="rounded-full bg-gray-200 px-2 py-0.5 text-xs font-medium dark:bg-gray-700">
                                    {{ $stats[$f] }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>

                <div class="mt-4 border-t pt-4 dark:border-gray-700">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between py-1.5 text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Tổng số email:</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $stats['total'] ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Chưa đọc:</span>
                            <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $stats['unread'] ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Đã đánh dấu:</span>
                            <span class="font-semibold text-amber-600 dark:text-amber-400">{{ $stats['starred'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Header -->
            <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900 mb-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="icon-clock text-2xl text-gray-700 dark:text-gray-300"></i>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Email đã lên lịch</h1>
                            @if(isset($scheduledEmails) && $scheduledEmails->total() > 0)
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $scheduledEmails->total() }} email chờ gửi</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        {{-- BULK ACTIONS --}}
                        <div class="hidden items-center gap-2" id="bulk-actions">
                            <span class="text-sm text-gray-600 dark:text-gray-400" id="selected-count">0 đã chọn</span>
                            
                            <button onclick="bulkReschedule()" class="secondary-button flex items-center gap-2">
                                <i class="icon-clock"></i>
                                Đổi lịch
                            </button>
                            
                            <button onclick="bulkCancel()" class="danger-button flex items-center gap-2">
                                <i class="icon-cancel"></i>
                                Hủy bỏ
                            </button>
                        </div>

                        {{-- FILTER & BACK --}}
                        <button onclick="toggleFilters()" class="secondary-button flex items-center gap-2">
                            <i class="icon-filter"></i>
                            Bộ lọc
                        </button>

                        <a href="{{ route('admin.mail.index') }}" class="secondary-button flex items-center gap-2">
                            <i class="icon-arrow-left"></i>
                            Quay lại
                        </a>
                    </div>
                </div>
            </div>

            {{-- STATS CARDS --}}
            <div class="box-shadow rounded bg-white dark:bg-gray-900 mb-4">
                @php
                    $totalCount = isset($scheduledEmails) ? $scheduledEmails->total() : 0;
                    $pendingCount = 0;
                    $processingCount = 0;
                    $todayCount = 0;
                    $thisWeekCount = 0;
                    
                    if(isset($scheduledEmails)) {
                        foreach($scheduledEmails as $email) {
                            $scheduledTime = \Carbon\Carbon::parse($email->scheduled_at);
                            if($scheduledTime->isPast()) {
                                $processingCount++;
                            } else {
                                $pendingCount++;
                            }
                            if($scheduledTime->isToday()) {
                                $todayCount++;
                            }
                            if($scheduledTime->isCurrentWeek()) {
                                $thisWeekCount++;
                            }
                        }
                    }
                @endphp

                <div class="grid grid-cols-2 gap-4 p-4">
                    <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalCount }}</p>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Tổng số lên lịch</p>
                    </div>
                    
                    <div class="rounded-lg bg-blue-50 p-4 dark:bg-blue-900/30">
                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $pendingCount }}</p>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Chờ gửi</p>
                    </div>
                    
                    <div class="rounded-lg bg-orange-50 p-4 dark:bg-orange-900/30">
                        <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $processingCount }}</p>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Đang xử lý</p>
                    </div>
                    
                    <div class="rounded-lg bg-green-50 p-4 dark:bg-green-900/30">
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $todayCount }}</p>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Hôm nay</p>
                    </div>
                </div>
            </div>

            {{-- FILTERS PANEL --}}
            <div id="filters-panel" class="box-shadow hidden rounded bg-white p-4 dark:bg-gray-900 mb-4">
                <form id="filter-form" method="GET" action="{{ route('admin.mail.folder', 'scheduled') }}">
                    <div class="grid grid-cols-4 gap-4">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Khoảng thời gian</label>
                            <select name="time_range" id="time-range-filter" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800">
                                <option value="all">Tất cả</option>
                                <option value="today">Hôm nay</option>
                                <option value="tomorrow">Ngày mai</option>
                                <option value="this-week">Tuần này</option>
                                <option value="next-week">Tuần sau</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Trạng thái</label>
                            <select name="status" id="status-filter" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800">
                                <option value="all">Tất cả</option>
                                <option value="pending">Chờ gửi</option>
                                <option value="processing">Đang xử lý</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Loại email</label>
                            <select name="type" id="type-filter" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800">
                                <option value="all">Tất cả</option>
                                <option value="new">Email mới</option>
                                <option value="reply">Trả lời</option>
                            </select>
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="button" onclick="applyFilters()" class="primary-button flex-1">
                                Áp dụng
                            </button>
                            <button type="button" onclick="resetFiltersWithSpin()" class="secondary-button !px-3 group" title="Reset bộ lọc">
                                <svg id="reset-icon" class="h-4 w-4 text-blue-600 dark:text-blue-400 transition-transform group-hover:rotate-180 duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- SCHEDULED EMAILS TABLE --}}
            <div class="box-shadow rounded bg-white dark:bg-gray-900">
                @if(isset($scheduledEmails) && $scheduledEmails->isEmpty())
                    <div class="flex flex-col items-center justify-center py-20">
                        <div class="rounded-full bg-blue-50 p-6 dark:bg-blue-900">
                            <i class="icon-clock text-6xl text-blue-600"></i>
                        </div>
                        <p class="mt-6 text-xl font-semibold text-gray-800 dark:text-white">Không có email lên lịch</p>
                        <p class="mt-2 text-sm text-gray-500">Lên lịch email để gửi vào thời điểm hoàn hảo</p>
                        <a href="{{ route('admin.mail.compose') }}" class="primary-button mt-6 inline-flex items-center gap-2">
                            <i class="icon-plus"></i>
                            Soạn email mới
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="border-b-2 border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-800">
                                <tr>
                                    <th class="w-12 px-4 py-4 text-left">
                                        <input type="checkbox" id="select-all" class="h-4 w-4 rounded border-gray-300" onchange="toggleSelectAll(this)">
                                    </th>
                                    <th class="px-4 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-400">
                                        Chủ đề & người nhận
                                    </th>
                                    <th class="w-48 px-4 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-400">
                                        Thời gian gửi
                                    </th>
                                    <th class="w-36 px-4 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-400">
                                        Trạng thái
                                    </th>
                                    <th class="w-44 px-4 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-400">
                                        Loại
                                    </th>
                                    <th class="w-56 px-4 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-400">
                                        Hành động
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-800" id="emails-tbody">
                                @foreach($scheduledEmails as $email)
                                    @php
                                        $scheduledTime = \Carbon\Carbon::parse($email->scheduled_at);
                                        $isPast = $scheduledTime->isPast();
                                        $toData = is_string($email->to) ? json_decode($email->to, true) : $email->to;
                                        if (is_array($toData)) {
                                            $toEmail = isset($toData[0]['email']) ? $toData[0]['email'] : (isset($toData[0]) ? $toData[0] : 'N/A');
                                        } else {
                                            $toEmail = $email->to;
                                        }
                                    @endphp

                                    <tr class="email-row group transition-colors hover:bg-blue-50 dark:hover:bg-gray-800" 
                                        data-email-id="{{ $email->email_id }}"
                                        data-scheduled="{{ $email->scheduled_at }}"
                                        data-status="{{ $isPast ? 'processing' : 'pending' }}"
                                        data-type="{{ $email->thread_id ? 'reply' : 'new' }}">
                                        <td class="px-4 py-4 align-top">
                                            <input type="checkbox" class="email-checkbox h-4 w-4 rounded border-gray-300" value="{{ $email->email_id }}" onchange="updateBulkActions()">
                                        </td>
                                        
                                        <td class="px-4 py-4 align-top">
                                            <div class="flex items-start gap-3">
                                                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300">
                                                    <i class="icon-mail text-lg"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2 flex-wrap">
                                                        @if($email->thread_id)
                                                            <a href="{{ route('admin.mail.show', $email->thread_id) }}" class="text-sm font-semibold text-gray-900 hover:text-blue-600 dark:text-white dark:hover:text-blue-400">
                                                                {{ Str::limit($email->subject, 50) }}
                                                            </a>
                                                            <span class="rounded-full bg-purple-100 px-2 py-0.5 text-xs font-medium text-purple-700 dark:bg-purple-900 dark:text-purple-300">
                                                                Trả lời
                                                            </span>
                                                        @else
                                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                                                {{ Str::limit($email->subject, 50) }}
                                                            </span>
                                                            <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700 dark:bg-green-900 dark:text-green-300">
                                                                Mới
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="mt-1 flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                                        <i class="icon-user"></i>
                                                        <span>{{ Str::limit($toEmail, 40) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="px-4 py-4 align-top">
                                            <div class="flex flex-col gap-1">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $scheduledTime->format('d/m/Y') }}
                                                    </span>
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $scheduledTime->format('H:i') }}
                                                    </span>
                                                </div>
                                                <div class="text-xs {{ $isPast ? 'text-orange-600 font-medium' : 'text-gray-400' }}">
                                                    {{ $scheduledTime->diffForHumans() }}
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="px-4 py-4 align-top status-cell">
                                            @if($isPast)
                                                <span class="inline-flex items-center gap-1.5 rounded-full bg-orange-100 px-3 py-1.5 text-xs font-semibold text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                                                    <i class="icon-sync animate-spin"></i>
                                                    Đang xử lý
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    <i class="icon-clock"></i>
                                                    Chờ gửi
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-4 align-top type-cell">
                                            @if($email->thread_id)
                                                <div class="flex items-center gap-2">
                                                    <i class="icon-reply text-purple-600 flex-shrink-0"></i>
                                                    <span class="text-sm text-gray-700 dark:text-gray-300">Trả lời</span>
                                                </div>
                                            @else
                                                <div class="flex items-center gap-2">
                                                    <i class="icon-mail text-green-600 flex-shrink-0"></i>
                                                    <span class="text-sm text-gray-700 dark:text-gray-300">Email mới</span>
                                                </div>
                                            @endif
                                        </td>
                                        
                                        <td class="px-4 py-4 align-top">
                                            <div class="flex items-start justify-start gap-2">
                                                @if($email->thread_id)
                                                    <a 
                                                        href="{{ route('admin.mail.show', $email->thread_id) }}"
                                                        class="inline-flex items-center gap-1.5 rounded-lg bg-gray-100 px-3 py-1.5 text-xs font-medium text-gray-700 transition-colors hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                                                        title="Xem thread"
                                                    >
                                                        <i class="icon-eye"></i>
                                                        Xem
                                                    </a>
                                                @endif
                                                
                                                <button
                                                    onclick="openRescheduleModal(event, {{ $email->email_id }}, '{{ $email->scheduled_at }}')"
                                                    class="inline-flex items-center gap-1.5 rounded-lg bg-gray-100 px-3 py-1.5 text-xs font-medium text-gray-700 transition-colors hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                                                    title="Đổi lịch"
                                                    id="reschedule-btn-{{ $email->email_id }}"
                                                >
                                                    <i class="icon-clock"></i>
                                                    Đổi lịch
                                                </button>
                                                
                                                <button
                                                    onclick="cancelScheduled({{ $email->email_id }})"
                                                    class="inline-flex items-center gap-1.5 rounded-lg bg-red-100 px-3 py-1.5 text-xs font-medium text-red-700 transition-colors hover:bg-red-200 dark:bg-red-900 dark:text-red-300 dark:hover:bg-red-800"
                                                    title="Hủy lịch"
                                                >
                                                    <i class="icon-cancel"></i>
                                                    Hủy
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINATION --}}
                    @if($scheduledEmails->hasPages())
                        <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-800">
                            {{ $scheduledEmails->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    {{-- RESCHEDULE DROPDOWN - POSITIONED BELOW BUTTON --}}
    <div id="reschedule-modal" class="fixed z-[9999] hidden" onclick="event.stopPropagation()">
        <div class="w-[320px] rounded-lg bg-white shadow-2xl border border-gray-200 dark:bg-gray-900 dark:border-gray-700">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-gray-200 px-3 py-2 dark:border-gray-700">
                <div class="flex items-center gap-2">
                    <i class="icon-clock text-sm text-blue-600 dark:text-blue-400"></i>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Đổi lịch gửi</h3>
                </div>
                <button onclick="closeRescheduleModal()" class="rounded p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800">
                    <i class="icon-cancel text-sm"></i>
                </button>
            </div>

            <!-- Body -->
            <div class="p-3">
                <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">Thời gian gửi mới</label>
                <input 
                    type="datetime-local" 
                    id="new-scheduled-time" 
                    class="w-full rounded-lg border border-gray-300 px-2 py-1.5 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                >
                <div class="mt-2 flex items-start gap-1.5 rounded bg-blue-50 p-2 dark:bg-blue-900/30">
                    <i class="icon-info-circle mt-0.5 text-xs text-blue-600 dark:text-blue-400"></i>
                    <p class="text-xs leading-relaxed text-blue-800 dark:text-blue-200">Email sẽ được gửi tự động vào thời gian đã lên lịch.</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex gap-2 border-t border-gray-200 px-3 py-2 dark:border-gray-700">
                <button 
                    onclick="confirmReschedule()" 
                    class="primary-button flex-1 !py-1.5 !text-xs"
                >
                    Xác nhận
                </button>
                <button 
                    onclick="closeRescheduleModal()" 
                    class="secondary-button flex-1 !py-1.5 !text-xs"
                >
                    Hủy
                </button>
            </div>
        </div>
    </div>

    @pushOnce('scripts')
        <script>
            let currentEmailId = null;

            /**
             * Toggle hiển thị/ẩn panel filter
             */
            function toggleFilters() {
                const panel = document.getElementById('filters-panel');
                panel.classList.toggle('hidden');
            }

            /**
             * Áp dụng bộ lọc - Filter emails theo time_range, status, type
             */
            function applyFilters() {
                const timeRange = document.getElementById('time-range-filter').value;
                const status = document.getElementById('status-filter').value;
                const type = document.getElementById('type-filter').value;
                
                const rows = document.querySelectorAll('.email-row');
                let visibleCount = 0;
                
                rows.forEach(row => {
                    let show = true;
                    
                    // Filter by time range
                    if (timeRange !== 'all') {
                        const scheduledAt = new Date(row.dataset.scheduled);
                        const now = new Date();
                        const tomorrow = new Date(now);
                        tomorrow.setDate(tomorrow.getDate() + 1);
                        
                        switch(timeRange) {
                            case 'today':
                                show = scheduledAt.toDateString() === now.toDateString();
                                break;
                            case 'tomorrow':
                                show = scheduledAt.toDateString() === tomorrow.toDateString();
                                break;
                            case 'this-week':
                                const weekStart = new Date(now);
                                weekStart.setDate(now.getDate() - now.getDay());
                                const weekEnd = new Date(weekStart);
                                weekEnd.setDate(weekStart.getDate() + 6);
                                show = scheduledAt >= weekStart && scheduledAt <= weekEnd;
                                break;
                            case 'next-week':
                                const nextWeekStart = new Date(now);
                                nextWeekStart.setDate(now.getDate() - now.getDay() + 7);
                                const nextWeekEnd = new Date(nextWeekStart);
                                nextWeekEnd.setDate(nextWeekStart.getDate() + 6);
                                show = scheduledAt >= nextWeekStart && scheduledAt <= nextWeekEnd;
                                break;
                        }
                    }
                    
                    // Filter by status
                    if (show && status !== 'all') {
                        show = row.dataset.status === status;
                    }
                    
                    // Filter by type
                    if (show && type !== 'all') {
                        show = row.dataset.type === type;
                    }
                    
                    // Show/hide row
                    if (show) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                // Show notification
                showNotification('success', `Hiển thị ${visibleCount} email phù hợp`);
            }

            /**
             * Reset tất cả bộ lọc về mặc định
             */
            function resetFilters() {
                document.getElementById('time-range-filter').value = 'all';
                document.getElementById('status-filter').value = 'all';
                document.getElementById('type-filter').value = 'all';
                
                // Show all rows
                document.querySelectorAll('.email-row').forEach(row => {
                    row.style.display = '';
                });
                
                showNotification('success', 'Đã reset bộ lọc');
            }

            /**
             * Toggle select all checkboxes
             */
            function toggleSelectAll(checkbox) {
                // Only select visible rows
                document.querySelectorAll('.email-row:not([style*="display: none"]) .email-checkbox').forEach(cb => {
                    cb.checked = checkbox.checked;
                });
                updateBulkActions();
            }

            /**
             * Update bulk actions visibility
             * Hiển thị/ẩn thanh bulk actions dựa trên số checkbox được chọn
             */
            function updateBulkActions() {
                const checkedBoxes = document.querySelectorAll('.email-checkbox:checked');
                const bulkActions = document.getElementById('bulk-actions');
                const selectedCount = document.getElementById('selected-count');
                
                if (checkedBoxes.length > 0) {
                    bulkActions.classList.remove('hidden');
                    bulkActions.classList.add('flex');
                    selectedCount.textContent = `${checkedBoxes.length} đã chọn`;
                } else {
                    bulkActions.classList.add('hidden');
                    bulkActions.classList.remove('flex');
                }
            }

            /**
             * Open reschedule modal positioned below button
             */
            function openRescheduleModal(event, emailId, currentTime) {
                event.stopPropagation();
                currentEmailId = emailId;
                
                // Convert to datetime-local format
                const date = new Date(currentTime);
                const formatted = date.toISOString().slice(0, 16);
                document.getElementById('new-scheduled-time').value = formatted;
                
                // Set min datetime to now
                const now = new Date();
                const minDateTime = now.toISOString().slice(0, 16);
                document.getElementById('new-scheduled-time').min = minDateTime;
                
                // Get button position
                const button = document.getElementById(`reschedule-btn-${emailId}`);
                const buttonRect = button.getBoundingClientRect();
                const modal = document.getElementById('reschedule-modal');
                
                // Position modal below button
                modal.style.top = `${buttonRect.bottom + window.scrollY + 8}px`;
                modal.style.left = `${buttonRect.left + window.scrollX}px`;
                
                // Show modal
                modal.classList.remove('hidden');
                
                // Close on click outside
                setTimeout(() => {
                    document.addEventListener('click', handleClickOutside);
                }, 10);
            }
            
            /**
             * Handle click outside modal to close
             */
            function handleClickOutside(event) {
                const modal = document.getElementById('reschedule-modal');
                if (!modal.contains(event.target)) {
                    closeRescheduleModal();
                }
            }

            /**
             * Close reschedule modal
             */
            function closeRescheduleModal() {
                const modal = document.getElementById('reschedule-modal');
                modal.classList.add('hidden');
                currentEmailId = null;
                document.removeEventListener('click', handleClickOutside);
            }

            /**
             * Confirm and submit reschedule
             */
            function confirmReschedule() {
                const newTime = document.getElementById('new-scheduled-time').value;
                if (!newTime) {
                    alert('Vui lòng chọn thời gian');
                    return;
                }
                
                const date = new Date(newTime);
                const formatted = date.toISOString().slice(0, 16).replace('T', ' ');
                
                fetch(`/admin/mail/email/${currentEmailId}/reschedule`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ scheduled_at: formatted })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        closeRescheduleModal();
                        showNotification('success', data.message);
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showNotification('error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('error', 'Có lỗi xảy ra');
                });
            }

            /**
             * Cancel a scheduled email
             */
            function cancelScheduled(emailId) {
                if (!confirm('Bạn có chắc chắn muốn hủy email đã lên lịch này? Email sẽ được chuyển về nháp.')) {
                    return;
                }
                
                fetch(`/admin/mail/email/${emailId}/cancel-schedule`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showNotification('success', data.message);
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showNotification('error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('error', 'Có lỗi xảy ra');
                });
            }

            /**
             * Bulk reschedule selected emails
             */
            function bulkReschedule() {
                const checkedBoxes = Array.from(document.querySelectorAll('.email-checkbox:checked'));
                if (checkedBoxes.length === 0) return;
                
                const newTime = prompt('Nhập thời gian gửi mới (YYYY-MM-DD HH:MM):');
                if (!newTime) return;
                
                let completed = 0;
                checkedBoxes.forEach(checkbox => {
                    const emailId = checkbox.value;
                    fetch(`/admin/mail/email/${emailId}/reschedule`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ scheduled_at: newTime })
                    })
                    .then(() => {
                        completed++;
                        if (completed === checkedBoxes.length) {
                            showNotification('success', `Đã đổi lịch ${completed} email`);
                            setTimeout(() => location.reload(), 1500);
                        }
                    });
                });
            }

            /**
             * Bulk cancel selected emails
             */
            function bulkCancel() {
                const checkedBoxes = Array.from(document.querySelectorAll('.email-checkbox:checked'));
                if (checkedBoxes.length === 0) return;
                
                if (!confirm(`Hủy ${checkedBoxes.length} email đã lên lịch? Chúng sẽ được chuyển về nháp.`)) return;
                
                let completed = 0;
                checkedBoxes.forEach(checkbox => {
                    const emailId = checkbox.value;
                    fetch(`/admin/mail/email/${emailId}/cancel-schedule`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(() => {
                        completed++;
                        if (completed === checkedBoxes.length) {
                            showNotification('success', `Đã hủy ${completed} email và chuyển về nháp`);
                            setTimeout(() => location.reload(), 1500);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        completed++;
                    });
                });
            }

            /**
             * Show notification toast - Fixed position
             */
            function showNotification(type, message) {
                // Remove existing notifications first
                document.querySelectorAll('.toast-notification').forEach(n => n.remove());
                
                const notification = document.createElement('div');
                notification.className = `toast-notification fixed top-20 right-4 z-[9999] rounded-lg px-6 py-4 shadow-2xl border transition-all duration-300 ${
                    type === 'success' 
                        ? 'bg-green-50 text-green-800 border-green-200 dark:bg-green-900 dark:text-green-200 dark:border-green-700' 
                        : 'bg-red-50 text-red-800 border-red-200 dark:bg-red-900 dark:text-red-200 dark:border-red-700'
                }`;
                notification.style.transform = 'translateX(400px)';
                notification.innerHTML = `
                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            ${type === 'success' 
                                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                            }
                        </svg>
                        <span class="font-medium">${message}</span>
                    </div>
                `;
                
                document.body.appendChild(notification);
                
                // Slide in
                setTimeout(() => {
                    notification.style.transform = 'translateX(0)';
                }, 10);
                
                // Auto dismiss after 3 seconds
                setTimeout(() => {
                    notification.style.transform = 'translateX(400px)';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }

            /**
             * Reset filters with spin animation
             */
            function resetFiltersWithSpin() {
                const icon = document.getElementById('reset-icon');
                icon.classList.add('animate-spin');
                
                resetFilters();
                
                setTimeout(() => {
                    icon.classList.remove('animate-spin');
                }, 500);
            }

            /**
             * Close modal on ESC key
             */
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const modal = document.getElementById('reschedule-modal');
                    if (modal && !modal.classList.contains('hidden')) {
                        closeRescheduleModal();
                    }
                }
            });
        </script>

        <style>
            .overflow-x-auto::-webkit-scrollbar {
                height: 8px;
            }
            .overflow-x-auto::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 4px;
            }
            .overflow-x-auto::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 4px;
            }
            .overflow-x-auto::-webkit-scrollbar-thumb:hover {
                background: #555;
            }
            .dark .overflow-x-auto::-webkit-scrollbar-track {
                background: #374151;
            }
            .dark .overflow-x-auto::-webkit-scrollbar-thumb {
                background: #6b7280;
            }
            .dark .overflow-x-auto::-webkit-scrollbar-thumb:hover {
                background: #9ca3af;
            }
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            .animate-spin {
                animation: spin 1s linear infinite;
            }
            #reschedule-modal {
                animation: fadeIn 0.2s ease;
            }
            #reschedule-modal.hidden {
                display: none !important;
            }
            #reschedule-modal > div {
                animation: slideUp 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            }

            .toast-notification {
                min-width: 300px;
                max-width: 500px;
                pointer-events: auto;
            }
            
            /* Ensure notification stays on top */
            .toast-notification {
                position: fixed !important;
                z-index: 9999 !important;
            }
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: scale(0.9) translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: scale(1) translateY(0);
                }
            }
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            .animate-spin {
                animation: spin 0.5s linear;
            }
        </style>
    @endPushOnce
</x-admin::layouts>