<?php if (isset($component)) { $__componentOriginal8001c520f4b7dcb40a16cd3b411856d1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8001c520f4b7dcb40a16cd3b411856d1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.layouts.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::layouts'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('title', null, []); ?> 
        <?php echo app('translator')->get('admin::app.leads.index.title'); ?>
     <?php $__env->endSlot(); ?>

    <!-- Header -->
    <?php echo view_render_event('admin.leads.index.header.before'); ?>


    <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
        <?php echo view_render_event('admin.leads.index.header.left.before'); ?>


        <div class="flex flex-col gap-2">
            <!-- Breadcrumb's -->
            <?php if (isset($component)) { $__componentOriginal477735b45b070062c5df1d72c43d48f5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal477735b45b070062c5df1d72c43d48f5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.breadcrumbs.index','data' => ['name' => 'leads']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::breadcrumbs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'leads']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal477735b45b070062c5df1d72c43d48f5)): ?>
<?php $attributes = $__attributesOriginal477735b45b070062c5df1d72c43d48f5; ?>
<?php unset($__attributesOriginal477735b45b070062c5df1d72c43d48f5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal477735b45b070062c5df1d72c43d48f5)): ?>
<?php $component = $__componentOriginal477735b45b070062c5df1d72c43d48f5; ?>
<?php unset($__componentOriginal477735b45b070062c5df1d72c43d48f5); ?>
<?php endif; ?>

            <div class="text-xl font-bold dark:text-white">
                <?php echo app('translator')->get('admin::app.leads.index.title'); ?>
            </div>
        </div>

        <?php echo view_render_event('admin.leads.index.header.left.after'); ?>


        <?php echo view_render_event('admin.leads.index.header.right.before'); ?>


        <div class="flex items-center gap-x-2.5">
            <!-- Upload File for Lead Creation -->
            <?php if(core()->getConfigData('general.magic_ai.doc_generation.enabled')): ?>
                <?php echo $__env->make('admin::leads.index.upload', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>

            <?php if((request()->view_type ?? "kanban") == "table"): ?>
                <!-- Export Modal -->
                <?php if (isset($component)) { $__componentOriginal3e5e7d009dccab33c23fb94a77703935 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3e5e7d009dccab33c23fb94a77703935 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.datagrid.export.index','data' => ['src' => route('admin.leads.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::datagrid.export'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.leads.index'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3e5e7d009dccab33c23fb94a77703935)): ?>
<?php $attributes = $__attributesOriginal3e5e7d009dccab33c23fb94a77703935; ?>
<?php unset($__attributesOriginal3e5e7d009dccab33c23fb94a77703935); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3e5e7d009dccab33c23fb94a77703935)): ?>
<?php $component = $__componentOriginal3e5e7d009dccab33c23fb94a77703935; ?>
<?php unset($__componentOriginal3e5e7d009dccab33c23fb94a77703935); ?>
<?php endif; ?>
            <?php endif; ?>

            <!-- Create button for Leads -->
            <div class="flex items-center gap-x-2.5">
                <?php if(bouncer()->hasPermission('leads.create')): ?>
                    <a
                        href="<?php echo e(route('admin.leads.create', request()->query())); ?>"
                        class="primary-button"
                    >
                        <?php echo app('translator')->get('admin::app.leads.index.create-btn'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <?php echo view_render_event('admin.leads.index.header.right.after'); ?>

    </div>

    <?php echo view_render_event('admin.leads.index.header.after'); ?>


    <?php echo view_render_event('admin.leads.index.content.before'); ?>


    <!-- Content -->
    <div class="[&>*>*>*.toolbarRight]:max-lg:w-full [&>*>*>*.toolbarRight]:max-lg:justify-between [&>*>*>*.toolbarRight]:max-md:gap-y-2 [&>*>*>*.toolbarRight]:max-md:flex-wrap mt-3.5 [&>*>*:nth-child(1)]:max-lg:!flex-wrap">
        <?php if((request()->view_type ?? "kanban") == "table"): ?>
            <?php echo $__env->make('admin::leads.index.table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php else: ?>
            <?php echo $__env->make('admin::leads.index.kanban', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
    </div>

    <?php echo view_render_event('admin.leads.index.content.after'); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8001c520f4b7dcb40a16cd3b411856d1)): ?>
<?php $attributes = $__attributesOriginal8001c520f4b7dcb40a16cd3b411856d1; ?>
<?php unset($__attributesOriginal8001c520f4b7dcb40a16cd3b411856d1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8001c520f4b7dcb40a16cd3b411856d1)): ?>
<?php $component = $__componentOriginal8001c520f4b7dcb40a16cd3b411856d1; ?>
<?php unset($__componentOriginal8001c520f4b7dcb40a16cd3b411856d1); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/leads/index.blade.php ENDPATH**/ ?>