<?php echo view_render_event('admin.leads.view.quotes.before', ['lead' => $lead]); ?>


<v-lead-quotes></v-lead-quotes>

<?php echo view_render_event('admin.leads.view.quotes.after', ['lead' => $lead]); ?>


<?php if (! $__env->hasRenderedOnce('87132b8f-6be8-4bf0-9a99-a578481f5114')): $__env->markAsRenderedOnce('87132b8f-6be8-4bf0-9a99-a578481f5114');
$__env->startPush('scripts'); ?>
    <script
        type="text/x-template"
        id="v-lead-quotes-template"
    >
        <?php if(bouncer()->hasPermission('quotes')): ?>
            <div class="p-3">
                <?php echo view_render_event('admin.leads.view.quotes.table.before', ['lead' => $lead]); ?>


                <?php if (isset($component)) { $__componentOriginala9dad9f471f1e8ff345be80579eb8136 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala9dad9f471f1e8ff345be80579eb8136 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.index','data' => ['vIf' => 'quotes.length']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['v-if' => 'quotes.length']); ?>
                    <?php echo view_render_event('admin.leads.view.quotes.table.table_head.before', ['lead' => $lead]); ?>


                    <?php if (isset($component)) { $__componentOriginal8ee89c0b398bd7314c2e7815b044fc82 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8ee89c0b398bd7314c2e7815b044fc82 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.thead.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.thead'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                        <?php if (isset($component)) { $__componentOriginal95a122c91c33f6d66a15a82d7ca67172 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal95a122c91c33f6d66a15a82d7ca67172 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.thead.tr','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.thead.tr'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                            <?php if (isset($component)) { $__componentOriginal2b66f2da706603ab43da37c4a360ae32 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2b66f2da706603ab43da37c4a360ae32 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.th','data' => ['class' => '!px-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.th'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!px-2']); ?>
                                <?php echo app('translator')->get('admin::app.leads.view.quotes.subject'); ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2b66f2da706603ab43da37c4a360ae32)): ?>
<?php $attributes = $__attributesOriginal2b66f2da706603ab43da37c4a360ae32; ?>
<?php unset($__attributesOriginal2b66f2da706603ab43da37c4a360ae32); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2b66f2da706603ab43da37c4a360ae32)): ?>
<?php $component = $__componentOriginal2b66f2da706603ab43da37c4a360ae32; ?>
<?php unset($__componentOriginal2b66f2da706603ab43da37c4a360ae32); ?>
<?php endif; ?>

                            <?php if (isset($component)) { $__componentOriginal2b66f2da706603ab43da37c4a360ae32 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2b66f2da706603ab43da37c4a360ae32 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.th','data' => ['class' => '!px-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.th'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!px-2']); ?>
                                <?php echo app('translator')->get('admin::app.leads.view.quotes.expired-at'); ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2b66f2da706603ab43da37c4a360ae32)): ?>
<?php $attributes = $__attributesOriginal2b66f2da706603ab43da37c4a360ae32; ?>
<?php unset($__attributesOriginal2b66f2da706603ab43da37c4a360ae32); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2b66f2da706603ab43da37c4a360ae32)): ?>
<?php $component = $__componentOriginal2b66f2da706603ab43da37c4a360ae32; ?>
<?php unset($__componentOriginal2b66f2da706603ab43da37c4a360ae32); ?>
<?php endif; ?>

                            <?php if (isset($component)) { $__componentOriginal2b66f2da706603ab43da37c4a360ae32 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2b66f2da706603ab43da37c4a360ae32 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.th','data' => ['class' => '!px-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.th'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!px-2']); ?>
                                <?php echo app('translator')->get('admin::app.leads.view.quotes.sub-total'); ?>
                                <span class="currency-code">(<?php echo e(core()->currencySymbol(config('app.currency'))); ?>)</span>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2b66f2da706603ab43da37c4a360ae32)): ?>
<?php $attributes = $__attributesOriginal2b66f2da706603ab43da37c4a360ae32; ?>
<?php unset($__attributesOriginal2b66f2da706603ab43da37c4a360ae32); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2b66f2da706603ab43da37c4a360ae32)): ?>
<?php $component = $__componentOriginal2b66f2da706603ab43da37c4a360ae32; ?>
<?php unset($__componentOriginal2b66f2da706603ab43da37c4a360ae32); ?>
<?php endif; ?>

                            <?php if (isset($component)) { $__componentOriginal2b66f2da706603ab43da37c4a360ae32 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2b66f2da706603ab43da37c4a360ae32 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.th','data' => ['class' => '!px-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.th'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!px-2']); ?>
                                <?php echo app('translator')->get('admin::app.leads.view.quotes.discount'); ?>
                                <span class="currency-code">(<?php echo e(core()->currencySymbol(config('app.currency'))); ?>)</span>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2b66f2da706603ab43da37c4a360ae32)): ?>
<?php $attributes = $__attributesOriginal2b66f2da706603ab43da37c4a360ae32; ?>
<?php unset($__attributesOriginal2b66f2da706603ab43da37c4a360ae32); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2b66f2da706603ab43da37c4a360ae32)): ?>
<?php $component = $__componentOriginal2b66f2da706603ab43da37c4a360ae32; ?>
<?php unset($__componentOriginal2b66f2da706603ab43da37c4a360ae32); ?>
<?php endif; ?>

                            <?php if (isset($component)) { $__componentOriginal2b66f2da706603ab43da37c4a360ae32 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2b66f2da706603ab43da37c4a360ae32 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.th','data' => ['class' => '!px-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.th'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!px-2']); ?>
                                <?php echo app('translator')->get('admin::app.leads.view.quotes.tax'); ?>
                                <span class="currency-code">(<?php echo e(core()->currencySymbol(config('app.currency'))); ?>)</span>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2b66f2da706603ab43da37c4a360ae32)): ?>
<?php $attributes = $__attributesOriginal2b66f2da706603ab43da37c4a360ae32; ?>
<?php unset($__attributesOriginal2b66f2da706603ab43da37c4a360ae32); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2b66f2da706603ab43da37c4a360ae32)): ?>
<?php $component = $__componentOriginal2b66f2da706603ab43da37c4a360ae32; ?>
<?php unset($__componentOriginal2b66f2da706603ab43da37c4a360ae32); ?>
<?php endif; ?>

                            <?php if (isset($component)) { $__componentOriginal2b66f2da706603ab43da37c4a360ae32 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2b66f2da706603ab43da37c4a360ae32 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.th','data' => ['class' => '!px-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.th'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!px-2']); ?>
                                <?php echo app('translator')->get('admin::app.leads.view.quotes.adjustment'); ?>
                                <span class="currency-code">(<?php echo e(core()->currencySymbol(config('app.currency'))); ?>)</span>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2b66f2da706603ab43da37c4a360ae32)): ?>
<?php $attributes = $__attributesOriginal2b66f2da706603ab43da37c4a360ae32; ?>
<?php unset($__attributesOriginal2b66f2da706603ab43da37c4a360ae32); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2b66f2da706603ab43da37c4a360ae32)): ?>
<?php $component = $__componentOriginal2b66f2da706603ab43da37c4a360ae32; ?>
<?php unset($__componentOriginal2b66f2da706603ab43da37c4a360ae32); ?>
<?php endif; ?>

                            <?php if (isset($component)) { $__componentOriginal2b66f2da706603ab43da37c4a360ae32 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2b66f2da706603ab43da37c4a360ae32 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.th','data' => ['class' => '!px-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.th'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!px-2']); ?>
                                <?php echo app('translator')->get('admin::app.leads.view.quotes.grand-total'); ?>
                                <span class="currency-code">(<?php echo e(core()->currencySymbol(config('app.currency'))); ?>)</span>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2b66f2da706603ab43da37c4a360ae32)): ?>
<?php $attributes = $__attributesOriginal2b66f2da706603ab43da37c4a360ae32; ?>
<?php unset($__attributesOriginal2b66f2da706603ab43da37c4a360ae32); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2b66f2da706603ab43da37c4a360ae32)): ?>
<?php $component = $__componentOriginal2b66f2da706603ab43da37c4a360ae32; ?>
<?php unset($__componentOriginal2b66f2da706603ab43da37c4a360ae32); ?>
<?php endif; ?>

                            <?php if (isset($component)) { $__componentOriginal2b66f2da706603ab43da37c4a360ae32 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2b66f2da706603ab43da37c4a360ae32 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.th','data' => ['class' => 'actions']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.th'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'actions']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2b66f2da706603ab43da37c4a360ae32)): ?>
<?php $attributes = $__attributesOriginal2b66f2da706603ab43da37c4a360ae32; ?>
<?php unset($__attributesOriginal2b66f2da706603ab43da37c4a360ae32); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2b66f2da706603ab43da37c4a360ae32)): ?>
<?php $component = $__componentOriginal2b66f2da706603ab43da37c4a360ae32; ?>
<?php unset($__componentOriginal2b66f2da706603ab43da37c4a360ae32); ?>
<?php endif; ?>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal95a122c91c33f6d66a15a82d7ca67172)): ?>
<?php $attributes = $__attributesOriginal95a122c91c33f6d66a15a82d7ca67172; ?>
<?php unset($__attributesOriginal95a122c91c33f6d66a15a82d7ca67172); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal95a122c91c33f6d66a15a82d7ca67172)): ?>
<?php $component = $__componentOriginal95a122c91c33f6d66a15a82d7ca67172; ?>
<?php unset($__componentOriginal95a122c91c33f6d66a15a82d7ca67172); ?>
<?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8ee89c0b398bd7314c2e7815b044fc82)): ?>
<?php $attributes = $__attributesOriginal8ee89c0b398bd7314c2e7815b044fc82; ?>
<?php unset($__attributesOriginal8ee89c0b398bd7314c2e7815b044fc82); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8ee89c0b398bd7314c2e7815b044fc82)): ?>
<?php $component = $__componentOriginal8ee89c0b398bd7314c2e7815b044fc82; ?>
<?php unset($__componentOriginal8ee89c0b398bd7314c2e7815b044fc82); ?>
<?php endif; ?>

                    <?php echo view_render_event('admin.leads.view.quotes.table.table_head.after', ['lead' => $lead]); ?>


                    <?php echo view_render_event('admin.leads.view.quotes.table.table_body.before', ['lead' => $lead]); ?>


                    <?php if (isset($component)) { $__componentOriginalde01fbd71b7145d08385ea395943e136 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalde01fbd71b7145d08385ea395943e136 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.tbody.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.tbody'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                        <?php if (isset($component)) { $__componentOriginal1156c87c0af7e56868c6b86a8597c6cc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1156c87c0af7e56868c6b86a8597c6cc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.tbody.tr','data' => ['vFor' => 'quote in quotes','class' => 'border-b']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.tbody.tr'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['v-for' => 'quote in quotes','class' => 'border-b']); ?>
                            <?php if (isset($component)) { $__componentOriginal7bda9cdc3924faf4607e2df004a89fbc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.td','data' => ['class' => 'text-wrap !px-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.td'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'text-wrap !px-2']); ?>{{ quote.subject }} <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc)): ?>
<?php $attributes = $__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc; ?>
<?php unset($__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7bda9cdc3924faf4607e2df004a89fbc)): ?>
<?php $component = $__componentOriginal7bda9cdc3924faf4607e2df004a89fbc; ?>
<?php unset($__componentOriginal7bda9cdc3924faf4607e2df004a89fbc); ?>
<?php endif; ?>

                            <?php if (isset($component)) { $__componentOriginal7bda9cdc3924faf4607e2df004a89fbc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.td','data' => ['class' => '!px-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.td'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!px-2']); ?>{{ quote.expired_at }} <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc)): ?>
<?php $attributes = $__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc; ?>
<?php unset($__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7bda9cdc3924faf4607e2df004a89fbc)): ?>
<?php $component = $__componentOriginal7bda9cdc3924faf4607e2df004a89fbc; ?>
<?php unset($__componentOriginal7bda9cdc3924faf4607e2df004a89fbc); ?>
<?php endif; ?>

                            <?php if (isset($component)) { $__componentOriginal7bda9cdc3924faf4607e2df004a89fbc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.td','data' => ['class' => '!px-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.td'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!px-2']); ?>{{ quote.sub_total }} <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc)): ?>
<?php $attributes = $__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc; ?>
<?php unset($__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7bda9cdc3924faf4607e2df004a89fbc)): ?>
<?php $component = $__componentOriginal7bda9cdc3924faf4607e2df004a89fbc; ?>
<?php unset($__componentOriginal7bda9cdc3924faf4607e2df004a89fbc); ?>
<?php endif; ?>

                            <?php if (isset($component)) { $__componentOriginal7bda9cdc3924faf4607e2df004a89fbc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.td','data' => ['class' => '!px-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.td'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!px-2']); ?>{{ quote.discount_amount }} <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc)): ?>
<?php $attributes = $__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc; ?>
<?php unset($__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7bda9cdc3924faf4607e2df004a89fbc)): ?>
<?php $component = $__componentOriginal7bda9cdc3924faf4607e2df004a89fbc; ?>
<?php unset($__componentOriginal7bda9cdc3924faf4607e2df004a89fbc); ?>
<?php endif; ?>

                            <?php if (isset($component)) { $__componentOriginal7bda9cdc3924faf4607e2df004a89fbc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.td','data' => ['class' => '!px-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.td'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!px-2']); ?>{{ quote.tax_amount }} <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc)): ?>
<?php $attributes = $__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc; ?>
<?php unset($__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7bda9cdc3924faf4607e2df004a89fbc)): ?>
<?php $component = $__componentOriginal7bda9cdc3924faf4607e2df004a89fbc; ?>
<?php unset($__componentOriginal7bda9cdc3924faf4607e2df004a89fbc); ?>
<?php endif; ?>

                            <?php if (isset($component)) { $__componentOriginal7bda9cdc3924faf4607e2df004a89fbc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.td','data' => ['class' => '!px-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.td'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!px-2']); ?>{{ quote.adjustment_amount }} <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc)): ?>
<?php $attributes = $__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc; ?>
<?php unset($__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7bda9cdc3924faf4607e2df004a89fbc)): ?>
<?php $component = $__componentOriginal7bda9cdc3924faf4607e2df004a89fbc; ?>
<?php unset($__componentOriginal7bda9cdc3924faf4607e2df004a89fbc); ?>
<?php endif; ?>

                            <?php if (isset($component)) { $__componentOriginal7bda9cdc3924faf4607e2df004a89fbc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.td','data' => ['class' => '!px-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.td'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!px-2']); ?>{{ quote.grand_total }} <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc)): ?>
<?php $attributes = $__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc; ?>
<?php unset($__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7bda9cdc3924faf4607e2df004a89fbc)): ?>
<?php $component = $__componentOriginal7bda9cdc3924faf4607e2df004a89fbc; ?>
<?php unset($__componentOriginal7bda9cdc3924faf4607e2df004a89fbc); ?>
<?php endif; ?>

                            <?php if (isset($component)) { $__componentOriginal7bda9cdc3924faf4607e2df004a89fbc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.td','data' => ['class' => '!px-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.td'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!px-2']); ?>
                                <?php echo view_render_event('admin.leads.view.quotes.table.table_body.dropdown.before', ['lead' => $lead]); ?>


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
                                        <i class="icon-more text-2xl"></i>
                                     <?php $__env->endSlot(); ?>

                                     <?php $__env->slot('menu', null, ['class' => '!min-w-40']); ?> 
                                        <?php if(bouncer()->hasPermission('quotes.edit')): ?>
                                            <?php echo view_render_event('admin.leads.view.quotes.table.table_body.dropdown.item.before', ['lead' => $lead]); ?>


                                            <?php if (isset($component)) { $__componentOriginal0223c8534d6a243be608c3a65289c4d0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0223c8534d6a243be608c3a65289c4d0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.dropdown.menu.item','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::dropdown.menu.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                                <a :href="'<?php echo e(route('admin.quotes.edit')); ?>/' + quote.id + '?from=lead&lead_id=<?php echo e($lead->id); ?>'">
                                                    <div class="flex items-center gap-2">
                                                        <span class="icon-edit text-2xl"></span>

                                                        <?php echo app('translator')->get('admin::app.leads.view.quotes.edit'); ?>
                                                    </div>
                                                </a>
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

                                            <?php echo view_render_event('admin.leads.view.quotes.table.table_body.dropdown.item.after', ['lead' => $lead]); ?>

                                        <?php endif; ?>

                                        <?php if(bouncer()->hasPermission('quotes.print')): ?>
                                            <?php echo view_render_event('admin.leads.view.quotes.table.table_body.dropdown.item.before', ['lead' => $lead]); ?>


                                            <?php if (isset($component)) { $__componentOriginal0223c8534d6a243be608c3a65289c4d0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0223c8534d6a243be608c3a65289c4d0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.dropdown.menu.item','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::dropdown.menu.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                                <a :href="'<?php echo e(route('admin.quotes.print')); ?>/' + quote.id" target="_blank">
                                                    <div class="flex items-center gap-2">
                                                        <span class="icon-download text-2xl"></span>

                                                        <?php echo app('translator')->get('admin::app.leads.view.quotes.download'); ?>
                                                    </div>
                                                </a>

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

                                            <?php echo view_render_event('admin.leads.view.quotes.table.table_body.dropdown.item.after', ['lead' => $lead]); ?>

                                        <?php endif; ?>

                                        <?php if(bouncer()->hasPermission('quotes.delete')): ?>
                                            <?php echo view_render_event('admin.leads.view.quotes.table.table_body.dropdown.item.before', ['lead' => $lead]); ?>


                                            <?php if (isset($component)) { $__componentOriginal0223c8534d6a243be608c3a65289c4d0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0223c8534d6a243be608c3a65289c4d0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.dropdown.menu.item','data' => ['@click' => 'removeQuote(quote)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::dropdown.menu.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['@click' => 'removeQuote(quote)']); ?>
                                                <div class="flex items-center gap-2">
                                                    <span class="icon-delete text-2xl"></span>

                                                    <?php echo app('translator')->get('admin::app.leads.view.quotes.delete'); ?>
                                                </div>
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

                                            <?php echo view_render_event('admin.leads.view.quotes.table.table_body.dropdown.item.after', ['lead' => $lead]); ?>

                                        <?php endif; ?>
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

                                <?php echo view_render_event('admin.leads.view.quotes.table.table_body.dropdown.after', ['lead' => $lead]); ?>

                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc)): ?>
<?php $attributes = $__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc; ?>
<?php unset($__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7bda9cdc3924faf4607e2df004a89fbc)): ?>
<?php $component = $__componentOriginal7bda9cdc3924faf4607e2df004a89fbc; ?>
<?php unset($__componentOriginal7bda9cdc3924faf4607e2df004a89fbc); ?>
<?php endif; ?>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1156c87c0af7e56868c6b86a8597c6cc)): ?>
<?php $attributes = $__attributesOriginal1156c87c0af7e56868c6b86a8597c6cc; ?>
<?php unset($__attributesOriginal1156c87c0af7e56868c6b86a8597c6cc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1156c87c0af7e56868c6b86a8597c6cc)): ?>
<?php $component = $__componentOriginal1156c87c0af7e56868c6b86a8597c6cc; ?>
<?php unset($__componentOriginal1156c87c0af7e56868c6b86a8597c6cc); ?>
<?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalde01fbd71b7145d08385ea395943e136)): ?>
<?php $attributes = $__attributesOriginalde01fbd71b7145d08385ea395943e136; ?>
<?php unset($__attributesOriginalde01fbd71b7145d08385ea395943e136); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalde01fbd71b7145d08385ea395943e136)): ?>
<?php $component = $__componentOriginalde01fbd71b7145d08385ea395943e136; ?>
<?php unset($__componentOriginalde01fbd71b7145d08385ea395943e136); ?>
<?php endif; ?>

                    <?php echo view_render_event('admin.leads.view.quotes.table.table_body.after', ['lead' => $lead]); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala9dad9f471f1e8ff345be80579eb8136)): ?>
<?php $attributes = $__attributesOriginala9dad9f471f1e8ff345be80579eb8136; ?>
<?php unset($__attributesOriginala9dad9f471f1e8ff345be80579eb8136); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala9dad9f471f1e8ff345be80579eb8136)): ?>
<?php $component = $__componentOriginala9dad9f471f1e8ff345be80579eb8136; ?>
<?php unset($__componentOriginala9dad9f471f1e8ff345be80579eb8136); ?>
<?php endif; ?>

                <?php echo view_render_event('admin.leads.view.quotes.table.after', ['lead' => $lead]); ?>


                <div v-else>
                    <div class="grid justify-center justify-items-center gap-3.5 py-12">
                        <img
                            class="dark:mix-blend-exclusion dark:invert"
                            src="<?php echo e(vite()->asset('images/empty-placeholders/quotes.svg')); ?>"
                        >

                        <div class="flex flex-col items-center gap-2">
                            <p class="text-xl font-semibold dark:text-white">
                                <?php echo app('translator')->get('admin::app.leads.view.quotes.empty-title'); ?>
                            </p>

                            <p class="text-gray-400">
                                <?php echo app('translator')->get('admin::app.leads.view.quotes.empty-info'); ?>
                            </p>
                        </div>

                        <a
                            class="secondary-button"
                            href="<?php echo e(route('admin.quotes.create', $lead->id)); ?>?from=lead"
                        >
                            <?php echo app('translator')->get('admin::app.leads.view.quotes.add-btn'); ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </script>


    <script type="module">
        app.component('v-lead-quotes', {
            template: '#v-lead-quotes-template',

            props: ['data'],

            data: function () {
                return {
                    quotes: <?php echo json_encode($lead->quotes()->with(['person', 'user'])->get(), 512) ?>
                }
            },

            methods: {
                removeQuote(quote) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            this.isLoading = true;

                            this.$axios.delete("<?php echo e(route('admin.leads.quotes.delete', $lead->id)); ?>/" + quote.id)
                                .then(response => {
                                    this.isLoading = false;

                                    const index = this.quotes.indexOf(quote);

                                    if (index !== -1) {
                                        this.quotes.splice(index, 1);
                                    }

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                })
                                .catch(error => {
                                    this.isLoading = false;

                                    this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                                });
                        }
                    });
                }

            },
        });
    </script>
<?php $__env->stopPush(); endif; ?>
<?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/leads/view/quotes.blade.php ENDPATH**/ ?>