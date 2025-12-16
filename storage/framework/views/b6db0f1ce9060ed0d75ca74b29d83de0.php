<?php if (isset($component)) { $__componentOriginal18bfb654f62d7deb6056bc468d4a99ec = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18bfb654f62d7deb6056bc468d4a99ec = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.controls.inline.text','data' => ['type' => 'inline',':name' => '\''.e($attribute->code).'\'',':value' => '\''.e($value).'\'','position' => 'left','rules' => 'required','label' => $attribute->name,'placeholder' => $attribute->name,':errors' => 'errors','url' => $url,'allowEdit' => $allowEdit]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group.controls.inline.text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'inline',':name' => '\''.e($attribute->code).'\'',':value' => '\''.e($value).'\'','position' => 'left','rules' => 'required','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attribute->name),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attribute->name),':errors' => 'errors','url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($url),'allow-edit' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($allowEdit)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18bfb654f62d7deb6056bc468d4a99ec)): ?>
<?php $attributes = $__attributesOriginal18bfb654f62d7deb6056bc468d4a99ec; ?>
<?php unset($__attributesOriginal18bfb654f62d7deb6056bc468d4a99ec); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18bfb654f62d7deb6056bc468d4a99ec)): ?>
<?php $component = $__componentOriginal18bfb654f62d7deb6056bc468d4a99ec; ?>
<?php unset($__componentOriginal18bfb654f62d7deb6056bc468d4a99ec); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/components/attributes/view/price.blade.php ENDPATH**/ ?>