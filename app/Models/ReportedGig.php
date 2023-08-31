<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportedGig extends Model
{
    protected $fillable = [
        'gig_id',
        'issue_id',
        'user_id',
        'flag_msg',
        'created_by',
        'updated_by'
    ];


    public static function checkUserReportedGig($user_id,$gig_id)
    {
        $isReported = ReportedGig::where('user_id',$user_id)->where('gig_id',$gig_id)->first();
        if ($isReported)
            return 1;
        else
            return 0;
    }

    public static function add($request, $user_id)
    {
        $ReportedGig = new ReportedGig();
        $data = $request->only($ReportedGig->getFillable());

        $ReportedGig->fill(array_merge($data, ['user_id' => $user_id]))->save();
        return $ReportedGig->id;
    }
}
