<?php

namespace Webkul\Appointment\Providers;

use Webkul\Core\Providers\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    /**
     * Models.
     */
    protected $models = [
        \Webkul\Appointment\Models\Appointment::class,
    ];
}
