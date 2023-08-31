<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserReferralCode extends Model
{
    protected $fillable = [
    	'referral_id',
    	'user_id',
    	'referral_code'
    ];


    public static function getCode($referral_id)
    {
    	$user = Auth::guard('api')->user();
        if ($user) {
            $user_id = $user->id;

        	$code = UserReferralCode::where('referral_id',$referral_id)->where('user_id',$user_id)->first();
        	if ($code) {
        		return $code->referral_code;
        	}else{
        		$code = UserReferralCode::create([
        			'referral_id' => $referral_id,
        			'user_id'     => $user_id,
        			'referral_code' => substr($user->full_name, 0, 3). rand (100,9999),
        		]);
        		return $code->referral_code;
        	}
        }else{
            return "";
        }
    }
}
