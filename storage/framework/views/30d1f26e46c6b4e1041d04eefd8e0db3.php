<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'attachEndpoint',
    'detachEndpoint',
    'addedTags' => [],
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'attachEndpoint',
    'detachEndpoint',
    'addedTags' => [],
]); ?>
<?php foreach (array_filter(([
    'attachEndpoint',
    'detachEndpoint',
    'addedTags' => [],
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<v-tags
    attach-endpoint="<?php echo e($attachEndpoint); ?>"
    detach-endpoint="<?php echo e($detachEndpoint); ?>"
    :added-tags='<?php echo json_encode($addedTags, 15, 512) ?>'
>
    <?php if (isset($component)) { $__componentOriginal26369d44ed0268264f891db1829d490c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal26369d44ed0268264f891db1829d490c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.shimmer.tags.index','data' => ['count' => '3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::shimmer.tags'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['count' => '3']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal26369d44ed0268264f891db1829d490c)): ?>
<?php $attributes = $__attributesOriginal26369d44ed0268264f891db1829d490c; ?>
<?php unset($__attributesOriginal26369d44ed0268264f891db1829d490c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal26369d44ed0268264f891db1829d490c)): ?>
<?php $component = $__componentOriginal26369d44ed0268264f891db1829d490c; ?>
<?php unset($__componentOriginal26369d44ed0268264f891db1829d490c); ?>
<?php endif; ?>
</v-tags>

<?php if (! $__env->hasRenderedOnce('bac92508-00f0-4b34-8afd-74fee31b73dd')): $__env->markAsRenderedOnce('bac92508-00f0-4b34-8afd-74fee31b73dd');
$__env->startPush('scripts'); ?>
    <script type="text/x-template" id="v-tags-template">
        <div class="flex flex-wrap items-center gap-1">
            <!-- Tags -->
            <span
                class="flex items-center gap-1 break-all rounded-md bg-rose-100 px-3 py-1.5 text-xs font-medium"
                :style="{
                    'background-color': tag.color,
                    'color': backgroundColors.find(color => color.background === tag.color)?.text
                }"
                v-for="(tag, index) in tags"
                v-safe-html="tag.name"
            >
            </span>

            <!-- Add Button -->
            <?php if (isset($component)) { $__componentOriginalaf937e0ec72fa678d3a0c6dc6c0ac5f2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalaf937e0ec72fa678d3a0c6dc6c0ac5f2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.dropdown.index','data' => [':closeOnClick' => 'false','position' => 'bottom-'.e(in_array(app()->getLocale(), ['fa', 'ar']) ? 'right' : 'left').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([':close-on-click' => 'false','position' => 'bottom-'.e(in_array(app()->getLocale(), ['fa', 'ar']) ? 'right' : 'left').'']); ?>
                 <?php $__env->slot('toggle', null, []); ?> 
                    <button class="icon-settings-tag rounded-md p-1 text-xl transition-all hover:bg-gray-200 dark:hover:bg-gray-950"></button>
                 <?php $__env->endSlot(); ?>

                 <?php $__env->slot('content', null, ['class' => '!p-0']); ?> 
                    <!-- Dropdown Container !-->
                    <div class="flex flex-col gap-2">
                        <!-- Search Input -->
                        <div class="flex flex-col gap-1 px-4 py-2">
                            <label class="font-semibold text-gray-600 dark:text-gray-300">
                                <?php echo app('translator')->get('admin::app.components.tags.index.title'); ?>
                            </label>

                            <!-- Search Button -->
                            <div class="relative">
                                <div class="relative rounded border border-gray-200 p-2 hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:hover:border-gray-400 dark:focus:border-gray-400" role="button">
                                    <input
                                        type="text"
                                        class="w-full cursor-pointer pr-6 dark:bg-gray-900 dark:text-gray-300"
                                        placeholder="<?php echo app('translator')->get('admin::app.components.tags.index.placeholder'); ?>"
                                        v-model.lazy="searchTerm"
                                        v-debounce="500"
                                    />

                                    <template v-if="! isSearching">
                                        <span
                                            class="absolute right-1.5 top-1.5 text-2xl"
                                            :class="[searchTerm.length >= 2 ? 'icon-up-arrow' : 'icon-down-arrow']"
                                        ></span>
                                    </template>

                                    <template v-else>
                                        <?php if (isset($component)) { $__componentOriginal991e5e3816aa635af8067aa2abbd328b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal991e5e3816aa635af8067aa2abbd328b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.spinner.index','data' => ['class' => 'absolute right-2 top-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::spinner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'absolute right-2 top-2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal991e5e3816aa635af8067aa2abbd328b)): ?>
<?php $attributes = $__attributesOriginal991e5e3816aa635af8067aa2abbd328b; ?>
<?php unset($__attributesOriginal991e5e3816aa635af8067aa2abbd328b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal991e5e3816aa635af8067aa2abbd328b)): ?>
<?php $component = $__componentOriginal991e5e3816aa635af8067aa2abbd328b; ?>
<?php unset($__componentOriginal991e5e3816aa635af8067aa2abbd328b); ?>
<?php endif; ?>
                                    </template>
                                </div>

                                <!-- Search Tags Dropdown -->
                                <div
                                    class="absolute z-10 w-full rounded bg-white shadow-[0px_10px_20px_0px_#0000001F] dark:bg-gray-800"
                                    v-if="searchTerm.length >= 2"
                                >
                                    <ul class="p-2">
                                        <li
                                            class="cursor-pointer break-all rounded-sm px-5 py-2 text-sm text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-950"
                                            v-for="tag in searchedTags"
                                            @click="attachToEntity(tag)"
                                        >
                                            {{ tag.name }}
                                        </li>

                                        <?php if(bouncer()->hasPermission('settings.other_settings.tags.create')): ?>
                                            <template v-if="! searchedTags.length && ! isSearching">
                                                <li
                                                    class="cursor-pointer rounded-sm bg-gray-100 px-5 py-2 text-sm text-gray-800 dark:bg-gray-950 dark:text-white"
                                                    @click="create"
                                                >
                                                    {{ `<?php echo app('translator')->get('admin::app.components.tags.index.add-tag', ['term' => 'replaceTerm']); ?>`.replace('replaceTerm', searchTerm) }}
                                                </li>
                                            </template>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Tags -->
                        <div
                            class="flex flex-col gap-2 px-4 py-1.5"
                            v-if="tags.length"
                        >
                            <label class="text-gray-600 dark:text-gray-300">
                                <?php echo app('translator')->get('admin::app.components.tags.index.added-tags'); ?>
                            </label>

                            <!-- Added Tags List -->
                            <ul class="flex flex-col">
                                <template v-for="tag in tags">
                                    <li
                                        class="flex items-center justify-between gap-2.5 rounded-sm p-2 text-sm text-gray-800 dark:text-white"
                                        v-if="tag.id"
                                    >
                                        <!-- Name -->
                                        <span
                                            class="break-all rounded-md bg-rose-100 px-3 py-1.5 text-xs font-medium"
                                            :style="{
                                                'background-color': tag.color,
                                                'color': backgroundColors.find(color => color.background === tag.color)?.text
                                            }"
                                        >
                                            {{ tag.name }}
                                        </span>

                                        <!-- Action -->
                                        <div class="flex items-center gap-1">
                                            <?php if(bouncer()->hasPermission('settings.other_settings.tags.edit')): ?>
                                                <?php if (isset($component)) { $__componentOriginalaf937e0ec72fa678d3a0c6dc6c0ac5f2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalaf937e0ec72fa678d3a0c6dc6c0ac5f2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.dropdown.index','data' => ['position' => 'bottom-right']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['position' => 'bottom-right']); ?>
                                                     <?php $__env->slot('toggle', null, []); ?> 
                                                        <button class="flex cursor-pointer items-center gap-1 rounded border border-gray-200 px-2 py-0.5 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:hover:border-gray-400 dark:focus:border-gray-400">
                                                            <span
                                                                class="h-4 w-4 break-all rounded-full"
                                                                :style="'background-color: ' + (tag.color ? tag.color : '#546E7A')"
                                                            >
                                                            </span>

                                                            <span class="icon-down-arrow text-xl"></span>
                                                        </button>
                                                     <?php $__env->endSlot(); ?>

                                                     <?php $__env->slot('menu', null, ['class' => '!top-7 !p-0']); ?> 
                                                        <?php if (isset($component)) { $__componentOriginal0223c8534d6a243be608c3a65289c4d0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0223c8534d6a243be608c3a65289c4d0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.dropdown.menu.item','data' => ['class' => 'top-5 flex gap-2',':class' => '{ \'bg-gray-100 dark:bg-gray-950\': tag.color === color.background }','vFor' => 'color in backgroundColors','@click' => 'update(tag, color)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::dropdown.menu.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'top-5 flex gap-2',':class' => '{ \'bg-gray-100 dark:bg-gray-950\': tag.color === color.background }','v-for' => 'color in backgroundColors','@click' => 'update(tag, color)']); ?>
                                                            <span
                                                                class="flex h-4 w-4 break-all rounded-full"
                                                                :style="'background-color: ' + color.background"
                                                            >
                                                            </span>

                                                            {{ color.label }}
                                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0223c8534d6a243be608c3a65289c4d0)): ?>
<?php $attributes = $__attributesOriginal0223c8534d6a243be608c3a65289c4d0; ?>
<?php unset($__attributesOriginal0223c8534d6a243be608c3a65289c4d0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0223c8534d6a243be608c3a65289c4d0)): ?>
<?php $component = $__componentOriginal0223c8534d6a243be608c3a65289c4d0; ?>
<?php unset($__componentOriginal0223c8534d6a243be608c3a65289c4d0); ?>
<?php endif; ?>
                                                     <?php $__env->endSlot(); ?>
                                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalaf937e0ec72fa678d3a0c6dc6c0ac5f2)): ?>
<?php $attributes = $__attributesOriginalaf937e0ec72fa678d3a0c6dc6c0ac5f2; ?>
<?php unset($__attributesOriginalaf937e0ec72fa678d3a0c6dc6c0ac5f2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalaf937e0ec72fa678d3a0c6dc6c0ac5f2)): ?>
<?php $component = $__componentOriginalaf937e0ec72fa678d3a0c6dc6c0ac5f2; ?>
<?php unset($__componentOriginalaf937e0ec72fa678d3a0c6dc6c0ac5f2); ?>
<?php endif; ?>
                                            <?php endif; ?>

                                            <?php if(bouncer()->hasPermission('settings.other_settings.tags.delete')): ?>
                                                <div class="flex items-center">
                                                    <span
                                                        class="icon-cross-large flex cursor-pointer rounded-md p-1 text-xl text-gray-600 transition-all hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-800"
                                                        v-show="! isRemoving[tag.id]"
                                                        @click="detachFromEntity(tag)"
                                                    ></span>

                                                    <span
                                                        class="p-1"
                                                        v-show="isRemoving[tag.id]"
                                                    >
                                                        <?php if (isset($component)) { $__componentOriginal991e5e3816aa635af8067aa2abbd328b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal991e5e3816aa635af8067aa2abbd328b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.spinner.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::spinner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal991e5e3816aa635af8067aa2abbd328b)): ?>
<?php $attributes = $__attributesOriginal991e5e3816aa635af8067aa2abbd328b; ?>
<?php unset($__attributesOriginal991e5e3816aa635af8067aa2abbd328b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal991e5e3816aa635af8067aa2abbd328b)): ?>
<?php $component = $__componentOriginal991e5e3816aa635af8067aa2abbd328b; ?>
<?php unset($__componentOriginal991e5e3816aa635af8067aa2abbd328b); ?>
<?php endif; ?>
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                 <?php $__env->endSlot(); ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalaf937e0ec72fa678d3a0c6dc6c0ac5f2)): ?>
<?php $attributes = $__attributesOriginalaf937e0ec72fa678d3a0c6dc6c0ac5f2; ?>
<?php unset($__attributesOriginalaf937e0ec72fa678d3a0c6dc6c0ac5f2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalaf937e0ec72fa678d3a0c6dc6c0ac5f2)): ?>
<?php $component = $__componentOriginalaf937e0ec72fa678d3a0c6dc6c0ac5f2; ?>
<?php unset($__componentOriginalaf937e0ec72fa678d3a0c6dc6c0ac5f2); ?>
<?php endif; ?>
        </div>
    </script>

    <script type="module">
        app.component('v-tags', {
            template: '#v-tags-template',

            props: {
                attachEndpoint: {
                    type: String,
                    default: '',
                },

                detachEndpoint: {
                    type: String,
                    default: '',
                },

                addedTags: {
                    type: Array,
                    default: () => [],
                },
            },

            data: function () {
                return {
                    searchTerm: '',

                    isStoring: false,

                    isSearching: false,

                    isRemoving: {},

                    tags: [],

                    searchedTags: [],

                    backgroundColors: [
                        {
                            label: "<?php echo app('translator')->get('admin::app.components.tags.index.aquarelle-red'); ?>",
                            text: '#DC2626',
                            background: '#FEE2E2',
                        }, {
                            label: "<?php echo app('translator')->get('admin::app.components.tags.index.crushed-cashew'); ?>",
                            text: '#EA580C',
                            background: '#FFEDD5',
                        }, {
                            label: "<?php echo app('translator')->get('admin::app.components.tags.index.beeswax'); ?>",
                            text: '#D97706',
                            background: '#FEF3C7',
                        }, {
                            label: "<?php echo app('translator')->get('admin::app.components.tags.index.lemon-chiffon'); ?>",
                            text: '#CA8A04',
                            background: '#FEF9C3',
                        }, {
                            label: "<?php echo app('translator')->get('admin::app.components.tags.index.snow-flurry'); ?>",
                            text: '#65A30D',
                            background: '#ECFCCB',
                        }, {
                            label: "<?php echo app('translator')->get('admin::app.components.tags.index.honeydew'); ?>",
                            text: '#16A34A',
                            background: '#DCFCE7',
                        },
                    ],
                }
            },

            watch: {
                searchTerm(newVal, oldVal) {
                    this.search();
                },
            },

            mounted() {
                this.tags = this.addedTags;
            },

            methods: {
                openModal(type) {
                    this.$refs.mailActivityModal.open();
                },

                search() {
                    if (this.searchTerm.length <= 1) {
                        this.searchedTags = [];

                        this.isSearching = false;

                        return;
                    }

                    this.isSearching = true;

                    let self = this;

                    this.$axios.get("<?php echo e(route('admin.settings.tags.search')); ?>", {
                            params: {
                                search: 'name:' + this.searchTerm,
                                searchFields: 'name:like',
                            }
                        })
                        .then (function(response) {
                            self.tags.forEach(function(addedTag) {
                                response.data.data = response.data.data.filter(function(tag) {
                                    return tag.id !== addedTag.id;
                                });
                            });

                            self.searchedTags = response.data.data;

                            self.isSearching = false;
                        })
                        .catch (function (error) {
                            self.isSearching = false;
                        });
                },

                create() {
                    this.isStoring = true;

                    var self = this;

                    this.$axios.post("<?php echo e(route('admin.settings.tags.store')); ?>", {
                        name: this.searchTerm,
                        color: this.backgroundColors[Math.floor(Math.random() * this.backgroundColors.length)].background,
                    })
                        .then(response => {
                            self.attachToEntity(response.data.data);
                        })
                        .catch(error => {
                            self.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });

                            self.isStoring = false;
                        });
                },

                update(tag, color) {
                    var self = this;

                    this.$axios.put("<?php echo e(route('admin.settings.tags.update', 'replaceTagId')); ?>".replace('replaceTagId', tag.id), {
                        name: tag.name,
                        color: color.background,
                    })
                        .then(response => {
                            tag.color = color.background;

                            self.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                        })
                        .catch(error => {
                            self.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                        });
                },

                attachToEntity(params) {
                    this.isStoring = true;

                    var self = this;

                    this.$axios.post(this.attachEndpoint, {
                        tag_id: params.id,
                    })
                        .then(response => {
                            self.searchedTags = [];

                            self.searchTerm = '';

                            self.isStoring = false;

                            self.tags.push(params);

                            self.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                        })
                        .catch(error => {
                            self.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });

                            self.isStoring = false;
                        });
                },

                detachFromEntity(tag) {
                    var self = this;

                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            this.isRemoving[tag.id] = true;

                            this.$axios.delete(this.detachEndpoint, {
                                    data: {
                                        tag_id: tag.id,
                                    }
                                })
                                .then(response => {
                                    self.isRemoving[tag.id] = false;

                                    const index = self.tags.indexOf(tag);

                                    self.tags.splice(index, 1);

                                    self.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                })
                                .catch(error => {
                                    self.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });

                                    self.isRemoving[tag.id] = false;
                                });
                        },
                    });
                },
            },
        });
    </script>
<?php $__env->stopPush(); endif; ?><?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/components/tags/index.blade.php ENDPATH**/ ?>