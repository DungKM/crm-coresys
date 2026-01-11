<x-admin::layouts>
    <x-slot:title>
        @lang('email_template_extended::app.templates.edit.title')
    </x-slot>

    <x-admin::form
        :action="route('admin.email_templates.update', $emailTemplate->id)"
        enctype="multipart/form-data"
        method="PUT"
        id="emailTemplateForm"
    >
        <div class="flex flex-col gap-4">
            <!-- Header -->
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2 text-xl font-bold dark:text-white">
                        @lang('email_template_extended::app.templates.edit.title')
                        <span class="rounded px-2 py-1 text-xs {{ $emailTemplate->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                            {{ $emailTemplate->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-x-2.5">
                    <!-- Save Button -->
                    <button type="button" class="primary-button" id="saveBtn">
                        @lang('email_template_extended::app.templates.edit.save-btn')
                    </button>

                    <!-- Preview Button -->
                    <button type="button" class="secondary-button" id="previewBtn">
                        <span class="icon-eye"></span>
                        Preview
                    </button>

                    <!-- Back Button -->
                    <a 
                        href="{{ route('admin.email_templates.index') }}" 
                        class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                    >
                        @lang('email_template_extended::app.templates.edit.back-btn')
                    </a>
                </div>
            </div>

            <!-- Statistics Bar -->
            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <div class="grid grid-cols-4 gap-4 max-md:grid-cols-2">
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-600 dark:text-gray-300">Usage Count</span>
                        <span class="text-lg font-semibold text-gray-800 dark:text-white">{{ $emailTemplate->usage_count }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-600 dark:text-gray-300">Last Used</span>
                        <span class="text-lg font-semibold text-gray-800 dark:text-white">
                            {{ $emailTemplate->last_used_at ? $emailTemplate->last_used_at->diffForHumans() : 'Never' }}
                        </span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-600 dark:text-gray-300">Created</span>
                        <span class="text-lg font-semibold text-gray-800 dark:text-white">{{ $emailTemplate->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($emailTemplate->cloned_from_id)
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-600 dark:text-gray-300">Cloned From</span>
                            <a href="{{ route('admin.email_templates.show', $emailTemplate->cloned_from_id) }}" class="text-lg font-semibold text-blue-600 hover:underline">
                                #{{ $emailTemplate->cloned_from_id }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Warning for Undefined Variables -->
            @if($emailTemplate->hasUndefinedVariables())
                @php
                    $undefinedVars = array_map(function($var) {
                        return "{{" . $var . "}}";
                    }, $emailTemplate->getUndefinedVariables());
                @endphp
                <div class="flex items-start gap-2.5 rounded-lg border border-yellow-300 bg-yellow-50 p-4 dark:border-yellow-800 dark:bg-yellow-900/20">
                    <span class="icon-information text-2xl text-yellow-600 dark:text-yellow-400"></span>
                    <div class="flex flex-col gap-1">
                        <p class="font-semibold text-yellow-800 dark:text-yellow-300">
                            @lang('email_template_extended::app.templates.edit.undefined-variables-warning')
                        </p>
                        <p class="text-sm text-yellow-700 dark:text-yellow-400">
                            {{ implode(', ', $undefinedVars) }}
                        </p>
                    </div>
                </div>
            @endif

            <!-- Form Content -->
            <div class="flex gap-2.5 max-xl:flex-wrap">
                <!-- Left Section -->
                <div class="flex flex-1 flex-col gap-2">
                    <!-- Basic Information -->
                    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('email_template_extended::app.templates.edit.basic-info')
                        </p>

                        <!-- Name -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('email_template_extended::app.templates.edit.name')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="name"
                                id="name"
                                rules="required"
                                :value="old('name', $emailTemplate->name)"
                                :label="trans('email_template_extended::app.templates.edit.name')"
                                :placeholder="trans('email_template_extended::app.templates.edit.name')"
                            />

                            <x-admin::form.control-group.error control-name="name" />
                        </x-admin::form.control-group>

                        <!-- Subject -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('email_template_extended::app.templates.edit.subject')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="subject"
                                id="subject"
                                rules="required"
                                :value="old('subject', $emailTemplate->subject)"
                                :label="trans('email_template_extended::app.templates.edit.subject')"
                                :placeholder="trans('email_template_extended::app.templates.edit.subject')"
                            />

                            <x-admin::form.control-group.error control-name="subject" />
                        </x-admin::form.control-group>

                        <!-- Preview Text -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('email_template_extended::app.templates.edit.preview-text')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                name="preview_text"
                                id="preview_text"
                                rows="2"
                                :value="old('preview_text', $emailTemplate->preview_text)"
                                :label="trans('email_template_extended::app.templates.edit.preview-text')"
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
                            <div class="flex flex-1 cursor-pointer items-center justify-center gap-2 rounded-lg border-2 p-4 transition-all
                                {{ $emailTemplate->editor_mode === 'classic' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-300 bg-white dark:bg-gray-800' }}" 
                                id="classicModeLabel">
                                <input type="radio" name="editor_mode" value="classic" 
                                    {{ $emailTemplate->editor_mode === 'classic' ? 'checked' : '' }} 
                                    id="classicMode" class="sr-only">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="icon-code text-3xl text-gray-600 dark:text-gray-300"></span>
                                    <span class="font-semibold text-gray-800 dark:text-white">Classic Editor</span>
                                    <span class="text-xs text-center text-gray-600 dark:text-gray-400">HTML trực tiếp (TinyMCE)</span>
                                </div>
                            </div>

                            <div class="flex flex-1 cursor-pointer items-center justify-center gap-2 rounded-lg border-2 p-4 transition-all
                                {{ $emailTemplate->editor_mode === 'pro' ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-gray-300 bg-white dark:bg-gray-800' }}" 
                                id="proModeLabel">
                                <input type="radio" name="editor_mode" value="pro" 
                                    {{ $emailTemplate->editor_mode === 'pro' ? 'checked' : '' }} 
                                    id="proMode" class="sr-only">
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
                                @lang('email_template_extended::app.templates.edit.content')
                            </p>
                            <p class="text-xs text-gray-600 dark:text-gray-300" id="editorModeIndicator">
                                <span class="rounded px-2 py-1 {{ $emailTemplate->editor_mode === 'pro' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                    {{ $emailTemplate->editor_mode === 'pro' ? 'Pro Builder Mode' : 'Classic Mode' }}
                                </span>
                            </p>
                        </div>

                        <!-- Classic Editor (TinyMCE) -->
                        <div id="classicEditor" style="{{ $emailTemplate->editor_mode === 'pro' ? 'display: none;' : '' }}">
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.control
                                    type="textarea"
                                    name="content"
                                    id="content"
                                    rules="required"
                                    :value="old('content', $emailTemplate->content)"
                                    :tinymce="true"
                                    :label="trans('email_template_extended::app.templates.edit.content')"
                                />

                                <x-admin::form.control-group.error control-name="content" />
                                
                                <p class="mt-2 text-xs text-gray-600 dark:text-gray-300">
                                    @lang('email_template_extended::app.templates.create.use-variables')
                                    <code class="rounded bg-gray-100 px-1 dark:bg-gray-800">&#123;&#123;customer_name&#125;&#125;</code>
                                    <code class="rounded bg-gray-100 px-1 dark:bg-gray-800">&#123;&#123;company_name&#125;&#125;</code>
                                </p>
                            </x-admin::form.control-group>
                        </div>

                        <!-- Pro Builder -->
                        <div id="proBuilder" style="{{ $emailTemplate->editor_mode === 'classic' ? 'display: none;' : '' }}">
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
                        <input type="hidden" name="builder_config" id="builderConfig" value="{{ $emailTemplate->builder_config ? $emailTemplate->builder_config : '' }}">
                        <input type="hidden" name="generated_html" id="generatedHtml">
                    </div>

                    <!-- Variables Analysis -->
                    @if($emailTemplate->getAllUsedVariables())
                        <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                            <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                                @lang('email_template_extended::app.templates.edit.variables-used')
                            </p>

                            <div class="flex flex-wrap gap-2">
                                @foreach($emailTemplate->getAllUsedVariables() as $var)
                                    <span class="rounded bg-gray-100 px-3 py-1 text-sm font-mono dark:bg-gray-800 dark:text-white">
                                        @{{ $var }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Section -->
                <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">
                    <!-- Settings -->
                    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('email_template_extended::app.templates.edit.settings')
                        </p>

                        <!-- Category -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('email_template_extended::app.templates.edit.category')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="category"
                                id="category"
                                rules="required"
                                :value="old('category', $emailTemplate->category)"
                                :label="trans('email_template_extended::app.templates.edit.category')"
                            >
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" {{ $emailTemplate->category == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="category" />
                        </x-admin::form.control-group>

                        <!-- Locale -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('email_template_extended::app.templates.edit.locale')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="locale"
                                id="locale"
                                rules="required"
                                :value="old('locale', $emailTemplate->locale)"
                                :label="trans('email_template_extended::app.templates.edit.locale')"
                            >
                                <option value="vi" {{ $emailTemplate->locale == 'vi' ? 'selected' : '' }}>Tiếng Việt</option>
                                <option value="en" {{ $emailTemplate->locale == 'en' ? 'selected' : '' }}>English</option>
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="locale" />
                        </x-admin::form.control-group>

                        <!-- Tags -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('email_template_extended::app.templates.edit.tags')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="tags"
                                id="tags"
                                :value="old('tags', is_array($emailTemplate->tags) ? implode(', ', $emailTemplate->tags) : '')"
                                :label="trans('email_template_extended::app.templates.edit.tags')"
                                :placeholder="trans('email_template_extended::app.templates.edit.tags-hint')"
                            />

                            <x-admin::form.control-group.error control-name="tags" />
                        </x-admin::form.control-group>

                        <!-- Active Status -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.control
                                type="checkbox"
                                name="is_active"
                                id="is_active"
                                value="1"
                                :checked="old('is_active', $emailTemplate->is_active)"
                                for="is_active"
                            />

                            <x-admin::form.control-group.label 
                                for="is_active"
                                class="cursor-pointer !text-sm !font-normal !text-gray-600 dark:!text-gray-300"
                            >
                                @lang('email_template_extended::app.templates.edit.is-active')
                            </x-admin::form.control-group.label>
                        </x-admin::form.control-group>
                    </div>

                    <!-- Quick Actions -->
                    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('email_template_extended::app.templates.edit.quick-actions')
                        </p>

                        <div class="flex flex-col gap-2">
                            <!-- Clone -->
                            <form action="{{ route('admin.email_templates.clone', $emailTemplate->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="secondary-button w-full">
                                    <span class="icon-copy text-xl"></span>
                                    @lang('email_template_extended::app.templates.edit.clone-btn')
                                </button>
                            </form>

                            <!-- Export -->
                            <button type="button" onclick="openExportModal()" class="secondary-button w-full">
                                <span class="icon-export text-xl"></span>
                                @lang('email_template_extended::app.templates.edit.export-btn')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-admin::form>

    {{-- EXPORT MODAL--}}
    <div id="exportModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 9999;">
        {{-- Overlay tối --}}
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.85); backdrop-filter: blur(4px);" onclick="closeExportModal()"></div>
        
        {{-- Modal Content --}}
        <div style="position: relative; z-index: 10000; display: flex; align-items: center; justify-content: center; height: 100%; padding: 1rem;">
            <div class="animate-scale-in" style="position: relative;">
                {{-- Close Button --}}
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

                {{-- Export Options --}}
                <div style="display: flex; gap: 24px;">
                    {{-- HTML Export --}}
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

                    {{-- JSON Export --}}
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

                    {{-- ZIP Export --}}
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
        (function() {
            'use strict';
            
            const builderConfigInput = document.getElementById('builderConfig');
            let builderConfig = builderConfigInput?.value;
            
            if (!builderConfig || builderConfig === 'undefined' || builderConfig === 'null' || builderConfig.trim() === '') {
                builderConfig = null;
            }
            
            let designToLoad = null;
            
            if (builderConfig) {
                try {
                    let parsed = JSON.parse(builderConfig);
                    designToLoad = parsed;
                } catch (e) {
                    designToLoad = null;
                }
            }
            
            localStorage.removeItem('emailDesign');
            localStorage.removeItem('email-builder-design');
            localStorage.removeItem('__PENDING_DESIGN__');
            
            let builderReady = false;
            let iframeWindow = null;
            let designSent = false;
            
            const form = document.getElementById('emailTemplateForm');
            const iframe = document.getElementById('emailBuilderIframe');
            const indicator = document.getElementById('editorModeIndicator');
            const classicModeLabel = document.getElementById('classicModeLabel');
            const proModeLabel = document.getElementById('proModeLabel');
            const classicMode = document.getElementById('classicMode');
            const proMode = document.getElementById('proMode');
            const saveBtn = document.getElementById('saveBtn');
            const previewBtn = document.getElementById('previewBtn');
            
            if (!form || !iframe || !saveBtn) return;
            
            window.addEventListener('message', function(event) {
                if (!event.data || !event.data.type) return;
                
                if (event.data.type === 'emailBuilderReady') {
                    builderReady = true;
                    iframeWindow = event.source;
                    
                    if (designToLoad && !designSent) {
                        setTimeout(function() {
                            iframeWindow.postMessage({
                                action: 'load',
                                design: designToLoad
                            }, '*');
                            designSent = true;
                        }, 300);
                    }
                } else if (event.data.type === 'emailBuilderError') {
                    alert('Cannot load design: ' + event.data.error);
                }
            });
            
            function switchMode(mode) {
                const classicEditor = document.getElementById('classicEditor');
                const proBuilder = document.getElementById('proBuilder');
                
                if (mode === 'classic') {
                    classicEditor.style.display = 'block';
                    proBuilder.style.display = 'none';
                    indicator.innerHTML = '<span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-blue-600/20">Classic Editor</span>';
                } else {
                    classicEditor.style.display = 'none';
                    proBuilder.style.display = 'block';
                    indicator.innerHTML = '<span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20">Pro Builder</span>';
                }
            }
            
            classicModeLabel?.addEventListener('click', function(e) {
                e.preventDefault();
                classicMode.checked = true;
                switchMode('classic');
            });
            
            proModeLabel?.addEventListener('click', function(e) {
                e.preventDefault();
                proMode.checked = true;
                switchMode('pro');
            });
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            });
            
            async function handleSave() {
                const currentSaveBtn = document.getElementById('saveBtn');
                const mode = document.querySelector('input[name="editor_mode"]:checked').value;
                
                currentSaveBtn.disabled = true;
                currentSaveBtn.innerHTML = '<svg class="h-4 w-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';
                
                if (mode === 'pro') {
                    if (!builderReady) {
                        alert('Builder is not ready. Please wait and try again.');
                        currentSaveBtn.disabled = false;
                        currentSaveBtn.innerHTML = '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Save';
                        return;
                    }
                    
                    if (!iframeWindow) {
                        alert('Cannot connect to builder. Please refresh the page.');
                        currentSaveBtn.disabled = false;
                        currentSaveBtn.innerHTML = '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Save';
                        return;
                    }
                    
                    try {
                        const exportData = await new Promise((resolve, reject) => {
                            const timeout = setTimeout(() => {
                                reject(new Error('Export timeout after 15 seconds'));
                            }, 15000);
                            
                            const handler = function(event) {
                                if (event.source !== iframeWindow) return;
                                
                                if (event.data && event.data.type === 'emailBuilderExport') {
                                    clearTimeout(timeout);
                                    window.removeEventListener('message', handler);
                                    resolve(event.data);
                                }
                                
                                if (event.data && event.data.type === 'emailBuilderError') {
                                    clearTimeout(timeout);
                                    window.removeEventListener('message', handler);
                                    reject(new Error(event.data.error || 'Unknown export error'));
                                }
                            };
                            
                            window.addEventListener('message', handler);
                            iframeWindow.postMessage({ action: 'export' }, '*');
                        });
                        
                        if (!exportData) {
                            throw new Error('No data received from builder');
                        }
                        
                        if (!exportData.html || typeof exportData.html !== 'string') {
                            throw new Error('Export missing HTML content');
                        }
                        
                        if (!exportData.design || typeof exportData.design !== 'object') {
                            throw new Error('Export missing design config');
                        }
                        
                        const htmlTrimmed = exportData.html.trim();
                        if (htmlTrimmed.length === 0) {
                            throw new Error('HTML export is empty');
                        }
                        
                        const textContent = htmlTrimmed.replace(/<[^>]*>/g, '').trim();
                        if (textContent.length === 0) {
                            throw new Error('HTML has no text content');
                        }
                        
                        const formElement = document.getElementById('emailTemplateForm');
                        if (!formElement) {
                            throw new Error('Form element not found');
                        }
                        
                        const formData = new FormData(formElement);
                        formData.set('generated_html', exportData.html);
                        formData.set('builder_config', JSON.stringify(exportData.design));
                        formData.set('content', exportData.html);
                        
                        if (!formData.has('_method')) {
                            formData.set('_method', 'PUT');
                        }
                        
                        const generatedHtmlValue = formData.get('generated_html');
                        if (!generatedHtmlValue || generatedHtmlValue.length === 0) {
                            throw new Error('FormData.generated_html is empty after set');
                        }
                        
                        const response = await fetch(formElement.action, {
                            method: 'POST',
                            body: formData,
                            headers: { 
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });
                        
                        if (response.ok) {
                            alert('Template saved successfully!');
                            window.location.reload();
                        } else {
                            let errorMsg = 'Server error';
                            try {
                                const errorDetails = await response.json();
                                errorMsg = errorDetails.message || errorDetails.error || errorMsg;
                            } catch (e) {}
                            
                            alert('Error: ' + errorMsg);
                            currentSaveBtn.disabled = false;
                            currentSaveBtn.innerHTML = '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Save';
                        }
                    } catch (error) {
                        alert('Error:\n\n' + error.message + '\n\nCheck Console (F12) for details.');
                        currentSaveBtn.disabled = false;
                        currentSaveBtn.innerHTML = '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Save';
                    }
                } else {
                    try {
                        const formElement = document.getElementById('emailTemplateForm');
                        const formData = new FormData(formElement);
                        
                        if (window.tinymce && tinymce.get('content')) {
                            const content = tinymce.get('content').getContent();
                            formData.set('content', content);
                        }
                        
                        if (!formData.has('_method')) {
                            formData.append('_method', 'PUT');
                        }
                        
                        const response = await fetch(formElement.action, {
                            method: 'POST',
                            body: formData,
                            headers: { 
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });
                        
                        if (response.ok) {
                            window.location.reload();
                        } else {
                            const result = await response.json();
                            alert('Error: ' + (result.message || 'Cannot save'));
                            currentSaveBtn.disabled = false;
                            currentSaveBtn.innerHTML = '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Save';
                        }
                    } catch (error) {
                        alert('Error: ' + error.message);
                        currentSaveBtn.disabled = false;
                        currentSaveBtn.innerHTML = '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Save';
                    }
                }
            }

            async function handlePreview() {
                if (!previewBtn) return;
                
                const mode = document.querySelector('input[name="editor_mode"]:checked').value;
                
                previewBtn.disabled = true;
                const originalHTML = previewBtn.innerHTML;
                previewBtn.innerHTML = '<svg class="h-4 w-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span class="ml-2">Loading...</span>';
                
                try {
                    const csrfToken = document.querySelector('input[name="_token"]')?.value;
                    if (!csrfToken) {
                        throw new Error('CSRF token not found');
                    }
                    
                    let previewData = {
                        name: document.getElementById('name')?.value || 'Untitled Template',
                        subject: document.getElementById('subject')?.value || 'No Subject',
                        preview_text: document.getElementById('preview_text')?.value || '',
                        category: document.getElementById('category')?.value || 'general',
                        locale: document.getElementById('locale')?.value || 'vi',
                        editor_mode: mode,
                        _token: csrfToken
                    };
                    
                    if (mode === 'pro') {
                        if (!builderReady || !iframeWindow) {
                            throw new Error('Email builder chưa sẵn sàng. Vui lòng đợi và thử lại.');
                        }
                        
                        const exportData = await new Promise((resolve, reject) => {
                            const timeout = setTimeout(() => {
                                reject(new Error('Export timeout sau 10 giây'));
                            }, 10000);
                            
                            const handler = function(event) {
                                if (event.source !== iframeWindow) return;
                                
                                if (event.data?.type === 'emailBuilderExport') {
                                    clearTimeout(timeout);
                                    window.removeEventListener('message', handler);
                                    resolve(event.data);
                                }
                                
                                if (event.data?.type === 'emailBuilderError') {
                                    clearTimeout(timeout);
                                    window.removeEventListener('message', handler);
                                    reject(new Error(event.data.error || 'Unknown export error'));
                                }
                            };
                            
                            window.addEventListener('message', handler);
                            iframeWindow.postMessage({ action: 'export' }, '*');
                        });
                        
                        if (!exportData?.html) {
                            throw new Error('Không thể export HTML từ builder');
                        }
                        
                        previewData.content = exportData.html;
                        previewData.builder_config = JSON.stringify(exportData.design);
                    } else {
                        if (window.tinymce && tinymce.get('content')) {
                            previewData.content = tinymce.get('content').getContent();
                        } else {
                            const contentTextarea = document.getElementById('content');
                            previewData.content = contentTextarea?.value || '';
                        }
                        
                        if (!previewData.content || previewData.content.trim() === '') {
                            throw new Error('Nội dung email đang trống');
                        }
                    }
                    
                    const previewForm = document.createElement('form');
                    previewForm.method = 'POST';
                    previewForm.action = '{{ route("admin.email_templates.preview", $emailTemplate->id) }}';
                    previewForm.target = '_blank';
                    previewForm.style.display = 'none';
                    
                    Object.keys(previewData).forEach(key => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = previewData[key] || '';
                        previewForm.appendChild(input);
                    });
                    
                    document.body.appendChild(previewForm);
                    previewForm.submit();
                    document.body.removeChild(previewForm);
                } catch (error) {
                    alert('Không thể tạo preview:\n\n' + error.message);
                } finally {
                    previewBtn.disabled = false;
                    previewBtn.innerHTML = originalHTML;
                }
            }
            
            saveBtn.addEventListener('click', handleSave, false);
            
            setTimeout(function() {
                const newBtn = saveBtn.cloneNode(true);
                saveBtn.parentNode.replaceChild(newBtn, saveBtn);
                
                const updatedBtn = document.getElementById('saveBtn');
                updatedBtn.addEventListener('click', function(e) {
                    handleSave(e);
                }, false);
                
                updatedBtn.onclick = function(e) {
                    handleSave(e);
                };
                
                const currentPreviewBtn = document.getElementById('previewBtn');
                if (currentPreviewBtn) {
                    const newPreviewBtn = currentPreviewBtn.cloneNode(true);
                    currentPreviewBtn.parentNode.replaceChild(newPreviewBtn, currentPreviewBtn);
                    
                    const finalPreviewBtn = document.getElementById('previewBtn');
                    finalPreviewBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        handlePreview();
                    }, false);
                    
                    finalPreviewBtn.onclick = function(e) {
                        e.preventDefault();
                        handlePreview();
                        return false;
                    };
                }
            }, 500);
            
            window.handleEmailSave = handleSave;
            window.handleEmailPreview = handlePreview;
            window.testSaveButton = function() {
                handleSave();
            };
            window.testPreviewButton = function() {
                handlePreview();
            };
        })();

        function openExportModal() {
            const modal = document.getElementById('exportModal');
            if (modal) {
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }
        }

        function closeExportModal() {
            const modal = document.getElementById('exportModal');
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeExportModal();
            }
        });

        const style = document.createElement('style');
        style.textContent = `
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
        `;
        document.head.appendChild(style);
        </script>
    @endpush
</x-admin::layouts>