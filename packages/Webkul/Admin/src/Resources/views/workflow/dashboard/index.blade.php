<x-admin::layouts>
    <x-slot:title>
        Dashboard Workflow
    </x-slot>
<v-dashboard :datasets='@json($datasets)'>
    <div class="flex items-center justify-between mb-6">
            <div class="grid gap-1">
                <p class="text-xl font-bold text-gray-800 dark:text-white">
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

        <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-4">
            <div class="card p-4 bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-lg shadow-sm">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Đang hoạt động</p>
                        <p class="text-2xl font-bold mt-1 text-green-600">12</p>
                    </div>
                    <div class="p-2 bg-green-100 rounded-md">
                        <span class="icon-workflow text-green-600 text-xl"></span>
                    </div>
                </div>
            </div>

            <div class="card p-4 bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-lg shadow-sm">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Tổng số lượt chạy</p>
                        <p class="text-2xl font-bold mt-1 text-blue-600">1,248</p>
                    </div>
                    <div class="p-2 bg-blue-100 rounded-md">
                        <span class="icon-play text-blue-600 text-xl"></span>
                    </div>
                </div>
            </div>

            <div class="card p-4 bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-lg shadow-sm">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Tỷ lệ thành công</p>
                        <p class="text-2xl font-bold mt-1 text-purple-600">98.5%</p>
                    </div>
                    <div class="p-2 bg-purple-100 rounded-md">
                        <span class="icon-done text-purple-600 text-xl"></span>
                    </div>
                </div>
            </div>

            <div class="card p-4 bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-lg shadow-sm">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Lỗi hệ thống</p>
                        <p class="text-2xl font-bold mt-1 text-red-600">3</p>
                    </div>
                    <div class="p-2 bg-red-100 rounded-md">
                        <span class="icon-error text-red-600 text-xl"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 card p-5 bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-lg shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <p class="font-bold text-gray-700 dark:text-white">Lưu lượng thực thi (7 ngày qua)</p>
                </div>
                {{-- <x-admin::charts.line
                    :labels="['January', 'February', 'March']" 
                    :datasets="$datasets"
                /> --}}
            </div>

            <div class="card bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-lg shadow-sm">
                <div class="p-4 border-b dark:border-gray-800 flex justify-between items-center">
                    <p class="font-bold text-gray-700 dark:text-white">Hoạt động gần nhất</p>
                    <a href="#" class="text-xs text-blue-600 hover:underline">Xem tất cả</a>
                </div>
                
                <div class="divide-y dark:divide-gray-800">
                    <div class="p-4 flex gap-3 items-center hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        <div class="w-2 h-2 rounded-full bg-green-500"></div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800 dark:text-white">Gửi Email chào mừng</p>
                            <p class="text-xs text-gray-500">Cho Lead: "Nguyễn Văn A" • 2 phút trước</p>
                        </div>
                        <span class="icon-arrow-right text-gray-400"></span>
                    </div>

                    <div class="p-4 flex gap-3 items-center hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        <div class="w-2 h-2 rounded-full bg-green-500"></div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800 dark:text-white">Tự động gắn Tag "VIP"</p>
                            <p class="text-xs text-gray-500">Tiến trình hoàn tất • 15 phút trước</p>
                        </div>
                        <span class="icon-arrow-right text-gray-400"></span>
                    </div>

                    <div class="p-4 flex gap-3 items-center hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        <div class="w-2 h-2 rounded-full bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.5)]"></div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800 dark:text-white">Đồng bộ Webhook</p>
                            <p class="text-xs text-red-500 italic">Thất bại: Sai định dạng JSON • 1 giờ trước</p>
                        </div>
                        <span class="icon-arrow-right text-gray-400"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 card bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-lg shadow-sm">
            <div class="p-4 border-b dark:border-gray-800">
                <p class="font-bold text-gray-700 dark:text-white">Top Workflow hiệu quả</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-300">
                        <tr>
                            <th class="p-4 font-semibold">Tên Workflow</th>
                            <th class="p-4 font-semibold">Sự kiện kích hoạt</th>
                            <th class="p-4 font-semibold">Lượt chạy</th>
                            <th class="p-4 font-semibold">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-gray-800">
                        <tr>
                            <td class="p-4 dark:text-white">Auto Assign Lead</td>
                            <td class="p-4 text-gray-500">Lead Created</td>
                            <td class="p-4 dark:text-white font-medium">450</td>
                            <td class="p-4">
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold">ACTIVE</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-4 dark:text-white">Lost Lead Notification</td>
                            <td class="p-4 text-gray-500">Stage Updated</td>
                            <td class="p-4 dark:text-white font-medium">128</td>
                            <td class="p-4">
                                <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-bold">PAUSED</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
</x-admin::layouts>
