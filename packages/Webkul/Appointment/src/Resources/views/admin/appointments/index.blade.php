    <x-admin::layouts>
        <x-slot:title>
            Quản Lý Lịch Hẹn
        </x-slot>

        <v-appointments>
            <!-- Header -->
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 mb-6">
                <div class="flex flex-col gap-2">
                    <div class="text-2xl font-bold dark:text-white flex items-center gap-2">
                        Quản Lý Lịch Hẹn
                    </div>
                </div>

                <div class="flex items-center gap-x-2.5">
                    <!-- Nút Tạo lịch hẹn -->
                    <a
                        href="{{ route('admin.appointments.create-newCustomer') }}"
                        class="px-4 py-2 bg-white text-gray-800 border border-gray-300 rounded-lg
                            hover:bg-gray-100 hover:border-gray-400 transition-colors flex items-center gap-2"
                    >
                        <i class="icon-add"></i>
                        Tạo lịch hẹn cho khách hàng mới
                    </a>

                    <a
                        href="{{ route('admin.appointments.create') }}"
                        class="px-4 py-2 bg-brandColor text-white rounded-lg hover:bg-blue-700 transition-colors"
                    >
                        <i class="icon-add"></i> Tạo lịch hẹn cho khách hàng tiềm năng
                    </a>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-4 gap-4 mb-6 max-lg:grid-cols-2 max-sm:grid-cols-1">
                <x-admin::card.card
                    title="Tổng lịch hẹn"
                    :value="$stats['total'] ?? 0"
                />

                <x-admin::card.card
                    title="Đã xác nhận"
                    :value="$stats['confirmed'] ?? 0"
                    color="bg-green-500"
                />

                <x-admin::card.card
                    title="Chờ xử lý"
                    :value="($stats['scheduled'] ?? 0) + ($stats['rescheduled'] ?? 0)"
                    color="bg-yellow-400"
                />

                <x-admin::card.card
                    title="Đã hủy"
                    :value="$stats['cancelled'] ?? 0"
                    color="bg-red-500"
                />
            </div>

            <!-- Content -->
            <template v-if="viewType === 'table'">
                <x-admin::shimmer.datagrid :is-multi-row="true"/>

                <x-admin::datagrid
                    src="{{ route('admin.appointments.datagrid') }}"
                    :isMultiRow="true"
                    ref="datagrid"
                />
            </template>

            <template v-else>
                <v-calendar></v-calendar>
            </template>
                    </div>
                </div>
        </v-appointments>

        @pushOnce('scripts')
            {{-- Appointments --}}
            <script type="module">
                app.component('v-appointments', {
                    template: `<slot></slot>`,

                    data() {
                        return {
                            viewType: '{{ request('view-type', 'table') }}',
                            showStatusHistoryModal: false,
                        };
                    },

                    methods: {
                        toggleView(type) {
                            this.viewType = type;

                            const url = new URL(window.location);
                            url.searchParams.set('view-type', type);
                            window.history.pushState({}, '', url);
                        },
                    },
                });
            </script>

            {{-- Calendar --}}
            <script type="module">
                app.component('v-calendar', {
                    template: `
                        <div>
                            <!-- Calendar Wrapper -->
                            <div class="calendar-wrapper">
                                <v-vue-cal
                                    ref="vuecal"
                                    hide-view-selector
                                    :watchRealTime="true"
                                    :disable-views="['years','year','month','day']"
                                    :time-from="0"
                                    :time-to="24 * 60"
                                    :time-step="30"
                                    :editable-events="{ title: false, drag: true, resize: true, delete: false, create: false }"
                                    style="height: calc(100vh - 150px)"
                                    :events="events"
                                    @ready="onReady"
                                    @view-change="load"
                                    @event-click="showDetails"
                                    @event-drop="handleEventDrop"
                                    @event-duration-change="handleEventResize"
                                >
                                    <template #event="{ event }">
                                        <div class="appointment-event">
                                            <div class="appointment-status-bar" :style="getStatusBarStyle(event.status)"></div>
                                            <div class="appointment-content">
                                                <div class="appointment-header">
                                                    <div class="appointment-customer">
                                                        <strong>@{{ event.customer_name }}</strong>
                                                    </div>
                                                    <div class="appointment-time">
                                                        @{{ formatTime(event.start) }} - @{{ formatTime(event.end) }}
                                                    </div>
                                                </div>
                                                <div class="appointment-body">
                                                    <div v-if="event.customer_phone" class="appointment-info">
                                                        <i class="icon-phone text-xs"></i> @{{ event.customer_phone }}
                                                    </div>
                                                    <div v-if="event.customer_email" class="appointment-info">
                                                        <i class="icon-mail text-xs"></i> @{{ event.customer_email }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </v-vue-cal>
                            </div>

                            <!-- Edit Modal -->
                            <div
                                v-if="editingAppointment"
                                class="fixed inset-0 flex items-center justify-center overflow-y-auto p-4"
                                style="z-index: 99999; background-color: rgba(0, 0, 0, 0.3); backdrop-filter: blur(2px);"
                                @click.self="closeEdit"
                            >
                                <div class="bg-white dark:bg-gray-900 rounded-lg shadow-2xl max-w-2xl w-full my-8" style="position: relative; z-index: 100000;">
                                    <!-- Modal Header -->
                                    <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-800">
                                        <h2 class="text-xl font-bold dark:text-white">Chỉnh Sửa Lịch Hẹn</h2>
                                        <button
                                            @click="closeEdit"
                                            class="icon-cross text-xl cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full p-1.5 transition-colors"
                                        ></button>
                                    </div>

                                    <!-- Modal Body -->
                                    <div class="p-4 space-y-5 max-h-[65vh] overflow-y-auto">
                                        <!-- Status Badge -->
                                        <div class="flex items-center gap-2">
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold" :style="{
                                                backgroundColor: getStatusColor(editingAppointment.status),
                                                color: 'white'
                                            }">
                                                @{{ getStatusLabel(editingAppointment.status) }}
                                            </span>
                                        </div>

                                        <!-- Attendance Action Box -->
                                        <div
                                            v-if="editingAppointment?.status === 'confirmed'"
                                            class="mt-4 p-4 border border-yellow-300 bg-yellow-50 rounded-lg"
                                        >
                                            <div class="font-semibold text-sm text-gray-800 mb-2">
                                                Xác nhận tình trạng khách hàng
                                            </div>

                                            <div class="flex gap-3">
                                                <button
                                                    @click="setAttendance('showed')"
                                                    class="px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 transition"
                                                >
                                                    Đã hoàn thành
                                                </button>

                                                <button class="bg-red-600 !text-black !opacity-100 font-bold"
                                                    @click="setAttendance('no_show')"
                                                >
                                                    Chưa hoàn thành
                                                </button>
                                            </div>

                                            <p class="text-xs text-gray-600 mt-2">
                                                ⚠ Sau khi xác nhận, lịch hẹn sẽ bị khóa và không thể chỉnh sửa.
                                            </p>
                                        </div>

                                        <!-- Customer Info -->
                                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3 space-y-2">
                                            <h3 class="font-semibold text-sm text-gray-800 dark:text-white mb-2">Thông Tin Khách Hàng</h3>
                                            <div class="grid grid-cols-2 gap-3 text-xs">
                                                <div>
                                                    <span class="text-gray-500 dark:text-gray-400">Tên:</span>
                                                    <p class="font-medium dark:text-white">@{{ editingAppointment.customer_name }}</p>
                                                </div>
                                                <div v-if="editingAppointment.customer_phone">
                                                    <span class="text-gray-500 dark:text-gray-400">SĐT:</span>
                                                    <p class="font-medium dark:text-white">@{{ editingAppointment.customer_phone }}</p>
                                                </div>
                                                <div v-if="editingAppointment.customer_email" class="col-span-2">
                                                    <span class="text-gray-500 dark:text-gray-400">Email:</span>
                                                    <p class="font-medium dark:text-white">@{{ editingAppointment.customer_email }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Editable Form -->
                                        <form class="space-y-4">
                                            <fieldset :disabled="isReadonlyAppointment" class="space-y-4">
                                            <!-- Time Fields -->
                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                                        Thời gian bắt đầu *
                                                    </label>
                                                    <input
                                                        type="datetime-local"
                                                        v-model="editForm.start_at"
                                                        required
                                                        class="w-full px-2.5 py-1.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg dark:bg-gray-800 dark:text-white"
                                                    />
                                                    <p v-if="errors.start_at" class="text-xs text-red-600 mt-1">
                                                        @{{ errors.start_at }}
                                                    </p>
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                                        Thời gian kết thúc *
                                                    </label>
                                                    <input
                                                        type="datetime-local"
                                                        v-model="editForm.end_at"
                                                        required
                                                        class="w-full px-2.5 py-1.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg dark:bg-gray-800 dark:text-white"
                                                    />
                                                    <p v-if="errors.end_at" class="text-xs text-red-600 mt-1">
                                                        @{{ errors.end_at }}
                                                    </p>
                                                </div>
                                            </div>

                                        <!-- Meeting Type & Service -->
                                        <div class="space-y-4">
                                            <!-- Loại lịch hẹn -->
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                                    Loại lịch hẹn *
                                                </label>
                                                <select
                                                    v-model="editForm.meeting_type"
                                                    @change="handleMeetingTypeChange"
                                                    required
                                                    class="w-full px-2.5 py-1.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg dark:bg-gray-800 dark:text-white"
                                                >
                                                    <option value="">-- Chọn loại --</option>
                                                    <option value="call">Gọi điện</option>
                                                    <option value="onsite">Gặp trực tiếp</option>
                                                    <option value="online">Online Meeting</option>
                                                </select>

                                                <p v-if="errors.meeting_type" class="text-xs text-red-600 mt-1">
                                                    @{{ errors.meeting_type }}
                                                </p>
                                            </div>

                                            <!-- Số điện thoại gọi (Chỉ khi chọn Call) -->
                                            <div v-show="editForm.meeting_type === 'call'">
                                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                                    Số điện thoại liên hệ *
                                                </label>
                                                <input
                                                    type="text"
                                                    v-model="editForm.call_phone"
                                                    placeholder="0912345678"
                                                    :required="editForm.meeting_type === 'call'"
                                                    class="w-full px-2.5 py-1.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg dark:bg-gray-800 dark:text-white"
                                                />
                                                <p v-if="errors.call_phone" class="text-xs text-red-600 mt-1">
                                                    @{{ errors.call_phone }}
                                                </p>
                                            </div>

                                            <!-- Link Meeting (Chỉ khi chọn Online) -->
                                            <div v-show="editForm.meeting_type === 'online'">
                                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                                    Link Meeting *
                                                </label>
                                                <input
                                                    type="text"
                                                    v-model="editForm.meeting_link"
                                                    placeholder="https://meet.google.com/..."
                                                    :required="editForm.meeting_type === 'online'"
                                                    class="w-full px-2.5 py-1.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg dark:bg-gray-800 dark:text-white"
                                                />
                                                <p v-if="errors.meeting_link" class="text-xs text-red-600 mt-1">
                                                    @{{ errors.meeting_link }}
                                                </p>
                                            </div>

                                            <!-- Địa chỉ gặp trực tiếp (Chỉ khi chọn Onsite) -->
                                            <div v-show="editForm.meeting_type === 'onsite'" class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                                        Tỉnh/Thành phố *
                                                    </label>
                                                    <input
                                                        type="text"
                                                        v-model="editForm.province"
                                                        placeholder="VD: Hồ Chí Minh"
                                                        :required="editForm.meeting_type === 'onsite'"
                                                        class="w-full px-2.5 py-1.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg dark:bg-gray-800 dark:text-white"
                                                    />
                                                    <p v-if="errors.province" class="text-xs text-red-600 mt-1">
                                                        @{{ errors.province }}
                                                    </p>
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                                        Quận/Huyện *
                                                    </label>
                                                    <input
                                                        type="text"
                                                        v-model="editForm.district"
                                                        placeholder="Nhập quận/huyện"
                                                        :required="editForm.meeting_type === 'onsite'"
                                                        class="w-full px-2.5 py-1.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg dark:bg-gray-800 dark:text-white"
                                                    />
                                                    <p v-if="errors.district" class="text-xs text-red-600 mt-1">
                                                        @{{ errors.district }}
                                                    </p>
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                                        Phường/Xã *
                                                    </label>
                                                    <input
                                                        type="text"
                                                        v-model="editForm.ward"
                                                        placeholder="Nhập phường/xã"
                                                        :required="editForm.meeting_type === 'onsite'"
                                                        class="w-full px-2.5 py-1.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg dark:bg-gray-800 dark:text-white"
                                                    />
                                                        <p v-if="errors.ward" class="text-xs text-red-600 mt-1">
                                                            @{{ errors.ward }}
                                                        </p>
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                                        Địa chỉ cụ thể *
                                                    </label>
                                                    <input
                                                        type="text"
                                                        v-model="editForm.street_address"
                                                        placeholder="Số nhà, tên đường..."
                                                        :required="editForm.meeting_type === 'onsite'"
                                                        class="w-full px-2.5 py-1.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg dark:bg-gray-800 dark:text-white"
                                                    />
                                                    <p v-if="errors.street_address" class="text-xs text-red-600 mt-1">
                                                        @{{ errors.street_address }}
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Dịch vụ -->
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                                    Dịch vụ
                                                </label>
                                                <select
                                                    v-model="editForm.service_id"
                                                    class="w-full px-2.5 py-1.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg dark:bg-gray-800 dark:text-white"
                                                >
                                                    <option value="">-- Chọn dịch vụ --</option>
                                                    <option v-for="service in services" :key="service.id" :value="service.id">
                                                        @{{ service.name }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                            <!-- Note -->
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                                    Ghi chú
                                                </label>
                                                <textarea
                                                    v-model="editForm.note"
                                                    rows="2"
                                                    placeholder="Thêm ghi chú..."
                                                    class="w-full px-2.5 py-1.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg dark:bg-gray-800 dark:text-white"
                                                ></textarea>
                                            </div>
                                            </fieldset>
                                        </form>
                                    </div>

                                    <!-- Modal Footer -->
                                    <div class="flex justify-between p-4 border-t border-gray-200 dark:border-gray-800">
                                        <button
                                            v-if="!isReadonlyAppointment"
                                            @click="showCancelModal"
                                            :disabled="saving"
                                            class="px-3 py-1.5 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600 disabled:opacity-50 transition-colors"
                                        >
                                            <i class="icon-cancel"></i> Hủy lịch hẹn
                                        </button>
                                        <div class="flex gap-2">
                                            <button
                                                @click="closeEdit"
                                                class="px-3 py-1.5 text-sm bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300"
                                            >
                                                Đóng
                                            </button>
                                            <button
                                                v-if="!isReadonlyAppointment"
                                                type="button"
                                                @click="showUpdateModal"
                                                :disabled="!canSave"
                                                class="px-3 py-1.5 text-sm bg-brandColor text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 transition-colors"
                                            >
                                                <span v-if="saving">Đang lưu...</span>
                                                <span v-else><i class="icon-save"></i> Lưu</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Update Reason Modal -->
                            <div
                                v-if="showUpdateReasonModal"
                                class="fixed inset-0 flex items-center justify-center p-4"
                                style="z-index: 100001; background-color: rgba(0, 0, 0, 0.4); backdrop-filter: blur(3px);"
                                @click.self="closeReasonModal"
                            >
                                <div class="bg-white dark:bg-gray-900 rounded-lg shadow-2xl max-w-md w-full">
                                    <div class="p-4 border-b border-gray-200 dark:border-gray-800">
                                        <h3 class="text-lg font-bold dark:text-white">Lý do thay đổi</h3>
                                    </div>
                                    <div class="p-4">
                                        <textarea
                                            v-model="updateReason"
                                            rows="3"
                                            placeholder="Nhập lý do (không bắt buộc)..."
                                            class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-700 rounded-lg dark:bg-gray-800 dark:text-white"
                                        ></textarea>
                                        <p class="text-xs text-gray-500 mt-2">Để trống: "Khách hàng muốn đổi thông tin lịch hẹn"</p>
                                    </div>
                                    <div class="flex justify-end gap-2 p-4 border-t border-gray-200 dark:border-gray-800">
                                        <button
                                            @click="closeReasonModal"
                                            class="px-3 py-1.5 text-sm bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300"
                                        >
                                            Hủy
                                        </button>
                                        <button
                                            type="button"
                                            @click="confirmUpdate"
                                            class="px-3 py-1.5 text-sm bg-brandColor text-white rounded-lg hover:bg-blue-700"
                                        >
                                            Xác nhận
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Cancel Reason Modal -->
                            <div
                                v-if="showCancelReasonModal"
                                class="fixed inset-0 flex items-center justify-center p-4"
                                style="z-index: 100001; background-color: rgba(0, 0, 0, 0.4); backdrop-filter: blur(3px);"
                                @click.self="closeReasonModal"
                            >
                                <div class="bg-white dark:bg-gray-900 rounded-lg shadow-2xl max-w-md w-full">
                                    <div class="p-4 border-b border-gray-200 dark:border-gray-800">
                                        <h3 class="text-lg font-bold text-red-600">Lý do hủy lịch</h3>
                                    </div>
                                    <div class="p-4">
                                        <textarea
                                            v-model="cancelReason"
                                            rows="3"
                                            placeholder="Nhập lý do hủy (không bắt buộc)..."
                                            class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-700 rounded-lg dark:bg-gray-800 dark:text-white"
                                        ></textarea>
                                        <p class="text-xs text-gray-500 mt-2">Để trống: "Khách hàng hủy không rõ lý do"</p>
                                    </div>
                                    <div class="flex justify-end gap-2 p-4 border-t border-gray-200 dark:border-gray-800">
                                        <button
                                            @click="closeReasonModal"
                                            class="px-3 py-1.5 text-sm bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300"
                                        >
                                            Đóng
                                        </button>
                                        <button
                                            @click="confirmCancel"
                                            class="px-3 py-1.5 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600"
                                        >
                                            Xác nhận hủy
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>`,

                        data() {
                            return {
                                events: [],
                                calendarReady: false,
                                editingAppointment: null,
                                editForm: {},
                                saving: false,
                                showUpdateReasonModal: false,
                                showCancelReasonModal: false,
                                updateReason: '',
                                cancelReason: '',
                                services: [],
                                errors: {},
                            };
                        },

                        watch: {
                            editForm: {
                                deep: true,
                                handler() {
                                    this.validateForm();
                                }
                            }
                        },

                        computed: {
                        isReadonlyAppointment() {
                            if (!this.editingAppointment) return false;

                            return ['cancelled', 'no_show', 'showed', 'completed']
                                .includes(this.editingAppointment.status);
                            },
                            canSave() {
                                return !this.saving && Object.keys(this.errors).length === 0;
                            },
                    },

                    mounted() {
                        this.loadServices();
                    },

                    methods: {
                        validateForm() {
                            this.errors = {};

                            const now = new Date();

                            // Start time
                            if (!this.editForm.start_at) {
                                this.errors.start_at = 'Vui lòng nhập thời gian bắt đầu';
                            } else {
                                const start = new Date(this.editForm.start_at);
                                if (start <= now) {
                                    this.errors.start_at = 'Thời gian bắt đầu phải lớn hơn thời gian hiện tại';
                                }
                            }

                            // End time
                            if (!this.editForm.end_at) {
                                this.errors.end_at = 'Vui lòng nhập thời gian kết thúc';
                            } else {
                                const end = new Date(this.editForm.end_at);
                                if (end <= now) {
                                    this.errors.end_at = 'Thời gian kết thúc phải lớn hơn thời gian hiện tại';
                                }
                            }

                            // So sánh start < end
                            if (this.editForm.start_at && this.editForm.end_at) {
                                const start = new Date(this.editForm.start_at);
                                const end = new Date(this.editForm.end_at);

                                if (end <= start) {
                                    this.errors.end_at = 'Thời gian kết thúc phải lớn hơn thời gian bắt đầu';
                                }
                            }

                            // Meeting type
                            if (!this.editForm.meeting_type) {
                                this.errors.meeting_type = 'Vui lòng chọn loại lịch hẹn';
                            }

                            if (this.editForm.meeting_type === 'call' && !this.editForm.call_phone) {
                                this.errors.call_phone = 'Vui lòng nhập số điện thoại';
                            }

                            if (this.editForm.meeting_type === 'online' && !this.editForm.meeting_link) {
                                this.errors.meeting_link = 'Vui lòng nhập link meeting';
                            }

                            if (this.editForm.meeting_type === 'onsite') {
                                if (!this.editForm.province) {
                                    this.errors.province = 'Vui lòng nhập tỉnh/thành phố';
                                }
                                if (!this.editForm.district) {
                                    this.errors.district = 'Vui lòng nhập quận/huyện';
                                }
                                if (!this.editForm.ward) {
                                    this.errors.ward = 'Vui lòng nhập phường/xã';
                                }
                                if (!this.editForm.street_address) {
                                    this.errors.street_address = 'Vui lòng nhập địa chỉ cụ thể';
                                }
                            }

                            return Object.keys(this.errors).length === 0;
                        },
                        loadServices() {
                            // ✅ LẤY ĐÚNG FIELD records từ datagrid response
                            axios.get("{{ route('admin.products.index') }}")
                                .then(response => {
                                    // Lấy từ records thay vì data
                                    this.services = response.data.records || [];
                                    console.log('✅ Loaded services:', this.services);
                                    console.log('✅ Total services:', this.services.length);
                                })
                                .catch(error => {
                                    console.error('❌ Không thể tải dịch vụ:', error);
                                    this.services = [];
                                });
                        },

                        async setAttendance(status) {
                            if (!confirm('Bạn chắc chắn với lựa chọn này?')) return;

                            try {
                                const res = await axios.post(
                                    `/admin/appointments/${this.editingAppointment.id}/attendance`,
                                    { status }
                                );

                                if (res.data.success) {
                                    // ✅ Cập nhật status trong modal
                                    this.editingAppointment.status = status;
                                    this.editingAppointment.raw_status = status;

                                    // ✅ Cập nhật status trong events array
                                    const eventIndex = this.events.findIndex(e => e.id === this.editingAppointment.id);
                                    if (eventIndex !== -1) {
                                        this.events[eventIndex].status = status;
                                        this.events[eventIndex].raw_status = status;

                                        // ✅ Cập nhật màu sắc
                                        const statusColors = {
                                            'showed': '#3b82f6',      // Xanh dương
                                            'no_show': '#6b7280',     // Xám
                                            'completed': '#3b82f6'    // Xanh dương
                                        };
                                        this.events[eventIndex].borderColor = statusColors[status];
                                    }

                                    // ✅ Force Vue Calendar re-render
                                    this.$refs.vuecal.$forceUpdate();

                                    this.$toast.success(res.data.message || 'Cập nhật thành công');

                                    // ✅ KHÔNG reload trang, chỉ đóng modal sau 500ms
                                    setTimeout(() => {
                                        this.closeEdit();
                                    }, 500);
                                }
                            } catch (err) {
                                this.$toast.error(
                                    err.response?.data?.message || 'Không thể cập nhật trạng thái'
                                );
                            }
                        },

                        onReady(args) {
                            this.calendarReady = true;
                            this.load(args);
                        },

                        load({ startDate, endDate }) {
                            axios.get(
                                "{{ route('admin.appointments.datagrid') }}",
                                { params: { view_type: 'calendar', startDate, endDate } }
                            ).then(response => {
                                this.events = this.formatEvents(response.data.records || response.data.events || []);
                            });
                        },

                        formatEvents(appointments) {
                        const statusPriority = {
                            'confirmed': 5,
                            'scheduled': 4,
                            'rescheduled': 3,
                            'showed': 2,       // ✅ Showed có priority cao
                            'completed': 2,
                            'no_show': 1,
                            'cancelled': 0
                        };

                        const sortedAppointments = [...appointments].sort((a, b) => {
                            return (statusPriority[b.status] || 0) - (statusPriority[a.status] || 0);
                        });

                        const filteredAppointments = [];
                        const hiddenAppointments = [];

                        for (const apt of sortedAppointments) {
                            const aptStart = new Date(apt.start_at);
                            const aptEnd = new Date(apt.end_at);

                            const conflictingAppointment = filteredAppointments.find(existing => {
                                const existingStart = new Date(existing.start_at);
                                const existingEnd = new Date(existing.end_at);
                                const isOverlapping = aptStart < existingEnd && aptEnd > existingStart;

                                if (!isOverlapping) return false;

                                const aptPriority = statusPriority[apt.status] || 0;
                                const existingPriority = statusPriority[existing.status] || 0;

                                return existingPriority >= aptPriority;
                            });

                            if (!conflictingAppointment) {
                                filteredAppointments.push(apt);
                            } else {
                                hiddenAppointments.push({
                                    id: apt.id,
                                    customer: apt.customer_name,
                                    time: `${apt.start_at} - ${apt.end_at}`,
                                    status: apt.status,
                                    hidden_by: conflictingAppointment.id
                                });
                            }
                        }

                        console.log('✅ Shown appointments:', filteredAppointments.length);
                        console.log('❌ Hidden appointments:', hiddenAppointments.length, hiddenAppointments);

                        return filteredAppointments.map(apt => {
                            let displayStatus = apt.status;

                            // ✅ Chỉ merge scheduled/rescheduled thành pending
                            // ✅ KHÔNG merge showed/completed
                            if (['scheduled', 'rescheduled'].includes(apt.status)) {
                                displayStatus = 'pending';
                            }

                            // ✅ FIX: Màu sắc chính xác cho showed
                            const statusColors = {
                                'pending': '#fbbf24',      // Vàng
                                'confirmed': '#10b981',    // Xanh lá
                                'showed': '#3b82f6',       // ✅ Xanh dương (không phải xám)
                                'completed': '#3b82f6',    // Xanh dương
                                'cancelled': '#ef4444',    // Đỏ
                                'no_show': '#6b7280'       // Xám
                            };

                            return {
                                // Thông tin cơ bản
                                id: apt.id,
                                start: apt.start_at,
                                end: apt.end_at,
                                title: apt.customer_name,

                                // Thông tin khách hàng
                                customer_name: apt.customer_name,
                                customer_phone: apt.customer_phone,
                                customer_email: apt.customer_email,

                                // Thông tin thời gian
                                requested_at: apt.requested_at,
                                duration_minutes: apt.duration_minutes,
                                timezone: apt.timezone,

                                // Thông tin lịch hẹn
                                meeting_type: apt.meeting_type,
                                call_phone: apt.call_phone,
                                meeting_link: apt.meeting_link,
                                province: apt.province,
                                district: apt.district,
                                ward: apt.ward,
                                street_address: apt.street_address,

                                // Thông tin dịch vụ
                                service_id: apt.service_id,
                                service_name: apt.service_name,

                                // Thông tin phân công
                                assignment_type: apt.assignment_type,
                                assigned_user_id: apt.assigned_user_id,
                                assigned_user_name: apt.assigned_user_name,
                                routing_key: apt.routing_key,
                                resource_id: apt.resource_id,

                                // Thông tin khác
                                channel: apt.channel,
                                note: apt.note,

                                // Trạng thái - LƯU CẢ RAW STATUS
                                status: displayStatus,
                                raw_status: apt.status,
                                class: `status-${displayStatus}`,
                                background: '#ffffff',
                                borderColor: statusColors[displayStatus] || statusColors[apt.status] || '#6b7280',
                            };
                        });
                    },
                        handleEventDrop(event, newDate) {
                            this.updateEventTime(event.id, newDate, event.end);
                        },

                        handleEventResize(event, newEnd) {
                            this.updateEventTime(event.id, event.start, newEnd);
                        },

                        updateEventTime(eventId, newStart, newEnd) {
                            axios.post(
                                "{{ route('admin.appointments.update-time') }}",
                                {
                                    id: eventId,
                                    start_at: newStart,
                                    end_at: newEnd
                                }
                            ).then(response => {
                                if (response.data.success) {
                                    window.location.reload();
                                }
                            }).catch(error => {
                                alert('Không thể cập nhật thời gian');
                                this.$refs.vuecal.refreshEvents();
                            });
                        },

                        showDetails(event) {
                            this.editingAppointment = {
                                ...event,
                                status: event.raw_status
                            };
                            this.editForm = {
                                start_at: this.formatDateTimeLocal(event.start),
                                end_at: this.formatDateTimeLocal(event.end),
                                meeting_type: event.meeting_type || '',
                                call_phone: event.call_phone || '',
                                meeting_link: event.meeting_link || '',
                                province: event.province || '',
                                district: event.district || '',
                                ward: event.ward || '',
                                street_address: event.street_address || '',
                                service_id: event.service_id || '',
                                note: event.note || ''
                            };

                            this.errors = {};
                        },

                        closeEdit() {
                            this.editingAppointment = null;
                            this.editForm = {};
                            this.updateReason = '';
                            this.cancelReason = '';

                            this.errors = {};
                        },


                        showUpdateModal() {
                            if (!this.validateForm()) {
                                 alert('Vui lòng kiểm tra lại các trường bị lỗi');
                                return;
                            }

                            this.showUpdateReasonModal = true;
                        },

                        showCancelModal() {
                            this.showCancelReasonModal = true;
                        },

                        closeReasonModal() {
                            this.showUpdateReasonModal = false;
                            this.showCancelReasonModal = false;
                        },

                        confirmUpdate() {
                            if (this.saving) return;

                            const reason = this.updateReason.trim() || 'Khách hàng muốn đổi thông tin lịch hẹn';

                            this.saving = true;

                            // MERGE toàn bộ appointment + form đã sửa
                            const payload = {
                                ...this.editingAppointment,  // Lấy tất cả fields gốc
                                ...this.editForm,             // Override bằng fields đã sửa
                                reason: reason                // Thêm lý do
                            };

                            // Loại bỏ các field không cần thiết (nếu có)
                            delete payload.title;
                            delete payload.class;
                            delete payload.background;
                            delete payload.borderColor;

                            this.showUpdateReasonModal = false;
                            this.updateReason = '';

                            axios.post(
                                `/admin/appointments/${this.editingAppointment.id}/update`,
                                payload
                            ).then(response => {
                                if (response.data.success) {
                                    alert('Cập nhật thành công!');
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 300);
                                }
                            }).catch(error => {
                                alert('Có lỗi xảy ra');
                                console.error('Error:', error);
                            }).finally(() => {
                                this.saving = false;
                            });
                        },
                        confirmCancel() {
                            if (!confirm('Bạn có chắc chắn muốn hủy lịch hẹn?')) return;

                            const reason = this.cancelReason.trim() || 'Khách hàng hủy không rõ lý do';

                            this.saving = true;

                            this.showCancelReasonModal = false;
                            this.cancelReason = '';

                            axios.post(
                                `/admin/appointments/${this.editingAppointment.id}/cancel`,
                                {
                                    cancellation_reason: reason
                                }
                            ).then(response => {
                                if (response.data.success) {
                                    alert('Đã hủy lịch hẹn!');
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 300);
                                }
                            }).catch(error => {
                                alert('Có lỗi xảy ra');
                            }).finally(() => {
                                this.saving = false;
                            });
                        },

                        formatTime(datetime) {
                            if (!datetime) return '';
                            const date = new Date(datetime);
                            return date.toLocaleTimeString('vi-VN', {
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                        },

                        formatDateTimeLocal(datetime) {
                            if (!datetime) return '';

                            const date = new Date(datetime);

                            // Lấy offset múi giờ local (phút)
                            const offset = date.getTimezoneOffset();

                            // Tạo date đã điều chỉnh múi giờ
                            const localDate = new Date(date.getTime() - (offset * 60 * 1000));

                            // Trả về định dạng yyyy-MM-ddTHH:mm
                            return localDate.toISOString().slice(0, 16);
                        },

                        getStatusBarStyle(status) {
                            return {
                                backgroundColor: this.getStatusColor(status)
                            };
                        },

                        getStatusColor(status) {
                            const statusColors = {
                                'pending': '#fbbf24',      // Vàng
                                'confirmed': '#10b981',    // Xanh lá
                                'showed': '#3b82f6',       // Xanh dương
                                'completed': '#3b82f6',    // Xanh dương
                                'cancelled': '#ef4444',    // Đỏ
                                'no_show': '#6b7280',      // Xám
                                'scheduled': '#fbbf24',    // Vàng
                                'rescheduled': '#fbbf24'   // Vàng
                            };
                            return statusColors[status] || '#6b7280';
                        },

                        getStatusLabel(status) {
                        const labels = {
                            'scheduled': 'Đã đặt lịch',
                            'rescheduled': 'Đã đổi lịch',
                            'confirmed': 'Đã xác nhận',
                            'showed': 'Đã hoàn thành',
                            'completed': 'Hoàn thành',
                            'cancelled': 'Đã hủy',
                            'no_show': 'Không đến'
                        };
                        return labels[status] || status;
                    },
                        handleMeetingTypeChange() {
                        // Reset các trường không liên quan khi đổi loại
                        if (this.editForm.meeting_type !== 'call') {
                            this.editForm.call_phone = '';
                        }
                        if (this.editForm.meeting_type !== 'online') {
                            this.editForm.meeting_link = '';
                        }
                        if (this.editForm.meeting_type !== 'onsite') {
                            this.editForm.province = '';
                            this.editForm.district = '';
                            this.editForm.ward = '';
                            this.editForm.street_address = '';
                        }
                    },

                    },
                });

                document.addEventListener('DOMContentLoaded', function () {
                const meetingType = document.getElementById('meeting_type');

                if (meetingType) {
                    // Khi load trang Edit, trigger change để hiện đúng input
                    meetingType.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
            </script>

            {{-- Custom Calendar Styles --}}
            <style>
                .calendar-wrapper {
                    position: relative;
                }

                .appointment-event {
                    height: 100%;
                    overflow: hidden;
                    line-height: 1.3;
                    transition: all 0.2s;
                    position: relative;
                    background: white;
                    border-radius: 4px;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                    padding: 6px 8px;
                    cursor: move;
                }

                .dark .appointment-event {
                    background: #374151;
                }

                .appointment-status-bar {
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    height: 3px;
                    border-radius: 4px 4px 0 0;
                }

                .appointment-content {
                    padding-top: 4px;
                    display: flex;
                    flex-direction: column;
                    gap: 6px;
                }

                .appointment-header {
                    border-bottom: 1px solid #e5e7eb;
                    padding-bottom: 4px;
                }

                .dark .appointment-header {
                    border-bottom-color: #4b5563;
                }

                .appointment-customer {
                    font-weight: 700;
                    margin-bottom: 2px;
                    color: #1f2937;
                    font-size: 13px;
                }

                .dark .appointment-customer {
                    color: #f9fafb;
                }

                .appointment-time {
                    font-weight: 600;
                    color: #4b5563;
                    font-size: 11px;
                }

                .dark .appointment-time {
                    color: #d1d5db;
                }

                .appointment-body {
                    display: flex;
                    flex-direction: column;
                    gap: 3px;
                }

                .appointment-info {
                    font-size: 11px;
                    color: #6b7280;
                    display: flex;
                    align-items: center;
                    gap: 4px;
                }

                .dark .appointment-info {
                    color: #9ca3af;
                }

                .vuecal__event {
                    border: none !important;
                    border-left: none !important;
                }

                .vuecal__event:hover .appointment-event {
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    transform: scale(1.02);
                    cursor: pointer;
                    z-index: 5;
                }

                .vuecal__event.vuecal__event--dragging {
                    opacity: 0.7;
                    cursor: move !important;
                }

                .vuecal__event-resize-handle {
                    background-color: rgba(0, 0, 0, 0.1);
                    height: 6px;
                    cursor: ns-resize;
                }

                .vuecal__time-cell {
                    font-weight: 500;
                }
            </style>
        @endPushOnce
    </x-admin::layouts>
