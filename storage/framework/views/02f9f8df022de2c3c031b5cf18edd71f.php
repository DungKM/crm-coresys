<?php
    $options = $attribute->lookup_type
        ? app('Webkul\Attribute\Repositories\AttributeRepository')->getLookUpOptions($attribute->lookup_type)
        : $attribute->options()->orderBy('sort_order')->get();
?>

<?php if (isset($component)) { $__componentOriginal714fa23b49b59f0b5b989626b981b2e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal714fa23b49b59f0b5b989626b981b2e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.controls.inline.select','data' => [':name' => '\''.e($attribute->code).'\'','value' => $value,'options' => $options,'rules' => 'required','position' => 'left','label' => $attribute->name,':errors' => 'errors','placeholder' => $attribute->name,'url' => $url,'allowEdit' => $allowEdit]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group.controls.inline.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([':name' => '\''.e($attribute->code).'\'','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($value),'options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($options),'rules' => 'required','position' => 'left','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attribute->name),':errors' => 'errors','placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attribute->name),'url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($url),'allow-edit' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($allowEdit)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal714fa23b49b59f0b5b989626b981b2e1)): ?>
<?php $attributes = $__attributesOriginal714fa23b49b59f0b5b989626b981b2e1; ?>
<?php unset($__attributesOriginal714fa23b49b59f0b5b989626b981b2e1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal714fa23b49b59f0b5b989626b981b2e1)): ?>
<?php $component = $__componentOriginal714fa23b49b59f0b5b989626b981b2e1; ?>
<?php unset($__componentOriginal714fa23b49b59f0b5b989626b981b2e1); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/components/attributes/view/select.blade.php ENDPATH**/ ?>