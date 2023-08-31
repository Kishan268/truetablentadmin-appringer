<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Config\AppConfig;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\DataEntryController;
use App\Models\UserWorkProfile;
use App\Models\Job;
use App\Models\CompanyGig;
use App\Models\Auth\User;

class CronController extends Controller
{
    public function runCrons(Request $request)
    {
    	$api_key = $request->x_api_key;
    	if ($api_key != env('API_KEY')) {
    		return 'Please pass the API Key';
    	}
    	$HomeController = new HomeController();
    	$DataEntryController = new DataEntryController();

    	echo "<br /> =============Remove Skills=================== <br />";
    	if (AppConfig::isRemoveSkillEnabled()) {
    		$resp = $HomeController->removeDuplicateSkills();
    	}

    	echo "<br /> =============Data Pull Logic=================== <br />";

    	if (AppConfig::isDataPullEnabled()) {
    		$DataEntryController->insertData();
    	}

    	echo "<br /> =============Update User Search Visibility=================== <br />";

    	if (AppConfig::isUpdateSearchVisibilityEnabled()) {
    		UserWorkProfile::updateSearchVisibility();
    	}

    	echo "<br /> =============Update Searchable Hash For Candidates=================== <br />";

    	if (AppConfig::isUpdateCVEnabled()) {
    		$users = UserWorkProfile::where('searchable_hash','')->orWhere('searchable_hash',null)->skip(0)->take(10)->get();
    		foreach ($users as $key => $user) {
    			if(UserWorkProfile::updateSearchableHash($user->user_id)){
    				echo "<br /> Updated Searchable Hash successfully for ".$user->user_id;
    			}else{
    				echo "<br /> Update of Searchable Hash FAILED for ".$user->user_id;
    			}
    		}
    	}

        echo "<br /> =============Update Searchable Hash For Jobs=================== <br />";

        if (AppConfig::isUpdateJobsEnabled()) {
            $jobs = Job::where('searchable_hash','')->orWhere('searchable_hash',null)->skip(0)->take(10)->get();
            foreach ($jobs as $key => $job) {
                if(Job::updateSearchableHash($job->id)){
                    echo "<br /> Updated Searchable Hash successfully for ".$job->id;
                }else{
                    echo "<br /> Update of Searchable Hash FAILED for ".$job->id;
                }
            }
        }

        echo "<br /> =============Update Searchable Hash For Gigs=================== <br />";

        if (AppConfig::isUpdateGigsEnabled()) {
            $gigs = CompanyGig::where('searchable_hash','')->orWhere('searchable_hash',null)->skip(0)->take(10)->get();
            foreach ($gigs as $key => $gig) {
                if(CompanyGig::updateSearchableHash($gig->id)){
                    echo "<br /> Updated Searchable Hash successfully for ".$gig->id;
                }else{
                    echo "<br /> Update of Searchable Hash FAILED for ".$gig->id;
                }
            }
        }

        echo "<br /> =============Update Current Location for Candidates=================== <br />";
        $users = UserWorkProfile::where(function ($query) {
                $query->where('location_id', null);
                $query->orWhere('location_id', '');
            })->where(function ($query) {
                $query->where('location_name','!=',null);
                $query->where('location_name','!=','');
            })->skip(0)->take(10)->get();
        foreach ($users as $key => $user) {
            if(UserWorkProfile::updateUserLocation($user->user_id)){
                echo "<br /> Location updated successfully for ".$user->user_id;
            }else{
                echo "<br /> Location update FAILED for ".$user->user_id;
            }
        }

    	return 'success';
    }

    public function deleteUsers(Request $request)
    {
        $api_key = $request->x_api_key;
        if ($api_key != env('API_KEY')) {
            return 'Please pass the API Key';
        }

        $date = date('Y-m-d H:i:s', strtotime('-30 days'));

        $users = User::withTrashed()->where('deleted_at','<=',$date)->where('email','not like',"deleted-%")
            ->where('email','not like',"%-deleted")->get();


        foreach ($users as $user) {
            if ($user->id == $user->deleted_by) {
                $user->update([
                    'email' => $user->email.'-deleted'
                ]);

                echo "<br /> User deleted successfully ".$user->id;
            }elseif ($user->isCompanyAdmin()) {
                
            }
        }
        die();
    }
}
