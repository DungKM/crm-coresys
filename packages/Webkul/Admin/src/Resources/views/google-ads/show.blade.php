<x-admin::layouts>
    <x-slot:title>
        {{ $campaign['name'] }} - @lang('googleads::app.google-ads.campaign_details')
    </x-slot:title>

    <div class="flex flex-col gap-4">
        <!-- Header -->
        <div
            class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-2">
                <x-admin::breadcrumbs name="google_ads.campaigns.show" />
                <div class="text-xl font-bold dark:text-white">
                    {{ $campaign['name'] }}
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.google_ads.index') }}"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300">
                    ← @lang('googleads::app.google-ads.back_to_campaigns')
                </a>
                <a href="{{ route('admin.google_ads.campaigns.edit', $campaign['id']) }}" 
                    class="rounded-lg bg-green-500 px-4 py-2 text-sm font-medium text-white hover:bg-green-600">
                    ✏️ @lang('googleads::app.google-ads.edit_campaign')
                </a>
                <form action="{{ route('admin.google_ads.campaigns.destroy', $campaign['id']) }}" method="POST" class="inline"
                    onsubmit="return confirm('Are you sure you want to delete this campaign?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white hover:bg-red-600">
                        🗑️ @lang('googleads::app.google-ads.delete_campaign')
                    </button>
                </form>
            </div>
        </div>

        <!-- Campaign Overview -->
        <div class="flex flex-col gap-4">
            <!-- Main Info -->
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                    <div
                        class="border-b border-gray-200 px-4 py-3 font-semibold text-gray-900 dark:border-gray-800 dark:text-white">
                        @lang('googleads::app.google-ads.campaign_information')
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">@lang('googleads::app.google-ads.campaign_name')</label>
                                <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $campaign['name'] }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">@lang('googleads::app.google-ads.campaign_id')</label>
                                <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $campaign['id'] }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">@lang('googleads::app.google-ads.status')</label>
                                <p class="mt-1">
                                    <span class="rounded-full px-3 py-1 text-sm font-medium {{ $campaign['status'] == 2 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-800' }}">
                                        @if($campaign['status'] == 2)
                                            <span class="text-green-700">@lang('googleads::app.google-ads.active')</span>
                                        @elseif($campaign['status'] == 3)
                                            <span class="text-yellow-700">@lang('googleads::app.google-ads.paused')</span>
                                        @else
                                            <span class="text-gray-700">@lang('googleads::app.google-ads.unknown_status')</span>
                                        @endif
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">@lang('googleads::app.google-ads.account_name')</label>
                                <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $account_name ?? __('googleads::app.google-ads.not_available') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Metrics -->
                <div class="mt-4 rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                    <div
                        class="border-b border-gray-200 px-4 py-3 font-semibold text-gray-900 dark:border-gray-800 dark:text-white">
                        @lang('googleads::app.google-ads.performance_metrics')
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-4 gap-4">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ number_format($campaign['impressions']) }}</div>
                                <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    @lang('googleads::app.google-ads.impressions')</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ number_format($campaign['clicks']) }}</div>
                                <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    @lang('googleads::app.google-ads.clicks')</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ number_format($campaign['conversions']) }}</div>
                                <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    @lang('googleads::app.google-ads.conversions')</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-green-600 dark:text-green-400">
                                    ${{ number_format($campaign['cost'], 2) }}</div>
                                <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    @lang('googleads::app.google-ads.total_spent')</div>
                            </div>
                        </div>

                        <!-- Additional Metrics -->
                        <div class="mt-6 grid grid-cols-3 gap-4 border-t border-gray-200 pt-6 dark:border-gray-700">
                            <div>
                                <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">@lang('googleads::app.google-ads.ctr')</label>
                                <p class="mt-1 text-lg font-bold text-gray-900 dark:text-white">
                                    {{ $campaign['impressions'] > 0 ? number_format(($campaign['clicks'] / $campaign['impressions']) * 100, 2) : '0.00' }}%
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">@lang('googleads::app.google-ads.cpc')</label>
                                <p class="mt-1 text-lg font-bold text-gray-900 dark:text-white">
                                    ${{ $campaign['clicks'] > 0 ? number_format($campaign['cost'] / $campaign['clicks'], 2) : '0.00' }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">@lang('googleads::app.google-ads.cost_per_conversion')</label>
                                <p class="mt-1 text-lg font-bold text-gray-900 dark:text-white">
                                    ${{ $campaign['conversions'] > 0 ? number_format($campaign['cost'] / $campaign['conversions'], 2) : '0.00' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Campaign Settings -->
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                    <div
                        class="border-b border-gray-200 px-4 py-3 font-semibold text-gray-900 dark:border-gray-800 dark:text-white">
                        @lang('googleads::app.google-ads.campaign_settings')
                    </div>
                    <div class="p-4 space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">@lang('googleads::app.google-ads.created_date')</span>
                            <span class="font-medium text-gray-900 dark:text-white">
                                {{ $campaign['start_date'] ?? __('googleads::app.google-ads.not_available') }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">@lang('googleads::app.google-ads.last_modified')</span>
                            <span class="font-medium text-gray-900 dark:text-white">
                                {{ $campaign['end_date'] ?? __('googleads::app.google-ads.not_available') }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">@lang('googleads::app.google-ads.budget_type')</span>
                            <span class="font-medium text-gray-900 dark:text-white">
                                @if(isset($campaign['budget_period']) && $campaign['budget_period'] == 2)
                                    @lang('googleads::app.google-ads.daily')
                                @else
                                    @lang('googleads::app.google-ads.not_available')
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">@lang('googleads::app.google-ads.daily_budget')</span>
                            <span class="font-medium text-gray-900 dark:text-white">
                                ${{ isset($campaign['budget_amount']) ? number_format($campaign['budget_amount'], 2) : '0.00' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin::layouts>