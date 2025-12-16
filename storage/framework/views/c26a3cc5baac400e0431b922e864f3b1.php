<v-charts-bar <?php echo e($attributes); ?>></v-charts-bar>

<?php if (! $__env->hasRenderedOnce('7781d0ee-8b3c-4300-8ae3-bf6d9b7fdb7f')): $__env->markAsRenderedOnce('7781d0ee-8b3c-4300-8ae3-bf6d9b7fdb7f');
$__env->startPush('scripts'); ?>
    <!-- SEO Vue Component Template -->
    <script
        type="text/x-template"
        id="v-charts-bar-template"
    >
        <canvas
            :id="$.uid + '_chart'"
            class="flex w-full max-w-full items-end"
            :style="'aspect-ratio:' + aspectRatio + '/1'"
            style=""
        ></canvas>
    </script>

    <script type="module">
        app.component('v-charts-bar', {
            template: '#v-charts-bar-template',

            props: {
                labels: {
                    type: Array,
                    default: [],
                },

                datasets: {
                    type: Array,
                    default: [],
                },

                aspectRatio: {
                    type: Number,
                    default: 3.23,
                },
            },

            data() {
                return {
                    chart: undefined,
                }
            },

            mounted() {
                this.prepare();
            },

            methods: {
                prepare() {
                    const barCount = this.datasets.length;
                    
                    this.datasets.forEach((dataset) => {
                        dataset.barThickness = Math.max(4, 36 / barCount);
                    });
        
                    if (this.chart) {
                        this.chart.destroy();
                    }

                    this.chart = new Chart(document.getElementById(this.$.uid + '_chart'), {
                        type: 'bar',
                        
                        data: {
                            labels: this.labels,

                            datasets: this.datasets,
                        },

                        options: {
                            aspectRatio: this.aspectRatio,
                            
                            plugins: {
                                legend: {
                                    display: false
                                },
                            },
                            
                            scales: {
                                x: {
                                    beginAtZero: true,

                                    border: {
                                        dash: [8, 4],
                                    }
                                },

                                y: {
                                    beginAtZero: true,
                                    border: {
                                        dash: [8, 4],
                                    }
                                }
                            }
                        }
                    });
                }
            }
        });
    </script>
<?php $__env->stopPush(); endif; ?><?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/components/charts/bar.blade.php ENDPATH**/ ?>