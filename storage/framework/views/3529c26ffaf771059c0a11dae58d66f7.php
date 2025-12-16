<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['count' => 30]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['count' => 30]); ?>
<?php foreach (array_filter((['count' => 30]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="flex gap-1.5">
    <div class="grid">
        <?php $__currentLoopData = range(1, 10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="shimmer h-[15px] w-[39px]"></div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="grid w-full gap-1.5">
        <div class="flex aspect-[3.23/1] w-full items-end border-b border-l pl-2.5 dark:border-gray-800">
            <div class="flex aspect-[3.23/1] w-full items-end justify-between gap-5 max-lg:gap-4 max-sm:gap-2.5">
                <?php $__currentLoopData = range(1, $count); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div
                        class="shimmer flex w-full"
                        style="height: <?php echo e(rand(10, 100)); ?>%"
                    ></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div class="flex justify-between gap-5 pl-2.5 max-lg:gap-4 max-sm:gap-2.5">
            <?php $__currentLoopData = range(1, $count); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="shimmer flex h-[15px] w-full"></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/components/shimmer/charts/bar.blade.php ENDPATH**/ ?>