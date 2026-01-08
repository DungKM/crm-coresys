<x-admin::layouts>
    <x-slot:title>
         @lang('admin::app.workflows-automation.dashboard.title')
    </x-slot>

    <v-dashboard :datasets='@json($datasets)'>
        <div class="flex flex-col gap-6">
            {{-- Header --}}
            {{-- <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-start justify-between gap-4">
                    <div class="grid gap-1">
                        <p class="text-xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                            Dashboard
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Thống kê hiệu suất tự động hóa và các tiến trình đang chạy.
                        </p>
                    </div>

                    <div class="flex gap-x-2">
                        <button class="primary-button">
                            <span class="icon-plus text-lg"></span>
                            Tạo Workflow mới
                        </button>
                    </div>
                </div>
            </div> --}}
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    <x-admin::breadcrumbs name="workflow.dashboard" />
                    <div class="text-xl font-bold dark:text-white">
                        @lang('admin::app.workflows-automation.dashboard.title')
                    </div>
                </div>
                <div class="flex items-center gap-x-2.5">
                    <div class="flex items-center gap-x-2.5">
                    </div>
                </div>
            </div>
            {{-- KPI cards --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                {{-- 1 --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-[0_10px_30px_rgba(15,23,42,0.06)] transition hover:-translate-y-0.5 hover:shadow-[0_16px_40px_rgba(15,23,42,0.10)] dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Đang hoạt động</p>
                            <p class="mt-1 text-3xl font-extrabold text-green-600">12</p>
                        </div>

                        <div class="grid h-11 w-11 place-items-center rounded-2xl bg-green-100 text-green-700 ring-1 ring-green-200 dark:bg-green-500/15 dark:text-green-300 dark:ring-green-500/20">
                            <span class="icon-workflow text-xl"></span>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                        <span class="h-2 w-2 rounded-full bg-green-500"></span>
                        Đang chạy ổn định
                    </div>
                </div>

                {{-- 2 --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-[0_10px_30px_rgba(15,23,42,0.06)] transition hover:-translate-y-0.5 hover:shadow-[0_16px_40px_rgba(15,23,42,0.10)] dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Tổng số lượt chạy</p>
                            <p class="mt-1 text-3xl font-extrabold text-blue-600">1,248</p>
                        </div>

                        <div class="grid h-11 w-11 place-items-center rounded-2xl bg-blue-100 text-blue-700 ring-1 ring-blue-200 dark:bg-blue-500/15 dark:text-blue-300 dark:ring-blue-500/20">
                            <span class="icon-play text-xl"></span>
                        </div>
                    </div>

                    <div class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                        +48 so với tuần trước
                    </div>
                </div>

                {{-- 3 --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-[0_10px_30px_rgba(15,23,42,0.06)] transition hover:-translate-y-0.5 hover:shadow-[0_16px_40px_rgba(15,23,42,0.10)] dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Tỷ lệ thành công</p>
                            <p class="mt-1 text-3xl font-extrabold text-purple-600">98.5%</p>
                        </div>

                        <div class="grid h-11 w-11 place-items-center rounded-2xl bg-purple-100 text-purple-700 ring-1 ring-purple-200 dark:bg-purple-500/15 dark:text-purple-300 dark:ring-purple-500/20">
                            <span class="icon-chart-line text-xl"></span>
                        </div>
                    </div>

                    <div class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                        SLA: 99% mục tiêu
                    </div>
                </div>

                {{-- 4 --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-[0_10px_30px_rgba(15,23,42,0.06)] transition hover:-translate-y-0.5 hover:shadow-[0_16px_40px_rgba(15,23,42,0.10)] dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Lỗi hệ thống</p>
                            <p class="mt-1 text-3xl font-extrabold text-red-600">3</p>
                        </div>

                        <div class="grid h-11 w-11 place-items-center rounded-2xl bg-red-100 text-red-700 ring-1 ring-red-200 dark:bg-red-500/15 dark:text-red-300 dark:ring-red-500/20">
                            <span class="icon-error text-xl"></span>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center gap-2 text-xs text-red-600 dark:text-red-300">
                        <span class="h-2 w-2 rounded-full bg-red-500 shadow-[0_0_10px_rgba(239,68,68,0.45)]"></span>
                        Cần xử lý sớm
                    </div>
                </div>
            </div>

            {{-- Charts + Activity --}}
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                {{-- Chart --}}
                <div class="lg:col-span-2 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="mb-4 flex items-center justify-between">
                        <p class="font-extrabold text-gray-900 dark:text-white">
                            Lưu lượng thực thi (7 ngày qua)
                        </p>

                        <button class="rounded-full bg-gray-50 px-3 py-1 text-xs font-semibold text-gray-600 ring-1 ring-gray-200 hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-200 dark:ring-gray-700 dark:hover:bg-gray-700">
                            7 ngày
                        </button>
                    </div>

                    <div class="rounded-2xl bg-gray-50 p-4 ring-1 ring-gray-100 dark:bg-gray-800/60 dark:ring-gray-700">
                        {{-- <x-admin::charts.line ... /> --}}
                        <div class="h-56"></div>
                    </div>
                </div>

                {{-- Activity --}}
                <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-center justify-between border-b border-gray-200 p-4 dark:border-gray-800">
                        <p class="font-extrabold text-gray-900 dark:text-white">Hoạt động gần nhất</p>
                        <a href="#" class="text-xs font-semibold text-blue-600 hover:underline">Xem tất cả</a>
                    </div>

                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        <div class="flex gap-3 p-4 transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <div class="mt-1 h-2 w-2 rounded-full bg-green-500"></div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Gửi Email chào mừng</p>
                                <p class="text-xs text-gray-500">Cho Lead: "Nguyễn Văn A" • 2 phút trước</p>
                            </div>
                            <span class="icon-arrow-right text-gray-400"></span>
                        </div>

                        <div class="flex gap-3 p-4 transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <div class="mt-1 h-2 w-2 rounded-full bg-green-500"></div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Tự động gắn Tag "VIP"</p>
                                <p class="text-xs text-gray-500">Tiến trình hoàn tất • 15 phút trước</p>
                            </div>
                            <span class="icon-arrow-right text-gray-400"></span>
                        </div>

                        <div class="flex gap-3 p-4 transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <div class="mt-1 h-2 w-2 rounded-full bg-red-500 shadow-[0_0_10px_rgba(239,68,68,0.45)]"></div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Đồng bộ Webhook</p>
                                <p class="text-xs text-red-500 italic">Thất bại: Sai định dạng JSON • 1 giờ trước</p>
                            </div>
                            <span class="icon-arrow-right text-gray-400"></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top workflows table --}}
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="border-b border-gray-200 p-4 dark:border-gray-800">
                    <p class="font-extrabold text-gray-900 dark:text-white">Top Workflow hiệu quả</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                        <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-3">Tên Workflow</th>
                                <th class="px-4 py-3">Sự kiện kích hoạt</th>
                                <th class="px-4 py-3">Lượt chạy</th>
                                <th class="px-4 py-3">Trạng thái</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">Auto Assign Lead</td>
                                <td class="px-4 py-3 text-gray-500">Lead Created</td>
                                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">450</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-2 rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700 dark:bg-green-500/20 dark:text-green-300">
                                        <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                        ACTIVE
                                    </span>
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">Lost Lead Notification</td>
                                <td class="px-4 py-3 text-gray-500">Stage Updated</td>
                                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">128</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-2 rounded-full bg-yellow-100 px-3 py-1 text-xs font-bold text-yellow-700 dark:bg-yellow-500/20 dark:text-yellow-300">
                                        <span class="h-2 w-2 rounded-full bg-yellow-500"></span>
                                        PAUSED
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </v-dashboard>
</x-admin::layouts>
