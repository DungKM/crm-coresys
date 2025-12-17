<?php echo view_render_event('admin.leads.index.kanban.toolbar.before'); ?>


<div class="flex justify-between gap-2 max-md:flex-wrap">
    <div class="flex w-full items-center gap-x-1.5 max-md:justify-between">
        <?php echo view_render_event('admin.leads.index.kanban.toolbar.search.before'); ?>


        <!-- Search Panel -->
        <?php echo $__env->make('admin::leads.index.kanban.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php echo view_render_event('admin.leads.index.kanban.toolbar.search.after'); ?>


        <?php echo view_render_event('admin.leads.index.kanban.toolbar.filter.before'); ?>


        <!-- Filter -->
        <?php echo $__env->make('admin::leads.index.kanban.filter', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php echo view_render_event('admin.leads.index.kanban.toolbar.filter.after'); ?>


        <div class="z-10 hidden w-full divide-y divide-gray-100 rounded bg-white shadow dark:bg-gray-900"></div>
    </div>

    <?php echo view_render_event('admin.leads.index.kanban.toolbar.switcher.before'); ?>


    <!-- View Switcher -->
    <?php echo $__env->make('admin::leads.index.view-switcher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo view_render_event('admin.leads.index.kanban.toolbar.switcher.after'); ?>

</div>

<?php echo view_render_event('admin.leads.index.kanban.toolbar.after'); ?>

<?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/leads/index/kanban/toolbar.blade.php ENDPATH**/ ?>