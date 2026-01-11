<x-admin::layouts>
    <x-slot:title>
        Preview - {{ $emailTemplate->name }}
    </x-slot>

    <div class="flex flex-col gap-6">
        
        {{-- ENHANCED HEADER --}}
        <div class="flex items-center justify-between rounded-xl border border-gray-200 bg-gradient-to-r from-white to-gray-50 px-6 py-4 shadow-sm dark:border-gray-800 dark:from-gray-900 dark:to-gray-800">
            <div class="flex items-center gap-4">
                {{-- Logo Email Preview - XANH LÁ --}}
                <div class="flex h-12 w-12 items-center justify-center rounded-xl shadow-lg" style="background-color: #4ade80 !important;">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                        Email Preview
                    </h1>
                    <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">{{ $emailTemplate->name }}</p>
                </div>
            </div>

            {{-- NÚT BACK --}}
            @if(isset($isPreviewMode) && $isPreviewMode)
                <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition-all hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
            @else
                <a href="{{ route('admin.email_templates.show', $emailTemplate->id) }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition-all hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
            @endif
        </div>

        <div class="flex gap-6 max-xl:flex-wrap">
            
            {{-- EMAIL PREVIEW - MAIN CONTENT --}}
            <div class="flex-1">
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    
                    {{-- EMAIL HEADER (Gmail Style) --}}
                    <div class="border-b border-gray-200 bg-white px-6 py-5 dark:border-gray-800 dark:bg-gray-900">
                        <div class="mb-4 flex items-start justify-between">
                            <div class="flex items-start gap-3">
                                {{-- Avatar Your Company - XANH DƯƠNG --}}
                                <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl text-base font-bold text-white shadow-md" style="background-color: #0ea5e9 !important;">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                
                                {{-- Sender Info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-gray-900 dark:text-white">Your Company</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 truncate">&lt;noreply@company.com&gt;</span>
                                    </div>
                                    <div class="mt-1 flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                        <span>to me</span>
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Timestamp & Actions --}}
                            <div class="flex items-center gap-3">
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ now()->format('M d, h:i A') }}
                                </span>
                                <button class="rounded-lg p-1.5 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-300">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Subject --}}
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $renderedSubject }}
                        </h2>

                        {{-- Preview Text --}}
                        @if($emailTemplate->preview_text)
                            <div class="mt-3 flex gap-2">
                                <div class="rounded-md bg-gray-100 px-3 py-1.5 dark:bg-gray-800">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $emailTemplate->preview_text }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- EMAIL BODY --}}
                    <div class="bg-gray-50 p-8 dark:bg-gray-900/50">
                        <div class="mx-auto max-w-3xl rounded-xl bg-white p-8 shadow-sm dark:bg-gray-900">
                            <div class="prose prose-sm max-w-none dark:prose-invert
                                        prose-headings:font-bold
                                        prose-h1:text-3xl prose-h1:text-gray-900 dark:prose-h1:text-white
                                        prose-h2:text-2xl prose-h2:text-gray-900 dark:prose-h2:text-white
                                        prose-h3:text-xl prose-h3:text-gray-900 dark:prose-h3:text-white
                                        prose-p:text-gray-700 prose-p:leading-7 dark:prose-p:text-gray-300
                                        prose-a:text-blue-600 prose-a:no-underline hover:prose-a:underline dark:prose-a:text-blue-400
                                        prose-strong:text-gray-900 dark:prose-strong:text-white
                                        prose-ul:text-gray-700 dark:prose-ul:text-gray-300
                                        prose-ol:text-gray-700 dark:prose-ol:text-gray-300
                                        prose-img:rounded-lg prose-img:shadow-md">
                                {!! $renderedContent !!}
                            </div>
                        </div>
                    </div>

                    {{-- EMAIL FOOTER --}}
                    <div class="border-t border-gray-200 bg-white px-6 py-4 dark:border-gray-800 dark:bg-gray-900">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <button class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-all hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                    </svg>
                                    Reply
                                </button>
                                <button class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-all hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Forward
                                </button>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Preview mode only</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SIDEBAR - SAMPLE DATA --}}
            @if(!empty($sampleData))
                <div class="w-[380px] max-xl:w-full">
                    <div class="sticky top-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                        <div class="border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white px-5 py-4 dark:border-gray-800 dark:from-gray-800 dark:to-gray-900">
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30">
                                    <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">Sample Data</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Variables used in preview</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="max-h-[calc(100vh-200px)] overflow-y-auto p-5">
                            <div class="space-y-3">
                                @foreach($sampleData as $key => $value)
                                    <div class="group rounded-lg border border-gray-200 bg-gradient-to-br from-gray-50 to-white p-4 transition-all hover:border-blue-300 hover:shadow-md dark:border-gray-700 dark:from-gray-800 dark:to-gray-800/50 dark:hover:border-blue-600">
                                        <div class="mb-2 flex items-center gap-2">
                                            <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                            <code class="text-xs font-bold font-mono text-blue-600 dark:text-blue-400">
                                                &#123;&#123; {{ $key }} &#125;&#125;
                                            </code>
                                        </div>
                                        <div class="rounded-md bg-white p-3 text-sm text-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                            @if(is_array($value) || is_object($value))
                                                <pre class="overflow-x-auto text-xs font-mono leading-relaxed">{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            @else
                                                <div class="break-words">{{ $value }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Variables Count --}}
                        <div class="border-t border-gray-200 bg-gray-50 px-5 py-3 dark:border-gray-800 dark:bg-gray-800/50">
                            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                <span class="font-medium">Total Variables</span>
                                <span class="rounded-full bg-blue-100 px-2.5 py-1 font-bold text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                    {{ count($sampleData) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin::layouts>