<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\API\AddSkillRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterData;
use App\Models\SystemSettings;
use App\Models\UserWorkProfile;
use App\Models\Companies;
use App\Models\CompanyDetail;
use App\Models\UserPrefferedData;
use App\Models\HomepageLogo;
use App\Models\SystemConfig;
use App\Models\GiveawayWinner;
use App\Communicator\Communicator;
use App\Constants\ResponseMessages;
use App\Constants\StringConstants;
use App\Http\Requests\API\GetMasterDataRequest;
use App\Http\Requests\API\UploadMediaRequest;
use App\Http\Requests\API\GetLocationByNameOrPincodeRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\Auth\User;
use App\AppRinger\ImageUtils;
use App\Http\Requests\API\ContactUsRequest;
use App\Http\Requests\API\GetSkillByNameRequest;
use App\Mail\Frontend\Contact\SendContact;
use Exception;
use App\AppRinger\Logger;
use App\Models\GoogleLocation;
use App\AppRinger\GooglePlace;
use App\AppRinger\ChatGPT;
use App\AppRinger\Watermark;
use App\AppRinger\FileConverter;
use Illuminate\Support\Facades\Validator;
use App\Models\FooterContent;
use App\Models\Popup;

class CommonController extends Controller
{

	public function index()
	{

		return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::WELCOME_MSG, array()));
	}


	public function addSkill(AddSkillRequest $request)
	{
		try {
			$checkSkillExists = MasterData::checkSkillExists($request->name);

			if ($checkSkillExists) {
				$error_msg = '';
				return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::ADD_SKILL_SUCCESS_MSG, MasterData::getSkills()));
			}

			$add_skill = MasterData::addSkill($request->name);
			return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::ADD_SKILL_SUCCESS_MSG, MasterData::getSkills()));
		} catch (Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function getMasterData(GetMasterDataRequest $request)
	{
		try {

			$types = [];
			$system_config_keys = ['is_graph_enabled', 'validate_website_regex', 'is_company_dashboard_enabled'];
			if ($request->has('types') && count($request->types)  > 0) {
				$types = $request->types;
			}
			$data = MasterData::getMasterData($types);
			$data['config'] = SystemSettings::getSystemSettings();
			$data['system_config'] = SystemConfig::select('key', 'value')->whereIn('key', $system_config_keys)->get()->keyBy('key');
			$data['footer_content']= FooterContent::select('text', 'value','type')->get()->groupBy('type')->toArray();
			$data['popup'] = Popup::getPopupData('home');
			return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::MASTER_DATA_SUCCESS_MSG, $data));
		} catch (Exception $e) {

			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function getLocations(GetMasterDataRequest $request)
	{
		try {

			$data = MasterData::getLocations($request);

			return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::MASTER_DATA_SUCCESS_MSG, $data));
		} catch (Exception $e) {

			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function getLocationsByNameOrPincode(GetLocationByNameOrPincodeRequest $request)
	{
		try {

			// if ($request->has('type') && $request->type == 'JOB_LOCATIONS') {
			// 	$query = MasterData::select('master_data.id', 'master_data.type', 'master_data.name', 'master_data.description', 'master_data.value')
			// 		->leftJoin('company_job_details AS job_locations', function ($join) {
			// 			$join->on('job_locations.data_id', '=', 'master_data.id');
			// 			$join->where('job_locations.type', '=', 'locations');
			// 		})
			// 		->leftJoin('company_jobs', 'company_jobs.id', '=', 'job_locations.company_job_id')
			// 		->where('master_data.type', 'location')
			// 		->where('company_jobs.status', 'published');
			// } elseif ($request->has('type') && $request->type == 'USER_PROFILE_LOCATIONS') {
			// 	$user_locations = UserPrefferedData::where('type', 'locations')->pluck('data_id')->toArray();
			// 	$user_locations = array_unique($user_locations);
			// 	$query = MasterData::where('master_data.type', 'location')
			// 		->whereIn('master_data.id', $user_locations);
			// } elseif($request->has('type') && $request->type == 'GIG_LOCATIONS'){
	  //           $query = MasterData::select('master_data.id','master_data.type','master_data.name','master_data.description','master_data.value')
	  //               ->leftJoin('company_gig_details','company_gig_details.data_id','=','master_data.id')
	  //               ->leftJoin('company_gigs','company_gigs.id','=','company_gig_details.company_gig_id')
	  //               ->where('master_data.type','location')
	  //               ->where('company_gigs.status','published');
	  //       } else {
			// 	$query = MasterData::where('master_data.type', 'location');
			// }

			$query = MasterData::where('master_data.type', 'location');

			if (is_numeric($request->q)) {
				$query->where('master_data.value', 'like', '%' . $request->q . '%');
			} else {
				$query->where('master_data.name', 'like', '%' . $request->q . '%');
			}

			$data = $query->groupBy('master_data.id')->get();



			return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::MASTER_DATA_SUCCESS_MSG, $data));
		} catch (Exception $e) {

			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function uploadMedia(UploadMediaRequest $request)
	{
		try {
			$user_id 		= Auth::guard('api')->user()->id;
			$user_first_name 		= Auth::guard('api')->user()->first_name;
			$company_id 	= Auth::guard('api')->user()->company_id;
			$status 		= false;
			$data = [];
			$error_msg = StringConstants::SOMETHING_WRONG_MSG;
			$file = $request->file('file');
			$filename = 'TT' . str_pad($user_id, 5, '0', STR_PAD_LEFT) . "-" . $user_first_name . "." . $request->file->extension();


			switch ($request->type) {
				case 'resume':
					$validator  =  Validator::make(
						$request->all(),
						[
							'file' => 'mimes:jpeg,jpg,png,pdf,doc,docx',
						]
					);

					if ($validator->fails()) {
						return Communicator::returnResponse(ResponseMessages::VALIDATOR_FAIL($validator->errors()->first(), $validator->errors()));
					}
					if (env('IS_S3_UPLOAD')) {
						$key = date('m-Y') . '/' . 'resumes/' . $user_id . '/' . $filename;
						$file_type = $file->getClientOriginalExtension();
						$isDeleteFile = false;


						if($file_type == 'pdf'){
							$file = Watermark::addWatermarkToPdf($file->path());
							$pdf = UserWorkProfile::updateSearchableHash($user_id,$file);
							$isDeleteFile = true;
						}else if($file_type == 'doc' || $file_type == 'docx' || $file_type == 'ppt' || $file_type == 'pptx'){
							$pdfFile = FileConverter::convertDocToPDF($file->path());
							$file = Watermark::addWatermarkToPdf($pdfFile);
							$isDeleteFile = true;
							unlink($pdfFile);
						}


						$s3Url = ImageUtils::uploadImageOnS3($file, $key);

						$update_profile = UserWorkProfile::updateResume($key, $user_id);
						UserWorkProfile::updateUserSearchVisibility($user_id);
						if ($update_profile) {
							if ($isDeleteFile) {
								unlink($file);
							}
							$status = true;
							$message = StringConstants::UPLOAD_RESUME_SUCCESS_MSG;
							$data['file_url']  = $s3Url;
						}
					} else {
						$filename = ImageUtils::uploadImage($file, 'resumes/' . $user_id, $filename);
						$update_resume = UserWorkProfile::updateResume(url($filename), $user_id);
						UserWorkProfile::updateUserSearchVisibility($user_id);
						if ($update_resume) {
							$status = true;
							$message = StringConstants::UPLOAD_RESUME_SUCCESS_MSG;
							$data['file_url']  = url($filename);
						}
					}

					break;

				case 'video':

					if (env('IS_S3_UPLOAD')) {
						$key = date('m-Y') . '/' . 'videos/' . $user_id . '/' . $filename;
						$s3Url = ImageUtils::uploadImageOnS3($file, $key);
						$update_profile = UserWorkProfile::updateVideo($key, $user_id);
						if ($update_profile) {
							$status = true;
							$message = StringConstants::UPLOAD_VIDEO_SUCCESS_MSG;
							$data['file_url']  = $s3Url;
						}
					} else {
						$filename = ImageUtils::uploadImage($file, 'videos/' . $user_id, $filename);
						$update_profile = UserWorkProfile::updateVideo($key, $user_id);
						if ($update_profile) {
							$status = true;
							$message = StringConstants::UPLOAD_VIDEO_SUCCESS_MSG;
							$data['file_url']  = url($filename);
						}
					}

					break;

				case 'profile':

					if (env('IS_S3_UPLOAD')) {
						$key = date('m-Y') . '/' . 'avatars/' . $user_id . '/' . $filename;
						$s3Url = ImageUtils::uploadImageOnS3($file, $key);
						$update_profile = User::updateAvatar($key, $user_id);
						if ($update_profile) {
							$status = true;
							$message = StringConstants::UPLOAD_USER_IMAGE_SUCCESS_MSG;
							$data['file_url']  = $s3Url;
						}
					} else {
						$filename = ImageUtils::uploadImage($file, 'avatars/' . $user_id, $filename);
						$update_profile = User::updateAvatar(url($filename), $user_id);
						if ($update_profile) {
							$status = true;
							$message = StringConstants::UPLOAD_USER_IMAGE_SUCCESS_MSG;
							$data['file_url']  = url($filename);
						}
					}

					break;

				case 'company':
					if ($company_id != null && $company_id != '') {

						if (env('IS_S3_UPLOAD')) {
							$key = date('m-Y') . '/' . 'company_logo/' . $company_id . '/' . $filename;
							$s3Url = ImageUtils::uploadImageOnS3($file, $key);
							$update_logo = Companies::updateLogo($key, $company_id);
							if ($update_logo) {
								$status = true;
								$message = StringConstants::UPLOAD_COMPANY_LOGO_SUCCESS_MSG;
								$data['file_url']  = $s3Url;
							}
						} else {
							$filename = ImageUtils::uploadImage($file, 'company_logo/' . $company_id, $filename);

							$update_logo = Companies::updateLogo(url($filename), $company_id);
							if ($update_logo) {
								$status = true;
								$message = StringConstants::UPLOAD_COMPANY_LOGO_SUCCESS_MSG;
								$data['file_url']  = url($filename);
							}
						}
					}
					break;

				case 'company_cover_pic':
					if ($company_id != null && $company_id != '') {

						if (env('IS_S3_UPLOAD')) {
							$key = date('m-Y') . '/' . 'company_cover_pic/' . $company_id . '/' . $filename;
							$s3Url = ImageUtils::uploadImageOnS3($file, $key);
							$cover_pic = Companies::updateCompany(['cover_pic' => $key], $company_id);
							if ($cover_pic) {
								$status = true;
								$message = StringConstants::UPLOAD_COMPANY_COVER_SUCCESS_MSG;
								$data['file_url']  = $s3Url;
							}
						} else {
							$filename = ImageUtils::uploadImage($file, 'company_cover_pic/' . $company_id, $filename);

							$cover_pic = Companies::updateCompany(['cover_pic' => url($filename)], $company_id);
							if ($cover_pic) {
								$status = true;
								$message = StringConstants::UPLOAD_COMPANY_COVER_SUCCESS_MSG;
								$data['file_url']  = url($filename);
							}
						}
					}
					break;

				case 'company_media':
					if ($company_id != null && $company_id != '') {

						if (env('IS_S3_UPLOAD')) {
							$key = date('m-Y') . '/' . 'company_media/' . $company_id . '/' . time() . $filename;
							$s3Url = ImageUtils::uploadImageOnS3($file, $key);
							$company_media = CompanyDetail::addCompanyDetail(['company_id' => $company_id, 'type' => 'medias', 'value' => $key]);
							if ($company_media) {
								$status = true;
								$message = StringConstants::UPLOAD_COMPANY_MEDIA_SUCCESS_MSG;
								$data['file_url']  = $s3Url;
								$data['data']  = CompanyDetail::getCompanyMediasObject(CompanyDetail::getCompanyMedia($company_id));
							}
						} else {
							$filename = ImageUtils::uploadImage($file, 'company_media/' . $company_id, $filename);
							$company_media = CompanyDetail::addCompanyDetail(['type' => 'medias', 'value' => url($filename)]);
							if ($company_media) {
								$status = true;
								$message = StringConstants::UPLOAD_COMPANY_MEDIA_SUCCESS_MSG;
								$data['file_url']  = url($filename);
								$data['data']  = CompanyDetail::getCompanyMediasObject(CompanyDetail::getCompanyMedia($company_id));
							}
						}
					}
					break;

				default:
					$error_msg = StringConstants::UPLOAD_MEDIA_TYPE_ERROR_MSG;
					break;
			}

			if ($status) {

				return Communicator::returnResponse(ResponseMessages::SUCCESS($message, $data));
			} else {
				return Communicator::returnResponse(ResponseMessages::ERROR($error_msg, StringConstants::SOMETHING_WRONG_MSG));
			}
		} catch (Exception $e) {

			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function contact(ContactUsRequest $request)
	{
		\Mail::send(new SendContact($request));

		return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::CONTACT_US_SUCCESS_MSG, array()));
	}

	public function getSkillsByName(GetSkillByNameRequest $request)
	{
		
		try {

			Logger::logDebug("[Get Skill API]");
			Logger::logDebug("Request data: " . json_encode($request->all()));
			\DB::enableQueryLog();

			$data = MasterData::where('type', 'skills')
				->where('name', 'like', $request->q . '%')
				// ->orderByRaw('CASE WHEN name LIKE "'.$request->q.'" THEN 1 WHEN name LIKE "'.$request->q.'%" THEN 2 ELSE 3 END')
				->orderByRaw('LENGTH(name)')
				// ->groupBy('id')
				->get();

			Logger::logDebug("Query build: " . json_encode(\DB::getQueryLog()));

			return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::MASTER_DATA_SUCCESS_MSG, $data));
		} catch (Exception $e) {

			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function getSkills(GetMasterDataRequest $request)
	{
		try {

			$data = MasterData::getSkills();

			return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::MASTER_DATA_SUCCESS_MSG, $data));
		} catch (Exception $e) {

			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function getHomepageLogos()
	{
		try {
			$logos = HomepageLogo::getOrderableLogos();

			return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::HOMEPAGE_LOGO_SUCCESS_MSG, $logos));
		} catch (Exception $e) {

			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function getGooglePlaces(Request $request)
	{
		if ($request->has('q') && $request->q != null) {
			$q = $request->q;
			$locations = GoogleLocation::getLocations($q);
			if (count($locations) > 0) {
				return Communicator::returnResponse(ResponseMessages::SUCCESS('', $locations));
			} else {
				$response = GooglePlace::getPlaces($q);
				foreach ($response['predictions'] as $res) {
					GoogleLocation::create([
						'name' => $res['description'],
						'place_id' => $res['place_id']
					]);
				}
				$locations = GoogleLocation::getLocations($q);
				return Communicator::returnResponse(ResponseMessages::SUCCESS('', $locations));
			}
		}

		$locations = GoogleLocation::getLocations();
		return Communicator::returnResponse(ResponseMessages::SUCCESS('', $locations));
	}

	public function getDescriptioByChatGPT(Request $request)
	{
		try {
			$description = '';
			$title = $request->title;
			$minExp = $request->minExp;
			$maxExp = $request->maxExp;
			$locations = $request->location ? $request->location : [];
			$benefits = $request->benefit ? $request->benefit : [];
			$title_type = $request->type ? $request->type : 'job';

			$locations = implode(", ",$locations);
			$benefits = implode(", ",$benefits);


			// $command = 'cd .. & cd chatgpt & node index.js "Write '.$title_type.' description for '.$title.'?"';

			// // $description = shell_exec( $command );

			// $process = new Process($command);
			// $process->run();

			// // executes after the command finishes
			// if (!$process->isSuccessful()) {
			//     throw new ProcessFailedException($process);
			// }else{
			// 	$description = $process->getOutput();
			// }
			// $description = explode("\n",$description);

			// for ($i=0; $i <6 ; $i++) { 
			// 	unset($description[$i]);
			// }

			$command = 'Write '.$title_type.' description for '.$title;
			if(trim($minExp) != "Years"){
				$command .= ' with minimum experience '.$minExp;
			}

			if(trim($maxExp) != "Years"){
				$command .= ' and maximum experience '.$maxExp;
			}
			if(trim($locations) != ""){
				$command .= ' and on locations '.$locations;
			}

			if(trim($benefits) != ""){
				$command .= ' and with benefits '.$benefits;
			}

			$command .= '?';
			$description = ChatGPT::getAIDescription($command);
			$description = explode("\n",$description);
			$description = implode("<br />", $description);
			$data['description'] = $description;
			return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::SUCCESSS,$data));
			
		} catch (Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function getGiveawayWinner(Request $request)
	{
		try {
			$winners = GiveawayWinner::all();
			return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::SUCCESSS,$winners));
		} catch (Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}
}
