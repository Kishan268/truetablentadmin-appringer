<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Job;
use App\Models\UserWorkProfileDetail;
use App\Models\CompanyJobDetail;
use App\Models\JobAdditionalSkill;
use App\Models\ReferralUser;

class CandidateJobs extends Model
{
	protected $fillable = ['candidate_id','job_id', 'recruiter_msg', 'applied', 'saved', 'recruiter_action','applied_at','saved_at','is_profile_match','reason_id','recruiter_comment'];

    protected $appends = [
        'is_referred_candidate',
    ];

    public function getIsReferredCandidateAttribute(){

        $referral_data = ReferralUser::with('referral','referredByUser','referral.jobData','referral.jobData.company_details')->where('referred_to',$this->candidate_id)->whereHas('referral',function($query) {
                                $query->where('company_job_id',$this->job_id);
                            })->first();
        if ($referral_data) {
            return true;
        }else{
            return false;
        }
    }

    public function job(){
    	return $this->hasOne('App\Models\Job', 'id', 'job_id');
    }

    public function candidate(){
    	return $this->hasOne('App\Models\Auth\User', 'id', 'candidate_id');
    }

    public function candidates(){
        return $this->belongsToMany('App\Models\Job', 'id', 'candidate_id');
    }

    public function scopeApplied($query){
    	return $query->where('applied', 1);
    }

    public static function applyCandidateJob($request,$user_id)
    {
    	$candidateJob = CandidateJobs::where(['job_id' => $request->job_id, 'candidate_id' => $user_id])->first();

    	if($candidateJob == null)
            $candidateJob = new CandidateJobs;

        $data = $request->only($candidateJob->getFillable());
        if (isset($request->saved) && $request->saved == 1)
            $data = array_merge($data, [
                'saved_at' => date('Y-m-d H:i:s')
            ]);
        elseif (isset($request->applied) && $request->applied == 1)
            $data = array_merge($data, [
                'applied_at' => date('Y-m-d H:i:s')
            ]);

        $candidateJob->fill(array_merge($data, [
        	'candidate_id' => $user_id
        ]))->save();
    	return $candidateJob->id;
    }

    public static function changeApplicantStatus($request)
    {
        $CandidateJobs =  CandidateJobs::where(['job_id' => $request->job_id, 'candidate_id' => $request->candidate_id])->first();
        $data = $request->only($CandidateJobs->getFillable());
        return $CandidateJobs->fill($data)->save();
    }

    public static function getUserJobs($user_id)
    {
        $jobs = [];
        $jobs['applied'] = CandidateJobs::where(['candidate_id' => $user_id, 'applied' => 1])->has('job')->get();
        $jobs['saved'] = CandidateJobs::where('candidate_id', $user_id)->where('saved', 1)->has('job')->get();
        return $jobs;
    }

    public static function isApplied($job_id, $user_id)
    {
        $is_applied = CandidateJobs::where(['candidate_id' => $user_id, 'job_id' => $job_id,'applied' => 1])->first();

        if ($is_applied != null)
            return 1;
        else
            return 0;
    }

    public static function isSaved($job_id, $user_id)
    {
        $is_saved = CandidateJobs::where(['candidate_id' => $user_id, 'job_id' => $job_id,'saved' => 1])->first();

        if ($is_saved != null)
            return 1;
        else
            return 0;
    }

    public static function getJobApplicantsCount($job_id)
    {
        return CandidateJobs::where('job_id',$job_id)->where('applied','1')->count();
    }

    public static function getJobApplicants($job_id)
    {
        $data['applications'] = CandidateJobs::select('candidate_jobs.*','users.id AS user_id', 'users.email', 'users.first_name', 'users.last_name')->leftJoin('users','candidate_jobs.candidate_id','users.id')->where('candidate_jobs.job_id',$job_id)->where('recruiter_action', null)->where('applied','1')->get();

        foreach ($data['applications'] as $value) {
            $value->matchPercentage = CandidateJobs::candidateMatchPercentage($job_id,$value->user_id);
        }

        $data['shortlisted'] = CandidateJobs::select('candidate_jobs.*', 'users.id AS user_id', 'users.email', 'users.first_name', 'users.last_name')->leftJoin('users','candidate_jobs.candidate_id','users.id')->where('candidate_jobs.job_id',$job_id)->where('recruiter_action', 'shortlisted')->where('applied','1')->get();

        foreach ($data['shortlisted'] as $value) {
            $value->matchPercentage = CandidateJobs::candidateMatchPercentage($job_id,$value->user_id);
        }

        $data['rejected'] = CandidateJobs::select('candidate_jobs.*', 'users.id AS user_id', 'users.email', 'users.first_name', 'users.last_name')->leftJoin('users','candidate_jobs.candidate_id','users.id')->where('candidate_jobs.job_id',$job_id)->where('recruiter_action', 'rejected')->where('applied','1')->get();

        foreach ($data['rejected'] as $value) {
            $value->matchPercentage = CandidateJobs::candidateMatchPercentage($job_id,$value->user_id);
        }

        return $data;
    }

    public static function candidateMatchPercentage($job_id, $candidate_id)
    {
        $job_details = Job::find($job_id);
        $job_required_skills = CompanyJobDetail::where('type','required_skills')->where('company_job_id',$job_id)->pluck('data_id')->toArray();
        $job_additional_skills = CompanyJobDetail::where('type','additional_skills')->where('company_job_id',$job_id)->pluck('data_id')->toArray();
        $minimum_experience_required = $job_details->minimum_experience_required;
        $industry_domain_id = $job_details->industry_domain_id;
        $required_skill_percentage = env('WEIGHTAGE_REQUIRED_SKILLS');
        $additional_skill_percentage = env('WEIGHTAGE_ADDITIONAL_SKILLS');
        $inustry_domain_percentage = env('WEIGHTAGE_INDUSTRY_DOMAIN');

        if ($minimum_experience_required != null && $minimum_experience_required != '' && $minimum_experience_required > 0) 
        {
            if (count($job_required_skills) > 0) {
                $match_number = 0;
                foreach ($job_required_skills as $required_skill) {
                    
                    $candidate_skill = UserWorkProfileDetail::where('skill_id',$required_skill)->where('user_id',$candidate_id)->first();

                    if ($candidate_skill) {
                        $candidate_skill_experience = $candidate_skill->experience;
                        if ($candidate_skill_experience >= $minimum_experience_required) {
                            $match_number += 1;
                        }else{
                            $match_number += $candidate_skill_experience / $minimum_experience_required;
                        }
                    }
                }

                $required_skills_match = $match_number / count($job_required_skills);
                $required_skill_percentage = $required_skills_match * env('WEIGHTAGE_REQUIRED_SKILLS');   
            }
            
            if (count($job_additional_skills) > 0) {
                $match_number = 0;
                foreach ($job_additional_skills as $additional_skill) {
                    
                    $candidate_skill = UserWorkProfileDetail::where('skill_id',$additional_skill)->where('user_id',$candidate_id)->first();
                    if ($candidate_skill) {
                        $candidate_skill_experience = $candidate_skill->experience;
                        if ($candidate_skill_experience >= $minimum_experience_required) {
                            $match_number += 1;
                        }else{
                            $match_number += $candidate_skill_experience / $minimum_experience_required;
                        }
                    }
                }

                $additional_skills_match = $match_number / count($job_additional_skills);
                $additional_skill_percentage = $additional_skills_match * env('WEIGHTAGE_ADDITIONAL_SKILLS');
            }
            
        }

        // if ($industry_domain_id != null && $industry_domain_id != '' && $industry_domain_id > 0) {
            
        // }

        return $required_skill_percentage + $additional_skill_percentage + $inustry_domain_percentage;
        
    }
}
