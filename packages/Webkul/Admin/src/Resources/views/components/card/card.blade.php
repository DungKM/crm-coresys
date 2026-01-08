@props([
    'title' => '',
    'value' => 0,
    'color' => 'bg-brandColor', // mặc định
])

<div class="relative overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
    <!-- Thanh màu phía trên -->
    <div class="h-1 {{ $color }}"></div>

    <!-- Nội dung -->
    <div class="p-4">
        <div class="text-sm text-gray-500 dark:text-gray-400">
            {{ $title }}
        </div>

        <div class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">
            {{ $value }}
        </div>
    </div>
</div>
