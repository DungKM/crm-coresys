<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.workflows-automation.content-library.title')
    </x-slot>

    <v-contentlibrary>
        <div class="flex flex-col gap-6">
            {{-- Header --}}
              <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    <x-admin::breadcrumbs name="workflow.contentlibrary" />
                    <div class="text-xl font-bold dark:text-white">
                        @lang('admin::app.workflows-automation.content-library.title')
                    </div>
                </div>
                <div class="flex items-center gap-x-2.5">
                    <div class="flex items-center gap-x-2.5">
                    </div>
                </div>
            </div>
            {{-- <div class="flex items-start justify-between gap-4">
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-x-2">
                        <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                            Thư Viện Nội Dung
                        </h1>
                        <span class="label-active">Library</span>
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
                        Thêm nội dung
                    </button>
                </div>
            </div> --}}

            {{-- Toolbar --}}
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-[0_10px_30px_rgba(15,23,42,0.06)] dark:border-gray-800 dark:bg-gray-900">
                <div class="border-b border-gray-200 px-5 py-5 dark:border-gray-800">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex flex-1 flex-col gap-3">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Sử dụng AI để soạn nội dung marketing hoặc nhập liệu hàng loạt từ file Excel.
                            </p>

                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                {{-- Search --}}
                                <div class="relative w-full sm:max-w-xl">
                                    <i class="icon-search absolute left-3 top-2.5 text-xl text-gray-400"></i>
                                    <input
                                        type="text"
                                        class="h-11 w-full rounded-xl border border-gray-200 bg-white py-2 pl-10 pr-3 text-sm outline-none
                                               focus:border-blue-500 focus:ring-4 focus:ring-blue-100
                                               dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:ring-blue-500/20"
                                        placeholder="Ví dụ: 'Chương trình khuyến mãi hè rực rỡ'..."
                                    >
                                </div>

                                {{-- Type select --}}
                                <select
                                    class="h-11 w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm outline-none
                                           focus:border-blue-500 focus:ring-4 focus:ring-blue-100
                                           dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:ring-blue-500/20 sm:w-52"
                                >
                                    <option>Email Content</option>
                                    <option>Facebook Post</option>
                                    <option>TikTok Script</option>
                                    <option>Landing Page</option>
                                </select>

                                {{-- AI --}}
                                <button type="button" class="primary-button whitespace-nowrap">
                                    <i class="icon-flash text-xl"></i>
                                    Soạn bằng AI
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 lg:pl-4">
                            <button type="button" class="secondary-button whitespace-nowrap">
                                <i class="icon-export text-xl"></i>
                                Import Excel
                            </button>

                            <button type="button" class="secondary-button whitespace-nowrap">
                                <i class="icon-plus text-xl"></i>
                                Nhập thủ công
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Filter row --}}
                <div class="flex items-center justify-between px-5 py-4">
                    <div class="flex items-center gap-x-2">
                        <button class="secondary-button text-sm">
                            <i class="icon-filter text-lg"></i>
                            Bộ lọc
                        </button>

                        <span class="rounded-full bg-gray-50 px-3 py-1 text-xs font-semibold text-gray-600 ring-1 ring-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:ring-gray-700">
                            12 nội dung
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <button class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-700
                                       shadow-sm hover:bg-gray-50
                                       dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">
                            Mới nhất
                        </button>
                    </div>
                </div>
            </div>

            {{-- Cards --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                {{-- Card --}}
                <div class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-4
                            shadow-[0_10px_30px_rgba(15,23,42,0.06)]
                            transition hover:-translate-y-0.5 hover:shadow-[0_16px_40px_rgba(15,23,42,0.10)]
                            dark:border-gray-800 dark:bg-gray-900">
                    {{-- glow --}}
                    <span class="pointer-events-none absolute -top-10 left-1/2 h-28 w-28 -translate-x-1/2 rounded-full bg-indigo-100 blur-3xl opacity-0 transition group-hover:opacity-60 dark:bg-indigo-500/20"></span>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="rounded-full bg-gray-50 px-3 py-1 text-[11px] font-extrabold uppercase tracking-wider text-gray-600 ring-1 ring-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:ring-gray-700">
                                Email Body
                            </span>
                            <span class="h-2 w-2 rounded-full bg-gray-300"></span>
                        </div>

                        <span class="text-xs font-semibold text-gray-400">#001</span>
                    </div>

                    <h3 class="mt-3 line-clamp-2 text-base font-extrabold text-gray-900 dark:text-white">
                        Chào đón iPhone 15 Pro Max - Đỉnh cao công nghệ mới!
                    </h3>

                    <div class="mt-3 rounded-2xl bg-gray-50 p-4 text-sm leading-6 text-gray-600 ring-1 ring-gray-100 dark:bg-gray-800/60 dark:text-gray-300 dark:ring-gray-700">
                        Khám phá ngay iPhone 15 Pro Max với thiết kế Titanium siêu bền và chip A17 Pro cực mạnh...
                    </div>

                    <div class="mt-3 text-xs font-medium text-gray-400">
                        #iphone15 #apple #khuyếnmãi #côngnghệ
                    </div>

                    <div class="mt-4 flex items-center justify-end gap-2">
                        <button class="grid h-10 w-10 place-items-center rounded-xl border border-gray-200 bg-white text-gray-700
                                       shadow-sm hover:bg-gray-50
                                       dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800" title="Xem">
                            <i class="icon-eye text-xl"></i>
                        </button>
                        <button class="grid h-10 w-10 place-items-center rounded-xl border border-gray-200 bg-white text-gray-700
                                       shadow-sm hover:bg-red-50 hover:text-red-600
                                       dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-red-500/10 dark:hover:text-red-300" title="Xóa">
                            <i class="icon-delete text-xl"></i>
                        </button>
                    </div>
                </div>

                {{-- Card 2 --}}
                <div class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-4
                            shadow-[0_10px_30px_rgba(15,23,42,0.06)]
                            transition hover:-translate-y-0.5 hover:shadow-[0_16px_40px_rgba(15,23,42,0.10)]
                            dark:border-gray-800 dark:bg-gray-900">
                    <span class="pointer-events-none absolute -top-10 left-1/2 h-28 w-28 -translate-x-1/2 rounded-full bg-emerald-100 blur-3xl opacity-0 transition group-hover:opacity-60 dark:bg-emerald-500/15"></span>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="rounded-full bg-gray-50 px-3 py-1 text-[11px] font-extrabold uppercase tracking-wider text-gray-600 ring-1 ring-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:ring-gray-700">
                                Facebook Post
                            </span>
                            <span class="h-2 w-2 rounded-full bg-green-500"></span>
                        </div>

                        <span class="text-xs font-semibold text-gray-400">#002</span>
                    </div>

                    <h3 class="mt-3 line-clamp-2 text-base font-extrabold text-gray-900 dark:text-white">
                        Khuyến mãi Black Friday
                    </h3>

                    <div class="mt-3 rounded-2xl bg-gray-50 p-4 text-sm leading-6 text-gray-600 ring-1 ring-gray-100 dark:bg-gray-800/60 dark:text-gray-300 dark:ring-gray-700">
                        Săn deal hời cùng TechWorld! Giảm đến 50% tất cả mặt hàng.
                    </div>

                    <div class="mt-3 text-xs font-medium text-gray-400">
                        #sale #blackfriday
                    </div>

                    <div class="mt-4 flex items-center justify-end gap-2">
                        <button class="grid h-10 w-10 place-items-center rounded-xl border border-gray-200 bg-white text-gray-700
                                       shadow-sm hover:bg-gray-50
                                       dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800" title="Xem">
                            <i class="icon-eye text-xl"></i>
                        </button>
                        <button class="grid h-10 w-10 place-items-center rounded-xl border border-gray-200 bg-white text-gray-700
                                       shadow-sm hover:bg-red-50 hover:text-red-600
                                       dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-red-500/10 dark:hover:text-red-300" title="Xóa">
                            <i class="icon-delete text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </v-contentlibrary>
</x-admin::layouts>
