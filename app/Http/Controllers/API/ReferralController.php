<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;
use App\Communicator\Communicator;
use App\Constants\ResponseMessages;
use App\Constants\StringConstants;
use App\Models\Referral;
use App\Models\ReferralUser;
use App\Models\Companies;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;
use App\Mail\sendInvitation;
use App\AppRinger\Logger;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Frontend\ReferralNotification;

class ReferralController extends Controller
{

    public function getReferrals()
    {
        try {
            Logger::logDebug("[Get Referral API]");
            \DB::enableQueryLog();
            $user    = Auth::guard('api')->user();
            $now     = date('Y-m-d H:i:s');
            $my_jobs = Job::where('user_id',$user->id)->pluck('id')->toArray();
            $query = Referral::where('start_date','<=',$now)->where('end_date','>=',$now);
            if($user->isCandidate()){
                $query->where('user_type','candidate');
            }else{
                $query->where('user_type','companies');

            }

            $referrals = $query->where('company_job_id',null)->get();

            $query = Referral::where('start_date','<=',$now)->where('end_date','>=',$now);
            if($user->isCandidate()){
                $query->where('user_type','candidate');
            }else{
                $query->where('user_type','companies');

            }
            $query->whereNotNull('company_job_id');
            $query->orWhereIn('company_job_id',$my_jobs);
            $referral_jobs = $query->get();

            $data['referrals'] = $referrals;
            $data['referral_jobs'] = $referral_jobs;
            Logger::logDebug("Query build: " . json_encode(\DB::getQueryLog()));
            return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::SUCCESSS, $data));
            
        } catch (Exception $e) {
            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
        
    }

    public function getReferralUsers(Request $request)
    {
        try {
            $user    = Auth::guard('api')->user();
            $my_jobs = Job::where('user_id',$user->id)->pluck('id')->toArray();
            $referral_id = $request->referral_id;
            $referral_users = ReferralUser::with('referral','referredToUser')->where('referral_id',$request->referral_id)->where('referred_by',$user->id)->orWhereHas('referral',function($query) use ($my_jobs, $referral_id) {
                    $query->where('id', $referral_id);
                    $query->whereIn('company_job_id', $my_jobs);
                })->paginate(10);
            return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::SUCCESSS, $referral_users));
        } catch (Exception $e) {

            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
            
        }
    }

    public function sendInvitation(Request $request)
    {
        try {
            
            $referrals = json_decode($request->referrals, false);
            $id = $request->id;
            $link = $request->url;
            $user    = Auth::guard('api')->user();

            $is_error = false;
            $error = "Email already exist at row(s) ";
            foreach ($referrals as $key => $referral) {
                $row = $key+1;
                if(ReferralUser::isReferralExist($referral->email,$user->id,$id)){
                    if ($is_error) {
                        $error .= ', '.$row;
                    }else{
                        $error .= $row;

                    }
                    $is_error = true;
                }
            }
            if ($is_error) {
                return Communicator::returnResponse(ResponseMessages::ERROR($error, $error));
            }else{
                if (ReferralUser::canAddReferralUser($id,$user->id)) {
                    $remainingReferralCount = ReferralUser::getRemainingReferralsCount($id,$user->id);
                    if ($remainingReferralCount == -1 || count($referrals) <= $remainingReferralCount) {

                        foreach ($referrals as $referral) {
                            $first_name = $referral->first_name;
                            $last_name = $referral->last_name;
                            $email = $referral->email;
                            $phone_number = isset($referral->phone_number) ? $referral->phone_number : null;
                            $referral_details = Referral::find($id);
                            $referrer_name = $user->full_name;
                            $referee_name = $first_name.' '.$last_name;
                            if ($referral_details->target_audience == 'companies') {
                                $subject = notificationTemplates('send_invitation_comapany')->subject ? $referrer_name . notificationTemplates('send_invitation_comapany')->subject : $referrer_name.' is inviting you to join TrueTalent and hunt for curated geniuses';
                            }else{

                                $subject = notificationTemplates('send_invitation_other')->subject ? $referrer_name. notificationTemplates('send_invitation_other')->subject : $referrer_name.' is inviting you to apply for an exciting job on TrueTalent';
                            }
                            // \Mail::to($email)->send(new sendInvitation($link,$subject,$referrer_name,$referee_name,$referral_details->target_audience));
                            $data = [
                                'link' => $link, 
                                'referrer_name'=>$referrer_name,
                                'referee_name'=>$referee_name,
                                'target_audience'=>$referral_details->target_audience,
                                'phone_number'=>$phone_number
                            ];
                            $template = 'frontend.mail.sendInvitation';

                            $referralUser = new ReferralUser();
                            $referralUser->referral_id = $id;
                            $referralUser->referred_by = $user->id;
                            $referralUser->email = $email;
                            $referralUser->first_name = $first_name;
                            $referralUser->last_name = $last_name;
                            $referralUser->phone_number = $phone_number;
                            $referralUser->save();
                            $referralUserNoty = ReferralUser::where('id',$referralUser->id)->first();
                            $referralUserNoty->notify(new ReferralNotification($subject,$data, $template));
                        }
                    }else{
                        $error_msg = 'Your remaining count for this referral program is '.$remainingReferralCount;
                        return Communicator::returnResponse(ResponseMessages::ERROR($error_msg, $error_msg));
                    }
                }else{
                    $error_msg = 'You have exceeded the limit for this referral program';
                    return Communicator::returnResponse(ResponseMessages::ERROR($error_msg, $error_msg));
                }


                return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::INVITATION_SEND_SUCCESS_MSG, null));
            }

        } catch (Exception $e) {
            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }

    public function addJobReferral(Request $request)
    {
        try {
            
            $job_id = $request->job_id;
            $job_details = Job::find($job_id);
            $company_details = Companies::find($job_details->company_id);

            $duration = $request->duration;
            $amount = $request->amount;

            $start_date = date("Y-m-d 00:00:01");
            $end_date = date('Y-m-d 23:59:59', strtotime('+'.$duration.' days'));


            if ($job_details) {
                $referral = Referral::where('company_job_id',$job_details->id)->where('end_date', '>', date('Y-m-d H:i:s'))->first();
                if ($referral) {
                    return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::REFERRAL_EXISTS_FOR_JOB, StringConstants::REFERRAL_EXISTS_FOR_JOB));
                }else{

                    $referral = new Referral();
                    $referral->company_job_id = $job_details->id;
                    $referral->user_type = "candidate";
                    $referral->target_audience = "candidates";
                    $referral->program_name = $job_details->title." (".$company_details->name.")";
                    $referral->start_date = $start_date;
                    $referral->end_date = $end_date;
                    $referral->amount = $amount;
                    $referral->eligiblity_number = $request->referral_number;
                    $referral->data = json_encode(json_decode($request->referralData));
                    $referral->save();

                    return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::SUCCESSS, null));
                }
            }else{
                return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::SOMETHING_WRONG_MSG, StringConstants::SOMETHING_WRONG_MSG));
            }

        } catch (Exception $e) {
            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }

    public function endJobReferral($referral_id)
    {
        try {

            $referral = Referral::find($referral_id);
            $referral->end_date = date("Y-m-d 00:00:01");
            $referral->save();

            return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::JOB_END_REFERRAL_SUCCESS_MSG, null));
        } catch (Exception $e) {
            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }

}
