<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Communicator\Communicator;
use App\Constants\ResponseMessages;
use App\Constants\StringConstants;
use App\Models\CompanyGig;
use App\Models\CompanyGigDetail;
use App\Models\CandidateGig;
use App\Models\ReportedGig;
use App\Models\UserWorkProfile;
use App\Models\MasterData;
use App\Models\UserWorkProfileDetail;
use App\Http\Requests\API\AddEditGigRequest;
use App\Http\Requests\API\CloseGigRequest;
use App\Http\Requests\API\ChangeGigApplicantStatusRequest;
use App\Http\Requests\API\ChangeGigStatusRequest;
use App\Http\Requests\API\ApplyGigRequest;
use App\Http\Requests\API\ReportGigRequest;
use App\Http\Requests\API\DuplicateGigRequest;
use Exception;
use App\AppRinger\Logger;
use Illuminate\Support\Facades\Auth;
use App\Models\FeaturedGig;
use Carbon\Carbon;
use App\Config\AppConfig;

class GigController extends Controller
{
    public function addEdit(AddEditGigRequest $request)
    {
        try {

            $message = StringConstants::GIG_ADD_SUCCESS_MSG;

            if (isset($request->id) && $request->id != null && $request->id != '') {
                CompanyGigDetail::deleteData($request->id);

                $message = StringConstants::GIG_UPDATE_SUCCESS_MSG;
            }
            $user_id    = Auth::guard('api')->user()->id;
            $company_id    = Auth::guard('api')->user()->company_id;
            $gig = CompanyGig::addUpdateGig($request, $user_id, $company_id);
            if ($gig != null) {
                $gig_id = $gig->id;
                if ($request->has('required_skills') && count($request->required_skills) > 0) {

                    foreach ($request->required_skills as $required_skill) {
                        $temp_array = array();
                        $temp_array['company_gig_id']   = $gig_id;
                        $temp_array['data_id']          = $required_skill;
                        $temp_array['type']             = 'required_skills';

                        CompanyGigDetail::add($temp_array);
                    }
                }

                if ($request->has('work_locations') && count($request->work_locations) > 0) {

                    foreach ($request->work_locations as $work_location) {
                        $temp_array = array();
                        $temp_array['company_gig_id']   = $gig_id;
                        $temp_array['data_id']          = $work_location;
                        $temp_array['type']             = 'locations';

                        CompanyGigDetail::add($temp_array);
                    }
                }
                CompanyGig::updateSearchableHash($gig_id);
                return Communicator::returnResponse(ResponseMessages::SUCCESS($message, $gig));
            } else {
                return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::SOMETHING_WRONG_MSG, StringConstants::SOMETHING_WRONG_MSG));

                Logger::logWarning("Error occured in Add Gig API");
            }
        } catch (Exception $e) {
            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }

    public function closeGig(CloseGigRequest $request)
    {
        try {

            $update = CompanyGig::updateStatus($request);

            return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::GIG_CLOSE_SUCCESS_MSG, null));
        } catch (Exception $e) {

            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }

    public function changeApplicantStatus(ChangeGigApplicantStatusRequest $request)
    {
        try {

            $change_status = CandidateGig::changeApplicantStatus($request);
            if ($change_status)
                return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::CHANGE_APPLICANT_STATUS_SUCCESS_MSG, array()));
            else
                return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::SOMETHING_WRONG_MSG, StringConstants::SOMETHING_WRONG_MSG));
        } catch (Exception $e) {
            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }

    public function getMyGigs(Request $request)
    {
        try {
            $user_id = Auth::guard('api')->user()->id;
            $company_id = Auth::guard('api')->user()->company_id;
            $order_by = "DESC";

            if ($request->has('order_by') && $request->order_by == "MOST_RECENT") {
                $order_by = "DESC";
            } elseif ($request->has('order_by') && $request->order_by == "POST_DATE") {
                $order_by = "ASC";
            }

            if ($company_id != null && $company_id != '') {
                if ($request->has('type') && $request->type == 'ALL_GIGS') {
                    $get_jobs['active'] = CompanyGig::with('type', 'locations', 'skills','user')->where('company_id', $company_id)->where('status', 'published')->orderBy('updated_at', $order_by)->groupBy('id')->get();
                    $get_jobs['closed'] = CompanyGig::with('type', 'locations', 'skills','user')->where('company_id', $company_id)->where('status', 'closed')->orderBy('updated_at', $order_by)->groupBy('id')->get();
                    $get_jobs['draft']  = CompanyGig::with('type', 'locations', 'skills','user')->where('company_id', $company_id)->where('status', 'draft')->orderBy('updated_at', $order_by)->groupBy('id')->get();
                } else {
                    $get_jobs['active'] = CompanyGig::with('type', 'locations', 'skills','user')->where('user_id', $user_id)->where('status', 'published')->orderBy('updated_at', $order_by)->groupBy('id')->get();
                    $get_jobs['closed'] = CompanyGig::with('type', 'locations', 'skills','user')->where('user_id', $user_id)->where('status', 'closed')->orderBy('updated_at', $order_by)->groupBy('id')->get();
                    $get_jobs['draft']  = CompanyGig::with('type', 'locations', 'skills','user')->where('user_id', $user_id)->where('status', 'draft')->orderBy('updated_at', $order_by)->groupBy('id')->get();
                }

                foreach ($get_jobs['active'] as $key => &$job) {
                    $job_required_skills = [];
                    foreach ($job->skills as $value) {
                        $job_required_skills[] = $value->id;
                    }
                    $job->matching_count = UserWorkProfileDetail::where('type','skill')->where('skill_id','!=',null)->whereIN('skill_id',array_merge($job_required_skills))->distinct('user_work_profile_id')->count();

                    $job->applicants = CandidateGig::where('gig_id',$job->id)->where('applied','1')->count();
                }

                foreach ($get_jobs['closed'] as $key => &$job) {
                    $job_required_skills = [];
                    foreach ($job->skills as $value) {
                        $job_required_skills[] = $value->id;
                    }
                    $job->matching_count = UserWorkProfileDetail::where('type','skill')->where('skill_id','!=',null)->whereIN('skill_id',array_merge($job_required_skills))->distinct('user_work_profile_id')->count();

                    $job->applicants = CandidateGig::where('gig_id',$job->id)->where('applied','1')->count();
                }

                foreach ($get_jobs['draft'] as $key => &$job) {
                    $job_required_skills = [];
                    foreach ($job->skills as $value) {
                        $job_required_skills[] = $value->id;
                    }
                    $job->matching_count = UserWorkProfileDetail::where('type','skill')->where('skill_id','!=',null)->whereIN('skill_id',array_merge($job_required_skills))->distinct('user_work_profile_id')->count();

                    $job->applicants = CandidateGig::where('gig_id',$job->id)->where('applied','1')->count();
                }
            } else {

                $get_jobs['applied'] = CompanyGig::getUserAppliedGigs($user_id, $order_by);

                $get_jobs['saved'] = CompanyGig::getUserSavedGigs($user_id, $order_by);
            }
            return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::GET_GIGS_SUCCESS_MSG, $get_jobs));
        } catch (Exception $e) {

            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }

    public function changeGigStatus(ChangeGigStatusRequest $request)
    {
        try {
            $status = $request->status;
            if($status == "published"){
                if(!CompanyGig::isGigHaveAllDetails($request->id)){
                    return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::INCOMPLETE_GIG_DETAILS, StringConstants::INCOMPLETE_GIG_DETAILS));
                }
            }
            $update = CompanyGig::updateStatus($request);

            return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::CHANGE_GIG_STATUS_SUCCESS_MSG, null));
        } catch (Exception $e) {

            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }

    public function gigDetails($id)
    {
        try {
            $user_id            = '';
            $user_company_id    = '';
            if (Auth::guard('api')->user()) {
                $user_id    = Auth::guard('api')->user()->id;
                $user_company_id    = Auth::guard('api')->user()->company_id;
            }
            $gig_details = CompanyGig::with('type', 'locations', 'skills', 'company','engagementMode','user')->find($id);

            if ($gig_details->id == null || ($gig_details->status == 'closed' && $user_id != $gig_details->user_id)) {

                return Communicator::returnResponse(ResponseMessages::NOT_FOUND(StringConstants::NOT_FOUND, null));
            } else {
                $gig_details->is_applied = CandidateGig::isApplied($id, $user_id);
                $gig_details->is_saved = CandidateGig::isSaved($id, $user_id);
                $gig_details->is_reported = ReportedGig::checkUserReportedGig($user_id, $id);
                $gig_details->total_applicants = CandidateGig::getJobApplicantsCount($id);


                if ($user_company_id == $gig_details->company_id) {
                    $applicants = CandidateGig::getGigApplicants($id);

                    $gig_details->applicants = $applicants;
                }

                return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::GIG_DETAIL_SUCCESS_MSG, $gig_details));
            }
        } catch (Exception $e) {
            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }

    public function gigListing(Request $request)
    {
        try {
            $pageNumber = 1;
            $limit = 10;

            if ($request->has('pageNumber')) {
                $pageNumber = $request->pageNumber;
            }

            if ($request->has('limit')) {
                $limit = $request->limit;
            }

            $skill_names = [];

            $query = CompanyGig::with('type', 'locations', 'skills','company','engagementMode')
                ->select('company_gigs.*')
                ->leftJoin('company_gig_details AS gig_required_skills', 'company_gigs.id', '=', 'gig_required_skills.company_gig_id')
                ->leftJoin('company_gig_details AS gig_locations', 'company_gigs.id', '=', 'gig_locations.company_gig_id')
                ->leftJoin('companies', 'companies.id', '=', 'company_gigs.company_id')
                ->where('company_gigs.status', 'published')
                ->where('companies.deleted_at', null);

            if ($request->has('locations') && count($request->locations) > 0) {
                $query->whereIn('gig_locations.data_id', $request->locations);
            }

            if ($request->has('skills') && count($request->skills) > 0) {
                $skills = $request->skills;
                $skill_names = MasterData::whereIn('id', $request->skills)->pluck('name')->toArray();
                $query->where(function ($query) use ($skills, $skill_names) {
                    $query->orWhereIn('gig_required_skills.data_id', $skills);
                    // foreach ($skill_names as $skill_name) {
                    //     $query->orWhere('company_gigs.title', 'LIKE', '%' . $skill_name . '%');
                    //     $query->orWhere('company_gigs.description', 'LIKE', '%' . $skill_name . '%');
                    // }
                });
            }

            if ($request->has('salary_types') && count($request->salary_types) > 0) {
                $query->whereIn('company_gigs.gig_type_id', $request->salary_types);
            }




            if ($request->has('min_budget') && trim($request->min_budget) != "") {
                $query->where('company_gigs.min_budget', '>=', $request->min_budget);
            }

            if ($request->has('max_budget') && trim($request->max_budget) != "") {
                $query->where('company_gigs.max_budget', '<=', $request->max_budget);
            }
            
            if ($request->has('gig_from') && $request->gig_from != null && $request->gig_from != 0) {
                $query->where('company_gigs.renew_date', '>=', $request->gig_from . ' 00:00:00');
            }

            if ($request->has('gig_to') && $request->gig_to != null && $request->gig_to != 0) {
                $query->where('company_gigs.renew_date', '<=', $request->gig_to . ' 23:59:59');
            }

            if ($request->has('q') && $request->q != '') {
               $q = str_getcsv(strtolower($request->q), ' ');
                
                $query->where(function ($query) use ($q) {
                    foreach ($q as $key => $word) {
                        $word = trim($word);
                        if ($word != '' && $word != 'or' && $word != 'and' && $word != 'not') {

                            if ($key > 0 && $q[$key - 1] == 'not') {
                                $query->orWhere('company_gigs.searchable_hash', 'NOT LIKE', '%' . $word . '%');
                            }elseif ($key > 0 && $q[$key - 1] == 'and') {
                                $query->where('company_gigs.searchable_hash', 'LIKE', '%' . $word . '%');
                            }else{

                                $query->orWhere('company_gigs.searchable_hash', 'LIKE', '%' . $word . '%');
                            }
                        }
                    }
                });
            }

            if ($request->has('order_by') && $request->order_by == "MOST_RECENT") {
                $query->orderBy('company_gigs.updated_at', "DESC");
            } elseif ($request->has('order_by') && $request->order_by == "POST_DATE") {
                $query->orderBy('company_gigs.updated_at', "ASC");
            } else {
                $query->orderBy('company_gigs.updated_at', "DESC");
            }

            $query->groupBy('company_gigs.id');

            $gigs = $query->paginate($limit, ['*'], 'page', $pageNumber);

            return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::GIG_LISTING_SUCCESS_MSG, $gigs));
        } catch (Exception $e) {

            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }

    public function applyGig(ApplyGigRequest $request)
    {
        try {
            $user_id     = Auth::guard('api')->user()->id;
            $wp = UserWorkProfile::where('user_id', $user_id);
            if (!$wp->count()) {
                return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::WORK_PROFILE_COMPLETE, StringConstants::WORK_PROFILE_COMPLETE));
            }
            $apply_job = CandidateGig::applyCandidateGig($request, $user_id);
            if (isset($request->saved) && $request->saved == 1)
                $message = StringConstants::GIG_SAVE_SUCCESS_MSG;
            elseif (isset($request->saved) && $request->saved == 0)
                $message = StringConstants::GIG_UNSAVE_SUCCESS_MSG;
            else
                $message = StringConstants::GIG_APPLY_SUCCESS_MSG;

            return Communicator::returnResponse(ResponseMessages::SUCCESS($message, null));
        } catch (Exception $e) {

            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }


    public function reportGig(ReportGigRequest $request)
    {
        try {
            $user_id    = Auth::guard('api')->user()->id;
            $report_gig = ReportedGig::add($request, $user_id);
            return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::GIG_REPORT_SUCCESS_MSG, null));
        } catch (Exception $e) {
            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }

    public function getFeaturedGigs()
    {
        try {
            $gigs = FeaturedGig::getFeaturedGigs();

            foreach ($gigs as $key => $value) {
                $value->company_logo = ($value->company_logo != null && $value->company_logo != '') ? \App\Helpers\SiteHelper::getObjectUrl($value->company_logo) : $value->company_logo;
                $value->posted_date = Carbon::parse($value->created_at)->diffForHumans();
            }

            return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::SUCCESSS, $gigs));
        } catch (Exception $e) {

            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }

    public function reNewGig(Request $request)
    {
        try {
            $id = $request->id;
            $user_id    = Auth::guard('api')->user()->id;
            $date = date('Y-m-d H:i:s');
            
            $gig_renew = CompanyGig::reNewGig($id,$user_id,$date);

            return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::JOB_RENEW_SUCCESS_MSG, null));
        } catch (Exception $e) {

            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }

    public function duplicateJob(DuplicateGigRequest $request)
    {
        try {
            $id = $request->gig_id;
            if(CompanyGig::duplicateGig($id))
                return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::GIG_DUPLICATE_SUCCESS_MSG, null));
            else
                return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::SOMETHING_WRONG_MSG, StringConstants::SOMETHING_WRONG_MSG));

        } catch (Exception $e) {
            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }
}
