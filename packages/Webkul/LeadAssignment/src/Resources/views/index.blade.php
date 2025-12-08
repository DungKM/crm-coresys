<x-admin::layouts>
    <x-slot:title>@lang('Lead Assignment Settings')</x-slot:title>

    <x-admin::form :action="route('admin.settings.lead_assignment.store')" method="POST">
        <div class="flex items-center justify-between rounded-lg border dark:border-gray-800 bg-white dark:bg-gray-900 px-4 py-2">
            <div class="flex flex-col gap-2">
                <x-admin::breadcrumbs name="settings.lead_assignment" />
                <div class="text-xl font-bold dark:text-white">@lang('Lead Assignment')</div>
            </div>
            <button type="submit" class="primary-button">
                @lang('Save Settings')
            </button>
        </div>

        <div class="rounded-lg border bg-white p-6 dark:border-gray-800 dark:bg-gray-900 mt-4">
            <!-- Enable Lead Assignment -->
            <div class="mb-4">
                <label class="font-semibold">@lang('Enable Lead Assignment')</label>
                <input type="checkbox" name="enabled" value="1" {{ (old('enabled', $leadAssignmentConfig['lead_assignment.enabled'] ?? 0) == 1) ? 'checked' : '' }}>
            </div>

            <!-- Assignment Method -->
            <div class="mb-4">
                <label class="font-semibold">@lang('Assignment Method')</label>
                <select name="method" class="form-control w-60">
                    <option value="round_robin" {{ (old('method', $leadAssignmentConfig['lead_assignment.method'] ?? '') == 'round_robin') ? 'selected' : '' }}>@lang('Round Robin')</option>
                    <option value="weighted" {{ (old('method', $leadAssignmentConfig['lead_assignment.method'] ?? '') == 'weighted') ? 'selected' : '' }}>@lang('Weighted')</option>
                </select>
            </div>

            <!-- Active Sales Users -->
            <div class="mb-4">
                <label class="font-semibold">@lang('Active Sales Users')</label>
                <div class="flex flex-wrap gap-4 mt-2">
                    @php $activeUsers = json_decode($leadAssignmentConfig['lead_assignment.active_users'] ?? '[]', true); @endphp
                    @foreach($salesUsers as $user)
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="active_users[]" value="{{ $user->id }}"
                                {{ (is_array($activeUsers) && in_array($user->id, $activeUsers)) ? 'checked' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Weights Management (only show when Weighted is selected) -->
            <div class="mb-4" id="weights-section" style="display: none;">
                <label class="font-semibold">@lang('Weights Management')</label>
                <div class="flex flex-col gap-2 mt-2">
                    @php $weights = json_decode($leadAssignmentConfig['lead_assignment.weights'] ?? '{}', true); @endphp
                    @foreach($salesUsers as $user)
                        <div class="flex items-center gap-2">
                            <span class="w-40">{{ $user->name }} ({{ $user->email }})</span>
                            <input type="number" name="weights[{{ $user->id }}]" min="1" max="100" class="form-control w-24"
                                value="{{ $weights[$user->id] ?? 1 }}">
                            <span>%</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </x-admin::form>

    @pushOnce('scripts')
    <script>
        // Hiển thị/ẩn phần Weights khi chọn phương pháp Weighted
        document.addEventListener('DOMContentLoaded', function () {
            const methodSelect = document.querySelector('select[name="method"]');
            const weightsSection = document.getElementById('weights-section');
            function toggleWeights() {
                if (methodSelect.value === 'weighted') {
                    weightsSection.style.display = '';
                } else {
                    weightsSection.style.display = 'none';
                }
            }
            methodSelect.addEventListener('change', toggleWeights);
            toggleWeights();
        });
    </script>
    @endPushOnce
</x-admin::layouts>