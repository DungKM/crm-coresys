<x-admin::layouts.anonymous>
    <x-slot:title>
        @lang('admin::app.users.login.title')
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white dark:from-gray-950 dark:to-gray-950">
        <div class="mx-auto grid min-h-screen max-w-screen-2xl grid-cols-1 lg:grid-cols-2">
            <!-- LEFT: Banner -->
            <div class="relative hidden overflow-hidden lg:block">
                <!-- Background image (đổi link nếu muốn) -->
                <div
                    class="absolute inset-0 bg-cover bg-center"
                    style="background-image:url('https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1800&q=80')">
                </div>

                <!-- Overlay -->
                <div class="absolute inset-0 bg-gradient-to-br from-black/75 via-black/35 to-brandColor/30"></div>

                <!-- Decorative glow -->
                <div class="absolute -left-32 -top-32 h-96 w-96 rounded-full bg-brandColor/25 blur-3xl"></div>
                <div class="absolute -right-28 bottom-0 h-96 w-96 rounded-full bg-white/10 blur-3xl"></div>

                <div class="relative flex h-full flex-col justify-between p-14 text-white">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl bg-white ring-1 ring-white/30"><img src="{{ vite()->asset('images/logo.svg') }}" ></div>
                        <div>
                            <div class="text-2xl font-semibold tracking-wide">{{ config('app.name') }}</div>
                            <div class="text-sm text-white/80">Admin Portal</div>
                        </div>
                    </div>

                    <div class="max-w-xl">
                        <h1 class="text-5xl font-bold leading-tight tracking-tight">
                            Chào mừng quay lại 👋
                        </h1>

                        <p class="mt-5 text-base leading-7 text-white/85">
                            Đăng nhập để quản lý hệ thống, theo dõi dữ liệu và xử lý công việc trong một giao diện hiện đại.
                        </p>

                        <div class="mt-10 grid grid-cols-3 gap-4">
                            <div class="rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur">
                                <div class="text-sm font-semibold">Bảo mật</div>
                                <div class="mt-1 text-xs text-white/75">Phân quyền rõ ràng</div>
                            </div>
                            <div class="rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur">
                                <div class="text-sm font-semibold">Tối ưu</div>
                                <div class="mt-1 text-xs text-white/75">Hiệu năng ổn định</div>
                            </div>
                            <div class="rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur">
                                <div class="text-sm font-semibold">Trực quan</div>
                                <div class="mt-1 text-xs text-white/75">Dễ dùng, dễ nhìn</div>
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

            <!-- RIGHT: Login -->
            <div class="flex items-center justify-center p-6 lg:p-16">
                <div class="w-full max-w-2xl">
                    <!-- Mobile header -->
                    <div class="mb-10 flex items-center justify-center gap-4 lg:hidden">
                        <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-brandColor to-indigo-600"></div>
                        <div class="text-center">
                            <div class="text-xl font-semibold text-gray-900 dark:text-white">{{ config('app.name') }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Admin Login</div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-gray-200/70 bg-white shadow-2xl shadow-gray-200/50 dark:border-gray-800 dark:bg-gray-900 dark:shadow-none">
                        {!! view_render_event('admin.sessions.login.form_controls.before') !!}

                        <x-admin::form :action="route('admin.session.store')">
                            <!-- Header (FIX: dùng h1/div tránh gạch ngang) -->
                            <div class="px-10 pt-10">
                                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                                    @lang('admin::app.users.login.title')
                                </h1>

                                <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                    Vui lòng nhập thông tin để tiếp tục vào hệ thống.
                                </div>
                            </div>

                            <!-- Form body -->
                            <div class="mt-8 space-y-6 px-10 pb-10">
                                <!-- Email -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label
                                        class="required text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        @lang('admin::app.users.login.email')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="email"
                                        class="w-full rounded-2xl border-gray-200 bg-gray-50 px-4 py-3 text-base focus:border-brandColor focus:ring-brandColor dark:border-gray-800 dark:bg-gray-950/50"
                                        id="email"
                                        name="email"
                                        rules="required|email"
                                        :label="trans('admin::app.users.login.email')"
                                        :placeholder="trans('admin::app.users.login.email')" />

                                    <!-- giữ khoảng cách để không “nhảy” layout quá nhiều -->
                                    <div class="mt-2 min-h-[18px]">
                                        <x-admin::form.control-group.error control-name="email" />
                                    </div>
                                </x-admin::form.control-group>

                                <!-- Password (FIX: icon canh giữa chuẩn) -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label
                                        class="required text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        @lang('admin::app.users.login.password')
                                    </x-admin::form.control-group.label>

                                    <div class="relative">
                                        <x-admin::form.control-group.control
                                            type="password"
                                            class="w-full rounded-2xl border-gray-200 bg-gray-50 px-4 py-3 text-base ltr:pr-14 rtl:pl-14 focus:border-brandColor focus:ring-brandColor dark:border-gray-800 dark:bg-gray-950/50"
                                            id="password"
                                            name="password"
                                            rules="required|min:6"
                                            :label="trans('admin::app.users.login.password')"
                                            :placeholder="trans('admin::app.users.login.password')" />

                                        <button
                                            type="button"
                                            class="absolute top-1/2 -translate-y-1/2 rounded-xl p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-300 dark:hover:bg-gray-800 ltr:right-3 rtl:left-3"
                                            onclick="switchVisibility()"
                                            aria-label="Toggle password visibility"
                                        >
                                            <span id="visibilityIcon" class="icon-eye-hide text-2xl"></span>
                                        </button>
                                    </div>

                                    <div class="mt-2 min-h-[18px]">
                                        <x-admin::form.control-group.error control-name="password" />
                                    </div>
                                </x-admin::form.control-group>

                                <!-- Actions -->
                                <div class="flex items-center justify-between pt-2">
                                    <a class="text-sm font-semibold text-brandColor hover:underline"
                                       href="{{ route('admin.forgot_password.create') }}">
                                        @lang('admin::app.users.login.forget-password-link')
                                    </a>

                                    <button
                                        class="primary-button rounded-2xl px-8 py-3 text-base font-semibold"
                                        aria-label="{{ trans('admin::app.users.login.submit-btn') }}">
                                        @lang('admin::app.users.login.submit-btn')
                                    </button>
                                </div>
                            </div>
                        </x-admin::form>

                        {!! view_render_event('admin.sessions.login.form_controls.after') !!}
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

    @push('scripts')
        <script>
            function switchVisibility() {
                const passwordField = document.getElementById("password");
                const visibilityIcon = document.getElementById("visibilityIcon");

                passwordField.type = passwordField.type === "password" ? "text" : "password";

                // toggle icon class theo bộ icon của bạn
                visibilityIcon.classList.toggle("icon-eye");
                visibilityIcon.classList.toggle("icon-eye-hide");
            }
        </script>
    @endpush
</x-admin::layouts.anonymous>
