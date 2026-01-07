<x-admin::layouts.anonymous>
    <x-slot:title>
        @lang('admin::app.users.reset-password.title')
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white dark:from-gray-950 dark:to-gray-950">
        <div class="mx-auto grid min-h-screen max-w-screen-2xl grid-cols-1 lg:grid-cols-2">
            <!-- LEFT: Banner -->
            <div class="relative hidden overflow-hidden lg:block">
                <div
                    class="absolute inset-0 bg-cover bg-center"
                    style="background-image:url('https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1800&q=80')">
                </div>

                <div class="absolute inset-0 bg-gradient-to-br from-black/75 via-black/35 to-brandColor/30"></div>

                <div class="absolute -left-32 -top-32 h-96 w-96 rounded-full bg-brandColor/25 blur-3xl"></div>
                <div class="absolute -right-28 bottom-0 h-96 w-96 rounded-full bg-white/10 blur-3xl"></div>

                <div class="relative flex h-full flex-col justify-between p-14 text-white">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl bg-white ring-1 ring-black/10"><img src="{{ vite()->asset('images/logo.svg') }}" ></div>
                        <div>
                            <div class="text-2xl font-semibold tracking-wide">{{ config('app.name') }}</div>
                            <div class="text-sm text-white/80">Admin Portal</div>
                        </div>
                    </div>

                    <div class="max-w-xl">
                        <h1 class="text-5xl font-bold leading-tight tracking-tight">
                            Tạo mật khẩu mới
                        </h1>

                        <p class="mt-5 text-base leading-7 text-white/85">
                            Đặt lại mật khẩu để bảo vệ tài khoản và tiếp tục truy cập hệ thống.
                        </p>

                        <div class="mt-10 grid grid-cols-3 gap-4">
                            <div class="rounded-2xl bg-white p-4 ring-1 ring-black/10">
                                <div class="text-sm font-semibold text-gray-900">Mạnh</div>
                                <div class="mt-1 text-xs text-gray-600">Khuyến nghị ≥ 8 ký tự</div>
                            </div>
                            <div class="rounded-2xl bg-white p-4 ring-1 ring-black/10">
                                <div class="text-sm font-semibold text-gray-900">An toàn</div>
                                <div class="mt-1 text-xs text-gray-600">Không chia sẻ mật khẩu</div>
                            </div>
                            <div class="rounded-2xl bg-white p-4 ring-1 ring-black/10">
                                <div class="text-sm font-semibold text-gray-900">Nhanh</div>
                                <div class="mt-1 text-xs text-gray-600">Hoàn tất trong 1 phút</div>
                            </div>
                        </div>
                    </div>

                    <div class="text-sm text-white/75">
                        @lang('admin::app.components.layouts.powered-by.description', [
                            'krayin' => '<a class="text-white underline decoration-white/40 hover:decoration-white" href="https://krayincrm.com/">Krayin</a>',
                            'webkul' => '<a class="text-white underline decoration-white/40 hover:decoration-white" href="https://webkul.com/">Webkul</a>',
                        ])
                    </div>
                </div>
            </div>

            <!-- RIGHT: Form -->
            <div class="flex items-center justify-center p-6 lg:p-16">
                <div class="w-full max-w-2xl">
                    <!-- Mobile header -->
                    <div class="mb-10 flex items-center justify-center gap-4 lg:hidden">
                        <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-brandColor to-indigo-600"></div>
                        <div class="text-center">
                            <div class="text-xl font-semibold text-gray-900 dark:text-white">{{ config('app.name') }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Reset Password</div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-gray-200/70 bg-white shadow-2xl shadow-gray-200/50 dark:border-gray-800 dark:bg-gray-900 dark:shadow-none">
                        {!! view_render_event('admin.sessions.reset-password.form_controls.before') !!}

                        <x-admin::form :action="route('admin.reset_password.store')">
                            <!-- Header -->
                            <div class="px-10 pt-10">
                                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                                    @lang('admin::app.users.reset-password.title')
                                </h1>

                                <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                    Vui lòng nhập email và mật khẩu mới để hoàn tất đặt lại.
                                </div>
                            </div>

                            <!-- Token -->
                            <x-admin::form.control-group.control type="hidden" name="token" :value="$token" />

                            <!-- Body -->
                            <div class="mt-8 space-y-6 px-10 pb-10">
                                <!-- Email -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label
                                        class="required text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        @lang('admin::app.users.reset-password.email')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="email"
                                        class="w-full rounded-2xl border-gray-200 bg-gray-50 px-4 py-3 text-base focus:border-brandColor focus:ring-brandColor dark:border-gray-800 dark:bg-gray-950/50"
                                        id="email"
                                        name="email"
                                        rules="required|email"
                                        :label="trans('admin::app.users.reset-password.email')"
                                        :placeholder="trans('admin::app.users.reset-password.email')" />

                                    <!-- reserve space -->
                                    <div class="mt-2 min-h-[18px]">
                                        <x-admin::form.control-group.error control-name="email" />
                                    </div>
                                </x-admin::form.control-group>

                                <!-- Password -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label
                                        class="required text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        @lang('admin::app.users.reset-password.password')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="password"
                                        class="w-full rounded-2xl border-gray-200 bg-gray-50 px-4 py-3 text-base focus:border-brandColor focus:ring-brandColor dark:border-gray-800 dark:bg-gray-950/50"
                                        id="password"
                                        name="password"
                                        rules="required|min:6"
                                        :label="trans('admin::app.users.reset-password.password')"
                                        :placeholder="trans('admin::app.users.reset-password.password')"
                                        ref="password"
                                    />

                                    <div class="mt-2 min-h-[18px]">
                                        <x-admin::form.control-group.error control-name="password" />
                                    </div>
                                </x-admin::form.control-group>

                                <!-- Confirm Password -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label
                                        class="required text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        @lang('admin::app.users.reset-password.confirm-password')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="password"
                                        class="w-full rounded-2xl border-gray-200 bg-gray-50 px-4 py-3 text-base focus:border-brandColor focus:ring-brandColor dark:border-gray-800 dark:bg-gray-950/50"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        rules="confirmed:@password"
                                        :label="trans('admin::app.users.reset-password.confirm-password')"
                                        :placeholder="trans('admin::app.users.reset-password.confirm-password')"
                                        ref="password"
                                    />

                                    <div class="mt-2 min-h-[18px]">
                                        <x-admin::form.control-group.error control-name="password_confirmation" />
                                    </div>
                                </x-admin::form.control-group>

                                <div class="flex items-center justify-between pt-2">
                                    <a class="text-sm font-semibold text-brandColor hover:underline"
                                       href="{{ route('admin.session.create') }}">
                                        @lang('admin::app.users.reset-password.back-link-title')
                                    </a>

                                    <button class="primary-button rounded-2xl px-8 py-3 text-base font-semibold">
                                        @lang('admin::app.users.reset-password.submit-btn')
                                    </button>
                                </div>
                            </div>
                        </x-admin::form>

                        {!! view_render_event('admin.sessions.reset-password.form_controls.after') !!}
                    </div>

                    <!-- Mobile footer -->
                    <div class="mt-8 text-center text-xs text-gray-500 dark:text-gray-400 lg:hidden">
                        @lang('admin::app.components.layouts.powered-by.description', [
                            'krayin' => '<a class="text-brandColor hover:underline" href="https://krayincrm.com/">Krayin</a>',
                            'webkul' => '<a class="text-brandColor hover:underline" href="https://webkul.com/">Webkul</a>',
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin::layouts.anonymous>
