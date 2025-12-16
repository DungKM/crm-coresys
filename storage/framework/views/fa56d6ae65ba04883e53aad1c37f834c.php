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
        <?php echo app('translator')->get('admin::app.leads.view.title', ['title' => $lead->title]); ?>
     <?php $__env->endSlot(); ?>

    <!-- Content -->
    <div class="relative flex gap-4 max-lg:flex-wrap">
        <!-- Left Panel -->
        <?php echo view_render_event('admin.leads.view.left.before', ['lead' => $lead]); ?>


        <div
            class="max-lg:min-w-full max-lg:max-w-full [&>div:last-child]:border-b-0 lg:sticky lg:top-[73px] flex min-w-[394px] max-w-[394px] flex-col self-start rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <!-- Lead Information -->
            <div class="flex w-full flex-col gap-2 border-b border-gray-200 p-4 dark:border-gray-800">
                <!-- Breadcrumb's -->
                <div class="flex items-center justify-between">
                    <?php if (isset($component)) { $__componentOriginal477735b45b070062c5df1d72c43d48f5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal477735b45b070062c5df1d72c43d48f5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.breadcrumbs.index','data' => ['name' => 'leads.view','entity' => $lead]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::breadcrumbs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'leads.view','entity' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($lead)]); ?>
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
                </div>

                <div class="mb-2">
                    <?php if(($days = $lead->rotten_days) > 0): ?>
                        <?php
                            $lead->tags->prepend([
                                'name' =>
                                    '<span class="icon-rotten text-base"></span>' .
                                    trans('admin::app.leads.view.rotten-days', ['days' => $days]),
                                'color' => '#FEE2E2',
                            ]);
                        ?>
                    <?php endif; ?>

                    <?php echo view_render_event('admin.leads.view.tags.before', ['lead' => $lead]); ?>


                    <!-- Tags -->
                    <?php if (isset($component)) { $__componentOriginalf851be63606bb172aaceed482091e22c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf851be63606bb172aaceed482091e22c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.tags.index','data' => ['attachEndpoint' => route('admin.leads.tags.attach', $lead->id),'detachEndpoint' => route('admin.leads.tags.detach', $lead->id),'addedTags' => $lead->tags]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::tags'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['attach-endpoint' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.leads.tags.attach', $lead->id)),'detach-endpoint' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.leads.tags.detach', $lead->id)),'added-tags' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($lead->tags)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf851be63606bb172aaceed482091e22c)): ?>
<?php $attributes = $__attributesOriginalf851be63606bb172aaceed482091e22c; ?>
<?php unset($__attributesOriginalf851be63606bb172aaceed482091e22c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf851be63606bb172aaceed482091e22c)): ?>
<?php $component = $__componentOriginalf851be63606bb172aaceed482091e22c; ?>
<?php unset($__componentOriginalf851be63606bb172aaceed482091e22c); ?>
<?php endif; ?>

                    <?php echo view_render_event('admin.leads.view.tags.after', ['lead' => $lead]); ?>

                </div>


                <?php echo view_render_event('admin.leads.view.title.before', ['lead' => $lead]); ?>


                <!-- Title -->
                <h1 class="text-lg font-bold dark:text-white">
                    <?php echo e($lead->title); ?>

                </h1>

                <?php echo view_render_event('admin.leads.view.title.after', ['lead' => $lead]); ?>


                <!-- Activity Actions -->
                <div class="flex flex-wrap gap-2">
                    <?php echo view_render_event('admin.leads.view.actions.before', ['lead' => $lead]); ?>


                    <?php if(bouncer()->hasPermission('mail.compose')): ?>
                        <!-- Mail Activity Action -->
                        <?php if (isset($component)) { $__componentOriginal6e7e62b7e8fed3be26d9a026b4495e9a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6e7e62b7e8fed3be26d9a026b4495e9a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.activities.actions.mail','data' => ['entity' => $lead,'entityControlName' => 'lead_id']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::activities.actions.mail'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['entity' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($lead),'entity-control-name' => 'lead_id']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6e7e62b7e8fed3be26d9a026b4495e9a)): ?>
<?php $attributes = $__attributesOriginal6e7e62b7e8fed3be26d9a026b4495e9a; ?>
<?php unset($__attributesOriginal6e7e62b7e8fed3be26d9a026b4495e9a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6e7e62b7e8fed3be26d9a026b4495e9a)): ?>
<?php $component = $__componentOriginal6e7e62b7e8fed3be26d9a026b4495e9a; ?>
<?php unset($__componentOriginal6e7e62b7e8fed3be26d9a026b4495e9a); ?>
<?php endif; ?>
                    <?php endif; ?>

                    <?php if(bouncer()->hasPermission('activities.create')): ?>
                        <!-- File Activity Action -->
                        <?php if (isset($component)) { $__componentOriginale7ed32b182ae3e00a04d1065a8a2ff86 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale7ed32b182ae3e00a04d1065a8a2ff86 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.activities.actions.file','data' => ['entity' => $lead,'entityControlName' => 'lead_id']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::activities.actions.file'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['entity' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($lead),'entity-control-name' => 'lead_id']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale7ed32b182ae3e00a04d1065a8a2ff86)): ?>
<?php $attributes = $__attributesOriginale7ed32b182ae3e00a04d1065a8a2ff86; ?>
<?php unset($__attributesOriginale7ed32b182ae3e00a04d1065a8a2ff86); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale7ed32b182ae3e00a04d1065a8a2ff86)): ?>
<?php $component = $__componentOriginale7ed32b182ae3e00a04d1065a8a2ff86; ?>
<?php unset($__componentOriginale7ed32b182ae3e00a04d1065a8a2ff86); ?>
<?php endif; ?>

                        <!-- Note Activity Action -->
                        <?php if (isset($component)) { $__componentOriginal8bdd483ce4bdd8186f2e725c84d6fd85 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8bdd483ce4bdd8186f2e725c84d6fd85 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.activities.actions.note','data' => ['entity' => $lead,'entityControlName' => 'lead_id']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::activities.actions.note'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['entity' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($lead),'entity-control-name' => 'lead_id']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8bdd483ce4bdd8186f2e725c84d6fd85)): ?>
<?php $attributes = $__attributesOriginal8bdd483ce4bdd8186f2e725c84d6fd85; ?>
<?php unset($__attributesOriginal8bdd483ce4bdd8186f2e725c84d6fd85); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8bdd483ce4bdd8186f2e725c84d6fd85)): ?>
<?php $component = $__componentOriginal8bdd483ce4bdd8186f2e725c84d6fd85; ?>
<?php unset($__componentOriginal8bdd483ce4bdd8186f2e725c84d6fd85); ?>
<?php endif; ?>

                        <!-- Activity Action -->
                        <?php if (isset($component)) { $__componentOriginalc39954a677175d0d994f44af8c16faaf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc39954a677175d0d994f44af8c16faaf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.activities.actions.activity','data' => ['entity' => $lead,'entityControlName' => 'lead_id']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::activities.actions.activity'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['entity' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($lead),'entity-control-name' => 'lead_id']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc39954a677175d0d994f44af8c16faaf)): ?>
<?php $attributes = $__attributesOriginalc39954a677175d0d994f44af8c16faaf; ?>
<?php unset($__attributesOriginalc39954a677175d0d994f44af8c16faaf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc39954a677175d0d994f44af8c16faaf)): ?>
<?php $component = $__componentOriginalc39954a677175d0d994f44af8c16faaf; ?>
<?php unset($__componentOriginalc39954a677175d0d994f44af8c16faaf); ?>
<?php endif; ?>
                    <?php endif; ?>

                    <!-- Chat Button -->
                    <a href="<?php echo e(route('admin.leads.chat.index', $lead->id)); ?>"
                        class="secondary-button flex items-center gap-1">
                        <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                        </svg>
                        <span>Chat</span>
                    </a>

                    <?php echo view_render_event('admin.leads.view.actions.after', ['lead' => $lead]); ?>

                </div>
            </div>

            <!-- Lead Attributes -->
            <?php echo $__env->make('admin::leads.view.attributes', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <!-- Contact Person -->
            <?php echo $__env->make('admin::leads.view.person', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>

        <?php echo view_render_event('admin.leads.view.left.after', ['lead' => $lead]); ?>


        <?php echo view_render_event('admin.leads.view.right.before', ['lead' => $lead]); ?>


        <!-- Right Panel -->
        <div class="flex w-full flex-col gap-4 rounded-lg">
            <?php echo $__env->make('admin::leads.view.stages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <?php echo view_render_event('admin.leads.view.activities.before', ['lead' => $lead]); ?>


            
            <?php if (isset($component)) { $__componentOriginalbd04a9c4fb9c6cfa5ad6054d2fc88173 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd04a9c4fb9c6cfa5ad6054d2fc88173 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.activities.index','data' => ['endpoint' => route('admin.leads.activities.index', $lead->id),'emailDetachEndpoint' => route('admin.leads.emails.detach', $lead->id),'activeType' => request()->query('from') ?? 'all','extraTypes' => [
                ['name' => 'whatsapp', 'label' => '💬 WhatsApp'],
                ['name' => 'description', 'label' => trans('admin::app.leads.view.tabs.description')],
                ['name' => 'products', 'label' => trans('admin::app.leads.view.tabs.products')],
                ['name' => 'quotes', 'label' => trans('admin::app.leads.view.tabs.quotes')],
            ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::activities'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['endpoint' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.leads.activities.index', $lead->id)),'email-detach-endpoint' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.leads.emails.detach', $lead->id)),'activeType' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->query('from') ?? 'all'),'extra-types' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                ['name' => 'whatsapp', 'label' => '💬 WhatsApp'],
                ['name' => 'description', 'label' => trans('admin::app.leads.view.tabs.description')],
                ['name' => 'products', 'label' => trans('admin::app.leads.view.tabs.products')],
                ['name' => 'quotes', 'label' => trans('admin::app.leads.view.tabs.quotes')],
            ])]); ?>
                 <?php $__env->slot('whatsapp', null, []); ?> 
                    <div class="bg-white dark:bg-gray-900 rounded-b-lg border-t border-gray-200 dark:border-gray-800">

                        
                        <div class="chat-history flex flex-col gap-3 p-4 h-[400px] overflow-y-auto bg-gray-50 dark:bg-gray-950"
                            id="chat-scroll-area">
                            <?php
                                // Lọc lấy các activity là whatsapp và sắp xếp cũ nhất lên đầu
                                $whatsappLogs = $lead->activities->where('type', 'whatsapp')->sortBy('created_at');
                                // Debug: Log số lượng tin nhắn
                                \Log::info('[DEBUG View] Total WhatsApp activities: ' . $whatsappLogs->count());
                                \Log::info(
                                    '[DEBUG View] Activities types: ' . $lead->activities->pluck('type')->toJson(),
                                );
                            ?>

                            <?php if($whatsappLogs->isEmpty()): ?>
                                <div class="text-center text-gray-400 italic mt-10">
                                    Chưa có tin nhắn nào.
                                    
                                    <div class="text-xs mt-2">
                                        (Tổng số activities: <?php echo e($lead->activities->count()); ?>)
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php $__currentLoopData = $whatsappLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        // Logic phân biệt tin nhắn ĐẾN và ĐI dựa vào tiêu đề chúng ta đã lưu trong Controller
                                        // 'Tin nhắn WhatsApp đến' -> Khách hàng (Bên trái)
                                        // 'Gửi WhatsApp (Thủ công)' -> Sale (Bên phải)
                                        // SỬA: Kiểm tra cả tiếng Việt và tiếng Anh để tránh lỗi encoding
                                        $isIncoming =
                                            str_contains($log->title, 'đến') ||
                                            str_contains(strtolower($log->title), 'incoming');
                                    ?>

                                    <div class="flex flex-col <?php echo e($isIncoming ? 'items-start' : 'items-end'); ?>">

                                        <span class="text-xs text-gray-500 dark:text-gray-400 mb-1 px-1">
                                            <?php echo e($isIncoming ? 'Khách hàng' : $log->user->name ?? 'Bạn'); ?>

                                        </span>

                                        <div
                                            class="max-w-[80%] rounded-lg px-4 py-2 text-sm shadow-sm 
                            <?php echo e($isIncoming
                                ? 'bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-tl-none'
                                : 'bg-blue-600 dark:bg-blue-700 rounded-tr-none'); ?>">
                                            <span class="<?php echo e($isIncoming ? 'text-gray-900 dark:text-gray-100' : 'text-white'); ?>"><?php echo e($log->comment); ?></span>
                                        </div>

                                        <span class="text-[10px] text-gray-400 dark:text-gray-500 mt-1 px-1">
                                            <?php echo e($log->created_at->format('H:i d/m/Y')); ?>

                                        </span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>

                        
                        <div class="p-4 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
                            <div id="whatsapp-status" class="mb-2 text-sm font-medium h-5"></div>

                            <form id="whatsapp-reply-form" class="flex flex-col gap-3">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="lead_id" value="<?php echo e($lead->id); ?>">

                                <div class="relative">
                                    <textarea
                                        class="w-full rounded-md border border-gray-300 pl-3 pr-12 py-3 text-sm text-gray-600 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 shadow-sm"
                                        id="whatsapp-message" name="message" rows="2" placeholder="Nhập tin nhắn..."
                                        onkeydown="if(event.keyCode==13 && !event.shiftKey) { event.preventDefault(); sendWhatsApp(); }"></textarea>

                                    <button type="button" onclick="sendWhatsApp()"
                                        class="absolute right-2 bottom-2 p-2 text-blue-600 hover:text-blue-800 transition-colors"
                                        title="Gửi tin nhắn (Enter)">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            class="w-6 h-6">
                                            <path
                                                d="M3.478 2.405a.75.75 0 00-.926.94l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.405z" />
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <script>
                            // Tự động cuộn xuống cuối đoạn chat khi tải trang
                            document.addEventListener("DOMContentLoaded", function() {
                                var chatArea = document.getElementById("chat-scroll-area");
                                if (chatArea) {
                                    chatArea.scrollTop = chatArea.scrollHeight;
                                }
                            });

                            function sendWhatsApp() {
                                let messageField = document.getElementById('whatsapp-message');
                                let message = messageField.value;
                                let leadId = <?php echo e($lead->id); ?>;
                                let csrf = document.querySelector('input[name="_token"]').value;
                                let statusDiv = document.getElementById('whatsapp-status');

                                if (!message.trim()) return;

                                // Hiệu ứng UX: Disable nút và hiện đang gửi
                                messageField.disabled = true;
                                statusDiv.innerHTML =
                                    '<span class="text-blue-500 flex items-center gap-1"><span class="animate-spin h-3 w-3 border-2 border-blue-500 border-t-transparent rounded-full"></span> Đang gửi...</span>';

                                fetch(`/admin/leads/${leadId}/whatsapp-reply`, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': csrf,
                                            'Accept': 'application/json'
                                        },
                                        body: JSON.stringify({
                                            message: message,
                                            phone_number: document.querySelector('select[name="phone_number"]')?.value || ''
                                        })
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        messageField.disabled = false;
                                        messageField.focus();

                                        if (data.success) {
                                            statusDiv.innerHTML = '';
                                            messageField.value = '';

                                            // Append tin nhắn mới vào chat luôn để ko cần reload (UX tốt hơn)
                                            let chatArea = document.getElementById("chat-scroll-area");
                                            let now = new Date();
                                            let timeString = now.getHours() + ":" + String(now.getMinutes()).padStart(2, '0') + " " + now
                                                .getDate() + "/" + (now.getMonth() + 1) + "/" + now.getFullYear();

                                            let newBubble = `
                            <div class="flex flex-col items-end">
                                <span class="text-xs text-gray-500 mb-1 px-1">Bạn (Vừa xong)</span>
                                <div class="max-w-[80%] rounded-lg px-4 py-2 text-sm shadow-sm bg-blue-600 rounded-tr-none">
                                    <span class="text-white">${message.replace(/\n/g, '<br>')}</span>
                                </div>
                                <span class="text-[10px] text-gray-400 mt-1 px-1">${timeString}</span>
                            </div>
                        `;

                                            chatArea.insertAdjacentHTML('beforeend', newBubble);
                                            chatArea.scrollTop = chatArea.scrollHeight;

                                            // Reload ngầm sau 2s để đồng bộ activity ID chuẩn từ server
                                            // setTimeout(() => location.reload(), 2000); 
                                        } else {
                                            statusDiv.innerHTML = '<span class="text-red-600">❌ ' + (data.message || 'Lỗi gửi tin') +
                                                '</span>';
                                        }
                                    })
                                    .catch(error => {
                                        messageField.disabled = false;
                                        console.error('Error:', error);
                                        statusDiv.innerHTML = '<span class="text-red-600">❌ Lỗi kết nối.</span>';
                                    });
                            }
                        </script>
                    </div>
                 <?php $__env->endSlot(); ?>

                 <?php $__env->slot('products', null, []); ?> 
                    <?php echo $__env->make('admin::leads.view.products', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                 <?php $__env->endSlot(); ?>

                 <?php $__env->slot('quotes', null, []); ?> 
                    <?php echo $__env->make('admin::leads.view.quotes', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                 <?php $__env->endSlot(); ?>

                 <?php $__env->slot('description', null, []); ?> 
                    <div class="p-4 dark:text-white">
                        <?php echo e($lead->description); ?>

                    </div>
                 <?php $__env->endSlot(); ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd04a9c4fb9c6cfa5ad6054d2fc88173)): ?>
<?php $attributes = $__attributesOriginalbd04a9c4fb9c6cfa5ad6054d2fc88173; ?>
<?php unset($__attributesOriginalbd04a9c4fb9c6cfa5ad6054d2fc88173); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd04a9c4fb9c6cfa5ad6054d2fc88173)): ?>
<?php $component = $__componentOriginalbd04a9c4fb9c6cfa5ad6054d2fc88173; ?>
<?php unset($__componentOriginalbd04a9c4fb9c6cfa5ad6054d2fc88173); ?>
<?php endif; ?>

            <?php echo view_render_event('admin.leads.view.activities.after', ['lead' => $lead]); ?>

        </div>

        <?php echo view_render_event('admin.leads.view.right.after', ['lead' => $lead]); ?>

    </div>

    <?php if (! $__env->hasRenderedOnce('a95499d6-e46a-4b8e-b7d1-4cad1f791f71')): $__env->markAsRenderedOnce('a95499d6-e46a-4b8e-b7d1-4cad1f791f71');
$__env->startPush('scripts'); ?>
        <script type="text/x-template" id="v-whatsapp-activity-action-template">
            <div>
                <slot name="body" :open="() => $refs.whatsappModal.open()"></slot>
    
                <?php if (isset($component)) { $__componentOriginal81b4d293d9113446bb908fc8aef5c8f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal81b4d293d9113446bb908fc8aef5c8f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.index','data' => ['vSlot' => '{ meta, errors, handleSubmit }','as' => 'div']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['v-slot' => '{ meta, errors, handleSubmit }','as' => 'div']); ?>
                    <form @submit="handleSubmit($event, call)">
                        <?php if (isset($component)) { $__componentOriginal09768308838b828c7799162f44758281 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal09768308838b828c7799162f44758281 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.modal.index','data' => ['ref' => 'whatsappModal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['ref' => 'whatsappModal']); ?>
                             <?php $__env->slot('header', null, []); ?> 
                                <h3 class="text-lg font-bold">
                                    <i class="icon-whatsapp mr-2"></i>
                                    <?php echo app('translator')->get('admin::app.leads.send-whatsapp-message.title'); ?>
                                </h3>
                             <?php $__env->endSlot(); ?>
    
                             <?php $__env->slot('content', null, []); ?> 
                                <div class="p-4">
                                    <?php if($lead->person && $lead->person->contact_numbers && count($lead->person->contact_numbers) > 0): ?>
                                        <!-- Message Input -->
                                        <?php if (isset($component)) { $__componentOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.index','data' => ['class' => 'mb-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-4']); ?>
                                            <?php if (isset($component)) { $__componentOriginal8378211f70f8c39b16d47cecdac9c7c8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8378211f70f8c39b16d47cecdac9c7c8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.label','data' => ['class' => 'required']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'required']); ?>
                                                <?php echo app('translator')->get('admin::app.leads.send-whatsapp-message.message'); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.control','data' => ['type' => 'textarea','name' => 'message','rules' => 'required','label' => trans('admin::app.leads.send-whatsapp-message.message'),'placeholder' => trans('admin::app.leads.send-whatsapp-message.placeholder')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'textarea','name' => 'message','rules' => 'required','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('admin::app.leads.send-whatsapp-message.message')),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('admin::app.leads.send-whatsapp-message.placeholder'))]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.error','data' => ['controlName' => 'message']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['control-name' => 'message']); ?>
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
    
                                        <!-- Phone Number Display -->
                                        <?php if (isset($component)) { $__componentOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7b1bc76a00ab5e7f1bf2c6429dae85a3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.index','data' => ['class' => 'mb-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-4']); ?>
                                            <?php if (isset($component)) { $__componentOriginal8378211f70f8c39b16d47cecdac9c7c8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8378211f70f8c39b16d47cecdac9c7c8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.label','data' => ['class' => 'required']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'required']); ?>
                                                <?php echo app('translator')->get('admin::app.leads.send-whatsapp-message.phone-number'); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.control','data' => ['type' => 'select','name' => 'contact_number','rules' => 'required','label' => trans('admin::app.leads.send-whatsapp-message.phone-number')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'select','name' => 'contact_number','rules' => 'required','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('admin::app.leads.send-whatsapp-message.phone-number'))]); ?>
                                                <option value=""><?php echo app('translator')->get('admin::app.leads.send-whatsapp-message.select-phone'); ?></option>
                                                <?php $__currentLoopData = $lead->person->contact_numbers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $number): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($number['value']); ?>">
                                                        <?php echo e($number['value']); ?>

                                                        <?php if($number['label']): ?>
                                                            (<?php echo e($number['label']); ?>)
                                                        <?php endif; ?>
                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.error','data' => ['controlName' => 'contact_number']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['control-name' => 'contact_number']); ?>
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
                                    <?php else: ?>
                                        <div class="rounded bg-yellow-50 p-4 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-200">
                                            <p class="font-semibold"><?php echo app('translator')->get('admin::app.leads.send-whatsapp-message.no-phone'); ?></p>
                                            <p class="text-sm"><?php echo app('translator')->get('admin::app.leads.send-whatsapp-message.add-phone-first'); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                             <?php $__env->endSlot(); ?>
    
                             <?php $__env->slot('footer', null, []); ?> 
                                <div class="flex items-center gap-x-2.5">
                                    <button
                                        type="submit"
                                        class="primary-button"
                                    >
                                        <?php echo app('translator')->get('admin::app.leads.send-whatsapp-message.send'); ?>
                                    </button>
                                </div>
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
            </div>
        </script>

        <script type="module">
            app.component('v-whatsapp-activity-action', {
                template: '#v-whatsapp-activity-action-template',

                props: ['lead_id'],

                methods: {
                    call(params, {
                        resetForm,
                        setErrors
                    }) {
                        this.$axios.post(`/admin/leads/${this.lead_id}/send-whatsapp`, params)
                            .then(response => {
                                this.$emitter.emit('add-flash', {
                                    type: 'success',
                                    message: response.data.message
                                });

                                this.$refs.whatsappModal.close();

                                resetForm();
                            })
                            .catch(error => {
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                } else {
                                    this.$emitter.emit('add-flash', {
                                        type: 'error',
                                        message: error.response.data.message
                                    });
                                }

                                this.$refs.whatsappModal.close();
                            });
                    }
                }
            });
        </script>
    <?php $__env->stopPush(); endif; ?>
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
<?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/leads/view.blade.php ENDPATH**/ ?>