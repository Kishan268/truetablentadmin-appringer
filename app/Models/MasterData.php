<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\API\GetMasterDataRequest;
use App\Models\Auth\User;
use App\Models\UserPrefferedData;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterData extends Model
{
   use SoftDeletes;
    protected $table = 'master_data';
    protected $fillable = ['type', 'name', 'description', 'value','verified'];

    public static function getMasterData($types = [], $order_by = 'order')
    {
        if (count($types) > 0)
            return MasterData::select('id', 'type', 'name','value', 'description')->whereIn('type',$types)->orderBy($order_by,'ASC')->get()->groupBy('type');
        else
            return MasterData::select('id', 'type', 'name','value', 'description')->where('type','!=','location')->where('type','!=','skills')->orderBy($order_by,'ASC')->get()->groupBy('type');
    }

    public static function getLocations(GetMasterDataRequest $request)
    {
        // if ($request->has('type') && $request->type == 'USER_PROFILE_LOCATIONS') {
        //     $user_locations = UserPrefferedData::where('type','locations')->pluck('data_id')->toArray();
        //     $user_locations = array_unique($user_locations);
        //     return MasterData::select('master_data.id','master_data.type','master_data.name','master_data.description','master_data.value')
        //     ->where('master_data.type','location')
        //     ->whereIn('master_data.id', $user_locations)
        //     ->groupBy('master_data.id')
        //     ->orderBy('master_data.name','ASC')
        //     ->orderBy('master_data.description','ASC')
        //     ->get();
            
        // }elseif($request->has('type') && $request->type == 'GIG_LOCATIONS'){
        //     return MasterData::select('master_data.id','master_data.type','master_data.name','master_data.description','master_data.value')
        //         ->leftJoin('company_gig_details','company_gig_details.data_id','=','master_data.id')
        //         ->leftJoin('company_gigs','company_gigs.id','=','company_gig_details.company_gig_id')
        //         ->where('master_data.type','location')
        //         ->where('company_gigs.status','published')
        //         ->groupBy('master_data.id')
        //         ->orderBy('master_data.name','ASC')
        //         ->orderBy('master_data.description','ASC')
        //         ->get();
        // }   
        // else{

        //     return MasterData::select('master_data.id','master_data.type','master_data.name','master_data.description','master_data.value')
        //         ->leftJoin('company_job_details','company_job_details.data_id','=','master_data.id')
        //         ->leftJoin('company_jobs','company_jobs.id','=','company_job_details.company_job_id')
        //         ->where('master_data.type','location')
        //         ->where('company_jobs.status','published')
        //         ->groupBy('master_data.id')
        //         ->orderBy('master_data.name','ASC')
        //         ->orderBy('master_data.description','ASC')
        //         ->get();

        // }

        return MasterData::select('master_data.id','master_data.type','master_data.name','master_data.description','master_data.value')
                ->leftJoin('company_job_details','company_job_details.data_id','=','master_data.id')
                ->leftJoin('company_jobs','company_jobs.id','=','company_job_details.company_job_id')
                ->where('master_data.type','location')
                ->groupBy('master_data.id')
                ->orderBy('master_data.order','ASC')
                ->orderBy('master_data.name','ASC')
                ->orderBy('master_data.description','ASC')
                ->get();
        
    }

    public static function addSkill($skill_name)
    {
        $add_skill = MasterData::create([
            'name' => $skill_name,
            'type' => 'skills'
        ]);
        if ($add_skill)
            return true;
        else
            return false;
    }

    public static function getSkills()
    {
        return MasterData::select('id', 'type', 'name')->where('type','skills')->get();
    }

    public static function checkSkillExists($name)
    {
        return MasterData::select('id', 'type', 'name')->where('type','skills')->where(\DB::raw('lower(name)'), strtolower($name))->first();
    }

    public static function getNameFromArray($array)
    {
        return MasterData::whereIn('id',$array)->get();
        
    }

    public static function getMasterDataName($id)
    {
        return MasterData::find($id);
    }

    public static function existsData($data)
    {
        return MasterData::where('type',$data['type'])->where('name',$data['name'])->where('description',$data['description'])->first() ? true : false;
    }

    public static function getSkillsName($array)
    {
        return MasterData::whereIn('id',$array)->pluck('name')->toArray();
        
    }

    public static function getSameSkillsIds($name)
    {
        return MasterData::where('type','skills')->where(\DB::raw('lower(name)'),'LIKE','%' . strtolower($name) . '%')->pluck('id')->toArray();
        
    }
}
