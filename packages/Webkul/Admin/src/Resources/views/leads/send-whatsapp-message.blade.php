<?php
$whatsappMessage = core()->getConfigData('whatsapp.settings.message');
?>

<x-admin::modal id="send-whatsapp-message-modal">
    <x-slot:toggle>
        <button type="button" class="primary-button">
            @lang('admin::app.leads.send-whatsapp-message.title')
        </button>
    </x-slot:toggle>

    <x-slot:header>
        @lang('admin::app.leads.send-whatsapp-message.title')
    </x-slot:header>

    <x-slot:content>
        <x-admin::form :action="route('admin.leads.send_whatsapp_message', $lead->id)" enctype="multipart/form-data">
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required">
                    @lang('admin::app.leads.send-whatsapp-message.message')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control type="textarea" name="message" id="message" rules="required"
                    :value="old('message') ?? $whatsappMessage" :label="trans('admin::app.leads.send-whatsapp-message.message')" :placeholder="trans('admin::app.leads.send-whatsapp-message.message')">
                </x-admin::form.control-group.control>

                <x-admin::form.control-group.error control-name="message"></x-admin::form.control-group.error>
            </x-admin::form.control-group>
        </x-admin::form>
    </x-slot:content>

    <x-slot:footer>
        <button type="submit" class="primary-button">
            @lang('admin::app.leads.send-whatsapp-message.send')
        </button>
    </x-slot:footer>
</x-admin::modal>
