<?php

namespace App\Imports;

use App\Models\Auth\User;
use App\Models\UserWorkProfile;
use App\Models\MasterData;
use App\Models\UserWorkProfileDetail;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Helpers\SiteHelper;
use Illuminate\Support\Str;
use App\AppRinger\ImageUtils;

class UsersImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return User|null
     */

    public $response;

    public function collection(Collection $rows)
    {
      	$transposedData = array_map(function (...$rows) {
      		return $rows;
      	}, ...$rows->values()->toArray());

		$users = collect($transposedData);

		$headers = $users->shift();
  		$errors = [];
  		$i = 0;
  		foreach ($users as $key => $user) {
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
						'added_from'=> 'csv'
					]);

					$createdUser->assignRole(config('access.users.candidate_role'));

					$createdUserId = $createdUser->id;

					if($createdUserId)
					{
						$createdUserWorkProfile = UserWorkProfile::create([
							'user_id' => $createdUserId,
							'contact_number' => isset($user[4]) ? SiteHelper::trimContactNumber($user[4]) : '',
							'location_name' => isset($user[5]) ? trim($user[5]) : '',
							'summary' => isset($user[21]) ? trim($user[21]) : '',
							'total_experience' => isset($user[8]) ? (int)$user[8] * 12 : 0,
						]);

						if (isset($createdUserWorkProfile)) {
							if (str_contains($user[23], '\\')) {
							    $user[23] = str_replace('\\', '/', $user[23]);
							}

							if($user[23] && SiteHelper::checkValidRecord($user[23]) && file_exists( public_path().'/resumes/'.$user[23] )){
				      			$file_path = public_path().'/resumes/'.$user[23];
					      		$file = pathinfo($file_path);
					      		$extension = $file['extension'];
					      		$filename = 'TT'.str_pad($createdUserId, 5, '0', STR_PAD_LEFT)."-".$createdUser->first_name.".".$extension;
					      		$key = date('m-Y').'/'.'resumes/'.$createdUserId.'/'.$filename;
					      		$s3Url = ImageUtils::uploadImageOnS3($file_path,$key);
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
								elseif (SiteHelper::checkValidRecord($user[7])) {

									try {
								  		\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(trim($user[7]))->format('m/d/Y');
								  		UserWorkProfileDetail::create([
											'user_id' => $createdUserId,
											'user_work_profile_id' => $createdUserWorkProfileId,
											'type' => 'degree',
											'title' => trim($user[6]),
											'to_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((int)$user[7])->format('d/m/Y'),
										]);

									} catch (\Exception $exception) {
										if (strpos($user[7], '–') !== false) {
											$dates = explode("–",$user[7]);
										    $dates[0] = trim($dates[0]);
										    $dates[1] = trim($dates[1]);
											UserWorkProfileDetail::create([
												'user_id' => $createdUserId,
												'user_work_profile_id' => $createdUserWorkProfileId,
												'type' => 'degree',
												'title' => trim($user[6]),
												'from_date' => SiteHelper::checkValidRecord($dates[0]) ? SiteHelper::getRealDate("01/01/".$dates[0]) : null,
												'to_date' => SiteHelper::checkValidRecord($dates[1]) ? SiteHelper::getRealDate("01/01/".$dates[1]) : null,
											]);
										} 
										if (strpos($user[7], '‑') !== false) { 
										    $dates = explode("‑",$user[7]);
										    $dates[0] = trim($dates[0]);
										    $dates[1] = trim($dates[1]);
											UserWorkProfileDetail::create([
												'user_id' => $createdUserId,
												'user_work_profile_id' => $createdUserWorkProfileId,
												'type' => 'degree',
												'title' => trim($user[6]),
												'from_date' => SiteHelper::checkValidRecord($dates[0]) ? SiteHelper::getRealDate($dates[0]) : null,
												'to_date' => SiteHelper::checkValidRecord($dates[1]) ? SiteHelper::getRealDate($dates[1]) : null,
											]);
										}else{
											
											UserWorkProfileDetail::create([
												'user_id' => $createdUserId,
												'user_work_profile_id' => $createdUserWorkProfileId,
												'type' => 'degree',
												'title' => trim($user[6]),
												'to_date' => SiteHelper::checkValidRecord($user[7]) ? SiteHelper::getRealDate("01/01/".$user[7]) : null,
											]);
										}
									}
								}else{

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
								elseif (SiteHelper::isRealDate(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((int)$user[10])->format('d/m/Y')) && SiteHelper::isRealDate(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((int)$user[11])->format('d/m/Y'))) 
								{
									UserWorkProfileDetail::create([
										'user_id' => $createdUserId,
										'user_work_profile_id' => $createdUserWorkProfileId,
										'type' => 'experience',
										'awarded_by' => trim($user[9]),
										'description' => (isset($user[22]) && SiteHelper::checkValidRecord($user[22])) ? trim($user[22]) : '',
										'from_date' => SiteHelper::checkValidRecord($user[10]) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((int)$user[10])->format('d/m/Y') : null,
										'to_date' => isset($user[11]) ? ($user[11] == 'Present' || $user[11] == 'present') ? null : SiteHelper::checkValidRecord($user[11]) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((int)$user[11])->format('d/m/Y') : null : null,
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
								elseif (SiteHelper::isRealDate(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((int)$user[13])->format('d/m/Y')) && SiteHelper::isRealDate(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((int)$user[14])->format('d/m/Y'))) {
									UserWorkProfileDetail::create([
										'user_id' => $createdUserId,
										'user_work_profile_id' => $createdUserWorkProfileId,
										'type' => 'experience',
										'awarded_by' => trim($user[12]),
										'from_date' => SiteHelper::checkValidRecord($user[13]) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((int)$user[13])->format('d/m/Y') : null,
										'to_date' => ($user[14] == 'Present' || $user[14] == 'present') ? null : SiteHelper::checkValidRecord($user[14]) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((int)$user[14])->format('d/m/Y') : null,
										'is_present' => ($user[14] == 'Present' || $user[14] == 'present') ? '1' : '0',
									]);
								}
								else{
									$error_ar[] = 'Invalid date format for experience at column '.$column;
								}
							}
							// else{
							// 	$error_ar[] = 'Invalid data for Previous Company at column '.$column;
							// }
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
		      	
		      	$errors[] = $error_ar;
		      	
	      	} catch (Exception $e) {
	      		\DB::rollback();
	      		$error_ar[] = 'Error occured '.$e->getMessage().' at column '.$column;
	      		$error_ar[] = 'Record added failed at column '.$column;
	      		$errors[] = $error_ar;
	      		
	      	}

  		}
  		$this->response = $errors;
  		return $this->response;
    }

    public function getResponse(): array
    {
        return $this->response;
    }
}