<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\CandidateJobs;
use App\Models\Auth\User;
use App\Models\CompanyJobRenew;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\SiteHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;

class Job extends Model
{
    use Notifiable;

    use SoftDeletes;

    protected $table = 'company_jobs';
    
    protected $fillable = ['reference_number','user_id', 'title','description', 'job_type_id', 'salary_type_id', 'industry_domain_id', 'work_authorization_id', 'is_telecommute', 'minimum_experience_required', 'maximum_experience_required', 'is_travel_required', 'joining_preference_id', 'job_duration_id', 'min_salary', 'max_salary', 'company_id', 'status', 'travel_percentage', 'eeo', 'close_reason_id', 'close_reason_description','renew_date','renew_by','searchable_hash','created_by','updated_by','deleted_by'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $appends = [
        'posted_date', 'updated_date'
    ];

    // protected static function boot() {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->created_by = is_object(Auth::guard('api')->user()) ? Auth::guard('api')->user()->id : is_object(Auth::guard('web')->user()) ? Auth::guard('web')->user()->id : NULL;
    //         $model->updated_by = NULL;
    //     });

    //     static::updating(function ($model) {
    //         $model->updated_by = is_object(Auth::guard('api')->user()) ? Auth::guard('api')->user()->id : is_object(Auth::guard('web')->user()) ? Auth::guard('web')->user()->id : NULL;
    //     });

    //     static::deleting(function ($model) {
    //         $model->deleted_by = is_object(Auth::guard('api')->user()) ? Auth::guard('api')->user()->id : is_object(Auth::guard('web')->user()) ? Auth::guard('web')->user()->id : NULL;
    //     });
    // }

    public function getUidAttribute(){
        return 'TJ-'.str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    public function getPostedDateAttribute(){
        return Carbon::parse($this->renew_date)->diffForHumans();
    }

    public function getUpdatedDateAttribute(){
        return Carbon::parse($this->updated_at)->diffForHumans();
    }

    public function candidateApplications(){
        return $this->hasMany('App\Models\CandidateJobs', 'job_id', 'id')->applied();
    }
    public function company_details(){
        return $this->belongsTo('App\Models\Companies', 'company_id', 'id');
    }
    public function user_details(){
        return $this->belongsTo('App\Models\Auth\User', 'user_id', 'id');
    }
    public function candidate(){
        return $this->hasMany('App\Models\Auth\User');
    }
     public function company_jod_detail(){
        return $this->hasMany('App\Models\CompanyJobDetail','company_job_id','id');
    }

    public function candidateDetails(){
        return $this->hasManyThrough('App\Models\Auth\User','App\Models\CandidateJobs');
    }

    public function referral(){
        return $this->hasMany('App\Models\Referral','company_job_id');
    }

    public function jobType(){
        return $this->hasOne('App\Models\MasterData', 'id', 'job_type_id');
    }

    public function salaryType(){
        return $this->hasOne('App\Models\MasterData', 'id', 'salary_type_id');
    }

    public function industryDomain(){
        return $this->hasOne('App\Models\MasterData', 'id', 'industry_domain_id');
    }

    public function workAuthorization(){
        return $this->hasOne('App\Models\MasterData', 'id', 'work_authorization_id');
    }

    public function joiningPreference(){
        return $this->hasOne('App\Models\MasterData', 'id', 'joining_preference_id');
    }

    public function jobDuration(){
        return $this->hasOne('App\Models\MasterData', 'id', 'job_duration_id');
    }

    public function company(){
        return $this->belongsTo('App\Models\Companies');
    }

    public static function addUpdateJob($request,$user_id,$company_id)
    {
        try {
            if (isset($request->job_id) && $request->job_id != null && $request->job_id != '') 
            {
                $job = Job::find($request->job_id);
            }
            else
            {
                $job = new Job();
            }

            $data = $request->only($job->getFillable());
            
            $job->fill(array_merge($data, ['user_id' => $user_id,'company_id' => $company_id, 'renew_date' => date('Y-m-d H:i:s')]))->save();

            return $job->id;
            
        } catch (Exception $e) {
            return '';
            
        }
        
    }

    public static function updateStatus($request)
    {
        $job = Job::find($request->job_id);
        $data = $request->only($job->getFillable());
        $job->fill($data)->save();
        return $job->id;
    }

    public static function getDataById($job_id)
    {
        return Job::find($job_id);
    }

    public function locations()
    {
       return $this->belongsToMany(
            MasterData::class,
            'company_job_details',
            'company_job_id',
            'data_id')
            ->wherePivot('type', 'locations');
    }

    public function requiredSkills()
    {
       return $this->belongsToMany(
            MasterData::class,
            'company_job_details',
            'company_job_id',
            'data_id')
            ->wherePivot('type', 'required_skills');
    }

    public function additionalSkills()
    {
       return $this->belongsToMany(
            MasterData::class,
            'company_job_details',
            'company_job_id',
            'data_id')
            ->wherePivot('type', 'additional_skills');
    }

    public function benefits()
    {
       return $this->belongsToMany(
            MasterData::class,
            'company_job_details',
            'company_job_id',
            'data_id')
            ->wherePivot('type', 'benefits');
    }

    public static function getCompanyPublishedJobs($user_id,$order_by,$company_id = null)
    {
        $query = Job::with(['referral' => function($query)
                {
                    $query->where('end_date', '>', date('Y-m-d H:i:s'));
                 
                }])->select('users.first_name','users.last_name','company_jobs.id','company_jobs.title','company_jobs.description','company_jobs.min_salary','company_jobs.max_salary', 'job_type_table.name AS job_types','company_jobs.created_at','company_jobs.renew_date',DB::raw('group_concat(distinct job_skill_table.name) as job_skills'), DB::raw('group_concat(distinct CONCAT(job_location_table.name,", ",job_location_table.description)) as job_locations'), 'companies.name AS company_name',DB::raw('group_concat(distinct job_required_skills.data_id) as job_required_skills'),DB::raw('group_concat(distinct job_additional_skills.data_id) as job_additional_skills'))
            ->leftJoin('company_job_details AS job_additional_skills', function($join)
                {
                    $join->on('job_additional_skills.company_job_id', '=', 'company_jobs.id');
                    $join->where('job_additional_skills.type','=', 'additional_skills');
                })
            ->leftJoin('company_job_details AS job_required_skills', function($join)
                {
                    $join->on('job_required_skills.company_job_id', '=', 'company_jobs.id');
                    $join->where('job_required_skills.type','=', 'required_skills');
                })
            ->leftJoin('master_data AS job_skill_table', 'job_required_skills.data_id', '=', 'job_skill_table.id')
            ->leftJoin('company_job_details AS job_locations', function($join)
                {
                    $join->on('job_locations.company_job_id', '=', 'company_jobs.id');
                    $join->where('job_locations.type','=', 'locations');
                })
            ->leftJoin('master_data AS job_location_table', 'job_locations.data_id', '=', 'job_location_table.id')
            ->leftJoin('master_data AS job_type_table', 'job_type_table.id', '=', 'company_jobs.job_type_id')
            ->leftJoin('users', 'company_jobs.user_id', '=', 'users.id')
            ->leftJoin('companies', 'company_jobs.company_id', '=', 'companies.id')
            ->leftJoin('candidate_jobs', 'candidate_jobs.job_id', '=', 'company_jobs.id');
        if($company_id != null){
            $query->where('company_jobs.company_id',$company_id);
        }else{
            $query->where('company_jobs.user_id',$user_id);
        }
        $data = $query->where('status','published')
            ->where('company_jobs.updated_at','>',date('Y-m-d H:i:s', strtotime('-'.env('JOBEXPIREVALIDITYDAYS').' days')))->orderBy('company_jobs.updated_at',$order_by)->groupBy('id')->get();

        foreach ($data as $key => &$job) {
            $job->applicants = CandidateJobs::where('job_id',$job->id)->where('applied','1')->count();
            $job_required_skills = explode(",",$job->job_required_skills);
            $job_additional_skills = explode(",",$job->job_additional_skills);
            $job->matching_count = UserWorkProfileDetail::where('type','skill')->where('skill_id','!=',null)->whereIN('skill_id',array_merge($job_required_skills,$job_additional_skills))->distinct('user_work_profile_id')->count();
        }

        return $data;
    }

    public static function getCompanyClosedJobs($user_id,$order_by,$company_id = null)
    {
        $query = Job::with(['referral' => function($query)
                {
                    $query->where('end_date', '>', date('Y-m-d H:i:s'));
                 
                }])->select('users.first_name','users.last_name','company_jobs.id','company_jobs.title','company_jobs.description','company_jobs.min_salary','company_jobs.max_salary', 'job_type_table.name AS job_types','company_jobs.created_at','company_jobs.renew_date',DB::raw('group_concat(distinct job_skill_table.name) as job_skills'), DB::raw('group_concat(distinct CONCAT(job_location_table.name,", ",job_location_table.description)) as job_locations'), 'companies.name AS company_name',DB::raw('group_concat(distinct job_required_skills.data_id) as job_required_skills'),DB::raw('group_concat(distinct job_additional_skills.data_id) as job_additional_skills'))
            ->leftJoin('company_job_details AS job_additional_skills', function($join)
                {
                    $join->on('job_additional_skills.company_job_id', '=', 'company_jobs.id');
                    $join->where('job_additional_skills.type','=', 'additional_skills');
                })
            ->leftJoin('company_job_details AS job_required_skills', function($join)
                {
                    $join->on('job_required_skills.company_job_id', '=', 'company_jobs.id');
                    $join->where('job_required_skills.type','=', 'required_skills');
                })
            ->leftJoin('master_data AS job_skill_table', 'job_required_skills.data_id', '=', 'job_skill_table.id')
            ->leftJoin('company_job_details AS job_locations', function($join)
                {
                    $join->on('job_locations.company_job_id', '=', 'company_jobs.id');
                    $join->where('job_locations.type','=', 'locations');
                })
            ->leftJoin('master_data AS job_location_table', 'job_locations.data_id', '=', 'job_location_table.id')
            ->leftJoin('master_data AS job_type_table', 'job_type_table.id', '=', 'company_jobs.job_type_id')
            ->leftJoin('users', 'company_jobs.user_id', '=', 'users.id')
            ->leftJoin('companies', 'company_jobs.company_id', '=', 'companies.id')
            ->leftJoin('candidate_jobs', 'candidate_jobs.job_id', '=', 'company_jobs.id');
        if($company_id != null){
            $query->where('company_jobs.company_id',$company_id);
        }else{
            $query->where('company_jobs.user_id',$user_id);
        }
            
        $data = $query->where(function($query) {
                $query->where('company_jobs.updated_at','<',date('Y-m-d H:i:s', strtotime('-'.env('JOBEXPIREVALIDITYDAYS').' days')))
                ->orWhere('company_jobs.status', 'closed');
            })
            ->orderBy('company_jobs.updated_at',$order_by)->groupBy('id')->get();

        foreach ($data as $key => &$job) {
            $job->applicants = CandidateJobs::where('job_id',$job->id)->where('applied','1')->count();
            $job_required_skills = explode(",",$job->job_required_skills);
            $job_additional_skills = explode(",",$job->job_additional_skills);
            $job->matching_count = UserWorkProfileDetail::where('type','skill')->where('skill_id','!=',null)->whereIN('skill_id',array_merge($job_required_skills,$job_additional_skills))->distinct('user_work_profile_id')->count();
        }

        return $data;
    }

    public static function getCompanyDraftJobs($user_id,$order_by,$company_id = null)
    {
        $query = Job::with(['referral' => function($query)
                {
                    $query->where('end_date', '>', date('Y-m-d H:i:s'));
                 
                }])->select('users.first_name','users.last_name','company_jobs.id','company_jobs.title','company_jobs.description','company_jobs.min_salary','company_jobs.max_salary', 'job_type_table.name AS job_types','company_jobs.created_at','company_jobs.renew_date',DB::raw('group_concat(distinct job_skill_table.name) as job_skills'), DB::raw('group_concat(distinct CONCAT(job_location_table.name,", ",job_location_table.description)) as job_locations'), 'companies.name AS company_name',DB::raw('group_concat(distinct job_required_skills.data_id) as job_required_skills'),DB::raw('group_concat(distinct job_additional_skills.data_id) as job_additional_skills'))
            ->leftJoin('company_job_details AS job_additional_skills', function($join)
                {
                    $join->on('job_additional_skills.company_job_id', '=', 'company_jobs.id');
                    $join->where('job_additional_skills.type','=', 'additional_skills');
                })
            ->leftJoin('company_job_details AS job_required_skills', function($join)
                {
                    $join->on('job_required_skills.company_job_id', '=', 'company_jobs.id');
                    $join->where('job_required_skills.type','=', 'required_skills');
                })
            ->leftJoin('master_data AS job_skill_table', 'job_required_skills.data_id', '=', 'job_skill_table.id')
            ->leftJoin('company_job_details AS job_locations', function($join)
                {
                    $join->on('job_locations.company_job_id', '=', 'company_jobs.id');
                    $join->where('job_locations.type','=', 'locations');
                })
            ->leftJoin('master_data AS job_location_table', 'job_locations.data_id', '=', 'job_location_table.id')
            ->leftJoin('master_data AS job_type_table', 'job_type_table.id', '=', 'company_jobs.job_type_id')
            ->leftJoin('users', 'company_jobs.user_id', '=', 'users.id')
            ->leftJoin('companies', 'company_jobs.company_id', '=', 'companies.id')
            ->leftJoin('candidate_jobs', 'candidate_jobs.job_id', '=', 'company_jobs.id');
        if($company_id != null){
            $query->where('company_jobs.company_id',$company_id);
        }else{
            $query->where('company_jobs.user_id',$user_id);
        }
        $data = $query->where('status','draft')
            ->where('company_jobs.updated_at','>',date('Y-m-d H:i:s', strtotime('-'.env('JOBEXPIREVALIDITYDAYS').' days')))->orderBy('company_jobs.updated_at',$order_by)->groupBy('id')->get();

        foreach ($data as $key => &$job) {
            $job->applicants = CandidateJobs::where('job_id',$job->id)->where('applied','1')->count();
            $job_required_skills = explode(",",$job->job_required_skills);
            $job_additional_skills = explode(",",$job->job_additional_skills);
            $job->matching_count = UserWorkProfileDetail::where('type','skill')->where('skill_id','!=',null)->whereIN('skill_id',array_merge($job_required_skills,$job_additional_skills))->distinct('user_work_profile_id')->count();
        }

        return $data;
    }

    public static function getUserAppliedJobs($user_id,$order_by)
    {
        return Job::select('company_jobs.id','company_jobs.title','company_jobs.description','company_jobs.min_salary','company_jobs.max_salary', 'job_type_table.name AS job_types','company_jobs.created_at','company_jobs.renew_date',DB::raw('group_concat(distinct job_skill_table.name) as job_skills'), DB::raw('group_concat(distinct CONCAT(job_location_table.name,", ",job_location_table.description)) as job_locations'), 'companies.name AS company_name','candidate_jobs.applied_at')
                    ->leftJoin('company_job_details AS job_required_skills', function($join)
                        {
                            $join->on('job_required_skills.company_job_id', '=', 'company_jobs.id');
                            $join->where('job_required_skills.type','=', 'required_skills');
                        })
                    ->leftJoin('master_data AS job_skill_table', 'job_required_skills.data_id', '=', 'job_skill_table.id')
                    ->leftJoin('company_job_details AS job_locations', function($join)
                        {
                            $join->on('job_locations.company_job_id', '=', 'company_jobs.id');
                            $join->where('job_locations.type','=', 'locations');
                        })
                    ->leftJoin('master_data AS job_location_table', 'job_locations.data_id', '=', 'job_location_table.id')
                    ->leftJoin('master_data AS job_type_table', 'job_type_table.id', '=', 'company_jobs.job_type_id')
                    // ->leftJoin('users', 'company_jobs.user_id', '=', 'users.id')
                    ->leftJoin('companies', 'company_jobs.company_id', '=', 'companies.id')
                    ->leftJoin('candidate_jobs', 'candidate_jobs.job_id', '=', 'company_jobs.id')
                    ->where(['candidate_id' => $user_id, 'applied' => 1])
                    ->where('company_jobs.status','!=','closed')
                    ->orderBy('candidate_jobs.applied_at',$order_by)->groupBy('company_jobs.id')->get();
    }

    public static function getUserSavedJobs($user_id,$order_by)
    {
        return Job::select('company_jobs.id','company_jobs.title','company_jobs.description','company_jobs.min_salary','company_jobs.max_salary', 'job_type_table.name AS job_types','company_jobs.created_at','company_jobs.renew_date',DB::raw('group_concat(distinct job_skill_table.name) as job_skills'), DB::raw('group_concat(distinct CONCAT(job_location_table.name,", ",job_location_table.description)) as job_locations'), 'companies.name AS company_name')
                    ->leftJoin('company_job_details AS job_required_skills', function($join)
                        {
                            $join->on('job_required_skills.company_job_id', '=', 'company_jobs.id');
                            $join->where('job_required_skills.type','=', 'required_skills');
                        })
                    ->leftJoin('master_data AS job_skill_table', 'job_required_skills.data_id', '=', 'job_skill_table.id')
                   ->leftJoin('company_job_details AS job_locations', function($join)
                        {
                            $join->on('job_locations.company_job_id', '=', 'company_jobs.id');
                            $join->where('job_locations.type','=', 'locations');
                        })
                    ->leftJoin('master_data AS job_location_table', 'job_locations.data_id', '=', 'job_location_table.id')
                    ->leftJoin('master_data AS job_type_table', 'job_type_table.id', '=', 'company_jobs.job_type_id')
                    // ->leftJoin('users', 'company_jobs.user_id', '=', 'users.id')
                    ->leftJoin('companies', 'company_jobs.company_id', '=', 'companies.id')
                    ->leftJoin('candidate_jobs', 'candidate_jobs.job_id', '=', 'company_jobs.id')
                    ->where(['candidate_id' => $user_id, 'saved' => 1])
                    ->where('company_jobs.status','!=','closed')
                    ->orderBy('company_jobs.updated_at',$order_by)->groupBy('company_jobs.id')->get();
    }

    public static function getFirstYear()
    {
        return Job::select('updated_at')->orderBy('updated_at','ASC')->skip(0)->take(1)->first();
    }

    public static function getJobCountByMonths($yearStart,$yearEnd,$company_id,$status)
    {
        $query = Job::select(DB::raw('YEAR(updated_at) AS year'), DB::raw('MONTH(updated_at) AS month'), DB::raw('COUNT(DISTINCT id) AS count'))->where('company_id',$company_id);

        if ($status == 'cancelled') {
            $query->where('close_reason_id', env('CANCELLED_JOB_STATUS_ID',64362));
        }elseif($status == 'closed'){
            $query->where(function($query) use ($status) {
                $query->where('company_jobs.updated_at','<',date('Y-m-d H:i:s', strtotime('-'.env('JOBEXPIREVALIDITYDAYS').' days')))
                ->orWhere('company_jobs.status', $status);
            });
        }else{
            $query->where('status',$status)->where('company_jobs.updated_at','>',date('Y-m-d H:i:s', strtotime('-'.env('JOBEXPIREVALIDITYDAYS').' days')));
        }

        return $query->whereBetween('updated_at',[$yearStart,$yearEnd])->groupBy('year','month')->get()->makeHidden('posted_date');
    }

    public static function getCompanyLeastJobs($company_id)
    {
        return Job::select('company_jobs.*',DB::raw('group_concat(distinct job_required_skills.data_id) as job_required_skills'),DB::raw('group_concat(distinct job_additional_skills.data_id) as job_additional_skills'))
            ->leftJoin('company_job_details AS job_additional_skills', function($join)
                {
                    $join->on('job_additional_skills.company_job_id', '=', 'company_jobs.id');
                    $join->where('job_additional_skills.type','=', 'additional_skills');
                })
            ->leftJoin('company_job_details AS job_required_skills', function($join)
                {
                    $join->on('job_required_skills.company_job_id', '=', 'company_jobs.id');
                    $join->where('job_required_skills.type','=', 'required_skills');
                })
            ->leftJoin('master_data AS job_skill_table', 'job_required_skills.data_id', '=', 'job_skill_table.id')
            ->leftJoin('company_job_details AS job_locations', function($join)
                {
                    $join->on('job_locations.company_job_id', '=', 'company_jobs.id');
                    $join->where('job_locations.type','=', 'locations');
                })
            ->leftJoin('master_data AS job_location_table', 'job_locations.data_id', '=', 'job_location_table.id')
            ->leftJoin('master_data AS job_type_table', 'job_type_table.id', '=', 'company_jobs.job_type_id')
            ->leftJoin('users', 'company_jobs.user_id', '=', 'users.id')
            ->leftJoin('companies', 'company_jobs.company_id', '=', 'companies.id')
            ->leftJoin('candidate_jobs', 'candidate_jobs.job_id', '=', 'company_jobs.id')
            ->where('company_jobs.company_id',$company_id)
            ->where('status','published')
            ->where('company_jobs.updated_at','>',date('Y-m-d H:i:s', strtotime('-'.env('JOBEXPIREVALIDITYDAYS').' days')))->orderBy('company_jobs.updated_at','ASC')->groupBy('id')
            ->skip(0)->take(5)->get();
    }

    public static function getCompanyDashboardGraph1($yearStart, $yearEnd, $company_id, $backgrounds)
    {
        $job_labels = ['Active','Closed','Cancelled'];
        $months = ['January','Febuary','March','April','May','June','July','August','September','October','November','December'];
        $jobs[0] = Job::getJobCountByMonths($yearStart, $yearEnd, $company_id,'published');
        $jobs[1] = Job::getJobCountByMonths($yearStart, $yearEnd, $company_id,'closed');
        $jobs[2] = Job::getJobCountByMonths($yearStart, $yearEnd, $company_id,'cancelled');
        
        $data = [];
        $data['title'] = 'Job Statuses';
        $data['labels'] = $months;
        for ($i= 0; $i <= 2; $i++) { 
            $data['data'][$i]['label'] = $job_labels[$i];
            $jobsCount = [];
            for ($j=0; $j < 12; $j++) {
                $job_found = false; 
                foreach ($jobs[$i] as $job) {
                    if ($job->month == $j + 1) {
                        $jobsCount[] =  $job->count;
                        $job_found = true;
                        continue;
                    }
                }
                if (!$job_found) {
                    $jobsCount[] = 0;
                }
            }
            $data['data'][$i]['data'] = $jobsCount;
            $data['data'][$i]['backgroundColor'] = $backgrounds[$i];
        }
        return $data;
    }

    public static function getCompanyDashboardGraph2($company_id)
    {
        $active_jobs = Job::getCompanyLeastJobs($company_id);
        
        $data = [];
        $data['title'] = 'No. of candidates available on active jobs';
        foreach ($active_jobs as $key => &$job) {
            $data['labels'][] = $job->title;
            $job_required_skills = explode(",",$job->job_required_skills);
            $job_additional_skills = explode(",",$job->job_additional_skills);
            $job->matching_count = UserWorkProfileDetail::where('type','skill')->where('skill_id','!=',null)->whereIN('skill_id',array_merge($job_required_skills,$job_additional_skills))->distinct('user_work_profile_id')->count();
        }

        $data['data'][0]['label'] = "No. of match workprofile";
        $dataset = [];
        for ($i=0; $i < 5; $i++) {
            if (isset($active_jobs[$i])) {
                $dataset[] = $active_jobs[$i]->matching_count;
            } 
        }
        $data['data'][0]['data'] = $dataset;
        $data['data'][0]['backgroundColor'] = "rgb(255, 99, 132)";

        return $data;
    }

    public static function getCompanyDashboardGraph3($company_id, $backgrounds)
    {
        $recruiters = User::getCompanyRecuiters($company_id);
        $data = [];
        $data['title'] = "Recruiter's jobs statuses";
        $graph_data = [];
        $graph_labels = ['Active Jobs','Closed Jobs', 'Hire People', 'Position Scrapped', 'Position Deferred'];
        foreach ($recruiters as $key => &$user) {
            $data['labels'][] = $user->full_name;
            $graph_data[$graph_labels[0]][] = Job::where('user_id',$user->id)->where('status','published')->where('company_jobs.updated_at','>',date('Y-m-d H:i:s', strtotime('-'.env('JOBEXPIREVALIDITYDAYS').' days')))->count();
            $graph_data[$graph_labels[1]][] = Job::where('user_id',$user->id)
                                            ->where(function($query) {
                                                $query->where('updated_at','<',date('Y-m-d H:i:s', strtotime('-'.env('JOBEXPIREVALIDITYDAYS').' days')))->orWhere('status', 'closed');
                                            })->count();
            $graph_data[$graph_labels[2]][] = Job::where('user_id',$user->id)->where('close_reason_id', env('FULFILLED_JOB_STATUS_ID',64363))->count();
            $graph_data[$graph_labels[3]][] = Job::where('user_id',$user->id)->where('close_reason_id', env('SCRAPPED_JOB_STATUS_ID',64364))->count();
            $graph_data[$graph_labels[4]][] = Job::where('user_id',$user->id)->where('close_reason_id', env('DEFERRED_JOB_STATUS_ID',64365))->count();
        }

        for ($i=0; $i < 5; $i++) {
            $data['data'][$i]['label'][] = $graph_labels[$i];
            $data['data'][$i]['data'] = isset($graph_data[$graph_labels[$i]]) ? $graph_data[$graph_labels[$i]] : [];
            $data['data'][$i]['backgroundColor'][] = $backgrounds[$i];
        }

        return $data;
    }


    public static function getCompanyDashboardGraph4($company_id)
    {
        $active_jobs = Job::getCompanyLeastJobs($company_id);
        $data = [];
        $data['title'] = 'Open jobs with no. of posted days';
        foreach ($active_jobs as $key => &$job) {
            $data['labels'][] = $job->title;
            $job->days_count = round((time() - strtotime($job->updated_at)) / (60 * 60 * 24));
        }

        $data['data'][0]['label'] = "No. of days";
        $dataset = [];
        for ($i=0; $i < 5; $i++) {
            if (isset($active_jobs[$i])) {
                $dataset[] = $active_jobs[$i]->days_count;
            } 
        }
        $data['data'][0]['data'] = $dataset;
        $data['data'][0]['backgroundColor'] = "rgb(255, 99, 132)";
        return $data;
    }

    public static function getUserJobCount($user_id)
    {
        return Job::where('user_id',$user_id)->where('status','published')->count();
    }

    public static function getUserLatestJobsData($user_id, $backgrounds, $rangesArr)
    {
        $jobs = Job::select('company_jobs.*',DB::raw('group_concat(distinct job_required_skills.data_id) as job_required_skills'),DB::raw('group_concat(distinct job_additional_skills.data_id) as job_additional_skills'))
            ->leftJoin('company_job_details AS job_additional_skills', function($join)
                {
                    $join->on('job_additional_skills.company_job_id', '=', 'company_jobs.id');
                    $join->where('job_additional_skills.type','=', 'additional_skills');
                })
            ->leftJoin('company_job_details AS job_required_skills', function($join)
                {
                    $join->on('job_required_skills.company_job_id', '=', 'company_jobs.id');
                    $join->where('job_required_skills.type','=', 'required_skills');
                })
            ->leftJoin('master_data AS job_skill_table', 'job_required_skills.data_id', '=', 'job_skill_table.id')
            ->leftJoin('company_job_details AS job_locations', function($join)
                {
                    $join->on('job_locations.company_job_id', '=', 'company_jobs.id');
                    $join->where('job_locations.type','=', 'locations');
                })
            ->leftJoin('master_data AS job_location_table', 'job_locations.data_id', '=', 'job_location_table.id')
            ->leftJoin('master_data AS job_type_table', 'job_type_table.id', '=', 'company_jobs.job_type_id')
            ->leftJoin('users', 'company_jobs.user_id', '=', 'users.id')
            ->leftJoin('companies', 'company_jobs.company_id', '=', 'companies.id')
            ->leftJoin('candidate_jobs', 'candidate_jobs.job_id', '=', 'company_jobs.id')
            ->where('company_jobs.user_id',$user_id)
            ->where('status','published')
            ->where('company_jobs.updated_at','>',date('Y-m-d H:i:s', strtotime('-'.env('JOBEXPIREVALIDITYDAYS').' days')))->orderBy('company_jobs.updated_at','ASC')->groupBy('id')
            ->skip(0)->take(5)->get();
        $ranges = [ // the start of each age-range.
            '<50%' => '0-50',
            '50%-75%' => '50-75',
            '>75%' => '75-100'
        ];
        $data = [];
        $graph1_data = [];
        $skills_dataset = [];
        foreach ($jobs as $key => &$job) {
            $graph1_data['title'] = "Open Positions";
            $graph1_data['labels'][] = $job->title;
            $job_required_skills = explode(",",$job->job_required_skills);
            $job_additional_skills = explode(",",$job->job_additional_skills);
            $job->matching_candidates = UserWorkProfileDetail::select('user_work_profiles.user_id')->join('user_work_profiles', 'user_work_profiles.id', '=', 'user_work_profile_details.user_work_profile_id')->where('type','skill')->where('skill_id','!=',null)->whereIN('skill_id',array_merge($job_required_skills,$job_additional_skills))->distinct('user_work_profile_id')->get();
            $job->skills_dataset = array_merge($job_required_skills,$job_additional_skills);
            $skills_dataset = array_merge($skills_dataset, $job_required_skills,$job_additional_skills);
            
        }

        $i=0;
        foreach ($ranges as $key => $value) {
            $graph1_data['data'][$i]['label'] = $key;
            $breakpointArr = explode("-",$value);
            foreach ($jobs as $key => $job) {
                $count = 0;
                foreach ($job->matching_candidates as $key => $candidate) {
                
                    $matchPercentage = CandidateJobs::candidateMatchPercentage($job->id,$candidate->user_id);
                    if ($matchPercentage >= $breakpointArr[0] && $matchPercentage <= $breakpointArr[1]) {
                        $count++;
                    }
                }
                $graph1_data['data'][$i]['data'][] = $count;
            }
            $graph1_data['data'][$i]['backgroundColor'] = $backgrounds[$i];
            $i++;
        }
        $graph1_data['jobs'] = $jobs;
        $data['graph1'] = $graph1_data;
        \DB::enableQueryLog();

        $locations = \DB::select("select master_data.id AS location_id, master_data.name AS location_name, master_data.description AS location_desc,count(*) AS location_count from master_data,users,user_preffered_data 

            where
            user_preffered_data.user_id = users.id
            and
            master_data.type = 'location' 
            and user_preffered_data.data_id = master_data.id

            group by master_data.id order by location_count DESC limit 3");
        $location_graph_data = [];
        $graph2_data = [];
        if (count($locations) > 0) {
            foreach ($locations as $key => $location) {
                $graph2_data['title'] = "Top 3 locations";
                $graph2_data['labels'][] = $location->location_name;
                $graph2_data['label_ids'][] = $location->location_id;
                $range_data = [];
                $range_data = User::role('candidate')
                ->select('user_work_profiles.total_experience AS total_experience')
                ->leftJoin('user_work_profiles', 'users.id', '=', 'user_work_profiles.user_id')
                ->leftJoin('user_work_profile_details', 'user_work_profiles.id', '=', 'user_work_profile_details.user_work_profile_id')
                ->leftJoin('user_preffered_data', 'users.id', '=', 'user_preffered_data.user_id')
                ->whereIn('user_work_profile_details.skill_id',$skills_dataset)
                ->where('user_preffered_data.data_id',$location->location_id)
                ->groupBy('users.id')
                ->get()
                ->map(function ($user) use ($ranges) {
                    $experience = (int) ($user->total_experience / 12);
                    foreach($ranges as $key => $breakpoint)
                    {
                        $breakpointArr = explode("-",$breakpoint);
                        if (isset($breakpointArr[1]) && $breakpointArr[1] != '')
                        {
                            if ($experience <= $breakpointArr[1] && $experience >= $breakpointArr[0]) {
                                $user->range = $key;
                                break;
                            }
                        }else{
                            if ($experience > $breakpointArr[0]) {
                                $user->range = $key;
                                break;
                            }
                        }
                    }

                    return $user;
                })
                ->mapToGroups(function ($user, $key) {
                    return [$user->range => $user];
                })
                ->map(function ($group) {
                    return count($group);
                })
                ->sortKeys();

                $location_graph_data[] = $range_data;
            }
        }

        for ($i=0; $i < 3; $i++) { 
            $graph2_data['data'][$i]['label'] = $rangesArr[$i];
            foreach ($location_graph_data as $key => $value) {
                $graph2_data['data'][$i]['data'][] = isset($value[$rangesArr[$i]]) ? $value[$rangesArr[$i]] : 0;
            }
            $graph2_data['data'][$i]['backgroundColor'] = $backgrounds[$i];
        }
        $data['graph2'] = $graph2_data;

        return $data;
    }

    public static function isJobHaveAllDetails($id)
    {
        $job = Job::with('requiredSkills','locations')->find($id);
        if($job->title && $job->description && $job->job_type_id && $job->salary_type_id && $job->minimum_experience_required && $job->maximum_experience_required && $job->min_salary && $job->max_salary && count($job->requiredSkills) > 0 && count($job->locations) > 0){
            return true;
        }else{

            return false;
        }
    }

    public static function reNewJob($id,$user_id,$date)
    {
        try {
            Job::find($id)->update([
                'id' => $id,
                'renew_date' => $date,
                'updated_at' => $date,
                'renew_by' => $user_id,
            ]);

            CompanyJobRenew::create([
                'company_job_id' => $id,
                'renew_date' => $date, 
                'renew_by' => $user_id
            ]);

            return true;
            
        } catch (Exception $e) {
            throw new Exception($e);
        }
        
    }

    public static function updateSearchableHash($id)
    {
        try {
            $job = Job::with('jobType','salaryType','industryDomain','workAuthorization','joiningPreference','jobDuration','locations','requiredSkills','additionalSkills','benefits')->find($id);
            $searchable_hash = '';
            $searchable_hash .= $job->title.SiteHelper::getSearchHashSeperator().' '.strip_tags($job->description).SiteHelper::getSearchHashSeperator().$job->minimum_experience_required.SiteHelper::getSearchHashSeperator().$job->maximum_experience_required.SiteHelper::getSearchHashSeperator().$job->min_salary.SiteHelper::getSearchHashSeperator().$job->max_salary;

            $searchable_hash .= (isset($job->jobType) && isset($job->jobType->name)) ? SiteHelper::getSearchHashSeperator().$job->jobType->name : '';
            $searchable_hash .= (isset($job->salaryType) && isset($job->salaryType->name)) ? SiteHelper::getSearchHashSeperator().$job->salaryType->name : '';
            $searchable_hash .= (isset($job->industryDomain) && isset($job->industryDomain->name)) ? SiteHelper::getSearchHashSeperator().$job->industryDomain->name : '';
            $searchable_hash .= (isset($job->workAuthorization) && isset($job->workAuthorization->name)) ? SiteHelper::getSearchHashSeperator().$job->workAuthorization->name : '';
            $searchable_hash .= (isset($job->joiningPreference) && isset($job->joiningPreference->name)) ? SiteHelper::getSearchHashSeperator().$job->joiningPreference->name : '';
            $searchable_hash .= (isset($job->jobDuration) && isset($job->jobDuration->name)) ? SiteHelper::getSearchHashSeperator().$job->jobDuration->name : '';
            $searchable_hash .= (isset($job->company) && isset($job->company->name)) ? SiteHelper::getSearchHashSeperator().$job->company->name : '';
            $searchable_hash .= (isset($job->company) && isset($job->company->website)) ? SiteHelper::getSearchHashSeperator().$job->company->website : '';

            if (isset($job->locations) && count($job->locations) > 0) {
                foreach ($job->locations as $key => $location) {
                    $searchable_hash .= (isset($location->name) && isset($location->description)) ? SiteHelper::getSearchHashSeperator().$location->name.SiteHelper::getSearchHashSeperator().$location->description : '';
                }
            }

            if (isset($job->requiredSkills) && count($job->requiredSkills) > 0) {
                foreach ($job->requiredSkills as $key => $skill) {
                    $searchable_hash .= (isset($skill->name)) ? SiteHelper::getSearchHashSeperator().$skill->name : '';
                }
            }

            if (isset($job->additionalSkills) && count($job->additionalSkills) > 0) {
                foreach ($job->additionalSkills as $key => $skill) {
                    $searchable_hash .= (isset($skill->name)) ? SiteHelper::getSearchHashSeperator().$skill->name : '';
                }
            }

            if (isset($job->benefits) && count($job->benefits) > 0) {
                foreach ($job->benefits as $key => $benefit) {
                    $searchable_hash .= (isset($benefit->name)) ? SiteHelper::getSearchHashSeperator().$benefit->name : '';
                }
            }

            $job->searchable_hash = $searchable_hash;
            $job->timestamps = false;
            $job->save();

            return true;
            
        } catch (Exception $e) {
            throw new Exception($e);
        }
        
    }

    public static function duplicateJob($id)
    {
        try {
            $model = Job::find($id);

            $model->load('company_jod_detail');

            $newModel = $model->replicate()->fill([
                            'status' => 'draft'
                        ]);
            $newModel->push();

            foreach($model->getRelations() as $relation => $items){
                foreach($items as $item){
                    unset($item->id);
                    $newModel->{$relation}()->create($item->toArray());
                }
            }

            return true;
            
        } catch (Exception $e) {

            throw new Exception($e);
            
        }
    }
}
