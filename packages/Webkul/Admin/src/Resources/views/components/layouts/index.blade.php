<!DOCTYPE html>

<html class="{{ request()->cookie('dark_mode') ? 'dark' : '' }}" lang="{{ app()->getLocale() }}"
    dir="{{ in_array(app()->getLocale(), ['fa', 'ar']) ? 'rtl' : 'ltr' }}">

<head>

    {!! view_render_event('admin.layout.head.before') !!}

    <title>{{ $title ?? '' }}</title>

    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="content-language" content="{{ app()->getLocale() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="base-url" content="{{ url()->to('/') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="currency"
        content="{{ json_encode([
            'code' => config('app.currency'),
            'symbol' => core()->currencySymbol(config('app.currency')),
        ]) }}
        ">

    @stack('meta')

    {{ vite()->set(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js']) }}

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet" />

    <link rel="preload" as="image" href="{{ url('cache/logo/bagisto.png') }}">

    @if ($favicon = core()->getConfigData('general.design.admin_logo.favicon'))
        <link type="image/x-icon" href="{{ Storage::url($favicon) }}" rel="shortcut icon" sizes="16x16">
    @else
        <link type="image/x-icon" href="{{ vite()->asset('images/favicon.ico') }}" rel="shortcut icon"
            sizes="16x16" />
    @endif

    @php
        $brandColor = core()->getConfigData('general.settings.menu_color.brand_color') ?? '#0E90D9';
    @endphp

    @stack('styles')

    <style>
        :root {
            --brand-color: {{ $brandColor }};
        }

        {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}
    </style>

    {!! view_render_event('admin.layout.head.after') !!}
</head>

<body class="h-full font-inter dark:bg-gray-950">
    {!! view_render_event('admin.layout.body.before') !!}

    <div id="app" class="h-full">
        <!-- Flash Message Blade Component -->
        <x-admin::flash-group />

        <!-- Confirm Modal Blade Component -->
        <x-admin::modal.confirm />

        {!! view_render_event('admin.layout.content.before') !!}

        <!-- Page Header Blade Component -->
        <x-admin::layouts.header />

        <div class="group/container sidebar-collapsed flex gap-4" ref="appLayout">
            <!-- Page Sidebar Blade Component -->
            <x-admin::layouts.sidebar.desktop />

            <div
                class="flex min-h-[calc(100vh-62px)] max-w-full flex-1 flex-col bg-gray-100 pt-3 transition-all duration-300 dark:bg-gray-950">
                <!-- Page Content Blade Component -->
                <div class="px-4 pb-6 ltr:lg:pl-[85px] rtl:lg:pr-[85px]">
                    {{ $slot }}
                </div>

                <!-- Powered By -->
                <div class="mt-auto pt-6">
                    <div
                        class="border-t bg-white py-5 text-center text-sm font-normal dark:border-gray-800 dark:bg-gray-900 dark:text-white max-md:py-3">
                        <p>{!! core()->getConfigData('general.settings.footer.label') !!}</p>
                    </div>
                </div>
            </div>
        </div>

        {!! view_render_event('admin.layout.content.after') !!}
    </div>

    {!! view_render_event('admin.layout.body.after') !!}

    @stack('scripts')

    {!! view_render_event('admin.layout.vue-app-mount.before') !!}

    <script>
        /**
         * Load event, the purpose of using the event is to mount the application
         * after all of our `Vue` components which is present in blade file have
         * been registered in the app. No matter what `app.mount()` should be
         * called in the last.
         */
        window.addEventListener("load", function(event) {
            app.mount("#app");
        });
    </script>

    {!! view_render_event('admin.layout.vue-app-mount.after') !!}

    {{-- Global WhatsApp Notification Sound --}}
    <script>
        (function() {
            // ===========================================
            // WHATSAPP NOTIFICATION SYSTEM
            // ===========================================
            
            // Biến lưu thời điểm kiểm tra cuối
            let lastCheckTime = new Date().toISOString();
            let isPolling = false;
            let originalTitle = document.title;
            let unreadCount = 0;
            let audioContext = null;
            
            // Tracking tin nhắn đã thông báo (dùng localStorage để persist)
            const NOTIFIED_KEY = 'whatsapp_notified_ids';
            const MAX_TRACKED_IDS = 100; // Giữ tối đa 100 ID gần nhất
            
            function getNotifiedIds() {
                try {
                    const stored = localStorage.getItem(NOTIFIED_KEY);
                    return stored ? new Set(JSON.parse(stored)) : new Set();
                } catch (e) {
                    return new Set();
                }
            }
            
            function addNotifiedId(id) {
                try {
                    const ids = getNotifiedIds();
                    ids.add(id);
                    // Giữ tối đa MAX_TRACKED_IDS entries
                    const idsArray = Array.from(ids);
                    if (idsArray.length > MAX_TRACKED_IDS) {
                        idsArray.splice(0, idsArray.length - MAX_TRACKED_IDS);
                    }
                    localStorage.setItem(NOTIFIED_KEY, JSON.stringify(idsArray));
                } catch (e) {
                    console.log('[Notification] Storage error:', e);
                }
            }
            
            function isAlreadyNotified(id) {
                return getNotifiedIds().has(id);
            }
            
            // Khởi tạo Audio Context (lazy load khi cần)
            function getAudioContext() {
                if (!audioContext) {
                    audioContext = new (window.AudioContext || window.webkitAudioContext)();
                }
                return audioContext;
            }
            
            // Hàm phát âm thanh beep style WhatsApp (2 beep ngắn)
            function playNotificationSound() {
                try {
                    const ctx = getAudioContext();
                    
                    // Resume audio context nếu bị suspended (cần user interaction)
                    if (ctx.state === 'suspended') {
                        ctx.resume();
                    }
                    
                    // Tạo oscillator cho beep sound
                    function playBeep(startTime, frequency, duration) {
                        const oscillator = ctx.createOscillator();
                        const gainNode = ctx.createGain();
                        
                        oscillator.connect(gainNode);
                        gainNode.connect(ctx.destination);
                        
                        oscillator.frequency.value = frequency;
                        oscillator.type = 'sine';
                        
                        // Fade in/out để tránh click sound
                        gainNode.gain.setValueAtTime(0, startTime);
                        gainNode.gain.linearRampToValueAtTime(0.3, startTime + 0.01);
                        gainNode.gain.linearRampToValueAtTime(0, startTime + duration);
                        
                        oscillator.start(startTime);
                        oscillator.stop(startTime + duration);
                    }
                    
                    const now = ctx.currentTime;
                    // Beep 1: cao (WhatsApp style)
                    playBeep(now, 880, 0.1);        // A5 note
                    // Beep 2: cao hơn (sau 150ms)
                    playBeep(now + 0.15, 1046, 0.1); // C6 note
                    
                    console.log('[Notification] ✅ Sound played successfully');
                } catch (e) {
                    console.log('[Notification] ⚠️ Sound error:', e.message);
                }
            }
            
            // Cập nhật title tab khi có tin nhắn mới
            function updateBrowserTitle(count) {
                if (count > 0) {
                    document.title = `(${count}) 💬 ${originalTitle}`;
                } else {
                    document.title = originalTitle;
                }
            }
            
            // Container cho in-app notifications (style Zalo)
            function ensureNotificationContainer() {
                let container = document.getElementById('whatsapp-toast-container');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'whatsapp-toast-container';
                    container.style.cssText = `
                        position: fixed;
                        bottom: 20px;
                        right: 20px;
                        z-index: 99999;
                        display: flex;
                        flex-direction: column-reverse;
                        gap: 10px;
                        max-height: 80vh;
                        overflow: hidden;
                        pointer-events: none;
                    `;
                    document.body.appendChild(container);
                }
                return container;
            }
            
            // Hiện in-app notification style Zalo
            function showInAppNotification(message, leadName, leadId) {
                const container = ensureNotificationContainer();
                
                const toast = document.createElement('div');
                toast.style.cssText = `
                    display: flex;
                    align-items: flex-start;
                    gap: 12px;
                    padding: 14px 16px;
                    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
                    border-radius: 12px;
                    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15), 0 2px 8px rgba(0, 0, 0, 0.1);
                    border: 1px solid rgba(37, 211, 102, 0.3);
                    min-width: 320px;
                    max-width: 400px;
                    cursor: pointer;
                    transform: translateX(120%);
                    transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
                    pointer-events: auto;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                `;
                
                toast.innerHTML = `
                    <div style="
                        width: 48px;
                        height: 48px;
                        border-radius: 50%;
                        background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        flex-shrink: 0;
                        box-shadow: 0 2px 8px rgba(37, 211, 102, 0.4);
                    ">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="white">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="
                            font-weight: 600;
                            font-size: 14px;
                            color: #1a1a1a;
                            margin-bottom: 4px;
                            display: flex;
                            align-items: center;
                            gap: 6px;
                        ">
                            <span style="color: #25D366;">●</span>
                            ${leadName || 'Khách hàng'}
                        </div>
                        <div style="
                            font-size: 13px;
                            color: #666;
                            white-space: nowrap;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            max-width: 280px;
                        ">${message || 'Tin nhắn mới'}</div>
                        <div style="
                            font-size: 11px;
                            color: #999;
                            margin-top: 4px;
                        ">Vừa xong • Click để xem</div>
                    </div>
                    <button onclick="event.stopPropagation(); this.parentElement.remove();" style="
                        background: none;
                        border: none;
                        color: #999;
                        cursor: pointer;
                        padding: 4px;
                        font-size: 18px;
                        line-height: 1;
                        opacity: 0.6;
                        transition: opacity 0.2s;
                    " onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.6'">×</button>
                `;
                
                // Click để mở lead chat
                toast.onclick = function() {
                    if (leadId) {
                        window.location.href = '/admin/leads/' + leadId + '/chat';
                    }
                    toast.remove();
                };
                
                // Hover effect
                toast.onmouseenter = function() {
                    this.style.transform = 'translateX(0) scale(1.02)';
                    this.style.boxShadow = '0 12px 40px rgba(0, 0, 0, 0.2), 0 4px 12px rgba(0, 0, 0, 0.15)';
                };
                toast.onmouseleave = function() {
                    this.style.transform = 'translateX(0) scale(1)';
                    this.style.boxShadow = '0 8px 32px rgba(0, 0, 0, 0.15), 0 2px 8px rgba(0, 0, 0, 0.1)';
                };
                
                container.appendChild(toast);
                
                // Animate in
                requestAnimationFrame(() => {
                    toast.style.transform = 'translateX(0)';
                });
                
                // Auto remove sau 5 giây
                setTimeout(() => {
                    toast.style.transform = 'translateX(120%)';
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 400);
                }, 5000);
            }
            
            // Hàm kiểm tra tin nhắn mới
            async function checkForNewMessages() {
                if (isPolling) return;
                isPolling = true;
                
                try {
                    const response = await fetch('/admin/whatsapp/check-new?last_check=' + encodeURIComponent(lastCheckTime), {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        }
                    });
                    
                    if (!response.ok) {
                        throw new Error('HTTP ' + response.status);
                    }
                    
                    const data = await response.json();
                    
                    if (data.success && data.has_new && data.count > 0) {
                        // Filter ra chỉ những tin nhắn CHƯA được thông báo
                        const newMessages = data.messages.filter(msg => !isAlreadyNotified(msg.id));
                        
                        if (newMessages.length > 0) {
                            console.log('[Notification] 📩 New messages:', newMessages.length, 'of', data.count, 'total');
                            
                            // Phát âm thanh (chỉ 1 lần)
                            playNotificationSound();
                            
                            // Cập nhật unread count và title
                            unreadCount += newMessages.length;
                            updateBrowserTitle(unreadCount);
                            
                            // Hiện in-app notification style Zalo cho mỗi tin nhắn MỚI
                            newMessages.forEach(msg => {
                                showInAppNotification(
                                    msg.preview || 'Tin nhắn mới',
                                    msg.lead_name || 'Khách hàng',
                                    msg.lead_id
                                );
                                // Đánh dấu đã thông báo
                                addNotifiedId(msg.id);
                            });
                        } else {
                            console.log('[Notification] All messages already notified, skipping');
                        }
                    }
                    
                    if (data.server_time) {
                        lastCheckTime = data.server_time;
                    }
                } catch (e) {
                    console.log('[Notification] ⚠️ Error:', e.message);
                } finally {
                    isPolling = false;
                }
            }
            
            // Reset unread count khi user focus vào tab
            window.addEventListener('focus', function() {
                unreadCount = 0;
                updateBrowserTitle(0);
            });
            
            // User interaction để enable Audio Context
            document.addEventListener('click', function() {
                if (audioContext && audioContext.state === 'suspended') {
                    audioContext.resume();
                }
            }, { once: true });
            
            // Bắt đầu polling mỗi 5 giây
            setInterval(checkForNewMessages, 5000);
            
            // Chạy lần đầu sau 2 giây
            setTimeout(checkForNewMessages, 2000);
            
            console.log('[Notification] ✅ WhatsApp notification started (5s polling)');
        })();
    </script>
</body>

</html>
