<x-admin::layouts>
    <!--Page title -->
    <x-slot:title>
        @lang('admin::app.contacts.persons.create.title')
    </x-slot>

    {!! view_render_event('admin.persons.create.form.before') !!}

    <!--Create Page Form -->
    <x-admin::form
        :action="route('admin.contacts.persons.store')"
        enctype="multipart/form-data"
    >
        <div class="flex flex-col gap-4">
            <!-- Header -->
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    {!! view_render_event('admin.persons.create.breadcrumbs.before') !!}

                    <!-- Breadcrumb -->
                    <x-admin::breadcrumbs name="contacts.persons.create" />

                    {!! view_render_event('admin.persons.create.breadcrumbs.after') !!}

                    <div class="text-xl font-bold dark:text-white">
                        @lang('admin::app.contacts.persons.create.title')
                    </div>
                </div>

                <div class="flex items-center gap-x-2.5">
                    <div class="flex items-center gap-x-2.5">
                        {!! view_render_event('admin.persons.create.create_button.before') !!}

                        <!-- Create button for Person -->
                        <button
                            type="submit"
                            class="primary-button"
                        >
                            @lang('admin::app.contacts.persons.create.save-btn')
                        </button>

                        {!! view_render_event('admin.persons.create.create_button.after') !!}
                    </div>
                </div>
            </div>

            <!-- Form fields -->
            <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                {!! view_render_event('admin.persons.create.form_controls.before') !!}

                <x-admin::attributes
                    :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                        ['code', 'NOTIN', ['organization_id']],
                        'entity_type' => 'persons',
                    ])"
                    :custom-validations="[
                        'name' => [
                            'min:2',
                            'max:100',
                        ],
                        'job_title' => [
                            'max:100',
                        ],
                    ]"
                />

                {{-- Gender --}}
                <x-admin::form.control-group>
                    <x-admin::form.control-group.label>
                        @lang('Giới tính')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="select"
                        name="gender"
                    >
                        <option value="">@lang('admin::app.contacts.persons.common.select-gender')</option>
                        <option value="male">@lang('Nam')</option>
                        <option value="female">@lang('Nữ')</option>
                        <option value="other">@lang('Khác')</option>
                    </x-admin::form.control-group.control>

                    <x-admin::form.control-group.error control-name="gender" />
                </x-admin::form.control-group>

                {{-- Date of Birth --}}
                <x-admin::form.control-group>
                    <x-admin::form.control-group.label>
                        @lang('Ngày sinh')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="date"
                        name="date_of_birth"
                    />

                    <x-admin::form.control-group.error control-name="date_of_birth" />
                </x-admin::form.control-group>

                {{-- Address --}}
                <x-admin::form.control-group>
                    <x-admin::form.control-group.label>
                        @lang('Địa chỉ')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="textarea"
                        name="address"
                    />

                    <x-admin::form.control-group.error control-name="address" />
                </x-admin::form.control-group>

                {{-- Occupation --}}
                <x-admin::form.control-group>
                    <x-admin::form.control-group.label>
                        @lang('Nghề nghiệp')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="text"
                        name="occupation"
                    />

                    <x-admin::form.control-group.error control-name="occupation" />
                </x-admin::form.control-group>

                {{-- Income --}}
                <x-admin::form.control-group>
                    <x-admin::form.control-group.label>
                        @lang('Thu nhập cá nhân')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="text"
                        name="income"
                    />

                    <x-admin::form.control-group.error control-name="income" />
                </x-admin::form.control-group>

                {{-- Hobbies --}}
                <x-admin::form.control-group>
                    <x-admin::form.control-group.label>
                        @lang('Sở thích')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="textarea"
                        name="hobbies"
                    />

                    <x-admin::form.control-group.error control-name="hobbies" />
                </x-admin::form.control-group>

                {{-- Habits and Behaviors --}}
                <x-admin::form.control-group>
                    <x-admin::form.control-group.label>
                        @lang('Thói quen và hành vi')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="textarea"
                        name="habits_and_behaviors"
                    />

                    <x-admin::form.control-group.error control-name="habits_and_behaviors" />
                </x-admin::form.control-group>

                {{-- Needs and Pain Points --}}
                <x-admin::form.control-group>
                    <x-admin::form.control-group.label>
                        @lang('Nhu cầu và vấn đề')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="textarea"
                        name="needs_and_pain_points"
                    />

                    <x-admin::form.control-group.error control-name="needs_and_pain_points" />
                </x-admin::form.control-group>

                <v-organization></v-organization>

                {!! view_render_event('admin.persons.create.form_controls.after') !!}
            </div>
        </div>
    </x-admin::form>

    {!! view_render_event('admin.persons.create.form.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-organization-template"
        >
            <div>
                <x-admin::attributes
                    :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                        ['code', 'IN', ['organization_id']],
                        'entity_type' => 'persons',
                    ])"
                />

                <template v-if="organizationName">
                    <x-admin::form.control-group.control
                        type="hidden"
                        name="organization_name"
                        v-model="organizationName"
                    />
                </template>
            </div>
        </script>

        <script type="module">
            app.component('v-organization', {
                template: '#v-organization-template',

                data() {
                    return {
                        organizationName: null,
                    };
                },

                methods: {
                    handleLookupAdded(event) {
                        this.organizationName = event?.name || null;
                    },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
