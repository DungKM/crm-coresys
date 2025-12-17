<?php echo view_render_event('admin.leads.view.products.before', ['lead' => $lead]); ?>


<v-lead-products></v-lead-products>

<?php echo view_render_event('admin.leads.view.products.after', ['lead' => $lead]); ?>


<?php if (! $__env->hasRenderedOnce('520049ec-8696-4231-9464-9a7418996a45')): $__env->markAsRenderedOnce('520049ec-8696-4231-9464-9a7418996a45');
$__env->startPush('scripts'); ?>
    <script
        type="text/x-template"
        id="v-lead-products-template"
    >
        <div v-if="products.length" class="flex flex-col gap-4 p-3">
            <?php echo view_render_event('admin.leads.view.products.table.before', ['lead' => $lead]); ?>


            <!-- Table -->
            <div class="block w-full overflow-x-auto">
                <?php if (isset($component)) { $__componentOriginala9dad9f471f1e8ff345be80579eb8136 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala9dad9f471f1e8ff345be80579eb8136 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                    <?php echo view_render_event('admin.leads.view.products.table.table_head.before', ['lead' => $lead]); ?>


                    <!-- Table Head -->
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.th','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.th'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                <?php echo app('translator')->get('admin::app.leads.view.products.product-name'); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.th','data' => ['class' => 'ltr:text-left rtl:text-right']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.th'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'ltr:text-left rtl:text-right']); ?>
                                <?php echo app('translator')->get('admin::app.leads.view.products.quantity'); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.th','data' => ['class' => 'ltr:text-left rtl:text-right']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.th'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'ltr:text-left rtl:text-right']); ?>
                                <?php echo app('translator')->get('admin::app.leads.view.products.price'); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.th','data' => ['class' => 'ltr:text-left rtl:text-right']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.th'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'ltr:text-left rtl:text-right']); ?>
                                <?php echo app('translator')->get('admin::app.leads.view.products.amount'); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.th','data' => ['class' => 'ltr:text-right rtl:text-left']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.th'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'ltr:text-right rtl:text-left']); ?>
                                <?php echo app('translator')->get('admin::app.leads.view.products.action'); ?>
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

                    <?php echo view_render_event('admin.leads.view.products.table.table_head.after', ['lead' => $lead]); ?>


                    <?php echo view_render_event('admin.leads.view.products.table.table_body.before', ['lead' => $lead]); ?>


                    <!-- Table Body -->
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
                        <!-- Product Item Vue Component -->
                        <v-product-item
                            v-for='(product, index) in products'
                            :product="product"
                            :key="index"
                            :index="index"
                            @onRemoveProduct="removeProduct($event)"
                        ></v-product-item>
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
                    <?php echo view_render_event('admin.leads.view.products.table.table_body.after', ['lead' => $lead]); ?>

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
            </div>
            
            <?php echo view_render_event('admin.leads.view.products.table.after', ['lead' => $lead]); ?>


            <?php echo view_render_event('admin.leads.view.products.table.add_more.before', ['lead' => $lead]); ?>


            <!-- Add New Product Item -->
            <div>
                <button
                    type="button"
                    class="flex max-w-max items-center gap-2 text-brandColor"
                    @click="addProduct"
                >
                    <i class="icon-add text-md !text-brandColor"></i>

                    <?php echo app('translator')->get('admin::app.leads.view.products.add-more'); ?>
                </button>
            </div>

            <?php echo view_render_event('admin.leads.view.products.table.add_more.after', ['lead' => $lead]); ?>

        </div>

        <div v-else>
            <div class="grid justify-center justify-items-center gap-3.5 py-12">
                <img
                    class="dark:mix-blend-exclusion dark:invert"
                    src="<?php echo e(vite()->asset('images/empty-placeholders/products.svg')); ?>"
                >

                <div class="flex flex-col items-center gap-2">
                    <p class="text-xl font-semibold dark:text-white">
                        <?php echo app('translator')->get('admin::app.leads.view.products.empty-title'); ?>
                    </p>

                    <p class="text-gray-400 dark:text-gray-400">
                        <?php echo app('translator')->get('admin::app.leads.view.products.empty-info'); ?>
                    </p>
                </div>

                <div
                    class="secondary-button"
                    @click="addProduct"
                >
                     <?php echo app('translator')->get('admin::app.leads.view.products.add-product'); ?>
                </div>
            </div>
        </div>
    </script>

    <script
        type="text/x-template"
        id="v-product-item-template"
    >
        <?php if (isset($component)) { $__componentOriginal1156c87c0af7e56868c6b86a8597c6cc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1156c87c0af7e56868c6b86a8597c6cc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.tbody.tr','data' => ['class' => 'border-b border-gray-200 align-top dark:border-gray-800']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.tbody.tr'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'border-b border-gray-200 align-top dark:border-gray-800']); ?>
            <!-- Product Name -->
            <?php if (isset($component)) { $__componentOriginal7bda9cdc3924faf4607e2df004a89fbc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.td','data' => ['class' => '!px-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.td'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!px-4']); ?>
                <v-form v-slot="{ errors }" @keydown.enter.prevent>
                    <?php if (isset($component)) { $__componentOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.index','data' => ['class' => '!mb-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!mb-0']); ?>
                        <?php if (isset($component)) { $__componentOriginal47ddd4958c1891eaeb228b5253e37cdf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal47ddd4958c1891eaeb228b5253e37cdf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.lookup.index','data' => [':key' => 'product.product_id',':src' => 'src','name' => 'name',':params' => 'params','placeholder' => trans('admin::app.leads.view.products.product-name'),'@onSelected' => '(product) => addProduct(product)',':value' => '{ id: product.product_id, name: product.name }']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::lookup'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([':key' => 'product.product_id',':src' => 'src','name' => 'name',':params' => 'params','placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('admin::app.leads.view.products.product-name')),'@on-selected' => '(product) => addProduct(product)',':value' => '{ id: product.product_id, name: product.name }']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal47ddd4958c1891eaeb228b5253e37cdf)): ?>
<?php $attributes = $__attributesOriginal47ddd4958c1891eaeb228b5253e37cdf; ?>
<?php unset($__attributesOriginal47ddd4958c1891eaeb228b5253e37cdf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal47ddd4958c1891eaeb228b5253e37cdf)): ?>
<?php $component = $__componentOriginal47ddd4958c1891eaeb228b5253e37cdf; ?>
<?php unset($__componentOriginal47ddd4958c1891eaeb228b5253e37cdf); ?>
<?php endif; ?>

                        <?php if (isset($component)) { $__componentOriginal53af403f6b2179a3039d488b8ab2a267 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53af403f6b2179a3039d488b8ab2a267 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.control','data' => ['type' => 'hidden','name' => 'product_id','vModel' => 'product.product_id','rules' => 'required','label' => trans('admin::app.leads.view.products.product-name'),'placeholder' => trans('admin::app.leads.view.products.product-name'),':url' => 'url(product)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'hidden','name' => 'product_id','v-model' => 'product.product_id','rules' => 'required','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('admin::app.leads.view.products.product-name')),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('admin::app.leads.view.products.product-name')),':url' => 'url(product)']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.error','data' => [':name' => '`${inputName}[product_id]`']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([':name' => '`${inputName}[product_id]`']); ?>
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
                </v-form>
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

            <!-- Product Quantity -->
            <?php if (isset($component)) { $__componentOriginal7bda9cdc3924faf4607e2df004a89fbc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.td','data' => ['class' => '!px-4 ltr:text-right rtl:text-left']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.td'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!px-4 ltr:text-right rtl:text-left']); ?>
                <v-form v-slot="{ errors }" @keydown.enter.prevent>
                    <?php if (isset($component)) { $__componentOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.index','data' => ['class' => '!mb-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!mb-0']); ?>
                        <?php if (isset($component)) { $__componentOriginal53af403f6b2179a3039d488b8ab2a267 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53af403f6b2179a3039d488b8ab2a267 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.control','data' => ['type' => 'inline',':name' => '\'quantity\'',':value' => 'product.quantity','rules' => 'required|decimal:4','label' => trans('admin::app.leads.view.products.quantity'),'placeholder' => trans('admin::app.leads.view.products.quantity'),'@onChange' => '(event) => product.quantity = event.value',':url' => 'url(product)',':params' => '{product_id: product.product_id}','position' => 'left',':errors' => 'errors']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'inline',':name' => '\'quantity\'',':value' => 'product.quantity','rules' => 'required|decimal:4','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('admin::app.leads.view.products.quantity')),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('admin::app.leads.view.products.quantity')),'@on-change' => '(event) => product.quantity = event.value',':url' => 'url(product)',':params' => '{product_id: product.product_id}','position' => 'left',':errors' => 'errors']); ?>
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
                </v-form>
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

            <!-- Price -->
            <?php if (isset($component)) { $__componentOriginal7bda9cdc3924faf4607e2df004a89fbc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.td','data' => ['class' => '!px-4 ltr:text-right rtl:text-left']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.td'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!px-4 ltr:text-right rtl:text-left']); ?>
                <v-form v-slot="{ errors }" @keydown.enter.prevent>
                    <?php if (isset($component)) { $__componentOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.index','data' => ['class' => '!mb-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!mb-0']); ?>
                        <?php if (isset($component)) { $__componentOriginal53af403f6b2179a3039d488b8ab2a267 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53af403f6b2179a3039d488b8ab2a267 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.control','data' => ['type' => 'inline',':name' => '\'price\'',':value' => 'product.price','rules' => 'required|decimal:4','label' => trans('admin::app.leads.view.products.price'),'placeholder' => trans('admin::app.leads.view.products.price'),'@onChange' => '(event) => product.price = event.value',':url' => 'url(product)',':params' => '{product_id: product.product_id}','position' => 'left',':valueLabel' => '$admin.formatPrice(product.price)',':errors' => 'errors']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'inline',':name' => '\'price\'',':value' => 'product.price','rules' => 'required|decimal:4','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('admin::app.leads.view.products.price')),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('admin::app.leads.view.products.price')),'@on-change' => '(event) => product.price = event.value',':url' => 'url(product)',':params' => '{product_id: product.product_id}','position' => 'left',':value-label' => '$admin.formatPrice(product.price)',':errors' => 'errors']); ?>
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
                </v-form>
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

            <!-- Total -->
            <?php if (isset($component)) { $__componentOriginal7bda9cdc3924faf4607e2df004a89fbc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.td','data' => ['class' => '!px-4 ltr:text-right rtl:text-left']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.td'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!px-4 ltr:text-right rtl:text-left']); ?>
                <v-form v-slot="{ errors }" @keydown.enter.prevent>
                    <?php if (isset($component)) { $__componentOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.index','data' => ['class' => '!mb-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!mb-0']); ?>
                        <?php if (isset($component)) { $__componentOriginal53af403f6b2179a3039d488b8ab2a267 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53af403f6b2179a3039d488b8ab2a267 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.control','data' => ['type' => 'inline',':name' => '\'amount\'',':value' => 'product.price * product.quantity','rules' => 'required|decimal:4','label' => trans('admin::app.leads.view.products.total'),'placeholder' => trans('admin::app.leads.view.products.total'),'allowEdit' => false,':url' => 'url(product)','position' => 'left',':valueLabel' => '$admin.formatPrice(product.price * product.quantity)',':errors' => 'errors']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'inline',':name' => '\'amount\'',':value' => 'product.price * product.quantity','rules' => 'required|decimal:4','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('admin::app.leads.view.products.total')),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('admin::app.leads.view.products.total')),'allowEdit' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),':url' => 'url(product)','position' => 'left',':value-label' => '$admin.formatPrice(product.price * product.quantity)',':errors' => 'errors']); ?>
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
                </v-form>
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

            <!-- Action -->
            <?php if (isset($component)) { $__componentOriginal7bda9cdc3924faf4607e2df004a89fbc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7bda9cdc3924faf4607e2df004a89fbc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.table.td','data' => ['class' => 'ltr:text-right rtl:text-left']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::table.td'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'ltr:text-right rtl:text-left']); ?>
                <template v-if="product.is_new">
                    <?php if (isset($component)) { $__componentOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.index','data' => ['class' => '!mb-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!mb-0']); ?>
                        <div class="flex items-center justify-center gap-4">
                            <i
                                @click="attachProduct(product)"
                                class="icon-enter cursor-pointer text-2xl text-black"
                            ></i>

                            <i
                                @click="removeProduct"
                                class="icon-cross-large cursor-pointer text-2xl text-black"
                            ></i>
                        </div>
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

                <template v-else>
                    <?php if (isset($component)) { $__componentOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.index','data' => ['class' => '!mb-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!mb-0']); ?>
                        <i
                            @click="removeProduct"
                            class="icon-delete cursor-pointer text-2xl"
                        ></i>
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
    </script>

    <script type="module">
        app.component('v-lead-products', {
            template: '#v-lead-products-template',

            props: ['data'],

            data: function () {
                return {
                    products: <?php echo json_encode($lead->products, 15, 512) ?>,
                }
            },

            methods: {
                addProduct() {
                    this.products.push({
                        is_new: true,
                        id: null,
                        product_id: null,
                        name: '',
                        quantity: 0,
                        price: 0,
                        amount: null,
                    })
                },

                removeProduct (product) {
                    const index = this.products.indexOf(product);
                    this.products.splice(index, 1);
                },
            },
        });

        app.component('v-product-item', {
            template: '#v-product-item-template',

            props: ['index', 'product'],

            data() {
                return {
                    products: [],
                }
            },

            computed: {
                inputName() {
                    if (this.product.id) {
                        return "products[" + this.product.id + "]";
                    }

                    return "products[product_" + this.index + "]";
                },

                src() {
                    return '<?php echo e(route('admin.products.search')); ?>';
                },

                params() {
                    return {
                        params: {
                            query: this.product.name
                        },
                    };
                },
            },

            methods: {
                /**
                 * Add the product.
                 *
                 * @param {Object} result
                 *
                 * @return {void}
                 */
                addProduct(result) {
                    this.product.product_id = result.id;

                    this.product.name = result.name;

                    this.product.price = result.price;

                    this.product.quantity = result.quantity ?? 0;
                },

                /**
                 * Attach Product.
                 *
                 * @return {void}
                 */
                attachProduct(product) {
                    this.$axios.post('<?php echo e(route('admin.leads.product.add', $lead->id)); ?>', {
                        _method: 'PUT',
                        ...product,
                    })
                        .then(response => {
                            this.product.is_new = false;

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                        })
                        .catch(error => {});
                },

                /**
                 * Remove the product.
                 *
                 * @return {void}
                 */
                removeProduct() {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            this.$axios.post('<?php echo e(route('admin.leads.product.remove', $lead->id)); ?>', {
                                _method: 'DELETE',
                                product_id: this.product.product_id,
                            })
                                .then(response => {
                                    this.$emit('onRemoveProduct', this.product);

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                })
                                .catch(error => {});
                        },
                    });
                },

                /**
                 * Get the product URL.
                 *
                 * @param {Object} product
                 *
                 * @return {String}
                 */
                url(product) {
                    if (product.is_new) {
                        return;
                    }

                    return '<?php echo e(route('admin.leads.product.add', $lead->id)); ?>';
                }
            }
        });
    </script>
<?php $__env->stopPush(); endif; ?>
<?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/leads/view/products.blade.php ENDPATH**/ ?>