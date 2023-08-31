<?php

use App\Http\Controllers\Backend\DashboardController;

// All route names are prefixed with 'admin.'.
Route::redirect('/', '/admin/dashboard', 301);
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('settings', [DashboardController::class, 'system_settings'])->name('system_settings');
Route::post('settings', [DashboardController::class, 'system_settings'])->name('system_settings.save');
Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
Route::post('candicates-data', [DashboardController::class, 'candicatesData'])->name('candicates-data');
Route::get('dashboard/job-gigs', [DashboardController::class, 'JobsAndGigsData'])->name('dashboard.jobsGigs');
Route::get('dashboard/companies', [DashboardController::class, 'companiesData'])->name('dashboard.companies');
Route::get('footer-content', [App\Http\Controllers\Backend\FooterDynamicController::class, 'footerContent'])->name('footer_content');
Route::post('footer-content', [App\Http\Controllers\Backend\FooterDynamicController::class, 'footerContent'])->name('footer_content.save');
Route::get('popup-management', [App\Http\Controllers\Backend\PopupManagementController::class, 'popupManagement'])->name('popup_management');
Route::post('popup-management', [App\Http\Controllers\Backend\PopupManagementController::class, 'popupManagement'])->name('popup_management.save');
Route::get('fetch_skill', [App\Http\Controllers\API\CommonController::class,'getSkillsByName'])->name('fetch_skill');
Route::get('fetch_location', [App\Http\Controllers\API\CommonController::class,'getLocationsByNameOrPincode'])->name('fetch_location');
