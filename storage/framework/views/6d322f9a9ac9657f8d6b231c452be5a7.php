<!DOCTYPE html>

<html
    lang="<?php echo e(app()->getLocale()); ?>"
    dir="<?php echo e(in_array(app()->getLocale(), ['fa', 'ar']) ? 'rtl' : 'ltr'); ?>"
>

<head>
    <title><?php echo e($title ?? ''); ?></title>

    <meta charset="UTF-8">

    <meta
        http-equiv="X-UA-Compatible"
        content="IE=edge"
    >
    <meta
        http-equiv="content-language"
        content="<?php echo e(app()->getLocale()); ?>"
    >

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >
    <meta
        name="base-url"
        content="<?php echo e(url()->to('/')); ?>"
    >
    <meta
        name="currency-code"
        
    >

    <?php echo $__env->yieldPushContent('meta'); ?>

    <?php echo e(vite()->set(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'])); ?>


    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    />

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap"
        rel="stylesheet"
    />

    <?php if($favicon = core()->getConfigData('general.design.admin_logo.favicon')): ?>
        <link
            type="image/x-icon"
            href="<?php echo e(Storage::url($favicon)); ?>"
            rel="shortcut icon"
            sizes="16x16"
        >
    <?php else: ?>
        <link
            type="image/x-icon"
            href="<?php echo e(vite()->asset('images/favicon.ico')); ?>"
            rel="shortcut icon"
            sizes="16x16"
        />
    <?php endif; ?>

    <?php
        $brandColor = core()->getConfigData('general.settings.menu_color.brand_color') ?? '#0E90D9';
    ?>

    <?php echo $__env->yieldPushContent('styles'); ?>

    <style>
        :root {
            --brand-color: <?php echo e($brandColor); ?>;
        }

        <?php echo core()->getConfigData('general.content.custom_scripts.custom_css'); ?>

    </style>

    <?php echo view_render_event('admin.layout.head'); ?>

</head>

<body>
    <?php echo view_render_event('admin.layout.body.before'); ?>


    <div id="app">
        <!-- Flash Message Blade Component -->
        <?php if (isset($component)) { $__componentOriginal701f473bf36886c6d0b4697249a816f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal701f473bf36886c6d0b4697249a816f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin::components.flash-group.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin::flash-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal701f473bf36886c6d0b4697249a816f6)): ?>
<?php $attributes = $__attributesOriginal701f473bf36886c6d0b4697249a816f6; ?>
<?php unset($__attributesOriginal701f473bf36886c6d0b4697249a816f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal701f473bf36886c6d0b4697249a816f6)): ?>
<?php $component = $__componentOriginal701f473bf36886c6d0b4697249a816f6; ?>
<?php unset($__componentOriginal701f473bf36886c6d0b4697249a816f6); ?>
<?php endif; ?>

        <?php echo view_render_event('admin.layout.content.before'); ?>


        <!-- Page Content Blade Component -->
        <?php echo e($slot); ?>


        <?php echo view_render_event('admin.layout.content.after'); ?>

    </div>

    <?php echo view_render_event('admin.layout.body.after'); ?>


    <?php echo $__env->yieldPushContent('scripts'); ?>

    <?php echo view_render_event('admin.layout.vue-app-mount.before'); ?>


    <script>
        /**
         * Load event, the purpose of using the event is to mount the application
         * after all of our `Vue` components which is present in blade file have
         * been registered in the app. No matter what `app.mount()` should be
         * called in the last.
         */
        window.addEventListener("load", function(event) {
            app.mount("#app");
        });
    </script>

    <?php echo view_render_event('admin.layout.vue-app-mount.after'); ?>


    <script type="text/javascript">
        <?php echo core()->getConfigData('general.content.custom_scripts.custom_javascript'); ?>

    </script>
</body>

</html>
<?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/components/layouts/anonymous.blade.php ENDPATH**/ ?>