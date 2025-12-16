<!-- Stages Navigation -->
<?php echo view_render_event('admin.leads.view.stages.before', ['lead' => $lead]); ?>


<!-- Stages Vue Component -->
<v-lead-stages>
    <?php if (isset($component)) { $__componentOriginal764305c35593e3abd5b9e523677855a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal764305c35593e3abd5b9e523677855a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.shimmer.leads.view.stages','data' => ['count' => $lead->pipeline->stages->count() - 1]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::shimmer.leads.view.stages'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['count' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($lead->pipeline->stages->count() - 1)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal764305c35593e3abd5b9e523677855a5)): ?>
<?php $attributes = $__attributesOriginal764305c35593e3abd5b9e523677855a5; ?>
<?php unset($__attributesOriginal764305c35593e3abd5b9e523677855a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal764305c35593e3abd5b9e523677855a5)): ?>
<?php $component = $__componentOriginal764305c35593e3abd5b9e523677855a5; ?>
<?php unset($__componentOriginal764305c35593e3abd5b9e523677855a5); ?>
<?php endif; ?>
</v-lead-stages>

<?php echo view_render_event('admin.leads.view.stages.after', ['lead' => $lead]); ?>


<?php if (! $__env->hasRenderedOnce('d2255791-2669-4210-8d13-2929ff9ecfa7')): $__env->markAsRenderedOnce('d2255791-2669-4210-8d13-2929ff9ecfa7');
$__env->startPush('scripts'); ?>
    <script type="text/x-template" id="v-lead-stages-template">
        <!-- Stages Container -->
        <div
            class="flex w-full max-w-full"
            :class="{'opacity-50 pointer-events-none': isUpdating}"
        >
            <!-- Stages Item -->
            <template v-for="stage in stages">
                <?php echo view_render_event('admin.leads.view.stages.items.before', ['lead' => $lead]); ?>


                <div
                    class="stage relative flex h-7 cursor-pointer items-center justify-center bg-white pl-7 pr-4 dark:bg-gray-900 ltr:first:rounded-l-lg rtl:first:rounded-r-lg"
                    :class="{
                        '!bg-green-500 text-white dark:text-gray-900 ltr:after:bg-green-500 rtl:before:bg-green-500': currentStage.sort_order >= stage.sort_order,
                        '!bg-red-500 text-white dark:text-gray-900 ltr:after:bg-red-500 rtl:before:bg-red-500': currentStage.code == 'lost',
                    }"
                    v-if="! ['won', 'lost'].includes(stage.code)"
                    @click="update(stage)"
                >
                    <span class="z-20 whitespace-nowrap text-sm font-medium dark:text-white">
                        {{ stage.name }}
                    </span>
                </div>

                <?php echo view_render_event('admin.leads.view.stages.items.after', ['lead' => $lead]); ?>

            </template>

            <?php echo view_render_event('admin.leads.view.stages.items.dropdown.before', ['lead' => $lead]); ?>


            <!-- Won/Lost Stage Item -->
            <?php if (isset($component)) { $__componentOriginalaf937e0ec72fa678d3a0c6dc6c0ac5f2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalaf937e0ec72fa678d3a0c6dc6c0ac5f2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.dropdown.index','data' => ['position' => 'bottom-right']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['position' => 'bottom-right']); ?>
                 <?php $__env->slot('toggle', null, []); ?> 
                    <?php echo view_render_event('admin.leads.view.stages.items.dropdown.toggle.before', ['lead' => $lead]); ?>


                    <div
                        class="relative flex h-7 min-w-24 cursor-pointer items-center justify-center rounded-r-lg bg-white pl-7 pr-4 dark:bg-gray-900"
                        :class="{
                            '!bg-green-500 text-white dark:text-gray-900 after:bg-green-500': ['won', 'lost'].includes(currentStage.code) && currentStage.code == 'won',
                            '!bg-red-500 text-white dark:text-gray-900 after:bg-red-500': ['won', 'lost'].includes(currentStage.code) && currentStage.code == 'lost',
                        }"
                        @click="stageToggler = ! stageToggler"
                    >
                        <span class="z-20 whitespace-nowrap text-sm font-medium dark:text-white">
                            <?php echo e(__('admin::app.leads.view.stages.won-lost')); ?>

                        </span>

                        <span
                            class="text-2xl dark:text-gray-900"
                            :class="{'icon-up-arrow': stageToggler, 'icon-down-arrow': ! stageToggler}"
                        ></span>
                    </div>

                    <?php echo view_render_event('admin.leads.view.stages.items.dropdown.toggle.after', ['lead' => $lead]); ?>

                 <?php $__env->endSlot(); ?>

                 <?php $__env->slot('menu', null, []); ?> 
                    <?php echo view_render_event('admin.leads.view.stages.items.dropdown.menu_item.before', ['lead' => $lead]); ?>


                    <?php if (isset($component)) { $__componentOriginal0223c8534d6a243be608c3a65289c4d0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0223c8534d6a243be608c3a65289c4d0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.dropdown.menu.item','data' => ['@click' => 'openModal(this.stages.find(stage => stage.code == \'won\'))']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::dropdown.menu.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['@click' => 'openModal(this.stages.find(stage => stage.code == \'won\'))']); ?>
                        <?php echo app('translator')->get('admin::app.leads.view.stages.won'); ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0223c8534d6a243be608c3a65289c4d0)): ?>
<?php $attributes = $__attributesOriginal0223c8534d6a243be608c3a65289c4d0; ?>
<?php unset($__attributesOriginal0223c8534d6a243be608c3a65289c4d0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0223c8534d6a243be608c3a65289c4d0)): ?>
<?php $component = $__componentOriginal0223c8534d6a243be608c3a65289c4d0; ?>
<?php unset($__componentOriginal0223c8534d6a243be608c3a65289c4d0); ?>
<?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginal0223c8534d6a243be608c3a65289c4d0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0223c8534d6a243be608c3a65289c4d0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.dropdown.menu.item','data' => ['@click' => 'openModal(this.stages.find(stage => stage.code == \'lost\'))']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::dropdown.menu.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['@click' => 'openModal(this.stages.find(stage => stage.code == \'lost\'))']); ?>
                        <?php echo app('translator')->get('admin::app.leads.view.stages.lost'); ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0223c8534d6a243be608c3a65289c4d0)): ?>
<?php $attributes = $__attributesOriginal0223c8534d6a243be608c3a65289c4d0; ?>
<?php unset($__attributesOriginal0223c8534d6a243be608c3a65289c4d0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0223c8534d6a243be608c3a65289c4d0)): ?>
<?php $component = $__componentOriginal0223c8534d6a243be608c3a65289c4d0; ?>
<?php unset($__componentOriginal0223c8534d6a243be608c3a65289c4d0); ?>
<?php endif; ?>

                    <?php echo view_render_event('admin.leads.view.stages.items.dropdown.menu_item.after', ['lead' => $lead]); ?>

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

            <?php echo view_render_event('admin.leads.view.stages.items.dropdown.after', ['lead' => $lead]); ?>


            <?php echo view_render_event('admin.leads.view.stages.form_controls.before', ['lead' => $lead]); ?>


            <?php if (isset($component)) { $__componentOriginal81b4d293d9113446bb908fc8aef5c8f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal81b4d293d9113446bb908fc8aef5c8f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.index','data' => ['vSlot' => '{ meta, errors, handleSubmit }','as' => 'div','ref' => 'stageUpdateForm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['v-slot' => '{ meta, errors, handleSubmit }','as' => 'div','ref' => 'stageUpdateForm']); ?>
                <form @submit="handleSubmit($event, handleFormSubmit)">
                    <?php echo view_render_event('admin.leads.view.stages.form_controls.modal.before', ['lead' => $lead]); ?>


                    <?php if (isset($component)) { $__componentOriginal09768308838b828c7799162f44758281 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal09768308838b828c7799162f44758281 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.modal.index','data' => ['ref' => 'stageUpdateModal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['ref' => 'stageUpdateModal']); ?>
                         <?php $__env->slot('header', null, []); ?> 
                            <?php echo view_render_event('admin.leads.view.stages.form_controls.modal.header.before', ['lead' => $lead]); ?>


                            <h3 class="text-base font-semibold dark:text-white">
                                <?php echo app('translator')->get('admin::app.leads.view.stages.need-more-info'); ?>
                            </h3>

                            <?php echo view_render_event('admin.leads.view.stages.form_controls.modal.header.after', ['lead' => $lead]); ?>

                         <?php $__env->endSlot(); ?>

                         <?php $__env->slot('content', null, []); ?> 
                            <?php echo view_render_event('admin.leads.view.stages.form_controls.modal.content.before', ['lead' => $lead]); ?>


                            <!-- Won Value -->
                            <template v-if="nextStage.code == 'won'">
                                <?php if (isset($component)) { $__componentOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                    <?php if (isset($component)) { $__componentOriginal8378211f70f8c39b16d47cecdac9c7c8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8378211f70f8c39b16d47cecdac9c7c8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.label','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                        <?php echo app('translator')->get('admin::app.leads.view.stages.won-value'); ?>
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8378211f70f8c39b16d47cecdac9c7c8)): ?>
<?php $attributes = $__attributesOriginal8378211f70f8c39b16d47cecdac9c7c8; ?>
<?php unset($__attributesOriginal8378211f70f8c39b16d47cecdac9c7c8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8378211f70f8c39b16d47cecdac9c7c8)): ?>
<?php $component = $__componentOriginal8378211f70f8c39b16d47cecdac9c7c8; ?>
<?php unset($__componentOriginal8378211f70f8c39b16d47cecdac9c7c8); ?>
<?php endif; ?>

                                    <?php if (isset($component)) { $__componentOriginal53af403f6b2179a3039d488b8ab2a267 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53af403f6b2179a3039d488b8ab2a267 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.control','data' => ['type' => 'price','name' => 'lead_value','value' => $lead->lead_value,'vModel' => 'nextStage.lead_value']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'price','name' => 'lead_value','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($lead->lead_value),'v-model' => 'nextStage.lead_value']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53af403f6b2179a3039d488b8ab2a267)): ?>
<?php $attributes = $__attributesOriginal53af403f6b2179a3039d488b8ab2a267; ?>
<?php unset($__attributesOriginal53af403f6b2179a3039d488b8ab2a267); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53af403f6b2179a3039d488b8ab2a267)): ?>
<?php $component = $__componentOriginal53af403f6b2179a3039d488b8ab2a267; ?>
<?php unset($__componentOriginal53af403f6b2179a3039d488b8ab2a267); ?>
<?php endif; ?>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3)): ?>
<?php $attributes = $__attributesOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3; ?>
<?php unset($__attributesOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3)): ?>
<?php $component = $__componentOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3; ?>
<?php unset($__componentOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3); ?>
<?php endif; ?>
                            </template>

                            <!-- Lost Reason -->
                            <template v-else>
                                <?php if (isset($component)) { $__componentOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                    <?php if (isset($component)) { $__componentOriginal8378211f70f8c39b16d47cecdac9c7c8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8378211f70f8c39b16d47cecdac9c7c8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.label','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                        <?php echo app('translator')->get('admin::app.leads.view.stages.lost-reason'); ?>
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8378211f70f8c39b16d47cecdac9c7c8)): ?>
<?php $attributes = $__attributesOriginal8378211f70f8c39b16d47cecdac9c7c8; ?>
<?php unset($__attributesOriginal8378211f70f8c39b16d47cecdac9c7c8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8378211f70f8c39b16d47cecdac9c7c8)): ?>
<?php $component = $__componentOriginal8378211f70f8c39b16d47cecdac9c7c8; ?>
<?php unset($__componentOriginal8378211f70f8c39b16d47cecdac9c7c8); ?>
<?php endif; ?>

                                    <?php if (isset($component)) { $__componentOriginal53af403f6b2179a3039d488b8ab2a267 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53af403f6b2179a3039d488b8ab2a267 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.control','data' => ['type' => 'textarea','name' => 'lost_reason','vModel' => 'nextStage.lost_reason']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'textarea','name' => 'lost_reason','v-model' => 'nextStage.lost_reason']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53af403f6b2179a3039d488b8ab2a267)): ?>
<?php $attributes = $__attributesOriginal53af403f6b2179a3039d488b8ab2a267; ?>
<?php unset($__attributesOriginal53af403f6b2179a3039d488b8ab2a267); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53af403f6b2179a3039d488b8ab2a267)): ?>
<?php $component = $__componentOriginal53af403f6b2179a3039d488b8ab2a267; ?>
<?php unset($__componentOriginal53af403f6b2179a3039d488b8ab2a267); ?>
<?php endif; ?>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3)): ?>
<?php $attributes = $__attributesOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3; ?>
<?php unset($__attributesOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3)): ?>
<?php $component = $__componentOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3; ?>
<?php unset($__componentOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3); ?>
<?php endif; ?>
                            </template>

                            <!-- Closed At -->
                            <?php if (isset($component)) { $__componentOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                <?php if (isset($component)) { $__componentOriginal8378211f70f8c39b16d47cecdac9c7c8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8378211f70f8c39b16d47cecdac9c7c8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.label','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                    <?php echo app('translator')->get('admin::app.leads.view.stages.closed-at'); ?>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8378211f70f8c39b16d47cecdac9c7c8)): ?>
<?php $attributes = $__attributesOriginal8378211f70f8c39b16d47cecdac9c7c8; ?>
<?php unset($__attributesOriginal8378211f70f8c39b16d47cecdac9c7c8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8378211f70f8c39b16d47cecdac9c7c8)): ?>
<?php $component = $__componentOriginal8378211f70f8c39b16d47cecdac9c7c8; ?>
<?php unset($__componentOriginal8378211f70f8c39b16d47cecdac9c7c8); ?>
<?php endif; ?>

                                <?php if (isset($component)) { $__componentOriginal53af403f6b2179a3039d488b8ab2a267 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53af403f6b2179a3039d488b8ab2a267 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.control','data' => ['type' => 'datetime','name' => 'closed_at','vModel' => 'nextStage.closed_at','label' => trans('admin::app.leads.view.stages.closed-at')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'datetime','name' => 'closed_at','v-model' => 'nextStage.closed_at','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('admin::app.leads.view.stages.closed-at'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53af403f6b2179a3039d488b8ab2a267)): ?>
<?php $attributes = $__attributesOriginal53af403f6b2179a3039d488b8ab2a267; ?>
<?php unset($__attributesOriginal53af403f6b2179a3039d488b8ab2a267); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53af403f6b2179a3039d488b8ab2a267)): ?>
<?php $component = $__componentOriginal53af403f6b2179a3039d488b8ab2a267; ?>
<?php unset($__componentOriginal53af403f6b2179a3039d488b8ab2a267); ?>
<?php endif; ?>

                                <?php if (isset($component)) { $__componentOriginal8da25fb6534e2ef288914e35c32417f8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8da25fb6534e2ef288914e35c32417f8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.error','data' => ['controlName' => 'closed_at']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['control-name' => 'closed_at']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8da25fb6534e2ef288914e35c32417f8)): ?>
<?php $attributes = $__attributesOriginal8da25fb6534e2ef288914e35c32417f8; ?>
<?php unset($__attributesOriginal8da25fb6534e2ef288914e35c32417f8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8da25fb6534e2ef288914e35c32417f8)): ?>
<?php $component = $__componentOriginal8da25fb6534e2ef288914e35c32417f8; ?>
<?php unset($__componentOriginal8da25fb6534e2ef288914e35c32417f8); ?>
<?php endif; ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3)): ?>
<?php $attributes = $__attributesOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3; ?>
<?php unset($__attributesOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3)): ?>
<?php $component = $__componentOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3; ?>
<?php unset($__componentOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3); ?>
<?php endif; ?>

                            <?php echo view_render_event('admin.leads.view.stages.form_controls.modal.content.after', ['lead' => $lead]); ?>

                         <?php $__env->endSlot(); ?>

                         <?php $__env->slot('footer', null, []); ?> 
                            <?php echo view_render_event('admin.leads.view.stages.form_controls.modal.footer.before', ['lead' => $lead]); ?>


                            <button
                                type="submit"
                                class="primary-button"
                            >
                                <?php echo app('translator')->get('admin::app.leads.view.stages.save-btn'); ?>
                            </button>

                            <?php echo view_render_event('admin.leads.view.stages.form_controls.modal.footer.after', ['lead' => $lead]); ?>

                         <?php $__env->endSlot(); ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal09768308838b828c7799162f44758281)): ?>
<?php $attributes = $__attributesOriginal09768308838b828c7799162f44758281; ?>
<?php unset($__attributesOriginal09768308838b828c7799162f44758281); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal09768308838b828c7799162f44758281)): ?>
<?php $component = $__componentOriginal09768308838b828c7799162f44758281; ?>
<?php unset($__componentOriginal09768308838b828c7799162f44758281); ?>
<?php endif; ?>

                    <?php echo view_render_event('admin.leads.view.stages.form_controls.modal.after', ['lead' => $lead]); ?>

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

            <?php echo view_render_event('admin.leads.view.stages.form_controls.after', ['lead' => $lead]); ?>

        </div>
    </script>

    <script type="module">
        app.component('v-lead-stages', {
            template: '#v-lead-stages-template',

            data() {
                return {
                    isUpdating: false,

                    currentStage: <?php echo json_encode($lead->stage, 15, 512) ?>,

                    nextStage: null,

                    stages: <?php echo json_encode($lead->pipeline->stages, 15, 512) ?>,

                    stageToggler: '',
                }
            },

            methods: {
                openModal(stage) {
                    if (this.currentStage.code == stage.code) {
                        return;
                    }

                    this.nextStage = stage;

                    this.$refs.stageUpdateModal.open();
                },

                handleFormSubmit(event) {
                    let params = {
                        'lead_pipeline_stage_id': this.nextStage.id
                    };

                    if (this.nextStage.code == 'won') {
                        params.lead_value = this.nextStage.lead_value;

                        params.closed_at = this.nextStage.closed_at;
                    } else if (this.nextStage.code == 'lost') {
                        params.lost_reason = this.nextStage.lost_reason;

                        params.closed_at = this.nextStage.closed_at;
                    }

                    this.update(this.nextStage, params);
                },

                update(stage, params = null) {
                    if (this.currentStage.code == stage.code) {
                        return;
                    }

                    this.$refs.stageUpdateModal.close();

                    this.isUpdating = true;

                    this.$axios
                        .put("<?php echo e(route('admin.leads.stage.update', $lead->id)); ?>", params ?? {
                            'lead_pipeline_stage_id': stage.id
                        })
                        .then ((response) => {
                            this.isUpdating = false;

                            this.currentStage = stage;

                            this.$parent.$refs.activities.get();

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                        })
                        .catch ((error) => {
                            this.isUpdating = false;

                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                        });
                },
            },
        });
    </script>
<?php $__env->stopPush(); endif; ?>
<?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/leads/view/stages.blade.php ENDPATH**/ ?>