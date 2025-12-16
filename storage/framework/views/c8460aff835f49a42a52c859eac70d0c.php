<?php echo view_render_event('admin.dashboard.index.top_selling_proudcts.before'); ?>


<!-- Top Selling Products Vue Component -->
<v-dashboard-top-selling-products>
    <!-- Shimmer -->
    <?php if (isset($component)) { $__componentOriginal4f7a5179573c78c10772c54c95b1fe82 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4f7a5179573c78c10772c54c95b1fe82 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.shimmer.dashboard.index.top-selling-products','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::shimmer.dashboard.index.top-selling-products'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4f7a5179573c78c10772c54c95b1fe82)): ?>
<?php $attributes = $__attributesOriginal4f7a5179573c78c10772c54c95b1fe82; ?>
<?php unset($__attributesOriginal4f7a5179573c78c10772c54c95b1fe82); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4f7a5179573c78c10772c54c95b1fe82)): ?>
<?php $component = $__componentOriginal4f7a5179573c78c10772c54c95b1fe82; ?>
<?php unset($__componentOriginal4f7a5179573c78c10772c54c95b1fe82); ?>
<?php endif; ?>
</v-dashboard-top-selling-products>

<?php echo view_render_event('admin.dashboard.index.top_selling_proudcts.after'); ?>


<?php if (! $__env->hasRenderedOnce('2dabda2b-070b-4c38-9044-d5eedcb66354')): $__env->markAsRenderedOnce('2dabda2b-070b-4c38-9044-d5eedcb66354');
$__env->startPush('scripts'); ?>
    <script
        type="text/x-template"
        id="v-dashboard-top-selling-products-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <?php if (isset($component)) { $__componentOriginal4f7a5179573c78c10772c54c95b1fe82 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4f7a5179573c78c10772c54c95b1fe82 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.shimmer.dashboard.index.top-selling-products','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::shimmer.dashboard.index.top-selling-products'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4f7a5179573c78c10772c54c95b1fe82)): ?>
<?php $attributes = $__attributesOriginal4f7a5179573c78c10772c54c95b1fe82; ?>
<?php unset($__attributesOriginal4f7a5179573c78c10772c54c95b1fe82); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4f7a5179573c78c10772c54c95b1fe82)): ?>
<?php $component = $__componentOriginal4f7a5179573c78c10772c54c95b1fe82; ?>
<?php unset($__componentOriginal4f7a5179573c78c10772c54c95b1fe82); ?>
<?php endif; ?>
        </template>

        <!-- Total Sales Section -->
        <template v-else>
            <div class="w-full rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-center justify-between p-4">
                    <p class="text-base font-semibold text-gray-600 dark:text-gray-300">
                        <?php echo app('translator')->get('admin::app.dashboard.index.top-selling-products.title'); ?>
                    </p>
                </div>

                <!-- Top Selling Products Details -->
                <div
                    class="flex flex-col"
                    v-if="report.statistics.length"
                >
                    <a
                        :href="`<?php echo e(route('admin.products.view', '')); ?>/${item.id}`"
                        class="flex gap-2.5 border-b p-4 transition-all last:border-b-0 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-950"
                        target="_blank"
                        v-for="item in report.statistics"
                    >
                        <!-- Product Details -->
                        <div class="flex w-full flex-col gap-1.5">
                            <p
                                class="text-gray-600 dark:text-gray-300"
                                v-text="item.name"
                            >
                            </p>

                            <div class="flex justify-between">
                                <p class="font-medium text-gray-800 dark:text-white">
                                    {{ item.formatted_price }}
                                </p>

                                <p class="font-normal text-gray-800 dark:text-white">
                                    {{ item.formatted_revenue }}
                                </p>
                            </div>
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
                            src="<?php echo e(vite()->asset('images/empty-placeholders/products.svg')); ?>"
                            class="dark:mix-blend-exclusion dark:invert"
                        >

                        <!-- Add Variants Information -->
                        <div class="flex flex-col items-center">
                            <p class="text-base font-semibold text-gray-400">
                                <?php echo app('translator')->get('admin::app.dashboard.index.top-selling-products.empty-title'); ?>
                            </p>

                            <p class="text-gray-400">
                                <?php echo app('translator')->get('admin::app.dashboard.index.top-selling-products.empty-info'); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-top-selling-products', {
            template: '#v-dashboard-top-selling-products-template',

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

                    filters.type = 'top-selling-products';

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
<?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/dashboard/index/top-selling-products.blade.php ENDPATH**/ ?>