<x-admin::layouts>
    <x-slot:title>
        @lang('email_template_extended::app.templates.create.title')
    </x-slot>

    <x-admin::form
        :action="route('admin.email_templates.store')"
        enctype="multipart/form-data"
        method="POST"
        id="emailTemplateForm"
    >
        @csrf

        <div class="flex flex-col gap-4">
            <!-- Header -->
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    <x-admin::breadcrumbs name="settings.email_templates.create" />

                    <div class="text-xl font-bold dark:text-white">
                        @lang('email_template_extended::app.templates.create.title')
                    </div>
                </div>

                <div class="flex items-center gap-x-2.5">
                    <button type="submit" class="primary-button" id="saveBtn">
                        @lang('email_template_extended::app.templates.create.save-btn')
                    </button>

                    <button type="button" class="secondary-button" id="previewBtn">
                        <span class="icon-eye text-2xl"></span>
                        Preview
                    </button>

                    <a 
                        href="{{ route('admin.email_templates.index') }}" 
                        class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                    >
                        @lang('email_template_extended::app.templates.create.back-btn')
                    </a>
                </div>
            </div>

            <!-- Form Content -->
            <div class="flex gap-2.5 max-xl:flex-wrap">
                <!-- Left Section -->
                <div class="flex flex-1 flex-col gap-2">
                    <!-- Basic Information -->
                    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('email_template_extended::app.templates.create.basic-info')
                        </p>

                        <!-- Name -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('email_template_extended::app.templates.create.name')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="name"
                                id="name"
                                rules="required"
                                :value="old('name')"
                                :label="trans('email_template_extended::app.templates.create.name')"
                                :placeholder="trans('email_template_extended::app.templates.create.name')"
                            />

                            <x-admin::form.control-group.error control-name="name" />
                        </x-admin::form.control-group>

                        <!-- Subject -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('email_template_extended::app.templates.create.subject')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="subject"
                                id="subject"
                                rules="required"
                                :value="old('subject')"
                                :label="trans('email_template_extended::app.templates.create.subject')"
                                :placeholder="trans('email_template_extended::app.templates.create.subject')"
                            />

                            <x-admin::form.control-group.error control-name="subject" />
                        </x-admin::form.control-group>

                        <!-- Preview Text -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('email_template_extended::app.templates.create.preview-text')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                name="preview_text"
                                id="preview_text"
                                rows="2"
                                :value="old('preview_text')"
                                :label="trans('email_template_extended::app.templates.create.preview-text')"
                                :placeholder="trans('email_template_extended::app.templates.create.preview-text-hint')"
                            />

                            <x-admin::form.control-group.error control-name="preview_text" />
                        </x-admin::form.control-group>
                    </div>

                    <!-- Editor Mode Selector -->
                    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            Chọn Editor Mode
                        </p>

                        <div class="flex gap-3">
                            <div class="flex flex-1 cursor-pointer items-center justify-center gap-2 rounded-lg border-2 border-blue-500 bg-blue-50 p-4 transition-all hover:border-blue-600 dark:border-blue-700 dark:bg-blue-900/20" id="classicModeLabel">
                                <input type="radio" name="editor_mode" value="classic" checked id="classicMode" class="sr-only">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="icon-code text-3xl text-gray-600 dark:text-gray-300"></span>
                                    <span class="font-semibold text-gray-800 dark:text-white">Classic Editor</span>
                                    <span class="text-xs text-center text-gray-600 dark:text-gray-400">HTML trực tiếp (TinyMCE)</span>
                                </div>
                            </div>

                            <div class="flex flex-1 cursor-pointer items-center justify-center gap-2 rounded-lg border-2 border-gray-300 bg-white p-4 transition-all hover:border-green-500 dark:border-gray-700 dark:bg-gray-800" id="proModeLabel">
                                <input type="radio" name="editor_mode" value="pro" id="proMode" class="sr-only">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="icon-magic text-3xl text-gray-600 dark:text-gray-300"></span>
                                    <span class="font-semibold text-gray-800 dark:text-white">Pro Builder</span>
                                    <span class="text-xs text-center text-gray-600 dark:text-gray-400">Kéo thả không cần code</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Editor -->
                    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                        <div class="mb-4 flex items-center justify-between">
                            <p class="text-base font-semibold text-gray-800 dark:text-white">
                                @lang('email_template_extended::app.templates.create.content')
                            </p>
                            <p class="text-xs text-gray-600 dark:text-gray-300" id="editorModeIndicator">
                                <span class="rounded bg-blue-100 px-2 py-1 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    Classic Mode
                                </span>
                            </p>
                        </div>

                        <!-- Classic Editor (TinyMCE) -->
                        <div id="classicEditor">
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.control
                                    type="textarea"
                                    name="content"
                                    id="content"
                                    rules="required"
                                    :value="old('content')"
                                    :tinymce="true"
                                    :label="trans('email_template_extended::app.templates.create.content')"
                                />

                                <x-admin::form.control-group.error control-name="content" />
                                
                                <p class="mt-2 text-xs text-gray-600 dark:text-gray-300">
                                    @lang('email_template_extended::app.templates.create.use-variables')
                                    <code class="rounded bg-gray-100 px-1 dark:bg-gray-800">&#123;&#123;customer_name&#125;&#125;</code>
                                    <code class="rounded bg-gray-100 px-1 dark:bg-gray-800">&#123;&#123;company_name&#125;&#125;</code>
                                </p>
                            </x-admin::form.control-group>
                        </div>

                        <!-- Pro Builder (EmailBuilder.js) -->
                        <div id="proBuilder" style="display: none;">
                            <div class="overflow-hidden rounded-lg border border-gray-300 dark:border-gray-700">
                            <iframe 
                                id="emailBuilderIframe" 
                                src="{{ asset('vendor/emailtemplateextended/email-builder/index.html') }}"
                                style="width: 100%; height: 700px; border: none; background: white;">
                            </iframe>
                            </div>
                            <div class="mt-3 rounded-lg bg-green-50 p-3 dark:bg-green-900/20">
                                <p class="flex items-start gap-2 text-sm text-gray-700 dark:text-gray-300">
                                    <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                    <span>Sử dụng drag & drop để thiết kế email. Nhấn "Save" để lưu template.</span>
                                </p>
                            </div>
                        </div>

                        <!-- Hidden fields -->
                        <input type="hidden" name="builder_config" id="builderConfig">
                        <input type="hidden" name="generated_html" id="generatedHtml">
                    </div>
                </div>

                <!-- Right Section -->
                <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">
                    <!-- Settings -->
                    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('email_template_extended::app.templates.create.settings')
                        </p>

                        <!-- Category -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('email_template_extended::app.templates.create.category')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="category"
                                id="category"
                                rules="required"
                                :value="old('category', 'general')"
                                :label="trans('email_template_extended::app.templates.create.category')"
                            >
                                <option value="">@lang('email_template_extended::app.templates.create.select-category')</option>
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="category" />
                        </x-admin::form.control-group>

                        <!-- Locale -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('email_template_extended::app.templates.create.locale')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="locale"
                                id="locale"
                                rules="required"
                                :value="old('locale', 'vi')"
                                :label="trans('email_template_extended::app.templates.create.locale')"
                            >
                                <option value="vi">Tiếng Việt</option>
                                <option value="en">English</option>
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="locale" />
                        </x-admin::form.control-group>

                        <!-- Tags -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('email_template_extended::app.templates.create.tags')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="tags"
                                id="tags"
                                :value="old('tags')"
                                :label="trans('email_template_extended::app.templates.create.tags')"
                                :placeholder="trans('email_template_extended::app.templates.create.tags-hint')"
                            />

                            <x-admin::form.control-group.error control-name="tags" />
                            
                            <p class="mt-1 text-xs text-gray-600 dark:text-gray-300">
                                @lang('email_template_extended::app.templates.create.tags-hint')
                            </p>
                        </x-admin::form.control-group>

                        <!-- Active Status -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.control
                                type="checkbox"
                                name="is_active"
                                id="is_active"
                                value="1"
                                :checked="old('is_active', true)"
                                for="is_active"
                            />

                            <x-admin::form.control-group.label 
                                for="is_active"
                                class="cursor-pointer !text-sm !font-normal !text-gray-600 dark:!text-gray-300"
                            >
                                @lang('email_template_extended::app.templates.create.is-active')
                            </x-admin::form.control-group.label>
                        </x-admin::form.control-group>
                    </div>

                    <!-- Variables Hint -->
                    <x-admin::accordion>
                        <x-slot:header>
                            <p class="text-base font-semibold text-gray-800 dark:text-white">
                                @lang('email_template_extended::app.templates.create.variables')
                            </p>
                        </x-slot>

                        <x-slot:content>
                            <p class="mb-3 text-xs text-gray-600 dark:text-gray-300">
                                @lang('email_template_extended::app.templates.create.variables-hint')
                            </p>

                            <div class="space-y-2 text-xs">
                                <div class="flex items-center gap-2">
                                    <code class="rounded bg-blue-50 px-2 py-1 text-blue-700 dark:bg-blue-900 dark:text-blue-200">&#123;&#123;customer_name&#125;&#125;</code>
                                    <span class="text-gray-600 dark:text-gray-400">Tên khách hàng</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <code class="rounded bg-blue-50 px-2 py-1 text-blue-700 dark:bg-blue-900 dark:text-blue-200">&#123;&#123;company_name&#125;&#125;</code>
                                    <span class="text-gray-600 dark:text-gray-400">Tên công ty</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <code class="rounded bg-blue-50 px-2 py-1 text-blue-700 dark:bg-blue-900 dark:text-blue-200">&#123;&#123;email&#125;&#125;</code>
                                    <span class="text-gray-600 dark:text-gray-400">Địa chỉ email</span>
                                </div>
                            </div>
                        </x-slot>
                    </x-admin::accordion>
                </div>
            </div>
        </div>
    </x-admin::form>

    @push('scripts')
    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                console.log('Initializing editor mode switcher...');
                
                const form = document.getElementById('emailTemplateForm');
                const classicEditor = document.getElementById('classicEditor');
                const proBuilder = document.getElementById('proBuilder');
                const iframe = document.getElementById('emailBuilderIframe');
                const indicator = document.getElementById('editorModeIndicator');
                
                const classicModeLabel = document.getElementById('classicModeLabel');
                const proModeLabel = document.getElementById('proModeLabel');
                const classicMode = document.getElementById('classicMode');
                const proMode = document.getElementById('proMode');

                console.log('Elements found:', {
                    classicModeLabel: !!classicModeLabel,
                    proModeLabel: !!proModeLabel,
                    classicMode: !!classicMode,
                    proMode: !!proMode
                });

                if (!classicModeLabel || !proModeLabel) {
                    console.error('Elements not found!');
                    return;
                }

                // Switch Editor Mode
                function switchMode(mode) {
                    console.log('Switching to:', mode);
                    
                    if (mode === 'classic') {
                        classicEditor.style.display = 'block';
                        proBuilder.style.display = 'none';
                        indicator.innerHTML = '<span class="rounded bg-blue-100 px-2 py-1 text-blue-800 dark:bg-blue-900 dark:text-blue-200">Classic Mode</span>';
                        
                        classicModeLabel.classList.remove('border-gray-300', 'bg-white');
                        classicModeLabel.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                        proModeLabel.classList.remove('border-green-500', 'bg-green-50', 'dark:bg-green-900/20');
                        proModeLabel.classList.add('border-gray-300', 'bg-white', 'dark:bg-gray-800');
                    } else {
                        classicEditor.style.display = 'none';
                        proBuilder.style.display = 'block';
                        indicator.innerHTML = '<span class="rounded bg-green-100 px-2 py-1 text-green-800 dark:bg-green-900 dark:text-green-200">Pro Builder Mode</span>';
                        
                        proModeLabel.classList.remove('border-gray-300', 'bg-white', 'dark:bg-gray-800');
                        proModeLabel.classList.add('border-green-500', 'bg-green-50', 'dark:bg-green-900/20');
                        classicModeLabel.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                        classicModeLabel.classList.add('border-gray-300', 'bg-white');
                    }
                }

                // Click handlers
                classicModeLabel.addEventListener('click', function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                    console.log('Classic label clicked');
                    classicMode.checked = true;
                    switchMode('classic');
                }, true);

                proModeLabel.addEventListener('click', function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                    console.log('Pro label clicked');
                    proMode.checked = true;
                    switchMode('pro');
                }, true);

                console.log('Event listeners attached');

                var builderReady = false; 
                window.addEventListener('message', function(event) {
                    if (event.data && event.data.type === 'emailBuilderReady') {
                        builderReady = true;
                        console.log('Builder ready');
                        
                        setTimeout(function() {
                            try {
                                var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                                var buttons = iframeDoc.querySelectorAll('button');
                                buttons.forEach(function(btn) {
                                    btn.disabled = false;
                                    btn.style.pointerEvents = 'auto';
                                });
                                console.log('Enabled buttons:', buttons.length);
                            } catch (e) {
                                console.log('Cannot access iframe');
                            }
                        }, 500);
                    }
                });
                
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const mode = document.querySelector('input[name="editor_mode"]:checked').value;
                    const formData = new FormData(form);

                    if (mode === 'pro') {
                        try {
                            // Request export từ Pro Builder
                            iframe.contentWindow.postMessage({ action: 'export' }, '*');
                            
                            // Đợi response từ Pro Builder
                            const emailData = await new Promise((resolve, reject) => {
                                const timeout = setTimeout(() => reject(new Error('Timeout: EmailBuilder không phản hồi')), 10000);
                                
                                const handler = (event) => {
                                    if (event.data.type === 'emailBuilderExport') {
                                        clearTimeout(timeout);
                                        window.removeEventListener('message', handler);
                                        resolve(event.data);
                                    }
                                };
                                
                                window.addEventListener('message', handler);
                            });

                            formData.set('builder_config', JSON.stringify(emailData.design));
                            formData.set('content', emailData.html);
                            formData.set('generated_html', emailData.html); // ← QUAN TRỌNG!
                            
                        } catch (error) {
                            alert('Lỗi khi export Pro Builder: ' + error.message);
                            return;
                        }
                    } else {
                        // Classic mode - lấy từ TinyMCE
                        const tinymceContent = tinymce.get('content')?.getContent() || '';
                        formData.set('content', tinymceContent);
                    }

                    const saveBtn = document.getElementById('saveBtn');
                    const originalText = saveBtn.innerHTML;
                    saveBtn.disabled = true;
                    saveBtn.innerHTML = '<span class="icon-spinner2 spinner"></span> Đang lưu...';

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (response.ok) {
                            window.location.href = '{{ route("admin.email_templates.index") }}';
                        } else {
                            const result = await response.json();
                            alert('Lỗi: ' + (result.message || 'Không thể lưu template'));
                        }
                    } catch (error) {
                        alert('Lỗi: ' + error.message);
                    } finally {
                        saveBtn.disabled = false;
                        saveBtn.innerHTML = originalText;
                    }
                });

                // Preview Button
                document.getElementById('previewBtn').addEventListener('click', async function() {
                    const mode = document.querySelector('input[name="editor_mode"]:checked').value;
                    const previewBtn = this;
                    const originalHTML = previewBtn.innerHTML;
                    
                    previewBtn.disabled = true;
                    previewBtn.innerHTML = '<svg class="h-4 w-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Loading...';

                    try {
                        let content = '';
                        let builderConfig = '';

                        if (mode === 'pro') {
                            iframe.contentWindow.postMessage({ action: 'export' }, '*');
                            
                            const emailData = await new Promise((resolve, reject) => {
                                const timeout = setTimeout(() => reject(new Error('Timeout')), 5000);
                                
                                const handler = (event) => {
                                    if (event.data.type === 'emailBuilderExport') {
                                        clearTimeout(timeout);
                                        window.removeEventListener('message', handler);
                                        resolve(event.data);
                                    }
                                };
                                
                                window.addEventListener('message', handler);
                            });
                            
                            content = emailData.html;
                            builderConfig = JSON.stringify(emailData.design);
                        } else {
                            content = tinymce.get('content')?.getContent() || '';
                        }

                        if (!content || content.trim() === '') {
                            alert('No content to preview');
                            previewBtn.disabled = false;
                            previewBtn.innerHTML = originalHTML;
                            return;
                        }

                        const previewForm = document.createElement('form');
                        previewForm.method = 'POST';
                        previewForm.action = '{{ route("admin.email_templates.preview_draft") }}';
                        previewForm.target = '_blank';
                        previewForm.style.display = 'none';

                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = '{{ csrf_token() }}';
                        previewForm.appendChild(csrfInput);

                        const inputs = {
                            name: document.getElementById('name')?.value || 'Preview Template',
                            subject: document.getElementById('subject')?.value || 'Preview Subject',
                            preview_text: document.getElementById('preview_text')?.value || '',
                            content: content,
                            editor_mode: mode,
                            category: document.getElementById('category')?.value || 'general',
                            locale: document.getElementById('locale')?.value || 'vi',
                            builder_config: builderConfig || null
                        };

                        for (const [key, value] of Object.entries(inputs)) {
                            if (value !== null && value !== '') {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = key;
                                input.value = value;
                                previewForm.appendChild(input);
                            }
                        }

                        document.body.appendChild(previewForm);
                        previewForm.submit();
                        document.body.removeChild(previewForm);

                    } catch (error) {
                        alert('Cannot preview: ' + error.message);
                    } finally {
                        previewBtn.disabled = false;
                        previewBtn.innerHTML = originalHTML;
                    }
                });
            }, 500); 
        });
    </script>
    @endpush
</x-admin::layouts>