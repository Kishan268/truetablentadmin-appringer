<?php

Breadcrumbs::for('admin.dashboard', function ($trail) {
    $trail->push(__('strings.backend.dashboard.title'), route('admin.dashboard'));
});

Breadcrumbs::for('admin.auth.company.payments', function ($trail) {
    $trail->push('Payments', route('admin.auth.company.payments'));
});

Breadcrumbs::for('admin.system_settings', function ($trail) {
    $trail->push(__('strings.backend.settings'), route('admin.system_settings'));
});
Breadcrumbs::for('admin.system-config.index', function ($trail) {
    $trail->push(__('System Config'), route('admin.system-config.index'));
});
Breadcrumbs::for('admin.system-config.edit', function ($trail) {
    $trail->push(__('Edit System Config'), route('admin.system-config.edit',['id']));
});
Breadcrumbs::for('admin.candicates-data', function ($trail) {
    $trail->push(__('Candicates Data'), route('admin.candicates-data'));
});
Breadcrumbs::for('admin.footer_content', function ($trail) {
    $trail->push(__('strings.backend.footer_content'), route('admin.footer_content'));
});
Breadcrumbs::for('admin.popup_management', function ($trail) {
    $trail->push(__('strings.backend.popup_management'), route('admin.popup_management'));
});
Breadcrumbs::for('admin.auth.notification.index', function ($trail) {
    $trail->push(__('strings.backend.notification_settings'), route('admin.auth.notification.index'));
});
Breadcrumbs::for('admin.auth.notification.create', function ($trail) {
    $trail->push(__('strings.backend.notification_settings'), route('admin.auth.notification.create'));
});
Breadcrumbs::for('admin.auth.notification.edit', function ($trail) {
    $trail->push(__('strings.backend.notification_settings'), route('admin.auth.notification.edit',['id']));
});
Breadcrumbs::for('admin.auth.notification.update', function ($trail) {
    $trail->push(__('strings.backend.notification_settings'), route('admin.auth.notification.update',['id']));
});
require __DIR__.'/auth.php';
require __DIR__.'/log-viewer.php';
