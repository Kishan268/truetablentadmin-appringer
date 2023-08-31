<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\WorkJobs;
use App\Models\CandidateJobs;
use App\Models\Locations;
use App\Models\WorkProfile;
use App\Models\WorkProfileDetails;
use Illuminate\Support\Facades\Auth;


use App\Models\MasterData;
use App\Models\UserWorkProfileDetail;
use App\Models\UserPrefferedData;
use App\Models\CompanyJobDetail;
use App\Models\CompanyGigDetail;
use App\AppRinger\Logger;

/**
 * Class HomeController.
 */
class HomeController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if(Auth::guard('web')->user() !=null){
            return redirect()->route('admin.dashboard');
        }else{
            return view('frontend.auth.login');
        }
    
    }

    public function searchJobs(Request $request){
        if($request->method() == 'GET'){
            $results = WorkJobs::orderBy('updated_at', 'DESC');
            return view('frontend.searchResults', ['results' => $results->paginate(10), 'params' => []]);
        }
        if(!$request->has('skills')){
            return redirect()->back()->withErrors("Please enter at-least 1 skill to continue <b>searching jobs</b>.")->withInput();
        }
        $searchParams = [];
    	foreach ($request->all() as $key => $value) {
    		if($value != null && in_array($key, ['type', 'duration', 'work_authorization', 'joining', 'domain'])){
    			$searchParams[$key] = strtolower(trim($value));
    		}
    	}
    	$results = WorkJobs::orWhere(function ($q) use ($searchParams){
    		foreach ($searchParams as $key => $value) {
    			$value = explode(',', $value);
    			foreach ($value as $val) {
    				$q->where($key, 'like', '%'.$val.'%');
    			}
    		}
        });
        if($request->has('skills') && $request->get('skills') != null){
            $skills = $request->get('skills');
            $results = $results->orWhere(function($q) use ($skills){
                foreach ($skills as $skill) {
                    $q->where('skills', 'like', '%'.strtolower($skill).'%');
                }
            });
        }
        $locations = [];
        if($request->has('locations') && $request->get('locations') != null){
            $locations = $request->get('locations');
            $results->whereIn('location', $locations);
            $locations = Locations::whereIn('id', $locations)->get()->map(function($q){
                return ['id' => $q->id, 'text' => sprintf('%s, %s', $q->city, $q->state)];
            });
        }
        if($request->has('updated_at') && $request->get('updated_at') != null){
            $results->orWhere('updated_at', '>=', $request->get('updated_at').' 00:00:00');
        }
        if($request->has('min-salary') && $request->get('min-salary') != null){
            $results->orWhere('min_salary', '>=', (float) $request->get('min-salary'));
        }
        if($request->has('min-experience') && $request->get('min-experience') != null){
            $results->orWhere('experience_req', '>=', $request->get('min-experience'));
        }
        if($request->has('max-experience') && $request->get('max-experience') != null){
            $results->orWhere('experience_req', '=<', $request->get('max-experience'));
        }
        if($request->has('travel')){
            $results->where(['travel' => $request->get('travel') == "on" ? 1: 0]);
            if($request->has('percentage')){
                $results->where('percentage', '>=', (float) $request->get('percentage'));
            }
        }
    	return view('frontend.searchResults', ['results' => $results->orderBy('updated_at', 'DESC')->get(), 'params' => $request->all(), 'locations' => $locations]);
    }

    public function searchCandidates(Request $request){
        if($request->method() == 'GET'){
            $results = WorkProfile::orderBy('completenessLevel', 'DESC');
            return view('frontend.candidateSearchResults', ['results' => $results->paginate(10), 'params' => []]);
        }
        if($request->get('work_profiles_summary') == null){
            return redirect()->back()->withErrors("Please enter valid search term to continue <b>searching candidates</b>.")->withInput();
        }
        $searchParams = [];
        $fieldMappings = ['work_profiles_summary'=> 'work_profiles.summary', 'user_details_job_type' => 'user_details.job_type', 'updated_at' => 'work_profiles.updated_at'];
    	foreach ($request->all() as $key => $value) {
    		if($value != null && in_array($key, ['work_profiles_summary', 'user_details.job_type'])){
    			$searchParams[$fieldMappings[$key]] = strtolower(trim($value));
    		}
        }
        $wps = WorkProfile::leftJoin('work_profile_details', 'work_profiles.user_id', '=', 'work_profile_details.user_id')->leftJoin('user_details', 'work_profiles.user_id', '=', 'user_details.user_id')->select('work_profile_details.*', 'user_details.*', 'work_profiles.*');
        
        if(count($searchParams) > 0){
            $wps = $wps->orWhere(function ($q) use ($searchParams){
                foreach ($searchParams as $key => $value) {
                    $value = explode(',', $value);
                    foreach ($value as $val) {
                        $q->where($key, 'like', '%'.$val.'%');
                    }
                }
            });
        }
        if($request->has('user_details_telecommute')){
            $wps = $wps->orWhere('user_details.telecommute', 1);
        }

        if($request->has('work_profiles_summary') && $request->get('work_profiles_summary') != null){
            $wildSearch = $request->get('work_profiles_summary');
            $wps = $wps->orWhere('work_profiles.experience', 'like', '%'.$wildSearch.'%')
                               ->orWhere('work_profiles.work_authorization', 'like', '%'.$wildSearch.'%')
                               ->orWhere('user_details.job_type', 'like', '%'.$wildSearch.'%')
                               ->orWhere('user_details.min_salary', 'like', '%'.$wildSearch.'%')
                               ->orWhere('work_profile_details.title', 'like', '%'.$wildSearch.'%')
                               ->orWhere('work_profile_details.shortDesc', 'like', '%'.$wildSearch.'%')
                               ->orWhere('work_profile_details.longDesc', 'like', '%'.$wildSearch.'%')
                               ->orWhere('work_profile_details.rating', 'like', '%'.$wildSearch.'%')
                               ->orWhere('work_profile_details.remarks', 'like', '%'.$wildSearch.'%');
        }

        if($request->has('user_details_min_salary') && $request->get('user_details_min_salary') != null){
            $wps = $wps->orWhere('user_details.min_salary', '>=', $request->has('user_details_min_salary'));
        }

        if($request->has('work_profiles_work_authorization') && $request->get('work_profiles_work_authorization') != null){
            $wAs = $request->get('work_profiles_work_authorization');
            $wps = $wps->orWhere(function($q) use ($wAs){
                foreach ($wAs as $wA) {
                    $q->where('work_profiles.work_authorization', 'like', '%'.strtolower($wA).'%');
                }
            });
        }
    	
        if($request->has('skills') && $request->get('skills') != null){
            $skills = $request->get('skills');
            $wps = $wps->orWhere(function($q) use ($skills){
                foreach ($skills as $skill) {
                    $q->where('type', 10)->where('work_profile_details.title', 'like', '%'.strtolower($skill).'%');
                }
            });
        }
        $locations = [];
        if($request->has('locations') && $request->get('locations') != null){
            $locations = $request->get('locations');
            $wps->whereIn('user_details.preferred_location', $locations);
            $locations = Locations::whereIn('id', $locations)->get()->map(function($q){
                return ['id' => $q->id, 'text' => sprintf('%s, %s', $q->city, $q->state)];
            });
        }
        if($request->has('updated_at') && $request->get('updated_at') != null){
            $wps->orWhere('work_profiles.updated_at', 'like', '%'.$request->get('updated_at').'%');
        }
        if($request->has('min-experience') && $request->get('min-experience') != null){
            $wps->orWhere('work_profiles.experience', '>=', $request->get('min-experience'));
        }
        if($request->has('max-experience') && $request->get('max-experience') != null){
            $wps->orWhere('work_profiles.experience', '=<', $request->get('max-experience'));
        }
       
        $results = []; $done = [];
        if($wps->count()){
            foreach($wps->get() as $wp){
                if(!in_array($wp->user_id, $done)){
                    array_push($done, $wp->user_id);
                    array_push($results, $wp);
                }
            }
        }
    	return view('frontend.candidateSearchResults', ['results' => $results, 'params' => $request->all(), 'locations' => $locations]);
    }


    public function viewJobDetails($job_id){
        $job = WorkJobs::where('id', $job_id);
        if($job->count()){
            $job = $job->first();
            $candidateJob = CandidateJobs::where(['candidate_id' => Auth()->user()->id, 'job_id' => $job->id])->first();
            return view('frontend.jobDetails', compact('job', 'candidateJob'));
        }else{
            abort(404);
        }
    }

    public function imageFiles($file, $directory = False){
        if($directory){
            $path = storage_path('app' . DIRECTORY_SEPARATOR . $directory . DIRECTORY_SEPARATOR . $file);
        }else{
            $path = storage_path('app' . DIRECTORY_SEPARATOR . $file);
        }
        return response()->file($path);
    }


    public function removeDuplicateSkills(){
        // 16258 total on local
        Logger::logDebug("=== Remove Duplicate Skills ===");
        \DB::enableQueryLog();
        $skills = MasterData::where('type','skills')->where('verified','0')->skip('0')->take('10')->get();
        if (count($skills) > 0) {
            
        
            foreach ($skills as $skill) {
                echo "--- Main Skill ---".$skill->name."(".$skill->id.")<br />";
                $duplicate_skills = [];
                $duplicate_skills = MasterData::where('type','skills')->where('verified','0')
                ->where(function ($query) use ($skill) {
                    $query->where(\DB::raw('lower(name)'), strtolower($skill->name));
                    $query->orWhere(\DB::raw('lower(name)'), strtolower($skill->name.'.'));
                    $query->orWhere(\DB::raw('lower(name)'), strtolower($skill->name.' .'));
                    $query->orWhere(\DB::raw('lower(name)'), strtolower($skill->name.','));
                    $query->orWhere(\DB::raw('lower(name)'), strtolower($skill->name.' ,'));
                })

                

                ->where('id','!=',$skill->id)->get();
                if (count($duplicate_skills) > 0) {
                    foreach ($duplicate_skills as $duplicate_skill) {
                        echo "--- Duplicate Skill ---".$duplicate_skill->name."(".$duplicate_skill->id.")<br />";
                        UserWorkProfileDetail::where('skill_id',$duplicate_skill->id)->update([
                            'skill_id' => $skill->id
                        ]);

                        UserPrefferedData::where('data_id',$duplicate_skill->id)->update([
                            'data_id' => $skill->id
                        ]);

                        CompanyJobDetail::where('data_id',$duplicate_skill->id)->update([
                            'data_id' => $skill->id
                        ]);

                        CompanyGigDetail::where('data_id',$duplicate_skill->id)->update([
                            'data_id' => $skill->id
                        ]);

                        MasterData::where('id',$duplicate_skill->id)->delete();

                    }
                }
                MasterData::where('id',$skill->id)->update([
                    'verified' => '1',
                ]);

                Logger::logDebug("Query build: " . json_encode(\DB::getQueryLog()));

                echo "==================================================================================<br />";
            }
        }
        echo "success";
    }
}
