<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.workflows-automation.connect-key.title')
    </x-slot>

    <v-connectkey>
        
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
          <div class="flex flex-col gap-2">
              <x-admin::breadcrumbs name="workflow.connectkey" />
              <div class="text-xl font-bold dark:text-white">
                  @lang('admin::app.workflows-automation.connect-key.title')
              </div>
          </div>
          <div class="flex items-center gap-x-2.5">
              <div class="flex items-center gap-x-2.5">
              </div>
          </div>
        </div>
        <div class="mx-auto w-full max-w-6xl px-4 py-10">
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                {{-- Card 1 --}}
                <div class="group rounded-[34px] border border-gray-100 bg-white p-10 shadow-[0_10px_30px_rgba(15,23,42,0.06)] transition hover:-translate-y-0.5 hover:shadow-[0_14px_38px_rgba(15,23,42,0.08)] dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex flex-col items-center text-center">
                        {{-- icon bubble --}}
                        <div class="grid h-20 w-20 place-items-center rounded-full bg-gray-50 ring-1 ring-gray-100 dark:bg-gray-800 dark:ring-gray-700">
                            <i class="icon-facebook-outline text-3xl text-blue-600 dark:text-blue-400"></i>
                        </div>

                        <h3 class="mt-7 text-lg font-extrabold text-gray-900 dark:text-white">
                            FB Page - TechWorld VN
                        </h3>

                        <p class="mt-1 text-[11px] font-semibold uppercase tracking-wider text-gray-400">
                            1829384756 (Page ID)
                        </p>

                        {{-- status pill --}}
                        <span class="mt-6 inline-flex items-center gap-2 rounded-full bg-emerald-50 px-5 py-2 text-[11px] font-extrabold uppercase tracking-[0.18em] text-emerald-700 ring-1 ring-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-500/20">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            Online
                        </span>

                        {{-- button --}}
                        <button
                            type="button"
                            class="mt-8 inline-flex h-11 w-full max-w-xs items-center justify-center rounded-full bg-gray-900 px-6 text-sm font-semibold text-white shadow-sm transition hover:bg-black dark:bg-white dark:text-gray-900"
                        >
                            Cấu hình n8n Webhook
                        </button>
                    </div>
                </div>

                {{-- Card 2 --}}
                <div class="group rounded-[34px] border border-gray-100 bg-white p-10 shadow-[0_10px_30px_rgba(15,23,42,0.06)] transition hover:-translate-y-0.5 hover:shadow-[0_14px_38px_rgba(15,23,42,0.08)] dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex flex-col items-center text-center">
                        <div class="grid h-20 w-20 place-items-center rounded-full bg-gray-50 ring-1 ring-gray-100 dark:bg-gray-800 dark:ring-gray-700">
                            <i class="icon-mail text-3xl text-gray-700 dark:text-gray-200"></i>
                        </div>

                        <h3 class="mt-7 text-lg font-extrabold text-gray-900 dark:text-white">
                            Giao dịch Email<br />(SendGrid)
                        </h3>

                        <p class="mt-1 text-[11px] font-semibold uppercase tracking-wider text-gray-400">
                            smtp.sendgrid.net
                        </p>

                        <span class="mt-6 inline-flex items-center gap-2 rounded-full bg-emerald-50 px-5 py-2 text-[11px] font-extrabold uppercase tracking-[0.18em] text-emerald-700 ring-1 ring-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-500/20">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            Online
                        </span>

                        <button
                            type="button"
                            class="mt-8 inline-flex h-11 w-full max-w-xs items-center justify-center rounded-full bg-gray-900 px-6 text-sm font-semibold text-white shadow-sm transition hover:bg-black dark:bg-white dark:text-gray-900"
                        >
                            Cấu hình n8n Webhook
                        </button>
                    </div>
                </div>

                {{-- Create new --}}
                <button
                    type="button"
                    class="group relative flex min-h-[420px] flex-col items-center justify-center rounded-[34px] border-2 border-dashed border-indigo-200 bg-white/60 p-10 text-center shadow-[0_10px_30px_rgba(15,23,42,0.06)] transition hover:-translate-y-0.5 hover:border-indigo-300 hover:shadow-[0_14px_38px_rgba(15,23,42,0.08)] dark:border-indigo-500/30 dark:bg-gray-900/40"
                >
                    <span class="pointer-events-none absolute top-1/2 h-24 w-24 -translate-y-[180px] rounded-full bg-indigo-100 blur-2xl opacity-60 dark:bg-indigo-500/20"></span>
                    <div
                        class="grid h-20 w-20 place-items-center rounded-full
                            bg-white
                            shadow-[0_10px_25px_rgba(15,23,42,0.15)]
                            ring-1 ring-gray-100
                            dark:bg-gray-900 dark:ring-gray-700">
                        <i class="icon-add text-3xl text-indigo-600 dark:text-indigo-300"></i>
                    </div>

                    <div class="mt-10 text-sm font-extrabold uppercase tracking-[0.25em] text-indigo-600 dark:text-indigo-300">
                        Tạo kết nối mới
                    </div>
                </button>
            </div>
        </div>
    </v-connectkey>
</x-admin::layouts>
