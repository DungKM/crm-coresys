<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.lead-assignment.index.title')
        </x-slot>

        <div class="flex flex-col gap-4">
            <div
                class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    <!-- Breadcrumbs -->
                    <x-admin::breadcrumbs name="settings.lead_assignment" />

                    <div class="text-xl font-bold dark:text-white">
                        @lang('admin::app.settings.lead-assignment.index.title')
                    </div>
                </div>

                <div class="flex items-center gap-x-2.5">
                    {!! view_render_event('admin.settings.lead_assignment.save_button.before') !!}

                    <!-- Save Button -->
                    <button type="submit" form="lead-assignment-form" class="primary-button">
                        @lang('admin::app.settings.lead-assignment.index.save-btn')
                    </button>

                    {!! view_render_event('admin.settings.lead_assignment.save_button.after') !!}
                </div>
            </div>

            {!! view_render_event('admin.settings.lead_assignment.content.before') !!}

            <!-- Use the registered Vue component -->
            <lead-assignment-settings :sales-users='@json($salesUsers)'
                :lead-assignment-config='@json($leadAssignmentConfig)'></lead-assignment-settings>

            {!! view_render_event('admin.settings.lead_assignment.content.after') !!}
        </div>

        @pushOnce('scripts')
            @vite('packages/Webkul/LeadAssignment/src/Resources/assets/js/app.js')
        @endPushOnce
</x-admin::layouts>