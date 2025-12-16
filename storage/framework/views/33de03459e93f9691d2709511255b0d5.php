<?php echo view_render_event('admin.dashboard.index.total_leads.before'); ?>


<!-- Total Leads Vue Component -->
<v-dashboard-total-leads>
    <!-- Shimmer -->
    <?php if (isset($component)) { $__componentOriginal062d45ebc040f3196384fffd487ab510 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal062d45ebc040f3196384fffd487ab510 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.shimmer.dashboard.index.total-leads','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::shimmer.dashboard.index.total-leads'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal062d45ebc040f3196384fffd487ab510)): ?>
<?php $attributes = $__attributesOriginal062d45ebc040f3196384fffd487ab510; ?>
<?php unset($__attributesOriginal062d45ebc040f3196384fffd487ab510); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal062d45ebc040f3196384fffd487ab510)): ?>
<?php $component = $__componentOriginal062d45ebc040f3196384fffd487ab510; ?>
<?php unset($__componentOriginal062d45ebc040f3196384fffd487ab510); ?>
<?php endif; ?>
</v-dashboard-total-leads>

<?php echo view_render_event('admin.dashboard.index.total_leads.after'); ?>


<?php if (! $__env->hasRenderedOnce('97f704ff-3c30-4ada-a506-fbf24efcf032')): $__env->markAsRenderedOnce('97f704ff-3c30-4ada-a506-fbf24efcf032');
$__env->startPush('scripts'); ?>
    <script
        type="text/x-template"
        id="v-dashboard-total-leads-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <?php if (isset($component)) { $__componentOriginal062d45ebc040f3196384fffd487ab510 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal062d45ebc040f3196384fffd487ab510 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.shimmer.dashboard.index.total-leads','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::shimmer.dashboard.index.total-leads'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal062d45ebc040f3196384fffd487ab510)): ?>
<?php $attributes = $__attributesOriginal062d45ebc040f3196384fffd487ab510; ?>
<?php unset($__attributesOriginal062d45ebc040f3196384fffd487ab510); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal062d45ebc040f3196384fffd487ab510)): ?>
<?php $component = $__componentOriginal062d45ebc040f3196384fffd487ab510; ?>
<?php unset($__componentOriginal062d45ebc040f3196384fffd487ab510); ?>
<?php endif; ?>
        </template>

        <!-- Total Sales Section -->
        <template v-else>
            <div class="grid gap-4 rounded-lg border border-gray-200 bg-white px-4 py-2 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex flex-col justify-between gap-1">
                    <p class="text-base font-semibold dark:text-gray-300">
                        <?php echo app('translator')->get('admin::app.dashboard.index.total-leads.title'); ?>
                    </p>
                </div>

                <!-- Bar Chart -->
                <div class="flex w-full max-w-full flex-col gap-4">
                    <?php if (isset($component)) { $__componentOriginalf196fc0ab37fc50ba89798b7f8a09a8b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf196fc0ab37fc50ba89798b7f8a09a8b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.charts.bar','data' => [':labels' => 'chartLabels',':datasets' => 'chartDatasets']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::charts.bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([':labels' => 'chartLabels',':datasets' => 'chartDatasets']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf196fc0ab37fc50ba89798b7f8a09a8b)): ?>
<?php $attributes = $__attributesOriginalf196fc0ab37fc50ba89798b7f8a09a8b; ?>
<?php unset($__attributesOriginalf196fc0ab37fc50ba89798b7f8a09a8b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf196fc0ab37fc50ba89798b7f8a09a8b)): ?>
<?php $component = $__componentOriginalf196fc0ab37fc50ba89798b7f8a09a8b; ?>
<?php unset($__componentOriginalf196fc0ab37fc50ba89798b7f8a09a8b); ?>
<?php endif; ?>

                    <div class="flex justify-center gap-5">
                        <div class="flex items-center gap-2">
                            <span class="h-3.5 w-3.5 rounded-sm bg-[#8979FF]"></span>
                            
                            <p class="text-xs dark:text-gray-300">
                                <?php echo app('translator')->get('admin::app.dashboard.index.total-leads.total'); ?>
                            </p>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <span class="h-3.5 w-3.5 rounded-sm bg-[#63CFE5]"></span>
                            
                            <p class="text-xs dark:text-gray-300">
                                <?php echo app('translator')->get('admin::app.dashboard.index.total-leads.won'); ?>
                            </p>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <span class="h-3.5 w-3.5 rounded-sm bg-[#FFA8A1]"></span>
                            
                            <p class="text-xs dark:text-gray-300">
                                <?php echo app('translator')->get('admin::app.dashboard.index.total-leads.lost'); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-total-leads', {
            template: '#v-dashboard-total-leads-template',

            data() {
                return {
                    report: [],

                    isLoading: true,
                }
            },

            computed: {
                chartLabels() {
                    return this.report.statistics.all.over_time.map(({ label }) => label);
                },

                chartDatasets() {
                    return [{
                        data: this.report.statistics.all.over_time.map(({ count }) => count),
                        barThickness: 24,
                        backgroundColor: '#8979FF',
                    },{
                        data: this.report.statistics.won.over_time.map(({ count }) => count),
                        barThickness: 24,
                        backgroundColor: '#63CFE5',
                    },{
                        data: this.report.statistics.lost.over_time.map(({ count }) => count),
                        barThickness: 24,
                        backgroundColor: '#FFA8A1',
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

                    filters.type = 'total-leads';

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
<?php $__env->stopPush(); endif; ?><?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/dashboard/index/total-leads.blade.php ENDPATH**/ ?>