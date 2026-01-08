<x-admin::layouts>
    <x-slot:title>
        Tạo Lịch Hẹn Mới
    </x-slot>

    <x-admin::form :action="route('admin.appointments.store')" method="POST" id="appointment-form">

        {{-- Header --}}
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 mb-4">
            <div class="flex flex-col gap-2">
                <div class="text-xl font-bold dark:text-white">
                    Tạo Lịch Hẹn Mới
                </div>
            </div>

            <div class="flex items-center gap-x-2.5">
                <a href="{{ route('admin.appointments.index') }}" class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800">
                    Quay lại
                </a>

                <button type="submit" class="primary-button">
                    Lưu lịch hẹn
                </button>
            </div>
        </div>

        {{-- Form Content --}}
        <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">

            {{-- Thông tin khách hàng --}}
            <div class="mb-6 border-b border-gray-200 pb-6 dark:border-gray-800">
                <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                    Thông tin khách hàng
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Chọn khách hàng --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            Khách hàng
                        </x-admin::form.control-group.label>

                        <select
                            name="lead_id"
                            id="lead_select"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm
                                   focus:border-blue-500 focus:ring focus:ring-blue-200
                                   dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            required
                        >
                            <option value="">-- Chọn khách hàng --</option>
                            @php
                            $toString = function ($value) {
                                if (is_string($value)) {
                                    $decoded = json_decode($value, true);
                                    if (json_last_error() === JSON_ERROR_NONE) {
                                        $value = $decoded;
                                    }
                                }

                                if (is_array($value)) {
                                    $first = $value[0] ?? null;
                                    if (is_array($first)) {
                                        return (string) ($first['value'] ?? $first['email'] ?? '');
                                    }
                                    return (string) $first;
                                }

                                return is_scalar($value) ? (string) $value : '';
                            };
                            @endphp

                            @foreach($leads ?? [] as $lead)
                                @php
                                    $personName = '';
                                    if (!empty($lead->person?->name)) {
                                        $personName = $toString($lead->person->name);
                                    } elseif (!empty($lead->title)) {
                                        $personName = $toString($lead->title);
                                    }

                                    $email = '';
                                    if (!empty($lead->person?->emails)) {
                                        $email = $toString($lead->person->emails);
                                    }

                                    $phone = '';
                                    if (!empty($lead->person?->contact_numbers)) {
                                        $phone = $toString($lead->person->contact_numbers);
                                    }

                                    $displayText = trim($personName);
                                    if ($email !== '') $displayText .= ' - ' . $email;
                                    if ($phone !== '') $displayText .= ' - ' . $phone;
                                @endphp

                                <option
                                    value="{{ $lead->id }}"
                                    data-name="{{ e($personName) }}"
                                    data-email="{{ e($email) }}"
                                    data-phone="{{ e($phone) }}"
                                >
                                    {{ $displayText }}
                                </option>
                            @endforeach
                            <option value="new">+ Thêm khách hàng mới</option>
                        </select>

                        <x-admin::form.control-group.error control-name="lead_id" />
                    </x-admin::form.control-group>
                </div>
            </div>

            {{-- Thông tin lịch hẹn --}}
            <div class="mt-8 mb-6 border-b border-gray-200 pb-6 dark:border-gray-800">
                <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                    Thông tin lịch hẹn
                </p>

                <div class="grid grid-cols-2 gap-4">

                     <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            Ngày yêu cầu
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="datetime"
                            name="requested_at"
                            :value="old('requested_at', now())"
                            rules="required"
                        />

                        <x-admin::form.control-group.error control-name="requested_at" />
                    </x-admin::form.control-group>

                  {{-- Thời gian bắt đầu --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            Thời gian bắt đầu
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="datetime"
                            name="start_at"
                            :value="old('start_at', now()->addMinutes(30))"
                            rules="required"
                        />

                        <x-admin::form.control-group.error control-name="start_at" />
                    </x-admin::form.control-group>

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            Thời lượng (phút)
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="number"
                            name="duration_minutes"
                            :value="old('duration_minutes', 30)"
                            min="15"
                            step="15"
                            rules="required"
                            :label="trans('Thời lượng')"
                        />

                        <x-admin::form.control-group.error control-name="duration_minutes" />
                    </x-admin::form.control-group>

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            Múi giờ
                        </x-admin::form.control-group.label>

                        <select
                            name="timezone"
                            id="timezone"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm
                                   focus:border-blue-500 focus:ring focus:ring-blue-200
                                   dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                        >
                            @foreach($timezones ?? [] as $value => $label)
                                <option value="{{ $value }}" {{ $value === 'Asia/Ho_Chi_Minh' ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </x-admin::form.control-group>

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            Loại lịch hẹn
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            name="meeting_type"
                            id="meeting_type"
                            :value="old('meeting_type')"
                            rules="required"
                            :label="trans('Loại lịch hẹn')"
                        >
                            <option value="">-- Chọn loại --</option>
                            <option value="call">Gọi điện</option>
                            <option value="onsite">Gặp trực tiếp</option>
                            <option value="online">Online Meeting</option>
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error control-name="meeting_type" />
                    </x-admin::form.control-group>

                    {{-- Số điện thoại gọi (Chỉ hiện khi chọn Gọi điện) --}}
                    <div id="call_phone_group" class="col-span-2 hidden">
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                Số điện thoại liên hệ
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="call_phone"
                                id="call_phone"
                                placeholder="0912345678"
                            />
                        </x-admin::form.control-group>
                    </div>

                    {{-- Link Meeting (Chỉ hiện khi chọn Online) --}}
                    <div id="meeting_link_group" class="col-span-2 hidden">
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                Link Meeting
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="meeting_link"
                                id="meeting_link"
                            />
                        </x-admin::form.control-group>
                    </div>

                    {{-- Địa chỉ gặp trực tiếp (Chỉ hiện khi chọn Onsite) --}}
                    <div id="onsite_address_group" class="col-span-2 grid grid-cols-2 gap-4 hidden">
                        {{-- Tỉnh/Thành phố --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                Tỉnh/Thành phố
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="province"
                                id="province"
                                :value="old('province')"
                                placeholder="VD: Hồ Chí Minh"
                            />
                        </x-admin::form.control-group>

                        {{-- Quận/Huyện --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                Quận/Huyện
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="district"
                                id="district"
                                :value="old('district')"
                                placeholder="Nhập quận/huyện"
                            />
                        </x-admin::form.control-group>

                        {{-- Phường/Xã --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                Phường/Xã
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="ward"
                                id="ward"
                                :value="old('ward')"
                                placeholder="Nhập phường/xã"
                            />
                        </x-admin::form.control-group>

                        {{-- Địa chỉ cụ thể --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                Địa chỉ cụ thể
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="street_address"
                                id="street_address"
                                :value="old('street_address')"
                                placeholder="Số nhà, tên đường..."
                            />
                        </x-admin::form.control-group>
                    </div>

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            Dịch vụ
                        </x-admin::form.control-group.label>

                        <select
                            name="service_id"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm
                                   focus:border-blue-500 focus:ring focus:ring-blue-200
                                   dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                        >
                            <option value="">-- Chọn dịch vụ --</option>
                            @foreach($products ?? [] as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </x-admin::form.control-group>

                    <x-admin::form.control-group class="col-span-2">
                        <x-admin::form.control-group.label>
                            Ghi chú
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            name="note"
                            :value="old('note')"
                            rows="4"
                            placeholder="Ghi chú thêm về lịch hẹn..."
                        />
                    </x-admin::form.control-group>
                </div>
            </div>

            {{-- Phân công xử lý --}}
            <div class="mb-6">
                <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                    Phân công xử lý
                </p>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Loại phân công --}}
                    <x-admin::form.control-group class="col-span-2">
                        <x-admin::form.control-group.label class="required">
                            Loại phân công
                        </x-admin::form.control-group.label>

                        <select
                            name="assignment_type"
                            id="assignment_type"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm
                                   focus:border-blue-500 focus:ring focus:ring-blue-200
                                   dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                        >
                            <option value="direct">Chỉ định trực tiếp</option>
                            <option value="routing">Gán theo chi nhánh/khu vực/nhóm</option>
                            <option value="resource">Gán theo tài nguyên</option>
                        </select>
                        <small class="text-gray-500">Chọn cách thức phân công lịch hẹn</small>
                    </x-admin::form.control-group>

                    {{-- 1. CHỈ ĐỊNH TRỰC TIẾP --}}
                    <div id="direct_assignment" class="col-span-2 grid grid-cols-2 gap-4" style="display:grid">
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                Nhân viên phụ trách
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="assigned_user_id"
                                id="assigned_user_id"
                                :value="old('assigned_user_id')"
                            >
                                <option value="">-- Chọn nhân viên --</option>
                                @foreach($users ?? [] as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </x-admin::form.control-group.control>
                        </x-admin::form.control-group>
                    </div>

                    {{-- 2. GÁN THEO CHI NHÁNH/KHU VỰC/NHÓM --}}
                    <div id="routing_assignment" class="col-span-2 grid grid-cols-2 gap-4 hidden">
                        <x-admin::form.control-group class="col-span-2">
                            <x-admin::form.control-group.label class="required">
                                Routing Key
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="routing_key"
                                id="routing_key"
                                :value="old('routing_key')"
                                placeholder="VD: branch.hanoi, region.north, group.sales_team_1"
                            />

                            <small class="text-gray-500 text-xs mt-1">
                                Nhập routing key để định tuyến lịch hẹn đến chi nhánh, khu vực hoặc nhóm cụ thể.<br>
                                Ví dụ: <strong>branch.hanoi</strong>, <strong>region.north</strong>, <strong>group.sales_team_1</strong>
                            </small>
                        </x-admin::form.control-group>
                    </div>

                    {{-- 3. GÁN THEO TÀI NGUYÊN --}}
                    <div id="resource_assignment" class="col-span-2 grid grid-cols-2 gap-4 hidden">
                        <x-admin::form.control-group class="col-span-2">
                            <x-admin::form.control-group.label class="required">
                                Resource ID
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="resource_id"
                                id="resource_id"
                                :value="old('resource_id')"
                                placeholder="VD: room_001, doctor_123, equipment_456"
                            />

                            <small class="text-gray-500 text-xs mt-1">
                                Nhập ID của tài nguyên cần sử dụng.<br>
                                Ví dụ: <strong>room_001</strong> (Phòng họp A), <strong>doctor_123</strong> (Bác sĩ Nguyễn Văn A), <strong>equipment_456</strong> (Thiết bị X)
                            </small>
                        </x-admin::form.control-group>
                    </div>

                    {{-- Kênh đặt lịch --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            Kênh đặt lịch
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            name="channel"
                            :value="old('channel', 'manual')"
                        >
                            <option value="manual">Nhập tay</option>
                            <option value="web">Website</option>
                            <option value="app">Mobile App</option>
                            <option value="api">API/Tích hợp</option>
                        </x-admin::form.control-group.control>
                    </x-admin::form.control-group>
                </div>
            </div>

            {{-- UTM Tracking (optional) --}}
           <div class="mb-4">
            <details class="rounded-lg border border-gray-200 dark:border-gray-800">
                <summary class="cursor-pointer p-4 font-semibold text-gray-800 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-800">
                    Thông tin tracking (tùy chọn)
                </summary>
                <div class="grid grid-cols-2 gap-4 p-4 pt-0">
                    {{-- External Source & ID - Hiện khi channel != manual --}}
                    <x-admin::form.control-group id="external_source_group" class="hidden">
                        <x-admin::form.control-group.label class="required">
                            External Source
                        </x-admin::form.control-group.label>
                        <x-admin::form.control-group.control
                            type="text"
                            name="external_source"
                            id="external_source"
                            :value="old('external_source')"
                            placeholder="booking_system, partner_crm..."
                        />
                        <small class="text-gray-500 text-xs">Bắt buộc khi đặt lịch qua Web/App/API</small>
                    </x-admin::form.control-group>

                    <x-admin::form.control-group id="external_id_group" class="hidden">
                        <x-admin::form.control-group.label class="required">
                            External ID
                        </x-admin::form.control-group.label>
                        <x-admin::form.control-group.control
                            type="text"
                            name="external_id"
                            id="external_id"
                            :value="old('external_id')"
                            placeholder="ID từ hệ thống khác"
                        />
                        <small class="text-gray-500 text-xs">Bắt buộc khi đặt lịch qua Web/App/API</small>
                    </x-admin::form.control-group>

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            UTM Source
                        </x-admin::form.control-group.label>
                        <x-admin::form.control-group.control
                            type="text"
                            name="utm_source"
                            :value="old('utm_source')"
                            placeholder="google, facebook..."
                        />
                    </x-admin::form.control-group>

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            UTM Campaign
                        </x-admin::form.control-group.label>
                        <x-admin::form.control-group.control
                            type="text"
                            name="utm_campaign"
                            :value="old('utm_campaign')"
                            placeholder="summer_sale..."
                        />
                    </x-admin::form.control-group>
                </div>
            </details>
        </div>

        </div>
    </x-admin::form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Trigger initial state khi load trang
    const leadSelect = document.getElementById('lead_select');
    const meetingType = document.getElementById('meeting_type');
    const assignmentType = document.getElementById('assignment_type');
    const channelSelect = document.querySelector('[name="channel"]');

    if (leadSelect) leadSelect.dispatchEvent(new Event('change', { bubbles: true }));
    if (meetingType) meetingType.dispatchEvent(new Event('change', { bubbles: true }));
    if (assignmentType) assignmentType.dispatchEvent(new Event('change', { bubbles: true }));
    if (channelSelect) channelSelect.dispatchEvent(new Event('change', { bubbles: true }));
});

/* ===============================
 * EVENT DELEGATION - Xử lý change events
 * =============================== */
document.addEventListener('change', function (e) {

    /* ===============================
     * LEAD SELECT - Chọn khách hàng
     * =============================== */
    if (e.target && e.target.id === 'lead_select') {
        const select = e.target;

        // Nếu chọn "Thêm khách hàng mới" thì chuyển trang
        if (select.value === 'new') {
            window.location.href = '{{ route("admin.leads.create") }}';
            return;
        }
    }

    /* ===============================
     * MEETING TYPE - Loại lịch hẹn
     * =============================== */
    if (e.target && e.target.id === 'meeting_type') {
        const meetingType = e.target.value;

        const callPhoneGroup = document.getElementById('call_phone_group');
        const meetingLinkGroup = document.getElementById('meeting_link_group');
        const onsiteAddressGroup = document.getElementById('onsite_address_group');

        const callPhoneInput = document.getElementById('call_phone');
        const meetingLinkInput = document.getElementById('meeting_link');
        const provinceInput = document.getElementById('province');
        const districtInput = document.getElementById('district');
        const wardInput = document.getElementById('ward');
        const streetAddressInput = document.getElementById('street_address');

        // Ẩn tất cả và tắt required
        if (callPhoneGroup) {
            callPhoneGroup.style.display = 'none';
            if (callPhoneInput) callPhoneInput.required = false;
        }

        if (meetingLinkGroup) {
            meetingLinkGroup.style.display = 'none';
            if (meetingLinkInput) meetingLinkInput.required = false;
        }

        if (onsiteAddressGroup) {
            onsiteAddressGroup.style.display = 'none';
            if (provinceInput) provinceInput.required = false;
            if (districtInput) districtInput.required = false;
            if (wardInput) wardInput.required = false;
            if (streetAddressInput) streetAddressInput.required = false;
        }

        // Hiện group tương ứng và bật required
        if (meetingType === 'call' && callPhoneGroup) {
            callPhoneGroup.style.display = 'block';
            if (callPhoneInput) callPhoneInput.required = true;
        }
        else if (meetingType === 'online' && meetingLinkGroup) {
            meetingLinkGroup.style.display = 'block';
            if (meetingLinkInput) meetingLinkInput.required = true;
        }
        else if (meetingType === 'onsite' && onsiteAddressGroup) {
            onsiteAddressGroup.style.display = 'grid';
            if (provinceInput) provinceInput.required = true;
            if (districtInput) districtInput.required = true;
            if (wardInput) wardInput.required = true;
            if (streetAddressInput) streetAddressInput.required = true;
        }
    }

    /* ===============================
     * ASSIGNMENT TYPE - Loại phân công
     * =============================== */
    if (e.target && e.target.id === 'assignment_type') {
        const assignmentType = e.target.value;

        const directAssignment = document.getElementById('direct_assignment');
        const routingAssignment = document.getElementById('routing_assignment');
        const resourceAssignment = document.getElementById('resource_assignment');

        const assignedUserId = document.getElementById('assigned_user_id');
        const routingKey = document.getElementById('routing_key');
        const resourceId = document.getElementById('resource_id');

        // Ẩn tất cả và tắt required
        if (directAssignment) {
            directAssignment.style.display = 'none';
            if (assignedUserId) assignedUserId.required = false;
        }

        if (routingAssignment) {
            routingAssignment.style.display = 'none';
            if (routingKey) routingKey.required = false;
        }

        if (resourceAssignment) {
            resourceAssignment.style.display = 'none';
            if (resourceId) resourceId.required = false;
        }

        // Hiện group tương ứng và bật required
        if (assignmentType === 'direct' && directAssignment) {
            directAssignment.style.display = 'grid';
            if (assignedUserId) assignedUserId.required = true;
        }
        else if (assignmentType === 'routing' && routingAssignment) {
            routingAssignment.style.display = 'grid';
            if (routingKey) routingKey.required = true;
        }
        else if (assignmentType === 'resource' && resourceAssignment) {
            resourceAssignment.style.display = 'grid';
            if (resourceId) resourceId.required = true;
        }
    }

    /* ===============================
     * CHANNEL - Hiển thị external fields
     * =============================== */
    if (e.target && e.target.name === 'channel') {
        const channel = e.target.value;
        const externalSourceGroup = document.getElementById('external_source_group');
        const externalIdGroup = document.getElementById('external_id_group');
        const externalSourceInput = document.getElementById('external_source');
        const externalIdInput = document.getElementById('external_id');

        if (channel === 'manual') {
            // Ẩn và không bắt buộc
            if (externalSourceGroup) {
                externalSourceGroup.classList.add('hidden');
                if (externalSourceInput) externalSourceInput.required = false;
            }
            if (externalIdGroup) {
                externalIdGroup.classList.add('hidden');
                if (externalIdInput) externalIdInput.required = false;
            }
        } else {
            // Hiện và bắt buộc
            if (externalSourceGroup) {
                externalSourceGroup.classList.remove('hidden');
                if (externalSourceInput) externalSourceInput.required = true;
            }
            if (externalIdGroup) {
                externalIdGroup.classList.remove('hidden');
                if (externalIdInput) externalIdInput.required = true;
            }
        }
    }
});

/* ===============================
 * FORM VALIDATION - Kiểm tra trước khi submit
 * =============================== */
document.addEventListener('submit', function (e) {
    if (e.target && e.target.id === 'appointment-form') {
        const meetingType = document.getElementById('meeting_type');
        const assignmentType = document.getElementById('assignment_type');
        const channel = document.querySelector('[name="channel"]');

        // 1. Kiểm tra external fields khi channel != manual
        if (channel && channel.value !== 'manual') {
            const externalSource = document.getElementById('external_source');
            const externalId = document.getElementById('external_id');

            if (externalSource && !externalSource.value.trim()) {
                e.preventDefault();
                alert('Vui lòng nhập External Source khi đặt lịch qua ' + channel.value.toUpperCase());
                externalSource.focus();
                return false;
            }

            if (externalId && !externalId.value.trim()) {
                e.preventDefault();
                alert('Vui lòng nhập External ID khi đặt lịch qua ' + channel.value.toUpperCase());
                externalId.focus();
                return false;
            }
        }

        if (!meetingType || !assignmentType) return;

        const meetingTypeValue = meetingType.value;
        const assignmentTypeValue = assignmentType.value;

        // 2. Kiểm tra theo meeting type
        if (meetingTypeValue === 'call') {
            const callPhoneInput = document.getElementById('call_phone');
            if (callPhoneInput && !callPhoneInput.value.trim()) {
                e.preventDefault();
                alert('Vui lòng nhập số điện thoại liên hệ');
                callPhoneInput.focus();
                return false;
            }
        }
        else if (meetingTypeValue === 'online') {
            const meetingLinkInput = document.getElementById('meeting_link');
            if (meetingLinkInput && !meetingLinkInput.value.trim()) {
                e.preventDefault();
                alert('Vui lòng nhập link meeting');
                meetingLinkInput.focus();
                return false;
            }
        }
        else if (meetingTypeValue === 'onsite') {
            const provinceInput = document.getElementById('province');
            const districtInput = document.getElementById('district');
            const wardInput = document.getElementById('ward');
            const streetAddressInput = document.getElementById('street_address');

            if (provinceInput && !provinceInput.value.trim()) {
                e.preventDefault();
                alert('Vui lòng nhập tỉnh/thành phố');
                provinceInput.focus();
                return false;
            }
            if (districtInput && !districtInput.value.trim()) {
                e.preventDefault();
                alert('Vui lòng nhập quận/huyện');
                districtInput.focus();
                return false;
            }
            if (wardInput && !wardInput.value.trim()) {
                e.preventDefault();
                alert('Vui lòng nhập phường/xã');
                wardInput.focus();
                return false;
            }
            if (streetAddressInput && !streetAddressInput.value.trim()) {
                e.preventDefault();
                alert('Vui lòng nhập địa chỉ cụ thể');
                streetAddressInput.focus();
                return false;
            }
        }

        // 3. Kiểm tra assignment type
        if (assignmentTypeValue === 'direct') {
            const assignedUserId = document.getElementById('assigned_user_id');
            if (assignedUserId && !assignedUserId.value) {
                e.preventDefault();
                alert('Vui lòng chọn nhân viên phụ trách');
                assignedUserId.focus();
                return false;
            }
        }
        else if (assignmentTypeValue === 'routing') {
            const routingKey = document.getElementById('routing_key');
            if (routingKey && !routingKey.value.trim()) {
                e.preventDefault();
                alert('Vui lòng nhập routing key');
                routingKey.focus();
                return false;
            }
        }
        else if (assignmentTypeValue === 'resource') {
            const resourceId = document.getElementById('resource_id');
            if (resourceId && !resourceId.value.trim()) {
                e.preventDefault();
                alert('Vui lòng nhập resource ID');
                resourceId.focus();
                return false;
            }
        }
    }
});
</script>
@endpush

</x-admin::layouts>
