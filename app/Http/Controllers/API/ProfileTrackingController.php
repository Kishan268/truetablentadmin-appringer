<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Config\AppConfig;
use App\Models\CandidateProfileTracking;
use App\Communicator\Communicator;
use App\Constants\ResponseMessages;
use App\Constants\StringConstants;

class ProfileTrackingController extends Controller
{
    public function addTracking(Request $request)
    {
    	$candidate_id = $request->candidate_id;
    	$is_profile_viewed = isset($request->is_profile_viewed) ? $request->is_profile_viewed : '0';
    	$is_profile_downloaded = isset($request->is_profile_downloaded) ? $request->is_profile_downloaded : '0';

    	$user_id = Auth::guard('api')->user()->id;
    	$company_id = Auth::guard('api')->user()->company_id;
    	if(CandidateProfileTracking::canAddTrackingData($user_id,$candidate_id,$is_profile_viewed,$is_profile_downloaded)){
    		CandidateProfileTracking::create([
    			'candidate_id' 			=> $candidate_id,
    			'recruiter_id' 			=> $user_id,
    			'company_id' 			=> $company_id,
    			'is_profile_viewed' 	=> $is_profile_viewed,
    			'is_profile_downloaded' => $is_profile_downloaded
    		]);
    	}

    	return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::SUCCESSS, null));
    }
}
