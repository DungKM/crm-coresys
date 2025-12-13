const i={name:"LeadAssignmentSettings",props:{salesUsers:{type:Array,required:!0},leadAssignmentConfig:{type:Object,required:!0},storeUrl:{type:String,required:!0},csrfToken:{type:String,required:!0},translations:{type:Object,required:!0}},data(){let e=[];try{const t=this.leadAssignmentConfig["lead_assignment.active_users"];t&&(e=typeof t=="string"?JSON.parse(t):t)}catch(t){console.error("[LeadAssignmentSettings] Error parsing active_users:",t)}return{form:{enabled:this.leadAssignmentConfig["lead_assignment.enabled"]==1,method:this.leadAssignmentConfig["lead_assignment.method"]||"round_robin"},search:"",selectedUserIds:Array.isArray(e)?e.map(t=>parseInt(t)):[],userStars:{},hoveredStar:{}}},computed:{filteredUsers(){if(!this.search)return this.salesUsers;const e=this.search.toLowerCase();return this.salesUsers.filter(t=>(t.name+" "+t.email).toLowerCase().includes(e))},isAllSelected(){return this.filteredUsers.length>0&&this.filteredUsers.every(e=>this.selectedUserIds.includes(e.id))},roundRobinPercent(){const e=this.selectedUserIds.length||this.filteredUsers.length;return e>0?Math.floor(100/e):0}},mounted(){console.log("[LeadAssignmentSettings] Component mounted successfully!"),console.log("[LeadAssignmentSettings] Props received:",{salesUsers:this.salesUsers,config:this.leadAssignmentConfig}),this.userStars=this.initUserStars()},methods:{initUserStars(){console.log("[LeadAssignmentSettings] Initializing user stars...");let e={};try{e=this.leadAssignmentConfig["lead_assignment.weights"]?JSON.parse(this.leadAssignmentConfig["lead_assignment.weights"]):{}}catch(s){console.error("[LeadAssignmentSettings] Error parsing weights:",s),e={}}const t={};for(const s of this.salesUsers)t[s.id]=e[s.id]?Math.max(1,Math.min(5,parseInt(e[s.id]))):1;return t},toggleSelectAll(){this.isAllSelected?this.selectedUserIds=[]:this.selectedUserIds=this.filteredUsers.map(e=>e.id)},clearAll(){this.selectedUserIds=[]},setHoveredStar(e,t){t===null?delete this.hoveredStar[e]:this.hoveredStar[e]=t},setUserStars(e,t){console.log("[LeadAssignment] setUserStars called:",e,t),this.userStars[e]===t?this.userStars[e]=1:this.userStars[e]=t,delete this.hoveredStar[e],console.log("[LeadAssignment] Updated userStars:",this.userStars)},weightedPercent(e){const t=this.selectedUserIds.reduce((r,a)=>r+(this.userStars[a]||1),0);if(!this.selectedUserIds.includes(e)||t===0)return 0;const s=this.userStars[e]||1;return Math.round(s/t*100)}},template:`
        <div>
            <form id="lead-assignment-form" method="POST" :action="storeUrl">
                <input type="hidden" name="_token" :value="csrfToken" />
                <div class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <!-- Enable Lead Assignment -->
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" v-model="form.enabled" class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500" id="lead-assignment-enabled" />
                            <input type="hidden" name="enabled" :value="form.enabled ? 1 : 0" />
                            <label for="lead-assignment-enabled" class="text-base font-semibold text-gray-900 dark:text-white cursor-pointer">
                                {{ translations.enableFeature }}
                            </label>
                        </div>
                        <p class="mt-2 ml-8 text-sm text-gray-600 dark:text-gray-400">
                            {{ translations.enableDescription }}
                        </p>
                    </div>

                    <!-- Assignment Method -->
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                        <label class="block text-base font-semibold text-gray-900 dark:text-white mb-3">
                            {{ translations.methodLabel }}
                        </label>
                        <div class="relative w-full max-w-md">
                            <select v-model="form.method" name="method" class="block w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-base shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white transition">
                                <option value="round_robin">{{ translations.roundRobinOption }}</option>
                                <option value="weighted">{{ translations.weightedOption }}</option>
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
                                    <div class="font-medium">{{ translations.roundRobinInfo }}</div>
                                    <div class="text-xs text-blue-600 dark:text-blue-300">{{ translations.roundRobinCalc.replace(':percent', roundRobinPercent) }}</div>
                                </div>
                            </div>
                        </div>
                        <div v-if="form.method === 'weighted'" class="mt-3">
                            <div class="flex items-center gap-3 rounded-md bg-purple-50 border border-purple-100 px-3 py-2 text-sm text-purple-700 dark:bg-purple-900 dark:border-purple-800 dark:text-purple-200">
                                <svg class="h-4 w-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                                <div>
                                    <div class="font-medium">{{ translations.weightedInfo }}</div>
                                    <div class="text-xs text-purple-600 dark:text-purple-300">{{ translations.weightedCalc }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Sales Users -->
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                        <label class="block text-base font-semibold text-gray-900 dark:text-white mb-3">
                            {{ translations.salesListLabel }}
                        </label>
                        <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                            {{ translations.salesListDescription }}
                        </p>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="relative w-full max-w-xs">
                                <input type="text" v-model="search" :placeholder="translations.searchPlaceholder" class="w-full rounded-lg border border-gray-300 bg-white dark:bg-gray-800 dark:border-gray-700 dark:text-white px-4 py-2 pr-10 text-sm shadow focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition" />
                            </div>
                            <div v-if="filteredUsers.length !== salesUsers.length || search" class="text-sm text-gray-600 dark:text-gray-400 mt-0 ml-2">
                                {{ filteredUsers.length }} / {{ salesUsers.length }} {{ translations.results }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400 mt-0 ml-4">
                                {{ translations.selected }}: {{ selectedUserIds.length }}
                            </div>
                            <button type="button" class="primary-button" @click="search = search">{{ translations.searchButton }}</button>
                            <button type="button" class="primary-button" @click="toggleSelectAll">
                                <span>{{ isAllSelected ? translations.deselectAll : translations.selectAll }}</span>
                            </button>
                        </div>
                        <div v-if="selectedUserIds.length > 0" class="rounded-lg border border-gray-200 bg-white px-4 py-2 mb-3 flex items-center justify-between shadow-sm dark:bg-gray-900 dark:border-gray-800">
                            <div class="flex items-center gap-3">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ translations.selected }}: {{ selectedUserIds.length }}</div>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button" class="text-sm text-gray-700 dark:text-gray-300 hover:underline" @click="clearAll">{{ translations.clearAll }}</button>
                            </div>
                        </div>
                        <div class="overflow-x-auto overflow-y-auto max-h-[65vh] rounded-lg border border-gray-200 dark:border-gray-700">
                            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 bg-gray-50 dark:bg-gray-800">{{ translations.selectColumn }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 bg-gray-50 dark:bg-gray-800">{{ translations.nameColumn }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 bg-gray-50 dark:bg-gray-800">{{ translations.emailColumn }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 bg-gray-50 dark:bg-gray-800">{{ translations.levelColumn }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 bg-gray-50 dark:bg-gray-800">{{ translations.percentColumn }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-100 dark:divide-gray-800">
                                    <tr v-for="user in filteredUsers" :key="user.id" class="user-row">
                                        <td class="px-4 py-2">
                                            <input type="checkbox" :value="user.id" v-model="selectedUserIds" name="active_users[]" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 user-checkbox" />
                                        </td>
                                        <td class="px-6 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ user.name }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-500 dark:text-gray-400">{{ user.email }}</td>
                                        <td class="px-6 py-3">
                                            <div 
                                                v-if="form.method === 'weighted' && selectedUserIds.includes(user.id)" 
                                                class="star-rating weights-input flex gap-1"
                                                @mouseleave="setHoveredStar(user.id, null)"
                                            >
                                                <svg
                                                    v-for="i in 5"
                                                    :key="i"
                                                    class="star cursor-pointer w-6 h-6 transition-colors"
                                                    :style="{ color: i <= (hoveredStar[user.id] !== undefined ? hoveredStar[user.id] : (userStars[user.id] || 1)) ? '#fbbf24' : '#d1d5db' }"
                                                    @mouseenter="setHoveredStar(user.id, i)"
                                                    @click="setUserStars(user.id, i)"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 24 24"
                                                    fill="currentColor"
                                                >
                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                </svg>
                                            </div>
                                            <input v-if="form.method === 'weighted' && selectedUserIds.includes(user.id)" type="hidden" :name="'weights[' + user.id + ']'" :value="userStars[user.id] || 1" />
                                        </td>
                                        <td class="px-6 py-3">
                                            <span v-if="form.method === 'weighted'">{{ weightedPercent(user.id) }}%</span>
                                            <span v-else-if="selectedUserIds.includes(user.id)">{{ roundRobinPercent }}%</span>
                                            <span v-else class="text-gray-400">-</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Weights Management -->
                    <div v-if="form.method === 'weighted'" class="px-6 py-4">
                        <label class="block text-base font-semibold text-gray-900 dark:text-white mb-3">
                            {{ translations.weightsLabel }}
                        </label>
                        <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                            {{ translations.weightsDescription }}
                        </p>
                    </div>
                </div>
            </form>
        </div>
    `};window.app?(console.log("[LeadAssignment] Registering component to window.app"),window.app.component("lead-assignment-settings",i)):console.warn("[LeadAssignment] window.app not found, component registration delayed");
