<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['count' => 5]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['count' => 5]); ?>
<?php foreach (array_filter((['count' => 5]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="flex items-center gap-1">
    <?php for($i = 0; $i < $count; $i++): ?>
        <div class="shimmer h-7 w-16 rounded-md"></div>
    <?php endfor; ?>

    <div class="shimmer h-7 w-7 rounded-md"></div>
</div><?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/components/shimmer/tags/index.blade.php ENDPATH**/ ?>