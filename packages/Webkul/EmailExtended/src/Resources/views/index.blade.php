<x-admin::layouts>
    <x-slot:title>
        {{ get_folder_label($folder) }}
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
                    {{-- Dashboard Tab --}}
                    <a href="{{ route('admin.mail.tracking.dashboard') }}"
                    class="flex items-center justify-between rounded px-3 py-2.5 transition-colors hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('admin.mail.tracking.dashboard') ? 'bg-blue-50 font-semibold text-blue-600 dark:bg-gray-800' : 'text-gray-700 dark:text-gray-300' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <span>Dashboard</span>
                        </div>
                    </a>

                    {{-- Settings Tab - MỚI THÊM --}}
                    <a href="{{ route('admin.mail.settings.index') }}"
                    class="flex items-center justify-between rounded px-3 py-2.5 transition-colors hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('admin.mail.settings.*') ? 'bg-blue-50 font-semibold text-blue-600 dark:bg-gray-800' : 'text-gray-700 dark:text-gray-300' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Settings</span>
                        </div>
                    </a>

                    {{-- Divider --}}
                    <div class="my-2 border-t dark:border-gray-700"></div>

                    {{-- Các folder email --}}
                    @foreach(['inbox', 'sent', 'draft', 'scheduled', 'archive', 'trash'] as $f)
                        <a href="{{ route('admin.mail.folder', $f) }}"
                        class="flex items-center justify-between rounded px-3 py-2.5 transition-colors hover:bg-gray-100 dark:hover:bg-gray-800 {{ isset($folder) && $folder === $f ? 'bg-blue-50 font-semibold text-blue-600 dark:bg-gray-800' : 'text-gray-700 dark:text-gray-300' }}">
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
                    <div class="flex justify-between py-1.5 text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Tổng số email</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $stats['total'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between py-1.5 text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Chưa đọc</span>
                        <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $stats['unread'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between py-1.5 text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Đã đánh dấu</span>
                        <span class="font-semibold text-amber-600 dark:text-amber-400">{{ $stats['starred'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <div class="box-shadow rounded bg-white dark:bg-gray-900">
                <!-- Header -->
                <div class="flex items-center justify-between border-b p-4 dark:border-gray-800">
                    <div class="flex items-center gap-3">
                        <i class="{{ get_folder_icon($folder) }} text-2xl text-gray-700 dark:text-gray-300"></i>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ get_folder_label($folder) }}</h1>
                            @if(isset($stats['unread']) && $stats['unread'] > 0 && $folder === 'inbox')
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $stats['unread'] }} tin nhắn chưa đọc</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="window.location.reload()" class="secondary-button flex items-center gap-2" title="Làm mới">
                            <i class="icon-refresh"></i>
                            <span class="hidden md:inline">Làm mới</span>
                        </button>
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="border-b p-4 dark:border-gray-800">
                    <form action="{{ route('admin.mail.search') }}" method="GET" class="flex items-center gap-3">
                        <input type="hidden" name="folder" value="{{ $folder }}">
                        
                        <div class="relative flex-1">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                name="query" 
                                value="{{ request('query') }}"
                                placeholder="Tìm kiếm theo tiêu đề, người gửi, người nhận..." 
                                class="block w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-10 pr-4 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500">
                        </div>
                        
                        <button type="submit" class="primary-button flex items-center gap-2 whitespace-nowrap">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span>Tìm kiếm</span>
                        </button>
                        
                        @if(request('query'))
                            <a href="{{ route('admin.mail.folder', $folder) }}" class="secondary-button flex items-center gap-2 whitespace-nowrap" title="Xóa bộ lọc">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span>Xóa</span>
                            </a>
                        @endif
                    </form>
                    
                    @if(request('query'))
                        <div class="mt-3 flex items-center gap-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                Kết quả tìm kiếm cho: 
                                <span class="font-semibold text-gray-900 dark:text-white">"{{ request('query') }}"</span>
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Bulk Actions Bar - Thay đổi theo từng folder -->
                <div id="bulkBar" class="hidden border-b bg-blue-50 p-3 dark:border-gray-800 dark:bg-gray-800">
                    <div class="flex items-center gap-3">
                        <span id="selectedCount" class="font-semibold text-gray-700 dark:text-gray-300">0 đã chọn</span>
                        
                        @if($folder === 'trash')
                            {{-- Tab THÙNG RÁC: Chỉ có Khôi phục và Xóa vĩnh viễn --}}
                            <button onclick="bulkRestore()" class="secondary-button text-sm">
                                <i class="icon-refresh"></i> Khôi phục
                            </button>
                            <button onclick="bulkDeletePermanent()" class="danger-button text-sm">
                                <i class="icon-trash"></i> Xóa vĩnh viễn
                            </button>
                            
                        @elseif($folder === 'archive')
                            {{-- Tab LƯU TRỮ: Chỉ có Khôi phục và Xóa --}}
                            <button onclick="bulkRestore()" class="secondary-button text-sm">
                                <i class="icon-refresh"></i> Khôi phục
                            </button>
                            <button onclick="bulkDelete()" class="danger-button text-sm">
                                <i class="icon-trash"></i> Xóa
                            </button>
                            
                        @else
                            {{-- Tab KHÁC: Hiện đầy đủ chức năng --}}
                            @if(!in_array($folder, ['sent', 'draft', 'scheduled']))
                                <button onclick="bulkMarkRead()" class="secondary-button text-sm">
                                    <i class="icon-mail-open"></i> Đánh dấu đã đọc
                                </button>
                            @endif
                            
                            <button onclick="bulkArchive()" class="secondary-button text-sm">
                                <i class="icon-archive"></i> Lưu trữ
                            </button>
                            <button onclick="bulkDelete()" class="danger-button text-sm">
                                <i class="icon-trash"></i> Xóa
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)">
                                </th>
                                <th>⭐</th>
                                <th>ID</th>
                                <th>Người gửi</th>
                                <th>Người nhận</th>
                                <th>Chủ đề</th>
                                <th>Trạng thái</th>
                                <th>Thời gian</th>
                                <th>Số email</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($threads as $thread)
                                <tr class="email-row {{ !$thread->is_read ? 'unread' : '' }} {{ $thread->is_starred ? 'starred' : '' }}"
                                    data-id="{{ $thread->id }}"
                                    onclick="window.location='{{ route('admin.mail.show', $thread->id) }}'">
                                    
                                    <!-- Checkbox -->
                                    <td onclick="event.stopPropagation();">
                                        <input type="checkbox" class="row-checkbox" value="{{ $thread->id }}" onchange="updateBulkBar()">
                                    </td>
                                    
                                    <!-- Star -->
                                    <td onclick="event.stopPropagation();">
                                        <button onclick="toggleStar({{ $thread->id }}, event)" class="star-btn" title="{{ $thread->is_starred ? 'Bỏ đánh dấu' : 'Đánh dấu' }}">
                                            <svg class="{{ $thread->is_starred ? 'star-filled' : 'star-empty' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                            </svg>
                                        </button>
                                    </td>
                                    
                                    <!-- ID -->
                                    <td>
                                        <span class="text-id">#{{ $thread->id }}</span>
                                    </td>
                                    
                                    <!-- Người gửi -->
                                    <td>
                                        @php
                                            $emailFrom = str_replace('"', '', $thread->email_from ?? '');
                                            $emailFrom = $emailFrom ?: '-';
                                        @endphp
                                        <span class="email-text {{ !$thread->is_read ? 'font-semibold' : '' }}">{{ $emailFrom }}</span>
                                    </td>
                                    
                                    <!-- Người nhận -->
                                    <td>
                                        @php
                                            $emailTo = '-';
                                            if (!empty($thread->email_to)) {
                                                $to = json_decode($thread->email_to, true);
                                                if (is_array($to) && !empty($to)) {
                                                    $first = $to[0];
                                                    $emailTo = is_array($first) ? ($first['email'] ?? '-') : $first;
                                                    
                                                    // Nếu có nhiều người nhận, hiển thị số lượng
                                                    if (count($to) > 1) {
                                                        $emailTo .= ' (+' . (count($to) - 1) . ')';
                                                    }
                                                }
                                            }
                                        @endphp
                                        <span class="email-text {{ !$thread->is_read ? 'font-semibold' : '' }}" title="{{ $emailTo }}">{{ $emailTo }}</span>
                                    </td>
                                    
                                    <!-- Subject -->
                                    <td onclick="event.stopPropagation();">
                                        <a href="{{ route('admin.mail.show', $thread->id) }}" 
                                           class="subject-link {{ !$thread->is_read ? 'font-semibold' : '' }}">
                                            {{ $thread->subject ?: '(Không có tiêu đề)' }}
                                        </a>
                                    </td>
                                    
                                    <!-- Status -->
                                    <td>
                                        @php
                                            $badges = [
                                                'inbox' => 'bg-purple-100 text-purple-800',
                                                'sent' => 'bg-green-100 text-green-800',
                                                'draft' => 'bg-yellow-100 text-yellow-800',
                                                'scheduled' => 'bg-blue-100 text-blue-800',
                                                'archive' => 'bg-gray-100 text-gray-800',
                                                'trash' => 'bg-red-100 text-red-800',
                                            ];
                                            $labels = [
                                                'inbox' => 'Hộp thư',
                                                'sent' => 'Đã gửi',
                                                'draft' => 'Nháp',
                                                'scheduled' => 'Lên lịch',
                                                'archive' => 'Lưu trữ',
                                                'trash' => 'Thùng rác',
                                            ];
                                        @endphp
                                        <span class="status-badge {{ $badges[$thread->folder] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $labels[$thread->folder] ?? $thread->folder }}
                                        </span>
                                    </td>
                                    
                                    <!-- Time -->
                                    <td>
                                        @php
                                            $date = $thread->last_email_at ?? $thread->updated_at ?? $thread->created_at;
                                            $timestamp = strtotime($date);
                                            $diff = time() - $timestamp;
                                            
                                            if ($diff < 60) {
                                                $timeText = 'Vừa xong';
                                            } elseif ($diff < 3600) {
                                                $timeText = floor($diff / 60) . ' phút';
                                            } elseif ($diff < 86400) {
                                                $timeText = floor($diff / 3600) . ' giờ';
                                            } elseif ($diff < 604800) {
                                                $timeText = floor($diff / 86400) . ' ngày';
                                            } else {
                                                $timeText = date('d/m/Y', $timestamp);
                                            }
                                        @endphp
                                        <span class="time-text">{{ $timeText }}</span>
                                    </td>
                                    
                                    <!-- Count -->
                                    <td class="text-center">
                                        <span class="count-text">{{ $thread->email_count ?? 1 }}</span>
                                    </td>
                                    
                                    <!-- Actions -->
                                    <td onclick="event.stopPropagation();">
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.mail.show', $thread->id) }}" title="Xem chi tiết">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            
                                            @if(in_array($folder, ['archive', 'trash']))
                                                {{-- Tab Archive/Trash: Chỉ có Khôi phục và Xóa --}}
                                                <button onclick="restoreThread({{ $thread->id }}, event)" title="Khôi phục">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                    </svg>
                                                </button>
                                                
                                                @if($folder === 'trash')
                                                    {{-- Tab Trash: Xóa vĩnh viễn --}}
                                                    <button onclick="deletePermanent({{ $thread->id }}, event)" title="Xóa vĩnh viễn" class="delete-btn">
                                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                @else
                                                    {{-- Tab Archive: Chuyển vào trash --}}
                                                    <button onclick="deleteThread({{ $thread->id }}, event)" title="Xóa" class="delete-btn">
                                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                @endif
                                            @else
                                                {{-- Tab khác: Hiển thị Lưu trữ và Xóa --}}
                                                <button onclick="archiveThread({{ $thread->id }}, event)" title="Lưu trữ">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                                    </svg>
                                                </button>
                                                
                                                <button onclick="deleteThread({{ $thread->id }}, event)" title="Xóa" class="delete-btn">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-8 text-gray-500">
                                        Không có email nào trong {{ get_folder_label($folder) }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($threads->hasPages())
                    <div class="border-t p-4 dark:border-gray-800">
                        {{ $threads->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @pushOnce('styles')
    <style>
        /* [Giữ nguyên CSS như cũ - không thay đổi] */
        table {
            border-collapse: collapse !important;
            width: 100% !important;
            table-layout: fixed !important;
        }
        
        table thead {
            background-color: #f9fafb !important;
            border-bottom: 2px solid #e5e7eb !important;
        }
        
        table th {
            padding: 12px 12px !important;
            text-align: left !important;
            font-weight: 600 !important;
            font-size: 0.875rem !important;
            color: #374151 !important;
            text-transform: none !important;
            letter-spacing: 0 !important;
            white-space: nowrap !important;
            vertical-align: middle !important;
        }
        
        table td {
            padding: 12px 12px !important;
            border-bottom: 1px solid #e5e7eb !important;
            vertical-align: middle !important;
        }

        table th:nth-child(1), table td:nth-child(1) { width: 45px !important; max-width: 45px !important; text-align: center !important; padding: 10px 8px !important; }
        table th:nth-child(2), table td:nth-child(2) { width: 45px !important; max-width: 45px !important; text-align: center !important; padding: 10px 8px !important; }
        table th:nth-child(3), table td:nth-child(3) { width: 60px !important; max-width: 60px !important; text-align: left !important; padding: 12px 12px !important; }
        table th:nth-child(4), table td:nth-child(4) { width: 220px !important; max-width: 220px !important; padding: 12px 12px !important; }
        table th:nth-child(5), table td:nth-child(5) { width: 220px !important; max-width: 220px !important; padding: 12px 12px !important; }
        table th:nth-child(6), table td:nth-child(6) { width: 150px !important; min-width: 180px !important; padding: 12px 12px !important; }
        table th:nth-child(7), table td:nth-child(7) { width: 120px !important; max-width: 120px !important; text-align: center !important; padding: 12px 12px !important; }
        table th:nth-child(8), table td:nth-child(8) { width: 95px !important; max-width: 95px !important; text-align: center !important; padding: 12px 12px !important; }
        table th:nth-child(9), table td:nth-child(9) { width: 75px !important; max-width: 75px !important; text-align: center !important; padding: 12px 12px !important; }
        table th:nth-child(10), table td:nth-child(10) { width: 130px !important; max-width: 130px !important; text-align: center !important; padding: 12px 12px !important; }

        .email-row { cursor: pointer !important; transition: background-color 0.15s ease !important; }
        .email-row:hover { background-color: #f9fafb !important; }
        .email-row.unread { background-color: #eff6ff !important; }
        .email-row.unread:hover { background-color: #dbeafe !important; }
        .email-row.starred { background-color: #fef3c7 !important; }
        .email-row.starred:hover { background-color: #fde68a !important; }
        
        .star-btn { border: none !important; background: none !important; cursor: pointer !important; padding: 2px !important; }
        .star-btn:hover { transform: scale(1.15) !important; }
        .star-btn svg { width: 16px !important; height: 16px !important; }
        .star-filled { color: #f59e0b !important; fill: #f59e0b !important; }
        .star-empty { color: #d1d5db !important; fill: none !important; }
        
        .text-id { font-size: 0.875rem !important; color: #6b7280 !important; font-weight: 500 !important; }
        .email-text { font-size: 0.875rem !important; color: #111827 !important; display: block !important; overflow: hidden !important; text-overflow: ellipsis !important; white-space: nowrap !important; }
        .subject-link { color: #111827 !important; font-size: 0.875rem !important; text-decoration: none !important; display: block !important; overflow: hidden !important; text-overflow: ellipsis !important; white-space: nowrap !important; }
        .subject-link:hover { color: #2563eb !important; text-decoration: underline !important; }
        .status-badge { display: inline-flex !important; align-items: center !important; justify-content: center !important; padding: 4px 12px !important; border-radius: 9999px !important; font-size: 0.75rem !important; font-weight: 500 !important; white-space: nowrap !important; }
        .time-text { font-size: 0.875rem !important; color: #6b7280 !important; white-space: nowrap !important; }
        .count-text { font-size: 0.875rem !important; color: #6b7280 !important; font-weight: 500 !important; }
        
        .action-buttons { display: flex !important; gap: 4px !important; justify-content: center !important; align-items: center !important; }
        .action-buttons a, .action-buttons button { padding: 6px !important; border: none !important; background: none !important; border-radius: 4px !important; cursor: pointer !important; transition: all 0.15s ease !important; }
        .action-buttons a svg, .action-buttons button svg { width: 16px !important; height: 16px !important; color: #6b7280 !important; }
        .action-buttons a:hover, .action-buttons button:hover { background-color: #f3f4f6 !important; }
        .action-buttons .delete-btn:hover { background-color: #fee2e2 !important; }
        .action-buttons .delete-btn:hover svg { color: #dc2626 !important; }
        
        input[type="checkbox"] { cursor: pointer !important; width: 16px !important; height: 16px !important; }
    </style>
    @endPushOnce

    @pushOnce('scripts')
    <script>
        let selected = new Set();
        
        function toggleSelectAll(checkbox) {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = checkbox.checked;
                if (checkbox.checked) {
                    selected.add(parseInt(cb.value));
                } else {
                    selected.delete(parseInt(cb.value));
                }
            });
            updateBulkBar();
        }
        
        function updateBulkBar() {
            const checkboxes = document.querySelectorAll('.row-checkbox:checked');
            selected.clear();
            checkboxes.forEach(cb => selected.add(parseInt(cb.value)));
            
            const bar = document.getElementById('bulkBar');
            const count = document.getElementById('selectedCount');
            
            if (selected.size > 0) {
                bar.classList.remove('hidden');
                count.textContent = selected.size + ' đã chọn';
            } else {
                bar.classList.add('hidden');
            }
        }
        
        window.toggleStar = function(id, event) {
            event.stopPropagation();
            axios.put(`/admin/mail/${id}/toggle-star`)
                .then(response => {
                    if (response.data.success) {
                        location.reload();
                    }
                })
                .catch(err => {
                    console.error('Toggle star error:', err);
                    alert('Lỗi: ' + (err.response?.data?.message || 'Không thể đánh dấu sao'));
                });
        };
        
        window.archiveThread = function(id, event) {
            event.stopPropagation();
            axios.put(`/admin/mail/${id}/move`, { folder: 'archive' })
                .then(response => {
                    if (response.data.success) {
                        location.reload();
                    }
                })
                .catch(err => {
                    console.error('Archive error:', err);
                    alert('Lỗi khi lưu trữ');
                });
        };
        
        window.restoreThread = function(id, event) {
            event.stopPropagation();
            axios.put(`/admin/mail/${id}/restore`)
                .then(response => {
                    if (response.data.success) {
                        location.reload();
                    }
                })
                .catch(err => {
                    console.error('Restore error:', err);
                    alert('Lỗi khi khôi phục: ' + (err.response?.data?.message || 'Không thể khôi phục'));
                });
        };
        
        window.deleteThread = function(id, event) {
            event.stopPropagation();
            if (confirm('Bạn có chắc chắn muốn xóa email này?')) {
                axios.delete(`/admin/mail/${id}`)
                    .then(response => {
                        if (response.data.success) {
                            location.reload();
                        }
                    })
                    .catch(err => {
                        console.error('Delete error:', err);
                        alert('Lỗi khi xóa: ' + (err.response?.data?.message || 'Không thể xóa'));
                    });
            }
        };
        
        window.deletePermanent = function(id, event) {
            event.stopPropagation();
            if (confirm('CẢNH BÁO: Email sẽ bị xóa vĩnh viễn và không thể khôi phục!\n\nBạn có chắc chắn muốn xóa vĩnh viễn?')) {
                axios.delete(`/admin/mail/${id}/permanent`)
                    .then(response => {
                        if (response.data.success) {
                            location.reload();
                        }
                    })
                    .catch(err => {
                        console.error('Permanent delete error:', err);
                        alert('Lỗi khi xóa vĩnh viễn: ' + (err.response?.data?.message || 'Không thể xóa'));
                    });
            }
        };
        
        window.bulkMarkRead = function() {
            if (selected.size === 0) {
                alert('Vui lòng chọn ít nhất một email');
                return;
            }
            axios.put('/admin/mail/mass_update', {
                indices: Array.from(selected),
                action: 'mark_read'
            })
            .then(response => {
                if (response.data.success) {
                    location.reload();
                } else {
                    alert(response.data.message || 'Có lỗi xảy ra');
                }
            })
            .catch(err => {
                console.error('Bulk mark read error:', err);
                alert('Lỗi: ' + (err.response?.data?.message || 'Không thể đánh dấu đã đọc'));
            });
        };
        
        window.bulkArchive = function() {
            if (selected.size === 0) {
                alert('Vui lòng chọn ít nhất một email');
                return;
            }
            axios.put('/admin/mail/mass_update', {
                indices: Array.from(selected),
                action: 'move',
                folder: 'archive'
            })
            .then(response => {
                if (response.data.success) {
                    location.reload();
                } else {
                    alert(response.data.message || 'Có lỗi xảy ra');
                }
            })
            .catch(err => {
                console.error('Bulk archive error:', err);
                alert('Lỗi: ' + (err.response?.data?.message || 'Không thể lưu trữ'));
            });
        };
        
        window.bulkDelete = function() {
            if (selected.size === 0) {
                alert('Vui lòng chọn ít nhất một email');
                return;
            }
            
            if (confirm(`Bạn có chắc chắn muốn xóa ${selected.size} email?`)) {
                axios.put('/admin/mail/mass_update', {
                    indices: Array.from(selected),
                    action: 'delete'
                })
                .then(response => {
                    if (response.data.success) {
                        location.reload();
                    } else {
                        alert(response.data.message || 'Có lỗi xảy ra');
                    }
                })
                .catch(err => {
                    console.error('Bulk delete error:', err);
                    alert('Lỗi: ' + (err.response?.data?.message || 'Không thể xóa'));
                });
            }
        };
        
        window.bulkRestore = function() {
            if (selected.size === 0) {
                alert('Vui lòng chọn ít nhất một email');
                return;
            }
            
            if (confirm(`Bạn có muốn khôi phục ${selected.size} email đã chọn?`)) {
                axios.put('/admin/mail/mass_restore', {
                    indices: Array.from(selected)
                })
                .then(response => {
                    if (response.data.success) {
                        location.reload();
                    } else {
                        alert(response.data.message || 'Có lỗi xảy ra');
                    }
                })
                .catch(err => {
                    console.error('Bulk restore error:', err);
                    alert('Lỗi: ' + (err.response?.data?.message || 'Không thể khôi phục'));
                });
            }
        };
        
        window.bulkDeletePermanent = function() {
            if (selected.size === 0) {
                alert('Vui lòng chọn ít nhất một email');
                return;
            }
            
            if (confirm(`CẢNH BÁO: ${selected.size} email sẽ bị xóa vĩnh viễn không thể khôi phục!\n\nBạn có chắc chắn muốn tiếp tục?`)) {
                axios.delete('/admin/mail/mass_permanent', {
                    data: {
                        indices: Array.from(selected)
                    }
                })
                .then(response => {
                    if (response.data.success) {
                        location.reload();
                    } else {
                        alert(response.data.message || 'Có lỗi xảy ra');
                    }
                })
                .catch(err => {
                    console.error('Bulk permanent delete error:', err);
                    alert('Lỗi: ' + (err.response?.data?.message || 'Không thể xóa vĩnh viễn'));
                });
            }
        };
    </script>
    @endPushOnce
</x-admin::layouts>