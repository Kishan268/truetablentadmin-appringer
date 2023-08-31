<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CompanyGig;
use App\Models\CompanyGigDetail;

class CandidateGig extends Model
{
    protected $fillable = ['candidate_id','gig_id', 'recruiter_msg', 'applied', 'saved', 'recruiter_action','applied_at','saved_at'];

    public static function changeApplicantStatus($request)
    {
        return CandidateGig::where(['gig_id' => $request->gig_id, 'candidate_id' => $request->candidate_id])->update(['recruiter_action' => $request->recruiter_action]);
    }


    public static function isApplied($gig_id, $user_id)
    {
        $is_applied = CandidateGig::where(['candidate_id' => $user_id, 'gig_id' => $gig_id,'applied' => 1])->first();

        if ($is_applied != null)
            return 1;
        else
            return 0;
    }

    public static function isSaved($gig_id, $user_id)
    {
        $is_saved = CandidateGig::where(['candidate_id' => $user_id, 'gig_id' => $gig_id,'saved' => 1])->first();

        if ($is_saved != null)
            return 1;
        else
            return 0;
    }

    public static function getJobApplicantsCount($gig_id)
    {
        return CandidateGig::where('gig_id',$gig_id)->where('applied','1')->count();
    }

    public static function getGigApplicants($gig_id)
    {
        $data['applications'] = CandidateGig::select('candidate_gigs.recruiter_msg', 'candidate_gigs.recruiter_action', 'users.id AS user_id', 'users.email', 'users.first_name', 'users.last_name')->leftJoin('users','candidate_gigs.candidate_id','users.id')->where('candidate_gigs.gig_id',$gig_id)->where('recruiter_action', null)->where('applied',1)->get();

        foreach ($data['applications'] as $value) {
            $value->matchPercentage = CandidateGig::candidateMatchPercentage($gig_id,$value->user_id);
        }
        $data['shortlisted'] = CandidateGig::select('candidate_gigs.recruiter_msg', 'candidate_gigs.recruiter_action', 'users.id AS user_id', 'users.email', 'users.first_name', 'users.last_name')->leftJoin('users','candidate_gigs.candidate_id','users.id')->where('candidate_gigs.gig_id',$gig_id)->where('recruiter_action', 'shortlisted')->where('applied',1)->get();

        foreach ($data['shortlisted'] as $value) {
            $value->matchPercentage = CandidateGig::candidateMatchPercentage($gig_id,$value->user_id);
        }
        $data['rejected'] = CandidateGig::select('candidate_gigs.recruiter_msg', 'candidate_gigs.recruiter_action', 'users.id AS user_id', 'users.email', 'users.first_name', 'users.last_name')->leftJoin('users','candidate_gigs.candidate_id','users.id')->where('candidate_gigs.gig_id',$gig_id)->where('recruiter_action', 'rejected')->where('applied',1)->get();

        foreach ($data['rejected'] as $value) {
            $value->matchPercentage = CandidateGig::candidateMatchPercentage($gig_id,$value->user_id);
        }
        return $data;
    }

    public static function applyCandidateGig($request,$user_id)
    {
        $CandidateGig = CandidateGig::where(['gig_id' => $request->gig_id, 'candidate_id' => $user_id])->first();

        if($CandidateGig == null)
            $CandidateGig = new CandidateGig;

        $data = $request->only($CandidateGig->getFillable());

        if (isset($request->saved) && $request->saved == 1)
            $data = array_merge($data, [
                'saved_at' => date('Y-m-d H:i:s')
            ]);
        elseif (isset($request->applied) && $request->applied == 1)
            $data = array_merge($data, [
                'applied_at' => date('Y-m-d H:i:s')
            ]);

        $CandidateGig->fill(array_merge($data, [
            'candidate_id' => $user_id
        ]))->save();
        return $CandidateGig->id;
    }

    public static function candidateMatchPercentage($gig_id, $candidate_id)
    {
        $gig_required_skills = CompanyGigDetail::where('type','required_skills')->where('company_gig_id',$gig_id)->pluck('data_id')->toArray();
        
        $required_skill_percentage = env('WEIGHTAGE_REQUIRED_SKILLS');


        if (count($gig_required_skills) > 0) {
            $match_number = 0;
            foreach ($gig_required_skills as $required_skill) {
                
                $candidate_skill = UserWorkProfileDetail::where('skill_id',$required_skill)->where('user_id',$candidate_id)->first();
                if ($candidate_skill) {
                    $candidate_skill_experience = $candidate_skill->experience;
                    $match_number += 1;
                }
            }

            $required_skills_match = $match_number / count($gig_required_skills);
            $required_skill_percentage = $required_skills_match * env('WEIGHTAGE_REQUIRED_SKILLS');   
        }

        return $required_skill_percentage;
    }
}
