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
                                    :key="c.id"
                                    type="button"
                                    class="flex w-full items-center gap-3 rounded-xl p-3 text-left hover:bg-gray-50 dark:hover:bg-gray-800"
                                    :class="activeId===c.id ? 'bg-indigo-50 dark:bg-indigo-900/20' : ''"
                                    @click="openChat(c.id)"
                                >
                                    <div class="relative flex h-11 w-11 items-center justify-center rounded-full bg-gradient-to-br from-sky-300 to-violet-400 font-bold text-white">
                                        @{{ c.avatar }}

                                        <span
                                            v-if="activeId===c.id"
                                            class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white bg-green-500 dark:border-gray-900"
                                        ></span>

                                        <span
                                            v-else
                                            class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white bg-transparent dark:border-gray-900"
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
                                </button>
                            </div>
                        </div>

                        <!-- RIGHT: Chat -->
                        <div class="col-span-8 flex flex-col min-h-0">
                            <!-- Chat header -->
                            <div class="flex items-center justify-between border-b border-gray-200 p-4 dark:border-gray-800">
                                <div class="flex min-w-0 items-center gap-3">
                                    <div class="relative flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-sky-300 to-violet-400 font-bold text-white">
                                        @{{ activeConvo?.avatar || 'C' }}
                                        <span class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white bg-green-500 dark:border-gray-900"></span>
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
                        activeId: 'c2',
                        draft: '',
                        convos: [
                            { id:'c1', name:'Khu Vườn Trên Mây ☁️', snippet:'Cuộc gọi đang diễn ra', time:'1 phút', type:'group', unread:true, avatar:'KV' },
                            { id:'c2', name:'Chị Xinh Đẹp 😂😂😂', snippet:'Bạn: Dạ', time:'1 giờ', type:'dm', unread:false, avatar:'CX' },
                            { id:'c3', name:'Phi Hùng', snippet:'Cuộc gọi video đã kết thúc.', time:'2 giờ', type:'dm', unread:false, avatar:'PH' },
                            { id:'c4', name:'Cherry Nguyễn', snippet:'Ủ đây chị thấy bảo bị tắt...', time:'3 giờ', type:'dm', unread:true, avatar:'CN' },
                            { id:'c5', name:'Victoria Fitness', snippet:'Dạ vâng. Cảm ơn bạn ạ', time:'9 giờ', type:'group', unread:false, avatar:'VF' },
                        ],
                        messages: {
                            c2: [
                                { from:'in',  text:'Hehe', at:'21:02' },
                                { from:'in',  text:'Béo như cục thịt', at:'21:02' },
                                { from:'out', text:'Dạo này béo à ❤️', at:'21:03' },
                                { from:'in',  text:'Dai zai 😆', at:'21:04' },
                                { from:'in',  text:'Béo', at:'21:04' },
                                { from:'in',  text:'K chui vừa lỗ kia nữa r', at:'21:05' },
                                { from:'in',  text:'Ngủ đây', at:'21:05' },
                                { from:'out', text:'Dạ', at:'21:06' },
                            ],
                            c1: [{ from:'in', text:'(Group) Cuộc gọi đang diễn ra…', at:'20:34' }],
                            c3: [{ from:'in', text:'Cuộc gọi video đã kết thúc.', at:'19:10' }],
                            c4: [{ from:'in', text:'Ủ đây chị thấy bảo bị tắt...', at:'18:22' }],
                            c5: [{ from:'in', text:'Dạ vâng. Cảm ơn bạn ạ', at:'12:05' }],
                        },
                    };
                },

                computed: {
                    filteredConvos() {
                        const q = (this.q || '').toLowerCase().trim();

                        return this.convos.filter(c => {
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
                        return this.convos.find(c => c.id === this.activeId);
                    },

                    activeMessages() {
                        return this.messages[this.activeId] || [];
                    },
                },

                methods: {
                    openChat(id) {
                        this.activeId = id;
                        this.$nextTick(() => this.scrollToBottom());
                    },

                    send() {
                        const text = (this.draft || '').trim();
                        if (!text) return;

                        const now = new Date();
                        const hh = String(now.getHours()).padStart(2, '0');
                        const mm = String(now.getMinutes()).padStart(2, '0');

                        if (!this.messages[this.activeId]) this.messages[this.activeId] = [];

                        this.messages[this.activeId].push({
                            from: 'out',
                            text,
                            at: `${hh}:${mm}`,
                        });

                        const convo = this.convos.find(c => c.id === this.activeId);
                        if (convo) {
                            convo.snippet = `Bạn: ${text}`;
                            convo.time = 'vừa xong';
                            convo.unread = false;
                        }

                        this.draft = '';
                        this.$nextTick(() => this.scrollToBottom());
                    },

                    scrollToBottom() {
                        const el = this.$refs.body;
                        if (!el) return;
                        el.scrollTop = el.scrollHeight;
                    },

                    resetDemo() {
                        window.location.reload();
                    },
                },

                mounted() {
                    this.scrollToBottom();
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
