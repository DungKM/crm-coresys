<?php echo view_render_event('admin.dashboard.index.over_all.before'); ?>


<!-- Over Details Vue Component -->
<v-dashboard-over-all-stats>
    <!-- Shimmer -->
    <?php if (isset($component)) { $__componentOriginal749a64c217134518f8b1b509819c7e9f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal749a64c217134518f8b1b509819c7e9f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.shimmer.dashboard.index.over-all','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::shimmer.dashboard.index.over-all'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal749a64c217134518f8b1b509819c7e9f)): ?>
<?php $attributes = $__attributesOriginal749a64c217134518f8b1b509819c7e9f; ?>
<?php unset($__attributesOriginal749a64c217134518f8b1b509819c7e9f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal749a64c217134518f8b1b509819c7e9f)): ?>
<?php $component = $__componentOriginal749a64c217134518f8b1b509819c7e9f; ?>
<?php unset($__componentOriginal749a64c217134518f8b1b509819c7e9f); ?>
<?php endif; ?>
</v-dashboard-over-all-stats>

<?php echo view_render_event('admin.dashboard.index.over_all.after'); ?>


<?php if (! $__env->hasRenderedOnce('91c8b07e-f0b1-41b7-8ea3-c4e6bd96dc2b')): $__env->markAsRenderedOnce('91c8b07e-f0b1-41b7-8ea3-c4e6bd96dc2b');
$__env->startPush('scripts'); ?>
    <script
        type="text/x-template"
        id="v-dashboard-over-all-stats-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <?php if (isset($component)) { $__componentOriginal749a64c217134518f8b1b509819c7e9f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal749a64c217134518f8b1b509819c7e9f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.shimmer.dashboard.index.over-all','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::shimmer.dashboard.index.over-all'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal749a64c217134518f8b1b509819c7e9f)): ?>
<?php $attributes = $__attributesOriginal749a64c217134518f8b1b509819c7e9f; ?>
<?php unset($__attributesOriginal749a64c217134518f8b1b509819c7e9f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal749a64c217134518f8b1b509819c7e9f)): ?>
<?php $component = $__componentOriginal749a64c217134518f8b1b509819c7e9f; ?>
<?php unset($__componentOriginal749a64c217134518f8b1b509819c7e9f); ?>
<?php endif; ?>
        </template>

        <!-- Total Sales Section -->
        <template v-else>
            <!-- Stats Cards -->
            <div class="grid grid-cols-3 gap-4 max-md:grid-cols-2 max-sm:grid-cols-1">
                <!-- Average Revenue Card -->
                <div class="flex flex-col gap-2 rounded-lg border border-gray-200 bg-white px-4 py-5 dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-300">
                        <?php echo app('translator')->get('admin::app.dashboard.index.over-all.average-lead-value'); ?>
                    </p>

                    <div class="flex gap-2">
                        <p class="text-xl font-bold dark:text-gray-300">
                            {{ report.statistics.average_lead_value.formatted_total }}
                        </p>

                        <div class="flex items-center gap-0.5">
                            <span
                                class="text-base !font-semibold text-green-500"
                                :class="[report.statistics.average_lead_value.progress < 0 ? 'icon-stats-down text-red-500 dark:!text-red-500' : 'icon-stats-up text-green-500 dark:!text-green-500']"
                            ></span>

                            <p
                                class="text-xs font-semibold text-green-500"
                                :class="[report.statistics.average_lead_value.progress < 0 ?  'text-red-500' : 'text-green-500']"
                            >
                                {{ Math.abs(report.statistics.average_lead_value.progress.toFixed(2)) }}%
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Average Revenue Card -->
                <div class="flex flex-col gap-2 rounded-lg border border-gray-200 bg-white px-4 py-5 dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-300">
                        <?php echo app('translator')->get('admin::app.dashboard.index.over-all.total-leads'); ?>
                    </p>

                    <div class="flex gap-2">
                        <p class="text-xl font-bold dark:text-gray-300">
                            {{ report.statistics.total_leads.current }}
                        </p>

                        <div class="flex items-center gap-0.5">
                            <span
                                class="text-base !font-semibold text-green-500"
                                :class="[report.statistics.total_leads.progress < 0 ? 'icon-stats-down text-red-500 dark:!text-red-500' : 'icon-stats-up text-green-500 dark:!text-green-500']"
                            ></span>

                            <p
                                class="text-xs font-semibold text-green-500"
                                :class="[report.statistics.total_leads.progress < 0 ?  'text-red-500' : 'text-green-500']"
                            >
                                {{ Math.abs(report.statistics.total_leads.progress.toFixed(2)) }}%
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Average Lead Per Day -->
                <div class="flex flex-col gap-2 rounded-lg border border-gray-200 bg-white px-4 py-5 dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-300">
                        <?php echo app('translator')->get('admin::app.dashboard.index.over-all.average-leads-per-day'); ?>
                    </p>

                    <div class="flex gap-2">
                        <p class="text-xl font-bold dark:text-gray-300">
                            {{ report.statistics.average_leads_per_day.current.toFixed(2) }}
                        </p>

                        <div class="flex items-center gap-0.5">
                            <span
                                class="text-base !font-semibold text-green-500"
                                :class="[report.statistics.average_leads_per_day.progress < 0 ? 'icon-stats-down text-red-500 dark:!text-red-500' : 'icon-stats-up text-green-500 dark:!text-green-500']"
                            ></span>

                            <p
                                class="text-xs font-semibold text-green-500"
                                :class="[report.statistics.average_leads_per_day.progress < 0 ?  'text-red-500' : 'text-green-500']"
                            >
                                {{ Math.abs(report.statistics.average_leads_per_day.progress.toFixed(2)) }}%
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Total Quotes -->
                <div class="flex flex-col gap-2 rounded-lg border border-gray-200 bg-white px-4 py-5 dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-300">
                        <?php echo app('translator')->get('admin::app.dashboard.index.over-all.total-quotations'); ?>
                    </p>

                    <div class="flex gap-2">
                        <p class="text-xl font-bold dark:text-gray-300">
                            {{ report.statistics.total_quotations.current }}
                        </p>

                        <div class="flex items-center gap-0.5">
                            <span
                                class="text-base !font-semibold text-green-500"
                                :class="[report.statistics.total_quotations.progress < 0 ? 'icon-stats-down text-red-500 dark:!text-red-500' : 'icon-stats-up text-green-500 dark:!text-green-500']"
                            ></span>

                            <p
                                class="text-xs font-semibold text-green-500"
                                :class="[report.statistics.total_quotations.progress < 0 ?  'text-red-500' : 'text-green-500']"
                            >
                                {{ Math.abs(report.statistics.total_quotations.progress.toFixed(2)) }}%
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Total Persons -->
                <div class="flex flex-col gap-2 rounded-lg border border-gray-200 bg-white px-4 py-5 dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-300">
                        <?php echo app('translator')->get('admin::app.dashboard.index.over-all.total-persons'); ?>
                    </p>

                    <div class="flex gap-2">
                        <p class="text-xl font-bold dark:text-gray-300">
                            {{ report.statistics.total_persons.current }}
                        </p>

                        <div class="flex items-center gap-0.5">
                            <span
                                class="text-base !font-semibold text-green-500"
                                :class="[report.statistics.total_persons.progress < 0 ? 'icon-stats-down text-red-500 dark:!text-red-500' : 'icon-stats-up text-green-500 dark:!text-green-500']"
                            ></span>

                            <p
                                class="text-xs font-semibold text-green-500"
                                :class="[report.statistics.total_persons.progress < 0 ?  'text-red-500' : 'text-green-500']"
                            >
                                {{ Math.abs(report.statistics.total_persons.progress.toFixed(2)) }}%
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Total Organizations -->
                <div class="flex flex-col gap-2 rounded-lg border border-gray-200 bg-white px-4 py-5 dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-300">
                        <?php echo app('translator')->get('admin::app.dashboard.index.over-all.total-organizations'); ?>
                    </p>

                    <div class="flex gap-2">
                        <p class="text-xl font-bold dark:text-gray-300">
                            {{ report.statistics.total_organizations.current }}
                        </p>

                        <div class="flex items-center gap-0.5">
                            <span
                                class="text-base !font-semibold text-green-500"
                                :class="[report.statistics.total_organizations.progress < 0 ? 'icon-stats-down text-red-500 dark:!text-red-500' : 'icon-stats-up text-green-500 dark:!text-green-500']"
                            ></span>

                            <p
                                class="text-xs font-semibold text-green-500"
                                :class="[report.statistics.total_organizations.progress < 0 ?  'text-red-500' : 'text-green-500']"
                            >
                                {{ Math.abs(report.statistics.total_organizations.progress.toFixed(2)) }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-over-all-stats', {
            template: '#v-dashboard-over-all-stats-template',

            data() {
                return {
                    report: [],

                    isLoading: true,
                    
                    chart: undefined,
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

                    filters.type = 'over-all';

                    this.$axios.get("<?php echo e(route('admin.dashboard.stats')); ?>", {
                            params: filters
                        })
                        .then(response => {
                            this.report = response.data;

                            this.isLoading = false;
                        })
                        .catch(error => {});
                },
            }
        });
    </script>
<?php $__env->stopPush(); endif; ?><?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/dashboard/index/over-all.blade.php ENDPATH**/ ?>