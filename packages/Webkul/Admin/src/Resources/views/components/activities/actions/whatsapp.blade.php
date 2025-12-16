@props([
    'entity' => null,
])

<span
    class="inline-flex w-full cursor-pointer items-center justify-between gap-x-2 rounded-md p-2 text-sm text-gray-600 transition-all hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950"
    @click="$refs.whatsappModal.open()"
>
    <div class="flex items-center gap-x-2">
        <span
            class="icon-whatsapp text-2xl"
            role="button"
            tabindex="0"
        >
        </span>

        <p class="font-semibold">
            @lang('admin::app.leads.send-whatsapp-message.title')
        </p>
    </div>
</span>

<x-admin::form
    v-slot="{ meta, errors, handleSubmit }"
    as="div"
>
    <form @submit="handleSubmit($event, call)">
        <x-admin::modal ref="whatsappModal">
            <x-slot:header>
                <h3 class="text-lg font-bold">
                    <i class="icon-whatsapp mr-2"></i>
                    @lang('admin::app.leads.send-whatsapp-message.title')
                </h3>
            </x-slot:header>

            <x-slot:content>
                <div class="p-4">
                    @if ($entity && $entity->person && $entity->person->contact_numbers && count($entity->person->contact_numbers) > 0)
                        <!-- Message Input -->
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.leads.send-whatsapp-message.message')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                name="message"
                                rules="required"
                                :label="trans('admin::app.leads.send-whatsapp-message.message')"
                                :placeholder="trans('admin::app.leads.send-whatsapp-message.placeholder')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="message" />
                        </x-admin::form.control-group>

                        <!-- Phone Number Display -->
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.leads.send-whatsapp-message.phone-number')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="contact_number"
                                rules="required"
                                :label="trans('admin::app.leads.send-whatsapp-message.phone-number')"
                            >
                                <option value="">@lang('admin::app.leads.send-whatsapp-message.select-phone')</option>
                                @foreach ($entity->person->contact_numbers as $number)
                                    <option value="{{ $number['value'] }}">
                                        {{ $number['value'] }}
                                        @if ($number['label'])
                                            ({{ $number['label'] }})
                                        @endif
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="contact_number" />
                        </x-admin::form.control-group>
                    @else
                        <div class="rounded bg-yellow-50 p-4 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-200">
                            <p class="font-semibold">@lang('admin::app.leads.send-whatsapp-message.no-phone')</p>
                            <p class="text-sm">@lang('admin::app.leads.send-whatsapp-message.add-phone-first')</p>
                        </div>
                    @endif
                </div>
            </x-slot:content>

            <x-slot:footer>
                <div class="flex items-center gap-x-2.5">
                    <button
                        type="submit"
                        class="primary-button"
                    >
                        @lang('admin::app.leads.send-whatsapp-message.send')
                    </button>
                </div>
            </x-slot:footer>
        </x-admin::modal>
    </form>
</x-admin::form>

@pushOnce('scripts')
    <script type="module">
        function call(params, { setErrors }) {
            this.$axios.post("{{ route('admin.leads.send_whatsapp_message', $entity->id) }}", params)
                .then(response => {
                    this.$refs.whatsappModal.close();

                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                })
                .catch(error => {
                    setErrors(error.response.data.errors);
                });
        }
    </script>
@endPushOnce