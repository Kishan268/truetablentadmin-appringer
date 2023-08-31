<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Companies extends Model
{
    protected $fillable = ['name', 'logo', 'cover_pic', 'description', 'website', 'address', 'location_id', 'size_id', 'industry_domain_id', 'facebook', 'linkedin', 'twitter', 'instagram', 'equal_opportunity_employer','created_by','updated_by','deleted_by','is_deleted'];


    use SoftDeletes;

    // protected static function boot() {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->created_by = is_object(Auth::guard('api')->user()) ? Auth::guard('api')->user()->id : is_object(Auth::guard('web')->user()) ? Auth::guard('web')->user()->id : NULL;
    //         $model->updated_by = NULL;
    //     });

    //     static::updating(function ($model) {
    //         $model->updated_by = is_object(Auth::guard('api')->user()) ? Auth::guard('api')->user()->id : is_object(Auth::guard('web')->user()) ? Auth::guard('web')->user()->id : NULL;
    //     });

    //     static::deleting(function ($model) {
    //         $model->deleted_by = is_object(Auth::guard('api')->user()) ? Auth::guard('api')->user()->id : is_object(Auth::guard('web')->user()) ? Auth::guard('web')->user()->id : NULL;
    //     });
    // }

    public function view_transactions()
    {
        return $this->hasMany('App\Models\ProfileViewTransactions', 'company_id', 'id');
    }

    public function jobs()
    {
        return $this->hasMany('App\Models\Job', 'company_id', 'id')->where('company_jobs.status', 'published');
    }

    public function gigs()
    {
        return $this->hasMany('App\Models\CompanyGig', 'company_id', 'id')->where('company_gigs.status', 'published');
    }

    public function details()
    {
        return $this->hasMany('App\Models\CompanyDetail', 'company_id', 'id');
    }

    public function size()
    {
        return $this->hasOne('App\Models\MasterData', 'id', 'size_id');
    }

    public function industry()
    {
        return $this->hasOne('App\Models\MasterData', 'id', 'industry_domain_id');
    }

    public function getUpdatedAtAttribute()
    {
        return Carbon::parse($this->attributes['updated_at'])->diffForHumans();
    }

    public function getRemainingViewsAttribute()
    {
        // dd($this->latest_view_transaction);
        $remaining = 0;
        $transaction = $this->view_transactions()
                        ->where('profile_view_transactions.user_id' ,null)
                        ->orderByDesc('id')
                        ->first();
        if ($transaction) {
            $remaining = $transaction->remaining;
        }
        // if ($this->view_transactions->last() && $this->view_transactions->last() != null) {
        //     $remaining = $this->view_transactions->last()->remaining;
        // }
        return $remaining;
    }

    protected $appends = ['remaining_views'];

    public static function createCompany($data)
    {
        try {
            $company = new Companies;
            $company->name = $data['company_name'];
            $company->website = $data['website'];
            $company->cover_pic = isset($data['cover_pic']) ? $data['cover_pic'] : null;
            $company->logo = isset($data['logo']) ? $data['logo'] : null;
            $company->location_id = isset($data['location']) ? $data['location'] : null;
            if (isset($data['company_size'])) {
                $company->size_id = $data['company_size'];
            }
            $company->industry_domain_id = isset($data['industry_domain']) ? $data['industry_domain'] : null;
            if (!$company->save()) {
                return false;
            }
            $company_id = $company->id;
            return $company_id;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function getList()
    {
        return Companies::select('id', 'name', 'description', 'website', 'location_id', 'size_id AS size', 'industry_domain_id AS industry_domain', 'facebook', 'linkedin', 'twitter', 'equal_opportunity_employer')->get()->makeHidden(['remaining_views', 'view_transactions']);
    }


    public static function updateLogo($filename, $company_id)
    {
        $status = false;
        $Company = Companies::find($company_id);
        if ($Company->count()) {
            if ($Company->update(['logo' => $filename])) {
                $status = true;
            }
        }

        return $status;
    }

    public static function updateCompany($data, $id)
    {
        return Companies::where('id', $id)->update($data);
    }

    public function getLogoAttribute($value)
    {
        return ($value != null && $value != '') ? \App\Helpers\SiteHelper::getObjectUrl($value) : $value;
    }

    public function getCoverPicAttribute($value)
    {
        return ($value != null && $value != '') ? \App\Helpers\SiteHelper::getObjectUrl($value) : $value;
    }

    public static function isCompanyActive($id)
    {
        return Companies::find($id) ? true : false;
    }

    public static function addUpdatedata($request)
    {
        try {
            if (isset($request->id) && $request->id != null && $request->id != '') {
                $company = Companies::find($request->id);
            } else {
                $company = new Companies();
            }

            if (isset($request->instagram) && strpos($request->instagram, 'http') === false && strpos($request->instagram, 'https') === false) {
                $request->merge([
                    'instagram' => 'http://' .$request->instagram,
                ]);
            }

            if (isset($request->linkedin) && strpos($request->linkedin, 'http') === false && strpos($request->linkedin, 'https') === false) {
                $request->merge([
                    'linkedin' => 'http://' .$request->linkedin,
                ]);
            }

            if (isset($request->twitter) && strpos($request->twitter, 'http') === false && strpos($request->twitter, 'https') === false) {
                $request->merge([
                    'twitter' => 'http://' .$request->twitter,
                ]);
            }

            if (isset($request->facebook) && strpos($request->facebook, 'http') === false && strpos($request->facebook, 'https') === false) {
                $request->merge([
                    'facebook' => 'http://' .$request->facebook,
                ]);
            }

            $data = $request->only($company->getFillable());

            $company->fill($data)->save();

            return $company;
        } catch (Exception $e) {
            return '';
        }
    }

    public static function getCompanyProgress($company)
    {
        $progress = 0;
        // if ($user->hasVerifiedEmail())
        //     $progress = $progress + 25;
        if ($company->name != null && $company->name != '')
            $progress = $progress + 20;
        if ($company->logo != null && $company->logo != '')
            $progress = $progress + 20;
        if ($company->cover_pic != null && $company->cover_pic != '')
            $progress = $progress + 20;
        if ($company->facebook != null && $company->facebook != '')
            $progress = $progress + 10;
        if ($company->instagram != null && $company->instagram != '')
            $progress = $progress + 10;
        if ($company->linkedin != null && $company->linkedin != '')
            $progress = $progress + 10;
        if ($company->twitter != null && $company->twitter != '')
            $progress = $progress + 10;

        return $progress;
    }
}
