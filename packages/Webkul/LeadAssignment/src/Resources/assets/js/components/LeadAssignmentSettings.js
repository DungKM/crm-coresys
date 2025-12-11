export default {
    name: "LeadAssignmentSettings",
    props: {
        salesUsers: {
            type: Array,
            required: true,
        },
        leadAssignmentConfig: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            form: {
                enabled:
                    this.leadAssignmentConfig["lead_assignment.enabled"] == 1,
                method:
                    this.leadAssignmentConfig["lead_assignment.method"] ||
                    "round_robin",
            },
            search: "",
            selectedUserIds: this.leadAssignmentConfig[
                "lead_assignment.active_users"
            ]
                ? JSON.parse(
                      this.leadAssignmentConfig["lead_assignment.active_users"]
                  )
                : [],
            userStars: {},
            hoveredStar: {},
        };
    },
    computed: {
        filteredUsers() {
            if (!this.search) return this.salesUsers;
            const s = this.search.toLowerCase();
            return this.salesUsers.filter((u) =>
                (u.name + " " + u.email).toLowerCase().includes(s)
            );
        },
        isAllSelected() {
            return (
                this.filteredUsers.length > 0 &&
                this.filteredUsers.every((u) =>
                    this.selectedUserIds.includes(u.id)
                )
            );
        },
        roundRobinPercent() {
            const count =
                this.selectedUserIds.length || this.filteredUsers.length;
            return count > 0 ? Math.floor(100 / count) : 0;
        },
    },
    mounted() {
        console.log("[LeadAssignmentSettings] Component mounted successfully!");
        console.log("[LeadAssignmentSettings] Props received:", {
            salesUsers: this.salesUsers,
            config: this.leadAssignmentConfig,
        });

        // Initialize user stars after component is mounted
        this.userStars = this.initUserStars();
    },
    methods: {
        initUserStars() {
            console.log("[LeadAssignmentSettings] Initializing user stars...");
            let weights = {};
            try {
                weights = this.leadAssignmentConfig["lead_assignment.weights"]
                    ? JSON.parse(
                          this.leadAssignmentConfig["lead_assignment.weights"]
                      )
                    : {};
            } catch (e) {
                console.error(
                    "[LeadAssignmentSettings] Error parsing weights:",
                    e
                );
                weights = {};
            }
            const stars = {};
            for (const user of this.salesUsers) {
                stars[user.id] = weights[user.id]
                    ? Math.max(
                          1,
                          Math.min(5, Math.round(weights[user.id] / 20))
                      )
                    : 1;
            }
            return stars;
        },
        toggleSelectAll() {
            if (this.isAllSelected) {
                this.selectedUserIds = [];
            } else {
                this.selectedUserIds = this.filteredUsers.map((u) => u.id);
            }
        },
        clearAll() {
            this.selectedUserIds = [];
        },
        setHoveredStar(userId, star) {
            if (star === null) {
                delete this.hoveredStar[userId];
            } else {
                this.hoveredStar[userId] = star;
            }
        },
        setUserStars(userId, stars) {
            console.log("[LeadAssignment] setUserStars called:", userId, stars);
            // Allow deselecting by clicking the same star again (optional UX)
            if (this.userStars[userId] === stars) {
                this.userStars[userId] = 1;
            } else {
                this.userStars[userId] = stars;
            }
            // Clear hover state after click so the actual rating shows
            delete this.hoveredStar[userId];
            console.log("[LeadAssignment] Updated userStars:", this.userStars);
        },
        weightedPercent(userId) {
            const totalStars = this.selectedUserIds.reduce(
                (sum, id) => sum + (this.userStars[id] || 1),
                0
            );
            if (!this.selectedUserIds.includes(userId) || totalStars === 0)
                return 0;
            const stars = this.userStars[userId] || 1;
            return Math.round((stars / totalStars) * 100);
        },
        submitForm() {
            this.$emit("submit", {
                ...this.form,
                active_users: this.selectedUserIds,
                weights: Object.fromEntries(
                    this.selectedUserIds.map((id) => [
                        id,
                        (this.userStars[id] || 1) * 20,
                    ])
                ),
            });
        },
    },
    template: `
        <div>
            <form id="lead-assignment-form" @submit.prevent="submitForm">
                <div class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <!-- Enable Lead Assignment -->
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" v-model="form.enabled" class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500" id="lead-assignment-enabled" />
                            <label for="lead-assignment-enabled" class="text-base font-semibold text-gray-900 dark:text-white cursor-pointer">
                                Bật tính năng chia lead
                            </label>
                        </div>
                        <p class="mt-2 ml-8 text-sm text-gray-600 dark:text-gray-400">
                            Tự động chia lead cho sales theo phương pháp đã chọn.
                        </p>
                    </div>

                    <!-- Assignment Method -->
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                        <label class="block text-base font-semibold text-gray-900 dark:text-white mb-3">
                            Phương pháp chia lead
                        </label>
                        <div class="relative w-full max-w-md">
                            <select v-model="form.method" class="block w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-base shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white transition">
                                <option value="round_robin">Round Robin - Chia đều</option>
                                <option value="weighted">Weighted - Theo phần trăm</option>
                            </select>
                            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="h-5 w-5 text-gray-400 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </div>
                        <div v-if="form.method === 'round_robin'" class="mt-3">
                            <div class="flex items-center gap-3 rounded-md bg-blue-50 border border-blue-100 px-3 py-2 text-sm text-blue-700 dark:bg-blue-900 dark:border-blue-800 dark:text-blue-200">
                                <svg class="h-4 w-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m4-4h.01M12 8v4m0 0v4h.01M12 8V4m0 12h.01" />
                                </svg>
                                <div>
                                    <div class="font-medium">Round Robin chia đều lead cho sales đang bật.</div>
                                    <div class="text-xs text-blue-600 dark:text-blue-300">Mỗi sales nhận ~{{ roundRobinPercent }}% lead</div>
                                </div>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Round Robin: chia luân phiên, Weighted: chia theo phần trăm tuỳ chỉnh.
                        </p>
                    </div>

                    <!-- Active Sales Users -->
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                        <label class="block text-base font-semibold text-gray-900 dark:text-white mb-3">
                            Danh sách sales
                        </label>
                        <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                            Quản lý nhân viên nhận lead và tỉ lệ phân bổ cho từng người.
                        </p>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="relative w-full max-w-xs">
                                <input type="text" v-model="search" placeholder="Tìm kiếm nhân viên..." class="w-full rounded-lg border border-gray-300 bg-white dark:bg-gray-800 dark:border-gray-700 dark:text-white px-4 py-2 pr-10 text-sm shadow focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition" />
                            </div>
                            <div v-if="filteredUsers.length !== salesUsers.length || search" class="text-sm text-gray-600 dark:text-gray-400 mt-0 ml-2">
                                {{ filteredUsers.length }} / {{ salesUsers.length }} kết quả
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400 mt-0 ml-4">
                                Đã chọn: {{ selectedUserIds.length }}
                            </div>
                            <button type="button" class="primary-button" @click="search = search">Tìm kiếm</button>
                            <button type="button" class="primary-button" @click="toggleSelectAll">
                                <span>{{ isAllSelected ? "Bỏ chọn tất cả" : "Chọn tất cả" }}</span>
                            </button>
                        </div>
                        <div v-if="selectedUserIds.length > 0" class="rounded-lg border border-gray-200 bg-white px-4 py-2 mb-3 flex items-center justify-between shadow-sm dark:bg-gray-900 dark:border-gray-800">
                            <div class="flex items-center gap-3">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">Đã chọn: {{ selectedUserIds.length }}</div>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button" class="text-sm text-gray-700 dark:text-gray-300 hover:underline" @click="clearAll">Bỏ chọn tất cả</button>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700 rounded-lg border border-gray-200 dark:border-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">Chọn</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">Tên nhân viên</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">Level/Stars</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">Phần trăm (%)</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-100 dark:divide-gray-800">
                                    <tr v-for="user in filteredUsers" :key="user.id" class="user-row">
                                        <td class="px-4 py-2">
                                            <input type="checkbox" :value="user.id" v-model="selectedUserIds" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 user-checkbox" />
                                        </td>
                                        <td class="px-6 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ user.name }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-500 dark:text-gray-400">{{ user.email }}</td>
                                        <td class="px-6 py-3">
                                            <div 
                                                v-if="form.method === 'weighted' && selectedUserIds.includes(user.id)" 
                                                class="star-rating weights-input flex gap-1"
                                                @mouseleave="setHoveredStar(user.id, null)"
                                            >
                                                <span
                                                    v-for="i in 5"
                                                    :key="i"
                                                    class="star cursor-pointer text-xl select-none transition-colors"
                                                    :style="{ color: i <= (hoveredStar[user.id] !== undefined ? hoveredStar[user.id] : (userStars[user.id] || 1)) ? '#fbbf24' : '#d1d5db' }"
                                                    @mouseenter="setHoveredStar(user.id, i)"
                                                    @click="setUserStars(user.id, i)"
                                                >★</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3">
                                            <span v-if="form.method === 'weighted'">{{ weightedPercent(user.id) }}%</span>
                                            <span v-else>{{ roundRobinPercent }}%</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Weights Management -->
                    <div v-if="form.method === 'weighted'" class="px-6 py-4">
                        <label class="block text-base font-semibold text-gray-900 dark:text-white mb-3">
                            Quản lý tỉ lệ chia
                        </label>
                        <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                            Tổng phần trăm phải bằng 100%. (Tự động tính theo số sao)
                        </p>
                    </div>
                </div>
            </form>
        </div>
    `,
};
