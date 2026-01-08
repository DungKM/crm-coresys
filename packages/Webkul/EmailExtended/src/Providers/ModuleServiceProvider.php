<?php

namespace Webkul\EmailExtended\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\EmailExtended\Models\Email::class,
        \Webkul\EmailExtended\Models\EmailThread::class,
        \Webkul\EmailExtended\Models\EmailTracking::class,
        \Webkul\EmailExtended\Models\EmailScheduled::class,
    ];
}