<x-admin::layouts>
    <x-slot:title>
        Email Marketing Dashboard
    </x-slot>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto p-6">
            
            <!-- Header with Title centered and Time Range -->
            <div class="mb-8">
                <!-- Title centered -->
                <div class="text-center mb-4">
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Hello! Here's your recent email activity.</h1>
                </div>
                
                <!-- Time Range Selector centered -->
                <div class="flex justify-center">
                    <div class="inline-flex gap-2 bg-white dark:bg-gray-800 p-1.5 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                        <a href="{{ route('admin.mail.tracking.dashboard') }}?days=7" 
                           style="{{ request('days', 7) == 7 ? 'background-color: #2299DC !important; color: white !important;' : '' }}"
                           class="px-4 py-2 rounded-md text-sm font-semibold transition-all {{ request('days', 7) == 7 ? 'shadow-md' : 'bg-transparent text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            7 ngày
                        </a>
                        <a href="{{ route('admin.mail.tracking.dashboard') }}?days=30" 
                           style="{{ request('days') == 30 ? 'background-color: #2299DC !important; color: white !important;' : '' }}"
                           class="px-4 py-2 rounded-md text-sm font-semibold transition-all {{ request('days') == 30 ? 'shadow-md' : 'bg-transparent text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            30 ngày
                        </a>
                        <a href="{{ route('admin.mail.tracking.dashboard') }}?days=90" 
                           style="{{ request('days') == 90 ? 'background-color: #2299DC !important; color: white !important;' : '' }}"
                           class="px-4 py-2 rounded-md text-sm font-semibold transition-all {{ request('days') == 90 ? 'shadow-md' : 'bg-transparent text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            90 ngày
                        </a>
                    </div>
                </div>
            </div>

            <!-- 6 Stats Cards - SendGrid Style -->
            <div class="overflow-x-auto mb-8">
                <div class="flex justify-center gap-4 min-w-max px-4">
                    <!-- Requests -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-9 border border-gray-200 dark:border-gray-700 flex-shrink-0" style="width: 150px;">
                        <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3 text-center">Requests</div>
                        <div class="text-5xl font-bold text-gray-900 dark:text-white text-center mb-2">{{ number_format($stats['total_sent']) }}</div>
                        <div class="text-base font-bold text-blue-600 text-center">100.00%</div>
                        <div class="text-xs text-gray-400 text-center mt-1">{{ number_format($stats['total_sent']) }}</div>
                    </div>

                    <!-- Delivered -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-9 border border-gray-200 dark:border-gray-700 flex-shrink-0" style="width: 150px;">
                        <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3 text-center">Delivered</div>
                        <div class="text-5xl font-bold text-lime-500 text-center mb-2">
                            {{ $stats['total_sent'] > 0 ? number_format(($stats['total_delivered'] / $stats['total_sent']) * 100, 2) : 0 }}%
                        </div>
                        <div class="text-xs text-gray-400 text-center">{{ number_format($stats['total_delivered']) }}</div>
                    </div>

                    <!-- Opened -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-9 border border-gray-200 dark:border-gray-700 flex-shrink-0" style="width: 150px;">
                        <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3 text-center">Opened</div>
                        <div class="text-5xl font-bold text-teal-400 text-center mb-2">{{ $stats['open_rate'] }}%</div>
                        <div class="text-xs text-gray-400 text-center">{{ number_format($stats['total_opened']) }}</div>
                    </div>

                    <!-- Clicked -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-9 border border-gray-200 dark:border-gray-700 flex-shrink-0" style="width: 150px;">
                        <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3 text-center">Clicked</div>
                        <div class="text-5xl font-bold text-cyan-400 text-center mb-2">{{ $stats['click_rate'] }}%</div>
                        <div class="text-xs text-gray-400 text-center">{{ number_format($stats['total_clicked']) }}</div>
                    </div>

                    <!-- Bounces -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-9 border border-gray-200 dark:border-gray-700 flex-shrink-0" style="width: 150px;">
                        <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3 text-center">Bounces</div>
                        <div class="text-5xl font-bold text-pink-400 text-center mb-2">
                            {{ $stats['total_sent'] > 0 ? number_format(($stats['total_bounced'] / $stats['total_sent']) * 100, 2) : 0 }}%
                        </div>
                        <div class="text-xs text-gray-400 text-center">{{ number_format($stats['total_bounced']) }}</div>
                    </div>
                    
                    <!-- Spam Reports -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-9 border border-gray-200 dark:border-gray-700 flex-shrink-0" style="width: 150px;">
                        <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3 text-center">Spam Reports</div>
                        <div class="text-5xl font-bold {{ $stats['total_complaints'] > 0 ? 'text-red-500' : 'text-gray-300' }} text-center mb-2">
                            {{ $stats['total_sent'] > 0 ? number_format(($stats['total_complaints'] / $stats['total_sent']) * 100, 2) : 0 }}%
                        </div>
                        <div class="text-xs text-gray-400 text-center">{{ number_format($stats['total_complaints']) }}</div>
                    </div>
                </div>
            </div>

            <!-- 3 Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Chart 1: Line Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Hoạt động theo thời gian</h3>
                    <div style="position: relative; height: 300px;">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>

                <!-- Chart 2: Bar Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Hiệu suất theo ngày</h3>
                    <div style="position: relative; height: 300px;">
                        <canvas id="performanceChart"></canvas>
                    </div>
                </div>

                <!-- Chart 3: Pie Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Tỷ lệ mở email</h3>
                    <div style="position: relative; height: 300px;">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Hoạt động email gần đây</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Chủ đề</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Sự kiện</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Thời gian</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($recentEvents as $event)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                        @if($event->email)
                                            {{ is_array($event->email->to) ? ($event->email->to[0]['email'] ?? 'N/A') : ($event->email->to ?? 'N/A') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($event->email)
                                            <a href="{{ route('admin.mail.show', $event->email->thread_id ?? $event->email_id) }}" 
                                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:underline">
                                                {{ Str::limit($event->email->subject ?? 'Không có chủ đề', 50) }}
                                            </a>
                                        @else
                                            <span class="text-gray-500">Email đã bị xóa</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $badges = [
                                                'opened' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                'clicked' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                'bounced' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                                'delivered' => 'bg-teal-100 text-teal-800 dark:bg-teal-900 dark:text-teal-200',
                                                'complained' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
                                            ];
                                            
                                            $eventLabels = [
                                                'opened' => 'Đã mở',
                                                'clicked' => 'Đã click',
                                                'bounced' => 'Bị trả lại',
                                                'delivered' => 'Đã giao',
                                                'complained' => 'Báo spam',
                                            ];
                                        @endphp
                                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $badges[$event->event_type] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                            {{ $eventLabels[$event->event_type] ?? ucfirst($event->event_type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $event->created_at ? $event->created_at->locale('vi')->diffForHumans() : 'N/A' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                        <p class="text-lg font-medium">Chưa có hoạt động email nào</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        setTimeout(function() {
            // Activity Line Chart
            const activityCtx = document.getElementById('activityChart');
            if (activityCtx) {
                new Chart(activityCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($chartData['labels']) !!},
                        datasets: [
                            {
                                label: 'Đã gửi',
                                data: {!! json_encode($chartData['sent']) !!},
                                borderColor: 'rgb(59, 130, 246)',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.4,
                                fill: true,
                                borderWidth: 2
                            },
                            {
                                label: 'Đã mở',
                                data: {!! json_encode($chartData['opened']) !!},
                                borderColor: 'rgb(16, 185, 129)',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                tension: 0.4,
                                fill: true,
                                borderWidth: 2
                            },
                            {
                                label: 'Đã click',
                                data: {!! json_encode($chartData['clicked']) !!},
                                borderColor: 'rgb(245, 158, 11)',
                                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                                tension: 0.4,
                                fill: true,
                                borderWidth: 2
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    padding: 10,
                                    font: { size: 11 }
                                }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(0, 0, 0, 0.05)' }
                            },
                            x: {
                                grid: { display: false }
                            }
                        }
                    }
                });
            }

            // Pie Chart
            const pieCtx = document.getElementById('pieChart');
            if (pieCtx) {
                new Chart(pieCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Đã mở', 'Chưa mở'],
                        datasets: [{
                            data: [
                                {{ $stats['total_opened'] }},
                                {{ $stats['total_delivered'] - $stats['total_opened'] }}
                            ],
                            backgroundColor: ['rgb(16, 185, 129)', 'rgb(229, 231, 235)'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 10,
                                    font: { size: 11 }
                                }
                            }
                        }
                    }
                });
            }

            // Bar Chart
            const performanceCtx = document.getElementById('performanceChart');
            if (performanceCtx) {
                new Chart(performanceCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($chartData['labels']) !!},
                        datasets: [
                            {
                                label: 'Đã gửi',
                                data: {!! json_encode($chartData['sent']) !!},
                                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                                borderRadius: 4
                            },
                            {
                                label: 'Đã mở',
                                data: {!! json_encode($chartData['opened']) !!},
                                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                                borderRadius: 4
                            },
                            {
                                label: 'Đã click',
                                data: {!! json_encode($chartData['clicked']) !!},
                                backgroundColor: 'rgba(245, 158, 11, 0.8)',
                                borderRadius: 4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    padding: 10,
                                    font: { size: 11 }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(0, 0, 0, 0.05)' }
                            },
                            x: {
                                grid: { display: false }
                            }
                        }
                    }
                });
            }
        }, 500);
    </script>
    @endpush
</x-admin::layouts>