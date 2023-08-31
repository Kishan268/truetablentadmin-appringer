<?php

use App\Http\Controllers\Backend\Auth\Role\RoleController;
use App\Http\Controllers\Backend\Auth\User\UserConfirmationController;
use App\Http\Controllers\Backend\Auth\User\UserController;
use App\Http\Controllers\Backend\Auth\User\UserPasswordController;
use App\Http\Controllers\Backend\Auth\User\UserSessionController;
use App\Http\Controllers\Backend\Auth\User\UserSocialController;
use App\Http\Controllers\Backend\Auth\User\UserStatusController;

use App\Http\Controllers\Backend\CompanyController;
use App\Http\Controllers\Backend\RolePermissionController;
use App\Http\Controllers\Backend\Auth\Referral\ReferralController;
use App\Http\Controllers\Backend\NotificationSettingsController;

// All route names are prefixed with 'admin.auth'.


Route::group([
    'prefix' => 'auth',
    'as' => 'auth.',
    'namespace' => 'Auth',
    'middleware' => 'auth'
], function () {
    // User Management

    Route::group(['namespace' => 'User'], function () {
        // User Status'
        Route::get('user/deactivated', [UserStatusController::class, 'getDeactivated'])->name('user.deactivated');
        Route::get('user/deactivated', [UserStatusController::class, 'getDeleted'])->name('user.deleted');

        // User CRUD
        Route::get('user', [UserController::class, 'index'])->name('user.index');
        Route::get('user-list', [UserController::class, 'userList'])->name('user.list');
        Route::post('user-export', [UserController::class, 'exportUsers'])->name('user.export');
        Route::get('user/create', [UserController::class, 'create'])->name('user.create');
        Route::post('user', [UserController::class, 'store'])->name('user.store');

        Route::get('user/import', [UserController::class, 'import'])->name('user.import');
        Route::get('user/resumes/delete', [UserController::class, 'cleanResumesDirectory'])->name('user.resumes.delete');
        Route::post('user/import', [UserController::class, 'importFile'])->name('user.importFile');
        Route::get('user/export', [UserController::class, 'exportUsers'])->name('user.exportFile');
        // Specific User
        Route::group(['prefix' => 'user/{user}'], function () {
            // User
            Route::get('/', [UserController::class, 'show'])->name('user.show');
            Route::get('edit', [UserController::class, 'edit'])->name('user.edit');
            Route::get('rate', [UserController::class, 'rate'])->name('user.rate');
            Route::post('saveEvaluation', [UserController::class, 'saveEvaluation'])->name('user.saveEvaluation');
            Route::patch('/', [UserController::class, 'update'])->name('user.update');
            Route::get('/deactivate', [UserController::class, 'destroy'])->name('user.destroy');

            // Account
            Route::get('account/confirm/resend', [UserConfirmationController::class, 'sendConfirmationEmail'])->name('user.account.confirm.resend');

            // Status
            Route::get('mark/{status}', [UserStatusController::class, 'mark'])->name('user.mark')->where(['status' => '[0,1]']);

            // Social
            Route::delete('social/{social}/unlink', [UserSocialController::class, 'unlink'])->name('user.social.unlink');

            // Confirmation
            Route::get('confirm', [UserConfirmationController::class, 'confirm'])->name('user.confirm');
            Route::get('unconfirm', [UserConfirmationController::class, 'unconfirm'])->name('user.unconfirm');

            // Password
            Route::get('password/change', [UserPasswordController::class, 'edit'])->name('user.change-password');
            Route::patch('password/change', [UserPasswordController::class, 'update'])->name('user.change-password.post');

            // Session
            Route::get('clear-session', [UserSessionController::class, 'clearSession'])->name('user.clear-session');

            // Deleted
            Route::get('delete', [UserStatusController::class, 'delete'])->name('user.delete-permanently');
            Route::get('restore', [UserStatusController::class, 'restore'])->name('user.restore');
        });
    });

    Route::group(['namespace' => 'Company'], function () {

        // Company Management
        Route::get('allcompany', [CompanyController::class, 'index'])->name('allcompany.index');
        Route::get('alljobs', [CompanyController::class, 'jobs'])->name('company.alljobs');
        Route::get('jobs/reportedjob', [CompanyController::class, 'reportedJobs'])->name('company.jobs.reported');
        Route::post('jobs/block', [CompanyController::class, 'jobBlock'])->name('company.jobs.block');
        Route::post('addCash', [CompanyController::class, 'addCash'])->name('company.addCash');
        Route::get('payments', [CompanyController::class, 'paymentsList'])->name('company.payments');
        Route::get('company/create', [CompanyController::class, 'create'])->name('company.create');
        Route::post('company/store', [CompanyController::class, 'store'])->name('company.store');
        Route::get('company/edit/{id}', [CompanyController::class, 'edit'])->name('company.edit');
        Route::post('company/update', [CompanyController::class, 'update'])->name('company.update');

        // Route::get('user/create', [UserController::class, 'create'])->name('user.create');
        // Route::post('user', [UserController::class, 'store'])->name('user.store');
        Route::post('jobs/deactivate', [CompanyController::class, 'deactivateJob'])->name('company.jobs.deactivate');
        Route::post('company/deactivate', [CompanyController::class, 'deactivateCompany'])->name('company.deactivate');
        Route::post('company/delete/data', [CompanyController::class, 'deleteCompanyData'])->name('company.delete-data');
        Route::post('company/delete', [CompanyController::class, 'deleteCompany'])->name('company.delete');
        Route::post('company/restore', [CompanyController::class, 'restoreCompany'])->name('company.restore');

        Route::get('jobs/edit/{id}', [CompanyController::class, 'draftJobEdit'])->name('company.jobs.edit');
        Route::get('jobs/duplicate/{id}', [CompanyController::class, 'duplicateJob'])->name('company.jobs.duplicate');
        Route::post('jobs/edit/update', [CompanyController::class, 'draftJobUpdate'])->name('company.job.update');

        Route::get('company/job/create', [CompanyController::class, 'createJob'])->name('company.job.create');
        Route::post('company/job/store', [CompanyController::class, 'storeJob'])->name('company.job.store');
        Route::post('company/exports', [CompanyController::class, 'exportCompanies'])->name('company.exports');

        Route::post('company/job/description', [CompanyController::class, 'getDescriptioByChatGPT'])->name('company.job.description');
        Route::post('company/job/get-company-users', [CompanyController::class, 'getCompanyUsers'])->name('company.job.get-company-users');
        Route::post('company/job/add-skills', [CompanyController::class, 'addSkills'])->name('company.add-skills');

        Route::get('company-list', [CompanyController::class, 'companyList'])->name('company.list');
        Route::get('jobs-list', [CompanyController::class, 'jobsList'])->name('company.jobs-list');
        Route::get('reported-jobs-list', [CompanyController::class, 'ReportedJobsList'])->name('company.reported-jobs-list');
        Route::get('payment-list-pagination', [CompanyController::class, 'paymentsListPagination'])->name('company.payment-list-pagination');
        Route::post('jobs/jobs-exports', [CompanyController::class, 'exportJobs'])->name('jobs.exports');


    });

    // Role Management
    Route::group(['namespace' => 'Role'], function () {
        Route::get('role', [RoleController::class, 'index'])->name('role.index');
        Route::get('role/edit/{id}', [RoleController::class, 'edit'])->name('role.edit');
        Route::post('role/destroy', [RoleController::class, 'edit'])->name('role.destroy');
        Route::post('role/update', [RoleController::class, 'edit'])->name('role.update');
        Route::post('role/store', [RoleController::class, 'store'])->name('role.store');
    });

    // Permission Management
    Route::get('permission', [RolePermissionController::class, 'index'])->name('permission.index');
    Route::post('permission/store', [RolePermissionController::class, 'store'])->name('permission.store');
    Route::post('permission/assignAllPermission', [RolePermissionController::class, 'assignAllPermission'])->name('permission.assignAllPermission');
    Route::post('permission/removeAllPermission', [RolePermissionController::class, 'removeAllPermission'])->name('permission.removeAllPermission');
    Route::post('permission/showMembers', [RolePermissionController::class, 'showMembers'])->name('permission.showMembers');
    Route::post('permission/create', [RolePermissionController::class, 'create'])->name('permission.create');
    Route::post('permission/storeRole', [RolePermissionController::class, 'storeRole'])->name('permission.storeRole');
    Route::post('permission/deleteRole', [RolePermissionController::class, 'deleteRole'])->name('permission.deleteRole');
    Route::post('permission/assignRole', [RolePermissionController::class, 'assignRole'])->name('permission.assignRole');
    Route::post('permission/deleteRoleMembers', [RolePermissionController::class, 'deleteRoleMembers'])->name('permission.deleteRoleMembers');

    // Referral Management
    Route::group(['namespace' => 'Referral'], function () {
        Route::resource('referrals', 'ReferralController');
        Route::get('referral-list', [ReferralController::class, 'referralList'])->name('referral.list');
        Route::get('referral/edit/{id}', [ReferralController::class, 'edit'])->name('referral.referral-edit');
        Route::post('referral/update/{id}', [ReferralController::class, 'update'])->name('referral.referral-update');
    });

    // Referral Management
    Route::group(['namespace' => 'Notification'], function () {
        Route::get('notification', [NotificationSettingsController::class, 'index'])->name('notification.index');
        Route::get('notification/create', [NotificationSettingsController::class, 'create'])->name('notification.create');
        Route::post('notification/store', [NotificationSettingsController::class, 'store'])->name('notification.store');
        Route::get('notification/list', [NotificationSettingsController::class, 'getNotificationList'])->name('notification.list');
        Route::get('notification/edit/{id}', [NotificationSettingsController::class, 'edit'])->name('notification.edit');
        Route::post('notification/update', [NotificationSettingsController::class, 'update'])->name('notification.update');
    });

    /* Featured Jobs Routes */
        Route::get('featured-jobs', [App\Http\Controllers\Backend\Auth\HomePageSettings\HomePageSettingsController::class, 'index'])->name('featured-jobs.index');
        Route::post('featured-jobs/delete', [App\Http\Controllers\Backend\Auth\HomePageSettings\HomePageSettingsController::class, 'deleteJob'])->name('featured-jobs.delete');
        Route::get('featured-jobs/create', [App\Http\Controllers\Backend\Auth\HomePageSettings\HomePageSettingsController::class, 'createJob'])->name('featured-jobs.create');
        Route::post('featured-jobs/store', [App\Http\Controllers\Backend\Auth\HomePageSettings\HomePageSettingsController::class, 'storeJob'])->name('featured-jobs.store');
        Route::post('featured-jobs/order_change', [App\Http\Controllers\Backend\Auth\HomePageSettings\HomePageSettingsController::class, 'orderChange'])->name('featured-jobs.order_change');
        Route::get('featured-jobs/sequence', [App\Http\Controllers\Backend\Auth\HomePageSettings\HomePageSettingsController::class, 'sequenceJobs'])->name('featured-jobs.sequence');
        Route::post('featured-jobs/sequence/update', [App\Http\Controllers\Backend\Auth\HomePageSettings\HomePageSettingsController::class, 'updateJobsSequence'])->name('featured-jobs.sequence.update');
        Route::get('featured-jobs/company-jobs', [App\Http\Controllers\Backend\Auth\HomePageSettings\HomePageSettingsController::class, 'companyJobs'])->name('featured-jobs.company-jobs');
        Route::get('featured-jobs/all-featuredjobs-list', [App\Http\Controllers\Backend\Auth\HomePageSettings\HomePageSettingsController::class, 'allFeaturedJobsList'])->name('featured-jobs.all-featuredjobs-list');
    /* Homepage logos Routes */
        Route::get('homepage-logos', [App\Http\Controllers\Backend\Auth\HomePageSettings\HomePageSettingsController::class, 'logos'])->name('homepage-logos.index');
        Route::get('homepage-logos/create', [App\Http\Controllers\Backend\Auth\HomePageSettings\HomePageSettingsController::class, 'createLogo'])->name('homepage-logos.create');
        Route::post('homepage-logos/store', [App\Http\Controllers\Backend\Auth\HomePageSettings\HomePageSettingsController::class, 'storeLogo'])->name('homepage-logos.store');
        Route::post('homepage-logos/delete', [App\Http\Controllers\Backend\Auth\HomePageSettings\HomePageSettingsController::class, 'deleteLogo'])->name('homepage-logos.delete');
        Route::get('homepage-logos/sequence', [App\Http\Controllers\Backend\Auth\HomePageSettings\HomePageSettingsController::class, 'sequenceLogos'])->name('homepage-logos.sequence');
        Route::post('homepage-logos/sequence/update', [App\Http\Controllers\Backend\Auth\HomePageSettings\HomePageSettingsController::class, 'updateLogosSequence'])->name('homepage-logos.sequence.update');
        Route::post('homepage-logos/sequence/update', [App\Http\Controllers\Backend\Auth\HomePageSettings\HomePageSettingsController::class, 'updateLogosSequence'])->name('homepage-logos.sequence.update');
        Route::get('homepage-logos/edit/{id}', [App\Http\Controllers\Backend\Auth\HomePageSettings\HomePageSettingsController::class, 'editLogo'])->name('homepage-logos.edit');
        Route::post('homepage-logos/update/{id}', [App\Http\Controllers\Backend\Auth\HomePageSettings\HomePageSettingsController::class, 'updateLogo'])->name('homepage-logos.update');
        Route::post('homepage-logos/logo_order_change', [App\Http\Controllers\Backend\Auth\HomePageSettings\HomePageSettingsController::class, 'logoOrderChange'])->name('homepage-logos.logo_order_change');
        Route::get('homepage-logos/logos-company-list', [App\Http\Controllers\Backend\Auth\HomePageSettings\HomePageSettingsController::class, 'logosCompanyList'])->name('homepage-logos.logos-company-list');
        Route::post('homepage-logos/store-company-logo', [App\Http\Controllers\Backend\Auth\HomePageSettings\HomePageSettingsController::class, 'storeCompanyLogo'])->name('homepage-logos.store-company-logo');
    /* Featured Gigs Routes */
    
    // Route::resource('featured-gigs', [FeaturedGigsController::class]);
    Route::get('featured-gigs', [App\Http\Controllers\Backend\Auth\HomePageSettings\FeaturedGigsController::class,'index'])->name('featured-gigs.index');
    Route::post('featured-gigs/store', [App\Http\Controllers\Backend\Auth\HomePageSettings\FeaturedGigsController::class,'store'])->name('featured-gigs.store');
    Route::post('featured-gigs/delete', [App\Http\Controllers\Backend\Auth\HomePageSettings\FeaturedGigsController::class,'destroy'])->name('featured-gigs.delete');
    Route::post('featured-gigs/gig_order_change', [App\Http\Controllers\Backend\Auth\HomePageSettings\FeaturedGigsController::class, 'gigsOrderChange'])->name('featured-gigs.gig_order_change');
    Route::get('featured-gigs/all-gigs-list', [App\Http\Controllers\Backend\Auth\HomePageSettings\FeaturedGigsController::class, 'allGigsList'])->name('featured-gigs.all-gigs-list');

    Route::get('gigs/allgigs', [App\Http\Controllers\Backend\Auth\HomePageSettings\FeaturedGigsController::class,'allgigs'])->name('gigs.allgigs');
    Route::get('gigs/gigs-particular-section-list', [App\Http\Controllers\Backend\Auth\HomePageSettings\FeaturedGigsController::class,'gigsParticularSectionList'])->name('gigs.gigs-particular-section-list');
    Route::post('gigs/deactivate', [App\Http\Controllers\Backend\Auth\HomePageSettings\FeaturedGigsController::class, 'deactivateGig'])->name('gigs.deactivate');
    Route::get('gigs/duplicate/{id}', [App\Http\Controllers\Backend\Auth\HomePageSettings\FeaturedGigsController::class, 'duplicateGig'])->name('company.gigs.duplicate');
    
    //Reported gigs................
    Route::get('gigs/all-reported-gigs', [App\Http\Controllers\Backend\Auth\HomePageSettings\FeaturedGigsController::class,'allReportedGigs'])->name('gigs.all-reported-gigs');
    Route::get('gigs/reported-gigs-section-list', [App\Http\Controllers\Backend\Auth\HomePageSettings\FeaturedGigsController::class,'reportedGigsSectionList'])->name('gigs.reported-gigs-section-list');
    Route::post('gigs/deactivate-reported-gig', [App\Http\Controllers\Backend\Auth\HomePageSettings\FeaturedGigsController::class, 'deactivateReportedGig'])->name('gigs.deactivate-reported-gig');
    Route::post('gigs/exports', [App\Http\Controllers\Backend\Auth\HomePageSettings\FeaturedGigsController::class, 'exportGigs'])->name('gigs.exports');
    Route::get('gigs/create/{id?}', [App\Http\Controllers\Backend\Auth\HomePageSettings\FeaturedGigsController::class, 'createGig'])->name('gigs.create');
    Route::post('gigs/add-edit', [App\Http\Controllers\Backend\Auth\HomePageSettings\FeaturedGigsController::class, 'gigAddEdit'])->name('gigs.add-edit');
    Route::get('gigs/re-new-gig/{id}', [App\Http\Controllers\Backend\Auth\HomePageSettings\FeaturedGigsController::class, 'reNewGig'])->name('gigs.re-new-gig');
    // Route::get('gigs/edit/{id}', [App\Http\Controllers\Backend\Auth\HomePageSettings\FeaturedGigsController::class, 'gigEdit'])->name('gigs.edit');


    /* Blocked Domains Routes */

        Route::get('blocked-domains', [App\Http\Controllers\Backend\Auth\HomePageSettings\BlockedDomainController::class, 'index'])->name('blocked-domains.index');
        

        Route::get('location/import', [App\Http\Controllers\Backend\SettingController::class, 'locationImport'])->name('location.import');
        Route::post('location/import-file', [App\Http\Controllers\Backend\SettingController::class, 'locationImportFile'])->name('location.importFile');
});
