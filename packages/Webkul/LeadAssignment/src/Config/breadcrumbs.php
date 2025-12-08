<?php

use Diglactic\Breadcrumbs\Breadcrumbs;

// Lead Assignment breadcrumb
Breadcrumbs::for('settings.lead_assignment', function ($trail) {
    $trail->push(__('Dashboard'), route('admin.dashboard.index'));
    $trail->push(__('Settings'), route('admin.settings.index'));
    $trail->push(__('Lead Assignment'), route('admin.settings.lead_assignment.index'));
});
