<x-admin::layouts>
    <x-slot:title>
        {{ $campaign['name'] }} - @lang('googleads::app.google-ads.edit_campaign')
    </x-slot:title>

    <div class="flex flex-col gap-4">
        <!-- Header -->
        <div
            class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-2">
                <x-admin::breadcrumbs name="google_ads.campaigns.edit" />
                <div class="text-xl font-bold dark:text-white">
                    @lang('googleads::app.google-ads.edit_campaign'): {{ $campaign['name'] }}
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.google_ads.campaigns.show', $campaign['id']) }}"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300">
                    ← @lang('googleads::app.google-ads.cancel')
                </a>
            </div>
        </div>

        <!-- Edit Campaign Form -->
        <form method="POST" action="{{ route('admin.google_ads.campaigns.update', $campaign['id']) }}">
            @csrf
            @method('PUT')

            <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <div class="space-y-6">
                    <!-- Campaign Name -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            @lang('googleads::app.google-ads.campaign_name') <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="campaign_name" required value="{{ $campaign['name'] }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                            placeholder="@lang('googleads::app.google-ads.enter_campaign_name')" />
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            @lang('googleads::app.google-ads.status')
                        </label>
                        <select name="status"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                            <option value="ENABLED" {{ $campaign['status'] == 2 ? 'selected' : '' }}>@lang('googleads::app.google-ads.active')</option>
                            <option value="PAUSED" {{ $campaign['status'] == 3 ? 'selected' : '' }}>@lang('googleads::app.google-ads.paused')</option>
                            <option value="REMOVED" {{ $campaign['status'] == 4 ? 'selected' : '' }}>@lang('googleads::app.google-ads.removed')</option>
                        </select>
                    </div>

                    <!-- Budget -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            @lang('googleads::app.google-ads.daily_budget')
                        </label>
                        <input type="number" name="daily_budget" min="0" step="0.01"
                            value="{{ $campaign['budget_amount'] ?? 0 }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                            placeholder="0.00" />
                    </div>

                    <!-- Account Info (Read-only) -->
                    <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span
                                    class="font-semibold text-gray-700 dark:text-gray-300">@lang('googleads::app.google-ads.account_name'):</span>
                                <span class="text-gray-600 dark:text-gray-400">{{ $account_name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span
                                    class="font-semibold text-gray-700 dark:text-gray-300">@lang('googleads::app.google-ads.campaign_id'):</span>
                                <span class="text-gray-600 dark:text-gray-400">{{ $campaign['id'] }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Note -->
                    <div class="rounded-lg border-l-4 border-blue-500 bg-blue-50 p-4 dark:bg-blue-900/20">
                        <p class="text-sm text-blue-800 dark:text-blue-300">
                            <strong>@lang('googleads::app.google-ads.note'):</strong> @lang('googleads::app.google-ads.campaign_sync_note')
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3 border-t pt-6 dark:border-gray-700">
                        <a href="{{ route('admin.google_ads.campaigns.show', $campaign['id']) }}"
                            class="rounded-lg border border-gray-300 bg-white px-6 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300">
                            @lang('googleads::app.google-ads.cancel')
                        </a>
                        <button type="submit"
                            class="rounded-lg bg-green-500 px-6 py-2 text-sm font-medium text-white hover:bg-green-600">
                            💾 @lang('googleads::app.google-ads.save_changes')
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-admin::layouts>