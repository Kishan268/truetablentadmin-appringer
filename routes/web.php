<?php

use App\Http\Controllers\LanguageController;
use App\Http\Controllers\DataEntryController;
use App\Http\Controllers\AutoReminderController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\Backend\SystemConfigsController;
use App\Http\Controllers\Frontend\HomeController;

/*
 * Global Routes
 * Routes that are used between both frontend and backend.
 */

// Switch between the included languages
Route::get('lang/{lang}', [LanguageController::class, 'swap']);
Route::get('data-entry', [DataEntryController::class, 'insertData']);
Route::get('auto-reminder', [AutoReminderController::class, 'sendReminderEmail']);

Route::get('remove-skills', [HomeController::class, 'removeDuplicateSkills']);

Route::get('cron-jobs', [CronController::class, 'runCrons']);
Route::get('delete-users', [CronController::class, 'deleteUsers']);

// Route::get('mail', function () {
//     $user = App\Models\Auth\User::find(1);

//     return (new App\Notifications\Frontend\Auth\UserNeedsConfirmation($user->confirmation_code, $user->first_name))
//         ->toMail($user->email);
// });

/*
 * Frontend Routes
 * Namespaces indicate folder structure
 */
Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
    include_route_files(__DIR__ . '/frontend/');
});

/*
 * Backend Routes
 * Namespaces indicate folder structure
 */

Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    /*
     * These routes need view-backend permission
     * (good if you want to allow more than one group in the backend,
     * then limit the backend features by different roles or permissions)
     *
     * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
     * These routes can not be hit if the password is expired
     */
    Route::get('system/config', [SystemConfigsController::class, 'index'])->name('system-config.index');
    Route::get('system/config/list', [SystemConfigsController::class, 'systemConfigList'])->name('system-config.list');
    Route::get('system/config/{id}', [SystemConfigsController::class, 'edit'])->name('system-config.edit');
    Route::post('system/config/update', [SystemConfigsController::class, 'update'])->name('system-config.update');
    include_route_files(__DIR__ . '/backend/');

});
