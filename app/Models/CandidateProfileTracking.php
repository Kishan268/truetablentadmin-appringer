<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Config\AppConfig;

class CandidateProfileTracking extends Model
{
    use SoftDeletes;
    protected $table = 'candidate_profile_tracking';
	protected $fillable = ['candidate_id','recruiter_id','company_id','is_profile_viewed','is_profile_downloaded'];

	public static function getLastTrackedTime($recruiter_id,$candidate_id,$is_profile_viewed,$is_profile_downloaded)
	{
		return CandidateProfileTracking::where('recruiter_id',$recruiter_id)
										->where('candidate_id',$candidate_id)
										->where('is_profile_viewed',$is_profile_viewed)
										->where('is_profile_downloaded',$is_profile_downloaded)
										->latest('id')->first();
	}

	public static function canAddTrackingData($recruiter_id,$candidate_id,$is_profile_viewed,$is_profile_downloaded)
	{
		$last_track_data = CandidateProfileTracking::getLastTrackedTime($recruiter_id,$candidate_id,$is_profile_viewed,$is_profile_downloaded);
		if ($last_track_data) {
			$profile_view_count_bandwidth = AppConfig::getProfileViewCountBandwidth();

			return date('Y-m-d', strtotime($last_track_data->created_at.' + '.$profile_view_count_bandwidth.' minute')) < date('Y-m-d');
		}else{
			return true;
		}
	}

	public static function isProfileViewedByUser($recruiter_id,$candidate_id,$is_profile_viewed,$is_profile_downloaded)
	{
		$last_track_data = CandidateProfileTracking::getLastTrackedTime($recruiter_id,$candidate_id,$is_profile_viewed,$is_profile_downloaded);
		if ($last_track_data) {
			$profile_show_count_bandwidth = AppConfig::getProfileShowCountBandwidth();

			return date('Y-m-d', strtotime($last_track_data->created_at.'+'.$profile_show_count_bandwidth.' days')) > date('Y-m-d');
		}else{
			return false;
		}
	}

	public static function getLastTrackedTimeByCompany($company_id,$candidate_id,$is_profile_viewed,$is_profile_downloaded)
	{
		return CandidateProfileTracking::where('company_id',$company_id)
										->where('candidate_id',$candidate_id)
										->where('is_profile_viewed',$is_profile_viewed)
										->where('is_profile_downloaded',$is_profile_downloaded)
										->latest('id')->first();
	}

	public static function isProfileViewedByCompanyUser($company_id,$candidate_id,$is_profile_viewed,$is_profile_downloaded)
	{
		$last_track_data = CandidateProfileTracking::getLastTrackedTimeByCompany($company_id,$candidate_id,$is_profile_viewed,$is_profile_downloaded);
		if ($last_track_data) {
			$profile_show_count_bandwidth = AppConfig::getProfileShowCountBandwidth();

			return date('Y-m-d', strtotime($last_track_data->created_at.'+'.$profile_show_count_bandwidth.' days')) > date('Y-m-d');
		}else{
			return false;
		}
	}

}
