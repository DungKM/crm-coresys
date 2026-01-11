<x-admin::layouts>
    <x-slot:title>
        @lang('googleads::app.google-ads.create_campaign')
    </x-slot:title>

    <div class="flex flex-col gap-4">
        <!-- Header -->
        <div
            class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-2">
                <x-admin::breadcrumbs name="google_ads.campaigns.create" />
                <div class="text-xl font-bold dark:text-white">
                    @lang('googleads::app.google-ads.create_new_campaign')
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.google_ads.index') }}"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300">
                    ← @lang('googleads::app.google-ads.cancel')
                </a>
            </div>
        </div>

        <!-- Create Campaign Form -->
        <form method="POST" action="{{ route('admin.google_ads.campaigns.store') }}">
            @csrf

            <div class="grid grid-cols-12 gap-4">
                <!-- Main Form -->
                <div class="col-span-8">
                    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                        <div
                            class="border-b border-gray-200 px-4 py-3 font-semibold text-gray-900 dark:border-gray-800 dark:text-white">
                            @lang('googleads::app.google-ads.campaign_details')
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Campaign Name -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('googleads::app.google-ads.campaign_name') <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="campaign_name" required
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                    placeholder="@lang('googleads::app.google-ads.enter_campaign_name')" />
                            </div>

                            <!-- Campaign Goal -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('googleads::app.google-ads.campaign_goal') <span class="text-red-500">*</span>
                                </label>
                                <select name="campaign_goal" required
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                                    <option value="">@lang('googleads::app.google-ads.select_goal')</option>
                                    <option value="sales">@lang('googleads::app.google-ads.goal_sales')</option>
                                    <option value="leads">@lang('googleads::app.google-ads.goal_leads')</option>
                                    <option value="website_traffic">@lang('googleads::app.google-ads.goal_website_traffic')</option>
                                    <option value="brand_awareness">@lang('googleads::app.google-ads.goal_brand_awareness')</option>
                                    <option value="app_promotion">@lang('googleads::app.google-ads.goal_app_promotion')</option>
                                </select>
                            </div>

                            <!-- Campaign Type -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('googleads::app.google-ads.campaign_type') <span class="text-red-500">*</span>
                                </label>
                                <select name="campaign_type" required
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                                    <option value="">@lang('googleads::app.google-ads.select_type')</option>
                                    <option value="search">@lang('googleads::app.google-ads.type_search')</option>
                                    <option value="display">@lang('googleads::app.google-ads.type_display')</option>
                                    <option value="video">@lang('googleads::app.google-ads.type_video')</option>
                                    <option value="shopping">@lang('googleads::app.google-ads.type_shopping')</option>
                                    <option value="performance_max">@lang('googleads::app.google-ads.type_performance_max')</option>
                                </select>
                            </div>

                            <!-- Budget -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        @lang('googleads::app.google-ads.daily_budget') <span
                                            class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="daily_budget" required min="0" step="0.01"
                                        class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                        placeholder="0.00" />
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        @lang('googleads::app.google-ads.bidding_strategy')
                                    </label>
                                    <select name="bidding_strategy"
                                        class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                                        <option value="maximize_conversions">@lang('googleads::app.google-ads.bid_maximize_conversions')</option>
                                        <option value="target_cpa">@lang('googleads::app.google-ads.bid_target_cpa')</option>
                                        <option value="manual_cpc">@lang('googleads::app.google-ads.bid_manual_cpc')</option>
                                        <option value="maximize_clicks">@lang('googleads::app.google-ads.bid_maximize_clicks')</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Start & End Date -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        @lang('googleads::app.google-ads.start_date')
                                    </label>
                                    <input type="date" name="start_date"
                                        class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-800 dark:text-white" />
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        @lang('googleads::app.google-ads.end_date')
                                    </label>
                                    <input type="date" name="end_date"
                                        class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-800 dark:text-white" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-span-4">
                    <!-- Tips -->
                    <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-800 dark:bg-gray-900">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">💡</span>
                            <div>
                                <h3 class="font-semibold text-blue-900 dark:text-blue-300">@lang('googleads::app.google-ads.campaign_tips')</h3>
                                <ul class="mt-2 space-y-2 text-sm text-blue-800 dark:text-blue-400">
                                    <li>• @lang('googleads::app.google-ads.tip_clear_name')</li>
                                    <li>• @lang('googleads::app.google-ads.tip_realistic_budget')</li>
                                    <li>• @lang('googleads::app.google-ads.tip_right_type')</li>
                                    <li>• @lang('googleads::app.google-ads.tip_monitor_performance')</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div
                        class="mt-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <button type="submit"
                            class="w-full rounded-lg bg-blue-500 px-4 py-3 text-sm font-medium text-white hover:bg-blue-600">
                            🚀 @lang('googleads::app.google-ads.create_campaign')
                        </button>
                        <p class="mt-3 text-center text-xs text-gray-500 dark:text-gray-400">
                            @lang('googleads::app.google-ads.campaign_create_note')
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-admin::layouts>