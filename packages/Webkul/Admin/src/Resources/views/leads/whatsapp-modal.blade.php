<x-admin::modal>
    <x-slot:header>
        <h3 class="text-lg font-bold">
            <i class="icon-whatsapp mr-2"></i>
            @lang('admin::app.leads.send-whatsapp-message.title')
        </h3>
    </x-slot>

    <x-slot:content>
        @include('admin::leads.send-whatsapp-message')
    </x-slot>
</x-admin::modal>
