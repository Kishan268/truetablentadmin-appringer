<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\SiteHelper;
use Illuminate\Support\Facades\Auth;
use App\Models\Auth\User;
use App\Models\Referral;
use Illuminate\Notifications\Notifiable;

class ReferralUser extends Model
{
    use SoftDeletes;
    use Notifiable;
    

    protected $fillable = [
    	'referral_id',
    	'referred_by',
    	'referred_to',
    	'email',
        'first_name',
        'last_name',
        'phone_number',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $appends = [
        'reward',
        'reward_color',
        'candidate_status',
        'candidate_status_color',
        'referred_on',
    ];

    public function referral()
    {
        return $this->belongsTo(Referral::class);
    }

    public function referredToUser(){
        return $this->belongsTo('App\Models\Auth\User', 'referred_to', 'id');
    }

    public function referredByUser(){
        return $this->belongsTo('App\Models\Auth\User', 'referred_by', 'id');
    }

    public function getRewardAttribute(){

        if ($this->referred_to != null) {
            $user = User::find($this->referred_to);
            $progress = User::getUserProfileProgress($user);
            return $progress >= 100 ? 'View Reward' : 'Pending';
        }else{
            return 'Pending';
        }
    }

     public function getRewardColorAttribute(){

        if ($this->referred_to != null) {
            $user = User::find($this->referred_to);
            $progress = User::getUserProfileProgress($user);
            return $progress >= 100 ? 'success' : 'warning';
        }else{
            return 'warning';
        }
    }

    public function getCandidateStatusAttribute(){

        if ($this->referred_to != null) {
            $user = User::find($this->referred_to);
            $progress = User::getUserProfileProgress($user);
            return $progress >= 100 ? 'Eligible for referrals' : 'Profile Incomplete';
        }else{
            return 'Not Signed Up';
        }
    }



    public function getCandidateStatusColorAttribute(){

        if ($this->referred_to != null) {
            $user = User::find($this->referred_to);
            $progress = User::getUserProfileProgress($user);
            return $progress >= 100 ? 'success' : 'warning';
        }else{
            return 'danger';
        }
    }

    public function getReferredOnAttribute(){
        return date('d-m-Y', strtotime($this->created_at));
    }

    public static function add($request)
    {
        $referral = new ReferralUser();
        $data = $request->only($referral->getFillable());
        $referral->fill($data)->save();
        return $referral->id;
    }

    public static function addData($data)
    {
        $referral = ReferralUser::where('referral_id',$data['referral_id'])->where('referred_by',$data['referred_by'])->where('email',$data['email'])->first();
        if ($referral) {
            return ReferralUser::where('id',$referral->id)->update($data);
        }else{
            if (self::canAddReferralUser($data['referral_id'],$data['referred_by'])) {
                return ReferralUser::create($data);
            }
        }
        
    }

    public static function isReferralExist($email,$user_id,$referral_id)
    {
        $referral_user = ReferralUser::where('email',$email)->where('referred_by',$user_id)->where('referral_id',$referral_id)->first();

        if ($referral_user) {
            return true;
        }else{
            $user_exist = User::getUserByEmail($email);
            if ($user_exist) {
                return true;
            }else{
                return false;
            }
        }
    }

    public static function canAddReferralUser($referral_id,$referred_by)
    {
        $referral = Referral::find($referral_id);
        if ($referral->limit_per_user == null || $referral->limit_per_user == '') {
            return true;
        }else{
            $limit = $referral->limit_per_user;

            $referral_users_count = ReferralUser::where('referral_id',$referral_id)->where('referred_by',$referred_by)->count();

            return $referral_users_count < $limit;
        }
    }

    public static function getRemainingReferralsCount($referral_id,$referred_by)
    {
        $referral = Referral::find($referral_id);
        if ($referral->limit_per_user == null || $referral->limit_per_user == '') {
            return -1;
        }else{
            $limit = $referral->limit_per_user;

            $referral_users_count = ReferralUser::where('referral_id',$referral_id)->where('referred_by',$referred_by)->count();
            if ($limit < $referral_users_count) {
                return 0;
            }else{

                return $limit - $referral_users_count;
            }

        }
    }
}
