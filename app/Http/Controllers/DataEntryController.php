<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auth\User;
use App\Models\UserWorkProfile;
use App\Models\MasterData;
use App\Models\UserWorkProfileDetail;
use Illuminate\Support\Facades\Hash;
use App\Helpers\SiteHelper;
use Illuminate\Support\Str;
use App\AppRinger\ImageUtils;
use App\Config\AppConfig;

class DataEntryController extends Controller
{
    public function insertData()
    {
    	$users = $this->getData();
    	$errors = [];
  		$i = 0;
  		$synced_id = AppConfig::getDataSyncedId();
  		foreach ($users as $key => $data) {
  			$user = [];
  			$user[] = '';
  			$user[] = trim($data->first_name);
  			$user[] = trim($data->last_name);
  			$user[] = trim($data->email);
  			$user[] = trim($data->mobile_number);
  			$user[] = MasterData::getMasterDataName($data->current_location) != null ? $data->current_location : 1;
  			$user[] = trim($data->highest_education);
  			$user[] = $data->highest_education_in_year;
  			$user[] = $data->total_exp;
  			$user[] = trim($data->current_company);
  			$user[] = $data->current_company_start_date;
  			$user[] = $data->current_company_end_date;
  			$user[] = trim($data->previous_company);
  			$user[] = $data->previous_company_start_date;
  			$user[] = $data->previous_company_end_date;
  			$user[] = trim($data->primary_skill_1);
  			$user[] = trim($data->primary_skill_2);
  			$user[] = trim($data->primary_skill_3);
  			$user[] = trim($data->secondary_skill_1);
  			$user[] = trim($data->secondary_skill_2);
  			$user[] = trim($data->secondary_skill_3);
  			$user[] = trim($data->exp_summary);
  			$user[] = isset($data->project_details) ? trim($data->project_details) : '';
  			$user[] = $data->resume;
	      	\DB::beginTransaction();
	      	try {
	      		$error_ar = [];
	      		$column = $key + 1;
	      		$email = SiteHelper::removeAllWhitespaces(trim($user[3]));
	      		if (!SiteHelper::checkValidRecord(trim($email)) || !preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) {
	      			$error_ar[] = 'Please insert a valid email('.$user[3].') at column '.$column;
	      		}
		        $checkUser = User::where('email',$email)->first();
		        if ($checkUser) {
		          
	          		$error_ar[] = 'User already exists at column '.$column;
		        }

		        if (count($error_ar) < 1 && SiteHelper::checkValidRecord($user[1])) {
					$createdUser = User::create([
						'first_name' => trim($user[1]),
						'last_name' => trim($user[2]),
						'email'     => $email,
						'password'  => Hash::make(substr(Str::random(), 0, 6)),
						'contact'   => SiteHelper::trimContactNumber($user[4]),
						'added_from'=> 'cron'
					]);

					$createdUser->assignRole(config('access.users.candidate_role'));

					$createdUserId = $createdUser->id;

					if($createdUserId)
					{
						$createdUserWorkProfile = UserWorkProfile::create([
							'user_id' => $createdUserId,
							'contact_number' => isset($user[4]) ? SiteHelper::trimContactNumber($user[4]) : '',
							'location_id' => isset($user[5]) ? trim($user[5]) : '',
							'location_name' => isset($user[5]) ? (MasterData::getMasterDataName($user[5]) != null ? MasterData::getMasterDataName(trim($user[5]))->name : 'India') : '',
							'summary' => isset($user[21]) ? trim($user[21]) : '',
							'total_experience' => isset($user[8]) ? (int)$user[8] * 12 : 0,
						]);
							

						if (isset($createdUserWorkProfile)) {
							if (str_contains($user[23], '\\')) {
							    $user[23] = str_replace('\\', '/', $user[23]);
							}


							if($user[23] && SiteHelper::checkValidRecord($user[23])){
				      			$file_path = \App\Helpers\SiteHelper::getDataEntryObjectUrl($user[23]);
					      		$file = pathinfo($file_path);
					      		$extension = explode("?",$file['extension'])[0];
					      		$temp_file = public_path().'/resumes/'.'temp.'.$extension;
					      		try{
		  							file_put_contents(
									    $temp_file,
									    file_get_contents( $file_path )
									);

						    	}catch(\Exception $e){
						    		$error_ar[] = 'Error occured '.$e->getMessage().' at column '.$column;
						    	}
				      			
					      		$filename = 'TT'.str_pad($createdUserId, 5, '0', STR_PAD_LEFT)."-".$createdUser->first_name.".".$extension;
					      		$key = date('m-Y').'/'.'resumes/'.$createdUserId.'/'.$filename;
					      		$s3Url = ImageUtils::uploadImageOnS3($temp_file,$key);
								$update_profile = UserWorkProfile::updateResume($key, $createdUserId);
					      	}

							$createdUserWorkProfileId = $createdUserWorkProfile->id;

							if ( $user[6] && SiteHelper::checkValidRecord($user[6])) {
								if (SiteHelper::checkValidRecord($user[7]) && strlen((string)$user[7]) == 4) {
									UserWorkProfileDetail::create([
										'user_id' => $createdUserId,
										'user_work_profile_id' => $createdUserWorkProfileId,
										'type' => 'degree',
										'title' => trim($user[6]),
										'to_date' => SiteHelper::checkValidRecord($user[7]) ? SiteHelper::getRealDate("01/01/".$user[7]) : null,
									]);
								}
								else{

									UserWorkProfileDetail::create([
										'user_id' => $createdUserId,
										'user_work_profile_id' => $createdUserWorkProfileId,
										'type' => 'degree',
										'title' => trim($user[6]),
									]);
								}
							}

							if ( $user[9] && $user[10] && SiteHelper::checkValidRecord($user[9]) ) {

								if (SiteHelper::isRealDate($user[10])) {
									
									UserWorkProfileDetail::create([
										'user_id' => $createdUserId,
										'user_work_profile_id' => $createdUserWorkProfileId,
										'type' => 'experience',
										'awarded_by' => trim($user[9]),
										'description' => (isset($user[22]) && SiteHelper::checkValidRecord($user[22])) ? trim($user[22]) : '',
										'from_date' => SiteHelper::checkValidRecord($user[10]) ? SiteHelper::getRealDate($user[10]) : null,
										'to_date' => (isset($user[11]) && SiteHelper::isRealDate($user[11])) ? ($user[11] == 'Present' || $user[11] == 'present') ? null : SiteHelper::checkValidRecord($user[11]) ? SiteHelper::getRealDate($user[11]) : null : null,
										'is_present' => ($user[11] == 'Present' || $user[11] == 'present') ? '1' : '0',
									]);
								}
								else{
									$error_ar[] = 'Invalid date format for experience at column '.$column;
								}

							}

							if ($user[12] && $user[13] && $user[14] && SiteHelper::checkValidRecord($user[12])) {

								if (SiteHelper::isRealDate($user[13]) && SiteHelper::isRealDate($user[14])) {
									UserWorkProfileDetail::create([
										'user_id' => $createdUserId,
										'user_work_profile_id' => $createdUserWorkProfileId,
										'type' => 'experience',
										'awarded_by' => trim($user[12]),
										'from_date' => SiteHelper::checkValidRecord($user[13]) ? SiteHelper::getRealDate($user[13]) : null,
										'to_date' => ($user[14] == 'Present' || $user[14] == 'present') ? null : SiteHelper::checkValidRecord($user[14]) ? SiteHelper::getRealDate($user[14]) : null,
										'is_present' => ($user[14] == 'Present' || $user[14] == 'present') ? '1' : '0',
									]);
								}
								else{
									$error_ar[] = 'Invalid date format for experience at column '.$column;
								}
							}
							for ($i=15; $i <21 ; $i++) {
								if ($user[$i] && $user[$i] != '' && $user[$i] != 'N/A' && $user[$i] != 'n/a') {
									$getSkill = MasterData::where('type','skills')->where('name',trim($user[$i]))->first();
									if($getSkill){
										$skill_id = $getSkill->id;
									}
									else{
										$getSkill = MasterData::create([
										'name' => trim($user[$i]),
										'type' => 'skills'
										]);

										$skill_id = $getSkill->id;

									}

									UserWorkProfileDetail::create([
										'user_id' => $createdUserId,
										'user_work_profile_id' => $createdUserWorkProfileId,
										'type' => 'skill',
										'skill_id' => $skill_id,
									]);
								}
							}
						}
						else{
							$error_ar[] = 'Unable to create user workprofile at column '.$column;
						}
					}
					else{
						$error_ar[] = 'Unable to create user at column '.$column;
					}
		          
		          

		      	}
		      	if (count($error_ar) < 1) {
		      		\DB::commit();
		      		$error_ar[] = 'Record successfully added at column '.$column;
		      	}else{
		      		\DB::rollback();
		      		$error_ar[] = 'Record added failed at column '.$column;
		      	}
		      	
		      	$synced_id = $data->id;
		      	$errors[] = $error_ar;
		      	
	      	} catch (Exception $e) {
	      		\DB::rollback();
	      		$error_ar[] = 'Error occured '.$e->getMessage().' at column '.$column;
	      		$error_ar[] = 'Record added failed at column '.$column;
	      		$errors[] = $error_ar;
	      		$synced_id = $data->id;
	      	}

  		}

  		AppConfig::updateDataSyncedId($synced_id);
  		return $errors;
    	
    }

    public function getData()
    {
    	$get_synced_id = AppConfig::getDataSyncedId();
    	$data_per_cycle = AppConfig::getDataPerCycle();
    	return \DB::connection('data_entry')->table('data')->where('id','>',$get_synced_id)->limit($data_per_cycle)->get();

    }
}
