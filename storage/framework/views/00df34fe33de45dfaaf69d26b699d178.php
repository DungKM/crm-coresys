<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'endpoint',
    'emailDetachEndpoint' => null,
    'activeType'          => 'all',
    'types'               => null,
    'extraTypes'          => null,
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'endpoint',
    'emailDetachEndpoint' => null,
    'activeType'          => 'all',
    'types'               => null,
    'extraTypes'          => null,
]); ?>
<?php foreach (array_filter(([
    'endpoint',
    'emailDetachEndpoint' => null,
    'activeType'          => 'all',
    'types'               => null,
    'extraTypes'          => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php echo view_render_event('admin.components.activities.before'); ?>


<!-- Lead Activities Vue Component -->
<v-activities
    endpoint="<?php echo e($endpoint); ?>"
    email-detach-endpoint="<?php echo e($emailDetachEndpoint); ?>"
    active-type="<?php echo e($activeType); ?>"
    <?php if($types): ?>:types='<?php echo json_encode($types, 15, 512) ?>'<?php endif; ?>
    <?php if($extraTypes): ?>:extra-types='<?php echo json_encode($extraTypes, 15, 512) ?>'<?php endif; ?>
    ref="activities"
>
    <!-- Shimmer -->
    <?php if (isset($component)) { $__componentOriginalc27b22af519da78c59042bcaaab986b1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc27b22af519da78c59042bcaaab986b1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.shimmer.activities.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::shimmer.activities'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc27b22af519da78c59042bcaaab986b1)): ?>
<?php $attributes = $__attributesOriginalc27b22af519da78c59042bcaaab986b1; ?>
<?php unset($__attributesOriginalc27b22af519da78c59042bcaaab986b1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc27b22af519da78c59042bcaaab986b1)): ?>
<?php $component = $__componentOriginalc27b22af519da78c59042bcaaab986b1; ?>
<?php unset($__componentOriginalc27b22af519da78c59042bcaaab986b1); ?>
<?php endif; ?>

    <?php $__currentLoopData = $extraTypes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <template v-slot:<?php echo e($type['name']); ?>>
            <?php echo e(${$type['name']} ?? ''); ?>

        </template>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</v-activities>

<?php echo view_render_event('admin.components.activities.after'); ?>


<?php if (! $__env->hasRenderedOnce('3595113c-a121-44ba-b32b-2ccd073f9af7')): $__env->markAsRenderedOnce('3595113c-a121-44ba-b32b-2ccd073f9af7');
$__env->startPush('scripts'); ?>
    <script type="text/x-template" id="v-activities-template">
        <template v-if="isLoading">
            <!-- Shimmer -->
            <?php if (isset($component)) { $__componentOriginalc27b22af519da78c59042bcaaab986b1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc27b22af519da78c59042bcaaab986b1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.shimmer.activities.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::shimmer.activities'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc27b22af519da78c59042bcaaab986b1)): ?>
<?php $attributes = $__attributesOriginalc27b22af519da78c59042bcaaab986b1; ?>
<?php unset($__attributesOriginalc27b22af519da78c59042bcaaab986b1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc27b22af519da78c59042bcaaab986b1)): ?>
<?php $component = $__componentOriginalc27b22af519da78c59042bcaaab986b1; ?>
<?php unset($__componentOriginalc27b22af519da78c59042bcaaab986b1); ?>
<?php endif; ?>
        </template>

        <template v-else>
            <?php echo view_render_event('admin.components.activities.content.before'); ?>


            <div class="w-full rounded-md border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                <div class="flex gap-2 overflow-x-auto border-b border-gray-200 dark:border-gray-800">
                    <?php echo view_render_event('admin.components.activities.content.types.before'); ?>


                    <div
                        v-for="type in types"
                        class="cursor-pointer px-3 py-2.5 text-sm font-medium dark:text-white"
                        :class="{'border-brandColor border-b-2 !text-brandColor transition': selectedType == type.name }"
                        @click="selectedType = type.name"
                    >
                        {{ type.label }}
                    </div>

                    <?php echo view_render_event('admin.components.activities.content.types.after'); ?>

                </div>

                <!-- Show Default Activities if selectedType not in extraTypes -->
                <template v-if="! extraTypes.find(type => type.name == selectedType)">
                    <div class="animate-[on-fade_0.5s_ease-in-out] p-4">
                        <?php echo view_render_event('admin.components.activities.content.activity.list.before'); ?>


                        <!-- Activity List -->
                        <div class="flex flex-col gap-4">
                            <?php echo view_render_event('admin.components.activities.content.activity.item.before'); ?>


                            <!-- Activity Item -->
                            <div
                                class="flex gap-2"
                                v-for="(activity, index) in filteredActivities"
                            >
                                <?php echo view_render_event('admin.components.activities.content.activity.item.icon.before'); ?>


                                <!-- Activity Icon -->
                                <div
                                    class="mt-2 flex h-9 min-h-9 w-9 min-w-9 items-center justify-center rounded-full text-xl"
                                    :class="typeClasses[activity.type] ?? typeClasses['default']"
                                >
                                </div>

                                <?php echo view_render_event('admin.components.activities.content.activity.item.icon.after'); ?>


                                <?php echo view_render_event('admin.components.activities.content.activity.item.details.before'); ?>


                                <!-- Activity Details -->
                                <div
                                    class="flex w-full justify-between gap-4 rounded-md p-4"
                                    :class="{'bg-gray-100 dark:bg-gray-950': index % 2 != 0 }"
                                >
                                    <div class="flex flex-col gap-2">
                                        <?php echo view_render_event('admin.components.activities.content.activity.item.title.before'); ?>


                                        <!-- Activity Title -->
                                        <div
                                            class="flex flex-col gap-1"
                                            v-if="activity.title"
                                        >
                                            <p class="flex flex-wrap items-center gap-1 font-medium text-gray-800 dark:text-white">
                                                {{ activity.title }}

                                                <template v-if="activity.type == 'system' && activity.additional">
                                                    <p class="flex items-center gap-1 text-gray-800 dark:text-white">
                                                        <span>:</span>

                                                        <span class="break-words text-gray-700 dark:text-gray-200">
                                                            {{ (activity.additional.old.label ? String(activity.additional.old.label).replaceAll('<br>', ' ') : "<?php echo app('translator')->get('admin::app.components.activities.index.empty'); ?>") }}
                                                        </span>

                                                        <span class="icon-stats-up rotate-90 text-xl"></span>

                                                        <span class="break-words text-gray-700 dark:text-gray-200">
                                                            {{ (activity.additional.new.label ? String(activity.additional.new.label).replaceAll('<br>', ' ') : "<?php echo app('translator')->get('admin::app.components.activities.index.empty'); ?>") }}
                                                        </span>
                                                    </p>
                                                </template>
                                            </p>

                                            <template v-if="activity.type == 'email'">
                                                <p class="text-gray-800 dark:text-white">
                                                    <?php echo app('translator')->get('admin::app.components.activities.index.from'); ?>:

                                                    {{ activity.additional.from }}
                                                </p>

                                                <p class="text-gray-800 dark:text-white">
                                                    <?php echo app('translator')->get('admin::app.components.activities.index.to'); ?>:

                                                    {{ activity.additional.to.join(', ') }}
                                                </p>

                                                <p
                                                    v-if="activity.additional.cc"
                                                    class="text-gray-800 dark:text-white"
                                                >
                                                    <?php echo app('translator')->get('admin::app.components.activities.index.cc'); ?>:

                                                    {{ activity.additional.cc.join(', ') }}
                                                </p>

                                                <p
                                                    v-if="activity.additional.bcc"
                                                    class="text-gray-800 dark:text-white"
                                                >
                                                    <?php echo app('translator')->get('admin::app.components.activities.index.bcc'); ?>:

                                                    {{ activity.additional.bcc.join(', ') }}
                                                </p>
                                            </template>

                                            <template v-else>
                                                <!-- Activity Schedule -->
                                                <p
                                                    v-if="activity.schedule_from && activity.schedule_from"
                                                    class="text-gray-800 dark:text-white"
                                                >
                                                    <?php echo app('translator')->get('admin::app.components.activities.index.scheduled-on'); ?>:

                                                    {{ $admin.formatDate(activity.schedule_from, 'd MMM yyyy, h:mm A', timezone) + ' - ' + $admin.formatDate(activity.schedule_to, 'd MMM yyyy, h:mm A', timezone) }}
                                                </p>

                                                <!-- Activity Participants -->
                                                <p
                                                    v-if="activity.participants?.length"
                                                    class="text-gray-800 dark:text-white"
                                                >
                                                    <?php echo app('translator')->get('admin::app.components.activities.index.participants'); ?>:

                                                    <span class="after:content-[',_'] last:after:content-['']" v-for="(participant, index) in activity.participants">
                                                        {{ participant.user?.name ?? participant.person.name }}
                                                    </span>
                                                </p>

                                                <!-- Activity Location -->
                                                <p
                                                    v-if="activity.location"
                                                    class="text-gray-800 dark:text-white"
                                                >
                                                    <?php echo app('translator')->get('admin::app.components.activities.index.location'); ?>:

                                                    {{ activity.location }}
                                                </p>
                                            </template>
                                        </div>

                                        <?php echo view_render_event('admin.components.activities.content.activity.item.title.after'); ?>


                                        <?php echo view_render_event('admin.components.activities.content.activity.item.description.before'); ?>


                                        <!-- Activity Description -->
                                        <p
                                            class="text-gray-800 dark:text-white"
                                            v-if="activity.comment"
                                            v-safe-html="activity.comment"
                                        ></p>

                                        <?php echo view_render_event('admin.components.activities.content.activity.item.description.after'); ?>


                                        <?php echo view_render_event('admin.components.activities.content.activity.item.attachments.before'); ?>


                                        <!-- Attachments -->
                                        <div
                                            class="flex flex-wrap gap-2"
                                            v-if="activity.files.length"
                                        >
                                            <a
                                                :href="
                                                    activity.type == 'email'
                                                    ? `<?php echo e(route('admin.mail.attachment_download', 'replaceID')); ?>`.replace('replaceID', file.id)
                                                    : `<?php echo e(route('admin.activities.file_download', 'replaceID')); ?>`.replace('replaceID', file.id)
                                                "
                                                class="flex cursor-pointer items-center gap-1 rounded-md p-1.5"
                                                target="_blank"
                                                v-for="(file, index) in activity.files"
                                            >
                                                <span class="icon-attached-file text-xl"></span>

                                                <span class="font-medium text-brandColor">
                                                    {{ file.name }}
                                                </span>
                                            </a>
                                        </div>

                                        <?php echo view_render_event('admin.components.activities.content.activity.item.attachments.after'); ?>


                                        <?php echo view_render_event('admin.components.activities.content.activity.item.time_and_user.before'); ?>


                                        <!-- Activity Time and User -->
                                        <div class="text-gray-500 dark:text-gray-300">
                                            {{ $admin.formatDate(activity.created_at, 'd MMM yyyy, h:mm A', timezone) }},

                                            {{ "<?php echo app('translator')->get('admin::app.components.activities.index.by-user', ['user' => 'replace']); ?>".replace('replace', activity.user?.name ?? '<?php echo app('translator')->get('admin::app.components.activities.index.system'); ?>') }}
                                        </div>

                                        <?php echo view_render_event('admin.components.activities.content.activity.item.time_and_user.after'); ?>

                                    </div>

                                    <?php echo view_render_event('admin.components.activities.content.activity.item.more_actions.before'); ?>


                                    <!-- Activity More Options -->
                                    <template v-if="activity.type != 'system'">
                                        <?php echo view_render_event('admin.components.activities.content.activity.item.more_actions.dropdown.after'); ?>


                                        <?php if (isset($component)) { $__componentOriginalaf937e0ec72fa678d3a0c6dc6c0ac5f2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalaf937e0ec72fa678d3a0c6dc6c0ac5f2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.dropdown.index','data' => ['position' => 'bottom-'.e(in_array(app()->getLocale(), ['fa', 'ar']) ? 'left' : 'right').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['position' => 'bottom-'.e(in_array(app()->getLocale(), ['fa', 'ar']) ? 'left' : 'right').'']); ?>
                                             <?php $__env->slot('toggle', null, []); ?> 
                                                <?php echo view_render_event('admin.components.activities.content.activity.item.more_actions.dropdown.toggle.before'); ?>


                                                <template v-if="! isUpdating[activity.id]">
                                                    <button
                                                        class="icon-more flex h-7 w-7 cursor-pointer items-center justify-center rounded-md text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800"
                                                    ></button>
                                                </template>

                                                <template v-else>
                                                    <?php if (isset($component)) { $__componentOriginal991e5e3816aa635af8067aa2abbd328b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal991e5e3816aa635af8067aa2abbd328b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.spinner.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::spinner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal991e5e3816aa635af8067aa2abbd328b)): ?>
<?php $attributes = $__attributesOriginal991e5e3816aa635af8067aa2abbd328b; ?>
<?php unset($__attributesOriginal991e5e3816aa635af8067aa2abbd328b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal991e5e3816aa635af8067aa2abbd328b)): ?>
<?php $component = $__componentOriginal991e5e3816aa635af8067aa2abbd328b; ?>
<?php unset($__componentOriginal991e5e3816aa635af8067aa2abbd328b); ?>
<?php endif; ?>
                                                </template>

                                                <?php echo view_render_event('admin.components.activities.content.activity.item.more_actions.dropdown.toggle.after'); ?>

                                             <?php $__env->endSlot(); ?>

                                             <?php $__env->slot('menu', null, ['class' => '!min-w-40']); ?> 
                                                <?php echo view_render_event('admin.components.activities.content.activity.item.more_actions.dropdown.menu_item.before'); ?>


                                                <template v-if="activity.type != 'email'">
                                                    <?php if(bouncer()->hasPermission('activities.edit')): ?>
                                                        <?php if (isset($component)) { $__componentOriginal0223c8534d6a243be608c3a65289c4d0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0223c8534d6a243be608c3a65289c4d0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.dropdown.menu.item','data' => ['vIf' => '! activity.is_done && [\'call\', \'meeting\', \'lunch\'].includes(activity.type)','@click' => 'markAsDone(activity)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::dropdown.menu.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['v-if' => '! activity.is_done && [\'call\', \'meeting\', \'lunch\'].includes(activity.type)','@click' => 'markAsDone(activity)']); ?>
                                                            <div class="flex items-center gap-2">
                                                                <span class="icon-tick text-2xl"></span>

                                                                <?php echo app('translator')->get('admin::app.components.activities.index.mark-as-done'); ?>
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

                                                        <?php if (isset($component)) { $__componentOriginal0223c8534d6a243be608c3a65289c4d0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0223c8534d6a243be608c3a65289c4d0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.dropdown.menu.item','data' => ['vIf' => '[\'call\', \'meeting\', \'lunch\'].includes(activity.type)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::dropdown.menu.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['v-if' => '[\'call\', \'meeting\', \'lunch\'].includes(activity.type)']); ?>
                                                            <a
                                                                class="flex items-center gap-2"
                                                                :href="'<?php echo e(route('admin.activities.edit', 'replaceId')); ?>'.replace('replaceId', activity.id)"
                                                                target="_blank"
                                                            >
                                                                <span class="icon-edit text-2xl"></span>

                                                                <?php echo app('translator')->get('admin::app.components.activities.index.edit'); ?>
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
                                                    <?php endif; ?>

                                                    <?php if(bouncer()->hasPermission('activities.delete')): ?>
                                                        <?php if (isset($component)) { $__componentOriginal0223c8534d6a243be608c3a65289c4d0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0223c8534d6a243be608c3a65289c4d0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.dropdown.menu.item','data' => ['@click' => 'remove(activity)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::dropdown.menu.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['@click' => 'remove(activity)']); ?>
                                                            <div class="flex items-center gap-2">
                                                                <span class="icon-delete text-2xl"></span>

                                                                <?php echo app('translator')->get('admin::app.components.activities.index.delete'); ?>
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
                                                    <?php endif; ?>
                                                </template>

                                                <template v-else>
                                                    <?php if(bouncer()->hasPermission('mail.view')): ?>
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
                                                            <a
                                                                :href="'<?php echo e(route('admin.mail.view', ['route' => 'replaceFolder', 'id' => 'replaceMailId'])); ?>'.replace('replaceFolder', activity.additional.folders[0]).replace('replaceMailId', activity.id)"
                                                                class="flex items-center gap-2"
                                                                target="_blank"
                                                            >
                                                                <span class="icon-eye text-2xl"></span>

                                                                <?php echo app('translator')->get('admin::app.components.activities.index.view'); ?>
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
                                                    <?php endif; ?>

                                                    <?php if (isset($component)) { $__componentOriginal0223c8534d6a243be608c3a65289c4d0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0223c8534d6a243be608c3a65289c4d0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.dropdown.menu.item','data' => ['@click' => 'unlinkEmail(activity)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::dropdown.menu.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['@click' => 'unlinkEmail(activity)']); ?>
                                                        <div class="flex items-center gap-2">
                                                            <span class="icon-attachment text-2xl"></span>

                                                            <?php echo app('translator')->get('admin::app.components.activities.index.unlink'); ?>
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
                                                </template>

                                                <?php echo view_render_event('admin.components.activities.content.activity.item.more_actions.dropdown.menu_item.after'); ?>

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

                                        <?php echo view_render_event('admin.components.activities.content.activity.item.more_actions.dropdown.after'); ?>

                                    </template>

                                    <?php echo view_render_event('admin.components.activities.content.activity.item.more_actions.after'); ?>

                                </div>

                                <?php echo view_render_event('admin.components.activities.content.activity.item.details.after'); ?>

                            </div>

                            <?php echo view_render_event('admin.components.activities.content.activity.item.after'); ?>


                            <!-- Empty Placeholder -->
                            <div
                                class="grid justify-center justify-items-center gap-3.5 py-12"
                                v-if="! filteredActivities.length"
                            >
                                <img
                                    class="dark:mix-blend-exclusion dark:invert"
                                    :src="typeIllustrations[selectedType]?.image ?? typeIllustrations['all'].image"
                                >

                                <div class="flex flex-col items-center gap-2">
                                    <p class="text-xl font-semibold dark:text-white">
                                        {{ typeIllustrations[selectedType]?.title ?? typeIllustrations['all'].title }}
                                    </p>

                                    <p class="text-gray-400 dark:text-gray-400">
                                        {{ typeIllustrations[selectedType]?.description ?? typeIllustrations['all'].description }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <?php echo view_render_event('admin.components.activities.content.activity.list.after'); ?>

                    </div>
                </template>

                <template v-else>
                    <template v-for="type in extraTypes">
                        <?php echo view_render_event('admin.components.activities.content.activity.extra_types.before'); ?>


                        <div v-show="selectedType == type.name">
                            <slot :name="type.name"></slot>
                        </div>

                        <?php echo view_render_event('admin.components.activities.content.activity.extra_types.after'); ?>

                    </template>
                </template>
            </div>

            <?php echo view_render_event('admin.components.activities.content.after'); ?>

        </template>
    </script>

    <script type="module">
        app.component('v-activities', {
            template: '#v-activities-template',

            props: {
                endpoint: {
                    type: String,
                    default: '',
                },

                emailDetachEndpoint: {
                    type: String,
                    default: '',
                },

                activeType: {
                    type: String,
                    default: 'all',
                },

                types: {
                    type: Array,
                    default: [
                        {
                            name: 'all',
                            label: "<?php echo e(trans('admin::app.components.activities.index.all')); ?>",
                        }, {
                            name: 'planned',
                            label: "<?php echo e(trans('admin::app.components.activities.index.planned')); ?>",
                        }, {
                            name: 'note',
                            label: "<?php echo e(trans('admin::app.components.activities.index.notes')); ?>",
                        }, {
                            name: 'call',
                            label: "<?php echo e(trans('admin::app.components.activities.index.calls')); ?>",
                        }, {
                            name: 'meeting',
                            label: "<?php echo e(trans('admin::app.components.activities.index.meetings')); ?>",
                        }, {
                            name: 'lunch',
                            label: "<?php echo e(trans('admin::app.components.activities.index.lunches')); ?>",
                        }, {
                            name: 'file',
                            label: "<?php echo e(trans('admin::app.components.activities.index.files')); ?>",
                        }, {
                            name: 'email',
                            label: "<?php echo e(trans('admin::app.components.activities.index.emails')); ?>",
                        }, {
                            name: 'system',
                            label: "<?php echo e(trans('admin::app.components.activities.index.change-log')); ?>",
                        }
                    ],
                },

                extraTypes: {
                    type: Array,
                    default: [],
                },
            },

            data() {
                return {
                    isLoading: false,

                    isUpdating: {},

                    activities: [],

                    selectedType: this.activeType,

                    typeClasses: {
                        email: 'icon-mail bg-green-200 text-green-900 dark:!text-green-900',
                        note: 'icon-note bg-orange-200 text-orange-800 dark:!text-orange-800',
                        call: 'icon-call bg-cyan-200 text-cyan-800 dark:!text-cyan-800',
                        meeting: 'icon-activity bg-blue-200 text-blue-800 dark:!text-blue-800',
                        lunch: 'icon-activity bg-blue-200 text-blue-800 dark:!text-blue-800',
                        file: 'icon-file bg-green-200 text-green-900 dark:!text-green-900',
                        system: 'icon-system-generate bg-yellow-200 text-yellow-900 dark:!text-yellow-900',
                        default: 'icon-activity bg-blue-200 text-blue-800 dark:!text-blue-800',
                    },

                    typeIllustrations: {
                        all: {
                            image: "<?php echo e(vite()->asset('images/empty-placeholders/activities.svg')); ?>",
                            title: "<?php echo e(trans('admin::app.components.activities.index.empty-placeholders.all.title')); ?>",
                            description: "<?php echo e(trans('admin::app.components.activities.index.empty-placeholders.all.description')); ?>",
                        },

                        planned: {
                            image: "<?php echo e(vite()->asset('images/empty-placeholders/plans.svg')); ?>",
                            title: "<?php echo e(trans('admin::app.components.activities.index.empty-placeholders.planned.title')); ?>",
                            description: "<?php echo e(trans('admin::app.components.activities.index.empty-placeholders.planned.description')); ?>",
                        },

                        note: {
                            image: "<?php echo e(vite()->asset('images/empty-placeholders/notes.svg')); ?>",
                            title: "<?php echo e(trans('admin::app.components.activities.index.empty-placeholders.notes.title')); ?>",
                            description: "<?php echo e(trans('admin::app.components.activities.index.empty-placeholders.notes.description')); ?>",
                        },

                        call: {
                            image: "<?php echo e(vite()->asset('images/empty-placeholders/calls.svg')); ?>",
                            title: "<?php echo e(trans('admin::app.components.activities.index.empty-placeholders.calls.title')); ?>",
                            description: "<?php echo e(trans('admin::app.components.activities.index.empty-placeholders.calls.description')); ?>",
                        },

                        meeting: {
                            image: "<?php echo e(vite()->asset('images/empty-placeholders/meetings.svg')); ?>",
                            title: "<?php echo e(trans('admin::app.components.activities.index.empty-placeholders.meetings.title')); ?>",
                            description: "<?php echo e(trans('admin::app.components.activities.index.empty-placeholders.meetings.description')); ?>",
                        },

                        lunch: {
                            image: "<?php echo e(vite()->asset('images/empty-placeholders/lunches.svg')); ?>",
                            title: "<?php echo e(trans('admin::app.components.activities.index.empty-placeholders.lunches.title')); ?>",
                            description: "<?php echo e(trans('admin::app.components.activities.index.empty-placeholders.lunches.description')); ?>",
                        },

                        file: {
                            image: "<?php echo e(vite()->asset('images/empty-placeholders/files.svg')); ?>",
                            title: "<?php echo e(trans('admin::app.components.activities.index.empty-placeholders.files.title')); ?>",
                            description: "<?php echo e(trans('admin::app.components.activities.index.empty-placeholders.files.description')); ?>",
                        },

                        email: {
                            image: "<?php echo e(vite()->asset('images/empty-placeholders/emails.svg')); ?>",
                            title: "<?php echo e(trans('admin::app.components.activities.index.empty-placeholders.emails.title')); ?>",
                            description: "<?php echo e(trans('admin::app.components.activities.index.empty-placeholders.emails.description')); ?>",
                        },

                        system: {
                            image: "<?php echo e(vite()->asset('images/empty-placeholders/activities.svg')); ?>",
                            title: "<?php echo e(trans('admin::app.components.activities.index.empty-placeholders.system.title')); ?>",
                            description: "<?php echo e(trans('admin::app.components.activities.index.empty-placeholders.system.description')); ?>",
                        }
                    },

                    timezone: "<?php echo e(config('app.timezone')); ?>",
                }
            },

            computed: {
                filteredActivities() {
                    if (this.selectedType == 'all') {
                        return this.activities;
                    } else if (this.selectedType == 'planned') {
                        return this.activities.filter(activity => ! activity.is_done);
                    }

                    return this.activities.filter(activity => activity.type == this.selectedType);
                }
            },

            mounted() {
                this.get();

                if (this.extraTypes?.length) {
                    this.extraTypes.forEach(type => {
                        this.types.push(type);
                    });
                }

                this.$emitter.on('on-activity-added', (activity) => this.activities.unshift(activity));
            },

            methods: {
                get() {
                    this.isLoading = true;

                    this.$axios.get(this.endpoint)
                        .then(response => {
                            this.activities = response.data.data;

                            this.isLoading = false;
                        })
                        .catch(error => {
                            console.error(error);
                        });
                },

                markAsDone(activity) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            this.isUpdating[activity.id] = true;

                            this.$axios.put("<?php echo e(route('admin.activities.update', 'replaceId')); ?>".replace('replaceId', activity.id), {
                                    'is_done': 1
                                })
                                .then((response) => {
                                    this.isUpdating[activity.id] = false;

                                    activity.is_done = 1;

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                })
                                .catch((error) => {
                                    this.isUpdating[activity.id] = false;

                                    this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                                });
                        },
                    });
                },

                remove(activity) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            this.isUpdating[activity.id] = true;

                            this.$axios.delete("<?php echo e(route('admin.activities.delete', 'replaceId')); ?>".replace('replaceId', activity.id))
                                .then((response) => {
                                    this.isUpdating[activity.id] = false;

                                    this.activities.splice(this.activities.indexOf(activity), 1);

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                })
                                .catch((error) => {
                                    this.isUpdating[activity.id] = false;

                                    this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                                });
                        },
                    });
                },

                unlinkEmail(activity) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            let emailId = activity.parent_id ?? activity.id;

                            this.$axios.delete(this.emailDetachEndpoint, {
                                    data: {
                                        email_id: emailId,
                                    }
                                })
                                .then((response) => {
                                    let relatedActivities = this.activities.filter(activity => activity.parent_id == emailId || activity.id == emailId);

                                    relatedActivities.forEach(activity => {
                                        const index = this.activities.findIndex(a => a === activity);

                                        if (index !== -1) {
                                            this.activities.splice(index, 1);
                                        }
                                    });

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                })
                                .catch((error) => {
                                    this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                                });
                        }
                    });
                },
            },
        });
    </script>
<?php $__env->stopPush(); endif; ?>
<?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/components/activities/index.blade.php ENDPATH**/ ?>