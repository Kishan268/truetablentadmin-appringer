<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auth\User;
use App\Config\AppConfig;
use App\Mail\SendAccountVerificationReminderEmail;
use App\Mail\SendIncompleteProfileReminderEmail;
use Illuminate\Support\Facades\Mail;

class AutoReminderController extends Controller
{
    public function sendReminderEmail()
    {
    	$reminder_after_days = AppConfig::getReminderAfterDays();
    	$past_date = date('Y-m-d H:i:s', strtotime('-'.$reminder_after_days.' days'));


    	// $users = User::where('email_verified_at',null)->where('created_at','<',$past_date)->get();
    	// foreach ($users as $user) {
     //        $to_email = $this->getEmail($user);
    	// 	Mail::to($to_email)->send(new SendAccountVerificationReminderEmail($this->getMailData($user)));
    	// }

    	$incomplete_user_profiles = User::with('userWorkProfile')->where('users.avatar_location',null)->orWere('users.avatar_location','')->orWhere('user_work_profile_details.summary',null)->orWhere('user_work_profile_details.summary','')->orWhere('user_work_profile_details.cv_link',null)->orWhere('user_work_profile_details.cv_link','')->where('users.updated_at','<',$past_date)->get();

    	foreach ($incomplete_user_profiles as $user) {
            $to_email = $this->getEmail($user);
    		Mail::to($to_email)->send(new SendIncompleteProfileReminderEmail($this->getMailData($user)));
    	}

    	$incomplete_complete_profiles = User::with('companyDetails')->where('companies.name',null)->orWhere('companies.name','')->orWhere('companies.logo',null)->orWhere('companies.logo','')->orWhere('companies.cover_pic',null)->orWhere('companies.cover_pic','')->orWhere('companies.facebook',null)->orWhere('companies.facebook','')->orWhere('companies.instagram',null)->orWhere('companies.instagram','')->orWhere('companies.linkedin',null)->orWhere('companies.linkedin','')->orWhere('companies.twitter',null)->orWhere('companies.twitter','')->where('companies.updated_at','<',$past_date)->get();

    	foreach ($incomplete_complete_profiles as $user) {
            $to_email = $this->getEmail($user);
    		Mail::to($to_email)->send(new SendIncompleteProfileReminderEmail($this->getMailData($user)));
    	}
    }

    public function getEmail($user)
    {
        $admin_email = AppConfig::getAdminEmail();
        if (env('APP_DEBUG',true)) {
            return $admin_email;
        }

        return $user->email;
    }

    public function getMailData($user)
    {
        return ['first_name'=>$user->first_name,'last_name'=>$user->last_name];
    }
}
