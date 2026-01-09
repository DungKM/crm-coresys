<div class="flex flex-col gap-1 p-4">
    <div class="flex flex-col gap-4">
        <!-- Gender -->
        <div class="grid grid-cols-[1fr_2fr] items-center gap-2">
            <div class="flex items-center gap-2 label dark:text-white">
                <span class="icon-settings-user text-2xl"></span>
                @lang('Giới tính')
            </div>
            <div class="font-medium dark:text-white">
                @if ($person->gender == 'male')
                    @lang('Nam')
                @elseif ($person->gender == 'female')
                    @lang('Nữ')
                @elseif ($person->gender == 'other')
                    @lang('Khác')
                @else
                    {{ $person->gender }}
                @endif
            </div>
        </div>

        <!-- Date of Birth -->
        <div class="grid grid-cols-[1fr_2fr] items-center gap-2">
            <div class="flex items-center gap-2 label dark:text-white">
                <span class="icon-calendar text-2xl"></span>
                @lang('Ngày sinh')
            </div>
            <div class="font-medium dark:text-white">
                {{ $person->date_of_birth }}
            </div>
        </div>

        <!-- Address -->
        <div class="grid grid-cols-[1fr_2fr] items-center gap-2">
            <div class="flex items-center gap-2 label dark:text-white">
                <span class="icon-location text-2xl"></span>
                @lang('Địa chỉ')
            </div>
            <div class="font-medium dark:text-white">
                {{ $person->address }}
            </div>
        </div>

        <!-- Occupation -->
        <div class="grid grid-cols-[1fr_2fr] items-center gap-2">
            <div class="flex items-center gap-2 label dark:text-white">
                <span class="icon-role text-2xl"></span>
                @lang('Nghề nghiệp')
            </div>
            <div class="font-medium dark:text-white">
                {{ $person->occupation }}
            </div>
        </div>

        <!-- Income -->
        <div class="grid grid-cols-[1fr_2fr] items-center gap-2">
            <div class="flex items-center gap-2 label dark:text-white">
                <span class="icon-dollar text-2xl"></span>
                @lang('Thu nhập cá nhân')
            </div>
            <div class="font-medium dark:text-white">
                {{ number_format($person->income ?? 0, 0, ',', '.') }}
            </div>
        </div>

        <!-- Hobbies -->
        <div class="grid grid-cols-[1fr_2fr] items-center gap-2">
            <div class="flex items-center gap-2 label dark:text-white">
                <span class="icon-activity text-2xl"></span>
                @lang('Sở thích')
            </div>
            <div class="font-medium dark:text-white">
                {{ $person->hobbies }}
            </div>
        </div>

        <!-- Habits and Behaviors -->
        <div class="grid grid-cols-[1fr_2fr] items-center gap-2">
            <div class="flex items-center gap-2 label dark:text-white">
                <span class="icon-settings-flow text-2xl"></span>
                @lang('Thói quen và hành vi')
            </div>
            <div class="font-medium dark:text-white">
                {{ $person->habits_and_behaviors }}
            </div>
        </div>

        <!-- Needs and Pain Points -->
        <div class="grid grid-cols-[1fr_2fr] items-center gap-2">
            <div class="flex items-center gap-2 label dark:text-white">
                <span class="icon-warning text-2xl"></span>
                @lang('Nhu cầu và vấn đề')
            </div>
            <div class="font-medium dark:text-white">
                {{ $person->needs_and_pain_points }}
            </div>
        </div>
    </div>
</div>