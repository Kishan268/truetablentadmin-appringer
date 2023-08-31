<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyGigDetail extends Model
{
    protected $fillable = ['company_gig_id', 'data_id','type'];

    public static function add($data)
    {
        return CompanyGigDetail::create($data);
    }

    public static function deleteData($gig_id)
    {
        try {
        	CompanyGigDetail::where('company_gig_id',$gig_id)->delete();
        	return true;
        } catch (Exception $e) {
            #TODO throw error from here and catch it in controller catch function
            return false;
            
        }
    }
}
