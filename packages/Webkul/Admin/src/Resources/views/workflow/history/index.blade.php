<x-admin::layouts>
    <x-slot:title>
        Lịch sử tự động hóa
    </x-slot>
    <v-history>
        <div class="flex flex-col gap-8">
              <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    <x-admin::breadcrumbs name="workflow.history" />
                    <div class="text-xl font-bold dark:text-white">
                        @lang('admin::app.workflows-automation.history.title')
                    </div>
                </div>
                <div class="flex items-center gap-x-2.5">
                    <div class="flex items-center gap-x-2.5">
                    </div>
                </div>
            </div>
            <div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="rounded-3xl border border-gray-100 bg-white p-5 shadow-[0_10px_30px_rgba(15,23,42,0.06)] dark:border-gray-800 dark:bg-gray-900">
                        <div class="flex items-start justify-between">
                            <div class="grid h-11 w-11 place-items-center rounded-2xl bg-blue-50 text-blue-600 ring-1 ring-blue-100 dark:bg-blue-500/10 dark:text-blue-300 dark:ring-blue-500/20">
                                <i class="icon-facebook-outline text-2xl"></i>
                            </div>

                            <span class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-gray-300">
                                Hạng #1
                            </span>
                        </div>

                        <div class="mt-4">
                            <div class="text-sm font-extrabold text-gray-900 dark:text-white">
                                TechWorld VN
                            </div>
                            <div class="mt-1 text-[10px] font-semibold uppercase tracking-wider text-gray-400">
                                1 nội dung đã đăng
                            </div>
                        </div>

                        <div class="mt-5 grid grid-cols-2 gap-3">
                            <div class="rounded-2xl bg-gray-50 p-4 ring-1 ring-gray-100 dark:bg-gray-800/60 dark:ring-gray-700">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                    T.tác tổng
                                </div>
                                <div class="mt-1 text-lg font-extrabold text-gray-900 dark:text-white">
                                    170
                                </div>
                            </div>

                            <div class="rounded-2xl bg-gray-50 p-4 ring-1 ring-gray-100 dark:bg-gray-800/60 dark:ring-gray-700">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                    Avg/post
                                </div>
                                <div class="mt-1 text-lg font-extrabold text-indigo-600 dark:text-indigo-300">
                                    170.0
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center justify-between border-t border-gray-100 pt-4 dark:border-gray-800">
                            <div class="flex items-center gap-2 text-xs font-semibold text-emerald-600 dark:text-emerald-300">
                                <i class="icon-arrow-up text-sm"></i>
                                Top: +170
                            </div>

                            <a href="#" class="text-xs font-extrabold uppercase tracking-widest text-indigo-600 hover:underline dark:text-indigo-300">
                                Chi tiết →
                            </a>
                        </div>
                    </div>
                    <div class="rounded-3xl border border-gray-100 bg-white p-5 shadow-[0_10px_30px_rgba(15,23,42,0.06)] dark:border-gray-800 dark:bg-gray-900">
                        <div class="flex items-start justify-between">
                            <div class="grid h-11 w-11 place-items-center rounded-2xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-500/20">
                                <i class="icon-mail text-2xl"></i>
                            </div>

                            <span class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-gray-300">
                                Hạng #2
                            </span>
                        </div>

                        <div class="mt-4">
                            <div class="text-sm font-extrabold text-gray-900 dark:text-white">
                                customer_alpha@gmail.com
                            </div>
                            <div class="mt-1 text-[10px] font-semibold uppercase tracking-wider text-gray-400">
                                1 nội dung đã đăng
                            </div>
                        </div>

                        <div class="mt-5 grid grid-cols-2 gap-3">
                            <div class="rounded-2xl bg-gray-50 p-4 ring-1 ring-gray-100 dark:bg-gray-800/60 dark:ring-gray-700">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                    T.tác tổng
                                </div>
                                <div class="mt-1 text-lg font-extrabold text-gray-900 dark:text-white">
                                    3
                                </div>
                            </div>

                            <div class="rounded-2xl bg-gray-50 p-4 ring-1 ring-gray-100 dark:bg-gray-800/60 dark:ring-gray-700">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                    Avg/post
                                </div>
                                <div class="mt-1 text-lg font-extrabold text-indigo-600 dark:text-indigo-300">
                                    3.0
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center justify-between border-t border-gray-100 pt-4 dark:border-gray-800">
                            <div class="flex items-center gap-2 text-xs font-semibold text-emerald-600 dark:text-emerald-300">
                                <i class="icon-arrow-up text-sm"></i>
                                Top: +3
                            </div>

                            <a href="#" class="text-xs font-extrabold uppercase tracking-widest text-indigo-600 hover:underline dark:text-indigo-300">
                                Chi tiết →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="mb-4 flex items-center gap-2">
                    <i class="icon-activity text-xl text-indigo-600 dark:text-indigo-300"></i>
                    <h2 class="text-lg font-extrabold text-gray-900 dark:text-white">
                        Bảng xếp hạng Nội dung Viral
                    </h2>
                </div>

                <div class="flex flex-col gap-4">
                    {{-- Row #1 --}}
                    <div class="rounded-3xl border border-gray-100 bg-white p-5 shadow-[0_10px_30px_rgba(15,23,42,0.06)] dark:border-gray-800 dark:bg-gray-900">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4 min-w-0">
                               <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-600
                                        shadow-[0_10px_25px_rgba(79,70,229,0.25)]"
                                >
                                    <span class="text-sm font-extrabold leading-none !text-white">#1</span>
                                </div>

                                <div class="min-w-0">
                                    <div class="text-xs font-extrabold uppercase tracking-widest text-indigo-600 dark:text-indigo-300">
                                        AUTO_FB_DAILY
                                    </div>
                                    <div class="mt-1 truncate text-sm font-semibold text-gray-900 dark:text-white">
                                        Từ: TechWorld VN
                                    </div>
                                </div>
                            </div>

                            <div class="text-right">
                                <div class="text-lg font-extrabold text-gray-900 dark:text-white">
                                    +170
                                </div>
                                <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                    Tương tác
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-3xl border border-gray-100 bg-white p-5 shadow-[0_10px_30px_rgba(15,23,42,0.06)] dark:border-gray-800 dark:bg-gray-900">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4 min-w-0">
                                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-600
                                        shadow-[0_10px_25px_rgba(79,70,229,0.25)]"
                                >
                                <span class="text-sm font-extrabold leading-none !text-white">#2</span>
                                </div>

                                <div class="min-w-0">
                                    <div class="text-xs font-extrabold uppercase tracking-widest text-indigo-600 dark:text-indigo-300">
                                        AUTO_EMAIL_WELCOME
                                    </div>
                                    <div class="mt-1 truncate text-sm font-semibold text-gray-900 dark:text-white">
                                        Từ: customer_alpha@gmail.com
                                    </div>
                                </div>
                            </div>

                            <div class="text-right">
                                <div class="text-lg font-extrabold text-gray-900 dark:text-white">
                                    +3
                                </div>
                                <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                    Tương tác
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </v-history>
</x-admin::layouts>
