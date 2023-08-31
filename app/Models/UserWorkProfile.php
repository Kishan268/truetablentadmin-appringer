<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SiteHelper;
use App\Models\Auth\User;
use App\Config\AppConfig;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class UserWorkProfile extends Model
{
    protected $fillable = ['user_id', 'location_id', 'location_name', 'contact_number', 'summary', 'joining_preference_id', 'total_experience', 'work_authorization_id', 'cv_link', 'video_link', 'evaluation_feedback', 'layoff', 'her_career_reboot', 'differently_abled', 'armed_forces','searchable_hash','search_visibility'];

    protected $hidden = ['created_at', 'deleted_at'];

    public function locationData()
    {
        return $this->belongsTo(MasterData::class, 'location_id');
    }

    public function workAuthorization(){
        return $this->hasOne('App\Models\MasterData', 'id', 'work_authorization_id');
    }

    public function joiningPreference(){
        return $this->hasOne('App\Models\MasterData', 'id', 'joining_preference_id');
    }


    public static function add($request, $user_id)
    {
        $check_existance = UserWorkProfile::checkUserProfileExist($user_id);
        if ($check_existance) {
            $UserWorkProfile = UserWorkProfile::find(UserWorkProfile::getWorkProfileId($user_id));
        } else {
            $UserWorkProfile = new UserWorkProfile();
        }

        $data = $request->only($UserWorkProfile->getFillable());

        $UserWorkProfile->fill(array_merge($data, ['user_id' => $user_id]))->save();
        return $UserWorkProfile->id;
    }

    public static function getDataById($work_profile_id)
    {
        return UserWorkProfile::select('user_work_profiles.*', 'users.first_name', 'users.last_name')->leftJoin('users', 'users.id', 'user_work_profiles.user_id')->where('user_work_profiles.id', $work_profile_id)->first();
    }

    public static function updateResume($filename, $user_id)
    {
        $status = false;
        $UserWorkProfile = UserWorkProfile::where('user_id', $user_id)->first();
        if ($UserWorkProfile) {
            if ($UserWorkProfile->update(['cv_link' => $filename])) {
                $status = true;
            }
        } else {
            $UserWorkProfile = new UserWorkProfile;
            $UserWorkProfile->user_id = $user_id;
            $UserWorkProfile->cv_link = $filename;
            if ($UserWorkProfile->save()) {
                $status = true;
            }
        }

        return $status;
    }


    public static function updateVideo($filename, $user_id)
    {
        $status = false;
        $UserWorkProfile = UserWorkProfile::where('user_id', $user_id)->first();
        if ($UserWorkProfile) {
            if ($UserWorkProfile->update(['video_link' => $filename])) {
                $status = true;
            }
        } else {
            $UserWorkProfile = new UserWorkProfile;
            $UserWorkProfile->user_id = $user_id;
            $UserWorkProfile->video_link = $filename;
            if ($UserWorkProfile->save()) {
                $status = true;
            }
        }

        return $status;
    }

    public static function checkUserProfileExist($user_id)
    {
        $get_profile = UserWorkProfile::where('user_id', $user_id)->get();
        if ($get_profile->count() > 0)
            return true;
        else
            return false;
    }

    public static function getWorkProfileId($user_id)
    {
        $work_profile = UserWorkProfile::where('user_id', $user_id)->first();
        return $work_profile->id;
    }

    public function getCvLinkAttribute($value)
    {
        return ($value != null && $value != '') ? \App\Helpers\SiteHelper::getObjectUrl($value) : $value;
    }

    public function getVideoLinkAttribute($value)
    {
        return ($value != null && $value != '') ? \App\Helpers\SiteHelper::getObjectUrl($value) : $value;
    }

    public static function updateSearchableHash($id,$resumeFile = ''){

        try {
            
            $user = User::withTrashed()->with('userWorkProfile','userWorkProfile.joiningPreference','userWorkProfile.workAuthorization','userWorkProfileDetail','userPrefferedData')->find($id);
            if($user){

                $userWorkProfileDetail = $user->userWorkProfileDetail->groupBy('type');

                $searchable_hash = '';
                $searchable_hash .= $user->full_name.SiteHelper::getSearchHashSeperator().$user->designation.SiteHelper::getSearchHashSeperator().$user->email.SiteHelper::getSearchHashSeperator().$user->contact.SiteHelper::getSearchHashSeperator().$user->location.SiteHelper::getSearchHashSeperator().$user->date_of_birth.SiteHelper::getSearchHashSeperator().$user->gender.SiteHelper::getSearchHashSeperator().$user->min_salary;

                if (isset($user->userWorkProfile)) {
                    
                    $searchable_hash .= SiteHelper::getSearchHashSeperator().$user->userWorkProfile->location_name.SiteHelper::getSearchHashSeperator().$user->userWorkProfile->contact_number.SiteHelper::getSearchHashSeperator().$user->userWorkProfile->summary.SiteHelper::getSearchHashSeperator().$user->userWorkProfile->total_experience;

                    $searchable_hash .= (isset($user->userWorkProfile->workAuthorization) && isset($user->userWorkProfile->workAuthorization->name)) ? SiteHelper::getSearchHashSeperator().$user->userWorkProfile->workAuthorization->name : '';
                    $searchable_hash .= (isset($user->userWorkProfile->joiningPreference) && isset($user->userWorkProfile->joiningPreference->name)) ? SiteHelper::getSearchHashSeperator().$user->userWorkProfile->joiningPreference->name : '';
                }

                if ($resumeFile != '') {
                    $pdfData = '';
                    $pdfData = shell_exec('cd .. && python3 pdfParse.py '.$resumeFile);
                    
                    $searchable_hash .= $pdfData != '' ? SiteHelper::getSearchHashSeperator().$pdfData : '';
                }elseif (isset($user->userWorkProfile) && isset($user->userWorkProfile->cv_link) && $user->userWorkProfile->cv_link !== null && $user->userWorkProfile->cv_link != '') {

                    $ext = pathinfo($user->userWorkProfile->cv_link, PATHINFO_EXTENSION);
                    $ext = explode("?", $ext);
                    if ($ext[0] == 'pdf') {
                        $file = file_get_contents($user->userWorkProfile->cv_link);

                        $filename = 'resumes/hash-resume.pdf';
                        file_put_contents(public_path($filename), $file);
                        $file_path = public_path($filename);
                        $pdfData = '';
                        $pdfData = shell_exec('cd .. && python3 pdfParse.py '.$file_path);
                        
                        $searchable_hash .= $pdfData != '' ? SiteHelper::getSearchHashSeperator().$pdfData : '';
                        unlink($file_path);
                    }

                    
                }

                if (isset($userWorkProfileDetail['certificate']) && count($userWorkProfileDetail['certificate']) > 0) {
                    foreach ($userWorkProfileDetail['certificate'] as $key => $data) {
                        $searchable_hash .= isset($data->title) ? SiteHelper::getSearchHashSeperator().$data->title : '';

                        $searchable_hash .= isset($data->description) ? SiteHelper::getSearchHashSeperator().$data->description : '';
                        
                        $searchable_hash .= isset($data->awarded_by) ? SiteHelper::getSearchHashSeperator().$data->awarded_by : '';
                    }
                }

                if (isset($userWorkProfileDetail['degree']) && count($userWorkProfileDetail['degree']) > 0) {
                    foreach ($userWorkProfileDetail['degree'] as $key => $data) {
                        $searchable_hash .= isset($data->title) ? SiteHelper::getSearchHashSeperator().$data->title : '';

                        $searchable_hash .= isset($data->description) ? SiteHelper::getSearchHashSeperator().$data->description : '';
                        
                        $searchable_hash .= isset($data->awarded_by) ? SiteHelper::getSearchHashSeperator().$data->awarded_by : '';
                    }
                }

                if (isset($userWorkProfileDetail['experience']) && count($userWorkProfileDetail['experience']) > 0) {
                    foreach ($userWorkProfileDetail['experience'] as $key => $data) {
                        $searchable_hash .= isset($data->title) ? SiteHelper::getSearchHashSeperator().$data->title : '';

                        $searchable_hash .= isset($data->description) ? SiteHelper::getSearchHashSeperator().$data->description : '';
                        
                        $searchable_hash .= isset($data->awarded_by) ? SiteHelper::getSearchHashSeperator().$data->awarded_by : '';
                    }
                }

                if (isset($userWorkProfileDetail['award']) && count($userWorkProfileDetail['award']) > 0) {
                    foreach ($userWorkProfileDetail['award'] as $key => $data) {
                        $searchable_hash .= isset($data->title) ? SiteHelper::getSearchHashSeperator().$data->title : '';

                        $searchable_hash .= isset($data->description) ? SiteHelper::getSearchHashSeperator().$data->description : '';
                        
                        $searchable_hash .= isset($data->awarded_by) ? SiteHelper::getSearchHashSeperator().$data->awarded_by : '';
                    }
                }

                if (isset($userWorkProfileDetail['skill']) && count($userWorkProfileDetail['skill']) > 0) {
                    foreach ($userWorkProfileDetail['skill'] as $key => $data) {
                        $searchable_hash .= isset($data->skill->name) ? SiteHelper::getSearchHashSeperator().$data->skill->name : '';
                    }
                }

                if (isset($user->userPrefferedData) && count($user->userPrefferedData) > 0) {
                    foreach ($user->userPrefferedData as $key => $data) {
                        $searchable_hash .= isset($data->data->name) ? SiteHelper::getSearchHashSeperator().$data->data->name : '';
                        $searchable_hash .= isset($data->data->description) ? SiteHelper::getSearchHashSeperator().$data->data->description : '';
                    }
                }

                $user->userWorkProfile->searchable_hash = $searchable_hash;
                $user->userWorkProfile->timestamps = false;
                $user->userWorkProfile->save();
                return true;
            }else{
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public static function updateSearchVisibility()
    {
        $synced_id = AppConfig::userSearchVisibilitySyncedId();
        $users = User::role('candidate')->where('id','>',$synced_id)->skip(0)->take(10)->get();
        foreach ($users as $key => $user) {
            try {
                
                $user_progress = User::getUserSearchVisibilityPercentage($user);

                echo "<br /> Progress for user id ".$user->id." is ".$user_progress;
                $UserWorkProfile = UserWorkProfile::where('user_id',$user->id)->first();
                if ($UserWorkProfile) {
                    
                    $UserWorkProfile->timestamps = false;
                    if ($user_progress < 100) {
                        $UserWorkProfile->search_visibility = '0';
                        
                    }else{
                        $UserWorkProfile->search_visibility = '1';
                        
                    }

                    $UserWorkProfile->save();

                    $synced_id = $user->id;
                }else{
                    echo "<br />Workprofile not found for user id ".$user->id;
                }
                
            } catch (Exception $e) {
                echo "<br /> error ".$e->getMessage()." occured for user id ".$user->id;
                $synced_id = $user->id;
                
            }

        }

        AppConfig::updateUserSearchVisibilitySyncedId($synced_id);
    }

    public static function updateUserSearchVisibility($user_id)
    {
        try {

            $user = User::find($user_id);
            $user_progress = User::getUserSearchVisibilityPercentage($user);
            $UserWorkProfile = UserWorkProfile::where('user_id',$user->id)->first();
            $UserWorkProfile->timestamps = false;
            if ($user_progress < 100) {
                $UserWorkProfile->search_visibility = '0';
                
            }else{
                $UserWorkProfile->search_visibility = '1';
                
            }

            $UserWorkProfile->save();
            return true;
            
        } catch (Exception $e) {
            return false;
            
        }
    }

    public static function updateUserLocation($user_id)
    {
        try {

            $UserWorkProfile = UserWorkProfile::where('user_id',$user_id)->first();
            $UserWorkProfile->timestamps = false;
            if ($UserWorkProfile->location_name !== null && $UserWorkProfile->location_name !== '') {
                $locations = explode(',', $UserWorkProfile->location_name);

                $match_location = MasterData::where('type','location')->where(\DB::raw('lower(name)'), strtolower($locations[0]))->first();
                if ($match_location) {
                    $UserWorkProfile->location_id = $match_location->id;
                    $UserWorkProfile->location_name = $match_location->name;
                }else{
                    $match_location = MasterData::where('type','location')->where(\DB::raw('lower(name)'), strtolower('India'))->first();
                    $UserWorkProfile->location_id = $match_location->id;
                    $UserWorkProfile->location_name = $match_location->name;
                }

                
                $UserWorkProfile->save();
            }

            return true;
            
        } catch (Exception $e) {
            return false;
            
        }
    }
}
