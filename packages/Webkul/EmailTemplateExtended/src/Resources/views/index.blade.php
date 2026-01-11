<x-admin::layouts>
    <x-slot:title>
        @lang('email_template_extended::app.templates.index.title')
    </x-slot>

    <div class="flex flex-col gap-4">
        <!-- Header -->
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-2">
                <x-admin::breadcrumbs name="settings.email_templates" />

                <div class="text-xl font-bold dark:text-white">
                    @lang('email_template_extended::app.templates.index.title')
                </div>
            </div>

            <div class="flex items-center gap-x-2.5">
                <a 
                    href="{{ route('admin.email_templates.create') }}" 
                    class="primary-button"
                >
                    @lang('email_template_extended::app.templates.index.create-btn')
                </a>
            </div>
        </div>

        <!-- DataGrid -->
        <x-admin::datagrid :src="route('admin.email_templates.index')" />
    </div>
</x-admin::layouts> 