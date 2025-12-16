<?php echo view_render_event('admin.leads.index.view_switcher.before'); ?>


<div class="flex items-center gap-4 max-md:w-full max-md:!justify-between">
    <?php if (isset($component)) { $__componentOriginalaf937e0ec72fa678d3a0c6dc6c0ac5f2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalaf937e0ec72fa678d3a0c6dc6c0ac5f2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.dropdown.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
         <?php $__env->slot('toggle', null, []); ?> 
            <?php echo view_render_event('admin.leads.index.view_switcher.pipeline.button.before'); ?>


            <button
                type="button"
                class="flex cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-[7px] text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
            >
                <span class="whitespace-nowrap">
                    <?php echo e($pipeline->name); ?>

                </span>
                
                <span class="icon-down-arrow text-2xl"></span>
            </button>

            <?php echo view_render_event('admin.leads.index.view_switcher.pipeline.button.after'); ?>

         <?php $__env->endSlot(); ?>

         <?php $__env->slot('content', null, ['class' => '!p-0']); ?> 
            <?php echo view_render_event('admin.leads.index.view_switcher.pipeline.content.header.before'); ?>


            <!-- Header -->
            <div class="flex items-center justify-between px-3 py-2.5">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-300">
                    <?php echo app('translator')->get('admin::app.leads.index.view-switcher.all-pipelines'); ?>
                </span>
            </div>

            <?php echo view_render_event('admin.leads.index.view_switcher.pipeline.content.header.after'); ?>

            
            <!-- Pipeline Links -->
            <?php $__currentLoopData = app('Webkul\Lead\Repositories\PipelineRepository')->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tempPipeline): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo view_render_event('admin.leads.index.view_switcher.pipeline.content.before', ['tempPipeline' => $tempPipeline]); ?>


                <a
                    href="<?php echo e(route('admin.leads.index', [
                        'pipeline_id' => $tempPipeline->id,
                        'view_type'   => request('view_type')
                    ])); ?>"
                    class="block px-3 py-2.5 pl-4 text-gray-600 transition-all hover:bg-gray-100 dark:hover:bg-gray-950 dark:text-gray-300 <?php echo e($pipeline->id == $tempPipeline->id ? 'bg-gray-100 dark:bg-gray-950' : ''); ?>"
                >
                    <?php echo e($tempPipeline->name); ?>

                </a>

                <?php echo view_render_event('admin.leads.index.view_switcher.pipeline.content.after', ['tempPipeline' => $tempPipeline]); ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php echo view_render_event('admin.leads.index.view_switcher.pipeline.content.footer.before'); ?>


            <!-- Footer -->
            <a
                href="<?php echo e(route('admin.settings.pipelines.create')); ?>"
                target="_blank"
                class="flex items-center justify-between border-t border-gray-300 px-3 py-2.5 text-brandColor dark:border-gray-800"
            >
                <span class="font-medium">                    
                    <?php echo app('translator')->get('admin::app.leads.index.view-switcher.create-new-pipeline'); ?>
                </span>
            </a>

            <?php echo view_render_event('admin.leads.index.view_switcher.pipeline.content.footer.after'); ?>

         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalaf937e0ec72fa678d3a0c6dc6c0ac5f2)): ?>
<?php $attributes = $__attributesOriginalaf937e0ec72fa678d3a0c6dc6c0ac5f2; ?>
<?php unset($__attributesOriginalaf937e0ec72fa678d3a0c6dc6c0ac5f2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalaf937e0ec72fa678d3a0c6dc6c0ac5f2)): ?>
<?php $component = $__componentOriginalaf937e0ec72fa678d3a0c6dc6c0ac5f2; ?>
<?php unset($__componentOriginalaf937e0ec72fa678d3a0c6dc6c0ac5f2); ?>
<?php endif; ?>

    <div class="flex items-center gap-0.5">
        <?php echo view_render_event('admin.leads.index.view_switcher.pipeline.view_type.before'); ?>


        <?php if(request('view_type')): ?>
            <a
                class="flex"
                href="<?php echo e(route('admin.leads.index')); ?>"
            >
                <span class="icon-kanban p-2 text-2xl"></span>
            </a>

            <span class="icon-list rounded-md bg-gray-100 p-2 text-2xl dark:bg-gray-950"></span>
        <?php else: ?>
            <span class="icon-kanban rounded-md bg-white p-2 text-2xl dark:bg-gray-900"></span>

            <a
                href="<?php echo e(route('admin.leads.index', ['view_type' => 'table'])); ?>"
                class="flex"
            >
                <span class="icon-list p-2 text-2xl"></span>
            </a>
        <?php endif; ?>

        <?php echo view_render_event('admin.leads.index.view_switcher.pipeline.view_type.after'); ?>

    </div>
</div>

<?php echo view_render_event('admin.leads.index.view_switcher.after'); ?><?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/leads/index/view-switcher.blade.php ENDPATH**/ ?>