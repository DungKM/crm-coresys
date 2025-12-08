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

        <div class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900 mt-4">
            <!-- Enable Lead Assignment -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="enabled" value="1" id="lead-assignment-enabled" 
                        class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        {{ (old('enabled', $leadAssignmentConfig['lead_assignment.enabled'] ?? 0) == 1) ? 'checked' : '' }}>
                    <label for="lead-assignment-enabled" class="text-base font-semibold text-gray-900 dark:text-white cursor-pointer">
                        @lang('Enable Lead Assignment')
                    </label>
                </div>
                <p class="mt-2 ml-8 text-sm text-gray-600 dark:text-gray-400">
                    @lang('Automatically assign new leads to sales users based on the selected method.')
                </p>
            </div>

            <!-- Assignment Method -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                <label class="block text-base font-semibold text-gray-900 dark:text-white mb-3">
                    @lang('Assignment Method')
                </label>
                <div class="relative w-full max-w-md">
                    <select name="method" class="block w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-base shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white transition">
                        <option value="round_robin" {{ (old('method', $leadAssignmentConfig['lead_assignment.method'] ?? '') == 'round_robin') ? 'selected' : '' }} class="py-2 px-4 text-base text-gray-900 dark:text-white bg-white dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-gray-700 cursor-pointer">
                            @lang('Round Robin') - @lang('Distribute leads evenly')
                        </option>
                        <option value="weighted" {{ (old('method', $leadAssignmentConfig['lead_assignment.method'] ?? '') == 'weighted') ? 'selected' : '' }} class="py-2 px-4 text-base text-gray-900 dark:text-white bg-white dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-gray-700 cursor-pointer">
                            @lang('Weighted') - @lang('Distribute based on percentages')
                        </option>
                    </select>
                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </div>
                <div id="round-robin-info" class="mt-3 hidden">
                    <div class="flex items-center gap-3 rounded-md bg-blue-50 border border-blue-100 px-3 py-2 text-sm text-blue-700 dark:bg-blue-900 dark:border-blue-800 dark:text-blue-200">
                        <svg class="h-4 w-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m4-4h.01M12 8v4m0 0v4h.01M12 8V4m0 12h.01"/></svg>
                        <div>
                            <div id="roundRobinText" class="font-medium">@lang('Round Robin assigns leads equally among active sales users.')</div>
                            <div id="roundRobinCalc" class="text-xs text-blue-600 dark:text-blue-300">@lang('Each selected user will receive ~0% of leads')</div>
                        </div>
                    </div>
                </div>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    @lang('Round Robin assigns leads in rotation, Weighted assigns based on custom percentages.')
                </p>
            </div>

            <!-- Active Sales Users -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                <label class="block text-base font-semibold text-gray-900 dark:text-white mb-3">
                    @lang('Active Sales Users')
                </label>
                <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                    @lang('Quản lý nhân viên nhận lead và tỉ lệ phân bổ cho từng người.')
                </p>
                <div class="flex items-center gap-3 mb-4">
                    <div class="relative w-full max-w-xs">
                        <input type="text" id="searchInput" placeholder="Tìm kiếm nhân viên..." onkeydown="if(event.key === 'Enter'){ event.preventDefault(); if(window.doSearch) window.doSearch(); }" class="w-full rounded-lg border border-gray-300 bg-white dark:bg-gray-800 dark:border-gray-700 dark:text-white px-4 py-2 pr-10 text-sm shadow focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition" />
                        <button type="button" id="searchBtn" class="absolute inset-y-0 right-0 flex items-center pr-3 focus:outline-none" tabindex="0" aria-label="Tìm kiếm" onclick="if(window.doSearch) window.doSearch(); return false;">
                            <svg class="h-5 w-5 text-gray-400 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" /></svg>
                        </button>
                        <!-- debug placeholder removed (moved to a more visible area) -->
                    </div>
                    <!-- Search results count (visible, next to buttons) -->
                    <div id="searchCount" class="hidden text-sm text-gray-600 dark:text-gray-400 mt-0 ml-2" role="status" aria-live="polite"></div>
                    <div id="selectedCount" class="hidden text-sm text-gray-600 dark:text-gray-400 mt-0 ml-4" role="status" aria-live="polite">Đã chọn: 0</div>
                    <button type="button" id="searchBtnAlt" class="primary-button" aria-label="Tìm kiếm" onclick="if(window.doSearch) window.doSearch(); return false;">
                        <span>Tìm kiếm</span>
                    </button>
                    <button type="button" id="selectAllBtn" class="primary-button" onclick="toggleSelectAll(event); return false;">
                        <span id="selectAllText">Chọn tất cả</span>
                    </button>
                </div>
                <!-- Bulk toolbar (shows when any rows are selected) -->
                <div id="bulkToolbar" class="hidden rounded-lg border border-gray-200 bg-white px-4 py-2 mb-3 flex items-center justify-between shadow-sm dark:bg-gray-900 dark:border-gray-800">
                    <div class="flex items-center gap-3">
                        <div id="bulkSelectedCount" class="text-sm font-medium text-gray-900 dark:text-white">Đã chọn: 0</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" id="bulkClearBtn" class="text-sm text-gray-700 dark:text-gray-300 hover:underline">Bỏ chọn tất cả</button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200 dark:divide-gray-700 rounded-lg border border-gray-200 dark:border-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">@lang('Chọn')</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">@lang('Tên nhân viên')</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">@lang('Email')</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">@lang('Tỉ lệ (%)')</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-100 dark:divide-gray-800">
                            @php $activeUsers = json_decode($leadAssignmentConfig['lead_assignment.active_users'] ?? '[]', true); $weights = json_decode($leadAssignmentConfig['lead_assignment.weights'] ?? '{}', true); @endphp
                            @foreach($salesUsers as $user)
                                <tr class="user-row" data-search="{{ strtolower($user->name . ' ' . $user->email) }}">
                                    <td class="px-4 py-2">
                                        <input type="checkbox" name="active_users[]" value="{{ (int)$user->id }}"
                                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 user-checkbox">
                                    </td>
                                    <td class="px-6 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</td>
                                    <td class="px-6 py-3 text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</td>
                                    <td class="px-6 py-3">
                                        <input type="number" name="weights[{{ $user->id }}]" min="1" max="100"
                                            class="w-20 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white weights-input"
                                            value="{{ $weights[$user->id] ?? 1 }}">
                                        <span class="rr-percent hidden text-sm text-gray-700 dark:text-gray-300 ml-2">0%</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Weights Management (only show when Weighted is selected) -->
            <div id="weights-section" class="px-6 py-4" style="display: none;">
                <label class="block text-base font-semibold text-gray-900 dark:text-white mb-3">
                    @lang('Weights Management')
                </label>
                <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                    @lang('Set the percentage of leads each user should receive. Total should equal 100%.')
                </p>
                <div class="space-y-3">
                    @php $weights = json_decode($leadAssignmentConfig['lead_assignment.weights'] ?? '{}', true); @endphp
                    @foreach($salesUsers as $user)
                        <div class="flex items-center gap-4 p-3 rounded-lg border border-gray-200 dark:border-gray-700">
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</div>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="number" name="weights[{{ $user->id }}]" min="1" max="100" 
                                    class="w-20 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white"
                                    value="{{ $weights[$user->id] ?? 1 }}">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">%</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </x-admin::form>
    

    @pushOnce('scripts')
    <script>
        // Search + Select-all handlers (moved into script stack so layout prints it)
        (function() {
            function toggleSelectAll(event) {
                if (event && event.preventDefault) event.preventDefault();
                const checkboxes = document.querySelectorAll('input[name="active_users[]"]');
                const visibleCheckboxes = Array.from(checkboxes).filter(cb => {
                    const row = cb.closest('.user-row');
                    return row && row.offsetParent !== null;
                });
                const uncheckedCheckboxes = visibleCheckboxes.filter(cb => !cb.checked);
                const shouldCheck = uncheckedCheckboxes.length > 0;
                visibleCheckboxes.forEach(cb => cb.checked = shouldCheck);
                updateSelectAllButtonText();
                updateSelectedCount();
                updateSelectedCount();
            }

            function updateSelectAllButtonText() {
                const checkboxes = document.querySelectorAll('input[name="active_users[]"]');
                const visibleCheckboxes = Array.from(checkboxes).filter(cb => {
                    const row = cb.closest('.user-row');
                    return row && row.offsetParent !== null;
                });
                const checkedCount = visibleCheckboxes.filter(cb => cb.checked).length;
                const totalCount = visibleCheckboxes.length;
                const selectAllText = document.getElementById('selectAllText');
                const searchCountEl = document.getElementById('searchCount');
                if (selectAllText) {
                    if (checkedCount === totalCount && totalCount > 0) {
                        selectAllText.textContent = 'Bỏ chọn tất cả';
                    } else {
                        selectAllText.textContent = 'Chọn tất cả';
                    }
                }
                // Update search count UI
                if (searchCountEl) {
                    const userRows = document.querySelectorAll('.user-row');
                    const total = userRows.length;
                    const visibleCount = visibleCheckboxes.length; // same as visible rows
                    if (visibleCount === total && (document.getElementById('searchInput') ? document.getElementById('searchInput').value.trim() === '' : true)) {
                        searchCountEl.classList.add('hidden');
                    } else {
                        searchCountEl.classList.remove('hidden');
                        searchCountEl.textContent = `${visibleCount} / ${total} kết quả`;
                    }
                }
                // Update selected count UI
                const selectedCountEl = document.getElementById('selectedCount');
                if (selectedCountEl) {
                    const selectedTotal = document.querySelectorAll('input[name="active_users[]"]:checked').length;
                    selectedCountEl.textContent = `Đã chọn: ${selectedTotal}`;
                }
                // Update bulk toolbar
                const bulkToolbar = document.getElementById('bulkToolbar');
                const bulkSelectedCount = document.getElementById('bulkSelectedCount');
                if (bulkToolbar && bulkSelectedCount) {
                    const selectedTotal = document.querySelectorAll('input[name="active_users[]"]:checked').length;
                    bulkSelectedCount.textContent = `Đã chọn: ${selectedTotal}`;
                    if (selectedTotal > 0) {
                        bulkToolbar.classList.remove('hidden');
                    } else {
                        bulkToolbar.classList.add('hidden');
                    }
                }
                // Also update Round Robin per-user calc if visible
                const roundRobinInfo = document.getElementById('round-robin-info');
                if (roundRobinInfo && !roundRobinInfo.classList.contains('hidden')) {
                    updateRoundRobinInfo();
                }
            }

            function updateSelectedCount() {
                const selectedCountEl = document.getElementById('selectedCount');
                if (!selectedCountEl) return;
                const selectedTotal = document.querySelectorAll('input[name="active_users[]"]:checked').length;
                selectedCountEl.textContent = `Đã chọn: ${selectedTotal}`;
                // Update bulk toolbar (mirror)
                const bulkToolbar = document.getElementById('bulkToolbar');
                const bulkSelectedCount = document.getElementById('bulkSelectedCount');
                if (bulkSelectedCount) {
                    bulkSelectedCount.textContent = `Đã chọn: ${selectedTotal}`;
                }
                if (bulkToolbar) {
                    if (selectedTotal > 0) bulkToolbar.classList.remove('hidden'); else bulkToolbar.classList.add('hidden');
                }
                // Show/hide the small selectedCount element next to the controls
                if (selectedTotal > 0) selectedCountEl.classList.remove('hidden'); else selectedCountEl.classList.add('hidden');
                // If round robin UI is visible, recalc
                const roundRobinInfo = document.getElementById('round-robin-info');
                if (roundRobinInfo && !roundRobinInfo.classList.contains('hidden')) {
                    updateRoundRobinInfo();
                }
            }

            function doSearch() {
                const searchInput = document.getElementById('searchInput');
                if (!searchInput) { console.log('doSearch: searchInput not found'); return; }
                const searchText = searchInput.value.toLowerCase().trim();
                const userRows = document.querySelectorAll('.user-row');
                let matched = 0;
                userRows.forEach(row => {
                    const dataSearch = (row.dataset.search || '').toLowerCase();
                    if (searchText === '' || dataSearch.includes(searchText)) {
                        row.style.display = '';
                        matched++;
                    } else {
                        row.style.display = 'none';
                    }
                });
                console.log('doSearch matched rows:', matched, 'out of', userRows.length);
                // Update visible debug element
                const searchCountEl = document.getElementById('searchCount');
                if (searchCountEl) {
                    searchCountEl.textContent = matched + ' / ' + userRows.length + ' kết quả';
                }
                updateSelectAllButtonText();
                // If round robin is active, update its calc
                const roundRobinInfo = document.getElementById('round-robin-info');
                if (roundRobinInfo && !roundRobinInfo.classList.contains('hidden')) {
                    updateRoundRobinInfo();
                }
            }

            function updateRoundRobinInfo() {
                const activeCheckboxes = Array.from(document.querySelectorAll('input[name="active_users[]"]')).filter(cb => cb.closest('.user-row') && cb.closest('.user-row').offsetParent !== null && cb.checked);
                const selectedCount = activeCheckboxes.length || 0;
                const visibleRows = Array.from(document.querySelectorAll('.user-row')).filter(row => row.offsetParent !== null).length;
                const countForCalc = selectedCount > 0 ? selectedCount : visibleRows;
                const calcEl = document.getElementById('roundRobinCalc');
                if (!calcEl) return;
                console.log('updateRoundRobinInfo called, selected:', selectedCount, 'visible:', visibleRows, 'used:', countForCalc);
                let percent = 0;
                if (countForCalc <= 0) {
                    calcEl.textContent = '@lang("Each selected user will receive ~0% of leads")';
                } else {
                    percent = Math.floor(100 / countForCalc);
                    calcEl.textContent = `@lang('Each selected user will receive ~')` + percent + `%`;
                }

                // Update per-row Round Robin percent display
                const rows = document.querySelectorAll('.user-row');
                rows.forEach(row => {
                    const cb = row.querySelector('input[name="active_users[]"]');
                    const rrSpan = row.querySelector('.rr-percent');
                    if (!rrSpan) return;
                    const isConsidered = (selectedCount > 0) ? (cb && cb.checked) : (row.offsetParent !== null);
                    if (countForCalc <= 0) {
                        rrSpan.textContent = '—';
                    } else if (isConsidered) {
                        rrSpan.textContent = percent + '%';
                    } else {
                        rrSpan.textContent = '0%';
                    }
                });
            }

            function initHandlers() {
                                // Wire up bulk clear button
                                const bulkClearBtn = document.getElementById('bulkClearBtn');
                                if (bulkClearBtn) {
                                    bulkClearBtn.addEventListener('click', function(e) {
                                        e.preventDefault();
                                        const checkboxes = document.querySelectorAll('input[name="active_users[]"]');
                                        const visibleCheckboxes = Array.from(checkboxes).filter(cb => cb.closest('.user-row') && cb.closest('.user-row').offsetParent !== null);
                                        visibleCheckboxes.forEach(cb => cb.checked = false);
                                        updateSelectedCount();
                                        updateSelectAllButtonText();
                                    });
                                }
                console.log('leadAssignment script initHandlers running');
                // Expose toggleSelectAll globally for inline onclick
                window.toggleSelectAll = toggleSelectAll;

                // Use event delegation to handle dynamically replaced DOM elements
                document.addEventListener('input', function(e) {
                    if (e.target && e.target.id === 'searchInput') {
                        // console.log('delegated input event on searchInput');
                        doSearch();
                    }
                });
                document.addEventListener('keydown', function(e) {
                    if (e.target && e.target.id === 'searchInput' && e.key === 'Enter') {
                        e.preventDefault();
                        // console.log('delegated keydown Enter on searchInput');
                        doSearch();
                    }
                    // If Enter pressed and Round Robin is active and focus is on a checkbox, update info
                    if (e.target && e.target.name === 'active_users[]' && e.key === 'Enter') {
                        e.preventDefault();
                        if (document.querySelector('select[name="method"]').value === 'round_robin') {
                            updateRoundRobinInfo();
                        }
                    }
                });
                document.addEventListener('click', function(e) {
                    const target = e.target;
                    if (!target) return;
                    if (target.closest && (target.closest('#searchBtn') || target.closest('#searchBtnAlt'))) {
                        e.preventDefault();
                        // console.log('delegated click to search buttons');
                        doSearch();
                    }
                });
                // Delegate change events for dynamic checkboxes
                document.addEventListener('change', function(e) {
                    const target = e.target;
                    if (!target) return;
                    if (target.name === 'active_users[]') {
                        // checkbox changed
                        updateSelectAllButtonText();
                        updateSelectedCount();
                        // debug
                        console.log('checkbox change, checked total:', document.querySelectorAll('input[name="active_users[]"]:checked').length);
                        const selectMethod = document.querySelector('select[name="method"]');
                        if (selectMethod) console.log('current method value:', selectMethod.value);
                    }
                    // Method select changed (delegated) -> call toggleWeights if exposed
                    if (target.name === 'method') {
                        if (typeof window.toggleWeights === 'function') {
                            try { window.toggleWeights(); } catch (err) { console.error(err); }
                        }
                    }
                });

                const checkboxes = document.querySelectorAll('input[name="active_users[]"]');
                checkboxes.forEach(checkbox => checkbox.addEventListener('change', function(){ updateSelectAllButtonText(); updateSelectedCount(); }));
                // In case new checkboxes added later, also use delegated listeners defined above

                updateSelectAllButtonText();
                updateSelectedCount();
                updateSelectedCount();
                // Set global doSearch exposed
                window.doSearch = doSearch;
                // Expose updateRoundRobinInfo in case needed from console
                window.updateRoundRobinInfo = updateRoundRobinInfo;
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initHandlers);
            } else {
                initHandlers();
            }
        })();
    </script>

    <script>
        // Hiển thị/ẩn phần Weights khi chọn phương pháp Weighted và thông báo cho Round Robin
        document.addEventListener('DOMContentLoaded', function () {
            const methodSelect = document.querySelector('select[name="method"]');
            const weightsSection = document.getElementById('weights-section');
            const roundRobinInfo = document.getElementById('round-robin-info');
            function toggleWeights() {
                if (!methodSelect) return;
                const value = methodSelect.value;
                console.log('toggleWeights called, method:', value);
                if (value === 'weighted') {
                    if (weightsSection) weightsSection.style.display = '';
                    if (roundRobinInfo) roundRobinInfo.classList.add('hidden');
                    // Show weight inputs and hide round-robin per-row spans
                    document.querySelectorAll('.weights-input').forEach(i => i.classList.remove('hidden'));
                    document.querySelectorAll('.rr-percent').forEach(s => s.classList.add('hidden'));
                } else if (value === 'round_robin') {
                    if (weightsSection) weightsSection.style.display = 'none';
                    if (roundRobinInfo) roundRobinInfo.classList.remove('hidden');
                    // Update calculation for round robin when selected
                    // Hide weight inputs, show per-row rr-percent spans, then recalc
                    document.querySelectorAll('.weights-input').forEach(i => i.classList.add('hidden'));
                    document.querySelectorAll('.rr-percent').forEach(s => s.classList.remove('hidden'));
                    if (typeof updateRoundRobinInfo === 'function') updateRoundRobinInfo();
                } else {
                    if (weightsSection) weightsSection.style.display = 'none';
                    if (roundRobinInfo) roundRobinInfo.classList.add('hidden');
                    document.querySelectorAll('.weights-input').forEach(i => i.classList.add('hidden'));
                    document.querySelectorAll('.rr-percent').forEach(s => s.classList.add('hidden'));
                }
            }
            if (methodSelect) methodSelect.addEventListener('change', toggleWeights);
            // expose toggleWeights for manual debug
            window.toggleWeights = toggleWeights;
            toggleWeights();
        });
    </script>
    @endPushOnce
</x-admin::layouts>