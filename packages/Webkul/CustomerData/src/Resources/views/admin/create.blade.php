<x-admin::layouts>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('Thêm mới dữ liệu khách hàng')
    </x-slot>

    {{-- Page Header --}}
    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <div class="flex items-center gap-2.5">
            {{-- Back Button --}}
            <a href="{{ route('admin.customer-data.index') }}"
               class="icon-sort-left grid h-[39px] w-[39px] cursor-pointer place-content-center rounded-md border border-gray-200 bg-white text-2xl transition-all hover:bg-gray-100 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-950">
            </a>

            <div class="grid gap-1.5">
                <p class="text-xl font-bold leading-6 text-gray-800 dark:text-white">
                    @lang('Thêm mới dữ liệu khách hàng')
                </p>
            </div>
        </div>

        <div class="flex items-center gap-x-2.5">
            {{-- Save Button --}}
            <button type="submit" 
                    form="customer-data-form"
                    class="primary-button">
                @lang('Lưu lại')
            </button>
        </div>
    </div>

    {{-- Form --}}
    <x-admin::form 
        :action="route('admin.customer-data.store')"
        enctype="multipart/form-data"
        id="customer-data-form"
    >
        <div class="mt-3.5 box-shadow rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            
            {{-- General Information Section --}}
            <div class="p-4">
                <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                    @lang('Thông tin chung')
                </p>

                {{-- Name & Email --}}
                <div class="mb-4 grid grid-cols-2 gap-4">
                    {{-- Name --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('Họ và tên')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="name"
                            id="name"
                            rules="required"
                            :value="old('name')"
                            :label="trans('Họ và tên')"
                            :placeholder="trans('Nhập họ và tên')"
                        />

                        <x-admin::form.control-group.error control-name="name" />
                    </x-admin::form.control-group>

                    {{-- Email --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('Email')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="email"
                            name="email"
                            id="email"
                            rules="required|email"
                            :value="old('email')"
                            :label="trans('Email')"
                            :placeholder="trans('email@example.com')"
                        />

                        <x-admin::form.control-group.error control-name="email" />
                    </x-admin::form.control-group>
                </div>

                {{-- Phone & Customer Type --}}
                <div class="mb-4 grid grid-cols-2 gap-4">
                    {{-- Phone --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('Số điện thoại')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="phone"
                            id="phone"
                            :value="old('phone')"
                            :placeholder="trans('0901234567')"
                        />

                        <x-admin::form.control-group.error control-name="phone" />
                    </x-admin::form.control-group>

                    {{-- Customer Type --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('Loại khách hàng')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            name="customer_type"
                            id="customer_type"
                            rules="required"
                            :value="old('customer_type')"
                            :label="trans('Loại khách hàng')"
                        >
                            <option value="">@lang('-- Chọn loại --')</option>
                            <option value="retail">@lang('Cá nhân')</option>
                            <option value="business">@lang('Doanh nghiệp')</option>
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error control-name="customer_type" />
                    </x-admin::form.control-group>
                </div>

                {{-- Source --}}
                <div class="mb-4">
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('Nguồn dữ liệu')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            name="source"
                            id="source"
                            :value="old('source')"
                        >
                            <option value="">@lang('-- Chọn nguồn --')</option>
                            @foreach($sources as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error control-name="source" />
                    </x-admin::form.control-group>
                </div>

                {{-- Title / Content --}}
                <div class="mb-4">
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('Nội dung quan tâm')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            name="title"
                            id="title"
                            :value="old('title')"
                            :placeholder="trans('Ví dụ: Tôi muốn tìm hiểu về giải pháp CRM...')"
                            rows="4"
                        />

                        <x-admin::form.control-group.error control-name="title" />
                    </x-admin::form.control-group>
                </div>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>