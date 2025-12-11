<x-admin::layouts>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('Chi tiết dữ liệu khách hàng')
    </x-slot>

    {{-- Page Header --}}
    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <div class="flex items-center gap-2.5">
            {{-- Back Button --}}
            <a href="{{ route('admin.customer-data.index') }}"
               class="icon-sort-left grid h-[39px] w-[39px] cursor-pointer place-content-center rounded-md border border-gray-200 bg-white text-2xl transition-all hover:bg-gray-100 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-950">
            </a>

            <div class="grid gap-1.5">
                <p class="text-xl font-bold leading-6 text-gray-800 dark:text-white">
                    {{ $customerData->name }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-x-2.5">
            {{-- Send Verification Email --}}
            @if($customerData->status == 'pending')
                <form action="{{ route('admin.customer-data.send-verification', $customerData->id) }}"
                    method="POST"
                    style="display:inline;"
                    onsubmit="return confirm('@lang('Bạn có chắc chắn muốn gửi email xác thực?')')">
                    @csrf
                    <button type="submit" class="secondary-button">
                        <span class="icon-mail text-xl"></span>
                        @lang('Gửi email xác thực')
                    </button>
                </form>
            @endif

            {{-- Kiểm tra đã convert chưa --}}
            @if($customerData->isConverted())
                {{-- Đã convert rồi → Hiện nút "Xem Lead" --}}
                <a href="{{ route('admin.leads.view', $customerData->converted_to_lead_id) }}" 
                class="primary-button">
                    <span class="icon-lead text-xl"></span>
                    @lang('Xem Lead') #{{ $customerData->converted_to_lead_id }}
                </a>
            @elseif($customerData->canConvert())
                {{-- Có thể convert → Hiện nút "Chuyển thành Lead" --}}
                <form action="{{ route('admin.customer-data.convert-to-lead', $customerData->id) }}" 
                    method="POST" 
                    style="display:inline"
                    onsubmit="return confirm('Bạn có chắc chắn muốn chuyển thành Lead?')">
                    @csrf
                    <button type="submit" class="primary-button">
                        <span class="icon-lead text-xl"></span>
                        @lang('Chuyển thành Lead')
                    </button>
                </form>
            @endif

            {{-- Edit - Chỉ hiện khi chưa convert --}}
            @if(!$customerData->isConverted())
                <a href="{{ route('admin.customer-data.edit', $customerData->id) }}" 
                class="secondary-button">
                    <span class="icon-edit text-xl"></span>
                    @lang('Chỉnh sửa')
                </a>
            @endif

            {{-- Delete --}}
            <form action="{{ route('admin.customer-data.destroy', $customerData->id) }}" 
                method="POST" 
                style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="transparent-button hover:bg-red-100 dark:hover:bg-red-900"
                        onclick="return confirm('@lang('Bạn có chắc chắn muốn xóa?')')">
                    <span class="icon-delete text-xl text-red-600"></span>
                    @lang('Xóa')
                </button>
            </form>
        </div>
    </div>

    {{-- Status Information --}}
    <div class="mt-3.5 box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
            @lang('Trạng thái')
        </p>

        <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-950">
            <div class="mb-3 flex items-center gap-3">
                <span class="text-sm font-medium text-gray-600 dark:text-gray-300" style="min-width: 150px;">
                    @lang('Trạng thái hiện tại'):
                </span>
                @if($customerData->status == 'pending')
                    <span class="label-warning text-base">@lang('Đang chờ xác thực')</span>
                @elseif($customerData->status == 'verified')
                    <span class="label-success text-base">@lang('Đã xác thực')</span>
                @elseif($customerData->status == 'spam')
                    <span class="label-danger text-base">@lang('Spam')</span>
                @elseif($customerData->status == 'converted')
                    <span class="label-info text-base">@lang('Đã chuyển thành Lead')</span>
                @endif
            </div>

            @if($customerData->verified_at)
                <div class="mb-3 flex items-center gap-3">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300" style="min-width: 150px;">
                        @lang('Xác thực lúc'):
                    </span>
                    <span class="text-sm text-gray-600 dark:text-gray-300">
                        {{ $customerData->verified_at->format('d/m/Y H:i') }}
                    </span>
                </div>
            @endif

            @if($customerData->status == 'pending' && $customerData->verify_token_expires_at)
                <div class="mb-3 flex items-center gap-3">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300" style="min-width: 150px;">
                        @lang('Token hết hạn'):
                    </span>
                    <span class="text-sm text-gray-600 dark:text-gray-300">
                        {{ $customerData->verify_token_expires_at->format('d/m/Y H:i') }}
                    </span>
                    @if($customerData->verify_token_expires_at->isPast())
                        <span class="label-danger text-xs">@lang('Đã hết hạn')</span>
                    @endif
                </div>
            @endif

            @if($customerData->converted_to_lead_id)
                <div class="mb-3 flex items-center gap-3">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300" style="min-width: 150px;">
                        @lang('Lead ID'):
                    </span>
                    <a href="{{ route('admin.leads.view', $customerData->converted_to_lead_id) }}" 
                       class="text-sm text-blue-600 underline hover:text-blue-800 dark:text-blue-400">
                        #{{ $customerData->converted_to_lead_id }} - @lang('Xem Lead')
                    </a>
                </div>
            @endif

            @if($customerData->spam_reason)
                <div class="mt-4 rounded-lg border border-yellow-200 bg-yellow-50 p-3 dark:border-yellow-800 dark:bg-yellow-950">
                    <p class="mb-2 text-sm font-semibold text-yellow-800 dark:text-yellow-100">
                        @lang('Lý do đánh dấu spam'):
                    </p>
                    <p class="text-sm text-yellow-700 dark:text-yellow-200">
                        {{ $customerData->spam_reason }}
                    </p>
                </div>
            @endif
        </div>

        {{-- Actions based on status --}}
        @if($customerData->status == 'pending')
            <div class="mt-4 rounded-lg border border-yellow-200 bg-yellow-50 p-4 dark:border-yellow-800 dark:bg-yellow-950">
                <p class="mb-3 text-sm font-semibold text-yellow-800 dark:text-yellow-100">
                    @lang('Hành động khả dụng'):
                </p>
                
                <form action="{{ route('admin.customer-data.mark-spam', $customerData->id) }}" 
                      method="POST" 
                      class="flex gap-2">
                    @csrf
                    <input type="text" 
                           name="reason" 
                           placeholder="@lang('Lý do (tùy chọn)')"
                           class="min-w-[300px] rounded-lg border border-gray-200 px-3 py-2 text-sm dark:border-gray-800 dark:bg-gray-900">
                    <button type="submit" 
                            class="secondary-button bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900 dark:text-red-100"
                            onclick="return confirm('@lang('Đánh dấu là spam?')')">
                        @lang('Đánh dấu spam')
                    </button>
                </form>
            </div>
        @endif
    </div>

    {{-- General Information --}}
    <div class="mt-4 box-shadow rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="p-4">
            <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                @lang('Thông tin chung')
            </p>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <tbody>
                        <tr class="border-b border-gray-200 dark:border-gray-800">
                            <td class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300" style="width: 200px">
                                @lang('Họ và tên')
                            </td>
                            <td class="px-4 py-3 text-gray-800 dark:text-white">
                                {{ $customerData->name }}
                            </td>
                        </tr>
                        <tr class="border-b border-gray-200 dark:border-gray-800">
                            <td class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">
                                @lang('Email')
                            </td>
                            <td class="px-4 py-3">
                                <a href="mailto:{{ $customerData->email }}" 
                                   class="text-blue-600 hover:underline dark:text-blue-400">
                                    {{ $customerData->email }}
                                </a>
                            </td>
                        </tr>
                        <tr class="border-b border-gray-200 dark:border-gray-800">
                            <td class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">
                                @lang('Số điện thoại')
                            </td>
                            <td class="px-4 py-3">
                                @if($customerData->phone)
                                    <a href="tel:{{ $customerData->phone }}" 
                                       class="text-blue-600 hover:underline dark:text-blue-400">
                                        {{ $customerData->phone }}
                                    </a>
                                @else
                                    <span class="text-gray-400">@lang('Chưa có')</span>
                                @endif
                            </td>
                        </tr>
                        <tr class="border-b border-gray-200 dark:border-gray-800">
                            <td class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">
                                @lang('Loại khách hàng')
                            </td>
                            <td class="px-4 py-3">
                                @if($customerData->customer_type == 'business')
                                    <span class="label-info">@lang('Doanh nghiệp')</span>
                                @else
                                    <span class="label-default">@lang('Cá nhân')</span>
                                @endif
                            </td>
                        </tr>
                        <tr class="border-b border-gray-200 dark:border-gray-800">
                            <td class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">
                                @lang('Nguồn dữ liệu')
                            </td>
                            <td class="px-4 py-3">
                                @if($customerData->source)
                                    <span class="label-info">{{ ucfirst($customerData->source) }}</span>
                                @else
                                    <span class="text-gray-400">@lang('Không xác định')</span>
                                @endif
                            </td>
                        </tr>
                        <tr class="border-b border-gray-200 dark:border-gray-800">
                            <td class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">
                                @lang('Nội dung quan tâm')
                            </td>
                            <td class="px-4 py-3">
                                @if($customerData->title)
                                    <div class="whitespace-pre-wrap text-gray-800 dark:text-white">
                                        {{ $customerData->title }}
                                    </div>
                                @else
                                    <span class="text-gray-400">@lang('Không có')</span>
                                @endif
                            </td>
                        </tr>
                        <tr class="border-b border-gray-200 dark:border-gray-800">
                            <td class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">
                                @lang('Ngày tạo')
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                {{ $customerData->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">
                                @lang('Cập nhật cuối')
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                {{ $customerData->updated_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Metadata --}}
    @if($customerData->metadata && count($customerData->metadata) > 0)
        <div class="mt-4 box-shadow rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <div class="p-4">
                <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                    @lang('Thông tin bổ sung từ form')
                </p>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <tbody>
                            @foreach($customerData->metadata as $key => $value)
                                <tr class="border-b border-gray-200 last:border-0 dark:border-gray-800">
                                    <td class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300" style="width: 200px">
                                        {{ ucfirst(str_replace('_', ' ', $key)) }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                        @if(is_array($value))
                                            <pre class="rounded bg-gray-50 p-2 text-xs dark:bg-gray-950">{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        @else
                                            {{ $value }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- Verification Link --}}
    @if($customerData->status == 'pending' && $customerData->verify_token)
        <div class="mt-4 box-shadow rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <div class="p-4">
                <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                    @lang('Link xác thực')
                </p>

                <div class="rounded-lg bg-blue-50 p-4 dark:bg-blue-950">
                    <p class="mb-2 text-sm font-semibold text-blue-800 dark:text-blue-100">
                        @lang('Link xác thực'):
                    </p>
                    <input type="text" 
                           class="w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm dark:border-blue-800 dark:bg-gray-900" 
                           value="{{ $customerData->verify_url }}" 
                           readonly
                           onclick="this.select();">
                    <p class="mt-2 text-xs text-blue-600 dark:text-blue-300">
                        @lang('Copy link này và gửi cho khách hàng nếu cần')
                    </p>
                </div>
            </div>
        </div>
    @endif
</x-admin::layouts>