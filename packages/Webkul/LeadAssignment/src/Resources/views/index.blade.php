<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.lead-assignment.index.title')
        </x-slot>

        <div class="flex flex-col gap-4">
            @if (session('success'))
                <div class="rounded-md bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('warning'))
                <div class="rounded-md bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 text-sm">
                    {{ session('warning') }}
                </div>
            @endif
            @if (session('error'))
                <div class="rounded-md bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div
                class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    <!-- Breadcrumbs -->
                    <x-admin::breadcrumbs name="settings.lead_assignment" />

                    <div class="text-xl font-bold dark:text-white">
                        @lang('admin::app.settings.lead-assignment.index.title')
                    </div>
                </div>

                <div class="flex items-center gap-x-2.5">
                    {!! view_render_event('admin.settings.lead_assignment.save_button.before') !!}

                    <!-- Save Button -->
                    <button type="submit" form="lead-assignment-form" class="primary-button">
                        @lang('Lưu cấu hình')
                    </button>

                    {!! view_render_event('admin.settings.lead_assignment.save_button.after') !!}
                </div>
            </div>

            {!! view_render_event('admin.settings.lead_assignment.content.before') !!}

            <!-- Use the registered Vue component -->
            <lead-assignment-settings :sales-users='@json($salesUsers)'
                :lead-assignment-config='@json($leadAssignmentConfig)'
                store-url='{{ route('admin.settings.lead_assignment.store') }}' csrf-token='{{ csrf_token() }}'
                :translations='{
                "enableFeature": "Bật tính năng chia lead",
                "enableDescription": "Tự động chia lead cho sales theo phương pháp đã chọn.",
                "methodLabel": "Phương pháp chia lead",
                "roundRobinOption": "Round Robin - Chia đều",
                "weightedOption": "Weighted - Theo phần trăm",
                "roundRobinInfo": "Round Robin chia đều lead cho sales đang bật.",
                "roundRobinCalc": "Mỗi sales nhận ~:percent% lead",
                "weightedInfo": "Weighted phân bổ theo số sao của mỗi sales.",
                "weightedCalc": "Click vào số sao để đánh giá năng lực sales (1-5 sao)",
                "methodDescription": "Round Robin: chia luân phiên, Weighted: chia theo phần trăm tuỳ chỉnh.",
                "salesListLabel": "Danh sách sales",
                "salesListDescription": "Quản lý nhân viên nhận lead và tỉ lệ phân bổ cho từng người.",
                "searchPlaceholder": "Tìm kiếm nhân viên...",
                "results": "kết quả",
                "selected": "Đã chọn",
                "searchButton": "Tìm kiếm",
                "selectAll": "Chọn tất cả",
                "deselectAll": "Bỏ chọn tất cả",
                "clearAll": "Bỏ chọn tất cả",
                "selectColumn": "Chọn",
                "nameColumn": "Tên nhân viên",
                "emailColumn": "Email",
                "levelColumn": "Level/Stars",
                "percentColumn": "Phần trăm (%)",
                "weightsLabel": "Quản lý tỉ lệ chia",
                "weightsDescription": "Tổng phần trăm phải bằng 100%. (Tự động tính theo số sao)"
            }'></lead-assignment-settings>
            {!! view_render_event('admin.settings.lead_assignment.content.after') !!}
        </div>

        @pushOnce('scripts')
            @vite('packages/Webkul/LeadAssignment/src/Resources/assets/js/app.js')
        @endPushOnce
</x-admin::layouts>