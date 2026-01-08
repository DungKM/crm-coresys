<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.social-message.index.title')
    </x-slot>

    <v-social-message>
        <div class="flex flex-col gap-6">
            {{-- Header --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex flex-col gap-1">
                        <div class="text-xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                            @lang('admin::app.social-message.index.title')
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Chọn nền tảng để vào màn hình chat
                        </div>
                    </div>

                    <div class="hidden sm:flex items-center gap-2">
                        <span class="rounded-full bg-gray-50 px-3 py-1 text-xs font-semibold text-gray-600 ring-1 ring-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:ring-gray-700">
                            Tổng quan
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-4">
                @php
                    $unread = [
                        'facebook'  => 12,
                        'zalo_oa'   => 3,
                        'instagram' => 0,
                        'whatsapp'  => 7,
                        'tiktok'    => 7,
                        'telegram'  => 7,
                        'linkedin'  => 7,
                        'SMS'       => 7,
                    ];

                    $platforms = [
                        [
                            'key' => 'facebook',
                            'name' => 'Facebook',
                            'icon' => 'messenger.png',
                            'desc' => 'Messenger',
                            'url'  => route('admin.facebook.index'),
                        ],
                        [
                            'key' => 'zalo_oa',
                            'name' => 'Zalo OA',
                            'icon' => 'zalo.png',
                            'desc' => 'Official Account',
                        ],
                        [
                            'key' => 'instagram',
                            'name' => 'Instagram',
                            'icon' => 'instagram.png',
                            'desc' => 'Direct',
                            'url'  => route('admin.instagram.index'),
                        ],
                        [
                            'key' => 'whatsapp',
                            'name' => 'WhatsApp',
                            'icon' => 'whatsapp.png',
                            'desc' => 'Business',
                        ],
                        [
                            'key' => 'tiktok',
                            'name' => 'TikTok',
                            'icon' => 'tiktok.jpg',
                            'desc' => 'Business',
                        ],
                        [
                            'key' => 'telegram',
                            'name' => 'Telegram',
                            'icon' => 'telegram.jpg',
                            'desc' => 'Business',
                        ],
                        [
                            'key' => 'linkedin',
                            'name' => 'LinkedIn',
                            'icon' => 'inkedin.png',
                            'desc' => 'Business',
                        ],
                        [
                            'key' => 'SMS',
                            'name' => 'SMS',
                            'icon' => 'sms.png',
                            'desc' => 'Business',
                        ],
                    ];
                @endphp

                @foreach ($platforms as $p)
                    @php $count = $unread[$p['key']] ?? 0; @endphp

                    <a href="{{ $p['url'] ?? '#' }}"
                       class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-4
                              shadow-[0_10px_30px_rgba(15,23,42,0.06)]
                              transition
                              hover:-translate-y-0.5 hover:border-gray-300
                              hover:shadow-[0_16px_40px_rgba(15,23,42,0.10)]
                              dark:border-gray-800 dark:bg-gray-900 dark:hover:border-gray-700">

                        {{-- subtle glow --}}
                        <span class="pointer-events-none absolute -top-10 left-1/2 h-28 w-28 -translate-x-1/2 rounded-full bg-indigo-100 blur-3xl opacity-0 transition group-hover:opacity-60 dark:bg-indigo-500/20"></span>

                        {{-- Badge unread --}}
                        @if ($count > 0)
                            <span
                                class="absolute right-3 top-3 inline-flex min-w-[26px] items-center justify-center
                                       rounded-full bg-red-600 px-2 py-1 text-[11px] font-extrabold text-white
                                       shadow-[0_10px_20px_rgba(220,38,38,0.25)]">
                                {{ $count > 99 ? '99+' : $count }}
                            </span>
                        @endif

                        <div class="flex h-full flex-col items-center justify-center gap-3 text-center">
                            {{-- Icon box --}}
                            <div class="grid h-16 w-16 place-items-center rounded-2xl bg-gray-50 ring-1 ring-gray-100
                                        transition group-hover:scale-[1.03]
                                        dark:bg-gray-800 dark:ring-gray-700">
                                <img class="h-10 w-10 object-contain"
                                     src="{{ asset('admin/images/social/' . $p['icon']) }}"
                                     alt="{{ $p['name'] }}">
                            </div>

                            <div class="text-base font-extrabold text-gray-900 dark:text-white">
                                {{ $p['name'] }}
                            </div>

                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                {{ $p['desc'] }}
                            </div>

                            {{-- Status line --}}
                            @if ($count > 0)
                                <div class="inline-flex items-center gap-2 rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-600 ring-1 ring-red-100 dark:bg-red-500/10 dark:text-red-300 dark:ring-red-500/20">
                                    <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                    {{ $count }} tin nhắn mới
                                </div>
                            @else
                                <div class="inline-flex items-center gap-2 rounded-full bg-gray-50 px-3 py-1 text-xs font-semibold text-gray-500 ring-1 ring-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                    Không có tin mới
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </v-social-message>
</x-admin::layouts>
