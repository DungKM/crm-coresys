<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.workflows-automation.automation.title')
    </x-slot>

    <v-automation>
        <div class="flex flex-col gap-4">
             <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    <x-admin::breadcrumbs name="workflow.automation" />
                    <div class="text-xl font-bold dark:text-white">
                        @lang('admin::app.workflows-automation.automation.title')
                    </div>
                </div>
                <div class="flex items-center gap-x-2.5">
                    <div class="flex items-center gap-x-2.5">
                    </div>

                </div>
            </div>
            {{-- <div class="flex items-center justify-between">
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-x-2">
                        <h1 class="text-2xl font-bold dark:text-white">
                            Tự động hóa
                        </h1>
                        <span class="label-active">Automation</span>
                    </div>

                    <x-admin::breadcrumbs name="settings.workflows" />
                </div>

                <div class="flex items-center gap-x-2.5">
                    <button type="button" class="secondary-button">
                        <i class="icon-settings text-xl"></i>
                        Cấu hình
                    </button>

                    <button type="button" class="primary-button">
                        <i class="icon-plus text-xl"></i>
                        Tạo Rule
                    </button>
                </div>
            </div> --}}

            {{-- Card container giống Keys --}}
            <div class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                {{-- Toolbar giống Keys --}}
                <div class="flex flex-col gap-3 border-b border-gray-200 px-4 py-3 dark:border-gray-800 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-x-2">
                        <div class="relative">
                            <i class="icon-search absolute left-3 top-2 text-xl text-gray-400"></i>
                            <input
                                type="text"
                                class="w-72 rounded-md border border-gray-200 py-1.5 pl-10 pr-4 text-sm outline-none focus:border-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                placeholder="Tìm kiếm rule..."
                            >
                        </div>

                        <button class="secondary-button text-sm">
                            <i class="icon-filter text-lg"></i>
                            Bộ lọc
                        </button>
                    </div>

                    <div class="flex items-center gap-2">
                        <button class="secondary-button text-sm">
                            <i class="icon-refresh text-lg"></i>
                            Đồng bộ n8n
                        </button>
                    </div>
                </div>

                {{-- Table header (như Keys) --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                        <thead class="bg-gray-50 text-xs font-semibold uppercase text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-3">Cấu hình rule</th>
                                <th class="px-4 py-3">Lịch trình n8n</th>
                                <th class="px-4 py-3">Trạng thái</th>
                                <th class="px-4 py-3 text-right">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            {{-- Row 1 --}}
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="rounded-full flex align-items-center bg-blue-100 p-2 text-blue-600 dark:bg-blue-500/20 dark:text-blue-300">
                                            <i class="icon-facebook-outline text-2xl"></i>
                                        </div>

                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2">
                                                <p class="truncate font-semibold text-gray-900 dark:text-white">
                                                    AUTO FB DAILY
                                                </p>
                                                <span class="rounded-full bg-indigo-50 px-2 py-0.5 text-[10px] font-bold uppercase text-indigo-700 ring-1 ring-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-300 dark:ring-indigo-500/20">
                                                    Oldest unused
                                                </span>
                                            </div>

                                            <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-400">
                                                Facebook post
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="rounded-full bg-gray-100 p-2 text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                            <i class="icon-calendar text-xl"></i>
                                        </div>
                                        <div class="leading-tight">
                                            <p class="text-xs text-gray-400">Hàng ngày lúc</p>
                                            <p class="font-semibold text-gray-900 dark:text-white">08:00</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center gap-2 rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700 dark:bg-green-500/20 dark:text-green-300">
                                        <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                        Active
                                    </span>
                                </td>

                                <td class="px-4 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <button class="rounded-md flex align-items-center bg-gray-200 px-2 py-2" title="Cấu hình">
                                            <i class="icon-setting text-xl"></i>
                                        </button>
                                        <button class="rounded-md flex align-items-center bg-gray-200 px-2 py-2" title="Chạy">
                                            <i class="icon-play text-xl"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="rounded-full flex align-items-center bg-purple-100 p-2 text-purple-600 dark:bg-purple-500/20 dark:text-purple-300">
                                            <i class="icon-mail text-2xl"></i>
                                        </div>

                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2">
                                                <p class="truncate font-semibold text-gray-900 dark:text-white">
                                                    AUTO EMAIL WEEKLY
                                                </p>
                                                <span class="rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-bold uppercase text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                                    Random
                                                </span>
                                            </div>

                                            <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-400">
                                                Email content
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="rounded-full bg-gray-100 p-2 text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                            <i class="icon-calendar text-xl"></i>
                                        </div>
                                        <div class="leading-tight">
                                            <p class="text-xs text-gray-400">Mỗi tuần</p>
                                            <p class="font-semibold text-gray-900 dark:text-white">Thứ 2 • 09:30</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center gap-2 rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                        <span class="h-2 w-2 rounded-full bg-gray-400"></span>
                                        Paused
                                    </span>
                                </td>

                                <td class="px-4 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <button class="rounded-md flex align-items-center bg-gray-200 px-2 py-2" title="Cấu hình">
                                            <i class="icon-setting text-xl"></i>
                                        </button>
                                        <button class="rounded-md flex align-items-center bg-gray-200 px-2 py-2" title="Chạy">
                                            <i class="icon-play text-xl"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex items-center justify-between border-t border-gray-200 px-4 py-3 dark:border-gray-800">
                    <p class="text-xs text-gray-500">Hiển thị 1 đến 10 của 12 kết quả</p>
                    <div class="flex gap-1">
                        <button class="rounded border border-gray-200 px-2 py-1 text-xs dark:border-gray-700">Trước</button>
                        <button class="rounded border border-blue-600 bg-blue-600 px-2 py-1 text-xs text-white">1</button>
                        <button class="rounded border border-gray-200 px-2 py-1 text-xs dark:border-gray-700">Sau</button>
                    </div>
                </div>
            </div>
        </div>
    </v-automation>
</x-admin::layouts>
