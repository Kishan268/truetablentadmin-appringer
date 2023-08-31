<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::get('/tt-workprofile', 'API\CompanyController@downloadWorkprofile');
Route::get('/', 'API\CommonController@index');
Route::post('login', 'API\UserController@login');
Route::post('resend_verification_email', 'API\UserController@resendConfirmEmail');
Route::get('google-login', 'API\SocialController@redirectToAuth');
Route::post('auth/google/callback', 'API\SocialController@handleAuthCallback');
Route::post('candidate_register', 'API\UserController@candidateRegister');
Route::post('company_register', 'API\UserController@companyRegister');
Route::post('forgot_password_send_otp', 'API\UserController@sendOtpForgotPassword');
Route::post('forgot_password', 'API\UserController@forgotPassword');
Route::get('master_data', 'API\CommonController@getMasterData');
Route::get('fetch_location', 'API\CommonController@getLocationsByNameOrPincode');
Route::get('fetch_skill', 'API\CommonController@getSkillsByName');
Route::get('locations', 'API\CommonController@getLocations');
Route::get('skills', 'API\CommonController@getSkills');
Route::post('add_skill', 'API\CommonController@addSkill');
Route::post('search_job', 'API\JobController@jobListing');
Route::post('gigs/list', 'API\GigController@gigListing');
Route::post('search_candidate', 'API\UserController@candidateListing');
Route::get('featured_jobs', 'API\JobController@getFeaturedJobs');
Route::get('featured_gigs', 'API\GigController@getFeaturedGigs');
Route::get('homepage_logos', 'API\CommonController@getHomepageLogos');

Route::get('giveaway_winners', 'API\CommonController@getGiveawayWinner');

Route::post('contact', 'API\CommonController@contact');

Route::get('email/verify/{id}', 'VerificationController@verify')->name('verification.verify');

Route::get('email/resend', 'VerificationController@resend')->name('verification.resend');

Route::get('job_details', 'API\JobController@jobDetails');
Route::get('gig/details/{id}', 'API\GigController@gigDetails');
Route::get('google_places', 'API\CommonController@getGooglePlaces');
Route::post('otp/verify', 'API\UserController@validateOtp');
Route::post('email/otp/verify', 'API\UserController@validateEmailOtp');

/* Graph Routes */

Route::post('search/candidates/graphs', 'API\GraphController@getData');

Route::get('get_company_details/{company_id}', 'API\CompanyController@companyDetails');
Route::post('account/restore', 'API\UserController@restoreAccount');
Route::get('company/recruiter/{id}', 'API\CompanyController@getRecruiterDetail');
 
Route::middleware('auth:api')->group(function () {
    Route::post('change_password', 'API\UserController@changePassword');
    Route::post('reset_password_otp', 'API\UserController@sendOtpResetPassword');
    Route::post('reset_password', 'API\UserController@resetPassword');
    Route::get('profile', 'API\UserController@profile');
    Route::get('get_company_list', 'API\UserController@getCompanyList');
    Route::post('block_unblock_company', 'API\UserController@blockUnblockCompany');
    Route::post('add_company', 'API\UserController@addCompany');
    Route::post('add_and_block_company', 'API\UserController@addAndBlockCompany');
    Route::post('update_profile', 'API\UserController@updateProfile');
    Route::post('update_preferences', 'API\UserController@updatePreferences');
    
    Route::post('upload_resume', 'API\UserController@uploadResume');
    Route::post('update_work_profile', 'API\UserController@updateWorkProfile');
    Route::post('account/delete', 'API\UserController@deleteAccount');
    

    
    Route::post('add_edit_job', 'API\JobController@addEdit');
    Route::post('apply_job', 'API\JobController@applyJob');
    Route::post('report_job', 'API\JobController@reportJob');
    Route::post('close_job', 'API\JobController@closeJob');
    Route::post('change_applicant_status', 'API\JobController@changeApplicantStatus');
    Route::get('get_my_jobs', 'API\JobController@getMyJobs');

    Route::post('renew_job', 'API\JobController@reNewJob');
    Route::post('duplicate_job', 'API\JobController@duplicateJob');
    Route::post('job/boost', 'API\ReferralController@addJobReferral');
    Route::post('job/referral/end/{referral_id}', 'API\ReferralController@endJobReferral');
    
    Route::post('change_job_status', 'API\JobController@changeJobStatus');
    
    Route::post('upload_media', 'API\CommonController@uploadMedia');

    Route::get('get_description', 'API\CommonController@getDescriptioByChatGPT');
    
    /*Company Admin Routes*/

    Route::get('company/dashboard', 'API\CompanyController@dashboard');
    Route::get('company_users', 'API\CompanyController@getCompanyUsers');
    Route::post('update_company_user_status', 'API\CompanyController@updateCompanyUserStatus');
    Route::post('update_company_user_role', 'API\CompanyController@updateCompanyUserRole');
    Route::post('add_edit_company_user', 'API\CompanyController@saveCompanyUser');
    Route::post('offline_payment', 'API\CompanyController@offlinePayment');
    Route::get('get_token', 'API\CompanyController@getToken');
    Route::post('online_payment', 'API\CompanyController@buyTTCash');
    Route::get('user_work_profile', 'API\CompanyController@userWorkProfile');
    Route::get('buy_evaluation/{user_id}', 'API\CompanyController@buyCandidateEvaluation');

    Route::post('add_edit_company', 'API\CompanyController@addEditCompany');

    Route::post('place_order', 'API\CompanyController@placeOrder');
    Route::post('verify_payment', 'API\CompanyController@verifyPayment');
    Route::post('export_transactions', 'API\CompanyController@exportTransactions');
    Route::get('company_reporting', 'API\GraphController@getCompanyReporting');
    

    Route::post('logout', 'API\UserController@logout');

    Route::prefix('chat')->group(function () {
        Route::post('message/send', 'API\MessageController@sendMessage');
        Route::get('get', 'API\MessageController@getChats');
        Route::get('messages/{id}', 'API\MessageController@getMessages');
        Route::get('media/{id}', 'API\MessageController@getChatMedia');
        Route::get('media/{id}', 'API\MessageController@getChatMedia');
        Route::post('update/{id}', 'API\MessageController@updateChat');
        Route::post('delete/{id}', 'API\MessageController@delete');
        Route::post('message/seen/update', 'API\MessageController@lastMessageUpdate');
    });

    Route::prefix('gig')->group(function () {
        Route::post('add_edit', 'API\GigController@addEdit');
        Route::post('close', 'API\GigController@closeGig');
        Route::get('my_gigs', 'API\GigController@getMyGigs');
        Route::post('status/update', 'API\GigController@changeGigStatus');
        Route::post('applicant/update', 'API\GigController@changeApplicantStatus');
        Route::post('apply', 'API\GigController@applyGig');
        Route::post('report', 'API\GigController@reportGig');
        Route::post('renew', 'API\GigController@reNewGig');
        Route::post('duplicate', 'API\GigController@duplicateJob');
    });

    Route::prefix('tracking')->group(function () {
        Route::post('add', 'API\ProfileTrackingController@addTracking');
    });

    Route::prefix('referrals')->group(function () {
        Route::get('list', 'API\ReferralController@getReferrals');
        Route::get('users', 'API\ReferralController@getReferralUsers');
        Route::post('invitation/send', 'API\ReferralController@sendInvitation');
    });


});
