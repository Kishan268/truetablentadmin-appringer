<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomepageLogo extends Model
{
	use SoftDeletes;
	protected $fillable = ['logo_url','website_link','order','company_id'];


	public static function getOrderableLogos()
	{
		return HomepageLogo::orderBy('order','ASC')->whereNotNull('order')->with('company')->get();

	}

	public function getLogoUrlAttribute($value){
        return ($value != null && $value != '') ? \App\Helpers\SiteHelper::getObjectUrl($value) : $value;
    }
    public function company(){
        return $this->belongsTo('App\Models\Companies', 'company_id', 'id');

    }
}
