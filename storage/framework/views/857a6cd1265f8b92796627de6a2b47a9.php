<?php echo view_render_event('admin.dashboard.index.revenue_by_sources.before'); ?>


<!-- Total Leads Vue Component -->
<v-dashboard-revenue-by-sources>
    <!-- Shimmer -->
    <?php if (isset($component)) { $__componentOriginalf1c9bec180df02d0e97ef19f89780688 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf1c9bec180df02d0e97ef19f89780688 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.shimmer.dashboard.index.revenue-by-sources','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::shimmer.dashboard.index.revenue-by-sources'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf1c9bec180df02d0e97ef19f89780688)): ?>
<?php $attributes = $__attributesOriginalf1c9bec180df02d0e97ef19f89780688; ?>
<?php unset($__attributesOriginalf1c9bec180df02d0e97ef19f89780688); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf1c9bec180df02d0e97ef19f89780688)): ?>
<?php $component = $__componentOriginalf1c9bec180df02d0e97ef19f89780688; ?>
<?php unset($__componentOriginalf1c9bec180df02d0e97ef19f89780688); ?>
<?php endif; ?>
</v-dashboard-revenue-by-sources>

<?php echo view_render_event('admin.dashboard.index.revenue_by_sources.after'); ?>


<?php if (! $__env->hasRenderedOnce('b4afdeac-8d5a-4f78-a910-8758a4ba3bd2')): $__env->markAsRenderedOnce('b4afdeac-8d5a-4f78-a910-8758a4ba3bd2');
$__env->startPush('scripts'); ?>
    <script
        type="text/x-template"
        id="v-dashboard-revenue-by-sources-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <?php if (isset($component)) { $__componentOriginalf1c9bec180df02d0e97ef19f89780688 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf1c9bec180df02d0e97ef19f89780688 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.shimmer.dashboard.index.revenue-by-sources','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::shimmer.dashboard.index.revenue-by-sources'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf1c9bec180df02d0e97ef19f89780688)): ?>
<?php $attributes = $__attributesOriginalf1c9bec180df02d0e97ef19f89780688; ?>
<?php unset($__attributesOriginalf1c9bec180df02d0e97ef19f89780688); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf1c9bec180df02d0e97ef19f89780688)): ?>
<?php $component = $__componentOriginalf1c9bec180df02d0e97ef19f89780688; ?>
<?php unset($__componentOriginalf1c9bec180df02d0e97ef19f89780688); ?>
<?php endif; ?>
        </template>

        <!-- Total Sales Section -->
        <template v-else>
            <div class="grid gap-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex flex-col justify-between gap-1">
                    <p class="text-base font-semibold dark:text-gray-300">
                        <?php echo app('translator')->get('admin::app.dashboard.index.revenue-by-sources.title'); ?>
                    </p>
                </div>

                <!-- Doughnut Chart -->
                <div
                    class="flex w-full max-w-full flex-col gap-4 px-8 pt-8"
                    v-if="report.statistics.length"
                >
                    <?php if (isset($component)) { $__componentOriginal3ab4661c9a7b9c9428d04459c8f925c5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3ab4661c9a7b9c9428d04459c8f925c5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.charts.doughnut','data' => [':labels' => 'chartLabels',':datasets' => 'chartDatasets']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::charts.doughnut'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([':labels' => 'chartLabels',':datasets' => 'chartDatasets']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3ab4661c9a7b9c9428d04459c8f925c5)): ?>
<?php $attributes = $__attributesOriginal3ab4661c9a7b9c9428d04459c8f925c5; ?>
<?php unset($__attributesOriginal3ab4661c9a7b9c9428d04459c8f925c5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3ab4661c9a7b9c9428d04459c8f925c5)): ?>
<?php $component = $__componentOriginal3ab4661c9a7b9c9428d04459c8f925c5; ?>
<?php unset($__componentOriginal3ab4661c9a7b9c9428d04459c8f925c5); ?>
<?php endif; ?>

                    <div class="flex flex-wrap justify-center gap-5">
                        <div
                            class="flex items-center gap-2 whitespace-nowrap"
                            v-for="(stat, index) in report.statistics"
                        >
                            <span
                                class="h-3.5 w-3.5 rounded-sm"
                                :style="{ backgroundColor: colors[index] }"
                            ></span>

                            <p class="text-xs dark:text-gray-300">
                                {{ stat.name }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Empty Product Design -->
                <div
                    class="flex flex-col gap-8 p-4"
                    v-else
                >
                    <div class="grid justify-center justify-items-center gap-3.5 py-2.5">
                        <!-- Placeholder Image -->
                        <img
                            src="<?php echo e(vite()->asset('images/empty-placeholders/default.svg')); ?>"
                            class="dark:mix-blend-exclusion dark:invert"
                        >

                        <!-- Add Variants Information -->
                        <div class="flex flex-col items-center">
                            <p class="text-base font-semibold text-gray-400">
                                <?php echo app('translator')->get('admin::app.dashboard.index.revenue-by-sources.empty-title'); ?>
                            </p>

                            <p class="text-gray-400">
                                <?php echo app('translator')->get('admin::app.dashboard.index.revenue-by-sources.empty-info'); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-revenue-by-sources', {
            template: '#v-dashboard-revenue-by-sources-template',

            data() {
                return {
                    report: [],

                    colors: [
                        '#8979FF',
                        '#FF928A',
                        '#3CC3DF',
                    ],

                    isLoading: true,
                }
            },

            computed: {
                chartLabels() {
                    return this.report.statistics.map(({ name }) => name);
                },

                chartDatasets() {
                    return [{
                        data: this.report.statistics.map(({ total }) => total),
                        barThickness: 24,
                        backgroundColor: this.colors,
                    }];
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

                    filters.type = 'revenue-by-sources';

                    this.$axios.get("<?php echo e(route('admin.dashboard.stats')); ?>", {
                            params: filters
                        })
                        .then(response => {
                            this.report = response.data;

                            this.extendColors(this.report.statistics.length);

                            this.isLoading = false;
                        })
                        .catch(error => {});
                },
                
                extendColors(length) {
                    while (this.colors.length < length) {
                        const hue = Math.floor(Math.random() * 360);
                        const newColor = `hsl(${hue}, 70%, 60%)`;
                        this.colors.push(newColor);
                    }
                },
            }
        });
    </script>
<?php $__env->stopPush(); endif; ?>
<?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/dashboard/index/revenue-by-sources.blade.php ENDPATH**/ ?>