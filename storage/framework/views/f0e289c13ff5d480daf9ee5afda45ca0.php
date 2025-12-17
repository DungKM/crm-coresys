<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'name'                => 'attachments',
    'validations'         => null,
    'uploadedAttachments' => [],
    'allowMultiple'       => false,
    'hideButton'          => false,
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'name'                => 'attachments',
    'validations'         => null,
    'uploadedAttachments' => [],
    'allowMultiple'       => false,
    'hideButton'          => false,
]); ?>
<?php foreach (array_filter(([
    'name'                => 'attachments',
    'validations'         => null,
    'uploadedAttachments' => [],
    'allowMultiple'       => false,
    'hideButton'          => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<v-attachments
    name="<?php echo e($name); ?>"
    validations="<?php echo e($validations); ?>"
    :uploaded-attachments='<?php echo e(json_encode($uploadedAttachments)); ?>'
    :allow-multiple="<?php echo e($allowMultiple); ?>"
    :hide-button="<?php echo e($hideButton); ?>"
>
</v-attachments>

<?php if (! $__env->hasRenderedOnce('f841751a-2591-4dbc-9173-af12bce92a9b')): $__env->markAsRenderedOnce('f841751a-2591-4dbc-9173-af12bce92a9b');
$__env->startPush('scripts'); ?>
    <script
        type="text/x-template"
        id="v-attachments-template"
    >
        <!-- File Attachment Input -->
        <div
            class="relative items-center"
            v-show="! hideButton"
        >
            <input
                type="file"
                class="hidden"
                id="file-upload"
                accept="attachment/*"
                :multiple="allowMultiple"
                :ref="$.uid + '_attachmentInput'"
                @change="add"
            />

            <label
                class="flex cursor-pointer items-center gap-1"
                for="file-upload"
            >
                <i class="icon-attachment text-xl font-medium"></i>

                <span class="font-semibold">
                    <?php echo app('translator')->get('Add Attachments'); ?>
                </span>
            </label>
        </div>

        <!-- Uploaded attachments -->
        <div
            v-if="attachments?.length"
            class="flex flex-wrap gap-2"
        >
            <template v-for="(attachment, index) in attachments">
                <v-attachment-item
                    :name="name"
                    :index="index"
                    :attachment="attachment"
                    @onRemove="remove($event)"
                >
                </v-attachment-item>
            </template>
        </div>
    </script>

    <script type="text/x-template" id="v-attachment-item-template">
        <div class="flex items-center gap-2 rounded-md bg-gray-100 px-2.5 py-1 dark:bg-gray-950">
            <span class="max-w-xs truncate dark:text-white">
                {{ attachment.name }}
            </span>

            <?php if (isset($component)) { $__componentOriginal53af403f6b2179a3039d488b8ab2a267 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53af403f6b2179a3039d488b8ab2a267 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.form.control-group.control','data' => ['type' => 'file',':name' => 'name + \'[]\'','class' => 'hidden',':ref' => '$.uid + \'_attachmentInput_\' + index']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'file',':name' => 'name + \'[]\'','class' => 'hidden',':ref' => '$.uid + \'_attachmentInput_\' + index']); ?>
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

            <i 
                class="icon-cross-large cursor-pointer rounded-md p-0.5 text-xl hover:bg-gray-200 dark:hover:bg-gray-800"
                @click="remove"
            ></i>
        </div>
    </script>

    <script type="module">
        app.component('v-attachments', {
            template: '#v-attachments-template',

            props: {
                name: {
                    type: String, 
                    default: 'attachments',
                },

                validations: {
                    type: String,
                    default: '',
                },

                uploadedAttachments: {
                    type: Array,
                    default: () => []
                },

                allowMultiple: {
                    type: Boolean,
                    default: false,
                },

                hideButton: {
                    type: Boolean,
                    default: false,
                },

                errors: {
                    type: Object,
                    default: () => {}
                }
            },

            data() {
                return {
                    attachments: [],
                }
            },

            mounted() {
                this.attachments = this.uploadedAttachments;
            },

            methods: {
                add() {
                    let attachmentInput = this.$refs[this.$.uid + '_attachmentInput'];

                    if (attachmentInput.files == undefined) {
                        return;
                    }

                    attachmentInput.files.forEach((file, index) => {
                        this.attachments.push({
                            id: 'attachment_' + this.attachments.length,
                            name: file.name,
                            file: file
                        });
                    });
                },

                remove(attachment) {
                    let index = this.attachments.indexOf(attachment);

                    this.attachments.splice(index, 1);
                },
            }
        });

        app.component('v-attachment-item', {
            template: '#v-attachment-item-template',

            props: ['index', 'attachment', 'name'],

            mounted() {
                if (this.attachment.file instanceof File) {
                    this.setFile(this.attachment.file);
                }
            },

            methods: {
                remove() {
                    this.$emit('onRemove', this.attachment)
                },

                setFile(file) {
                    const dataTransfer = new DataTransfer();

                    dataTransfer.items.add(file);

                    this.$refs[this.$.uid + '_attachmentInput_' + this.index].files = dataTransfer.files;
                },
            }
        });
    </script>
<?php $__env->stopPush(); endif; ?><?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/components/attachments/index.blade.php ENDPATH**/ ?>