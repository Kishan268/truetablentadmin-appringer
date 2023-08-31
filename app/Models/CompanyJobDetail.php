<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyJobDetail extends Model
{
	protected $fillable = ['company_job_id', 'data_id','type'];

    public static function add($data)
    {
        return CompanyJobDetail::create($data);
    }

    public static function deleteData($job_id)
    {
        try {
        	CompanyJobDetail::where('company_job_id',$job_id)->delete();
        	return true;
        } catch (Exception $e) {
            #TODO throw error from here and catch it in controller catch function
            return false;
            
        }
    }
}
