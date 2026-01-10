<x-admin::layouts>
    <x-slot:title>
        @lang('googleads::app.google-ads.title')
    </x-slot:title>

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
                <button
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    @lang('googleads::app.google-ads.create_audience')
                </button>
                <button class="rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white hover:bg-blue-600">
                    @lang('googleads::app.google-ads.create_campaign')
                </button>
            </div>
        </div>

        <!-- Billing Alert -->
        <div class="rounded-lg border-l-4 border-orange-500 bg-orange-50 p-4 dark:border-orange-700 dark:bg-gray-900">
            <div class="flex items-start gap-3">
                <div class="flex-1">
                    <h3 class="font-semibold text-orange-900 dark:text-orange-300">
                        @lang('googleads::app.google-ads.billing_issue')
                    </h3>
                    <p class="mt-1 text-sm text-orange-800 dark:text-orange-400">
                        @lang('googleads::app.google-ads.billing_description')
                    </p>
                </div>
                <button class="text-orange-600 hover:text-orange-700 dark:text-orange-400">
                    ✕
                </button>
            </div>
        </div>

        <!-- Tabs -->
        <div
            class="flex items-center gap-8 border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 px-4">
            <button
                class="border-b-2 border-blue-600 py-3 font-semibold text-blue-600 dark:border-blue-500 dark:text-blue-400">
                @lang('googleads::app.google-ads.manage')
            </button>
            <button
                class="border-b-2 border-transparent py-3 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
                @lang('googleads::app.google-ads.audiences')
            </button>
            <button
                class="border-b-2 border-transparent py-3 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
                @lang('googleads::app.google-ads.events')
            </button>
            <button
                class="border-b-2 border-transparent py-3 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
                @lang('googleads::app.google-ads.analyze')
            </button>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-12 gap-4">
            <!-- Sidebar -->
            <div class="col-span-3">
                <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                    <div
                        class="border-b border-gray-200 px-4 py-3 font-semibold text-gray-900 dark:border-gray-800 dark:text-white">
                        @lang('googleads::app.google-ads.ad_campaigns')
                    </div>
                    <div class="p-4">
                        <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                            <span class="text-2xl font-bold">📊</span>
                            <span>@lang('googleads::app.google-ads.active_campaigns'): <strong>0</strong></span>
                        </div>
                        <div class="mt-4 space-y-2">
                            <a href="#"
                                class="block rounded-lg bg-gray-100 px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-200 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700">
                                @lang('googleads::app.google-ads.all_campaigns')
                            </a>
                            <a href="#"
                                class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800">
                                @lang('googleads::app.google-ads.drafts')
                            </a>
                            <a href="#"
                                class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800">
                                @lang('googleads::app.google-ads.active')
                            </a>
                            <a href="#"
                                class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800">
                                @lang('googleads::app.google-ads.paused')
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="col-span-9">
                <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                    <!-- TIP Banner -->
                    <div class="border-b border-gray-200 bg-blue-50 p-4 dark:border-gray-800 dark:bg-gray-800">
                        <div class="flex items-start gap-3">
                            <span
                                class="flex-shrink-0 rounded-full bg-blue-100 px-2 py-1 text-xs font-bold text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                TIP
                            </span>
                            <div class="flex-1">
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    @lang('googleads::app.google-ads.landing_page_tip')
                                </p>
                                <a href="#"
                                    class="text-sm font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                    @lang('googleads::app.google-ads.learn_more')
                                </a>
                            </div>
                            <button
                                class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-400">
                                ✕
                            </button>
                        </div>
                    </div>

                    <!-- Filters & Stats -->
                    <div class="border-b border-gray-200 p-4 dark:border-gray-800">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4 text-sm">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="font-semibold text-gray-900 dark:text-white">@lang('googleads::app.google-ads.account'):</span>
                                    <button class="text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                        TestLay (6265-1557-84) ▼
                                    </button>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="font-semibold text-gray-900 dark:text-white">@lang('googleads::app.google-ads.date_range'):</span>
                                    <button class="text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                        Last 30 days ▼
                                    </button>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="font-semibold text-gray-900 dark:text-white">@lang('googleads::app.google-ads.status'):</span>
                                    <button class="text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                        Active ▼
                                    </button>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button
                                    class="rounded-lg border border-gray-300 bg-white px-3 py-1 text-sm text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                    @lang('googleads::app.google-ads.attribution_reports')
                                </button>
                                <button
                                    class="rounded-lg bg-purple-600 px-3 py-1 text-sm text-white hover:bg-purple-700">
                                    📊 @lang('googleads::app.google-ads.generate_summary')
                                </button>
                                <button
                                    class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
                                    Export
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    @php
                        $totalImpressions = collect($campaigns)->sum('impressions');
                        $totalClicks = collect($campaigns)->sum('clicks');
                        $totalConversions = collect($campaigns)->sum('conversions');
                        $totalCost = collect($campaigns)->sum('cost');
                    @endphp
                    <div class="grid grid-cols-5 gap-4 border-b border-gray-200 p-4 dark:border-gray-800">
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

                    <!-- Edit Columns & Search -->
                    <div class="flex items-center justify-between border-b border-gray-200 p-4 dark:border-gray-800">
                        <button
                            class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                            📋 @lang('googleads::app.google-ads.edit_columns')
                        </button>
                        <div class="flex items-center gap-2">
                            <input type="text" placeholder="@lang('googleads::app.google-ads.search_campaigns')"
                                class="rounded-lg border border-gray-300 px-3 py-2 text-sm placeholder-gray-400 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                            <button
                                class="rounded-lg border border-gray-300 bg-white px-2 py-2 text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400">
                                🔍
                            </button>
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
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($error))
                                    <!-- Error State -->
                                    <tr>
                                        <td colspan="8" class="px-4 py-12 text-center">
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
                                        <td colspan="8" class="px-4 py-12 text-center">
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
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $campaign['name'] }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                                TestLay
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
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer Info -->
                    <div
                        class="border-t border-gray-200 px-4 py-2 text-right text-xs text-gray-500 dark:border-gray-800 dark:text-gray-400">
                        @lang('googleads::app.google-ads.reporting_note')
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
        </script>
    @endPushOnce
</x-admin::layouts>
