<x-admin::layouts>
    <x-slot:title>
        Instagram CRM
    </x-slot>

    <v-instagram></v-instagram>
    @pushOnce('scripts')
        {{-- ================= TEMPLATE ================= --}}
        <script type="text/x-template" id="v-instagram-template">
            <div class="flex flex-col gap-4">

                <!-- Layout -->
                <div
                    class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden"
                    style="height: calc(100vh - 189px);"
                >
                    <div class="grid h-full grid-cols-12 min-h-0">

                        <!-- ================= LEFT ================= -->
                        <div class="col-span-4 border-r border-gray-200 dark:border-gray-800 flex flex-col min-h-0">
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <div class="text-lg font-bold dark:text-white">
                                        Tin nhắn Instagram
                                    </div>
                                </div>

                                <!-- Search -->
                                <div class="mt-3 flex items-center gap-2 rounded-full bg-gray-100 px-3 py-2 text-sm dark:bg-gray-800">
                                    <span class="opacity-70">🔎</span>
                                    <input
                                        v-model="q"
                                        class="w-full bg-transparent outline-none dark:text-gray-200"
                                        placeholder="Tìm kiếm trên Instagram"
                                    />
                                </div>
                            </div>

                            <!-- Conversation list -->
                            <div class="flex-1 min-h-0 overflow-y-auto px-2 pb-3">
                                <button
                                    v-for="c in filteredConvos"
                                    :key="c.ig_user_id"
                                    type="button"
                                    class="flex w-full items-center gap-3 rounded-xl p-3 text-left hover:bg-gray-50 dark:hover:bg-gray-800"
                                    :class="activeId===c.ig_user_id ? 'bg-indigo-50 dark:bg-indigo-900/20' : ''"
                                    @click="openChat(c.ig_user_id)"
                                >
                                    <!-- Avatar -->
                                    <div class="relative h-11 w-11">
                                        <div class="h-11 w-11 rounded-full overflow-hidden bg-gradient-to-br from-pink-400 to-purple-500 flex items-center justify-center font-bold text-white">
                                            <img
                                                v-if="c.avatar && (''+c.avatar).startsWith('http')"
                                                :src="c.avatar"
                                                class="h-full w-full object-cover"
                                                referrerpolicy="no-referrer"
                                            />
                                            <span v-else>
                                                @{{ (c.username || 'IG').substring(0,2).toUpperCase() }}
                                            </span>
                                        </div>

                                        <!-- online dot -->
                                        <span
                                            class="absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 rounded-full
                                            bg-green-500 border-2 border-white dark:border-gray-900"
                                        ></span>
                                    </div>

                                    <!-- Text -->
                                    <div class="min-w-0 flex-1">
                                        <div class="truncate text-sm font-bold dark:text-white">
                                            @{{ c.username || c.ig_user_id }}
                                        </div>
                                        <div class="truncate text-xs text-gray-500 dark:text-gray-400">
                                            @{{ c.last_snippet || '' }}
                                        </div>
                                    </div>

                                    <div class="text-xs text-gray-400 dark:text-gray-500">
                                        @{{ c.last_time_human || '' }}
                                    </div>

                                    <!-- delete -->
                                    <button
                                        type="button"
                                        class="ml-2 rounded-full px-2 py-1 text-xs text-red-500 hover:bg-red-50"
                                        title="Xóa đoạn chat"
                                        @click.stop="deleteConversation(c.ig_user_id)"
                                    >
                                        🗑️
                                    </button>
                                </button>
                            </div>
                        </div>

                        <!-- ================= RIGHT ================= -->
                        <div class="col-span-8 flex flex-col min-h-0">

                            <!-- Header -->
                            <div class="flex items-center justify-between border-b border-gray-200 p-4 dark:border-gray-800">
                                <div class="flex min-w-0 items-center gap-3">
                                    <div class="relative h-10 w-10">
                                        <div class="h-10 w-10 rounded-full overflow-hidden bg-gradient-to-br from-pink-400 to-purple-500 flex items-center justify-center font-bold text-white">
                                            <img
                                                v-if="activeConvo?.avatar && (''+activeConvo.avatar).startsWith('http')"
                                                :src="activeConvo.avatar"
                                                class="h-full w-full object-cover"
                                                referrerpolicy="no-referrer"
                                            />
                                            <span v-else>
                                                @{{ activeConvo?.username || 'IG' }}
                                            </span>
                                        </div>
                                        <span
                                            class="absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 rounded-full bg-green-500
                                            border-2 border-white dark:border-gray-900"
                                        ></span>
                                    </div>

                                    <div class="min-w-0">
                                        <div class="truncate font-bold dark:text-white">
                                            @{{ activeConvo?.username || 'Instagram' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Đang hoạt động
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Messages -->
                            <div ref="body" class="flex-1 min-h-0 overflow-y-auto p-4">
                                <div v-for="(m, idx) in activeMessages" :key="idx" class="mb-2">
                                    <div class="flex" :class="m.from==='out' ? 'justify-end' : 'justify-start'">
                                        <div
                                            class="max-w-[70%] rounded-2xl px-3 py-2 text-sm"
                                            :class="m.from==='out'
                                                ? 'bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-br-md'
                                                : 'bg-gray-100 dark:bg-gray-800 dark:text-gray-100 rounded-bl-md'"
                                        >
                                            @{{ m.text }}
                                        </div>
                                    </div>
                                    <div class="mt-0.5 text-[11px] text-gray-400" :class="m.from==='out' ? 'text-right' : ''">
                                        @{{ m.at }}
                                    </div>
                                </div>
                            </div>

                            <!-- Composer -->
                            <div class="border-t border-gray-200 p-3 dark:border-gray-800">
                                <div class="flex items-center gap-2">
                                    <div class="flex flex-1 items-center gap-2 rounded-full bg-gray-100 px-3 py-2 dark:bg-gray-800">
                                        <input
                                            v-model="draft"
                                            @keydown.enter.prevent="send"
                                            class="w-full bg-transparent outline-none dark:text-gray-200"
                                            placeholder="Nhập tin nhắn Instagram..."
                                        />
                                    </div>
                                    <button
                                        class="rounded-full bg-gradient-to-r from-pink-500 to-purple-600 px-4 py-2 text-sm font-semibold text-white"
                                        @click="send"
                                    >
                                        ➤
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </script>

        {{-- ================= SCRIPT ================= --}}
        <script type="module">
            app.component('v-instagram', {
                template: '#v-instagram-template',

                data() {
                    return {
                        q: '',
                        activeId: null,
                        draft: '',
                        convos: [],
                        _activeConvo: null,
                        _activeMessages: [],
                        _timer: null,
                    }
                },

                computed: {
                    filteredConvos() {
                        const q = (this.q || '').toLowerCase()
                        return (this.convos || []).filter(c =>
                            !q || (c.username || '').toLowerCase().includes(q)
                        )
                    },
                    activeConvo() { return this._activeConvo },
                    activeMessages() { return this._activeMessages },
                },

                methods: {
                    async fetchConvos() {
                        const res = await fetch('/admin/instagram/conversations')
                        this.convos = await res.json()

                        if (!this.activeId && this.convos.length) {
                            this.openChat(this.convos[0].ig_user_id)
                        }
                    },

                    async openChat(id) {
                        this.activeId = id
                        const res = await fetch(`/admin/instagram/messages?ig_user_id=${id}`)
                        const data = await res.json()
                        this._activeConvo = data.conversation
                        this._activeMessages = data.messages
                        this.$nextTick(() => this.scroll())
                    },

                    async send() {
                        if (!this.draft || !this.activeId) return
                        await fetch('/admin/instagram/send', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({
                                ig_user_id: this.activeId,
                                text: this.draft,
                            }),
                        })
                        this.draft = ''
                        this.openChat(this.activeId)
                    },

                    async deleteConversation(id) {
                        if (!confirm('Xóa đoạn chat Instagram?')) return
                        await fetch('/admin/instagram/conversation', {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({ ig_user_id: id }),
                        })
                        this.activeId = null
                        this.fetchConvos()
                    },

                    scroll() {
                        const el = this.$refs.body
                        if (el) el.scrollTop = el.scrollHeight
                    }
                },

                mounted() {
                    this.fetchConvos()
                    this._timer = setInterval(this.fetchConvos, 4000)
                },

                beforeUnmount() {
                    clearInterval(this._timer)
                },
            })
        </script>
    @endPushOnce
</x-admin::layouts>
