<?php

use Diglactic\Breadcrumbs\Breadcrumbs;

// Google Ads breadcrumb - index
Breadcrumbs::for('google_ads.index', function ($trail) {
    $trail->push('Dashboard', route('admin.dashboard.index'));
    $trail->push('Google Ads', route('admin.google_ads.index'));
});

// Google Ads breadcrumb - campaigns
Breadcrumbs::for('google_ads.campaigns', function ($trail) {
    $trail->push('Dashboard', route('admin.dashboard.index'));
    $trail->push('Google Ads', route('admin.google_ads.index'));
    $trail->push('Campaigns', route('admin.google_ads.campaigns'));
});

// Google Ads breadcrumb - campaign show
Breadcrumbs::for('google_ads.campaigns.show', function ($trail) {
    $trail->push('Dashboard', route('admin.dashboard.index'));
    $trail->push('Google Ads', route('admin.google_ads.index'));
    $trail->push('Campaign Details');
});

// Google Ads breadcrumb - campaign create
Breadcrumbs::for('google_ads.campaigns.create', function ($trail) {
    $trail->push('Dashboard', route('admin.dashboard.index'));
    $trail->push('Google Ads', route('admin.google_ads.index'));
    $trail->push('Create Campaign', route('admin.google_ads.campaigns.create'));
});

// Google Ads breadcrumb - campaign edit
Breadcrumbs::for('google_ads.campaigns.edit', function ($trail) {
    $trail->push('Dashboard', route('admin.dashboard.index'));
    $trail->push('Google Ads', route('admin.google_ads.index'));
    $trail->push('Edit Campaign');
});

// Google Ads breadcrumb - settings
Breadcrumbs::for('google_ads.settings', function ($trail) {
    $trail->push('Dashboard', route('admin.dashboard.index'));
    $trail->push('Google Ads', route('admin.google_ads.index'));
    $trail->push('Settings', route('admin.google_ads.settings'));
});