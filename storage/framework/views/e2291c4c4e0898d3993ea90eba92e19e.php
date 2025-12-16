<?php
    if (! empty($value)) {
        if ($value instanceof \Carbon\Carbon) {
            $value = $value->format('Y-m-d');
        } elseif (is_string($value)) {
            $value = \Carbon\Carbon::parse($value)->format('Y-m-d');
        }
    }
?>

<?php if (isset($component)) { $__componentOriginal37d47d7aca4b707a5829dd03ab43e917 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal37d47d7aca4b707a5829dd03ab43e917 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.controls.inline.date','data' => [':name' => '\''.e($attribute->code).'\'',':value' => '\''.e($value).'\'','rules' => 'required','position' => 'left','label' => $attribute->name,':errors' => 'errors','placeholder' => $attribute->name,'url' => $url,'allowEdit' => $allowEdit]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group.controls.inline.date'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([':name' => '\''.e($attribute->code).'\'',':value' => '\''.e($value).'\'','rules' => 'required','position' => 'left','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attribute->name),':errors' => 'errors','placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attribute->name),'url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($url),'allow-edit' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($allowEdit)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal37d47d7aca4b707a5829dd03ab43e917)): ?>
<?php $attributes = $__attributesOriginal37d47d7aca4b707a5829dd03ab43e917; ?>
<?php unset($__attributesOriginal37d47d7aca4b707a5829dd03ab43e917); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal37d47d7aca4b707a5829dd03ab43e917)): ?>
<?php $component = $__componentOriginal37d47d7aca4b707a5829dd03ab43e917; ?>
<?php unset($__componentOriginal37d47d7aca4b707a5829dd03ab43e917); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/components/attributes/view/date.blade.php ENDPATH**/ ?>