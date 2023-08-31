<?php

namespace App\Models\Auth;

use App\Models\Auth\Traits\Attribute\UserAttribute;
use App\Models\Auth\Traits\Method\UserMethod;
use App\Models\Auth\Traits\Relationship\UserRelationship;
use App\Models\Auth\Traits\Scope\UserScope;
use App\Models\Locations;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Auth\UserDetails;
use App\Models\UserWorkProfile;
use App\Models\UserWorkProfileDetail;
use App\Models\CandidateProfileTracking;
use App\Notifications\Frontend\Auth\UserNeedsConfirmation;
use App\Helpers\SiteHelper;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Frontend\OtpForgotPasswordNotification;
/**
 * Class User.
 */
class User extends BaseUser
{
    use UserAttribute,
        UserMethod,
        UserRelationship,
        UserScope,
        HasApiTokens;

    protected $hidden = ['uuid', 'password', 'password_changed_at', 'confirmation_code', 'last_login_at', 'last_login_ip', 'to_be_logged_out', 'otp', 'avatar_type', 'remember_token'];

    public function workProfile()
    {
        return $this->hasOne('App\Models\WorkProfile');
    }

    public function userWorkProfile()
    {
        return $this->hasOne('App\Models\UserWorkProfile');
    }

    public function userWorkProfileDetail()
    {
        return $this->hasManyThrough('App\Models\UserWorkProfileDetail', 'App\Models\UserWorkProfile');
    }

    public function userPrefferedData()
    {
        return $this->hasMany('App\Models\UserPrefferedData');
    }

    public function details()
    {
        return $this->hasOne('App\Models\Auth\UserDetails');
    }

    public function companyDetails()
    {
        return $this->hasOne('App\Models\Companies', 'id', 'company_id');
    }

    public function blockedCompanies()
    {
        return $this->belongsToMany('App\Models\Companies', 'blocked_companies', 'candidate_id', 'company_id');
    }

    public function jobs(){
        return $this->hasMany('App\Models\Job');
    }

    public function gigs(){
        return $this->hasMany('App\Models\CompanyGig');
    }

    // public function getPreferredLocationAttribute(){
    //     $loc = null; $prefLocation = '';
    //     if($this->details){
    //         $prefLocation = $this->details->preferred_location;
    //     }
    //     if(strlen(trim($prefLocation)) > 0){
    //         $loc = Locations::where('id', $prefLocation)->get()->map(function($q){
    //             return ['id' => $q->id, 'text' => sprintf('%s, %s', $q->city, $q->state)];
    //         })->toArray()[0];
    //     }
    //     return $loc;
    // }

    // public function getPrefLocationAttribute(){
    //     $loc = null; $prefLocation = '';
    //     if($this->details){
    //         $prefLocation = $this->details->preferred_location;
    //     }
    //     $userLocation = $this->location;
    //     if(strlen(trim($prefLocation)) > 0){
    //         $loc = Locations::where('id', $prefLocation)->get()->map(function($q){
    //             return ['id' => $q->id, 'text' => sprintf('%s, %s', $q->city, $q->state)];
    //         })->toArray()[0];
    //     }else if(strlen(trim($userLocation)) > 0){
    //         $userCity = explode(',', $userLocation);
    //         $userState = explode(' ', trim($userCity[1]));
    //         // if(count($userState) > 1){ $userZip = $userState[1]; }
    //         $userState = $userState[0];
    //         $userCity = $userCity[0];
    //         $loc = Locations::where(['city' => $userCity, 'state' => $userState])->get()->map(function($q){
    //             return ['id' => $q->id, 'text' => sprintf('%s, %s', $q->city, $q->state)];
    //         })->toArray()[0];
    //     }else{
    //         $loc = Locations::where(['city' => 'New York'])->get()->map(function($q){
    //             return ['id' => $q->id, 'text' => sprintf('%s, %s', $q->city, $q->state)];
    //         })->toArray()[0];
    //     }
    //     // if($loc == null){
    //         // $ip = $_SERVER['REMOTE_ADDR'];
    //         // $ip = '8.8.8.8';
    //         // $locDetails = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"), true);
    //         // if(array_key_exists('city', $locDetails) && array_key_exists('region', $locDetails) && array_key_exists('postal', $locDetails)){
    //         //     $loc = Locations::where('city', 'like', sprintf('%%s%', $locDetails['city']))->where('state', 'like', sprintf('%%s%', $locDetails['region']))->orWhere('zipcode', $locDetails['postal'])->limit(1)->get()->map(function($q){
    //         //         return ['id' => $q->id, 'text' => sprintf('%s, %s %s', $q->city, $q->state, $q->zipcode)];
    //         //     })->toArray();
    //         //     if(count($loc) > 0){
    //         //         $loc = $loc[0];
    //         //     }
    //         // }
    //     // }
    //     return $loc;
    // }


    public function getIsProfileViewedByUserAttribute(){
        if (Auth::guard('api')->user()) {
            $user_id    = Auth::guard('api')->user()->id;
            $company_id     = Auth::guard('api')->user()->company_id;
            if ($company_id != null && $company_id != '') {
                return CandidateProfileTracking::isProfileViewedByUser($user_id,$this->id,'1','0');
            } else {

                return false;
            }
        }else {

            return false;
        }
    }

    public function getIsProfileViewedByCompanyUserAttribute(){
        if (Auth::guard('api')->user()) {
            $user_id    = Auth::guard('api')->user()->id;
            $company_id     = Auth::guard('api')->user()->company_id;
            if ($company_id != null && $company_id != '') {
                return CandidateProfileTracking::isProfileViewedByCompanyUser($company_id,$this->id,'1','0');
            } else {

                return false;
            }
        }else {

            return false;
        }
    }


    public function getBlockedCompaniesListAttribute()
    {
        $blocked = $this->blockedCompanies->map(function ($q) {
            return ['id' => $q->id, 'name' => $q->name];
        });
        return $blocked;
    }

    public function getUidAttribute()
    {
        if ($this->isCandidate()) {
            return 'TT-' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
        } else if ($this->isCompanyAdmin() || $this->isCompanyUser()) {
            return 'TC-' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
        } else if ($this->isEvaluator()) {
            return 'TE-' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
        }
    }


    public function view_transactions()
    {
        return $this->hasMany('App\Models\ProfileViewTransactions', 'user_id', 'id')->user();
    }

    public function getRemainingViewsAttribute()
    {
        // dd($this->view_transactions);
        $remaining = 0;
        if ($this->view_transactions->last() && $this->view_transactions->last() != null) {
            $remaining = $this->view_transactions->last()->remaining;
        }
        return $remaining;
    }

    public function getRoleTypeAttribute()
    {
        if ($this->isCompanyAdmin()) return "CA";
        elseif ($this->isCompanyUser()) return "CU";
        elseif ($this->isCandidate()) return "CD";
        elseif ($this->isEvaluator()) return "EV";
        else return "CD";
    }

    public static function updatePassword($password, $user_id)
    {
        try {
            $update_password = User::where('id', $user_id)->update(['password' => Hash::make($password)]);
            if ($update_password)
                return true;
            else
                return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function getUserProfile($user_id)
    {
        $user_data = User::getUserById($user_id);
        // $user_data->preferences  = UserDetails::getUserDetails($user_id);
        return $user_data;
    }

    public static function verifyOtp($otp, $user_id)
    {
        try {

            $verify_otp = User::where('id', $user_id)->where('otp', $otp)->first();
            if (!empty($verify_otp))
                return true;
            else
                return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function createUser($data,$isSendConfirmation = true)
    {
        $email_otp = rand(1000, 9999);
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email'     => $data['email'],
            'company_id' => isset($data['company_id']) ? $data['company_id'] : NULL,
            'contact' => isset($data['contact']) ? $data['contact'] : NULL,
            'password' => Hash::make($data['password']),
            'otp' => '1234',
            'email_otp' => $email_otp,
        ]);

        $is_company_user = isset($data['company_id']) ? true : false;
        $email_otp = isset($data['company_id']) ? null : $email_otp;
        if ($isSendConfirmation) {
            try {

                $user->notify(new UserNeedsConfirmation($user->confirmation_code, $user->first_name . ' ' . $user->last_name, null, $is_company_user,null,$email_otp));
                // $data1 = [
                //     'name' => $user->first_name . ' ' . $user->last_name,
                //     'password' => '', 
                //     'is_company_user' => $is_company_user
                // ];
                // $subject = app_name() . ': ' . __('exceptions.frontend.auth.confirmation.confirm');
                // $template = 'frontend.mail.confirm';
                // $user->notify(new UserNeedsConfirmation($subject,$data1, $template));

            } catch (\Swift_TransportException $ex) {
            } catch (\Exception $ex) {
            }
        }
        return $user;
    }

    public static function insertOtp($user_id, $otp)
    {
        try {
            $insert_otp = User::where('id', $user_id)->update([
                'otp' => $otp,
            ]);

            if ($insert_otp)
                return true;
            else
                return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function flushOtp($user_id)
    {
        try {
            $flush_otp = User::where('id', $user_id)->update([
                'otp' => null,
            ]);

            if ($flush_otp)
                return true;
            else
                return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function getUserByEmail($email)
    {
        try {
            $user = User::select('id', 'company_id', 'first_name', 'last_name', 'designation', 'email')->where('email', $email)->first();
            if ($user)
                return $user;
            else
                return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function getUserById($id)
    {
        try {
            $user = User::select('id', 'company_id', 'first_name', 'last_name', 'designation', 'email', 'contact', 'date_of_birth', 'min_salary', 'notification_new_jobs', 'notification_profile_viewed')->where('id', $id)->first();
            if ($user)
                return $user;
            else
                return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function updateUser($data, $user_id)
    {
        try {

            $user = User::where('id', $user_id)->update($data);

            return User::getUserById($user_id);
        } catch (Exception $e) {
            return false;
        }
    }

    public static function updateAvatar($filename, $user_id)
    {
        $status = false;
        $User = User::find($user_id);
        if ($User->count()) {
            if ($User->update(['avatar_location' => $filename])) {
                $status = true;
            }
        }

        return $status;
    }

    public static function updateCompanyUserStatus($loggedin_user, $user_id)
    {
        $status = false;
        $user = User::withTrashed()->where(['company_id' => $loggedin_user->company_id, 'id' => $user_id])->first();
        if ($user != null) {
            if ($user->deleted_at == null) {
                $user->delete();
            } else {
                $user->restore();
            }
            $status = true;
        }
        return $status;
    }

    public static function updateCompanyUserRole($loggedin_user, $user_id)
    {
        $status = false;
        $user = User::withTrashed()->where(['company_id' => $loggedin_user->company_id, 'id' => $user_id])->first();
        if ($user != null) {
            if ($user->isCompanyAdmin()) {
                $user->assignRole(config('access.users.company_user_role'));
                $user->removeRole(config('access.users.company_admin_role'));
                $resp = 'Hiring Specialist';
            } else {
                $user->assignRole(config('access.users.company_admin_role'));
                $user->removeRole(config('access.users.company_user_role'));
                $resp = 'Administrator';
            }
            $status = true;
        }
        return $status;
    }

    public static function getUserProfileProgress($user)
    {
        $progress = 0;
        $total_experience = 0;
        if (SiteHelper::isNotEmpty($user->first_name)) {
            $progress = $progress + 8;
        }
        if (SiteHelper::isNotEmpty($user->last_name)) {
            $progress = $progress + 8;
        }

        if (SiteHelper::isNotEmpty($user->email)) {
            $progress = $progress + 8;
        }

        if (SiteHelper::isNotEmpty($user->avatar_location)) {
            $progress = $progress + 6;
        }


        if (UserWorkProfile::checkUserProfileExist($user->id)) {
            $work_profile = UserWorkProfile::getDataById(UserWorkProfile::getWorkProfileId($user->id));

            if (SiteHelper::isNotEmpty($work_profile->contact_number)) {
                $progress = $progress + 8;
            }
            if (SiteHelper::isNotEmpty($work_profile->location_name)) {
                $progress = $progress + 8;
            }
            if (SiteHelper::isNotEmpty($work_profile->joining_preference_id) || $user->isEvaluator()) {
                $progress = $progress + 8;
            }
            if (SiteHelper::isNotEmpty($work_profile->summary)) {
                $progress = $progress + 8;
            }
            if (SiteHelper::isNotEmpty($work_profile->cv_link)) {
                $progress = $progress + 8;
            }
            if (SiteHelper::isNotEmpty($work_profile->total_experience) && $work_profile->total_experience > 0) {
                // $progress = $progress + 8;
                $total_experience = SiteHelper::isNotEmpty($work_profile->total_experience) ? $work_profile->total_experience : 0;
            }
            $progress = $progress + 8;
        }

        if (UserWorkProfileDetail::checkUserProfileExist($user->id)) {
            $skills = UserWorkProfileDetail::getDataByType($user->id, 'skill');
            if ($skills->count() > 0){
                $progress = $progress + 8;
            }

            $degree = UserWorkProfileDetail::getDataByType($user->id, 'degree');
            if ($degree->count() > 0){
                $progress = $progress + 8;
            }

            if (isset($work_profile) && $work_profile->total_experience > 0) {
                $experience = UserWorkProfileDetail::getDataByType($user->id, 'experience');
                if ($experience->count() > 0){
                    $progress = $progress + 6;
                }
            }else{
                $progress = $progress + 6;
            }
            
        }elseif ($total_experience == 0) {
            $progress = $progress + 6;
        }
        return $progress;
    }

    public function getAvatarLocationAttribute($value)
    {
        return ($value != null && $value != '') ? \App\Helpers\SiteHelper::getObjectUrl($value) : $value;
    }

    public static function getCompanyRecuiters($company_id)
    {
        return User::role('company user')->where('company_id', $company_id)->orderBy('updated_at', 'ASC')->skip(0)->take(5)->get();
    }

    public static function removeIncompleteProfileQuery($query)
    {
        $keys = [ 'users.first_name', 'users.last_name', 'users.email', 'user_work_profiles.summary', 'user_work_profiles.cv_link' ];

        foreach ($keys as $key) {
            $query->where($key,'!=', NULL);
            $query->where($key,'!=' ,'');
        }

        // $query->whereHas('userWorkProfileDetail', function (Builder $query) {
        //     $query->where('type', 'skill');
        // }, '>=', 1);
        return $query;
    }
    // public function roles(){
    //     return $this->belongsToMany('App\Models\Auth\Role');
    // }

    public static function getUserSearchVisibilityPercentage($user)
    {
        $progress = 0;
        $total_experience = 0;
        if (SiteHelper::isNotEmpty($user->first_name)) {
            $progress = $progress + 15;
        }
        if (SiteHelper::isNotEmpty($user->last_name)) {
            $progress = $progress + 15;
        }

        if (SiteHelper::isNotEmpty($user->email)) {
            $progress = $progress + 15;
        }


        if (UserWorkProfile::checkUserProfileExist($user->id)) {
            $work_profile = UserWorkProfile::getDataById(UserWorkProfile::getWorkProfileId($user->id));

            if (SiteHelper::isNotEmpty($work_profile->summary)) {
                $progress = $progress + 15;
            }
            // if (SiteHelper::isNotEmpty($work_profile->cv_link)) {
            //     $progress = $progress + 20;
            // }

        }

        if (UserWorkProfileDetail::checkUserProfileExist($user->id)) {
            $skills = UserWorkProfileDetail::getDataByType($user->id, 'skill');
            if ($skills->count() > 0){
                $progress = $progress + 40;
            }

        }
        return $progress;
    }


    public static function deleteMyAccount($user)
    {
        try {
            $user->deleted_at = date("Y-m-d H:i:s");
            $user->deleted_by = $user->id;
            $user->save();

            $user->tokens->each(function ($token, $key) {
                $token->delete();
            });

            return true;
            
        } catch (Exception $e) {

            throw new Exception($e);
            
        }
    }
}
