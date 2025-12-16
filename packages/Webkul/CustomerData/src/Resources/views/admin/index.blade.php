<x-admin::layouts>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('Dữ liệu khách hàng')
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <div class="grid gap-1.5">
            <p class="text-xl font-bold leading-6 text-gray-800 dark:text-white">
                @lang('Dữ liệu khách hàng')
            </p>
        </div>

        <div class="flex items-center gap-x-2.5">
            {{-- Create Button --}}
            <a href="{{ route('admin.customer-data.create') }}"
               class="primary-button">
                @lang('Thêm mới')
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="mt-3.5 flex items-center justify-between gap-4 max-md:flex-wrap">
        <form action="{{ route('admin.customer-data.index') }}" method="GET" class="flex gap-x-2.5 max-md:w-full max-md:flex-wrap">
            {{-- Search --}}
            <div class="flex w-full items-center gap-x-2 rounded-lg border border-gray-200 px-3.5 py-1.5 dark:border-gray-800">
                <label for="search" class="icon-search pointer-events-none text-2xl"></label>
                <input 
                    type="text" 
                    name="search" 
                    id="search"
                    class="w-full border-none bg-transparent text-sm font-normal text-gray-600 outline-none dark:text-gray-300" 
                    placeholder="@lang('Tìm kiếm...')"
                    value="{{ request('search') }}"
                />
            </div>

            {{-- Status Filter --}}
            <select name="status" 
                    class="min-w-[200px] rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-sm font-normal text-gray-600 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <option value="">@lang('Tất cả trạng thái')</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>@lang('Đang chờ')</option>
                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>@lang('Đã xác thực')</option>
                <option value="spam" {{ request('status') == 'spam' ? 'selected' : '' }}>@lang('Spam')</option>
                <option value="converted" {{ request('status') == 'converted' ? 'selected' : '' }}>@lang('Đã chuyển đổi')</option>
            </select>

            {{-- Customer Type Filter --}}
            <select name="customer_type"
                    class="min-w-[200px] rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-sm font-normal text-gray-600 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <option value="">@lang('Loại khách hàng')</option>
                <option value="retail" {{ request('customer_type') == 'retail' ? 'selected' : '' }}>@lang('Cá nhân')</option>
                <option value="business" {{ request('customer_type') == 'business' ? 'selected' : '' }}>@lang('Doanh nghiệp')</option>
            </select>

            {{-- Filter Buttons --}}
            <button type="submit" class="secondary-button">
                @lang('Lọc')
            </button>
            
            <a href="{{ route('admin.customer-data.index') }}" class="secondary-button">
                @lang('Reset')
            </a>
        </form>
    </div>

    {{-- Bulk Actions Form --}}
    <form action="{{ route('admin.customer-data.mass-action') }}" method="POST" id="mass-action-form">
        @csrf
        
        {{-- Bulk Action Dropdown --}}
        <div class="mt-4 flex items-center gap-2">
            <select name="action" class="rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-sm dark:border-gray-800 dark:bg-gray-900">
                <option value="">@lang('Chọn hành động')</option>
                <option value="send_verification">@lang('Gửi email xác thực')</option>
                <option value="mark_spam">@lang('Đánh dấu spam')</option>
                <option value="delete">@lang('Xóa')</option>
            </select>
            <button type="submit" class="secondary-button">
                @lang('Áp dụng')
            </button>
        </div>

        {{-- Table --}}
        <div class="mt-4 box-shadow rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <table class="w-full text-left">
                <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-4 font-medium text-gray-600 dark:text-gray-300">
                            <input type="checkbox" id="select-all" class="cursor-pointer rounded border-gray-300" onclick="toggleAll(this)">
                        </th>
                        <th class="px-6 py-4 font-medium text-gray-600 dark:text-gray-300">@lang('Tên')</th>
                        <th class="px-6 py-4 font-medium text-gray-600 dark:text-gray-300">@lang('Email')</th>
                        <th class="px-6 py-4 font-medium text-gray-600 dark:text-gray-300">@lang('Điện thoại')</th>
                        <th class="px-6 py-4 font-medium text-gray-600 dark:text-gray-300">@lang('Nguồn')</th>
                        <th class="px-6 py-4 font-medium text-gray-600 dark:text-gray-300">@lang('Loại')</th>
                        <th class="px-6 py-4 font-medium text-gray-600 dark:text-gray-300">@lang('Trạng thái')</th>
                        <th class="px-6 py-4 font-medium text-gray-600 dark:text-gray-300">@lang('Ngày tạo')</th>
                        <th class="px-6 py-4 font-medium text-gray-600 dark:text-gray-300">@lang('Hành động')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customerData as $data)
                        <tr class="border-b border-gray-200 transition-all hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-950">
                            <td class="px-6 py-4">
                                <input type="checkbox" name="ids[]" value="{{ $data->id }}" class="row-checkbox cursor-pointer rounded border-gray-300">
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                {{ $data->name }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                {{ $data->email }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                {{ $data->phone ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="rounded-md bg-gray-100 px-2 py-1 text-xs font-semibold dark:bg-gray-800">
                                    {{ $data->source ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($data->customer_type == 'business')
                                    <span class="label-info">@lang('DN')</span>
                                @else
                                    <span class="label-default">@lang('Lẻ')</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($data->status == 'pending')
                                    <span class="label-warning">@lang('Đang chờ')</span>
                                @elseif($data->status == 'verified')
                                    <span class="label-success">@lang('Đã xác thực')</span>
                                @elseif($data->status == 'spam')
                                    <span class="label-danger">@lang('Spam')</span>
                                @elseif($data->status == 'converted')
                                    <span class="label-info">@lang('Đã chuyển')</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                {{ $data->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-1.5 items-center">
                                    {{-- View --}}
                                    <a href="{{ route('admin.customer-data.show', $data->id) }}" 
                                    class="icon-eye cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
                                    title="@lang('Xem')">
                                    </a>

                                    {{-- Send Email --}}
                                    <button type="button"
                                            onclick="{{ $data->status == 'pending' ? 'sendVerificationEmail(' . $data->id . ')' : 'return false;' }}"
                                            class="icon-mail rounded-md p-1.5 text-2xl transition-all 
                                                {{ $data->status == 'pending' ? 'cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950' : 'opacity-50 cursor-not-allowed' }}"
                                            title="{{ $data->status == 'pending' ? __('Gửi email xác thực') : __('Đã xử lý') }}"
                                            {{ $data->status !== 'pending' ? 'disabled' : '' }}>
                                    </button>

                                    {{-- Convert to Lead --}}
                                    @if($data->status == 'pending' || $data->status == 'spam')
                                        <span class="inline-flex items-center justify-center rounded-md px-2 py-1 text-xs font-semibold bg-gray-200 text-gray-500 cursor-not-allowed"
                                            title="@lang('Chưa xác thực')">
                                            Lead
                                        </span>
                                    @elseif(($data->status == 'verified') || ($data->status == 'converted' && !$data->converted_to_lead_id))
                                        <button type="button"
                                                onclick="convertToLead({{ $data->id }})"
                                                class="inline-flex items-center justify-center rounded-md px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-700 hover:bg-blue-200 cursor-pointer transition-all"
                                                title="@lang('Chuyển thành Lead')">
                                            → Lead
                                        </button>
                                    @elseif($data->status == 'converted' && $data->converted_to_lead_id)
                                        <a href="{{ route('admin.leads.view', $data->converted_to_lead_id) }}"
                                        class="inline-flex items-center justify-center rounded-md px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 hover:bg-green-200 cursor-pointer transition-all"
                                        title="@lang('Xem Lead') #{{ $data->converted_to_lead_id }}">
                                            ✓ #{{ $data->converted_to_lead_id }}
                                        </a>
                                    @endif

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.customer-data.edit', $data->id) }}" 
                                    class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
                                    title="@lang('Chỉnh sửa')">
                                    </a>

                                    {{-- Delete --}}
                                    <button type="button"
                                            onclick="deleteItem({{ $data->id }})"
                                            class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
                                            title="@lang('Xóa')">
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-gray-400">
                                @lang('Không có dữ liệu')
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>

    {{-- Pagination --}}
    @if($customerData->hasPages())
        <div class="mt-4">
            {{ $customerData->links() }}
        </div>
    @endif

    {{-- Hidden Forms for Single Actions --}}
    <form id="send-verification-form" method="POST" style="display: none;">
        @csrf
    </form>

    <form id="convert-to-lead-form" method="POST" style="display: none;">
        @csrf
    </form>

    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    {{-- JavaScript --}}
    <script type="text/javascript">
        // Toggle all checkboxes
        function toggleAll(source) {
            var checkboxes = document.querySelectorAll('.row-checkbox');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = source.checked;
            }
        }

        // Send verification email
        function sendVerificationEmail(id) {
            if (confirm('@lang('Bạn có chắc chắn muốn gửi email xác thực?')')) {
                var form = document.getElementById('send-verification-form');
                form.action = '{{ url('admin/customer-data') }}/' + id + '/send-verification';
                form.submit();
            }
        }

        // Convert to lead
        function convertToLead(id) {
            if (confirm('@lang('Bạn có chắc chắn muốn chuyển thành Lead?')')) {
                var form = document.getElementById('convert-to-lead-form');
                form.action = '{{ url('admin/customer-data') }}/' + id + '/convert-to-lead';
                form.submit();
            }
        }

        // Delete item
        function deleteItem(id) {
            if (confirm('@lang('Bạn có chắc chắn muốn xóa?')')) {
                var form = document.getElementById('delete-form');
                form.action = '{{ url('admin/customer-data') }}/' + id;
                form.submit();
            }
        }

        // Mass action validation
        document.getElementById('mass-action-form').addEventListener('submit', function(e) {
            var action = this.querySelector('[name="action"]').value;
            var checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            
            if (!action) {
                e.preventDefault();
                alert('@lang('Vui lòng chọn hành động!')');
                return false;
            }

            if (checkedBoxes.length === 0) {
                e.preventDefault();
                alert('@lang('Vui lòng chọn ít nhất một mục!')');
                return false;
            }

            if (action === 'delete') {
                if (!confirm('@lang('Bạn có chắc chắn muốn xóa') ' + checkedBoxes.length + ' @lang('mục?')')) {
                    e.preventDefault();
                    return false;
                }
            }
            
            if (action === 'send_verification') {
                if (!confirm('@lang('Gửi email xác thực cho') ' + checkedBoxes.length + ' @lang('khách hàng?')')) {
                    e.preventDefault();
                    return false;
                }
            }
        });
    </script>
</x-admin::layouts>