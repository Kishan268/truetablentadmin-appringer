<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportedJobs extends Model{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'job_id',
        'issue_id',
        'user_id',
        'flag_msg',
        'created_by',
        'updated_by'
    ];

    public function job(){
        return $this->hasOne('App\Models\Job', 'id', 'job_id');
    }

    public function user(){
        return $this->hasOne('App\Models\Auth\User', 'id', 'user_id');
    }

    public function issue(){
        return $this->hasOne('App\Models\MasterData', 'id', 'issue_id');
    }

    public static function add($request, $user_id)
    {
        $ReportedJobs = new ReportedJobs();
        $data = $request->only($ReportedJobs->getFillable());

        $ReportedJobs->fill(array_merge($data, ['user_id' => $user_id]))->save();
        return $ReportedJobs->id;
    }

    public static function checkUserReportedJob($user_id,$job_id)
    {
        $isReported = ReportedJobs::where('user_id',$user_id)->where('job_id',$job_id)->first();
        if ($isReported)
            return 1;
        else
            return 0;
    }
}
