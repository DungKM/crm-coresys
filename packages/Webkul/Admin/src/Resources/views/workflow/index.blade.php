<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.workflow.index.title')
    </x-slot>

    <v-workflow>
        <div class="flex flex-col gap-4">
            <!-- Header -->
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    <div class="text-xl font-bold dark:text-white">
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        Chọn nền tảng và tạo quy trình công việc tự động cho các chiến dịch tiếp thị của bạn.
                    </div>
                </div>
            </div>
        </div>
    </v-workflow>
</x-admin::layouts>
