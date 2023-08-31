<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Auth\User;
use App\Models\MasterData;
use App\Constants\StringConstants;

class UserWorkProfileDetail extends Model
{
	protected $fillable = ['user_id', 'user_work_profile_id','type', 'title', 'description', 'awarded_by', 'experience', 'from_date', 'to_date', 'is_present', 'skill_id','remarks'];

	protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'laravel_through_key'];

	public function userWorkProfile()
    {
        return $this->belongsTo(UserWorkProfile::class);
    }

    public function skill()
    {
        return $this->belongsTo(MasterData::class, 'skill_id');
    }

	public static function add($data)
	{
		UserWorkProfileDetail::create($data);
	}

    public static function deleteData($work_profile_id)
    {
    	UserWorkProfileDetail::where('user_work_profile_id',$work_profile_id)->delete();
    }

    public static function getChartDataWithoutLogin($backgrounds, $rangesArr, $searched_skills, $searched_locations)
    {
    	$ranges = [ // the start of each age-range.
		    '0-5' => '0-60',
		    '5-10' => '61-120',
		    '10+' => '120-'
		];

		$data = [];
		$graph1_data = [];
		$graph1_data['title'] = StringConstants::TOP_5_SKILLS;

		if (count($searched_skills) > 0) {
			$graph1_data['title'] = StringConstants::DATA_BY_SKILLS;

			$skills = \DB::select("select master_data.id AS skill_id, master_data.name AS skill_name, count(*) AS skill_count
			from 
			master_data
			where
			master_data.id IN (".implode(',',$searched_skills).")
			
			group by master_data.id
			order by skill_count desc
			limit 5");
		}else{

			$preffered_skills = \DB::select("select `master_data`.`id` as `skill_id`, `master_data`.`name` as `skill_name`, COUNT(*) as skill_count, `users`.`is_preferred_skills` from `master_data`, `users`, `user_preffered_data`

				where
				master_data.type = 'skills'
				AND
				(users.is_preferred_skills = '1' and user_preffered_data.type = 'skills' and user_preffered_data.data_id = master_data.id and users.id = user_preffered_data.user_id)

				group by `master_data`.`id` order by `skill_count` desc limit 5 offset 0"
			);

			$workprofile_skills = \DB::select("select `master_data`.`id` as `skill_id`, `master_data`.`name` as `skill_name`, COUNT(*) as skill_count, `users`.`is_preferred_skills` from `master_data`, `users`, `user_work_profile_details`

				where 
				master_data.type = 'skills'
				AND
				(users.is_preferred_skills = '0' and user_work_profile_details.type = 'skill' and user_work_profile_details.skill_id = master_data.id and users.id = user_work_profile_details.user_id)

				group by `master_data`.`id` order by `skill_count` desc limit 5 offset 0"
			);


			$skills = array_merge($preffered_skills, $workprofile_skills);
			
			usort($skills, function($a, $b) {
			    return $a->skill_count <=> $b->skill_count;
			});

			$skills = array_slice($skills, -5);
			$skills = array_reverse($skills);
		}


    	$graph_data = [];
    	
	    if (count($skills) > 0) {
	    	foreach ($skills as $key => $skill) {
	    		
	    		$graph1_data['labels'][] = $skill->skill_name;
	    		$graph1_data['label_ids'][] = $skill->skill_id;
	    		$range_data = [];
	    		$skill_id = $skill->skill_id;
	    		$is_preferred_skills = isset($skill->is_preferred_skills) ? $skill->is_preferred_skills : '0';

			    $query = User::select('user_work_profiles.total_experience AS total_experience')
				->join('user_work_profiles', 'users.id', '=', 'user_work_profiles.user_id');
				
				
				if ($is_preferred_skills == 0) {
					$query->join('user_work_profile_details', 'user_work_profiles.id', '=', 'user_work_profile_details.user_work_profile_id');
    				$query->where('user_work_profile_details.skill_id', $skill_id);
				}
				else{

					$query->join('user_preffered_data', 'users.id', '=', 'user_preffered_data.user_id');
					$query->where('user_preffered_data.data_id', $skill_id);

                }
                $range_data = $query->where('users.company_id',NULL)
				->groupBy('users.id')
			    ->get()
			    ->map(function ($user) use ($ranges) {
			        $experience = (int) ($user->total_experience);
			        foreach($ranges as $key => $breakpoint)
			        {
			        	$breakpointArr = explode("-",$breakpoint);
			            if (isset($breakpointArr[1]) && $breakpointArr[1] != '')
			            {
			            	if ($experience <= $breakpointArr[1] && $experience >= $breakpointArr[0]) {
			            		$user->range = $key;
				                break;
			            	}
			            }else{
			            	if ($experience > $breakpointArr[0]) {
			            		$user->range = $key;
				                break;
			            	}
			            }
			        }

			        return $user;
			    })
			    ->mapToGroups(function ($user, $key) {
			        return [$user->range => $user];
			    })
			    ->map(function ($group) {
			        return count($group);
			    })
			    ->sortKeys();

			    $graph_data[] = $range_data;
	    	}
	    	if (count($skills) > 1) {
	    		
	    	
		    	$graph1_data['graph'] = "bar";
			    for ($i=0; $i < 3; $i++) { 
			    	$graph1_data['data'][$i]['label'] = $rangesArr[$i];
			    	foreach ($graph_data as $key => $value) {
			    		$graph1_data['data'][$i]['data'][] = isset($value[$rangesArr[$i]]) ? $value[$rangesArr[$i]] : 0;
			    	}
			    	$graph1_data['data'][$i]['backgroundColor'] = $backgrounds[$i];
			    }
			}else{
	    		$graph1_data['graph'] = "pie";
	    		$graph1_data['labels'] = [];
				for ($i=0; $i < 3; $i++) { 
		    		$graph1_data['labels'][] = $rangesArr[$i];
					$graph1_data['data'][0]['label'] = 'test';
			    	foreach ($graph_data as $key => $value) {
			    		$graph1_data['data'][0]['data'][] = isset($value[$rangesArr[$i]]) ? $value[$rangesArr[$i]] : 0;
			    	}
			    	$graph1_data['data'][0]['backgroundColor'][] = $backgrounds[$i];
			    }

			}
		}
	    $data['graph1'] = $graph1_data;
	    $graph2_data = [];
	    $graph2_data['title'] = StringConstants::TOP_5_LOCATIONS;
		if (count($searched_locations) > 0) {
			$graph2_data['title'] = StringConstants::DATA_BY_LOCATIONS;
			$locations = \DB::select("select master_data.id AS location_id, master_data.name AS location_name, master_data.description AS location_desc,count(*) AS location_count from master_data,users 

	    	where
	    	 master_data.id IN (".implode(',',$searched_locations).")

	    	group by master_data.id order by location_count DESC limit 5");
		}
		else{

			$locations = MasterData::select('master_data.id AS location_id','master_data.name AS location_name','master_data.description AS location_desc',\DB::raw('COUNT(*) as location_count'))
    				->join('user_preffered_data', 'master_data.id', '=', 'user_preffered_data.data_id')
    				->where('user_preffered_data.type','locations')
					->groupBy('user_preffered_data.data_id')
					->orderByDesc('location_count')
					->skip(0)->take(5)->get();

		}

	    $location_graph_data = [];
	    
	    if (count($locations) > 0) {
	    	foreach ($locations as $key => $location) {
	    		
	    		$graph2_data['labels'][] = $location->location_name;
	    		$graph2_data['label_ids'][] = $location->location_id;
	    		$range_data = [];
			    $range_data = User::role('candidate')
			    ->select('user_work_profiles.total_experience AS total_experience')
				->leftJoin('user_work_profiles', 'users.id', '=', 'user_work_profiles.user_id')
				->leftJoin('user_work_profile_details', 'user_work_profiles.id', '=', 'user_work_profile_details.user_work_profile_id')
				->leftJoin('user_preffered_data', 'users.id', '=', 'user_preffered_data.user_id')
				->where('user_preffered_data.data_id', $location->location_id)
				->orWhere('user_preffered_data.id',null)
				->groupBy('users.id')
			    ->get()
			    ->map(function ($user) use ($ranges) {
			        $experience = (int) ($user->total_experience);
			        foreach($ranges as $key => $breakpoint)
			        {
			        	$breakpointArr = explode("-",$breakpoint);
			            if (isset($breakpointArr[1]) && $breakpointArr[1] != '')
			            {
			            	if ($experience <= $breakpointArr[1] && $experience >= $breakpointArr[0]) {
			            		$user->range = $key;
				                break;
			            	}
			            }else{
			            	if ($experience > $breakpointArr[0]) {
			            		$user->range = $key;
				                break;
			            	}
			            }
			        }

			        return $user;
			    })
			    ->mapToGroups(function ($user, $key) {
			        return [$user->range => $user];
			    })
			    ->map(function ($group) {
			        return count($group);
			    })
			    ->sortKeys();

			    $location_graph_data[] = $range_data;
	    	}
	    

		    

		    if (count($locations) > 1) {
	    		
	    	
		    	$graph2_data['graph'] = "bar";
			    for ($i=0; $i < 3; $i++) { 
			    	$graph2_data['data'][$i]['label'] = $rangesArr[$i];
			    	foreach ($location_graph_data as $key => $value) {
			    		$graph2_data['data'][$i]['data'][] = isset($value[$rangesArr[$i]]) ? $value[$rangesArr[$i]] : 0;
			    	}
			    	$graph2_data['data'][$i]['backgroundColor'] = $backgrounds[$i];
			    }
			}else{
	    		$graph2_data['graph'] = "pie";
	    		$graph2_data['labels'] = [];
				for ($i=0; $i < 3; $i++) { 
		    		$graph2_data['labels'][] = $rangesArr[$i];
			    	foreach ($location_graph_data as $key => $value) {
			    		$graph2_data['data'][0]['data'][] = isset($value[$rangesArr[$i]]) ? $value[$rangesArr[$i]] : 0;
			    	}
			    	$graph2_data['data'][0]['backgroundColor'][] = $backgrounds[$i];
			    }

			}
		}
	    $data['graph2'] = $graph2_data;

	    return $data;
    }

    public static function checkUserProfileExist($user_id)
    {
        $get_profile = UserWorkProfileDetail::where('user_id', $user_id)->get();
        if ($get_profile->count() > 0)
            return true;
        else
            return false;
    }

    public static function getDataByType($user_id, $type)
    {
        return UserWorkProfileDetail::where('user_id', $user_id)->where('type', $type)->get();
      
    }
}
