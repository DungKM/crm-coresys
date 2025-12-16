<?php echo view_render_event('admin.leads.view.person.before', ['lead' => $lead]); ?>


<?php if($lead?->person): ?>
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
                    <h4 ><?php echo app('translator')->get('admin::app.leads.view.persons.title'); ?></h4>

                    <?php if(bouncer()->hasPermission('contacts.persons.edit')): ?>
                        <a
                            href="<?php echo e(route('admin.contacts.persons.edit', $lead->person->id)); ?>"
                            class="icon-edit rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
                            target="_blank"
                        ></a>
                    <?php endif; ?>
                </div>
             <?php $__env->endSlot(); ?>
            
             <?php $__env->slot('content', null, ['class' => 'mt-4 !px-0 !pb-0']); ?> 
                <div class="flex gap-2">
                    <?php echo view_render_event('admin.leads.view.person.avatar.before', ['lead' => $lead]); ?>

        
                    <!-- Person Initials -->
                    <?php if (isset($component)) { $__componentOriginal2d42bddad77c068ade50efea9ce906c7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2d42bddad77c068ade50efea9ce906c7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.avatar.index','data' => ['name' => $lead->person->name]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($lead->person->name)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2d42bddad77c068ade50efea9ce906c7)): ?>
<?php $attributes = $__attributesOriginal2d42bddad77c068ade50efea9ce906c7; ?>
<?php unset($__attributesOriginal2d42bddad77c068ade50efea9ce906c7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2d42bddad77c068ade50efea9ce906c7)): ?>
<?php $component = $__componentOriginal2d42bddad77c068ade50efea9ce906c7; ?>
<?php unset($__componentOriginal2d42bddad77c068ade50efea9ce906c7); ?>
<?php endif; ?>
        
                    <?php echo view_render_event('admin.leads.view.person.avatar.after', ['lead' => $lead]); ?>

        
                    <!-- Person Details -->
                    <div class="flex flex-col gap-1">
                        <?php echo view_render_event('admin.leads.view.person.name.before', ['lead' => $lead]); ?>

        
                        <a
                            href="<?php echo e(route('admin.contacts.persons.view', $lead->person->id)); ?>"
                            class="font-semibold text-brandColor"
                            target="_blank"
                        >
                            <?php echo e($lead->person->name); ?>

                        </a>
        
                        <?php echo view_render_event('admin.leads.view.person.name.after', ['lead' => $lead]); ?>

        
                        <?php echo view_render_event('admin.leads.view.person.job_title.before', ['lead' => $lead]); ?>

        
                        <?php if($lead->person->job_title): ?>
                            <span class="dark:text-white">
                                <?php if($lead->person->organization): ?>
                                    <?php echo app('translator')->get('admin::app.leads.view.persons.job-title', [
                                        'job_title'    => $lead->person->job_title,
                                        'organization' => $lead->person->organization->name
                                    ]); ?>
                                <?php else: ?>
                                    <?php echo e($lead->person->job_title); ?>

                                <?php endif; ?>
                            </span>
                        <?php endif; ?>
        
                        <?php echo view_render_event('admin.leads.view.person.job_title.after', ['lead' => $lead]); ?>

                    
                        <?php echo view_render_event('admin.leads.view.person.email.before', ['lead' => $lead]); ?>

        
                        <?php $__currentLoopData = $lead->person->emails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $email): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex gap-1">
                                <a 
                                    class="text-brandColor"
                                    href="mailto:<?php echo e($email['value']); ?>"
                                >
                                    <?php echo e($email['value']); ?>

                                </a>
        
                                <span class="text-gray-500 dark:text-gray-300">
                                    (<?php echo e($email['label']); ?>)
                                </span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        
                        <?php echo view_render_event('admin.leads.view.person.email.after', ['lead' => $lead]); ?>

        
                        <?php echo view_render_event('admin.leads.view.person.contact_numbers.before', ['lead' => $lead]); ?>

                    
                        <?php $__currentLoopData = $lead->person->contact_numbers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contactNumber): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex gap-1">
                                <a  
                                    class="text-brandColor"
                                    href="callto:<?php echo e($contactNumber['value']); ?>"
                                >
                                    <?php echo e($contactNumber['value']); ?>

                                </a>
        
                                <span class="text-gray-500 dark:text-gray-300">
                                    (<?php echo e($contactNumber['label']); ?>)
                                </span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        
                        <?php echo view_render_event('admin.leads.view.person.contact_numbers.after', ['lead' => $lead]); ?>

                    </div>
                </div>
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
<?php endif; ?>
<?php echo view_render_event('admin.leads.view.person.after', ['lead' => $lead]); ?><?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/leads/view/person.blade.php ENDPATH**/ ?>