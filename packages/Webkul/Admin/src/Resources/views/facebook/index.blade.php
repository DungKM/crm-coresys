<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.facebook.index.title')
    </x-slot>

    <v-facebook></v-facebook>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-facebook-template">
            <div class="flex flex-col gap-4">
                <!-- Header -->
              
                <!-- Messenger Layout (FIXED HEIGHT + INTERNAL SCROLL) -->
                <div
                    class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden"
                    style="height: calc(100vh - 189px);"
                >
                    <div class="grid h-full grid-cols-12 min-h-0">
                        <!-- LEFT: Conversations -->
                        <div class="col-span-4 border-r border-gray-200 dark:border-gray-800 flex flex-col min-h-0">
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <div class="text-lg font-bold dark:text-white">Đoạn chat</div>

                                    <div class="flex items-center gap-2">
                                        <button
                                            class="rounded-full border border-gray-200 bg-white px-3 py-2 text-sm hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-800"
                                            type="button"
                                            title="Tùy chọn"
                                        >
                                            ⋯
                                        </button>
                                        <button
                                            class="rounded-full border border-gray-200 bg-white px-3 py-2 text-sm hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-800"
                                            type="button"
                                            title="Soạn"
                                        >
                                            ✎
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-3 flex items-center gap-2 rounded-full bg-gray-100 px-3 py-2 text-sm dark:bg-gray-800">
                                    <span class="opacity-70">🔎</span>

                                    <input
                                        v-model="q"
                                        class="w-full bg-transparent outline-none dark:text-gray-200"
                                        placeholder="Tìm kiếm trên Messenger"
                                    />
                                </div>

                                <div class="mt-3 flex flex-wrap gap-2">
                                    <button
                                        type="button"
                                        class="rounded-full px-3 py-1.5 text-xs"
                                        :class="filter==='all'
                                            ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-200'
                                            : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700'"
                                        @click="filter='all'"
                                    >
                                        Tất cả
                                    </button>

                                    <button
                                        type="button"
                                        class="rounded-full px-3 py-1.5 text-xs"
                                        :class="filter==='unread'
                                            ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-200'
                                            : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700'"
                                        @click="filter='unread'"
                                    >
                                        Chưa đọc
                                    </button>

                                    <button
                                        type="button"
                                        class="rounded-full px-3 py-1.5 text-xs"
                                        :class="filter==='group'
                                            ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-200'
                                            : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700'"
                                        @click="filter='group'"
                                    >
                                        Nhóm
                                    </button>
                                </div>
                            </div>

                            <!-- ✅ internal scroll list -->
                            <div class="flex-1 min-h-0 overflow-y-auto px-2 pb-3">
                                <button
                                    v-for="c in filteredConvos"
                                    :key="c.psid"
                                    type="button"
                                    class="flex w-full items-center gap-3 rounded-xl p-3 text-left hover:bg-gray-50 dark:hover:bg-gray-800"
                                    :class="activeId===c.psid ? 'bg-indigo-50 dark:bg-indigo-900/20' : ''"
                                    @click="openChat(c.psid)"
                                >
                                    <div class="relative h-11 w-11">
                                        <!-- Avatar circle (crop ảnh) -->
                                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-gradient-to-br from-sky-300 to-violet-400 font-bold text-white overflow-hidden">
                                            <img
                                                v-if="c.avatar && ('' + c.avatar).startsWith('http')"
                                                :src="c.avatar"
                                                class="h-full w-full object-cover"
                                                referrerpolicy="no-referrer"
                                            />
                                            <span v-else>@{{ c.avatar }}</span>
                                        </div>

                                        <!-- Status dot (nằm ngoài overflow-hidden) -->
                                        <span
                                            v-if="activeId===c.psid"
                                            class="absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 rounded-full bg-green-500 border-2 border-white dark:border-gray-900"
                                        ></span>

                                        <span
                                            v-else
                                             class="absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 rounded-full bg-green-500 border-2 border-white dark:border-gray-900"
                                        ></span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="truncate text-sm font-bold dark:text-white">
                                            @{{ c.name }}
                                        </div>
                                        <div class="truncate text-xs text-gray-500 dark:text-gray-400">
                                            @{{ c.snippet }}
                                        </div>
                                    </div>

                                    <div class="text-xs text-gray-400 dark:text-gray-500">
                                        @{{ c.time }}
                                    </div>
                                    <button
                                    type="button"
                                    class="ml-2 rounded-full px-2 py-1 text-xs text-red-500 hover:bg-red-50"
                                    title="Xóa đoạn chat"
                                    @click.stop="deleteConversation(c.psid)"
                                    >
                                    🗑️
                                    </button>
                                </button>
                            </div>
                        </div>

                        <!-- RIGHT: Chat -->
                        <div class="col-span-8 flex flex-col min-h-0">
                            <!-- Chat header -->
                            <div class="flex items-center justify-between border-b border-gray-200 p-4 dark:border-gray-800">
                                <div class="flex min-w-0 items-center gap-3">
                                    <div class="relative h-10 w-10">
                                        <!-- vòng tròn avatar (crop ảnh) -->
                                        <div class="h-10 w-10 rounded-full overflow-hidden bg-gradient-to-br from-sky-300 to-violet-400 flex items-center justify-center font-bold text-white">
                                            <img
                                                v-if="activeConvo?.avatar && ('' + activeConvo.avatar).startsWith('http')"
                                                :src="activeConvo.avatar"
                                                class="h-full w-full object-cover"
                                                referrerpolicy="no-referrer"
                                            />
                                            <span v-else>@{{ activeConvo?.avatar || 'C' }}</span>
                                        </div>

                                        <!-- chấm trạng thái (nằm ngoài overflow-hidden) -->
                                        <span
                                            class="absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 rounded-full bg-green-500
                                                border-2 border-white dark:border-gray-900"
                                        ></span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="truncate font-bold dark:text-white">
                                            @{{ activeConvo?.name || 'Chat' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Đang hoạt động
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <button class="rounded-full border border-gray-200 bg-white px-3 py-2 text-sm text-red-500 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-800" type="button" title="Gọi">📞</button>
                                    <button class="rounded-full border border-gray-200 bg-white px-3 py-2 text-sm text-red-500 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-800" type="button" title="Video">🎥</button>
                                    <button class="rounded-full border border-gray-200 bg-white px-3 py-2 text-sm text-red-500 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-800" type="button" title="Thông tin">ℹ️</button>
                                </div>
                            </div>

                            <!-- ✅ messages scroll inside -->
                            <div ref="body" class="flex-1 min-h-0 overflow-y-auto p-4">
                                <div v-for="(m, idx) in activeMessages" :key="idx" class="mb-2">
                                    <div class="flex" :class="m.from==='out' ? 'justify-end' : 'justify-start'">
                                        <div
                                            class="max-w-[70%] rounded-2xl px-3 py-2 text-sm leading-relaxed"
                                            :class="m.from==='out'
                                                ? 'bg-violet-600 text-white rounded-br-md'
                                                : 'bg-gray-100 text-gray-900 border border-gray-200 rounded-bl-md dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700'"
                                        >
                                            @{{ m.text }}
                                        </div>
                                    </div>

                                    <div class="mt-0.5 flex text-[11px] text-gray-400" :class="m.from==='out' ? 'justify-end' : 'justify-start'">
                                        @{{ m.at }}
                                    </div>
                                </div>
                            </div>

                            <!-- Composer -->
                            <div class="border-t border-gray-200 p-3 dark:border-gray-800">
                                <div class="flex items-center gap-2">
                                    <button class="rounded-full border border-gray-200 bg-white px-3 py-2 text-sm hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-800" type="button" title="Mic">🎙️</button>
                                    <button class="rounded-full border border-gray-200 bg-white px-3 py-2 text-sm hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-800" type="button" title="Ảnh">🖼️</button>
                                    <button class="rounded-full border border-gray-200 bg-white px-3 py-2 text-xs hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-800" type="button" title="GIF">GIF</button>

                                    <div class="flex flex-1 items-center gap-2 rounded-full bg-gray-100 px-3 py-2 dark:bg-gray-800">
                                        <span class="text-gray-400">Aa</span>
                                        <input
                                            v-model="draft"
                                            @keydown.enter.prevent="send()"
                                            class="w-full bg-transparent outline-none dark:text-gray-200"
                                            placeholder="Nhập tin nhắn..."
                                        />
                                    </div>

                                    <button
                                        class="rounded-full bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:brightness-95"
                                        type="button"
                                        @click="send()"
                                        title="Gửi"
                                    >
                                        ➤
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- /RIGHT -->
                    </div>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-facebook', {
            template: '#v-facebook-template',

            data() {
                return {
                q: '',
                filter: 'all',
                activeId: null,        // psid
                draft: '',

                convos: [],
                _activeConvo: null,
                _activeMessages: [],

                _timer: null,
                };
            },

            computed: {
                filteredConvos() {
                const q = (this.q || '').toLowerCase().trim();

                return (this.convos || []).filter(c => {
                    if (this.filter === 'unread' && !c.unread) return false;
                    if (this.filter === 'group' && c.type !== 'group') return false;

                    if (q) {
                    const name = (c.name || '').toLowerCase();
                    const snip = (c.snippet || '').toLowerCase();
                    if (!name.includes(q) && !snip.includes(q)) return false;
                    }
                    return true;
                });
                },

                activeConvo() {
                return this._activeConvo;
                },

                activeMessages() {
                return this._activeMessages;
                },
            },

            methods: {
                async safeJson(res) {
                    const ct = res.headers.get('content-type') || '';
                    if (!ct.includes('application/json')) {
                    const html = await res.text();
                    console.error('Non-JSON response:', res.status, html);
                    throw new Error('API returned HTML (likely redirected to login or 404)');
                    }
                    return res.json();
                },

                async fetchConvos() {
                    const res = await fetch(`/admin/facebook/conversations?q=${encodeURIComponent(this.q || '')}`);
                    this.convos = await res.json();

                    if (!this.activeId && this.convos.length) {
                        await this.openChat(this.convos[0].psid);
                    }
                },
                async deleteConversation(psid = null) {
                    const target = psid || this.activeId;
                    if (!target) return;

                    if (!confirm('Xóa đoạn chat này trong CRM?')) return;

                    const res = await fetch('/admin/facebook/conversation', {
                        method: 'DELETE',
                        credentials: 'same-origin',
                        headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        },
                        body: JSON.stringify({ psid: target }),
                    });

                    const data = await this.safeJson(res);
                    if (!res.ok || !data.ok) {
                        alert(data?.message || 'Xóa thất bại');
                        return;
                    }

                    // nếu đang mở đúng chat bị xóa thì reset
                    if (this.activeId === target) {
                        this.activeId = null;
                        this._activeConvo = null;
                        this._activeMessages = [];
                    }

                    await this.fetchConvos();
                },
                async openChat(psid) {
                    this.activeId = psid;

                    const res = await fetch(`/admin/facebook/messages?psid=${encodeURIComponent(psid)}`, {
                    credentials: 'same-origin',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    });

                    const data = await this.safeJson(res);

                    this._activeConvo = data.conversation;
                    this._activeMessages = data.messages;

                    this.$nextTick(() => this.scrollToBottom());
                },

                async send() {
                    const text = (this.draft || '').trim();
                    if (!text || !this.activeId) return;

                    const res = await fetch('/admin/facebook/send', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    },
                    body: JSON.stringify({ psid: this.activeId, text }),
                    });

                    const data = await this.safeJson(res);
                    if (!res.ok) throw new Error(data?.message || 'Send failed');

                    this.draft = '';
                    await this.openChat(this.activeId); // reload messages
                },
                scrollToBottom() {
                const el = this.$refs.body;
                if (!el) return;
                el.scrollTop = el.scrollHeight;
                },
            },

            mounted() {
                this.fetchConvos();

                // cập nhật liên tục để thấy tin mới (webhook lưu DB -> UI pull)
                this._timer = setInterval(async () => {
                await this.fetchConvos();

                if (this.activeId) {
                    const res = await fetch(`/admin/facebook/messages?psid=${encodeURIComponent(this.activeId)}`);
                    const data = await res.json();
                    this._activeConvo = data.conversation;
                    this._activeMessages = data.messages;
                    this.$nextTick(() => this.scrollToBottom());
                }
                }, 3000);
            },

            beforeUnmount() {
                clearInterval(this._timer);
            },
            });

        </script>
    @endPushOnce
</x-admin::layouts>
