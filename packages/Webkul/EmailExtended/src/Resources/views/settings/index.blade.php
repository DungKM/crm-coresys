<x-admin::layouts>
    <x-slot:title>
        Email Settings
    </x-slot>

    <div class="flex flex-col gap-4">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <p class="text-xl text-gray-800 dark:text-white font-bold">Email Settings</p>
                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Configure your email sending, receiving, and tracking settings</p>
            </div>
        </div>

        <!-- Status Banner -->
        @if($settings && $settings->is_active)
            <div class="box-shadow rounded bg-green-50 dark:bg-green-900/20 p-4 border border-green-200 dark:border-green-800">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-green-900 dark:text-green-100 font-semibold">Email settings are active</p>
                        <p class="text-green-700 dark:text-green-300 text-sm mt-0.5">
                            Sending from: <strong>{{ $settings->from_email }}</strong> | {{ $settings->emails_sent_count }} emails sent
                            @if($settings->webhook_enabled)
                                | <span class="text-green-600">🔔 Webhook Active</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @elseif($settings)
            <div class="box-shadow rounded bg-yellow-50 dark:bg-yellow-900/20 p-4 border border-yellow-200 dark:border-yellow-800">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-yellow-900 dark:text-yellow-100 font-semibold">Settings saved but not verified</p>
                        <p class="text-yellow-700 dark:text-yellow-300 text-sm mt-0.5">Please test connections below to activate</p>
                    </div>
                </div>
            </div>
        @else
            <div class="box-shadow rounded bg-blue-50 dark:bg-blue-900/20 p-4 border border-blue-200 dark:border-blue-800">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-blue-900 dark:text-blue-100 font-semibold">No email settings configured</p>
                        <p class="text-blue-700 dark:text-blue-300 text-sm mt-0.5">Configure your settings below to start using custom email</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Settings Form -->
        <form action="{{ route('admin.mail.settings.store') }}" method="POST" id="settingsForm">
            @csrf

            <div class="flex gap-4 max-xl:flex-wrap">
                <!-- Left Column - Main Settings -->
                <div class="flex flex-col gap-4 flex-1">
                    
                    <!-- SendGrid Settings -->
                    <div class="box-shadow rounded bg-white dark:bg-gray-900">
                        <div class="border-b border-gray-200 dark:border-gray-700 p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-lg">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-base font-semibold text-gray-800 dark:text-white">SendGrid Configuration</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">For sending outbound emails</p>
                                    </div>
                                </div>
                                @if($settings && $settings->sendgrid_verified)
                                    <span class="text-xs font-semibold text-green-600 bg-green-100 dark:bg-green-900/30 dark:text-green-300 px-3 py-1 rounded-full">
                                        ✓ Verified
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="p-4 space-y-4">
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    SendGrid API Key
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="password"
                                    name="sendgrid_api_key"
                                    id="sendgrid_api_key"
                                    :value="old('sendgrid_api_key', $settings->sendgrid_api_key ?? '')"
                                    rules="required"
                                    placeholder="SG.xxxxxxxxxxxxxxxxxxxx"
                                />

                                <x-admin::form.control-group.error control-name="sendgrid_api_key" />

                                <p class="text-gray-600 dark:text-gray-400 text-xs mt-1.5">
                                    Get your API key from <a href="https://app.sendgrid.com/settings/api_keys" target="_blank" class="text-blue-600 hover:text-blue-700 underline">SendGrid Dashboard</a>
                                </p>
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    From Email
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="email"
                                    name="from_email"
                                    id="from_email"
                                    :value="old('from_email', $settings->from_email ?? '')"
                                    rules="required|email"
                                    placeholder="sales@yourcompany.com"
                                />

                                <x-admin::form.control-group.error control-name="from_email" />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    From Name
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="from_name"
                                    id="from_name"
                                    :value="old('from_name', $settings->from_name ?? '')"
                                    rules="required"
                                    placeholder="Sales Team"
                                />

                                <x-admin::form.control-group.error control-name="from_name" />
                            </x-admin::form.control-group>

                            <div class="pt-2">
                                <button 
                                    type="button"
                                    onclick="testSendgrid()"
                                    id="testSendgridBtn"
                                    class="secondary-button w-full justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Test SendGrid Connection
                                </button>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center">
                                    A test email will be sent to your From Email address
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Gmail IMAP Settings -->
                    <div class="box-shadow rounded bg-white dark:bg-gray-900">
                        <div class="border-b border-gray-200 dark:border-gray-700 p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="bg-red-100 dark:bg-red-900/30 p-2 rounded-lg">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-base font-semibold text-gray-800 dark:text-white">Gmail IMAP Configuration</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">For receiving email replies (Optional)</p>
                                    </div>
                                </div>
                                @if($settings && $settings->gmail_verified)
                                    <span class="text-xs font-semibold text-green-600 bg-green-100 dark:bg-green-900/30 dark:text-green-300 px-3 py-1 rounded-full">
                                        ✓ Verified
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="p-4 space-y-4">
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    Gmail Address
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="email"
                                    name="gmail_address"
                                    id="gmail_address"
                                    :value="old('gmail_address', $settings->gmail_address ?? '')"
                                    placeholder="your@gmail.com"
                                />

                                <p class="text-gray-600 dark:text-gray-400 text-xs mt-1.5">
                                    For receiving email replies via IMAP
                                </p>
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    Gmail App Password
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="password"
                                    name="gmail_app_password"
                                    id="gmail_app_password"
                                    :value="old('gmail_app_password', $settings->gmail_app_password ?? '')"
                                    placeholder="16-character app password"
                                />

                                <p class="text-gray-600 dark:text-gray-400 text-xs mt-1.5">
                                    Create at <a href="https://myaccount.google.com/apppasswords" target="_blank" class="text-blue-600 hover:text-blue-700 underline">Google App Passwords</a>
                                </p>
                            </x-admin::form.control-group>

                            <div class="pt-2">
                                <button 
                                    type="button"
                                    onclick="testGmail()"
                                    id="testGmailBtn"
                                    class="secondary-button w-full justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Test Gmail Connection
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Webhook Settings -->
                    <div class="box-shadow rounded bg-white dark:bg-gray-900">
                        <div class="border-b border-gray-200 dark:border-gray-700 p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="bg-purple-100 dark:bg-purple-900/30 p-2 rounded-lg">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-base font-semibold text-gray-800 dark:text-white">Webhook Events</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Real-time email tracking events (Optional)</p>
                                    </div>
                                </div>
                                @if($settings && $settings->webhook_verified_at)
                                    <span class="text-xs font-semibold text-green-600 bg-green-100 dark:bg-green-900/30 dark:text-green-300 px-3 py-1 rounded-full">
                                        ✓ Verified
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="p-4 space-y-4">
                            <!-- Enable Webhook Toggle -->
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-white">Enable Webhook</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">Receive real-time tracking events from SendGrid</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="webhook_enabled" 
                                        id="webhook_enabled"
                                        value="1"
                                        {{ old('webhook_enabled', $settings->webhook_enabled ?? false) ? 'checked' : '' }}
                                        class="sr-only peer"
                                        onchange="toggleWebhookFields(this.checked)">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                                </label>
                            </div>

                            <div id="webhookFields" style="display: {{ old('webhook_enabled', $settings->webhook_enabled ?? false) ? 'block' : 'none' }}">
                                <!-- Webhook URL (Read-only) -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        Webhook URL
                                    </x-admin::form.control-group.label>

                                    <div class="flex gap-2">
                                        <input 
                                            type="text" 
                                            id="webhook_url"
                                            value="{{ route('webhooks.sendgrid.email') }}"
                                            readonly
                                            class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-700 rounded bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-sm">
                                        <button 
                                            type="button"
                                            onclick="copyWebhookUrl()"
                                            class="secondary-button">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                            Copy
                                        </button>
                                    </div>

                                    <p class="text-gray-600 dark:text-gray-400 text-xs mt-1.5">
                                        Add this URL to your <a href="https://app.sendgrid.com/settings/mail_settings" target="_blank" class="text-blue-600 hover:text-blue-700 underline">SendGrid Event Webhook settings</a>
                                    </p>
                                </x-admin::form.control-group>

                                <!-- Webhook Signing Key -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        Webhook Signing Key (Optional)
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="password"
                                        name="webhook_signing_key"
                                        id="webhook_signing_key"
                                        :value="old('webhook_signing_key', $settings->webhook_signing_key ?? '')"
                                        placeholder="MFEwDQYJKoZIhvcNAQEBBQAD..."
                                    />

                                    <p class="text-gray-600 dark:text-gray-400 text-xs mt-1.5">
                                        For webhook signature verification (recommended for security)
                                    </p>
                                </x-admin::form.control-group>

                                <!-- Event Types Selection -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        Track Events
                                    </x-admin::form.control-group.label>

                                    <div class="space-y-2 mt-2">
                                        @php
                                            $events = [
                                                'processed' => 'Processed - Message has been received',
                                                'delivered' => 'Delivered - Message has been delivered',
                                                'open' => 'Open - Recipient opened the email',
                                                'click' => 'Click - Recipient clicked a link',
                                                'bounce' => 'Bounce - Email bounced',
                                                'dropped' => 'Dropped - Email was dropped',
                                                'spamreport' => 'Spam Report - Marked as spam',
                                                'unsubscribe' => 'Unsubscribe - Recipient unsubscribed',
                                            ];
                                            $selectedEvents = old('webhook_events', $settings->webhook_events ?? ['delivered', 'open', 'click', 'bounce']);
                                        @endphp

                                        @foreach($events as $value => $label)
                                            <label class="flex items-start gap-2 p-2 hover:bg-gray-50 dark:hover:bg-gray-800 rounded cursor-pointer">
                                                <input 
                                                    type="checkbox" 
                                                    name="webhook_events[]" 
                                                    value="{{ $value }}"
                                                    {{ in_array($value, $selectedEvents) ? 'checked' : '' }}
                                                    class="mt-0.5 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </x-admin::form.control-group>

                                <!-- Test Webhook Button -->
                                <div class="pt-2">
                                    <button 
                                        type="button"
                                        onclick="testWebhook()"
                                        id="testWebhookBtn"
                                        class="secondary-button w-full justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                        Test Webhook Connection
                                    </button>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center">
                                        Verify webhook is properly configured in SendGrid
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Sidebar -->
                <div class="flex flex-col gap-4 w-[360px] max-w-full max-sm:w-full">
                    
                    <!-- Email Signature -->
                    <div class="box-shadow rounded bg-white dark:bg-gray-900">
                        <div class="border-b border-gray-200 dark:border-gray-700 p-4">
                            <p class="text-base font-semibold text-gray-800 dark:text-white">Email Signature</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">Optional</p>
                        </div>
                        
                        <div class="p-4">
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.control
                                    type="textarea"
                                    name="signature"
                                    id="signature"
                                    :value="old('signature', $settings->signature ?? '')"
                                    rows="8"
                                    placeholder="Best regards,&#10;Your Name&#10;Your Company&#10;email@company.com"
                                />

                                <p class="text-gray-600 dark:text-gray-400 text-xs mt-1.5">
                                    Automatically added to the end of your emails
                                </p>
                            </x-admin::form.control-group>
                        </div>
                    </div>

                    <!-- Help Section -->
                    <div class="box-shadow rounded bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 p-4">
                        <p class="text-base font-semibold text-blue-900 dark:text-blue-100 mb-3">Need Help?</p>
                        <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
                            <li class="flex items-start gap-2">
                                <span class="text-blue-600 font-bold">•</span>
                                <span>SendGrid not working? Check API key permissions</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-blue-600 font-bold">•</span>
                                <span>Gmail issues? Use App Password, not regular password</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-blue-600 font-bold">•</span>
                                <span>Webhook not receiving events? Check SendGrid settings</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-blue-600 font-bold">•</span>
                                <span>Test emails not arriving? Check spam folder</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col gap-2">
                        <button type="submit" class="primary-button justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            Save Settings
                        </button>

                        @if($settings && $settings->is_active)
                            <form action="{{ route('admin.mail.settings.deactivate') }}" method="POST">
                                @csrf
                                <button 
                                    type="submit" 
                                    onclick="return confirm('Deactivate email settings? System will use default configuration.')"
                                    class="secondary-button w-full justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                    </svg>
                                    Deactivate Settings
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function toggleWebhookFields(enabled) {
            document.getElementById('webhookFields').style.display = enabled ? 'block' : 'none';
        }

        function copyWebhookUrl() {
            const url = document.getElementById('webhook_url');
            url.select();
            document.execCommand('copy');
            alert('✅ Webhook URL copied to clipboard!');
        }

        function testSendgrid() {
            const btn = document.getElementById('testSendgridBtn');
            const apiKey = document.getElementById('sendgrid_api_key').value;
            const fromEmail = document.getElementById('from_email').value;
            const fromName = document.getElementById('from_name').value;

            if (!apiKey || !fromEmail || !fromName) {
                alert('Please fill in all SendGrid fields first');
                return;
            }

            btn.disabled = true;
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Testing...';

            fetch('{{ route("admin.mail.settings.test-sendgrid") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    sendgrid_api_key: apiKey,
                    from_email: fromEmail,
                    from_name: fromName
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('✅ ' + data.message);
                    location.reload();
                } else {
                    alert('❌ ' + data.message + '\n\n' + (data.help || ''));
                }
            })
            .catch(err => {
                alert('❌ Test failed: ' + err.message);
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalHTML;
            });
        }

        function testGmail() {
            const btn = document.getElementById('testGmailBtn');
            const address = document.getElementById('gmail_address').value;
            const password = document.getElementById('gmail_app_password').value;

            if (!address || !password) {
                alert('Please fill in Gmail address and app password first');
                return;
            }

            btn.disabled = true;
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Testing...';

            fetch('{{ route("admin.mail.settings.test-gmail") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    gmail_address: address,
                    gmail_app_password: password
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('✅ ' + data.message);
                    location.reload();
                } else {
                    alert('❌ ' + data.message + '\n\n' + (data.help || ''));
                }
            })
            .catch(err => {
                alert('❌ Test failed: ' + err.message);
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalHTML;
            });
        }

        function testWebhook() {
            const btn = document.getElementById('testWebhookBtn');
            const enabled = document.getElementById('webhook_enabled').checked;

            if (!enabled) {
                alert('Please enable webhook first');
                return;
            }

            const signingKey = document.getElementById('webhook_signing_key').value;
            const events = Array.from(document.querySelectorAll('input[name="webhook_events[]"]:checked'))
                .map(cb => cb.value);

            if (events.length === 0) {
                alert('Please select at least one event to track');
                return;
            }

            btn.disabled = true;
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Testing...';

            fetch('{{ route("admin.mail.settings.test-webhook") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    webhook_signing_key: signingKey,
                    webhook_events: events
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('✅ ' + data.message);
                    location.reload();
                } else {
                    alert('❌ ' + data.message + '\n\n' + (data.help || ''));
                }
            })
            .catch(err => {
                alert('❌ Test failed: ' + err.message);
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalHTML;
            });
        }
    </script>
    @endpush
</x-admin::layouts>vv