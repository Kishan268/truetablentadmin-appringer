<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Communicator\Communicator;
use App\Constants\ResponseMessages;
use App\Constants\StringConstants;
use App\Http\Requests\API\SearchCandidateRequest;
use App\Models\UserWorkProfileDetail;
use App\Models\Job;
use App\Models\CandidateProfileTracking;
use App\AppRinger\Logger;
use Exception;

class GraphController extends Controller
{
	public function getData(SearchCandidateRequest $request)
	{

		try {

			Logger::logDebug("[Search Candidate Graph Data API]");
			Logger::logDebug("Request data: " . json_encode($request->all()));

			$searched_skills = [];
			$chart_data = [];

			if (getSystemConfig('is_graph_enabled')) {

				if ($request->has('skills') && count($request->skills) > 0) {
					$searched_skills = $request->skills;
				}

				$searched_locations = [];
				if ($request->has('locations') && count($request->locations) > 0) {
					$searched_locations = $request->locations;
				}
				$ranges = ["0-5", "5-10", "10+"];
				$backgrounds = ['#20C9AC', '#5542F6', '#FFA043', '#FA699D', '#00A5FF'];
				\DB::enableQueryLog();
				if (Auth::guard('api')->check() && count($searched_skills) < 1 && count($searched_locations) < 1) {
					$get_user_job_count = Job::getUserJobCount(Auth::guard('api')->user()->id);
					if ($get_user_job_count > 0) {
						$chart_data = job::getUserLatestJobsData(Auth::guard('api')->user()->id, $backgrounds, $ranges);
					} else {

						$chart_data = UserWorkProfileDetail::getChartDataWithoutLogin($backgrounds, $ranges, $searched_skills, $searched_locations);
					}
				} else {
					$chart_data = UserWorkProfileDetail::getChartDataWithoutLogin($backgrounds, $ranges, $searched_skills, $searched_locations);
				}
				Logger::logDebug("Query build: " . json_encode(\DB::getQueryLog()));
			} else {
				$data['graph1'] = [];
				$data['graph2'] = [];
				$chart_data = $data;
			}

			$data['chart_data'] = $chart_data;
			return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::GRAPH_DATA_SUCCESS_MSG, $data));
		} catch (Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function getCompanyReporting()
	{
		$user = Auth::guard('api')->user();
		$user_id = $user->id;
		$company_id = $user->company_id;
		$data = [];
        $graph1_data = [];
        $graph2_data = [];
		$labels = [];
		for ($i = 1; $i <= 12; $i++) {
			// $graph1_data['title'] = "Resume Viewed";
            $month = date("M", mktime(0, 0, 0, $i, 1));
            $labels[] = $month;
		    $month_first_date = date("Y-m-01 H:i:s", mktime(0, 0, 0, $i, 1));
		    $month_last_date = date("Y-m-t H:i:s", mktime(0, 0, 0, $i, 1));

		    if ($user->isCompanyAdmin()) {
		    	
				$data[] = CandidateProfileTracking::where('company_id',$company_id)->where('is_profile_viewed','1')->where('created_at','>=',$month_first_date)->where('created_at','<=',$month_last_date)->count();
		    }else{

				$data[] = CandidateProfileTracking::where('recruiter_id',$user_id)->where('is_profile_viewed','1')->where('created_at','>=',$month_first_date)->where('created_at','<=',$month_last_date)->count();
		    }
		}

		$datasets = [
			'label' => 'Essential',
			'data' => $data,	
			'backgroundColor' => '#20C9AC',		
		];

		$graph1_data['labels'] = $labels;
		$graph1_data['datasets'][] = $datasets;
		$resp['graph1_data'] = $graph1_data;


		$graph2_data['labels'] = ['Essential Resume'];
		$datasets = [
			'label' => 'Essential Resume',
			'data' => $user->isCompanyAdmin() ? [CandidateProfileTracking::where('company_id',$company_id)->where('is_profile_downloaded','1')->count()] : [CandidateProfileTracking::where('recruiter_id',$user_id)->where('is_profile_downloaded','1')->count()],	
			'backgroundColor' => ['#20C9AC'],		
			'borderColor' => ['#20C9AC'],
			'hoverBackgroundColor' => ['#20C9AC'],
			'hoverBorderColor' => ['#20C9AC'],
			'borderWidth' => 0.1,		
		];

		$graph2_data['datasets'][] = $datasets;
		$resp['graph1_data'] = $graph1_data;
		$resp['graph2_data'] = $graph2_data;

		if ($user->isCompanyAdmin()) {
		    	
			$resp['total_downloads'] = CandidateProfileTracking::where('company_id',$company_id)->where('is_profile_downloaded','1')->count();
	    }else{

			$resp['total_downloads'] = CandidateProfileTracking::where('recruiter_id',$user_id)->where('is_profile_downloaded','1')->count();
	    }

		
		return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::GRAPH_DATA_SUCCESS_MSG, $resp));
		
	}
}
