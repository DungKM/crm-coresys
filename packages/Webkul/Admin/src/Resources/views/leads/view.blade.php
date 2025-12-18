<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.leads.view.title', ['title' => $lead->title])
    </x-slot>

    {{-- Custom CSS for chat message text visibility --}}
    <style>
        /* Outgoing messages - white text on blue background */
        .chat-message-outgoing, .chat-message-outgoing span, .chat-message-outgoing p { color: #ffffff !important; }
        html.dark .chat-message-outgoing, html.dark .chat-message-outgoing span, html.dark .chat-message-outgoing p { color: #ffffff !important; }
        /* Incoming messages - dark text on light background, light on dark */
        .chat-message-incoming, .chat-message-incoming span, .chat-message-incoming p { color: #1f2937 !important; }
        html.dark .chat-message-incoming, html.dark .chat-message-incoming span, html.dark .chat-message-incoming p { color: #e5e7eb !important; }
    </style>

    <!-- Content -->
    <div class="relative flex gap-4 max-lg:flex-wrap">
        <!-- Left Panel -->
        {!! view_render_event('admin.leads.view.left.before', ['lead' => $lead]) !!}

        <div
            class="max-lg:min-w-full max-lg:max-w-full [&>div:last-child]:border-b-0 lg:sticky lg:top-[73px] flex min-w-[394px] max-w-[394px] flex-col self-start rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <!-- Lead Information -->
            <div class="flex w-full flex-col gap-2 border-b border-gray-200 p-4 dark:border-gray-800">
                <!-- Breadcrumb's -->
                <div class="flex items-center justify-between">
                    <x-admin::breadcrumbs name="leads.view" :entity="$lead" />
                </div>

                <div class="mb-2">
                    @if (($days = $lead->rotten_days) > 0)
                        @php
                            $lead->tags->prepend([
                                'name' =>
                                    '<span class="icon-rotten text-base"></span>' .
                                    trans('admin::app.leads.view.rotten-days', ['days' => $days]),
                                'color' => '#FEE2E2',
                            ]);
                        @endphp
                    @endif

                    {!! view_render_event('admin.leads.view.tags.before', ['lead' => $lead]) !!}

                    <!-- Tags -->
                    <x-admin::tags :attach-endpoint="route('admin.leads.tags.attach', $lead->id)" :detach-endpoint="route('admin.leads.tags.detach', $lead->id)" :added-tags="$lead->tags" />

                    {!! view_render_event('admin.leads.view.tags.after', ['lead' => $lead]) !!}
                </div>


                {!! view_render_event('admin.leads.view.title.before', ['lead' => $lead]) !!}

                <!-- Title -->
                <h1 class="text-lg font-bold dark:text-white">
                    {{ $lead->title }}
                </h1>

                {!! view_render_event('admin.leads.view.title.after', ['lead' => $lead]) !!}

                <!-- Activity Actions -->
                <div class="flex flex-wrap gap-2">
                    {!! view_render_event('admin.leads.view.actions.before', ['lead' => $lead]) !!}

                    @if (bouncer()->hasPermission('mail.compose'))
                        <!-- Mail Activity Action -->
                        <x-admin::activities.actions.mail :entity="$lead" entity-control-name="lead_id" />
                    @endif

                    @if (bouncer()->hasPermission('activities.create'))
                        <!-- File Activity Action -->
                        <x-admin::activities.actions.file :entity="$lead" entity-control-name="lead_id" />

                        <!-- Note Activity Action -->
                        <x-admin::activities.actions.note :entity="$lead" entity-control-name="lead_id" />

                        <!-- Activity Action -->
                        <x-admin::activities.actions.activity :entity="$lead" entity-control-name="lead_id" />
                    @endif

                    <!-- Chat Button -->
                    <a href="{{ route('admin.leads.chat.index', $lead->id) }}"
                        class="secondary-button flex items-center gap-1">
                        <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                        </svg>
                        <span>Chat</span>
                    </a>

                    {!! view_render_event('admin.leads.view.actions.after', ['lead' => $lead]) !!}
                </div>
            </div>

            <!-- Lead Attributes -->
            @include ('admin::leads.view.attributes')

            <!-- Contact Person -->
            @include ('admin::leads.view.person')
        </div>

        {!! view_render_event('admin.leads.view.left.after', ['lead' => $lead]) !!}

        {!! view_render_event('admin.leads.view.right.before', ['lead' => $lead]) !!}

        <!-- Right Panel -->
        <div class="flex w-full flex-col gap-4 rounded-lg">
            @include ('admin::leads.view.stages')

            {!! view_render_event('admin.leads.view.activities.before', ['lead' => $lead]) !!}

            {{-- 
                🔴 SỬA ĐỔI: Thêm tab WhatsApp vào danh sách extra-types 
            --}}
            <x-admin::activities :endpoint="route('admin.leads.activities.index', $lead->id)" :email-detach-endpoint="route('admin.leads.emails.detach', $lead->id)" :activeType="request()->query('from') ?? 'all'" :extra-types="[
                ['name' => 'whatsapp', 'label' => '💬 WhatsApp'],
                ['name' => 'description', 'label' => trans('admin::app.leads.view.tabs.description')],
                ['name' => 'products', 'label' => trans('admin::app.leads.view.tabs.products')],
                ['name' => 'quotes', 'label' => trans('admin::app.leads.view.tabs.quotes')],
            ]">
                <x-slot:whatsapp>
                    <div class="bg-white dark:bg-gray-900 rounded-b-lg border-t border-gray-200 dark:border-gray-800">

                        {{-- KHU VỰC HIỂN THỊ LỊCH SỬ CHAT --}}
                        <div class="chat-history flex flex-col gap-3 p-4 h-[400px] overflow-y-auto bg-gray-50 dark:bg-gray-950"
                            id="chat-scroll-area">
                            @php
                                // Lọc lấy các activity là whatsapp và sắp xếp cũ nhất lên đầu
                                $whatsappLogs = $lead->activities->where('type', 'whatsapp')->sortBy('created_at');
                                // Debug: Log số lượng tin nhắn
                                \Log::info('[DEBUG View] Total WhatsApp activities: ' . $whatsappLogs->count());
                                \Log::info(
                                    '[DEBUG View] Activities types: ' . $lead->activities->pluck('type')->toJson(),
                                );
                            @endphp

                            @if ($whatsappLogs->isEmpty())
                                <div class="text-center text-gray-400 italic mt-10">
                                    Chưa có tin nhắn nào.
                                    {{-- Debug info --}}
                                    <div class="text-xs mt-2">
                                        (Tổng số activities: {{ $lead->activities->count() }})
                                    </div>
                                </div>
                            @else
                                @foreach ($whatsappLogs as $log)
                                    @php
                                        // Logic phân biệt tin nhắn ĐẾN và ĐI dựa vào tiêu đề chúng ta đã lưu trong Controller
                                        // 'Tin nhắn WhatsApp đến' -> Khách hàng (Bên trái)
                                        // 'Gửi WhatsApp (Thủ công)' -> Sale (Bên phải)
                                        // SỬA: Kiểm tra cả tiếng Việt và tiếng Anh để tránh lỗi encoding
                                        $isIncoming =
                                            str_contains($log->title, 'đến') ||
                                            str_contains(strtolower($log->title), 'incoming');
                                    @endphp

                                    <div class="flex flex-col {{ $isIncoming ? 'items-start' : 'items-end' }}">

                                        <span class="text-xs text-gray-500 dark:text-gray-400 mb-1 px-1">
                                            {{ $isIncoming ? 'Khách hàng' : $log->user->name ?? 'Bạn' }}
                                        </span>

                                        <div
                                            class="max-w-[80%] rounded-lg px-4 py-2 text-sm shadow-sm {{ $isIncoming ? 'chat-message-incoming bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-tl-none' : 'chat-message-outgoing bg-blue-600 dark:bg-blue-700 rounded-tr-none' }}">
                                            <span>{{ $log->comment }}</span>
                                        </div>

                                        <span class="text-[10px] text-gray-400 dark:text-gray-500 mt-1 px-1">
                                            {{ $log->created_at->format('H:i d/m/Y') }}
                                        </span>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        {{-- KHU VỰC NHẬP TIN NHẮN --}}
                        <div class="p-4 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
                            <div id="whatsapp-status" class="mb-2 text-sm font-medium h-5"></div>

                            <form id="whatsapp-reply-form" class="flex flex-col gap-3">
                                @csrf
                                <input type="hidden" name="lead_id" value="{{ $lead->id }}">

                                <div class="relative">
                                    <textarea
                                        class="w-full rounded-md border border-gray-300 pl-3 pr-12 py-3 text-sm text-gray-600 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 shadow-sm"
                                        id="whatsapp-message" name="message" rows="2" placeholder="Nhập tin nhắn..."
                                        onkeydown="if(event.keyCode==13 && !event.shiftKey) { event.preventDefault(); sendWhatsApp(); }"></textarea>

                                    <button type="button" onclick="sendWhatsApp()"
                                        class="absolute right-2 bottom-2 p-2 text-blue-600 hover:text-blue-800 transition-colors"
                                        title="Gửi tin nhắn (Enter)">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            class="w-6 h-6">
                                            <path
                                                d="M3.478 2.405a.75.75 0 00-.926.94l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.405z" />
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <script>
                            // Tự động cuộn xuống cuối đoạn chat khi tải trang
                            document.addEventListener("DOMContentLoaded", function() {
                                var chatArea = document.getElementById("chat-scroll-area");
                                if (chatArea) {
                                    chatArea.scrollTop = chatArea.scrollHeight;
                                }
                            });

                            function sendWhatsApp() {
                                let messageField = document.getElementById('whatsapp-message');
                                let message = messageField.value;
                                let leadId = {{ $lead->id }};
                                let csrf = document.querySelector('input[name="_token"]').value;
                                let statusDiv = document.getElementById('whatsapp-status');

                                if (!message.trim()) return;

                                // Hiệu ứng UX: Disable nút và hiện đang gửi
                                messageField.disabled = true;
                                statusDiv.innerHTML =
                                    '<span class="text-blue-500 flex items-center gap-1"><span class="animate-spin h-3 w-3 border-2 border-blue-500 border-t-transparent rounded-full"></span> Đang gửi...</span>';

                                fetch(`/admin/leads/${leadId}/whatsapp-reply`, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': csrf,
                                            'Accept': 'application/json'
                                        },
                                        body: JSON.stringify({
                                            message: message,
                                            phone_number: document.querySelector('select[name="phone_number"]')?.value || ''
                                        })
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        messageField.disabled = false;
                                        messageField.focus();

                                        if (data.success) {
                                            statusDiv.innerHTML = '';
                                            messageField.value = '';

                                            // Append tin nhắn mới vào chat luôn để ko cần reload (UX tốt hơn)
                                            let chatArea = document.getElementById("chat-scroll-area");
                                            let now = new Date();
                                            let timeString = now.getHours() + ":" + String(now.getMinutes()).padStart(2, '0') + " " + now
                                                .getDate() + "/" + (now.getMonth() + 1) + "/" + now.getFullYear();

                                            let newBubble = `
                            <div class="flex flex-col items-end">
                                <span class="text-xs text-gray-500 mb-1 px-1">Bạn (Vừa xong)</span>
                                <div class="max-w-[80%] rounded-lg px-4 py-2 text-sm shadow-sm chat-message-outgoing bg-blue-600 rounded-tr-none">
                                    <span>${message.replace(/\n/g, '<br>')}</span>
                                </div>
                                <span class="text-[10px] text-gray-400 mt-1 px-1">${timeString}</span>
                            </div>
                        `;

                                            chatArea.insertAdjacentHTML('beforeend', newBubble);
                                            chatArea.scrollTop = chatArea.scrollHeight;

                                            // Reload ngầm sau 2s để đồng bộ activity ID chuẩn từ server
                                            // setTimeout(() => location.reload(), 2000); 
                                        } else {
                                            statusDiv.innerHTML = '<span class="text-red-600">❌ ' + (data.message || 'Lỗi gửi tin') +
                                                '</span>';
                                        }
                                    })
                                    .catch(error => {
                                        messageField.disabled = false;
                                        console.error('Error:', error);
                                        statusDiv.innerHTML = '<span class="text-red-600">❌ Lỗi kết nối.</span>';
                                    });
                            }
                        </script>
                    </div>
                </x-slot:whatsapp>

                <x-slot:products>
                    @include ('admin::leads.view.products')
                </x-slot:products>

                <x-slot:quotes>
                    @include ('admin::leads.view.quotes')
                </x-slot:quotes>

                <x-slot:description>
                    <div class="p-4 dark:text-white">
                        {{ $lead->description }}
                    </div>
                </x-slot:description>
            </x-admin::activities>

            {!! view_render_event('admin.leads.view.activities.after', ['lead' => $lead]) !!}
        </div>

        {!! view_render_event('admin.leads.view.right.after', ['lead' => $lead]) !!}
    </div>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-whatsapp-activity-action-template">
            <div>
                <slot name="body" :open="() => $refs.whatsappModal.open()"></slot>
    
                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, call)">
                        <x-admin::modal ref="whatsappModal">
                            <x-slot:header>
                                <h3 class="text-lg font-bold">
                                    <i class="icon-whatsapp mr-2"></i>
                                    @lang('admin::app.leads.send-whatsapp-message.title')
                                </h3>
                            </x-slot:header>
    
                            <x-slot:content>
                                <div class="p-4">
                                    @if ($lead->person && $lead->person->contact_numbers && count($lead->person->contact_numbers) > 0)
                                        <!-- Message Input -->
                                        <x-admin::form.control-group class="mb-4">
                                            <x-admin::form.control-group.label class="required">
                                                @lang('admin::app.leads.send-whatsapp-message.message')
                                            </x-admin::form.control-group.label>
    
                                            <x-admin::form.control-group.control
                                                type="textarea"
                                                name="message"
                                                rules="required"
                                                :label="trans('admin::app.leads.send-whatsapp-message.message')"
                                                :placeholder="trans('admin::app.leads.send-whatsapp-message.placeholder')"
                                            >
                                            </x-admin::form.control-group.control>
    
                                            <x-admin::form.control-group.error control-name="message" />
                                        </x-admin::form.control-group>
    
                                        <!-- Phone Number Display -->
                                        <x-admin::form.control-group class="mb-4">
                                            <x-admin::form.control-group.label class="required">
                                                @lang('admin::app.leads.send-whatsapp-message.phone-number')
                                            </x-admin::form.control-group.label>
    
                                            <x-admin::form.control-group.control
                                                type="select"
                                                name="contact_number"
                                                rules="required"
                                                :label="trans('admin::app.leads.send-whatsapp-message.phone-number')"
                                            >
                                                <option value="">@lang('admin::app.leads.send-whatsapp-message.select-phone')</option>
                                                @foreach ($lead->person->contact_numbers as $number)
                                                    <option value="{{ $number['value'] }}">
                                                        {{ $number['value'] }}
                                                        @if ($number['label'])
                                                            ({{ $number['label'] }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </x-admin::form.control-group.control>
    
                                            <x-admin::form.control-group.error control-name="contact_number" />
                                        </x-admin::form.control-group>
                                    @else
                                        <div class="rounded bg-yellow-50 p-4 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-200">
                                            <p class="font-semibold">@lang('admin::app.leads.send-whatsapp-message.no-phone')</p>
                                            <p class="text-sm">@lang('admin::app.leads.send-whatsapp-message.add-phone-first')</p>
                                        </div>
                                    @endif
                                </div>
                            </x-slot:content>
    
                            <x-slot:footer>
                                <div class="flex items-center gap-x-2.5">
                                    <button
                                        type="submit"
                                        class="primary-button"
                                    >
                                        @lang('admin::app.leads.send-whatsapp-message.send')
                                    </button>
                                </div>
                            </x-slot:footer>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
            </div>
        </script>

        <script type="module">
            app.component('v-whatsapp-activity-action', {
                template: '#v-whatsapp-activity-action-template',

                props: ['lead_id'],

                methods: {
                    call(params, {
                        resetForm,
                        setErrors
                    }) {
                        this.$axios.post(`/admin/leads/${this.lead_id}/send-whatsapp`, params)
                            .then(response => {
                                this.$emitter.emit('add-flash', {
                                    type: 'success',
                                    message: response.data.message
                                });

                                this.$refs.whatsappModal.close();

                                resetForm();
                            })
                            .catch(error => {
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                } else {
                                    this.$emitter.emit('add-flash', {
                                        type: 'error',
                                        message: error.response.data.message
                                    });
                                }

                                this.$refs.whatsappModal.close();
                            });
                    }
                }
            });
        </script>
    @endPushOnce
</x-admin::layouts>
