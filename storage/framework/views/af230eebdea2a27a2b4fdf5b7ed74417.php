<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'name'   => '',
    'entity' => null,
    'route'  => null,
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'name'   => '',
    'entity' => null,
    'route'  => null,
]); ?>
<?php foreach (array_filter(([
    'name'   => '',
    'entity' => null,
    'route'  => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="flex justify-start max-lg:hidden">
    <div class="flex items-center gap-x-3.5">        
        <?php if($route): ?>
            <?php echo e(Breadcrumbs::view('admin::partials.breadcrumbs', $name, $route, $entity)); ?>

        <?php else: ?>
            <?php echo e(Breadcrumbs::view('admin::partials.breadcrumbs', $name, $entity)); ?>

        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/components/breadcrumbs/index.blade.php ENDPATH**/ ?>