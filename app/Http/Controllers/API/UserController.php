<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Communicator\Communicator;
use App\Constants\ResponseMessages;
use App\Constants\StringConstants;
use Illuminate\Support\Facades\Hash;
use App\AppRinger\Logger;
use App\Models\Companies;
use App\Mail\sendOtp;
use App\Mail\SendCompanyExistsEmailToAdmin;
use App\Mail\SendCompanyExistsEmailToUser;
use App\Models\BlockedCompanies;
use App\Models\WorkProfile;
use App\Models\WorkProfileDetails;
use App\Models\MasterData;
use App\Models\UserWorkProfile;
use App\Models\UserWorkProfileDetail;
use App\Models\UserPrefferedData;
use App\Models\BlockedDomain;
use App\Models\Chat;
use App\Models\ReferralUser;
use App\Models\Job;
use App\Models\CompanyGig;
use App\Models\UserReferralCode;
use App\Models\Referral;
use App\Models\CandidateJobs;
use Symfony\Component\Process\Process;
use App\Http\Requests\API\LoginRequest;
use App\Http\Requests\API\CandidateRegisterRequest;
use App\Http\Requests\API\CompanyRegisterRequest;
use App\Http\Requests\API\ChangePasswordRequest;
use App\Http\Requests\API\SendOtpForgotPasswordRequest;
use App\Http\Requests\API\ForgotPasswordRequest;
use App\Http\Requests\API\BlockUnblockCompanyRequest;
use App\Http\Requests\API\AddAndBlockCompanyRequest;
use App\Http\Requests\API\UploadResumeRequest;
use App\Http\Requests\API\ResetPasswordRequest;
use App\Http\Requests\API\SearchCandidateRequest;
use App\Http\Requests\API\ValidateOtpRequest;
use App\Helpers\SiteHelper;
use App\Mail\SendCompanyRegistrationRequestEmail;
use Illuminate\Support\Facades\Mail;
use App\Config\AppConfig;
use App\Notifications\Frontend\Auth\UserNeedsConfirmation;
use App\AppRinger\Watermark;
use App\AppRinger\ImageUtils;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Frontend\OtpForgotPasswordNotification;
use App\Notifications\Frontend\CompanyAlreadyExistNotification;
use App\Notifications\Frontend\IssueWithCompanyRegistrationNotification;

class UserController extends Controller
{

	public function companyRegister(CompanyRegisterRequest $request)
	{


		try {

			$input = $request->all();
			$site = $input['website'];
			$email = $input['email'];
			$data = null;
			if (strpos($site, 'http') === false && strpos($site, 'https') === false) {
				$input['website'] = 'http://' . $request->website;
			}

			$url = str_replace('www.', '', parse_url($input['website'])['host']);
			$existing = Companies::where('website', 'like', '%' . $url . '%')->select('id', 'name')->first();
			$isCompanyExists = 0;
			$website_domain = SiteHelper::getDomain($url);
			$email_domain   = SiteHelper::getDomainFromEmail($email);
			if (!$website_domain) {
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::INVALID_WEBSITE_ERROR_MSG, StringConstants::INVALID_WEBSITE_ERROR_MSG));
			} elseif (!$email_domain) {
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::INVALID_EMAIL_ERROR_MSG, StringConstants::INVALID_EMAIL_ERROR_MSG));
			} elseif (BlockedDomain::checkDoamin($website_domain)) {
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::DOMAIN_BLOCKED_MSG, StringConstants::DOMAIN_BLOCKED_MSG));
			} elseif (strtolower($website_domain) != strtolower($email_domain)) {
				/*Send Email to Admin and User*/
				$location_name = MasterData::getMasterDataName($input['location']);
				$input['location_name'] = $location_name ? $location_name->name . ', ' . $location_name->description : '';
				$company_size_name = MasterData::getMasterDataName($input['company_size']);
				$input['company_size_name'] = $company_size_name ? $company_size_name->name : '';
				$industry_domain_name = MasterData::getMasterDataName($input['industry_domain']);
				$input['industry_domain_name'] = $industry_domain_name ? $industry_domain_name->name : '';
				$input['contact'] = $request->contact ? $request->contact : '';
				// Mail::to(AppConfig::getAdminEmail())->cc($input['email'])->send(new SendCompanyRegistrationRequestEmail($input));
				$subject = notificationTemplates('company_registration_faild')->subject ? notificationTemplates('company_registration_faild')->subject : 'Issue with Company Registration';
				$template = 'frontend.mail.company_registration_request';
				$user = User::where('email',AppConfig::getAdminEmail())->first();
				$ccMail[] = $input['email'];
				$inviteUser = new User();
				$inviteUser->email = AppConfig::getAdminEmail();
                $inviteUser->notify(new IssueWithCompanyRegistrationNotification($subject,$input, $template,$via=[],$ccMail));
				return Communicator::returnResponse(ResponseMessages::NO_REGISTRATION_SUCCESS(StringConstants::DIFFERENT_EMAIL_WEBSITE_DOMAIN_ERROR_MSG, $data));
			} elseif ($existing) {

				$checkUserExists = User::where('company_id', $existing->id)->first();
				if ($checkUserExists) {
					Logger::logWarning("Company Register API: " . $existing->name . " | already exists");

					
					$input['company_id'] = $existing->id;

					$created_user = User::createUser($input);
					$created_user->assignRole(config('access.users.company_user_role'));
					$created_user->delete();

					$data['email'] 		= $created_user->email;


					$data['company_admin_name'] = $checkUserExists->full_name;
					$data['user_email'] = $input['email'];
					$data['user_name'] = $input['first_name'].' '.$input['last_name'];

					// Mail::to($checkUserExists->email)->cc([$email, AppConfig::getAdminEmail()])->send(new SendCompanyExistsEmailToAdmin($data));
					$data['contact'] = $checkUserExists->contact;
	                $template = 'frontend.mail.company_exist_admin';
	                $subject = notificationTemplates('company_exist_admin')->subject ? notificationTemplates('company_exist_admin')->subject : 'Re-Registration of Company';
	                $ccMail = [$email, AppConfig::getAdminEmail()];
                	$checkUserExists->notify(new CompanyAlreadyExistNotification($subject,$data, $template,$via=[],$ccMail));

					return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::REGISTER_SUCCESS_MSG, $data));
					// Mail::to($email)->send(new SendCompanyExistsEmailToUser([]));

					// return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::COMPANY_EXISTS_MSG, StringConstants::COMPANY_EXISTS_MSG));
				} else {
					$isCompanyExists = 1;
				}
			}
			if ($isCompanyExists == 0)
				$comany_id = Companies::createCompany($input);
			else
				$comany_id = $existing->id;

			if ($comany_id == false) {

				Logger::logError("Company Register API: Unable to create company");
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::SOMETHING_WRONG_MSG, StringConstants::SOMETHING_WRONG_MSG));
			}

			$input['company_id'] = $comany_id;

			$user = User::createUser($input);
			$user = User::where('email', $request->email)->first();
			$user->assignRole(config('access.users.company_admin_role'));
			$user->removeRole(config('access.users.candidate_role'));

			if ($request->has('referralCode') && $request->referralCode != 'null' && $request->referralCode != '') {
				$data = [];
				$code_details = UserReferralCode::where('referral_code',$request->referralCode)->first();
				if ($code_details) {
					$referral_id = $code_details->referral_id;
					$referral_details = Referral::find($referral_id);
					if ($referral_details->target_audience == "companies") {
						$data['referral_id'] = $referral_id;
						$data['referred_by'] = $code_details->user_id;
						$data['referred_to'] = $user->id;
						$data['email'] 		= $user->email;

						$ref = ReferralUser::addData($data);
					}
				}
			}

			return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::REGISTER_SUCCESS_MSG, $data));
		} catch (Exception $e) {

			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function candidateRegister(CandidateRegisterRequest $request)
	{
		try {
			\DB::beginTransaction();
			$is_verified = false;
			if ($request->has('id') && $request->id != null && $request->id != '') {
				$request->merge([
				    'password' => Hash::make($request->password),
				]);
				$userOb = new User;
				$userData = $request->only($userOb->getFillable());
				$userData['otp'] = '1234';
				$user = User::updateUser($userData, $request->id);
				$is_verified = true;

				$data = User::getUserByEmail($user->email);
			    $data['profile'] = User::with('userWorkProfile')->with('userWorkProfileDetail')->find($data->id);

			    $data['is_profile_completed'] = true;
	            $data['is_mobile_verified'] = true;

	            if ($data['profile']->isCandidate() || $data['profile']->isEvaluator()) {
	            	if ($data['profile']->contact == null || $data['profile']->contact == '') {
		                $data['is_profile_completed'] = false;
		            }elseif($data['profile']->is_mobile_verified != '1'){
		            	$data['profile']->otp = '1234';
		            	$data['profile']->save();
		                $data['is_mobile_verified'] = false;
		            }
	            }

				if (($user->company_id == null || Companies::isCompanyActive($user->company_id)) && $data['is_profile_completed'] && $data['is_mobile_verified']) {
		            $user = User::getUserByEmail($user->email);
		            $user->tokens->each(function ($token, $key) {
	                    $token->delete();
	                });
	                $token = $user->createToken(env('APP_NAME'))->accessToken;
	                
	                if ($user->isAdmin()) {
	                    return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::ACCESS_DENIED_ERROR_MSG, StringConstants::EMAIL_VERIFY_ERROR_MSG));
	                }
	                $user['token'] = $token;
		            $user['profile'] = User::with('userWorkProfile')->with('userWorkProfileDetail')->find($user->id);
		            $user_id = $user->id;
		            $user['profile']->is_verified = true;
		            $user['profile']->preferred_location = UserPrefferedData::getData($user_id, 'locations');
		            if (is_array($user['profile']->preferred_location) && count($user['profile']->preferred_location) > 0) {
		                $preferred_location_names = MasterData::getNameFromArray($user['profile']->preferred_location);
		                $user['profile']->preferred_location_names = $preferred_location_names;
		                $user['profile']->preferred_location_data = implode(', ', MasterData::getNameFromArray($user['profile']->preferred_location)->pluck('name')->toArray());
		            }
		            $user['profile']->preferred_skills = UserPrefferedData::getData($user_id, 'skills');
		            if (is_array($user['profile']->preferred_skills) && count($user['profile']->preferred_skills) > 0) {
		                $preferred_skill_names = MasterData::getNameFromArray($user['profile']->preferred_skills);
		                $user['profile']->preferred_skill_names = $preferred_skill_names;
		            }
		            $user['profile']->progress = User::getUserProfileProgress($user['profile']);
		            if ($user->company_id != NULL && $user->company_id != '') {
		                $user['profile']->company = Companies::find($user->company_id);
		                $user['profile']->company->progress = Companies::getCompanyProgress($user['profile']->company);
		            }
		            $user['user_unread_messages'] = Chat::getUserUnreadMessagesCount($user_id);
		        }
			}else{

				$user = User::createUser($request->all(),true);
				if ($request->has('selectedProfile') && $request->selectedProfile == "evaluator") {
					$user->assignRole(config('access.users.evaluator_role'));
					$user->removeRole(config('access.users.candidate_role'));
				}else{

					$user->assignRole(config('access.users.candidate_role'));
				}
			}
			$data = null;

			$user_id = $user->id;
			$work_profile_id = UserWorkProfile::add($request, $user_id);
			if (isset($request->preferred_location) && count(json_decode($request->preferred_location)) > 0)
				UserPrefferedData::addData($user_id, 'locations', json_decode($request->preferred_location));

			if (isset($request->skills) && count(json_decode($request->skills)) > 0) {
				foreach (json_decode($request->skills) as $skill) {
					UserWorkProfileDetail::add(array_merge((array)$skill, [
						'user_id' => $user_id,
						'type' => 'skill',
						'user_work_profile_id' => $work_profile_id
					]));
				}
			}

			if ($request->has('avatar') && $request->avatar != null) {
				$file = $request->file('avatar');
				$filename = 'TT' . str_pad($user_id, 5, '0', STR_PAD_LEFT) . "-" . $user->first_name . "." . $request->avatar->extension();
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

			}

			if ($request->has('resume_file') && $request->resume_file != null) {
				$file = $request->file('resume_file');
				$filename = 'TT' . str_pad($user_id, 5, '0', STR_PAD_LEFT) . "-" . $user->first_name . "." . $request->resume_file->extension();
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
			}
			

			if ($request->has('referralCode') && $request->referralCode != 'null' && $request->referralCode != '') {
				$data = [];
				$code_details = UserReferralCode::where('referral_code',$request->referralCode)->first();
				if ($code_details) {
					$referral_id = $code_details->referral_id;
					$referral_details = Referral::find($referral_id);
					if ($referral_details->target_audience == "candidates") {
						$data['referral_id'] = $referral_id;
						$data['referred_by'] = $code_details->user_id;
						$data['referred_to'] = $user->id;
						$data['email'] 		= $user->email;

						$ref = ReferralUser::addData($data);
					}
				}
			}

			\DB::commit();

			$user->is_verified = $is_verified;

			return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::REGISTER_SUCCESS_MSG, $user));
		} catch (Exception $e) {

			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function validateOtp(ValidateOtpRequest $request)
	{
		try {
			$user = User::where('id',$request->user_id)->first();
			if ($user) {
				$email_otp = rand(1000, 9999);
				$user->is_mobile_verified = "1";
				$user->email_otp =  $email_otp;
				$user->save();
				if (!$user->hasVerifiedEmail()) {
					try {

					$is_evaluator = $user->isEvaluator();
	                $user->notify(new UserNeedsConfirmation($user->confirmation_code, $user->first_name . ' ' . $user->last_name, null, null,$is_evaluator,$email_otp));
	                
		            } catch (\Swift_TransportException $ex) {
		            	return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($ex));
		            } catch (\Exception $ex) {
		            	return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($ex));
	            	}
	            	return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::OTP_VERIFY_SUCCESS_MSG, null));
				}else{
					if ($user->company_id == null || Companies::isCompanyActive($user->company_id)) {
			            $data = User::getUserByEmail($user->email);
			            $user->tokens->each(function ($token, $key) {
		                    $token->delete();
		                });
		                $token = $user->createToken(env('APP_NAME'))->accessToken;
		                
		                if ($data->isAdmin()) {
		                    return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::ACCESS_DENIED_ERROR_MSG, StringConstants::EMAIL_VERIFY_ERROR_MSG));
		                }
		                $data['token'] = $token;
			            $data['profile'] = User::with('userWorkProfile')->with('userWorkProfileDetail')->find($data->id);
			            $user_id = $data->id;
			            $data['profile']->is_verified = true;
			            $data['profile']->preferred_location = UserPrefferedData::getData($user_id, 'locations');
			            if (is_array($data['profile']->preferred_location) && count($data['profile']->preferred_location) > 0) {
			                $preferred_location_names = MasterData::getNameFromArray($data['profile']->preferred_location);
			                $data['profile']->preferred_location_names = $preferred_location_names;
			                $data['profile']->preferred_location_data = implode(', ', MasterData::getNameFromArray($data['profile']->preferred_location)->pluck('name')->toArray());
			            }
			            $data['profile']->preferred_skills = UserPrefferedData::getData($user_id, 'skills');
			            if (is_array($data['profile']->preferred_skills) && count($data['profile']->preferred_skills) > 0) {
			                $preferred_skill_names = MasterData::getNameFromArray($data['profile']->preferred_skills);
			                $data['profile']->preferred_skill_names = $preferred_skill_names;
			            }
			            $data['profile']->progress = User::getUserProfileProgress($data['profile']);
			            if ($user->company_id != NULL && $user->company_id != '') {
			                $data['profile']->company = Companies::find($user->company_id);
			                $data['profile']->company->progress = Companies::getCompanyProgress($data['profile']->company);
			            }
			            $data['user_unread_messages'] = Chat::getUserUnreadMessagesCount($user_id);
			            return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::OTP_VERIFY_SUCCESS_MSG, $data));
			        }
				}
				
			}else{
				
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::OTP_VERIFY_ERROR_MSG, StringConstants::OTP_VERIFY_ERROR_MSG));
			}
		} catch (Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
			
		}
	}

	public function validateEmailOtp(ValidateOtpRequest $request)
	{
		try {
			$user = User::where('id',$request->user_id)->where('email_otp',$request->otp)->first();
			if ($user) {
				$msg = '';
				if ($user->hasVerifiedEmail()) {
			        $msg = StringConstants::EMAIL_ALREADY_VERIFIED_MSG;
			    }

			    if (!$user->hasVerifiedEmail()) {
			        $user->markEmailAsVerified();
			        $msg = StringConstants::EMAIL_VERIFY_SUCCESS_MSG;
			    }
			    $data = User::getUserByEmail($user->email);
			    $data['profile'] = User::with('userWorkProfile')->with('userWorkProfileDetail')->find($data->id);

			    $data['is_profile_completed'] = true;
	            $data['is_mobile_verified'] = true;

	            if ($data['profile']->isCandidate() || $data['profile']->isEvaluator()) {
	            	if ($data['profile']->contact == null || $data['profile']->contact == '') {
		                $data['is_profile_completed'] = false;
		            }elseif($data['profile']->is_mobile_verified != '1'){
		            	$data['profile']->otp = '1234';
		            	$data['profile']->save();
		                $data['is_mobile_verified'] = false;
		            }
	            }

			    if (($user->company_id == null || Companies::isCompanyActive($user->company_id)) && $data['is_profile_completed'] && $data['is_mobile_verified']) {
		            
		            $user->tokens->each(function ($token, $key) {
	                    $token->delete();
	                });
	                $token = $user->createToken(env('APP_NAME'))->accessToken;
	                
	                if ($data->isAdmin()) {
	                    return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::ACCESS_DENIED_ERROR_MSG, StringConstants::EMAIL_VERIFY_ERROR_MSG));
	                }
	                $data['token'] = $token;
		            
		            $user_id = $data->id;
		            $data['profile']->is_verified = true;
		            $data['profile']->preferred_location = UserPrefferedData::getData($user_id, 'locations');
		            if (is_array($data['profile']->preferred_location) && count($data['profile']->preferred_location) > 0) {
		                $preferred_location_names = MasterData::getNameFromArray($data['profile']->preferred_location);
		                $data['profile']->preferred_location_names = $preferred_location_names;
		                $data['profile']->preferred_location_data = implode(', ', MasterData::getNameFromArray($data['profile']->preferred_location)->pluck('name')->toArray());
		            }
		            $data['profile']->preferred_skills = UserPrefferedData::getData($user_id, 'skills');
		            if (is_array($data['profile']->preferred_skills) && count($data['profile']->preferred_skills) > 0) {
		                $preferred_skill_names = MasterData::getNameFromArray($data['profile']->preferred_skills);
		                $data['profile']->preferred_skill_names = $preferred_skill_names;
		            }
		            $data['profile']->progress = User::getUserProfileProgress($data['profile']);
		            if ($user->company_id != NULL && $user->company_id != '') {
		                $data['profile']->company = Companies::find($user->company_id);
		                $data['profile']->company->progress = Companies::getCompanyProgress($data['profile']->company);
		            }
		            $data['user_unread_messages'] = Chat::getUserUnreadMessagesCount($user_id);
		        }

		        
			    return Communicator::returnResponse(ResponseMessages::SUCCESS($msg, $data));
				
			}else{
				
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::OTP_VERIFY_ERROR_MSG, StringConstants::OTP_VERIFY_ERROR_MSG));
			}
		} catch (Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
			
		}
	}


	public function login(LoginRequest $request)
	{
		try {

			$credentials = [
				'email' => $request->email,
				'password' => $request->password,
			];

			if (auth()->attempt($credentials)) {
				if (auth()->user()->hasVerifiedEmail()) {
					if (auth()->user()->company_id == null || Companies::isCompanyActive(auth()->user()->company_id)) {

						$data = User::getUserByEmail(auth()->user()->email);
						$data['profile'] = User::with('userWorkProfile')->with('userWorkProfileDetail')->find($data->id);
						$user_id = $data->id;
						$data['is_profile_completed'] = true;
			            $data['is_mobile_verified'] = true;

			            if ($data['profile']->isCandidate() || $data['profile']->isEvaluator()) {
			            	if ($data['profile']->contact == null || $data['profile']->contact == '') {
				                $data['is_profile_completed'] = false;
				            }elseif($data['profile']->is_mobile_verified != '1'){
				            	$data['profile']->otp = '1234';
				            	$data['profile']->save();
				                $data['is_mobile_verified'] = false;
				            }
			            }

			            if (($data['is_profile_completed'] && $data['is_mobile_verified'])) {

							auth()->user()->tokens->each(function ($token, $key) {
								$token->delete();
							});
							$token = auth()->user()->createToken(env('APP_NAME'))->accessToken;
							
							if ($data->isAdmin()) {
								return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::ACCESS_DENIED_ERROR_MSG, StringConstants::EMAIL_VERIFY_ERROR_MSG));
							}
							$data['token'] = $token;
			            }

						
						$data['profile']->preferred_location = UserPrefferedData::getData($user_id, 'locations');
						if (is_array($data['profile']->preferred_location) && count($data['profile']->preferred_location) > 0) {
							$preferred_location_names = MasterData::getNameFromArray($data['profile']->preferred_location);
							$data['profile']->preferred_location_names = $preferred_location_names;
							$data['profile']->preferred_location_data = implode(', ', MasterData::getNameFromArray($data['profile']->preferred_location)->pluck('name')->toArray());
						}
						$data['profile']->preferred_skills = UserPrefferedData::getData($user_id, 'skills');
						if (is_array($data['profile']->preferred_skills) && count($data['profile']->preferred_skills) > 0) {
							$preferred_skill_names = MasterData::getNameFromArray($data['profile']->preferred_skills);
							$data['profile']->preferred_skill_names = $preferred_skill_names;
						}
						$data['profile']->progress = User::getUserProfileProgress($data['profile']);
						if (auth()->user()->company_id != NULL && auth()->user()->company_id != '') {
							$data['profile']->company = Companies::find(auth()->user()->company_id);
							$data['profile']->company->progress = Companies::getCompanyProgress($data['profile']->company);
						}
						$data['user_unread_messages'] = Chat::getUserUnreadMessagesCount($user_id);

        				// Update the logging in users time & IP
						$ip_address = request()->getClientIp();
				        User::where('id',$user_id)->update(['last_login_at' => now()->toDateTimeString(),'last_login_ip' => $ip_address,]);
				        $show_apply_popup = false;
				        $referral_data = ReferralUser::with('referral','referredByUser','referral.jobData','referral.jobData.company_details')->where('referred_to',$user_id)->whereHas('referral',function($query) {
			                    $query->where('company_job_id','!=' , null);
			                })->first();
				        if ($referral_data) {
				        	$job_id = $referral_data->referral->company_job_id;
				        	$is_applied = CandidateJobs::isApplied($job_id,$user_id);
				        	if (!$is_applied) {
				        		$show_apply_popup = true;
				        	}
				        }
				        $data['profile']->show_apply_popup = $show_apply_popup;
				        $data['profile']->referral_data = $referral_data;
						return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::LOGIN_SUCCESS_MSG, $data));
					} else {
						return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::COMPANY_DEACTIVATE_ERROR_MSG, StringConstants::COMPANY_DEACTIVATE_ERROR_MSG));
					}
				} else {
					$data['profile'] = auth()->user();
					$data['is_profile_completed'] = true;
		            $data['is_mobile_verified'] = true;
					if ($data['profile']->isCandidate() || $data['profile']->isEvaluator()) {
		            	if ($data['profile']->contact == null || $data['profile']->contact == '') {
			                $data['is_profile_completed'] = false;
			            }elseif($data['profile']->is_mobile_verified != '1'){
			            	$data['profile']->otp = '1234';
			            	$data['profile']->save();
			                $data['is_mobile_verified'] = false;
			            }elseif (!auth()->user()->hasVerifiedEmail()) {
						
							try {
								$user = auth()->user();
								$is_company_user = isset($user->company_id) ? true : false;
								$user->notify(new UserNeedsConfirmation($user->confirmation_code, $user->first_name . ' ' . $user->last_name, null, $is_company_user,$data['profile']->isEvaluator(),auth()->user()->email_otp));
							} catch (\Swift_TransportException $ex) {
							} catch (\Exception $ex) {
							}
							$data['show_email_screen'] = true;
							$data['user'] = $user;
							return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::EMAIL_VERIFY_ERROR_MSG, StringConstants::EMAIL_VERIFY_ERROR_MSG,$data));
						}
						if (!$data['is_profile_completed'] || !$data['is_mobile_verified']) {
							return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::MOBILE_VERIFY_ERROR_MSG, StringConstants::MOBILE_VERIFY_ERROR_MSG,$data));
						}
		            }elseif (!auth()->user()->hasVerifiedEmail()) {
						
						try {
							$user = auth()->user();
							$is_company_user = isset($user->company_id) ? true : false;
							$user->notify(new UserNeedsConfirmation($user->confirmation_code, $user->first_name . ' ' . $user->last_name, null, $is_company_user));
						} catch (\Swift_TransportException $ex) {
						} catch (\Exception $ex) {
						}
						// $data['show_email_screen'] = true;
						$data['user'] = $user;
						return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::EMAIL_VERIFY_ERROR_MSG, StringConstants::EMAIL_VERIFY_ERROR_MSG,$data));
					}				}
			} else {
				$user = User::withTrashed()->where('email',$credentials['email'])->where('deleted_at','!=',null)->first();
				if ($user && $user->id == $user->deleted_by) {
					return Communicator::returnResponse(ResponseMessages::ACCOUNT_DELETED_LOGIN_SUCCESS(StringConstants::ACCOUNT_DELETED_LOGIN_MSG, StringConstants::ACCOUNT_DELETED_LOGIN_MSG));
				}elseif ($user && ($user->isCompanyAdmin() || $user->isCompanyUser())) {
					$company_id = $user->company_id;
					$company = Companies::withTrashed()->find($company_id);
					if ($company && $company->is_deleted == '1') {
						$msg = '';
						if ($user->isCompanyAdmin()) {
							$msg = StringConstants::COMPANY_ADMIN_DELETE_LOGIN_MSG;
						}else{
							$msg = StringConstants::COMPANY_DELETE_LOGIN_MSG;
						}
						return Communicator::returnResponse(ResponseMessages::CONTACT_ADMIN_SUCCESS($msg,$msg));
					}
				}
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::LOGIN_ERROR_MSG, StringConstants::LOGIN_ERROR_MSG));
			}
		} catch (\Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function logout()
	{
		try {

			$user = Auth::guard('api')->user()->token();
			$user->revoke();
			return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::LOGOUT_SUCCESS_MSG, []));
		} catch (Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function changePassword(ChangePasswordRequest $request)
	{

		try {

			$input = $request->all();
			$userid = Auth::guard('api')->user()->id;

			if ((Hash::check(request('old_password'), Auth::guard('api')->user()->password)) == false) {
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::WRONG_PASSWORD_MSG, StringConstants::WRONG_PASSWORD_MSG));
			} else if ((Hash::check(request('new_password'), Auth::guard('api')->user()->password)) == true) {
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::SAME_PASSWORD_MSG, StringConstants::SAME_PASSWORD_MSG));
			} else {
				$password_update = User::updatePassword($input['new_password'], $userid);
				if ($password_update) {

					return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::PASSWORD_SUCCESS_MSG, null));
				} else {
					return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::SOMETHING_WRONG_MSG, StringConstants::SOMETHING_WRONG_MSG));
				}
			}
		} catch (Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function sendOtpResetPassword(Request $request)
	{
		try {
			$user_id 	= Auth::guard('api')->user()->id;
			$user_email = Auth::guard('api')->user()->email;
			$subject = notificationTemplates('send_otp')->subject ? notificationTemplates('send_otp')->subject : StringConstants::RESET_PASSWORD_OTP_SUBJECT;
			$otp = rand(1000, 9999);

			$insert_otp = User::insertOtp($user_id, $otp);

			if ($insert_otp) {
				\Mail::to($user_email)->send(new sendOtp($subject, $otp));
				return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::OTP_SEND_SUCCESS_MSG, User::getUserProfile()));
			} else {
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::SOMETHING_WRONG_MSG, StringConstants::SOMETHING_WRONG_MSG));
			}
		} catch (Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function resetPassword(ResetPasswordRequest $request)
	{

		try {

			if (User::verifyOtp($input['otp'], $userid) == false) {

				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::WRONG_OTP_MSG, StringConstants::WRONG_OTP_MSG));
			} else if ((Hash::check(request('new_password'), Auth::guard('api')->user()->password)) == true) {

				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::SAME_PASSWORD_MSG, StringConstants::SAME_PASSWORD_MSG));
			} else {
				$password_update = User::updatePassword($input['new_password'], $userid);
				if ($password_update) {
					User::flushOtp($userid);
					return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::PASSWORD_SUCCESS_MSG, User::getUserProfile()));
				} else {
					return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::SOMETHING_WRONG_MSG, StringConstants::SOMETHING_WRONG_MSG));
				}
			}
		} catch (Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function resendConfirmEmail(Request $request)
	{
		try {
			$email = $request->email;
			$user = User::where('email',$email)->first();
			if($user){

				$is_company_user = isset($user->company_id) ? true : false;
				$is_evaluator = $user->isEvaluator();
                $user->notify(new UserNeedsConfirmation($user->confirmation_code, $user->first_name . ' ' . $user->last_name, null, $is_company_user,$is_evaluator,$user->email_otp));

				return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::EMAIL_RESEND_SUCCESS_MSG, null));
			}
			else{
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::USER_NOT_FOUND_MSG, StringConstants::USER_NOT_FOUND_MSG));
			}
		} catch (\Swift_TransportException $ex) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($ex));
		} catch (\Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function profile()
	{
		try {
			$user_id 	= Auth::guard('api')->user()->id;
			$job_type_names = [];
			$preferred_location_names = [];
			$company_id 	= Auth::guard('api')->user()->company_id;

			$data = User::with('userWorkProfile')->with('userWorkProfileDetail')->find($user_id);

			$data->preferred_location = UserPrefferedData::getData($user_id, 'locations');
			$data->preferred_skills = UserPrefferedData::getData($user_id, 'skills');
			$data->job_type = UserPrefferedData::getData($user_id, 'job_types');
			$data->gig_type = UserPrefferedData::getData($user_id, 'gig_types');

			if (is_array($data->job_type) && count($data->job_type) > 0)
				$job_type_names = MasterData::getNameFromArray($data->job_type);

			$data->job_type_names = $job_type_names;
			if (is_array($data->preferred_location) && count($data->preferred_location) > 0) {
				$preferred_location_names = MasterData::getNameFromArray($data->preferred_location);
				$data->preferred_location_names = $preferred_location_names;
				$data->preferred_location_data = implode(', ', MasterData::getNameFromArray($data->preferred_location)->pluck('name')->toArray());
			} else {
				$data->preferred_location_names = [];
				$data->preferred_location_data = [];
			}

			if (is_array($data->preferred_skills) && count($data->preferred_skills) > 0) {
				$preferred_skill_names = MasterData::getNameFromArray($data->preferred_skills);
				$data->preferred_skill_names = $preferred_skill_names;
			} else {
				$data->preferred_skill_names = [];
			}



			if ($data->userWorkProfile && $data->userWorkProfile->location_id != null && $data->userWorkProfile->location_id != '') {
				$location_name = MasterData::getMasterDataName($data->userWorkProfile->location_id);
				$data->userWorkProfile->location_name = $location_name->name . ', ' . $location_name->description;
				$data->userWorkProfile->location_short_name = $location_name->name;
			}

			if ($data->userWorkProfile && $data->userWorkProfile->joining_preference_id != null && $data->userWorkProfile->joining_preference_id != '' && ($data->userWorkProfile->joining_preference_name == null || $data->userWorkProfile->joining_preference_name == '')) {
				$joining_preference_name = MasterData::getMasterDataName($data->userWorkProfile->joining_preference_id);
				$data->userWorkProfile->joining_preference_name = $joining_preference_name->name;
			}

			$data->progress = User::getUserProfileProgress(Auth::guard('api')->user());

			if ($company_id != NULL && $company_id != '') {
				$data->company = Companies::with('details')->find($company_id);
				$data->company->progress = Companies::getCompanyProgress($data->company);
				$data->userTransactions = Auth::guard('api')->user()->view_transactions;
				if ($data->company->details) {
					$data->company->Details = $data->company->details->groupBy('type');
				}
			}

			if ($data->userWorkProfileDetail) {
				$data->userWorkProfileDetail = $data->userWorkProfileDetail->groupBy('type');
			}

			return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::PROFILE_SUCCESS_MSG, $data));
		} catch (Exception $e) {

			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function sendOtpForgotPassword(SendOtpForgotPasswordRequest $request)
	{

		try {

			$user_details = User::getUserByEmail($request->email);
			if ($user_details == false) {
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::USER_NOT_FOUND_MSG, StringConstants::USER_NOT_FOUND_MSG));
			}

			$user_id 	= $user_details->id;
			$user_email = $user_details->email;
			$user_name  = $user_details->first_name . ' ' . $user_details->last_name;
			$subject = notificationTemplates('send_otp')->subject ? notificationTemplates('send_otp')->subject : StringConstants::FORGOT_PASSWORD_OTP_SUBJECT;
			$otp = rand(1000, 9999);

			$insert_otp = User::insertOtp($user_id, $otp);

			if ($insert_otp) {
				// \Mail::to($user_email)->send(new sendOtp($subject, $otp, $user_name));
				 $data = [
                    'otp'=>$otp,
                    'user_name'=>$user_name,
                    'contact'=>$user_details->contact
                ];
                $template = 'frontend.mail.sendotp';
                $user_details->notify(new OtpForgotPasswordNotification($subject,$data, $template));

				return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::OTP_SEND_SUCCESS_MSG, array()));
			} else {
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::SOMETHING_WRONG_MSG, StringConstants::SOMETHING_WRONG_MSG));
			}
		} catch (Exception $e) {

			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function forgotPassword(ForgotPasswordRequest $request)
	{

		try {
			$user_details = User::getUserByEmail($request->email);
			if ($user_details == false) {
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::USER_NOT_FOUND_MSG, StringConstants::USER_NOT_FOUND_MSG));
			}

			$user_id 	= $user_details->id;
			$user_email = $user_details->email;

			if (User::verifyOtp($request->otp, $user_id) == false) {
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::WRONG_OTP_MSG, StringConstants::WRONG_OTP_MSG));
			} else if ((Hash::check(request('new_password'), $user_details->password)) == true) {
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::SAME_PASSWORD_MSG, StringConstants::SAME_PASSWORD_MSG));
			} else {
				$password_update = User::updatePassword($request->new_password, $user_id);
				$user_details->markEmailAsVerified();

				if ($password_update) {
					User::flushOtp($user_id);

					return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::PASSWORD_SUCCESS_MSG, array()));
				} else {
					return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::SOMETHING_WRONG_MSG, StringConstants::SOMETHING_WRONG_MSG));
				}
			}
		} catch (Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function getCompanyList()
	{
		try {
			$user_id 	= Auth::guard('api')->user()->id;
			$get_companies = Companies::getList();
			$user_blocked_companies = BlockedCompanies::getUserBlockedCompanies($user_id);
			foreach ($get_companies as $key => &$value) {
				if (in_array($value->id, $user_blocked_companies)) {
					$value->is_blocked = 1;
				} else {
					$value->is_blocked = 0;
				}
			}

			return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::GET_COMPANY_SUCCESS_MSG, $get_companies));
		} catch (Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function blockUnblockCompany(BlockUnblockCompanyRequest $request)
	{
		try {

			$user_id 	= Auth::guard('api')->user()->id;
			if ($request->is_blocking == 1) {
				$block_company = BlockedCompanies::blockCompany($user_id, $request->company_id);
				if ($block_company) {

					return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::BLOCK_COMPANY_SUCCESS_MSG, array()));
				}
			} else {
				$unblock_company = BlockedCompanies::unblockCompany($user_id, $request->company_id);
				if ($unblock_company) {

					return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::UNBLOCK_COMPANY_SUCCESS_MSG, array()));
				}
			}

			return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::SOMETHING_WRONG_MSG, StringConstants::SOMETHING_WRONG_MSG));
		} catch (Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function addAndBlockCompany(AddAndBlockCompanyRequest $request)
	{
		try {

			$data = $request->all();
			$site = $data['website'];
			if (strpos($site, 'http') === false && strpos($site, 'https') === false) {
				$data['website'] = 'http://' . $request->website;
			}

			$url = str_replace('www.', '', parse_url($data['website'])['host']);

			$existing = Companies::where('website', 'like', '%' . $url . '%')->select('id', 'name')->first();

			if ($existing) {

				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::COMPANY_EXISTS_MSG, StringConstants::COMPANY_EXISTS_MSG));
			}

			$company_id = Companies::createCompany($data);

			if ($company_id == false) {
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::SOMETHING_WRONG_MSG, StringConstants::SOMETHING_WRONG_MSG));
			}
			$user_id 	= Auth::guard('api')->user()->id;
			$block_company = BlockedCompanies::blockCompany($user_id, $company_id);
			if ($block_company) {

				return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::ADD_BLOCK_COMPANY_SUCCESS_MSG, array()));
			} else {
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::SOMETHING_WRONG_MSG, StringConstants::SOMETHING_WRONG_MSG));
			}
		} catch (Exception $e) {

			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function uploadResume(UploadResumeRequest $request)
	{
		try {

			$filename = str_replace('resumes/', '', $request->file('resume')->store('resumes'));

			$process = new Process(['python3', base_path() . "/resumeParse.py", base_path() . '/storage/app/resumes/' . $filename]);

			$process->run();

			$out 		= [];
			$userUpdate = [];
			$wpDetails 	= [];
			$resumeInfo 	= [];
			$user_id 	= Auth::guard('api')->user()->id;
			if ($process->isSuccessful()) {
				$resumeInfo = json_decode($process->getOutput(), true);

				if (array_key_exists('mobile_number', $resumeInfo) && $resumeInfo['mobile_number'] != null) {
					$userUpdate['contact'] = $resumeInfo['mobile_number'];
				}
				if (array_key_exists('designation', $resumeInfo) && $resumeInfo['designation'] != null) {
					$userUpdate['designation'] = $resumeInfo['designation'];
					if (is_array($userUpdate['designation']) && count($userUpdate['designation']) > 0) {
						$userUpdate['designation'] = $userUpdate['designation'][0];
					}
				}
				if (count($userUpdate) > 0) {
					User::updateUser($user_id, $userUpdate);
				}

				if (array_key_exists('degree', $resumeInfo) && $resumeInfo['degree'] != null) {
					foreach ($resumeInfo['degree'] as $degree) {
						array_push($wpDetails, ['user_id' => $user_id, 'type' => 2, 'title' => $degree, 'remarks' => 'NA - NA']);
					}
				}
				if (array_key_exists('skills', $resumeInfo) && $resumeInfo['skills'] != null) {
					if (count($resumeInfo['skills']) > 10) {
						$resumeInfo['skills'] = array_splice($resumeInfo['skills'], 0, 10);
					}
					foreach ($resumeInfo['skills'] as $skill) {
						array_push($wpDetails, ['user_id' => $user_id, 'type' => 10, 'title' => $skill, 'remarks' => null]);
					}
				}
			}

			$status = false;

			$WorkProfile = WorkProfile::getUserWorkProfile($user_id);
			if ($WorkProfile->count()) {
				if ($WorkProfile->update(['cvLink' => $filename])) {
					$status = true;
				}
			} else {
				$WorkProfile = new WorkProfile;
				$WorkProfile->user_id = $user_id;
				$WorkProfile->cvLink = $filename;
				$WorkProfile->completenessLevel = 1;
				if ($WorkProfile->save()) {
					$status = true;
				}
			}

			if (count($wpDetails) > 0) {
				$WorkProfile = WorkProfile::where('user_id', $user_id)->first();
				foreach ($wpDetails as $ind => $wpD) {
					$wpDetails[$ind]['work_profile_id'] = $WorkProfile->id;
				}
				// dd($wpDetails);
				WorkProfileDetails::insert($wpDetails);
			}

			if ($status) {

				return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::UPLOAD_RESUME_SUCCESS_MSG, $resumeInfo));
			} else {
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::SOMETHING_WRONG_MSG, StringConstants::SOMETHING_WRONG_MSG));
			}
		} catch (Exception $e) {

			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function updateWorkProfile(Request $request)
	{
		try {

			$user_id 	= Auth::guard('api')->user()->id;
			$message = StringConstants::WORK_PROFILE_ADD_SUCCESS_MSG;

			$check_existance = UserWorkProfile::checkUserProfileExist($user_id);
			if ($check_existance) {
				$message = StringConstants::WORK_PROFILE_UPDATE_SUCCESS_MSG;
				UserWorkProfileDetail::deleteData(UserWorkProfile::getWorkProfileId($user_id));
			}


			$user_data = [];
			if ($request->has('first_name') && $request->first_name != null && $request->first_name != '') {
				$user_data['first_name'] =  $request->first_name;
			}
			if ($request->has('last_name') && $request->last_name != null && $request->last_name != '') {
				$user_data['last_name'] =  $request->last_name;
			}

			if (count($user_data) > 0) {
				$user = User::updateUser($user_data, $user_id);
			}

			$work_profile_id = UserWorkProfile::add($request, $user_id);



			if ($work_profile_id != NULL && $work_profile_id != '') {
				if (isset($request->certificate) && count($request->certificate) > 0) {
					foreach ($request->certificate as $certificate) {
						UserWorkProfileDetail::add(array_merge($certificate, [
							'user_id' => $user_id,
							'type' => 'certificate',
							'user_work_profile_id' => $work_profile_id
						]));
					}
				}

				if (isset($request->education_degree) && count($request->education_degree) > 0) {
					foreach ($request->education_degree as $degree) {
						UserWorkProfileDetail::add(array_merge($degree, [
							'user_id' => $user_id,
							'type' => 'degree',
							'user_work_profile_id' => $work_profile_id
						]));
					}
				}

				if (isset($request->experience) && count($request->experience) > 0) {
					foreach ($request->experience as $experience) {
						UserWorkProfileDetail::add(array_merge($experience, [
							'user_id' => $user_id,
							'type' => 'experience',
							'user_work_profile_id' => $work_profile_id
						]));
					}
				}

				if (isset($request->awards) && count($request->awards) > 0) {
					foreach ($request->awards as $award) {
						UserWorkProfileDetail::add(array_merge($award, [
							'user_id' => $user_id,
							'type' => 'award',
							'user_work_profile_id' => $work_profile_id
						]));
					}
				}

				if (isset($request->skills) && count($request->skills) > 0) {
					foreach ($request->skills as $skill) {
						UserWorkProfileDetail::add(array_merge($skill, [
							'user_id' => $user_id,
							'type' => 'skill',
							'user_work_profile_id' => $work_profile_id
						]));
					}
				}

				if (isset($request->links) && count($request->links) > 0) {
					foreach ($request->links as $link) {
						UserWorkProfileDetail::add(array_merge($link, [
							'user_id' => $user_id,
							'type' => 'link',
							'user_work_profile_id' => $work_profile_id
						]));
					}
				}
				UserWorkProfile::updateUserSearchVisibility($user_id);
				UserWorkProfile::updateSearchableHash($user_id);

				return Communicator::returnResponse(ResponseMessages::SUCCESS($message, UserWorkProfile::getDataById($work_profile_id)));
			} else {
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::SOMETHING_WRONG_MSG, StringConstants::SOMETHING_WRONG_MSG));
			}
		} catch (Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function updateProfile(Request $request)
	{
		try {
			$user_id 	= Auth::guard('api')->user()->id;
			$company_id 	= Auth::guard('api')->user()->company_id;
			$data = $request->all();
			if ($company_id != null && $request->has('company') && $request->company != null) {
				$update_company = Companies::updateCompany($request->company, $company_id);
			}
			if (isset($request->job_type) && count($request->job_type) > 0)
				UserPrefferedData::addData($user_id, 'job_types', $request->job_type);
			if (isset($request->gig_type) && count($request->gig_type) > 0)
				UserPrefferedData::addData($user_id, 'gig_types', $request->gig_type);
			if (isset($request->preferred_location) && count($request->preferred_location) > 0)
				UserPrefferedData::addData($user_id, 'locations', $request->preferred_location);
			if (isset($request->preferred_skills) && count($request->preferred_skills) > 0)
				UserPrefferedData::addData($user_id, 'skills', $request->preferred_skills);

			unset($data['company']);
			$userOb = new User;
			$userData = $request->only($userOb->getFillable());
			$user = User::updateUser($userData, $user_id);

			if ($user->isCandidate()) {
				UserWorkProfile::updateSearchableHash($user_id);
			}

			return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::PROFILE_UPDATE_SUCCESS_MSG, $user));
		} catch (Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}


	public function candidateListing(SearchCandidateRequest $request)
	{
		try {
			Logger::logDebug("[Search Candidate API]");
			Logger::logDebug("Request data: " . json_encode($request->all()));
			$pageNumber = 1;
			$limit = 10;
			$isSkillExistInRequest = ($request->has('skills') && count($request->skills) > 0);
			$isLocationExistInRequest = ($request->has('locations') && count($request->locations) > 0);
			$isJobTypeExistInRequest = ($request->has('job_types') && count($request->job_types) > 0);
			$isJoiningPreferenceExistInRequest = ($request->has('joining_preferences') && count($request->joining_preferences) > 0);
			$isSalaryExistInRequest = ($request->has('min_salary') && count($request->min_salary) > 0 && $request->has('max_salary') && count($request->max_salary) > 0);
			$isTelecommuteExistInRequest = ($request->has('is_telecommute') && $request->is_telecommute == '1');
			$isLayoffExistInRequest = ($request->has('layoff') && $request->layoff == '1');
			$isHerCareerRebootExistInRequest = ($request->has('her_career_reboot') && $request->her_career_reboot == '1');
			$isDifferentlyExistInRequest = ($request->has('differently_abled') && $request->differently_abled == '1');
			$isArmedForcesExistInRequest = ($request->has('armed_forces') && $request->armed_forces == '1');

			$reqData = $request->all();
			$reqCount = 0;
			if ($request->has('pageNumber')) {
				$pageNumber = $request->pageNumber;
			}

			if ($request->has('limit')) {
				$limit = $request->limit;
			}
			\DB::enableQueryLog();
			$skill_ids = [];
			$location_ids = [];
			$skill_weightage = 100;
			$location_weightage = 100;
			$show_percentage = false;
			if ($isSkillExistInRequest) {
				$show_percentage = true;
				$skill_ids = $request->skills;
			}
			if ($isLocationExistInRequest) {
				$show_percentage = true;
				$location_ids = $request->locations;
			}

			if ($isSkillExistInRequest && $isLocationExistInRequest) {
				$skill_weightage = 82;
				$location_weightage = 18;
			}
			$skill_users_count = 0;

			if ($isSkillExistInRequest && $isLocationExistInRequest) {
				$query = User::role(config('access.users.candidate_role'))->select('users.id', 'users.first_name', 'users.last_name', 'users.email', 'user_work_profiles.total_experience', 'location_master.name AS location_name', 'user_work_profiles.updated_at', DB::raw('group_concat(distinct master_data.name) as skills'),

					DB::raw('

						(IFNULL(skillPercentage.profile_percentage, 0) 
						+ 
						if(count(user_preffered_locations.data_id) > 0, IFNULL(locationPercentage.profile_percentage, 0), IFNULL(locationPercentage.profile_percentage, 0.33 * '.$location_weightage.'))
						+ 

						IFNULL(currentLocationPercentage.profile_percentage, 2)
					) 

						AS profile_percentage'));
			}elseif ($isSkillExistInRequest) {
				$query = User::role(config('access.users.candidate_role'))->select('users.id', 'users.first_name', 'users.last_name', 'users.email', 'user_work_profiles.total_experience', 'location_master.name AS location_name', 'user_work_profiles.updated_at', DB::raw('group_concat(distinct master_data.name) as skills'),'skillPercentage.profile_percentage');
			}elseif ($isLocationExistInRequest) {
				$query = User::role(config('access.users.candidate_role'))->select('users.id', 'users.first_name', 'users.last_name', 'users.email', 'user_work_profiles.total_experience', 'location_master.name AS location_name', 'user_work_profiles.updated_at', DB::raw('group_concat(distinct master_data.name) as skills'),DB::raw(

					'

					if(count(user_preffered_locations.data_id) > 0, IFNULL(locationPercentage.profile_percentage, 0), IFNULL(locationPercentage.profile_percentage, 0.33 * '.$location_weightage.'))
					+ 
						IFNULL(currentLocationPercentage.profile_percentage, 8) 

						AS profile_percentage'));
			}else{
				$query = User::role(config('access.users.candidate_role'))->select('users.id', 'users.first_name', 'users.last_name', 'users.email', 'user_work_profiles.total_experience', 'location_master.name AS location_name', 'user_work_profiles.updated_at', DB::raw('group_concat(distinct master_data.name) as skills'));
			}
				
			$query->leftJoin('user_work_profiles', 'users.id', '=', 'user_work_profiles.user_id')
				->leftJoin('user_work_profile_details', 'users.id', '=', 'user_work_profile_details.user_id')
				->leftJoin('user_work_profile_details AS user_detail_table', 'users.id', '=', 'user_detail_table.user_id')
				->leftJoin('master_data', 'master_data.id', '=', 'user_detail_table.skill_id')
				->leftJoin('master_data AS location_master', 'location_master.id', '=', 'user_work_profiles.location_id');

			if ($isSkillExistInRequest) {
				$query->leftJoin(DB::raw('(SELECT (count(DISTINCT skill_id)/'.count($skill_ids).')*'.$skill_weightage.' as profile_percentage, users.id as buid  FROM `users` left join user_work_profile_details on users.id = user_work_profile_details.user_id where user_work_profile_details.type = "skill" and user_work_profile_details.skill_id IN ('.implode(', ', $skill_ids).') GROUP BY users.id
			    ) as skillPercentage'), function ($join) {
			        $join->on ( 'skillPercentage.buid', '=', 'users.id' );
			    });
			}

			if ($isLocationExistInRequest) {
				$query->leftJoin(DB::raw('(SELECT (count(DISTINCT data_id)/'.count($location_ids).')* 0.6666 *'.$location_weightage.' as profile_percentage, users.id as buid  FROM `users` left join user_preffered_data on users.id = user_preffered_data.user_id where user_preffered_data.type = "locations" and user_preffered_data.data_id IN ('.implode(', ', $location_ids).') GROUP BY users.id
			    ) as locationPercentage'), function ($join) {
			        $join->on ( 'locationPercentage.buid', '=', 'users.id' );
			    });

			    $query->leftJoin(DB::raw('(SELECT (count(DISTINCT location_id)/'.count($location_ids).')* 0.3333 *'.$location_weightage.' as profile_percentage, users.id as buid  FROM `users` left join user_work_profiles on users.id = user_work_profiles.user_id where user_work_profiles.location_id IN ('.implode(', ', $location_ids).') GROUP BY users.id
			    ) as currentLocationPercentage'), function ($join) {
			        $join->on ( 'currentLocationPercentage.buid', '=', 'users.id' );
			    });
			}

			// if (AppConfig::isRemoveIncompleteCandidates()) {
		
			// 	$query->join('user_work_profile_details AS skill_table', function($join)
   //              {
   //                  $join->on('skill_table.user_id', '=', 'users.id');
   //                  $join->where('skill_table.type','=', 'skill');
   //              });

   //              // $query->join('user_work_profile_details AS degree_table', function($join)
   //              // {
   //              //     $join->on('degree_table.user_id', '=', 'users.id');
   //              //     $join->where('degree_table.type','=', 'degree');
   //              // });
			// }


			if ($isSkillExistInRequest) {

				// $query->leftJoin('user_preffered_data AS user_preffered_skills', 'users.id', '=', 'user_preffered_skills.user_id');
				$skill_ids = $request->skills;
				// $skills_name = MasterData::getSkillsName($skill_ids);

				// foreach ($skills_name as $key => $name) {
				// 	$skill_ids = array_merge($skill_ids,MasterData::getSameSkillsIds($name));
				// }
				$query->whereIn('user_work_profile_details.skill_id', $skill_ids);
				// $query->where(function ($query) use ($skill_ids) {


				// 	$query->where(function ($query) use ($skill_ids) {
				// 		$query->where('users.is_preferred_skills', '0');
				// 		$query->whereIn('user_work_profile_details.skill_id', $skill_ids);
				// 	});

				// 	$query->orWhere(function ($query) use ($skill_ids) {
				// 		$query->where('users.is_preferred_skills', '1');
				// 		$query->whereIn('user_preffered_skills.data_id', $skill_ids);
				// 	});
				// });

				$reqCount += 1;
			}

			if ($isLocationExistInRequest) {

				$query->leftJoin('user_preffered_data AS user_preffered_locations', function($join)
                {
                    $join->on('users.id', '=', 'user_preffered_locations.user_id');
                    $join->where('user_preffered_locations.type','=', 'locations');
                });

				$locations = $request->locations;
				$query->where(function ($query) use ($locations) {

					$query->whereIn('user_preffered_locations.data_id', $locations);
					$query->orWhere('user_preffered_locations.id', null);
					$query->orWhereIN('user_work_profiles.location_id', $locations);
				});
				$reqCount += 1;
			}

			if ($isJobTypeExistInRequest) {

				$query->leftJoin('user_preffered_data AS user_preffered_job_type', 'users.id', '=', 'user_preffered_job_type.user_id');
				
				$job_types = $request->job_types;
				$query->whereIn('user_preffered_job_type.data_id', $job_types);
				$reqCount += 1;
			}

			if ($isJoiningPreferenceExistInRequest) {
				$query->whereIn('user_work_profiles.joining_preference_id', $request->joining_preferences);
				$reqCount += 1;
			}

			if ($request->has('work_authorization') && count($request->work_authorization) > 0) {
				$query->whereIn('user_work_profiles.work_authorization_id', $request->work_authorization);
				$reqCount += 1;
			}

			if ($isSalaryExistInRequest) {
				$query->where(function ($query) use ($request) {
					for ($i = 0; $i < count($request->min_salary); $i++) {
						if (isset($request->min_salary[$i]) && isset($request->max_salary[$i])) {
							$min = $request->min_salary[$i];
							$max = $request->max_salary[$i];

							if ($i == 0) {
								if ($min == 0 && $max == 0) {
									$query->where('users.min_salary', NULL);
								}else{

									$query->where(function ($query) use ($min, $max) {
										$query->where('users.min_salary', '>=', $min);
										$query->where('users.min_salary', '<=', $max);
									});
								}
							} else {
								if ($min == 0 && $max == 0) {
									$query->orWhere('users.min_salary', NULL);
								}else{
									
									$query->orWhere(function ($query) use ($min, $max) {
										$query->where('users.min_salary', '>=', $min);
										$query->where('users.min_salary', '<=', $max);
									});
								}
							}
						}
					}
				});
				$reqCount += 1;
			}

			if ($isTelecommuteExistInRequest) {
				$query->where('users.is_telecommute', $request->is_telecommute);
				$reqCount += 1;
			}

			if ($request->has('min_experience') && $request->min_experience > 0) {
				$query->where('user_work_profiles.total_experience', '>=', $request->min_experience);
			}

			if ($request->has('max_experience') && $request->max_experience > 0 && $request->max_experience != 240) {
				$query->where('user_work_profiles.total_experience', '<=', $request->max_experience);
			}

			if ($request->has('min_experience') && $request->min_experience > 0 && $request->has('max_experience') && $request->max_experience > 0) {
				$reqCount += 1;
			}


			if ($isLayoffExistInRequest) {
				$query->where('user_work_profiles.layoff', '=', $request->layoff);
				$reqCount += 1;
			}

			if ($isHerCareerRebootExistInRequest) {
				$query->where('user_work_profiles.her_career_reboot', '=', $request->her_career_reboot);
				$reqCount += 1;
			}

			if ($isDifferentlyExistInRequest) {
				$query->where('user_work_profiles.differently_abled', '=', $request->differently_abled);
				$reqCount += 1;
			}

			if ($isArmedForcesExistInRequest) {
				$query->where('user_work_profiles.armed_forces', '=', $request->armed_forces);
				$reqCount += 1;
			}

			if ($request->has('updated_at_from') && $request->get('updated_at_from') != null) {
				$query->where('user_work_profiles.updated_at', '>=', $request->updated_at_from . ' 00:00:00');
			}
			if ($request->has('updated_at_to') && $request->get('updated_at_to') != null) {
				$query->where('user_work_profiles.updated_at', '<=', $request->updated_at_to . ' 23:59:59');
			}

			if ($request->has('updated_at_from') && $request->get('updated_at_from') != null && $request->has('updated_at_to') && $request->get('updated_at_to') != null) {
				$reqCount += 1;
			}

			if (Auth::guard('api')->check()) {
				$company_id 	= Auth::guard('api')->user()->company_id;
				$blocked_by_users = BlockedCompanies::where('company_id', $company_id)->pluck('candidate_id')->toArray();
				$query->whereNotIn('users.id', $blocked_by_users);
			}
			// always add in the end
			$query->where('users.company_id', NULL);


			if (AppConfig::isRemoveIncompleteCandidates()) {
				$query->where('user_work_profiles.search_visibility','1');
		
				// User::removeIncompleteProfileQuery($query);
			}


			$query->where('user_work_profiles.updated_at', '!=', NULL);


			if ($request->has('q') && $request->q != '') {
               $q = str_getcsv(strtolower($request->q), ' ');
                
                $query->where(function ($query) use ($q) {
                    foreach ($q as $key => $word) {
                    	$word = trim($word);
                    	if ($word != '' && $word != 'or' && $word != 'and' && $word != 'not') {

                    		if ($key > 0 && $q[$key - 1] == 'not') {
							    $query->orWhere('user_work_profiles.searchable_hash', 'NOT LIKE', '%' . $word . '%');
							}elseif ($key > 0 && $q[$key - 1] == 'and') {
							    $query->where('user_work_profiles.searchable_hash', 'LIKE', '%' . $word . '%');
							}else{

                				$query->orWhere('user_work_profiles.searchable_hash', 'LIKE', '%' . $word . '%');
							}
						}
                    }
                });
            }

			if ($request->has('order_by') && $request->order_by == "MOST_RECENT") {
				$query->orderBy('user_work_profiles.updated_at', "DESC");
			} elseif ($request->has('order_by') && $request->order_by == "POST_DATE") {
				$query->orderBy('user_work_profiles.updated_at', "ASC");
			} elseif (($isSkillExistInRequest || $isLocationExistInRequest) && $request->has('order_by') && $request->order_by == "MOST_RELEVANT") {
				$query->orderBy('profile_percentage', "DESC");
				$query->orderBy('total_experience', "DESC");
			}
			$query->groupBy('users.id');

			$users = $query->paginate($limit, ['*'], 'page', $pageNumber);
			

			if ($isSkillExistInRequest) {
				$skills_query = User::select('users.id', 'users.first_name', 'users.last_name', 'users.email', 'user_work_profiles.total_experience', 'user_work_profiles.location_name', 'user_work_profiles.updated_at', DB::raw('group_concat(distinct master_data.name) as skills'))
					->leftJoin('user_work_profiles', 'users.id', '=', 'user_work_profiles.user_id')
					->leftJoin('user_work_profile_details', 'users.id', '=', 'user_work_profile_details.user_id')
					->leftJoin('user_work_profile_details AS user_detail_table', 'users.id', '=', 'user_detail_table.user_id')
					->leftJoin('master_data', 'master_data.id', '=', 'user_detail_table.skill_id')
					->where('user_work_profiles.updated_at', '!=', NULL)
					->where('users.company_id', NULL);

				

				// $skills_query->leftJoin('user_preffered_data AS user_preffered_skills', 'users.id', '=', 'user_preffered_skills.user_id');
				$skill_ids = $request->skills;
				$skills_query->whereIn('user_work_profile_details.skill_id', $skill_ids);
				// $skills_query->where(function ($query) use ($skill_ids) {


				// 				$query->where(function ($query) use ($skill_ids) {
				// 					$query->where('users.is_preferred_skills', '0');
				// 					$query->whereIn('user_work_profile_details.skill_id', $skill_ids);
				// 				});

				// 				$query->orWhere(function ($query) use ($skill_ids) {
				// 					$query->where('users.is_preferred_skills', '1');
				// 					$query->whereIn('user_preffered_skills.data_id', $skill_ids);
				// 				});
				// 			});

				if (AppConfig::isRemoveIncompleteCandidates()) {

					$skills_query->where('user_work_profiles.search_visibility','1');
				}

				$skill_users = $skills_query->groupBy('users.id')->get();
				$skill_users_count = count($skill_users);


			}

			Logger::logDebug("Query build: " . json_encode(\DB::getQueryLog()));

			$data['candidates'] = $users;
			$data['skill_users_count'] = $skill_users_count;
			$data['show_percentage'] = $show_percentage;

			return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::CANDIDATES_LISTING_SUCCESS_MSG, $data));
		} catch (Exception $e) {

			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function deleteAccount()
	{
		try {
			$user 	= Auth::guard('api')->user();

			if ($user->isCandidate()) {

				Chat::where('candidate_id',$user->id)->delete();
				User::deleteMyAccount($user);
				return Communicator::returnResponse(ResponseMessages::ACCOUNT_DELETED_LOGIN_SUCCESS(StringConstants::ACCOUNT_DELETE_SUCCESS_MSG, null));

			}elseif ($user->isCompanyUser() || $user->isCompanyAdmin()) {

				$company_admin = User::role('company admin')->where('company_id',$user->company_id)->where('id','!=',$user->id)->first();
				if ($company_admin) {
					$company_admin_id = $company_admin->id;
					$update_job = Job::where('user_id',$user->id)->update([
						'user_id' => $company_admin_id
					]);

					$update_gig = CompanyGig::where('user_id',$user->id)->update([
						'user_id' => $company_admin_id
					]);
					Chat::where('recruiter_id',$user->id)->delete();
					User::deleteMyAccount($user);

					return Communicator::returnResponse(ResponseMessages::ACCOUNT_DELETED_LOGIN_SUCCESS(StringConstants::ACCOUNT_DELETE_SUCCESS_MSG, null));
				}else{
					return Communicator::returnResponse(ResponseMessages::CONTACT_ADMIN_SUCCESS(StringConstants::SUCCESSS, StringConstants::SUCCESSS));
				}

			}
		} catch (Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function restoreAccount(LoginRequest $request)
	{
		try {
			$credentials = [
				'email' => $request->email,
				'password' => $request->password,
			];

			$user = User::withTrashed()->where('email',$credentials['email'])->where('deleted_at','!=',null)->first();
			if ($user && $user->id == $user->deleted_by) {
				if ($user->isCandidate() || $user->isCompanyUser()) {

					DB::beginTransaction();
					Chat::where('candidate_id',$user->id)->restore();
					$user->restore();

					if (auth()->attempt($credentials)) {
						DB::commit();
						return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::ACCOUNT_RESTORE_SUCCESS_MSG, null));
					}else{
						DB::rollBack();
						return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::LOGIN_ERROR_MSG, StringConstants::LOGIN_ERROR_MSG));
					}
					

				}else{
					return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::LOGIN_ERROR_MSG, StringConstants::LOGIN_ERROR_MSG));
				}
			}

			
		} catch (Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}
}
