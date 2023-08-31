<?php

use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\User\AccountController;
use App\Http\Controllers\Frontend\User\DashboardController;
use App\Http\Controllers\Frontend\User\ProfileController;
use App\Http\Controllers\Frontend\User\CandidateController;
use App\Http\Controllers\Frontend\User\CompanyController;
use App\Http\Controllers\Frontend\Auth\LoginController;

use App\Models\SystemSettings;
/*
 * Frontend Controllers
 * All route names are prefixed with 'frontend.'.
 */
//Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

Route::get('/', [HomeController::class, 'index'])->name('index');

Route::group(['middleware' => ['auth', 'password_expires']], function () {
    Route::group(['namespace' => 'User', 'as' => 'user.'], function () {
        Route::get('account', [AccountController::class, 'index'])->name('account');
        Route::patch('profile/update', [ProfileController::class, 'update'])->name('profile.update');
    });
});