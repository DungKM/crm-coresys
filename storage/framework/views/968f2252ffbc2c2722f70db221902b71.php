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

<div class="flex">
    <?php for($i = 0; $i < $count; $i++): ?>
        <div class="stage relative flex h-7 min-w-24 cursor-pointer items-center justify-center bg-white pl-7 pr-4 first:rounded-l-lg dark:bg-gray-900">
            <div class="shimmer h-5 w-[68px]"></div>
        </div>
    <?php endfor; ?>
</div><?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/components/shimmer/leads/view/stages.blade.php ENDPATH**/ ?>