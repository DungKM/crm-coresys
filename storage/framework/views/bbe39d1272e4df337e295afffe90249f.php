<?php if (! ($breadcrumbs->isEmpty())): ?>
    <nav aria-label="">
        <ol class="flex flex-wrap">
            <?php $__currentLoopData = $breadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $breadcrumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(
                    $breadcrumb->url 
                    && ! $loop->last
                ): ?>
                    <li class="flex items-center gap-x-1 text-sm font-normal text-brandColor dark:text-brandColor">
                        <a href="<?php echo e($breadcrumb->url); ?>">
                            <?php echo e($breadcrumb->title); ?>

                        </a>

                        <span class="after:content-['/'] ltr:mr-1 rtl:ml-1"></span>
                    </li>
                <?php else: ?>
                    <li 
                        class="flex items-center gap-x-1 text-base text-gray-600 after:content-['/'] last:cursor-default after:last:hidden dark:text-gray-300" 
                        aria-current="page"
                    >
                        <?php echo e($breadcrumb->title); ?>

                    </li>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ol>
    </nav>
<?php endif; ?>
<?php /**PATH C:\laragon\www\base_crm1.0\packages\Webkul\Admin\src/resources/views/partials/breadcrumbs.blade.php ENDPATH**/ ?>