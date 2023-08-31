<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Communicator\Communicator;
use App\Constants\ResponseMessages;
use App\Constants\StringConstants;
use App\Models\Job;
use App\Models\CompanyJobDetail;
use App\Models\CandidateJobs;
use App\Models\ReportedJobs;
use App\Models\MasterData;
use App\Http\Requests\API\AddEditJobRequest;
use App\AppRinger\Logger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\API\ApplyJobRequest;
use App\Http\Requests\API\ReportJobRequest;
use App\Http\Requests\API\CloseJobRequest;
use App\Http\Requests\API\SearchJobRequest;
use App\Http\Requests\API\ChangeApplicantStatusRequest;
use App\Http\Requests\API\JobDetailRequest;
use App\Http\Requests\API\ChangeJobStatusRequest;
use App\Http\Requests\API\DuplicateJobRequest;
use Carbon\Carbon;
use App\Models\UserWorkProfile;
use App\Models\FeaturedJob;
use Exception;
use App\Config\AppConfig;
use App\Mail\SendJobApply;
use App\Helpers\SiteHelper;
use App\Notifications\Frontend\JobApplyNotification;
use Illuminate\Support\Facades\Notification;

class JobController extends Controller
{
    public function addEdit(AddEditJobRequest $request)
    {
        try {

            $message = StringConstants::JOB_ADD_SUCCESS_MSG;

            if (isset($request->job_id) && $request->job_id != null && $request->job_id != '') {
                CompanyJobDetail::deleteData($request->job_id);

                $message = StringConstants::JOB_UPDATE_SUCCESS_MSG;
            }
            $user_id    = Auth::guard('api')->user()->id;
            $company_id    = Auth::guard('api')->user()->company_id;
            $job_id = Job::addUpdateJob($request, $user_id, $company_id);
            if ($job_id != '') {
                if ($request->has('required_skills') && count($request->required_skills) > 0) {

                    foreach ($request->required_skills as $required_skill) {
                        $temp_array = array();
                        $temp_array['company_job_id']   = $job_id;
                        $temp_array['data_id']          = $required_skill;
                        $temp_array['type']             = 'required_skills';

                        CompanyJobDetail::add($temp_array);
                    }
                }

                if ($request->has('additional_skills') && count($request->additional_skills) > 0) {

                    foreach ($request->additional_skills as $additional_skill) {
                        $temp_array = array();
                        $temp_array['company_job_id']   = $job_id;
                        $temp_array['data_id']          = $additional_skill;
                        $temp_array['type']             = 'additional_skills';

                        CompanyJobDetail::add($temp_array);
                    }
                }

                if ($request->has('work_locations') && count($request->work_locations) > 0) {

                    foreach ($request->work_locations as $work_location) {
                        $temp_array = array();
                        $temp_array['company_job_id']   = $job_id;
                        $temp_array['data_id']          = $work_location;
                        $temp_array['type']             = 'locations';

                        CompanyJobDetail::add($temp_array);
                    }
                }

                if ($request->has('benefits') && count($request->benefits) > 0) {

                    foreach ($request->benefits as $benefit) {
                        $temp_array = array();
                        $temp_array['company_job_id']   = $job_id;
                        $temp_array['data_id']          = $benefit;
                        $temp_array['type']             = 'benefits';

                        CompanyJobDetail::add($temp_array);
                    }
                }
                Job::updateSearchableHash($job_id);

                return Communicator::returnResponse(ResponseMessages::SUCCESS($message, Job::getDataById($job_id)));
            } else {
                return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::SOMETHING_WRONG_MSG, StringConstants::SOMETHING_WRONG_MSG));

                Logger::logWarning("Error occured in Add Job API");
            }
        } catch (Exception $e) {
            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }


    public function jobListing(SearchJobRequest $request)
    {
        try {
            $pageNumber = 1;
            $limit = 10;
            DB::enableQueryLog();

            if ($request->has('pageNumber')) {
                $pageNumber = $request->pageNumber;
            }

            if ($request->has('limit')) {
                $limit = $request->limit;
            }

            $skill_names = [];

            $isSkillExistInRequest = ($request->has('skills') && count($request->skills) > 0);
            $isLocationExistInRequest = ($request->has('locations') && count($request->locations) > 0);

            $skill_weightage = 100;
            $location_weightage = 100;
            $show_percentage = false;
            if ($isSkillExistInRequest) {
                $show_percentage = true;
                $skill_ids = $request->skills;
            }
            if ($isLocationExistInRequest) {
                $show_percentage = true;
                $location_ids = $request->locations;
            }

            if ($isSkillExistInRequest && $isLocationExistInRequest) {
                $skill_weightage = 82;
                $location_weightage = 18;
            }

            $past_date = '2022-12-12';

            if ($isSkillExistInRequest && $isLocationExistInRequest) {
                $query = Job::with(['referral' => function($query)
                    {
                        $query->where('end_date', '>', date('Y-m-d H:i:s'));
                     
                    }])->select('company_jobs.id', 'company_jobs.title', 'company_jobs.description', 'company_jobs.min_salary', 'company_jobs.max_salary', 'company_jobs.minimum_experience_required', 'company_jobs.maximum_experience_required', \DB::raw("IF(DATE(company_jobs.updated_at) > DATE(NOW() - INTERVAL 90 DAY), status, 'expired') as job_status"), 'job_type_table.name AS job_types', 'company_jobs.created_at', 'company_jobs.renew_date', 'companies.name AS company_name', 'companies.location_id AS company_location', 'companies.logo AS company_logo', DB::raw('group_concat(job_required_skills.data_id) as required_skills'), DB::raw('group_concat(distinct CONCAT(job_location_table.name,", ",job_location_table.description)) as job_locations'), DB::raw('group_concat(distinct job_skill_table.name) as job_skills'), DB::raw('group_concat(job_additional_skills.data_id) as additional_skills'),DB::raw('(IFNULL(skillPercentage.profile_percentage, 0) + IFNULL(locationPercentage.profile_percentage, 0.66 * '.$location_weightage.')) AS profile_percentage'));
            }elseif ($isSkillExistInRequest) {
                    $query = Job::with(['referral' => function($query)
                    {
                        $query->where('end_date', '>', date('Y-m-d H:i:s'));
                     
                    }])->select('company_jobs.id', 'company_jobs.title', 'company_jobs.description', 'company_jobs.min_salary', 'company_jobs.max_salary', 'company_jobs.minimum_experience_required', 'company_jobs.maximum_experience_required', \DB::raw("IF(DATE(company_jobs.updated_at) > DATE(NOW() - INTERVAL 90 DAY), status, 'expired') as job_status"), 'job_type_table.name AS job_types', 'company_jobs.created_at', 'company_jobs.renew_date', 'companies.name AS company_name', 'companies.location_id AS company_location', 'companies.logo AS company_logo', DB::raw('group_concat(job_required_skills.data_id) as required_skills'), DB::raw('group_concat(distinct CONCAT(job_location_table.name,", ",job_location_table.description)) as job_locations'), DB::raw('group_concat(distinct job_skill_table.name) as job_skills'), DB::raw('group_concat(job_additional_skills.data_id) as additional_skills'),'skillPercentage.profile_percentage');
            }elseif ($isLocationExistInRequest) {
                $query = Job::with(['referral' => function($query)
                    {
                        $query->where('end_date', '>', date('Y-m-d H:i:s'));
                     
                    }])->select('company_jobs.id', 'company_jobs.title', 'company_jobs.description', 'company_jobs.min_salary', 'company_jobs.max_salary', 'company_jobs.minimum_experience_required', 'company_jobs.maximum_experience_required', \DB::raw("IF(DATE(company_jobs.updated_at) > DATE(NOW() - INTERVAL 90 DAY), status, 'expired') as job_status"), 'job_type_table.name AS job_types', 'company_jobs.created_at', 'company_jobs.renew_date', 'companies.name AS company_name', 'companies.location_id AS company_location', 'companies.logo AS company_logo', DB::raw('group_concat(job_required_skills.data_id) as required_skills'), DB::raw('group_concat(distinct CONCAT(job_location_table.name,", ",job_location_table.description)) as job_locations'), DB::raw('group_concat(distinct job_skill_table.name) as job_skills'), DB::raw('group_concat(job_additional_skills.data_id) as additional_skills'),'locationPercentage.profile_percentage');
            }else{
                $query = Job::with(['referral' => function($query)
                    {
                        $query->where('end_date', '>', date('Y-m-d H:i:s'));
                     
                    }])->select('company_jobs.id', 'company_jobs.title', 'company_jobs.description', 'company_jobs.min_salary', 'company_jobs.max_salary', 'company_jobs.minimum_experience_required', 'company_jobs.maximum_experience_required', \DB::raw("IF(DATE(company_jobs.updated_at) > DATE(NOW() - INTERVAL 90 DAY), status, 'expired') as job_status"), 'job_type_table.name AS job_types', 'company_jobs.created_at', 'company_jobs.renew_date', 'companies.name AS company_name', 'companies.location_id AS company_location', 'companies.logo AS company_logo', DB::raw('group_concat(job_required_skills.data_id) as required_skills'), DB::raw('group_concat(distinct CONCAT(job_location_table.name,", ",job_location_table.description)) as job_locations'), DB::raw('group_concat(distinct job_skill_table.name) as job_skills'), DB::raw('group_concat(job_additional_skills.data_id) as additional_skills'));
            }

            $query->leftJoin('company_job_details AS job_additional_skills', function($join)
                {
                    $join->on('job_additional_skills.company_job_id', '=', 'company_jobs.id');
                    $join->where('job_additional_skills.type','=', 'additional_skills');
                })
                ->leftJoin('company_job_details AS job_required_skills', function($join)
                {
                    $join->on('job_required_skills.company_job_id', '=', 'company_jobs.id');
                    $join->where('job_required_skills.type','=', 'required_skills');
                })
                ->leftJoin('company_job_details AS job_locations', function($join)
                {
                    $join->on('job_locations.company_job_id', '=', 'company_jobs.id');
                    $join->where('job_locations.type','=', 'locations');
                })
                ->leftJoin('master_data AS job_skill_table', 'job_required_skills.data_id', '=', 'job_skill_table.id')
                ->leftJoin('master_data AS job_type_table', 'job_type_table.id', '=', 'company_jobs.job_type_id')
                ->leftJoin('master_data AS job_location_table', 'job_locations.data_id', '=', 'job_location_table.id')
                ->leftJoin('users', 'company_jobs.user_id', '=', 'users.id')
                ->leftJoin('companies', 'company_jobs.company_id', '=', 'companies.id');

                if ($isSkillExistInRequest) {
                    $query->leftJoin(DB::raw('(SELECT (count(DISTINCT data_id)/'.count($skill_ids).')*'.$skill_weightage.' as profile_percentage, company_jobs.id as buid  FROM `company_jobs` left join company_job_details on company_jobs.id = company_job_details.company_job_id where (company_job_details.type = "required_skills" OR company_job_details.type = "additional_skills") and company_job_details.data_id IN ('.implode(', ', $skill_ids).') GROUP BY company_jobs.id
                    ) as skillPercentage'), function ($join) {
                        $join->on ( 'skillPercentage.buid', '=', 'company_jobs.id' );
                    });
                }

                if ($isLocationExistInRequest) {
                    $query->leftJoin(DB::raw('(SELECT (count(DISTINCT data_id)/'.count($location_ids).')*'.$location_weightage.' as profile_percentage, company_jobs.id as buid  FROM `company_jobs` left join company_job_details on company_jobs.id = company_job_details.company_job_id where company_job_details.type = "locations" and company_job_details.data_id IN ('.implode(', ', $location_ids).') GROUP BY company_jobs.id
                    ) as locationPercentage'), function ($join) {
                        $join->on ( 'locationPercentage.buid', '=', 'company_jobs.id' );
                    });
                }


                $query->where('company_jobs.status', 'published')
                ->where('companies.deleted_at', null);

            if ($request->has('locations') && count($request->locations) > 0) {
                $query->whereIn('job_locations.data_id', $request->locations);
            }

            if ($request->has('skills') && count($request->skills) > 0) {
                $skills = $request->skills;
                $skill_names = MasterData::whereIn('id', $request->skills)->pluck('name')->toArray();
                $query->where(function ($query) use ($skills, $skill_names) {
                    $query->orWhereIn('job_required_skills.data_id', $skills);
                    $query->orWhereIn('job_additional_skills.data_id', $skills);
                    // foreach ($skill_names as $skill_name) {
                    //     $query->orWhere('company_jobs.title', 'LIKE', '%' . $skill_name . '%');
                    //     $query->orWhere('company_jobs.description', 'LIKE', '%' . $skill_name . '%');
                    // }
                });
            }

            if ($request->has('job_types') && count($request->job_types) > 0) {
                $query->whereIn('company_jobs.job_type_id', $request->job_types);
            }

            if ($request->has('job_durations') && count($request->job_durations) > 0) {
                $query->whereIn('company_jobs.job_duration_id', $request->job_durations);
            }

            if ($request->has('work_auths') && count($request->work_auths) > 0) {
                $query->whereIn('company_jobs.work_authorization_id', $request->work_auths);
            }

            if ($request->has('joining_preferences') && count($request->joining_preferences) > 0) {
                $query->whereIn('company_jobs.joining_preference_id', $request->joining_preferences);
            }

            if ($request->has('industry_domains') && count($request->industry_domains) > 0) {
                $query->whereIn('company_jobs.industry_domain_id', $request->industry_domains);
            }

            if ($request->has('travel_required') && $request->travel_required == '1') {
                $query->where('company_jobs.is_travel_required', $request->travel_required);
            }

            if ($request->has('travel_required') && $request->travel_required == '1' && $request->has('travel_percentage') && $request->travel_percentage != null && $request->travel_percentage != '') {
                $query->where('company_jobs.travel_percentage', $request->travel_percentage);
            }

            if ($request->has('eeo') && $request->eeo == '1') {
                $query->where('companies.equal_opportunity_employer', $request->eeo);
            }

            if ($request->has('min_experience') && $request->has('max_experience')) {
                $query->where(function ($query) use ($request) {

                    $query->where(function ($query) use ($request) {
                        $query->where('company_jobs.minimum_experience_required', '>=', $request->min_experience);
                        $query->where('company_jobs.minimum_experience_required', '<=', $request->max_experience);
                    });

                    $query->orWhere(function ($query) use ($request) {
                        $query->where('company_jobs.maximum_experience_required', '>=', $request->min_experience);
                        $query->where('company_jobs.maximum_experience_required', '<=', $request->max_experience);
                    });
                });
            }


            if ($request->has('min_salary') && count($request->min_salary) > 0 && $request->has('max_salary') && count($request->max_salary) > 0) {
                $query->where(function ($query) use ($request) {
                    for ($i = 0; $i < count($request->min_salary); $i++) {
                        if (isset($request->min_salary[$i]) && isset($request->max_salary[$i])) {
                            $min = $request->min_salary[$i];
                            $max = $request->max_salary[$i];
                            if ($i == 0) {
                                $query->where(function ($query) use ($min, $max) {
                                    $query->where('company_jobs.min_salary', '>=', $min);
                                    $query->where('company_jobs.max_salary', '<=', $max);
                                });
                            } else {
                                $query->orWhere(function ($query) use ($min, $max) {
                                    $query->where('company_jobs.min_salary', '>=', $min);
                                    $query->where('company_jobs.max_salary', '<=', $max);
                                });
                            }
                        }
                    }
                });
            }
            if ($request->has('job_from') && $request->job_from != null && $request->job_from != 0) {
                $query->where('company_jobs.renew_date', '>=', $request->job_from . ' 00:00:00');
            }

            if ($request->has('job_to') && $request->job_to != null && $request->job_to != 0) {
                $query->where('company_jobs.renew_date', '<=', $request->job_to . ' 23:59:59');
            }


            if ($request->has('salary_types') && count($request->salary_types) > 0) {
                $query->whereIn('company_jobs.salary_type_id', $request->salary_types);
            }

            if ($request->has('q') && $request->q != '') {
               $q = str_getcsv(strtolower($request->q), ' ');
                
                $query->where(function ($query) use ($q) {
                    foreach ($q as $key => $word) {
                        $word = trim($word);
                        if ($word != '' && $word != 'or' && $word != 'and' && $word != 'not') {

                            if ($key > 0 && $q[$key - 1] == 'not') {
                                $query->orWhere('company_jobs.searchable_hash', 'NOT LIKE', '%' . $word . '%');
                            }elseif ($key > 0 && $q[$key - 1] == 'and') {
                                $query->where('company_jobs.searchable_hash', 'LIKE', '%' . $word . '%');
                            }else{

                                $query->orWhere('company_jobs.searchable_hash', 'LIKE', '%' . $word . '%');
                            }
                        }
                    }
                });
            }

            if ($request->has('order_by') && $request->order_by == "MOST_RECENT") {
                $query->orderBy('company_jobs.updated_at', "DESC");
            } elseif ($request->has('order_by') && $request->order_by == "POST_DATE") {
                $query->orderBy('company_jobs.updated_at', "ASC");
            } elseif (($isSkillExistInRequest || $isLocationExistInRequest) && $request->has('order_by') && $request->order_by == "MOST_RELEVANT") {
                $query->orderBy('profile_percentage', "DESC");
            } else {
                $query->orderBy('company_jobs.updated_at', "DESC");
            }

            $query->groupBy('company_jobs.id');

            $jobs = $query->paginate($limit, ['*'], 'page', $pageNumber);

            if ($request->has('order_by') && $request->order_by == "MOST_RELEVANT") {
                if ($request->has('skills') && count($request->skills) > 0) {
                    $search_skills = $request->skills;
                    foreach ($jobs as $key => $job) {
                        $total_score = 0;

                        $required_skills = explode(",", $job->required_skills);

                        $count_required_skills_matched = count(array_intersect($search_skills, $required_skills));
                        $total_score += ($count_required_skills_matched / count($search_skills)) * env('SEARCH_WEIGHTAGE_REQUIRED_SKILLS');

                        $additional_skills = explode(",", $job->additional_skills);
                        $count_additional_skills_matched = count(array_intersect($search_skills, $additional_skills));

                        $total_score += ($count_additional_skills_matched / count($search_skills)) * env('SEARCH_WEIGHTAGE_ADDITIONAL_SKILLS');

                        $count_title_match = 0;
                        $count_description_match = 0;
                        foreach ($skill_names as $skill_name) {
                            if (strpos($job->title, $skill_name) !== false) {
                                $count_title_match++;
                            }
                            if (strpos($job->description, $skill_name) !== false) {
                                $count_description_match++;
                            }
                        }

                        $total_score += ($count_title_match / count($search_skills)) * env('SEARCH_WEIGHTAGE_TITLE');

                        $total_score += ($count_description_match / count($search_skills)) * env('SEARCH_WEIGHTAGE_DESCRIPTION');




                        $job->score = $total_score;
                    }
                }
            }

            $scores = array();
            foreach ($jobs as $key => $row) {
                $scores[$key] = $row->score;
            }

            array_multisort($scores, SORT_DESC, $jobs->getCollection()->toArray());

            foreach ($jobs as $key => $value) {
                $value->company_logo = ($value->company_logo != null && $value->company_logo != '') ? \App\Helpers\SiteHelper::getObjectUrl($value->company_logo) : $value->company_logo;
            }

            $query = Job::has('referral')->with(['company','jobType','locations','requiredSkills','referral' => function($query)
                    {
                        $query->where('end_date', '>', date('Y-m-d H:i:s'));
                     
                    }]);

            $boost_jobs = $query->skip(0)->take(8)->get();

            $data['jobs'] = $jobs;
            $data['boost_jobs'] = $boost_jobs;
            $data['show_percentage'] = $show_percentage;

            return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::JOB_LISTING_SUCCESS_MSG, $data));
        } catch (Exception $e) {

            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }

    public function applyJob(ApplyJobRequest $request)
    {
        try {
            $user_id     = Auth::guard('api')->user()->id;
            $wp = UserWorkProfile::where('user_id', $user_id);
            if (!$wp->count()) {
                return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::WORK_PROFILE_COMPLETE, StringConstants::WORK_PROFILE_COMPLETE));
            }
            $apply_job = CandidateJobs::applyCandidateJob($request, $user_id);
            if (isset($request->saved) && $request->saved == 1)
                $message = StringConstants::JOB_SAVE_SUCCESS_MSG;
            elseif (isset($request->saved) && $request->saved == 0)
                $message = StringConstants::JOB_UNSAVE_SUCCESS_MSG;
            else
                $message = StringConstants::JOB_APPLY_SUCCESS_MSG;

            if (isset($request->applied) && $request->applied == 1) {
                $id = $request->job_id;
                $job = Job::with('user_details')->find($id);
                if ($job) {
                    $recruiter_name = $job->user_details->full_name;
                    $contact = $job->user_details->contact;
                    $job_link = env('FRONTEND_URL').'/job/details/'.$id.'/'.SiteHelper::createSlug($job->title);
                    $subject = notificationTemplates('send_job_apply')->subject ? notificationTemplates('send_job_apply')->subject. $job->title : 'New applicant for job - '.$job->title;
                    // \Mail::to($job->user_details->email)->send(new SendJobApply($subject, $job_link, $recruiter_name));
                    // Notification::send($job->user_details->email, new JobApplyNotification($job));
                    // dd($job->user_details->email);
                    $data = [
                        'link' => $job_link, 
                        'recruiter_name'=>$recruiter_name,
                        'contact'=>$contact,
                    ];
                    $template = 'frontend.mail.send_job_apply';
                    $job->user_details->notify(new JobApplyNotification($subject,$data, $template));



                }
            }

            return Communicator::returnResponse(ResponseMessages::SUCCESS($message, null));
        } catch (Exception $e) {

            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }


    public function reportJob(ReportJobRequest $request)
    {
        try {
            $user_id    = Auth::guard('api')->user()->id;
            $report_job = ReportedJobs::add($request, $user_id);
            return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::JOB_REPORT_SUCCESS_MSG, null));
        } catch (Exception $e) {
            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }

    public function closeJob(CloseJobRequest $request)
    {
        try {

            $job_update = Job::updateStatus($request);

            return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::JOB_CLOSE_SUCCESS_MSG, null));
        } catch (Exception $e) {

            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }

    public function changeApplicantStatus(ChangeApplicantStatusRequest $request)
    {
        try {

            $change_status = CandidateJobs::changeApplicantStatus($request);
            if ($change_status)
                return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::CHANGE_APPLICANT_STATUS_SUCCESS_MSG, array()));
            else
                return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::SOMETHING_WRONG_MSG, StringConstants::SOMETHING_WRONG_MSG));
        } catch (Exception $e) {
            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }

    public function getMyJobs(Request $request)
    {
        try {
            $user_id    = Auth::guard('api')->user()->id;
            $company_id     = Auth::guard('api')->user()->company_id;
            $order_by = "DESC";

            if ($request->has('order_by') && $request->order_by == "MOST_RECENT") {
                $order_by = "DESC";
            } elseif ($request->has('order_by') && $request->order_by == "POST_DATE") {
                $order_by = "ASC";
            }

            if ($company_id != null && $company_id != '') {
                if ($request->has('type') && $request->type == 'ALL_JOBS') {
                    $get_jobs['active'] = Job::getCompanyPublishedJobs($user_id, $order_by, $company_id);
                    $get_jobs['closed'] = Job::getCompanyClosedJobs($user_id, $order_by, $company_id);
                    $get_jobs['draft']  = Job::getCompanyDraftJobs($user_id, $order_by, $company_id);
                } else {
                    $get_jobs['active'] = Job::getCompanyPublishedJobs($user_id, $order_by);
                    $get_jobs['closed'] = Job::getCompanyClosedJobs($user_id, $order_by);
                    $get_jobs['draft']  = Job::getCompanyDraftJobs($user_id, $order_by);
                }
            } else {

                $get_jobs['applied'] = Job::getUserAppliedJobs($user_id, $order_by);

                $get_jobs['saved'] = Job::getUserSavedJobs($user_id, $order_by);
            }
            return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::GET_JOBS_SUCCESS_MSG, $get_jobs));
        } catch (Exception $e) {

            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }

    public function jobDetails(JobDetailRequest $request)
    {
        try {
            $user_id            = '';
            $user_company_id    = '';
            if (Auth::guard('api')->user()) {
                $user_id    = Auth::guard('api')->user()->id;
                $user_company_id    = Auth::guard('api')->user()->company_id;
            }
            $job_details = Job::with(['locations', 'requiredSkills', 'additionalSkills','user_details','referral' => function($query)
            {
                $query->where('end_date', '>', date('Y-m-d H:i:s'));
             
            }])->select('company_jobs.*', 'company_jobs.created_at AS job_posted_date', 'companies.name AS company_name', 'companies.description AS company_description', 'companies.website AS company_website',  DB::raw('concat(company_location_table.name,", ",company_location_table.description) as company_location'), 'companies.logo AS company_logo', 'job_type_table.name AS job_type_name', 'salary_type_table.name AS salary_type_name', 'industry_domain_table.name AS industry_domain_name', 'work_authorization_table.name AS work_authorization_name', 'joining_preference_table.name AS joining_preference_name', 'job_duration_table.name AS job_duration_name', DB::raw('group_concat(distinct job_skill_table.name) as job_required_skills'), DB::raw('group_concat(distinct job_location_table.name) as job_locations'), DB::raw('group_concat(distinct job_additional_skill_table.name) as job_additional_skills'), DB::raw('group_concat(distinct job_benefit_table.name) as job_benefits'), DB::raw('group_concat(distinct job_required_skills.data_id) as job_required_skill_ids'), DB::raw('group_concat(distinct job_locations.data_id) as job_location_ids'), DB::raw('group_concat(distinct job_additional_skills.data_id) as job_additional_skill_ids'), DB::raw('group_concat(distinct job_benefits.data_id) as job_benefit_ids'))
                ->leftJoin('companies', 'company_jobs.company_id', '=', 'companies.id')
                ->leftJoin('master_data AS company_location_table', 'companies.location_id', '=', 'company_location_table.id')
                ->leftJoin('master_data AS job_type_table', 'company_jobs.job_type_id', '=', 'job_type_table.id')
                ->leftJoin('master_data AS salary_type_table', 'company_jobs.salary_type_id', '=', 'salary_type_table.id')
                ->leftJoin('master_data AS industry_domain_table', 'company_jobs.industry_domain_id', '=', 'industry_domain_table.id')
                ->leftJoin('master_data AS work_authorization_table', 'company_jobs.work_authorization_id', '=', 'work_authorization_table.id')
                ->leftJoin('master_data AS job_duration_table', 'company_jobs.job_duration_id', '=', 'job_duration_table.id')
                ->leftJoin('master_data AS joining_preference_table', 'company_jobs.joining_preference_id', '=', 'joining_preference_table.id')
                ->leftJoin('company_job_details AS job_required_skills', function ($join) {
                    $join->on('job_required_skills.company_job_id', '=', 'company_jobs.id');
                    $join->where('job_required_skills.type', '=', 'required_skills');
                })
                ->leftJoin('master_data AS job_skill_table', 'job_required_skills.data_id', '=', 'job_skill_table.id')
                ->leftJoin('company_job_details AS job_additional_skills', function ($join) {
                    $join->on('job_additional_skills.company_job_id', '=', 'company_jobs.id');
                    $join->where('job_additional_skills.type', '=', 'additional_skills');
                })
                ->leftJoin('master_data AS job_additional_skill_table', 'job_additional_skills.data_id', '=', 'job_additional_skill_table.id')
                ->leftJoin('company_job_details AS job_locations', function ($join) {
                    $join->on('job_locations.company_job_id', '=', 'company_jobs.id');
                    $join->where('job_locations.type', '=', 'locations');
                })
                ->leftJoin('master_data AS job_location_table', 'job_locations.data_id', '=', 'job_location_table.id')
                ->leftJoin('company_job_details AS job_benefits', function ($join) {
                    $join->on('job_benefits.company_job_id', '=', 'company_jobs.id');
                    $join->where('job_benefits.type', '=', 'benefits');
                })
                ->leftJoin('master_data AS job_benefit_table', 'job_benefits.data_id', '=', 'job_benefit_table.id')
                ->where('company_jobs.id', $request->job_id)
                ->first();

            if ($job_details->status == 'draft' && $user_company_id != $job_details->company_id) {
                return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::ACCESS_DENIED_ERROR_MSG, StringConstants::ACCESS_DENIED_ERROR_MSG));
            }
            $job_details->is_applied = CandidateJobs::isApplied($request->job_id, $user_id);
            $job_details->is_saved = CandidateJobs::isSaved($request->job_id, $user_id);
            $job_details->is_reported = ReportedJobs::checkUserReportedJob($user_id, $request->job_id);
            $job_details->total_applicants = CandidateJobs::getJobApplicantsCount($request->job_id);
            $job_details->job_posted_date = Carbon::parse($job_details->job_posted_date)->diffForHumans();
            $job_details->job_required_skills = explode(",", $job_details->job_required_skills);
            $job_details->job_additional_skills = explode(",", $job_details->job_additional_skills);
            $job_details->job_benefits = explode(",", $job_details->job_benefits);
            $job_details->job_required_skill_ids = explode(",", $job_details->job_required_skill_ids);
            $job_details->job_location_ids = explode(",", $job_details->job_location_ids);
            $job_details->job_additional_skill_ids = explode(",", $job_details->job_additional_skill_ids);
            $job_details->job_benefit_ids = explode(",", $job_details->job_benefit_ids);
            $job_details->company_logo = ($job_details->company_logo != null && $job_details->company_logo != '') ? \App\Helpers\SiteHelper::getObjectUrl($job_details->company_logo) : $job_details->company_logo;

            if ($user_company_id == $job_details->company_id) {
                $applicants = CandidateJobs::getJobApplicants($request->job_id);

                $job_details->applicants = $applicants;
            }

            if ($job_details->id != null) {
                return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::JOB_DETAIL_SUCCESS_MSG, $job_details));
            }else{
                return Communicator::returnResponse(ResponseMessages::NOT_FOUND(StringConstants::NOT_FOUND, null));
            }

        } catch (Exception $e) {
            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }

    public function changeJobStatus(ChangeJobStatusRequest $request)
    {
        try {

            $status = $request->status;
            if($status == "published"){
                if(!Job::isJobHaveAllDetails($request->job_id)){
                    return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::INCOMPLETE_JOB_DETAILS, StringConstants::INCOMPLETE_JOB_DETAILS));
                }
            }

            $job_update = Job::updateStatus($request);

            return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::CHANGE_JOB_STATUS_SUCCESS_MSG, null));
        } catch (Exception $e) {

            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }

    public function getFeaturedJobs()
    {
        try {
            $jobs = FeaturedJob::getFeaturedJobs();

            foreach ($jobs as $key => $value) {
                $value->company_logo = ($value->company_logo != null && $value->company_logo != '') ? \App\Helpers\SiteHelper::getObjectUrl($value->company_logo) : $value->company_logo;
                $value->posted_date = Carbon::parse($value->renew_date)->diffForHumans();
            }

            return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::JOB_LISTING_SUCCESS_MSG, $jobs));
        } catch (Exception $e) {

            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }

    public function reNewJob(Request $request)
    {
        try {
            $job_id = $request->job_id;
            $user_id    = Auth::guard('api')->user()->id;
            $date = date('Y-m-d H:i:s');
            
            $job_renew = Job::reNewJob($job_id,$user_id,$date);

            return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::JOB_RENEW_SUCCESS_MSG, null));
        } catch (Exception $e) {

            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }

    public function duplicateJob(DuplicateJobRequest $request)
    {
        try {
            $id = $request->job_id;
            if(Job::duplicateJob($id))
                return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::JOB_DUPLICATE_SUCCESS_MSG, null));
            else
                return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::SOMETHING_WRONG_MSG, StringConstants::SOMETHING_WRONG_MSG));

        } catch (Exception $e) {
            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }
}
