<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\SiteHelper;
use Illuminate\Support\Facades\Auth;
use App\Models\UserReferralCode;
use App\Models\Job;

class Referral extends Model
{
    use SoftDeletes;

    protected $fillable = [
    	'user_type',
        'target_audience',
    	'program_name',
    	'program_description',
    	'program_image',
    	'limit_per_user',
    	'start_date',
    	'end_date',
    	'type',
    	'amount',
    	'eligiblity_number',
        'company_job_id',
        'data'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $appends = [
        'url',
        'code',
        'days_left',
        'referral_data'
    ];

    public function referralUsers()
    {
        return $this->hasMany(ReferralUser::class);
    }

    public function jobData()
    {
        return $this->belongsTo(Job::class,'company_job_id','id');
    }


    public function getUrlAttribute(){
    	$user    = Auth::guard('api')->user();
        $code = UserReferralCode::getCode($this->id);
        return env('FRONTEND_URL').'/referral/'.SiteHelper::createSlug($this->program_name).'?code='.$code.'&audience='.$this->target_audience;
    }

    public function getCodeAttribute(){
        return UserReferralCode::getCode($this->id);
    }

    public function getReferralDataAttribute(){
        return $this->data != null && $this->data != '' ? json_decode($this->data) : '';
    }

    public function getDaysLeftAttribute(){
        $today = date_create(date("Y-m-d H:i:s"));
        $end_date = date_create($this->end_date);
        if ($today > $end_date) {
            return 0;
        }else{

            $diff = date_diff($end_date, $today);
            return $diff->days;
        }
    }

    public static function add($request)
    {
        $referral = new Referral();
        $request->merge([
            'start_date' => date('Y-m-d', strtotime($request->start_date))." 00:00:01",
        ]);
        $request->merge([
            'end_date' => date('Y-m-d', strtotime($request->end_date))." 23:59:59",
        ]);
        $data = $request->only($referral->getFillable());
        $referral->fill($data)->save();
        return $referral->id;
    }

    public static function updateData($id, $request)
    {
        $referral = Referral::find($id);
        $request->merge([
            'start_date' => date('Y-m-d', strtotime($request->start_date))." 00:00:01",
        ]);
        $request->merge([
            'end_date' => date('Y-m-d', strtotime($request->end_date))." 23:59:59",
        ]);
        $data = $request->only($referral->getFillable());
        $referral->fill($data)->save();
        return $referral->id;
    }
}
