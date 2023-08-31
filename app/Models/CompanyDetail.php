<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyDetail extends Model
{

    protected $fillable = ['company_id','type','title','value','updated_at', 'created_at'];
    use SoftDeletes;


    public static function deleteData($company_id)
    {
        try {
        	CompanyDetail::where('company_id',$company_id)->delete();
        	return true;
        } catch (Exception $e) {
            return false;
            
        }
    }

    public static function addData($data)
    {
        try {
            CompanyDetail::create($data);
            return true;
        } catch (Exception $e) {
            return false;
            
        }
    }

    public function getValueAttribute($value){
        return ($this->attributes['type'] == 'medias' && $value != null && $value != '') ? \App\Helpers\SiteHelper::getObjectUrl($value) : $value;
    }

    public static function addCompanyDetail($data)
    {
        return CompanyDetail::create($data);
    }

    public static function getCompanyMedia($company_id)
    {
        return CompanyDetail::where('company_id',$company_id)->where('type','medias')->get();
    }


    public static function getCompanyMediasObject($medias)
    {
        $array = [];
        foreach ($medias as $media) {
            $obj = new \stdClass();
            $obj->uid = $media->id;
            $obj->name = '';
            $obj->status = 'done';
            $obj->url = $media->value;
            $array[] = $obj;
        }
        return $array;
    }
}
