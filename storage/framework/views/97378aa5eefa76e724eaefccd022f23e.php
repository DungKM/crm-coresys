<?php echo view_render_event('admin.dashboard.index.top_persons.before'); ?>


<!-- Top Selling Products Vue Component -->
<v-dashboard-top-persons>
    <!-- Shimmer -->
    <?php if (isset($component)) { $__componentOriginale3cb958e757058acb718425d99e7f9ce = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3cb958e757058acb718425d99e7f9ce = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.shimmer.dashboard.index.top-persons','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::shimmer.dashboard.index.top-persons'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3cb958e757058acb718425d99e7f9ce)): ?>
<?php $attributes = $__attributesOriginale3cb958e757058acb718425d99e7f9ce; ?>
<?php unset($__attributesOriginale3cb958e757058acb718425d99e7f9ce); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3cb958e757058acb718425d99e7f9ce)): ?>
<?php $component = $__componentOriginale3cb958e757058acb718425d99e7f9ce; ?>
<?php unset($__componentOriginale3cb958e757058acb718425d99e7f9ce); ?>
<?php endif; ?>
</v-dashboard-top-persons>

<?php echo view_render_event('admin.dashboard.index.top_persons.after'); ?>


<?php if (! $__env->hasRenderedOnce('6c1181b6-7316-4b9e-bc86-99c702ba2dad')): $__env->markAsRenderedOnce('6c1181b6-7316-4b9e-bc86-99c702ba2dad');
$__env->startPush('scripts'); ?>
    <script
        type="text/x-template"
        id="v-dashboard-top-persons-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <?php if (isset($component)) { $__componentOriginale3cb958e757058acb718425d99e7f9ce = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3cb958e757058acb718425d99e7f9ce = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.shimmer.dashboard.index.top-persons','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::shimmer.dashboard.index.top-persons'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3cb958e757058acb718425d99e7f9ce)): ?>
<?php $attributes = $__attributesOriginale3cb958e757058acb718425d99e7f9ce; ?>
<?php unset($__attributesOriginale3cb958e757058acb718425d99e7f9ce); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3cb958e757058acb718425d99e7f9ce)): ?>
<?php $component = $__componentOriginale3cb958e757058acb718425d99e7f9ce; ?>
<?php unset($__componentOriginale3cb958e757058acb718425d99e7f9ce); ?>
<?php endif; ?>
        </template>

        <!-- Total Sales Section -->
        <template v-else>
            <div class="w-full rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-center justify-between p-4">
                    <p class="text-base font-semibold text-gray-600 dark:text-gray-300">
                        <?php echo app('translator')->get('admin::app.dashboard.index.top-persons.title'); ?>
                    </p>
                </div>

                <!-- Top Selling Products Details -->
                <div
                    class="flex flex-col"
                    v-if="report.statistics.length"
                >
                    <a
                        :href="`<?php echo e(route('admin.contacts.persons.view', '')); ?>/${item.id}`"
                        class="flex gap-2.5 border-b p-4 transition-all last:border-b-0 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-950"
                        target="_blank"
                        v-for="item in report.statistics"
                    >
                        <!-- Person Initials -->
                        <?php if (isset($component)) { $__componentOriginal2d42bddad77c068ade50efea9ce906c7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2d42bddad77c068ade50efea9ce906c7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.avatar.index','data' => [':name' => 'item.name']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([':name' => 'item.name']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2d42bddad77c068ade50efea9ce906c7)): ?>
<?php $attributes = $__attributesOriginal2d42bddad77c068ade50efea9ce906c7; ?>
<?php unset($__attributesOriginal2d42bddad77c068ade50efea9ce906c7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2d42bddad77c068ade50efea9ce906c7)): ?>
<?php $component = $__componentOriginal2d42bddad77c068ade50efea9ce906c7; ?>
<?php unset($__componentOriginal2d42bddad77c068ade50efea9ce906c7); ?>
<?php endif; ?>

                        <!-- Person Details -->
                        <div class="flex flex-col gap-1">
                            <p class="font-medium text-gray-800 dark:text-white">{{ item.name }}</p>

                            <p class="font-normal text-gray-800 dark:text-white">{{ item.emails.map(item => item.value).join(', ') }}</p>
                        </div>
                    </a>
                </div>

                <!-- Empty Product Design -->
                <div
                    class="flex flex-col gap-8 p-4"
                    v-else
                >
                    <div class="grid justify-center justify-items-center gap-3.5 py-2.5">
                        <!-- Placeholder Image -->
                        <img
                            src="<?php echo e(vite()->asset('images/empty-placeholders/users.svg')); ?>"
                            class="dark:mix-blend-exclusion dark:invert"
                        >

                        <!-- Add Variants Information -->
                        <div class="flex flex-col items-center">
                            <p class="text-base font-semibold text-gray-400">
                                <?php echo app('translator')->get('admin::app.dashboard.index.top-persons.empty-title'); ?>
                            </p>

                            <p class="text-gray-400">
                                <?php echo app('translator')->get('admin::app.dashboard.index.top-persons.empty-info'); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-top-persons', {
            template: '#v-dashboard-top-persons-template',

            data() {
                return {
                    report: [],

                    isLoading: true,
                }
            },

            mounted() {
                this.getStats({});

                this.$emitter.on('reporting-filter-updated', this.getStats);
            },

            methods: {
                getStats(filters) {
                    this.isLoading = true;

                    var filters = Object.assign({}, filters);

                    filters.type = 'top-persons';

                    this.$axios.get("<?php echo e(route('admin.dashboard.stats')); ?>", {
                            params: filters
                        })
                        .then(response => {
                            this.report = response.data;

                            this.isLoading = false;
                        })
                        .catch(error => {});
                }
            }
        });
    </script>
<?php $__env->stopPush(); endif; ?>
<?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/dashboard/index/top-persons.blade.php ENDPATH**/ ?>