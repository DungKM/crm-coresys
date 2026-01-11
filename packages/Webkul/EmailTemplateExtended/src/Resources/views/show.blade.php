<x-admin::layouts>
    <x-slot:title>
        {{ $emailTemplate->name }}
    </x-slot>

    <div class="flex flex-col gap-5">

        {{-- HEADER --}}
        <div class="flex flex-wrap items-center justify-between gap-4 rounded-lg border border-gray-200 bg-white px-5 py-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <div class="flex items-center gap-3">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ $emailTemplate->name }}
                </h1>

                {{-- STATUS BADGE --}}
                <span class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full px-3 py-1 text-xs font-medium {{ $emailTemplate->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300' }}">
                    <span class="h-1.5 w-1.5 rounded-full {{ $emailTemplate->is_active ? 'bg-green-600' : 'bg-red-600' }}"></span>
                    {{ $emailTemplate->is_active ? 'Active' : 'Inactive' }}
                </span>

                {{-- EDITOR MODE BADGE --}}
                @if($emailTemplate->editor_mode === 'pro')
                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full px-3 py-1 text-xs font-medium bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300">
                        <span class="icon-magic"></span>
                        Pro Builder
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full px-3 py-1 text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300">
                        <span class="icon-code"></span>
                        Classic
                    </span>
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-2 max-sm:w-full">
                <a href="{{ route('admin.email_templates.edit', $emailTemplate->id) }}" class="primary-button">
                    <span class="icon-edit"></span> Edit
                </a>

                <button type="button" id="previewBtn" class="secondary-button" onclick="handlePreviewClick(); return false;">
                    <span class="icon-eye"></span> Preview
                </button>

                <form action="{{ route('admin.email_templates.clone', $emailTemplate->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="secondary-button" onclick="return confirm('Clone this template?')">
                        <span class="icon-copy"></span> Clone
                    </button>
                </form>

                <button type="button" onclick="openExportModal()" class="secondary-button">
                    <span class="icon-export"></span> Export
                </button>

                <a href="{{ route('admin.email_templates.index') }}" class="transparent-button">
                    <span class="icon-arrow-left"></span> Back
                </a>
            </div>
        </div>

        {{-- MAIN LAYOUT --}}
        <div class="main-layout-grid">

            {{-- LEFT COLUMN --}}
            <div style="display: flex; flex-direction: column; gap: 20px; min-width: 0;">

                {{-- BASIC INFO --}}
                <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                    <h2 class="mb-4 text-base font-semibold text-gray-900 dark:text-white">
                        @lang('email_template_extended::app.templates.show.basic-info')
                    </h2>

                    <div class="grid grid-cols-2 gap-5 max-sm:grid-cols-1">
                        <div class="space-y-1.5">
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400">Name</label>
                            <div class="font-semibold text-gray-900 dark:text-white break-words">
                                {{ $emailTemplate->name }}
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400">Category</label>
                            <div class="inline-flex items-center whitespace-nowrap rounded px-3 py-1.5 text-sm font-medium leading-none bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                {{ $emailTemplate->category_label }}
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400">Locale</label>
                            <div class="font-semibold text-gray-900 dark:text-white">
                                {{ strtoupper($emailTemplate->locale) }}
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400">Editor Mode</label>
                            <div class="font-semibold text-gray-900 dark:text-white">
                                {{ $emailTemplate->editor_mode === 'pro' ? 'Pro Builder' : 'Classic Editor' }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SUBJECT --}}
                <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                    <h2 class="mb-3 text-base font-semibold text-gray-900 dark:text-white">
                        Subject
                    </h2>
                    <div class="rounded-lg bg-gray-50 px-4 py-3.5 text-sm font-medium leading-relaxed text-gray-900 dark:bg-gray-800 dark:text-gray-100 break-words">
                        {{ $emailTemplate->subject }}
                    </div>
                </div>

                {{-- CONTENT --}}
                <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-base font-semibold text-gray-900 dark:text-white">
                            Content
                        </h2>
                        
                        @if($emailTemplate->editor_mode === 'pro')
                            <span class="text-xs text-purple-600 dark:text-purple-400">
                                <span class="icon-magic"></span> Pro Builder
                            </span>
                        @endif
                    </div>

                    @if($emailTemplate->editor_mode === 'pro')
                        @if($emailTemplate->content && strlen(trim($emailTemplate->content)) > 10)
                            <div class="content-display-wrapper">
                                <div id="safeContentWrapper" class="safe-content-container"></div>
                            </div>
                        @elseif($emailTemplate->builder_config)
                            <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-4 dark:border-yellow-800 dark:bg-yellow-900/20">
                                <div class="flex items-start gap-3">
                                    <span class="icon-alert text-yellow-600 dark:text-yellow-400"></span>
                                    <div>
                                        <p class="text-sm font-medium text-yellow-800 dark:text-yellow-300">
                                            HTML content chưa được tạo. 
                                        </p>
                                        <a 
                                            href="{{ route('admin.email_templates.edit', $emailTemplate->id) }}"
                                            class="mt-2 inline-flex items-center gap-1 text-sm font-medium text-yellow-700 hover:text-yellow-900 dark:text-yellow-400"
                                        >
                                            <span class="icon-edit"></span>
                                            Edit để tạo HTML
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="rounded-lg bg-gray-50 px-4 py-8 text-center text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                <span class="icon-file-empty text-3xl"></span>
                                <p class="mt-2 text-sm">Chưa có nội dung</p>
                            </div>
                        @endif
                    @else
                        @if($emailTemplate->content && strlen(trim($emailTemplate->content)) > 0)
                            <div class="content-display-wrapper">
                                <div id="classicContentWrapper" class="safe-content-container prose prose-sm max-w-none dark:prose-invert"></div>
                            </div>
                        @else
                            <div class="rounded-lg bg-gray-50 px-4 py-8 text-center text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                <span class="icon-file-empty text-3xl"></span>
                                <p class="mt-2 text-sm">Chưa có nội dung</p>
                            </div>
                        @endif
                    @endif
                </div>

            </div>

            {{-- RIGHT COLUMN --}}
            <div style="display: flex; flex-direction: column; gap: 20px; width: 400px;">

                {{-- STATISTICS --}}
                <div class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                    <div class="border-b border-gray-200 px-5 py-3.5 dark:border-gray-700">
                        <h2 class="text-base font-semibold text-gray-900 dark:text-white">
                            Statistics
                        </h2>
                    </div>

                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        @php
                            $stats = [
                                ['Total Uses', $emailTemplate->usage_count, true],
                                ['Last Used', $emailTemplate->last_used_at?->diffForHumans() ?? 'Never', false],
                                ['Created', $emailTemplate->created_at->format('d/m/Y'), false],
                                ['Variables', count($emailTemplate->getAllUsedVariables()), true],
                            ];
                        @endphp

                        @foreach($stats as [$label, $value, $big])
                            <div class="flex flex-col gap-1 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                    {{ $label }}
                                </span>
                                <span class="{{ $big ? 'text-xl font-bold' : 'text-sm font-semibold' }} text-gray-900 dark:text-white text-right leading-relaxed">
                                    {{ $value }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- CLONE HISTORY --}}
                @if($clones->count())
                    <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                        <h2 class="mb-3 text-base font-semibold text-gray-900 dark:text-white">
                            Clone History
                        </h2>
                        <ul class="space-y-2.5">
                            @foreach($clones as $clone)
                                <li class="flex items-start gap-2 text-sm">
                                    <span class="mt-0.5 text-gray-400 dark:text-gray-500">→</span>
                                    <a href="{{ route('admin.email_templates.show', $clone->id) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400">
                                        {{ $clone->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- TAGS --}}
                @if($emailTemplate->tags && count($emailTemplate->tags) > 0)
                    <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                        <h2 class="mb-3 text-base font-semibold text-gray-900 dark:text-white">
                            Tags
                        </h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($emailTemplate->tags as $tag)
                                <span class="inline-flex items-center whitespace-nowrap rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900/50 dark:text-blue-300">
                                    #{{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </div>

    {{-- EXPORT MODAL --}}
    <div id="exportModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 9999;">
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.85); backdrop-filter: blur(4px);" onclick="closeExportModal()"></div>
        
        <div style="position: relative; z-index: 10000; display: flex; align-items: center; justify-content: center; height: 100%; padding: 1rem;">
            <div class="animate-scale-in" style="position: relative;">
                <button 
                    onclick="closeExportModal()" 
                    style="position: absolute; top: -16px; right: -16px; width: 40px; height: 40px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 25px rgba(0,0,0,0.3); border: none; cursor: pointer; transition: all 0.3s; z-index: 10001;"
                    onmouseover="this.style.background='#ef4444'; this.style.transform='rotate(90deg)'; this.style.color='white';"
                    onmouseout="this.style.background='white'; this.style.transform='rotate(0deg)'; this.style.color='#374151';"
                >
                    <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <div style="display: flex; gap: 24px;">
                    <a href="{{ route('admin.email_templates.export_html', $emailTemplate->id) }}" 
                       style="display: block; text-decoration: none; transition: transform 0.3s;"
                       onmouseover="this.style.transform='scale(1.1)'"
                       onmouseout="this.style.transform='scale(1)'">
                        <div style="width: 192px; height: 192px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 16px; border-radius: 24px; background: linear-gradient(to bottom right, white, #f9fafb); padding: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
                            <div style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; border-radius: 16px; background: linear-gradient(to bottom right, #fb923c, #ea580c); box-shadow: 0 10px 25px rgba(251, 146, 60, 0.5);">
                                <svg style="width: 44px; height: 44px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                </svg>
                            </div>
                            <div style="text-align: center;">
                                <span style="display: block; font-size: 20px; font-weight: 700; color: #111827;">HTML</span>
                                <span style="display: block; margin-top: 4px; font-size: 12px; color: #6b7280;">Export as HTML file</span>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.email_templates.export_json', $emailTemplate->id) }}" 
                       style="display: block; text-decoration: none; transition: transform 0.3s;"
                       onmouseover="this.style.transform='scale(1.1)'"
                       onmouseout="this.style.transform='scale(1)'">
                        <div style="width: 192px; height: 192px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 16px; border-radius: 24px; background: linear-gradient(to bottom right, white, #f9fafb); padding: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
                            <div style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; border-radius: 16px; background: linear-gradient(to bottom right, #4ade80, #16a34a); box-shadow: 0 10px 25px rgba(74, 222, 128, 0.5);">
                                <svg style="width: 44px; height: 44px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div style="text-align: center;">
                                <span style="display: block; font-size: 20px; font-weight: 700; color: #111827;">JSON</span>
                                <span style="display: block; margin-top: 4px; font-size: 12px; color: #6b7280;">Export as JSON file</span>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.email_templates.export_zip', $emailTemplate->id) }}" 
                       style="display: block; text-decoration: none; transition: transform 0.3s;"
                       onmouseover="this.style.transform='scale(1.1)'"
                       onmouseout="this.style.transform='scale(1)'">
                        <div style="width: 192px; height: 192px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 16px; border-radius: 24px; background: linear-gradient(to bottom right, white, #f9fafb); padding: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
                            <div style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; border-radius: 16px; background: linear-gradient(to bottom right, #a855f7, #7e22ce); box-shadow: 0 10px 25px rgba(168, 85, 247, 0.5);">
                                <svg style="width: 44px; height: 44px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <div style="text-align: center;">
                                <span style="display: block; font-size: 20px; font-weight: 700; color: #111827;">ZIP</span>
                                <span style="display: block; margin-top: 4px; font-size: 12px; color: #6b7280;">Export as ZIP archive</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function renderSafeContent(containerId, htmlContent) {
        console.log('🔵 Rendering content for:', containerId);
        console.log('📏 Content length:', htmlContent ? htmlContent.length : 0);
        
        const container = document.getElementById(containerId);
        if (!container) {
            console.error('❌ Container not found:', containerId);
            return;
        }
        
        if (!htmlContent || htmlContent.trim() === '') {
            console.warn('⚠️ Empty content for:', containerId);
            return;
        }
        
        try {
            const temp = document.createElement('div');
            temp.innerHTML = htmlContent;
            
            console.log('✅ Parsed HTML, children:', temp.children.length);
            
            // ✅ CRITICAL: Remove ALL height constraints from source HTML
            const elements = temp.querySelectorAll('*');
            elements.forEach(el => {
                // Remove Vue directives
                Array.from(el.attributes).forEach(attr => {
                    if (attr.name.startsWith('v-') || attr.name.startsWith(':') || attr.name.startsWith('@')) {
                        el.removeAttribute(attr.name);
                    }
                });
                
                // Remove height attribute (like height="380")
                el.removeAttribute('height');
                el.removeAttribute('max-height');
                
                // Get inline style and remove height from it
                const inlineStyle = el.getAttribute('style');
                if (inlineStyle) {
                    // Remove height, max-height, overflow from inline style
                    const cleanStyle = inlineStyle
                        .split(';')
                        .filter(s => {
                            const prop = s.split(':')[0]?.trim().toLowerCase();
                            return prop !== 'height' && 
                                   prop !== 'max-height' && 
                                   prop !== 'overflow' && 
                                   prop !== 'overflow-y';
                        })
                        .join(';');
                    
                    if (cleanStyle) {
                        el.setAttribute('style', cleanStyle);
                    } else {
                        el.removeAttribute('style');
                    }
                }
                
                // Force CSS overrides
                el.style.setProperty('max-height', 'none', 'important');
                el.style.setProperty('height', 'auto', 'important');
                el.style.setProperty('overflow', 'visible', 'important');
            });
            
            console.log('🔧 Cleaned inline styles from', elements.length, 'elements');
            
            container.innerHTML = temp.innerHTML;
            
            // ✅ CRITICAL FIX: Force remove height from ALL elements AFTER render
            setTimeout(() => {
                const allElements = container.querySelectorAll('*');
                allElements.forEach(el => {
                    // Remove height attributes
                    el.removeAttribute('height');
                    
                    // Force CSS overrides
                    el.style.removeProperty('max-height');
                    el.style.removeProperty('height');
                    el.style.setProperty('max-height', 'none', 'important');
                    el.style.setProperty('height', 'auto', 'important');
                    
                    // Special fix for tables
                    if (el.tagName === 'TABLE' || el.tagName === 'TR' || el.tagName === 'TD' || el.tagName === 'TBODY') {
                        el.style.setProperty('height', 'auto', 'important');
                        el.style.setProperty('max-height', 'none', 'important');
                    }
                });
                
                console.log('🔧 Applied post-render fixes to', allElements.length, 'elements');
            }, 50);
            
            // Force expand ALL parent containers
            let parent = container;
            let level = 0;
            while (parent && level < 10) {
                const computed = window.getComputedStyle(parent);
                
                // Fix overflow hidden
                if (computed.overflow === 'hidden' || computed.overflowY === 'hidden') {
                    console.log(`🔧 Fixing overflow at level ${level}:`, parent.className);
                    parent.style.setProperty('overflow', 'visible', 'important');
                    parent.style.setProperty('overflow-y', 'visible', 'important');
                }
                
                // Fix max-height
                if (computed.maxHeight !== 'none') {
                    console.log(`🔧 Fixing max-height at level ${level}:`, computed.maxHeight, parent.className);
                    parent.style.setProperty('max-height', 'none', 'important');
                }
                
                parent.style.setProperty('height', 'auto', 'important');
                parent = parent.parentElement;
                level++;
            }
            
            // Debug info after render
            setTimeout(() => {
                console.log('📊 Final container height:', container.offsetHeight);
                console.log('📊 Final scrollHeight:', container.scrollHeight);
                
                const tables = container.querySelectorAll('table');
                console.log('🔍 Found tables:', tables.length);
                tables.forEach((table, i) => {
                    const rows = table.querySelectorAll('tr');
                    console.log(`\n📋 Table ${i}:`, {
                        offsetHeight: table.offsetHeight,
                        scrollHeight: table.scrollHeight,
                        totalRows: rows.length,
                        computedHeight: window.getComputedStyle(table).height,
                        computedMaxHeight: window.getComputedStyle(table).maxHeight,
                        computedOverflow: window.getComputedStyle(table).overflow
                    });
                    
                    // Check each row
                    rows.forEach((row, idx) => {
                        const computed = window.getComputedStyle(row);
                        console.log(`  Row ${idx}:`, {
                            offsetHeight: row.offsetHeight,
                            display: computed.display,
                            visibility: computed.visibility,
                            maxHeight: computed.maxHeight,
                            overflow: computed.overflow
                        });
                        
                        // Force show row
                        row.style.setProperty('display', computed.display === 'none' ? 'table-row' : computed.display, 'important');
                        row.style.setProperty('visibility', 'visible', 'important');
                        row.style.setProperty('max-height', 'none', 'important');
                        row.style.setProperty('height', 'auto', 'important');
                        row.removeAttribute('height');
                        
                        // Fix cells in row
                        row.querySelectorAll('td, th').forEach(cell => {
                            cell.style.setProperty('max-height', 'none', 'important');
                            cell.style.setProperty('height', 'auto', 'important');
                            cell.style.setProperty('overflow', 'visible', 'important');
                            cell.removeAttribute('height');
                        });
                    });
                    
                    // Force fix table
                    table.removeAttribute('height');
                    table.style.setProperty('height', 'auto', 'important');
                    table.style.setProperty('max-height', 'none', 'important');
                    table.style.setProperty('overflow', 'visible', 'important');
                });
                
                // Check again after 200ms
                setTimeout(() => {
                    console.log('🔍 Re-check after fixes:');
                    tables.forEach((table, i) => {
                        console.log(`  Table ${i} now:`, {
                            offsetHeight: table.offsetHeight,
                            scrollHeight: table.scrollHeight
                        });
                    });
                }, 200);
            }, 300);
            
        } catch (error) {
            console.error('❌ Error rendering content:', error);
            container.innerHTML = '<p style="color: red;">Error: ' + error.message + '</p>';
        }
    }

    function waitAndInit() {
        let attempts = 0;
        const maxAttempts = 30;
        
        function tryInit() {
            try {
                attempts++;
                console.log(`🔄 Init attempt: ${attempts}/${maxAttempts}`);
                
                const proContainer = document.getElementById('safeContentWrapper');
                const classicContainer = document.getElementById('classicContentWrapper');
                
                if ((proContainer || classicContainer) && attempts < maxAttempts) {
                    console.log('✅ Containers found, initializing...');
                    setTimeout(() => initPage(), 500);
                } else if (attempts >= maxAttempts) {
                    console.error('❌ Max attempts reached');
                } else {
                    setTimeout(tryInit, 100);
                }
            } catch (error) {
                console.error('❌ Error in tryInit:', error);
            }
        }
        
        tryInit();
    }

    function initPage() {
        console.log('🚀 === Starting page initialization ===');
        
        // Render Pro Builder content
        @if($emailTemplate->editor_mode === 'pro' && $emailTemplate->content && strlen(trim($emailTemplate->content)) > 10)
            const proContent = @json($emailTemplate->content);
            console.log('📝 Pro content length:', proContent.length);
            renderSafeContent('safeContentWrapper', proContent);
        @endif
        
        // Render Classic content  
        @if($emailTemplate->editor_mode !== 'pro' && $emailTemplate->content && strlen(trim($emailTemplate->content)) > 0)
            const classicContent = @json($emailTemplate->content);
            console.log('📝 Classic content length:', classicContent.length);
            renderSafeContent('classicContentWrapper', classicContent);
        @endif
        
        // Preview function
        window.handlePreviewClick = function() {
            const btn = document.getElementById('previewBtn');
            if (!btn) {
                alert('Không tìm thấy nút preview');
                return false;
            }
            
            const content = @json($emailTemplate->content ?? '');
            
            if (!content || content.trim() === '') {
                alert('Không có nội dung để xem trước');
                return false;
            }
            
            btn.disabled = true;
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<svg class="h-4 w-4 animate-spin inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Loading...';
            
            try {
                const previewData = {
                    name: @json($emailTemplate->name),
                    subject: @json($emailTemplate->subject),
                    preview_text: @json($emailTemplate->preview_text ?? ''),
                    category: @json($emailTemplate->category),
                    locale: @json($emailTemplate->locale),
                    editor_mode: @json($emailTemplate->editor_mode ?? 'classic'),
                    content: content,
                    builder_config: @json($emailTemplate->builder_config ?? ''),
                    _token: '{{ csrf_token() }}'
                };
                
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.email_templates.preview", $emailTemplate->id) }}';
                form.target = '_blank';
                form.style.display = 'none';
                
                Object.keys(previewData).forEach(key => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = previewData[key] || '';
                    form.appendChild(input);
                });
                
                document.body.appendChild(form);
                form.submit();
                
                setTimeout(() => {
                    if (form && form.parentNode) {
                        document.body.removeChild(form);
                    }
                    btn.disabled = false;
                    btn.innerHTML = originalHTML;
                }, 500);
            } catch (error) {
                console.error('Preview error:', error);
                alert('Lỗi: ' + error.message);
                btn.disabled = false;
                btn.innerHTML = originalHTML;
            }
            
            return false;
        };

        // Export modal
        window.openExportModal = function() {
            const modal = document.getElementById('exportModal');
            if (modal) {
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }
        };

        window.closeExportModal = function() {
            const modal = document.getElementById('exportModal');
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        };

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                window.closeExportModal();
            }
        });
        
        console.log('✅ === Page initialization complete ===');
    }

    // Start init when DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', waitAndInit);
    } else {
        waitAndInit();
    }
    </script>

    <style>
    /* Force full height display - NO truncation */
    #safeContentWrapper,
    #safeContentWrapper *,
    #classicContentWrapper,
    #classicContentWrapper *,
    .safe-content-container,
    .safe-content-container *,
    .content-display-wrapper,
    .content-display-wrapper * {
        max-height: none !important;
        height: auto !important;
    }

    /* Remove overflow constraints */
    .content-display-wrapper {
        overflow: visible !important;
        max-height: none !important;
        height: auto !important;
    }

    .main-layout-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 400px;
        gap: 20px;
    }

    /* Safe content container */
    .safe-content-container {
        max-width: 100%;
        overflow-x: auto;
        overflow-y: visible !important;
        min-height: 100px;
        max-height: none !important;
        height: auto !important;
    }

    .safe-content-container * {
        max-width: 100% !important;
    }

    .safe-content-container img {
        max-width: 100% !important;
        height: auto !important;
    }

    .safe-content-container table {
        max-width: 100% !important;
        width: 100% !important;
        max-height: none !important;
        height: auto !important;
    }

    .safe-content-container td,
    .safe-content-container tr,
    .safe-content-container tbody,
    .safe-content-container thead {
        max-height: none !important;
        height: auto !important;
    }

    /* Force parent containers to expand */
    .rounded-lg.border.border-gray-200.bg-white {
        overflow: visible !important;
        max-height: none !important;
    }

    @keyframes scale-in {
        from { 
            opacity: 0; 
            transform: scale(0.9); 
        }
        to { 
            opacity: 1; 
            transform: scale(1); 
        }
    }

    .animate-scale-in {
        animation: scale-in 0.3s ease-out;
    }

    @media (max-width: 1024px) {
        .main-layout-grid {
            display: flex !important;
            flex-direction: column !important;
        }
    }
    </style>
    @endpush
</x-admin::layouts>