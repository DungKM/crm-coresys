<!-- Spinner -->
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['color' => 'currentColor']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['color' => 'currentColor']); ?>
<?php foreach (array_filter((['color' => 'currentColor']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<svg
    xmlns="http://www.w3.org/2000/svg"
    fill="none"
    aria-hidden="true"
    viewBox="0 0 24 24"
    <?php echo e($attributes->merge(['class' => 'h-5 w-5 animate-spin dark:text-white'])); ?>

>
    <circle
        class="opacity-25"
        cx="12"
        cy="12"
        r="10"
        stroke="<?php echo e($color); ?>"
        stroke-width="4"
    >
    </circle>

    <path
        class="opacity-75"
        fill="<?php echo e($color); ?>"
        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
    >
    </path>
</svg><?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/components/spinner/index.blade.php ENDPATH**/ ?>