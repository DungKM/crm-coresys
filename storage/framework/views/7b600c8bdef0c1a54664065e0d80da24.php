<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'customAttributes' => [],
    'entity'           => null,
    'allowEdit'        => false,
    'url'              => null,
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'customAttributes' => [],
    'entity'           => null,
    'allowEdit'        => false,
    'url'              => null,
]); ?>
<?php foreach (array_filter(([
    'customAttributes' => [],
    'entity'           => null,
    'allowEdit'        => false,
    'url'              => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="flex flex-col gap-1">
    <?php $__currentLoopData = $customAttributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(view()->exists($typeView = 'admin::components.attributes.view.' . $attribute->type)): ?>
            <div class="grid grid-cols-[1fr_2fr] items-center gap-1">
                <div class="label dark:text-white"><?php echo e($attribute->name); ?></div>

                <div class="font-medium dark:text-white">
                    <?php echo $__env->make($typeView, [
                        'attribute' => $attribute,
                        'value'     => isset($entity) ? $entity[$attribute->code] : null,
                        'allowEdit' => $allowEdit,
                        'url'       => $url,
                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div><?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/components/attributes/view.blade.php ENDPATH**/ ?>