<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Helpers\SiteHelper;
use App\Models\CompanyGigRenew;
use Illuminate\Support\Facades\Auth;

class CompanyGig extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'title', 'description', 'gig_type_id','engagement_mode_id', 'min_budget', 'max_budget', 'company_id', 'status', 'close_reason_id', 'close_reason_description','searchable_hash','renew_date','created_by','updated_by','deleted_by'];

    protected $appends = [
        'posted_date', 'updated_date'
    ];

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

    public function details()
    {
        return $this->hasMany('App\Models\CompanyGigDetail');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Companies');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\MasterData', 'gig_type_id', 'id');
    }

    public function engagementMode()
    {
        return $this->belongsTo('App\Models\MasterData', 'engagement_mode_id', 'id');
    }

    public function locations()
    {
        return $this->belongsToMany('App\Models\MasterData', 'company_gig_details', 'company_gig_id', 'data_id')->wherePivot('type', 'locations');
    }

    public function skills()
    {
        return $this->belongsToMany('App\Models\MasterData', 'company_gig_details', 'company_gig_id', 'data_id')->wherePivot('type', 'required_skills');
    }

    public function applicants()
    {
        return $this->hasMany('App\Models\CandidateGig','gig_id','id');
    }

    public function getPostedDateAttribute(){
        return Carbon::parse($this->renew_date)->diffForHumans();
    } 

    public function getUpdatedDateAttribute(){
        return Carbon::parse($this->updated_at)->diffForHumans();
    }

    public static function addUpdateGig($request, $user_id, $company_id)
    {
        try {
            if (isset($request->id) && $request->id != null && $request->id != '') {
                $companyGig = CompanyGig::find($request->id);
            } else {
                $companyGig = new CompanyGig();
            }

            if ($companyGig == null) {
                $companyGig = new CompanyGig();
            }

            $data = $request->only($companyGig->getFillable());

            $companyGig->fill(array_merge($data, ['user_id' => $user_id, 'company_id' => $company_id, 'renew_date' => date('Y-m-d H:i:s')]))->save();

            return $companyGig;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function getUserAppliedGigs($user_id, $order_by)
    {
        return CompanyGig::with('type')->select('company_gigs.id', 'company_gigs.title', 'company_gigs.description', 'company_gigs.min_budget', 'company_gigs.max_budget', 'company_gigs.created_at','company_gigs.gig_type_id', DB::raw('group_concat(distinct gig_skill_table.name) as job_skills'), DB::raw('group_concat(distinct CONCAT(gig_location_table.name,", ",gig_location_table.description)) as gig_locations'), 'companies.name AS company_name','candidate_gigs.applied_at')
            ->leftJoin('company_gig_details AS gig_required_skills', function ($join) {
                $join->on('gig_required_skills.company_gig_id', '=', 'company_gigs.id');
                $join->where('gig_required_skills.type', '=', 'required_skills');
            })
            ->leftJoin('master_data AS gig_skill_table', 'gig_required_skills.data_id', '=', 'gig_skill_table.id')
            ->leftJoin('company_gig_details AS gig_locations', function ($join) {
                $join->on('gig_locations.company_gig_id', '=', 'company_gigs.id');
                $join->where('gig_locations.type', '=', 'locations');
            })
            ->leftJoin('master_data AS gig_location_table', 'gig_locations.data_id', '=', 'gig_location_table.id')
            // ->leftJoin('users', 'company_gigs.user_id', '=', 'users.id')
            ->leftJoin('companies', 'company_gigs.company_id', '=', 'companies.id')
            ->leftJoin('candidate_gigs', 'candidate_gigs.gig_id', '=', 'company_gigs.id')
            ->where(['candidate_id' => $user_id, 'applied' => 1])
            ->where('company_gigs.status', '!=', 'closed')
            ->orderBy('candidate_gigs.applied_at', $order_by)->groupBy('company_gigs.id')->get();
    }

    public static function getUserSavedGigs($user_id, $order_by)
    {
        return CompanyGig::with('type')->select('company_gigs.id', 'company_gigs.title', 'company_gigs.description', 'company_gigs.min_budget', 'company_gigs.max_budget', 'company_gigs.created_at','company_gigs.gig_type_id', DB::raw('group_concat(distinct gig_skill_table.name) as job_skills'), DB::raw('group_concat(distinct CONCAT(gig_location_table.name,", ",gig_location_table.description)) as gig_locations'), 'companies.name AS company_name')
            ->leftJoin('company_gig_details AS gig_required_skills', function ($join) {
                $join->on('gig_required_skills.company_gig_id', '=', 'company_gigs.id');
                $join->where('gig_required_skills.type', '=', 'required_skills');
            })
            ->leftJoin('master_data AS gig_skill_table', 'gig_required_skills.data_id', '=', 'gig_skill_table.id')
            ->leftJoin('company_gig_details AS gig_locations', function ($join) {
                $join->on('gig_locations.company_gig_id', '=', 'company_gigs.id');
                $join->where('gig_locations.type', '=', 'locations');
            })
            ->leftJoin('master_data AS gig_location_table', 'gig_locations.data_id', '=', 'gig_location_table.id')
            // ->leftJoin('users', 'company_gigs.user_id', '=', 'users.id')
            ->leftJoin('companies', 'company_gigs.company_id', '=', 'companies.id')
            ->leftJoin('candidate_gigs', 'candidate_gigs.gig_id', '=', 'company_gigs.id')
            ->where(['candidate_id' => $user_id, 'saved' => 1])
            ->where('company_gigs.status', '!=', 'closed')
            ->orderBy('company_gigs.updated_at', $order_by)->groupBy('company_gigs.id')->get();
    }

    public static function updateStatus($request)
    {
        $CompanyGig = CompanyGig::find($request->id);
        $data = $request->only($CompanyGig->getFillable());
        $CompanyGig->fill($data)->save();
        return $CompanyGig->id;
    }

    public static function isGigHaveAllDetails($id)
    {
        $gig = CompanyGig::with('skills','locations','engagementMode')->find($id);
        if(($gig->title && $gig->description && $gig->gig_type_id && $gig->min_budget && $gig->max_budget && count($gig->skills) > 0) && ($gig->engagementMode->name == "Remote" || count($gig->locations) > 0)){
            return true;
        }else{
            return false;
        }
    }
    public function getUidAttribute(){
        return 'TG-'.str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    public static function updateSearchableHash($id)
    {
        try {
            $gig = CompanyGig::with('locations','skills')->find($id);
            $searchable_hash = '';
            $searchable_hash .= $gig->title.SiteHelper::getSearchHashSeperator().' '.strip_tags($gig->description).SiteHelper::getSearchHashSeperator().$gig->min_budget.SiteHelper::getSearchHashSeperator().$gig->max_budget;

            $searchable_hash .= (isset($gig->type) && isset($gig->type->name)) ? SiteHelper::getSearchHashSeperator().$gig->type->name : '';
            $searchable_hash .= (isset($job->engagementMode) && isset($job->engagementMode->name)) ? SiteHelper::getSearchHashSeperator().$job->engagementMode->name : '';

            $searchable_hash .= (isset($gig->company) && isset($gig->company->name)) ? SiteHelper::getSearchHashSeperator().$gig->company->name : '';
            $searchable_hash .= (isset($gig->company) && isset($gig->company->website)) ? SiteHelper::getSearchHashSeperator().$gig->company->website : '';

            if (isset($gig->locations) && count($gig->locations) > 0) {
                foreach ($gig->locations as $key => $location) {
                    $searchable_hash .= (isset($location->name) && isset($location->description)) ? SiteHelper::getSearchHashSeperator().$location->name.' '.$location->description : '';
                }
            }

            if (isset($gig->skills) && count($gig->skills) > 0) {
                foreach ($gig->skills as $key => $skill) {
                    $searchable_hash .= (isset($skill->name)) ? SiteHelper::getSearchHashSeperator().$skill->name : '';
                }
            }

            $gig->searchable_hash = $searchable_hash;
            $gig->timestamps = false;
            $gig->save();

            return true;
            
        } catch (Exception $e) {
            throw new Exception($e);
        }
        
    }

    public static function reNewGig($id,$user_id,$date)
    {
        try {
            CompanyGig::find($id)->update([
                'id' => $id,
                'renew_date' => $date,
                'updated_at' => $date,
                'renew_by' => $user_id,
            ]);

            CompanyGigRenew::create([
                'company_gig_id' => $id,
                'renew_date' => $date, 
                'renew_by' => $user_id
            ]);

            return true;
            
        } catch (Exception $e) {
            throw new Exception($e);
        }
        
    }

    public static function duplicateGig($id)
    {
        try {
            $model = CompanyGig::find($id);

            $model->load('details');

            $newModel = $model->replicate()->fill([
                            'status' => 'draft'
                        ]);
            $newModel->push();

            foreach($model->getRelations() as $relation => $items){
                foreach($items as $item){
                    unset($item->id);
                    $newModel->{$relation}()->create($item->toArray());
                }
            }

            return true;
            
        } catch (Exception $e) {

            throw new Exception($e);
            
        }
    }
}
