<div class="grid gap-4 rounded-lg border border-gray-200 bg-white px-4 py-2 dark:border-gray-800 dark:bg-gray-900">
    <div class="shimmer h-[17px] w-40"></div>
    
    <!-- Bar Chart -->
    <?php if (isset($component)) { $__componentOriginal2d5323ae793cc705fe0ec994be0ce119 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2d5323ae793cc705fe0ec994be0ce119 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.shimmer.charts.bar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::shimmer.charts.bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2d5323ae793cc705fe0ec994be0ce119)): ?>
<?php $attributes = $__attributesOriginal2d5323ae793cc705fe0ec994be0ce119; ?>
<?php unset($__attributesOriginal2d5323ae793cc705fe0ec994be0ce119); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2d5323ae793cc705fe0ec994be0ce119)): ?>
<?php $component = $__componentOriginal2d5323ae793cc705fe0ec994be0ce119; ?>
<?php unset($__componentOriginal2d5323ae793cc705fe0ec994be0ce119); ?>
<?php endif; ?>

    <div class="flex justify-center gap-5">
        <div class="flex items-center gap-2">
            <div class="shimmer h-3.5 w-3.5 rounded-sm"></div>
            <div class="shimmer h-[17px] w-[76px] rounded-sm"></div>
        </div>
        
        <div class="flex items-center gap-2">
            <div class="shimmer h-3.5 w-3.5 rounded-sm"></div>
            <div class="shimmer h-[17px] w-[76px] rounded-sm"></div>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/components/shimmer/dashboard/index/total-leads.blade.php ENDPATH**/ ?>