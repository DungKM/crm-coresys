<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.social-message.index.title')
    </x-slot>

    <v-social-message>
        <div class="flex flex-col gap-4">
            <!-- Header -->
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    <div class="text-xl font-bold dark:text-white">
                        @lang('admin::app.social-message.index.title')
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        Chọn nền tảng để vào màn hình chat
                    </div>
                </div>
            </div>

            <!-- Platforms grid -->
            <!-- Platforms grid (square cards) -->
<div class="grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-4">
    @php
        $unread = [
            'facebook'  => 12,
            'zalo_oa'   => 3,
            'instagram' => 0,
            'whatsapp'  => 7,
            'tiktok'  => 7,
            'telegram'  => 7,
            'linkedin'  => 7,
            'SMS'  => 7,
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

        <a href="{{$p['url'] ?? '#'}}"
           class="relative aspect-square rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition
                  hover:-translate-y-0.5 hover:shadow-md
                  dark:border-gray-800 dark:bg-gray-900">

            <!-- Badge đỏ -->
            @if ($count > 0)
                <span
                    class="absolute right-3 top-3 inline-flex min-w-[22px] items-center justify-center
                           rounded-full bg-red-600 px-1.5 py-0.5 text-[11px] font-semibold text-white">
                    {{ $count > 99 ? '99+' : $count }}
                </span>
            @endif

            <!-- Nội dung căn giữa -->
            <div class="flex h-full flex-col items-center justify-center gap-3 text-center">
                <!-- Icon -->
                <div
                    class="flex h-14 w-20 items-center justify-center rounded-xl
                           text-lg font-bold text-gray-700">
                    <img src="{{ asset('admin/images/social/' . $p['icon']) }}" alt="{{ $p['name'] }}" >
                </div>

                <!-- Tên platform -->
                <div class="text-base font-semibold text-gray-900 dark:text-white">
                    {{ $p['name'] }}
                </div>

                <!-- Sub text -->
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $p['desc'] }}
                </div>

                <!-- Trạng thái -->
                @if ($count > 0)
                    <div class="text-xs font-medium text-red-600 dark:text-red-400">
                        {{ $count }} tin nhắn mới
                    </div>
                @else
                    <div class="text-xs text-gray-400">
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
