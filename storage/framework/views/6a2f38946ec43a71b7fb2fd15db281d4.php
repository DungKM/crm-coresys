<v-button <?php echo e($attributes); ?>></v-button>

<?php if (! $__env->hasRenderedOnce('3b8d080d-e572-4af0-affe-81306cf7ce57')): $__env->markAsRenderedOnce('3b8d080d-e572-4af0-affe-81306cf7ce57');
$__env->startPush('scripts'); ?>
    <script
        type="text/x-template"
        id="v-button-template"
    >
        <button
            v-if="! loading"
            :class="[buttonClass, '']"
        >
            {{ title }}
        </button>

        <button
            v-else
            :class="[buttonClass, '']"
        >
            <!-- Spinner -->
            <?php if (isset($component)) { $__componentOriginal991e5e3816aa635af8067aa2abbd328b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal991e5e3816aa635af8067aa2abbd328b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.spinner.index','data' => ['class' => 'absolute']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::spinner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'absolute']); ?>
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

            <span class="relative h-full w-full opacity-0">
                {{ title }}
            </span>
        </button>
    </script>

    <script type="module">
        app.component('v-button', {
            template: '#v-button-template',

            props: {
                loading: Boolean,
                buttonType: String,
                title: String,
                buttonClass: String,
            },
        });
    </script>
<?php $__env->stopPush(); endif; ?><?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/components/button/index.blade.php ENDPATH**/ ?>