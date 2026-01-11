<x-admin::layouts>
    <x-slot:title>
        @lang('googleads::app.google-ads.title')
    </x-slot:title>

    @pushOnce('styles')
        <style>
            [x-cloak]{display:none !important;}
            .skeleton {
                background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
                background-size: 200% 100%;
                animation: loading 1.5s ease-in-out infinite;
            }
            @keyframes loading {
                0% { background-position: 200% 0; }
                100% { background-position: -200% 0; }
            }
            .dark .skeleton {
                background: linear-gradient(90deg, #374151 25%, #4b5563 50%, #374151 75%);
                background-size: 200% 100%;
            }
        </style>
    @endPushOnce

    <div class="flex flex-col gap-4">
        <!-- Header with Breadcrumbs -->
        <div
            class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-2">
                <x-admin::breadcrumbs name="google_ads.index" />
                <div class="text-xl font-bold dark:text-white">
                    @lang('googleads::app.google-ads.title')
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.google_ads.campaigns.create') }}" class="rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white hover:bg-blue-600">
                    + @lang('googleads::app.google-ads.create_campaign')
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <!-- Filters -->
            <div class="border-b border-gray-200 p-4 dark:border-gray-800">
            <!-- Filters -->
            <div class="border-b border-gray-200 p-4 dark:border-gray-800">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4 text-sm">
                        <span class="font-semibold text-gray-900 dark:text-white">@lang('googleads::app.google-ads.account'):</span>
                        <span class="text-gray-600 dark:text-gray-400">
                            @if($account_name && $customer_id)
                                {{ $account_name }} ({{ substr($customer_id, 0, 3) }}-{{ substr($customer_id, 3, 3) }}-{{ substr($customer_id, 6) }})
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="text" placeholder="@lang('googleads::app.google-ads.search_campaigns')"
                            class="rounded-lg border border-gray-300 px-3 py-2 text-sm placeholder-gray-400 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                        <button
                            class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400">
                            🔍
                        </button>
                    </div>
                </div>
            </div>

                    <!-- Statistics Cards -->
                    @php
                        $totalImpressions = isset($campaigns) ? collect($campaigns)->sum('impressions') : 0;
                        $totalClicks = isset($campaigns) ? collect($campaigns)->sum('clicks') : 0;
                        $totalConversions = isset($campaigns) ? collect($campaigns)->sum('conversions') : 0;
                        $totalCost = isset($campaigns) ? collect($campaigns)->sum('cost') : 0;
                    @endphp
                    <div class="grid grid-cols-5 gap-4 border-b border-gray-200 p-4 dark:border-gray-800" id="statistics-cards">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalImpressions) }}</div>
                            <div class="mt-1 text-xs font-semibold text-gray-600 dark:text-gray-400">
                                @lang('googleads::app.google-ads.impressions')
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalClicks) }}</div>
                            <div class="mt-1 text-xs font-semibold text-gray-600 dark:text-gray-400">
                                @lang('googleads::app.google-ads.clicks')
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalConversions) }}</div>
                            <div class="mt-1 text-xs font-semibold text-gray-600 dark:text-gray-400">
                                @lang('googleads::app.google-ads.contacts')
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($totalCost, 2) }}</div>
                            <div class="mt-1 text-xs font-semibold text-gray-600 dark:text-gray-400">
                                @lang('googleads::app.google-ads.amount_spent')
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                {{ $totalConversions > 0 ? number_format(($totalCost / $totalConversions), 2) : '0.00' }}
                            </div>
                            <div class="mt-1 text-xs font-semibold text-gray-600 dark:text-gray-400">
                                @lang('googleads::app.google-ads.roi')
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-800">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-900 dark:text-white">
                                        @lang('googleads::app.google-ads.name') ↓
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-900 dark:text-white">
                                        @lang('googleads::app.google-ads.account_name') ↓
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-900 dark:text-white">
                                        @lang('googleads::app.google-ads.type') ↓
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-900 dark:text-white">
                                        @lang('googleads::app.google-ads.impressions') ↓
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-900 dark:text-white">
                                        @lang('googleads::app.google-ads.clicks') ↓
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-900 dark:text-white">
                                        @lang('googleads::app.google-ads.total_contacts') ↓
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-900 dark:text-white">
                                        @lang('googleads::app.google-ads.cost_per_contact') ↓
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-900 dark:text-white">
                                        @lang('googleads::app.google-ads.amount_spent') ↓
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-900 dark:text-white">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="campaigns-table-body">
                                @if(!isset($campaigns) && !isset($error))
                                    <!-- Loading Skeleton State -->
                                    @for($i = 0; $i < 5; $i++)
                                    <tr class="border-b border-gray-200 dark:border-gray-800">
                                        <td class="px-4 py-3"><div class="skeleton h-4 w-32 rounded"></div></td>
                                        <td class="px-4 py-3"><div class="skeleton h-4 w-24 rounded"></div></td>
                                        <td class="px-4 py-3"><div class="skeleton h-6 w-16 rounded-full"></div></td>
                                        <td class="px-4 py-3 text-center"><div class="skeleton h-4 w-12 rounded mx-auto"></div></td>
                                        <td class="px-4 py-3 text-center"><div class="skeleton h-4 w-12 rounded mx-auto"></div></td>
                                        <td class="px-4 py-3 text-center"><div class="skeleton h-4 w-12 rounded mx-auto"></div></td>
                                        <td class="px-4 py-3 text-center"><div class="skeleton h-4 w-16 rounded mx-auto"></div></td>
                                        <td class="px-4 py-3 text-right"><div class="skeleton h-4 w-16 rounded ml-auto"></div></td>
                                        <td class="px-4 py-3 text-center"><div class="skeleton h-6 w-24 rounded mx-auto"></div></td>
                                    </tr>
                                    @endfor
                                @elseif(isset($error))
                                    <!-- Error State -->
                                    <tr>
                                        <td colspan="9" class="px-4 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="mb-4 text-6xl">⚠️</div>
                                                <p class="text-red-600 dark:text-red-400 font-semibold">
                                                    Không thể kết nối Google Ads API
                                                </p>
                                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-500">
                                                    {{ $error }}
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @elseif(empty($campaigns))
                                    <!-- Empty State -->
                                    <tr>
                                        <td colspan="9" class="px-4 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="mb-4 text-6xl">🔍</div>
                                                <p class="text-gray-600 dark:text-gray-400">
                                                    @lang('googleads::app.google-ads.no_campaigns')
                                                </p>
                                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-500">
                                                    Bạn chưa có chiến dịch quảng cáo nào. Hãy tạo chiến dịch đầu tiên!
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @else
                                    <!-- Campaigns Data -->
                                    @foreach($campaigns as $campaign)
                                        <tr class="border-b border-gray-200 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800">
                                            <td class="px-4 py-3 text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                                {{ $campaign['name'] }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                                {{ $account_name ?? 'N/A' }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                                <span class="rounded-full px-2 py-1 text-xs font-medium 
                                                    {{ $campaign['status'] == 2 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300' }}">
                                                    {{ $campaign['status'] == 2 ? 'Active' : 'Paused' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center text-sm text-gray-600 dark:text-gray-400">
                                                {{ number_format($campaign['impressions']) }}
                                            </td>
                                            <td class="px-4 py-3 text-center text-sm text-gray-600 dark:text-gray-400">
                                                {{ number_format($campaign['clicks']) }}
                                            </td>
                                            <td class="px-4 py-3 text-center text-sm text-gray-600 dark:text-gray-400">
                                                {{ number_format($campaign['conversions']) }}
                                            </td>
                                            <td class="px-4 py-3 text-center text-sm text-gray-600 dark:text-gray-400">
                                                ${{ $campaign['conversions'] > 0 ? number_format($campaign['cost'] / $campaign['conversions'], 2) : '0.00' }}
                                            </td>
                                            <td class="px-4 py-3 text-right text-sm font-medium text-gray-900 dark:text-white">
                                                ${{ number_format($campaign['cost'], 2) }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="flex items-center justify-center gap-2">
                                                    <a href="{{ route('admin.google_ads.campaigns.show', $campaign['id']) }}" 
                                                        class="rounded-lg bg-blue-500 px-3 py-1 text-xs font-medium text-white hover:bg-blue-600">
                                                        View
                                                    </a>
                                                    <a href="{{ route('admin.google_ads.campaigns.edit', $campaign['id']) }}" 
                                                        class="rounded-lg bg-green-500 px-3 py-1 text-xs font-medium text-white hover:bg-green-600">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('admin.google_ads.campaigns.destroy', $campaign['id']) }}" method="POST" class="inline"
                                                        onsubmit="return confirm('Are you sure you want to delete this campaign?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                            class="rounded-lg bg-red-500 px-3 py-1 text-xs font-medium text-white hover:bg-red-600">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                    
                </div>
            </div>
        </div>
    </div>

    @pushOnce('scripts')
        <script>
            // Tab switching
            document.querySelectorAll('[role="tab"]').forEach(tab => {
                tab.addEventListener('click', function () {
                    console.log('Tab clicked');
                });
            });

            // Close alerts
            document.querySelectorAll('[data-dismiss="alert"]').forEach(btn => {
                btn.addEventListener('click', function () {
                    this.closest('.alert').style.display = 'none';
                });
            });

            // Simple dropdown toggles (fallback if Alpine.js not present)
            (function () {
                function closeAllDropdowns() {
                    document.querySelectorAll('[data-dropdown-menu]').forEach(menu => {
                        menu.style.display = 'none';
                        menu.removeAttribute('data-open');
                    });
                }

                document.addEventListener('click', function (e) {
                    const toggle = e.target.closest('[data-dropdown-toggle]');
                    if (toggle) {
                        const key = toggle.getAttribute('data-dropdown-toggle');
                        const menu = document.querySelector('[data-dropdown-menu="' + key + '"]');
                        if (!menu) return;
                        // Toggle this menu
                        const isOpen = menu.getAttribute('data-open') === 'true';
                        closeAllDropdowns();
                        if (!isOpen) {
                            menu.style.display = 'block';
                            menu.setAttribute('data-open', 'true');
                        }
                        e.stopPropagation();
                        return;
                    }
                    // Clicked outside, close all
                    closeAllDropdowns();
                });

                // Close on ESC
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') closeAllDropdowns();
                });
            })();

            // Loading state management
            document.addEventListener('DOMContentLoaded', function() {
                const tableBody = document.getElementById('campaigns-table-body');
                const statsCards = document.getElementById('statistics-cards');
                
                // Check if we have error state
                const hasError = tableBody && tableBody.querySelector('td[colspan="8"]')?.textContent.includes('Không thể kết nối');
                
                if (hasError) {
                    console.log('Google Ads API error detected. Data may be temporarily unavailable.');
                    // Optional: Auto-retry after 5 seconds
                    // setTimeout(() => window.location.reload(), 5000);
                }

                // Add loading class during page load
                if (statsCards) {
                    statsCards.classList.add('animate-pulse');
                    setTimeout(() => {
                        statsCards.classList.remove('animate-pulse');
                    }, 1000);
                }
            });
        </script>
    @endPushOnce
</x-admin::layouts>
