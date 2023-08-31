<?php

Breadcrumbs::for('admin.auth.user.index', function ($trail) {
    $trail->push(__('labels.backend.access.users.management'), route('admin.auth.user.index'));
});

Breadcrumbs::for('admin.auth.user.deactivated', function ($trail) {
    $trail->parent('admin.auth.user.index');
    $trail->push(__('menus.backend.access.users.deactivated'), route('admin.auth.user.deactivated'));
});

Breadcrumbs::for('admin.auth.user.deleted', function ($trail) {
    $trail->parent('admin.auth.user.index');
    $trail->push(__('menus.backend.access.users.deactivated'), route('admin.auth.user.deleted'));
});

Breadcrumbs::for('admin.auth.user.import', function ($trail) {
    $trail->parent('admin.auth.user.index');
    $trail->push(__('labels.backend.access.users.management'), route('admin.auth.user.import'));
});

Breadcrumbs::for('admin.auth.user.importFile', function ($trail) {
    $trail->parent('admin.auth.user.index');
    $trail->push(__('labels.backend.access.users.management'), route('admin.auth.user.import'));
});

Breadcrumbs::for('admin.auth.user.create', function ($trail) {
    // $trail->parent('admin.auth.user.index');
    $trail->push(__('labels.backend.access.users.create'), route('admin.auth.user.create'));
});

Breadcrumbs::for('admin.auth.user.show', function ($trail, $id) {
    $trail->parent('admin.auth.user.index');
    $trail->push(__('menus.backend.access.users.view'), route('admin.auth.user.show', $id));
});

Breadcrumbs::for('admin.auth.user.edit', function ($trail, $id) {
    $trail->parent('admin.auth.user.index');
    $trail->push(__('menus.backend.access.users.edit'), route('admin.auth.user.edit', $id));
});

Breadcrumbs::for('admin.auth.user.rate', function ($trail, $id) {
    $trail->parent('admin.auth.user.index');
    $trail->push('Rate & Evaluate', route('admin.auth.user.rate', $id));
});

Breadcrumbs::for('admin.auth.user.change-password', function ($trail, $id) {
    $trail->parent('admin.auth.user.index');
    $trail->push(__('menus.backend.access.users.change-password'), route('admin.auth.user.change-password', $id));
});

Breadcrumbs::for('admin.auth.allcompany.index', function ($trail) {
    $trail->push('Company Management', route('admin.auth.allcompany.index'));
});

Breadcrumbs::for('admin.auth.company.alljobs', function ($trail) {
    $trail->push('Jobs Management', route('admin.auth.company.alljobs'));
});

Breadcrumbs::for('admin.auth.company.jobs.reported', function ($trail) {
    $trail->push('Reported Jobs Management', route('admin.auth.company.jobs.reported'));
});

Breadcrumbs::for('admin.auth.featured-jobs.index', function ($trail) {
    $trail->push('Manage Featured Jobs', route('admin.auth.featured-jobs.index'));
});

Breadcrumbs::for('admin.auth.featured-jobs.create', function ($trail) {
    $trail->push('Manage Featured Jobs', route('admin.auth.featured-jobs.create'));
});

Breadcrumbs::for('admin.auth.featured-jobs.sequence', function ($trail) {
    $trail->push('Manage Featured Jobs', route('admin.auth.featured-jobs.sequence'));
});

Breadcrumbs::for('admin.auth.homepage-logos.index', function ($trail) {
    $trail->push('Manage Featured Logos', route('admin.auth.homepage-logos.index'));
});

Breadcrumbs::for('admin.auth.homepage-logos.create', function ($trail) {
    $trail->push('Manage Featured Logos', route('admin.auth.homepage-logos.create'));
});

Breadcrumbs::for('admin.auth.homepage-logos.edit', function ($trail) {
    $trail->push('Manage Featured Logos', route('admin.auth.homepage-logos.edit',['id']));
});

Breadcrumbs::for('admin.auth.homepage-logos.sequence', function ($trail) {
    $trail->push('Manage Featured Logos', route('admin.auth.homepage-logos.sequence'));
});

Breadcrumbs::for('admin.auth.location.import', function ($trail) {
    $trail->push('Location Import', route('admin.auth.location.import'));
});

Breadcrumbs::for('admin.auth.location.importFile', function ($trail) {
    $trail->push('Location Import', route('admin.auth.location.importFile'));
});

Breadcrumbs::for('admin.auth.company.create', function ($trail) {
    $trail->push('Create Company', route('admin.auth.company.create'));
});

Breadcrumbs::for('admin.auth.company.edit', function ($trail) {
    $trail->push('Edit Company', route('admin.auth.company.edit',['id']));
});

Breadcrumbs::for('admin.auth.company.job.create', function ($trail) {
    $trail->push('Create Job', route('admin.auth.company.job.create'));
});
Breadcrumbs::for('admin.auth.featured-gigs.index', function ($trail) {
    $trail->push('Management Featured Gigs', route('admin.auth.featured-gigs.index'));
});
Breadcrumbs::for('admin.auth.gigs.allgigs', function ($trail) {
    $trail->push('Gigs Management', route('admin.auth.gigs.allgigs'));
});
Breadcrumbs::for('admin.auth.gigs.all-reported-gigs', function ($trail) {
    $trail->push('Reported Gigs Management', route('admin.auth.gigs.all-reported-gigs'));
});
Breadcrumbs::for('admin.auth.gigs.create', function ($trail) {
    $trail->push('Gigs Management', route('admin.auth.gigs.create'));
});

Breadcrumbs::for('admin.auth.company.jobs.edit', function ($trail) {
    $trail->push('Edit Job', route('admin.auth.company.jobs.edit',['id']));
});

Breadcrumbs::for('admin.auth.referrals.create', function ($trail) {
    $trail->push('Referrals Management', route('admin.auth.referrals.create'));
});

Breadcrumbs::for('admin.auth.referrals.index', function ($trail) {
    $trail->push('Referrals Management', route('admin.auth.referrals.index'));
});

Breadcrumbs::for('admin.auth.referral.referral-edit', function ($trail) {
    $trail->push('Referrals Management', route('admin.auth.referral.referral-edit',['id']));
});

Breadcrumbs::for('admin.auth.permission.index', function ($trail) {
    $trail->push('Permission Management', route('admin.auth.permission.index'));
});