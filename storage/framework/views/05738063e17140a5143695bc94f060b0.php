<?php echo view_render_event('admin.leads.view.attributes.before', ['lead' => $lead]); ?>


<div class="flex w-full flex-col gap-4 border-b border-gray-200 p-4 dark:border-gray-800">
    <?php if (isset($component)) { $__componentOriginale6717d929d3edd1e7d9927d6c11ccc02 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale6717d929d3edd1e7d9927d6c11ccc02 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.accordion.index','data' => ['class' => 'select-none !border-none']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::accordion'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'select-none !border-none']); ?>
         <?php $__env->slot('header', null, ['class' => '!p-0']); ?> 
            <div class="flex w-full items-center justify-between gap-4 font-semibold dark:text-white">
                <h4><?php echo app('translator')->get('admin::app.leads.view.attributes.title'); ?></h4>
                
                <?php if(bouncer()->hasPermission('leads.edit')): ?>
                    <a
                        href="<?php echo e(route('admin.leads.edit', $lead->id)); ?>"
                        class="icon-edit rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
                        target="_blank"
                    ></a>
                <?php endif; ?>
            </div>
         <?php $__env->endSlot(); ?>

         <?php $__env->slot('content', null, ['class' => 'mt-4 !px-0 !pb-0']); ?> 
            <?php echo view_render_event('admin.leads.view.attributes.form_controls.before', ['lead' => $lead]); ?>


            <?php if (isset($component)) { $__componentOriginal81b4d293d9113446bb908fc8aef5c8f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal81b4d293d9113446bb908fc8aef5c8f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.index','data' => ['vSlot' => '{ meta, errors, handleSubmit }','as' => 'div','ref' => 'modalForm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['v-slot' => '{ meta, errors, handleSubmit }','as' => 'div','ref' => 'modalForm']); ?>
                <form @submit="handleSubmit($event, () => {})">
                    <?php echo view_render_event('admin.leads.view.attributes.form_controls.attributes.view.before', ['lead' => $lead]); ?>

        
                    <?php if (isset($component)) { $__componentOriginalfedffa6c3d6e1212dbb369a6b5fa34f0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfedffa6c3d6e1212dbb369a6b5fa34f0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.attributes.view','data' => ['customAttributes' => app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                            'entity_type' => 'leads',
                            ['code', 'NOTIN', ['title', 'description', 'lead_pipeline_id', 'lead_pipeline_stage_id']]
                        ]),'entity' => $lead,'url' => route('admin.leads.attributes.update', $lead->id),'allowEdit' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::attributes.view'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['custom-attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                            'entity_type' => 'leads',
                            ['code', 'NOTIN', ['title', 'description', 'lead_pipeline_id', 'lead_pipeline_stage_id']]
                        ])),'entity' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($lead),'url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.leads.attributes.update', $lead->id)),'allow-edit' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfedffa6c3d6e1212dbb369a6b5fa34f0)): ?>
<?php $attributes = $__attributesOriginalfedffa6c3d6e1212dbb369a6b5fa34f0; ?>
<?php unset($__attributesOriginalfedffa6c3d6e1212dbb369a6b5fa34f0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfedffa6c3d6e1212dbb369a6b5fa34f0)): ?>
<?php $component = $__componentOriginalfedffa6c3d6e1212dbb369a6b5fa34f0; ?>
<?php unset($__componentOriginalfedffa6c3d6e1212dbb369a6b5fa34f0); ?>
<?php endif; ?>
        
                    <?php echo view_render_event('admin.leads.view.attributes.form_controls.attributes.view.after', ['lead' => $lead]); ?>

                </form>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal81b4d293d9113446bb908fc8aef5c8f6)): ?>
<?php $attributes = $__attributesOriginal81b4d293d9113446bb908fc8aef5c8f6; ?>
<?php unset($__attributesOriginal81b4d293d9113446bb908fc8aef5c8f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal81b4d293d9113446bb908fc8aef5c8f6)): ?>
<?php $component = $__componentOriginal81b4d293d9113446bb908fc8aef5c8f6; ?>
<?php unset($__componentOriginal81b4d293d9113446bb908fc8aef5c8f6); ?>
<?php endif; ?>
        
            <?php echo view_render_event('admin.leads.view.attributes.form_controls.after', ['lead' => $lead]); ?>

         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale6717d929d3edd1e7d9927d6c11ccc02)): ?>
<?php $attributes = $__attributesOriginale6717d929d3edd1e7d9927d6c11ccc02; ?>
<?php unset($__attributesOriginale6717d929d3edd1e7d9927d6c11ccc02); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale6717d929d3edd1e7d9927d6c11ccc02)): ?>
<?php $component = $__componentOriginale6717d929d3edd1e7d9927d6c11ccc02; ?>
<?php unset($__componentOriginale6717d929d3edd1e7d9927d6c11ccc02); ?>
<?php endif; ?>
</div>

<?php echo view_render_event('admin.leads.view.attributes.before', ['lead' => $lead]); ?>

<?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/leads/view/attributes.blade.php ENDPATH**/ ?>