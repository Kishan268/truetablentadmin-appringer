<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedCompanies extends Model{
	protected $fillable = ['candidate_id', 'company_id'];
    protected $table = 'blocked_companies';

    public static function getUserBlockedCompanies($user_id)
    {
    	return BlockedCompanies::where('candidate_id',$user_id)->pluck('company_id')->toArray();
    }

    public static function blockCompany($user_id, $company_id)
    {
    	return BlockedCompanies::create([
    		'candidate_id' => $user_id,
    		'company_id' => $company_id
    	]);
    }

    public static function unblockCompany($user_id, $company_id)
    {
    	return BlockedCompanies::where('candidate_id',$user_id)->where('company_id',$company_id)->delete();
    }
}
