<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Communicator\Communicator;
use App\Constants\ResponseMessages;
use App\Constants\StringConstants;
use Braintree;
use  App\Http\Requests\API\CompanyUserStatusUpdateRequest;
use  App\Http\Requests\API\CompanyUserRoleUpdateRequest;
use  App\Http\Requests\API\OfflinePaymentRequest;
use  App\Http\Requests\API\OnlinePaymentRequest;
use  App\Http\Requests\API\UserWorkProfileRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Exception;;
use App\Notifications\Frontend\Auth\UserNeedsConfirmation;
use App\Notifications\Frontend\Auth\UserConfirm;
use App\Models\ProfileViewTransactions;
use App\Models\Companies;
use App\Models\PaymentTransactions;
use App\Models\SystemSettings;
use App\Models\UserWorkProfile;
use App\Mail\OfflineTTCash;
use App\Mail\SendTransactionEmail;
use App\Models\BlockedCompanies;
use App\Models\MasterData;
use App\Models\UserPrefferedData;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\Job;
use App\Models\CompanyDetail;
use App\Helpers\SiteHelper;
use App\Mail\SendCompanyUserDifferentDomainEmail;
use App\Config\AppConfig;
use App\AppRinger\ImageUtils;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use App\Exports\ExportTransactions;
use Maatwebsite\Excel\Facades\Excel;
use App\Mail\SendTransactions;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Frontend\CompanyUserDifferentDomainEmailNotification;

class CompanyController extends Controller
{

	protected $gateway;
	public function __construct()
	{
		// $this->gateway = new Braintree\Gateway([
		// 	'environment' => config('app.BRAINTREE.ENV'),
		// 	'merchantId' => config('app.BRAINTREE.MERCHANTID'),
		// 	'publicKey' => config('app.BRAINTREE.PUBLICKEY'),
		// 	'privateKey' => config('app.BRAINTREE.PRIVATEKEY')
		// ]);

		$this->RZPkeys   = AppConfig::getRazorPayKeys();
		$this->razorpay = new Api($this->RZPkeys['key'], $this->RZPkeys['secret']);
	}


	public function getCompanyUsers(Request $request)
	{
		try {
			$user = Auth::guard('api')->user();
			$company_id = $user->company_id;
			$user_id = $user->id;

			$start_date = date('Y-m-01 00:00:01');
			$end_date = date('Y-m-d 23:59:59');
			if ($request->has('start_date') && $request->start_date != null && $request->start_date != '') {
				$start_date = date("Y-m-d 00:00:01", strtotime($request->start_date));
			}

			if ($request->has('end_date') && $request->end_date != null && $request->end_date != '') {
				$end_date = date("Y-m-d 23:59:59", strtotime($request->end_date));
			}

			$users['users'] = [];

			$users['company'] = Auth::guard('api')->user()->companyDetails;
			$users['ttcash_dollar'] = config('app.TTCASH_DOLLAR');

			if ($user->isCompanyAdmin()) {
				$users['users'] = User::withTrashed()->where('company_id', Auth::guard('api')->user()['company_id'])->get()->map->append(['uid', 'remaining_views', 'role_type']);

				$users['company']->view_transactions = Companies::find($company_id)->view_transactions()
	                    ->where('profile_view_transactions.user_id' ,null)
	                    ->where('profile_view_transactions.created_at', '>=' ,$start_date)
	                    ->where('profile_view_transactions.created_at', '<=' ,$end_date)
	                    ->get();
			}else {
				$users['company']->view_transactions = User::find($user_id)->view_transactions()
	                    ->where('profile_view_transactions.created_at', '>=' ,$start_date)
	                    ->where('profile_view_transactions.created_at', '<=' ,$end_date)
	                    ->get();
			}
            $users['start_date'] = $start_date;
            $users['end_date'] = $end_date;
			return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::COMPANY_USERS_SUCCESS_MSG, $users));
		} catch (Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function updateCompanyUserStatus(CompanyUserStatusUpdateRequest $request)
	{
		try {

			$loggedin_user = Auth::guard('api')->user();

			$update_status = User::updateCompanyUserStatus($loggedin_user, $request->user_id);
			if ($update_status) {
				return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::USER_STATUS_UPDATE_SUCCESS_MSG, null));
			} else {
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::SOMETHING_WRONG_MSG, StringConstants::SOMETHING_WRONG_MSG));
			}
		} catch (Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function updateCompanyUserRole(CompanyUserRoleUpdateRequest $request)
	{
		try {
			$loggedin_user = Auth::guard('api')->user();
			$update_role = User::updateCompanyUserRole($loggedin_user, $request->user_id);
			if ($update_role) {
				return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::USER_ROLE_UPDATE_SUCCESS_MSG, null));
			} else {
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::SOMETHING_WRONG_MSG, StringConstants::SOMETHING_WRONG_MSG));
			}
		} catch (Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function saveCompanyUser(Request $request)
	{
		try {
			$user_remaining = 0;
			$loggedin_user = Auth::guard('api')->user();
			$company_remaining = 5;//$loggedin_user->companyDetails->remaining_views;
			if ($request->has('id') && $request->get('id') != null && $request->get('id') != 'null') {
				$user_remaining = 0;//User::whereId($request->id)->withTrashed()->first()->remaining_views;
			}
			$max_views = $company_remaining;
			// $max_views = $company_remaining + $user_remaining;
			$validator  =  Validator::make(
				$request->all(),
				[
					'id' => 'nullable|exists:users,id',
					'first_name' => 'required|string',
					'last_name' => 'required|string',
					'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($request->get('id'))],
					'total_profile_views' => 'required|numeric|min:0|max:' . $max_views
				],
				[
					'email.unique' => 'Email already in use, please enter a new one.',
					'total_profile_views.max' => "You have $max_views TT Cash balance. You can purchase more here."
				]
			);

			if ($validator->fails()) {
				return Communicator::returnResponse(ResponseMessages::VALIDATOR_FAIL($validator->errors()->first(), $validator->errors()));
			}
			$data = $request->all();

			$email 	= $data['email'];
			$url 	= $loggedin_user->companyDetails->website;

			$website_domain = SiteHelper::getDomain($url);
			$email_domain   = SiteHelper::getDomainFromEmail($email);

			if (!$website_domain) {
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::INVALID_WEBSITE_ERROR_MSG, StringConstants::INVALID_WEBSITE_ERROR_MSG));
			} elseif (!$email_domain) {
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::INVALID_EMAIL_ERROR_MSG, StringConstants::INVALID_EMAIL_ERROR_MSG));
			} elseif (strtolower($website_domain) != strtolower($email_domain)) {
				/*Send Email to Admin and User*/
				$data['reciepient_first_name'] = $loggedin_user->first_name;
				$data['reciepient_last_name'] = $loggedin_user->last_name;
				// Mail::to($loggedin_user->email)->cc(AppConfig::getAdminEmail())->send(new SendCompanyUserDifferentDomainEmail($data));
				$template = 'frontend.mail.user_registration_request';
				$subject = notificationTemplates('user_registration_request')->subject ? notificationTemplates('user_registration_request')->subject : 'Issue with User Registration';
				$ccMail = [AppConfig::getAdminEmail()];
                $loggedin_user->notify(new CompanyUserDifferentDomainEmailNotification($subject,$data, $template,$via=[],$ccMail));

				return Communicator::returnResponse(ResponseMessages::NO_REGISTRATION_SUCCESS(StringConstants::DIFFERENT_USER_EMAIL_COMPANY_WEBSITE_DOMAIN_ERROR_MSG, $data));
			}

			$firstTimeData = ['company_id' => $loggedin_user->companyDetails->id, 'email' => $data['email']];
			$emailChanged = False;
			$matchCondition = ['email' => $data['email']];
			if (!($request->has('id') && $request->get('id') != null && $request->get('id') != 'null')) {
				$firstTimeData['password'] = 'qwerty';
			} else {
				$matchCondition = ['id' => $request->get('id')];
				$user = User::whereId($request->get('id'))->withTrashed()->first();
				if ($user['email'] != $data['email']) {
					$emailChanged = True;
					$firstTimeData['password'] = Hash::make(substr(Str::random(), 0, 6));
				}
			}

			DB::beginTransaction();
			if (User::withTrashed()->updateOrCreate(
				$matchCondition,
				array_merge($request->only('first_name', 'last_name'), $firstTimeData)
			)->assignRole(config('access.users.company_user_role'))) {
				if (!($request->has('id') && $request->get('id') != null && $request->get('id') != 'null')) {
					$user = User::where('email', $data['email'])->first();
					$user->assignRole(config('access.users.company_user_role'));
					$user->markEmailAsVerified();
					$password = substr(Str::random(), 0, 6);
					$user->update(['password' => Hash::make($password)]);
					$user->notify(new UserConfirm($user->email, $user->first_name, $password, $loggedin_user->full_name));
				}
				if ($emailChanged) {
					$user = User::where('email', $data['email'])->withTrashed()->first();
					$user->notify(new UserConfirm($user->email, $user->first_name, $firstTimeData['password'],$user->full_name));
				}
				if ($user_remaining != $data['total_profile_views']) {
					$type = 'credit';
					$company_tr_type = 'debit';
					$amount = $data['total_profile_views'] - $user_remaining;
					$remaining = $data['total_profile_views'];

					if ($user_remaining > $data['total_profile_views']) {
						$type = 'debit';
						$amount = $user_remaining - $data['total_profile_views'];
						$company_tr_type = 'credit';
						$company_remaining += $amount;
					} else {
						$company_remaining -= $amount;
					}

					// Record view transaction for User
					ProfileViewTransactions::create([
						'company_id' => $loggedin_user->companyDetails->id,
						'user_id' => User::where('email', $data['email'])->first()->id,
						'type' => $type,
						'amount' => $amount,
						'remaining' => $remaining,
						'by' => $loggedin_user->id
					]);

					// Record view transaction for Company
					ProfileViewTransactions::create([
						'company_id' => $loggedin_user->companyDetails->id,
						// 'user_id' => $loggedin_user->id,
						'type' => $company_tr_type,
						'amount' => $amount,
						'remaining' => $company_remaining,
						'by' => $loggedin_user->id,
						'company_user_id' => User::where('email', $data['email'])->first()->id
					]);
				}
				DB::commit();
				if ($request->has('id'))
					$message = StringConstants::COMPANY_USER_EDIT_SUCCESS_MSG;
				else
					$message = StringConstants::COMPANY_USER_ADD_SUCCESS_MSG;
				return Communicator::returnResponse(ResponseMessages::SUCCESS($message, null));
			}
			DB::rollBack();
			return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::SOMETHING_WRONG_MSG, StringConstants::SOMETHING_WRONG_MSG));
		} catch (\Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function offlinePayment(OfflinePaymentRequest $request)
	{
		try {
			$loggedin_user = Auth::guard('api')->user();
			Mail::send(new OfflineTTCash($company = Companies::whereId($request->get('company_id'))->first(), $request->get('amount'), $loggedin_user));
			return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::OFFLINE_PAYMENT_SUCCESS_MSG, null));
		} catch (Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function buyTTCash(OnlinePaymentRequest $request)
	{
		try {
			$loggedin_user = Auth::guard('api')->user();
			$result = $this->gateway->transaction()->sale([
				'amount' => $request->get('amount') * ((float) config('app.TTCASH_DOLLAR')),
				'paymentMethodNonce' => $request->get('nonce'),
				'deviceData' => $request->get('deviceData'),
				'options' => [
					'submitForSettlement' => True
				]
			]);
			if ($result->success) {
				try {
					DB::beginTransaction();

					PaymentTransactions::create([
						'company_id' => $loggedin_user->companyDetails->id,
						'user_id' => $loggedin_user->id,
						'amount' => '$' . $result->transaction->amount,
						'transaction_id' => $result->transaction->id,
						'transaction_details' => json_encode($result->transaction)
					]);
					ProfileViewTransactions::create([
						'company_id' => $loggedin_user->companyDetails->id,
						'user_id' => 0,
						'type' => 'credit',
						'amount' => $request->get('amount'),
						'remaining' => $loggedin_user->companyDetails->remaining_views + $request->get('amount'),
						'by' => $loggedin_user->id
					]);
					DB::commit();
					return Communicator::returnResponse(ResponseMessages::SUCCESS($request->get('amount') . StringConstants::ONLINE_PAYMENT_SUCCESS_MSG, null));
				} catch (Exception $e) {
					DB::rollBack();
					Log::error('Company.buyTTCash', ['error' => $e, 'data' => $request->all(), 'transaction_id' => $result->transaction->id]);
					return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
				}
			}
			Log::error('Company.buyTTCash.btTransactionError', ['error' => json_encode($result->errors->deepAll()), 'data' => $request->all(), 'bt_msg' => $result->message]);
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($result->message));
		} catch (Exception $bte) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function getToken(Request $request)
	{
		$cid  = [];
		$clientToken = $this->gateway->clientToken()->generate($cid);
		$data['clientToken'] = $clientToken;
		return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::GET_TOKEN_SUCCESS_MSG, $data));
	}

	public function userWorkProfile(UserWorkProfileRequest $request)
	{
		try {
			$user_id = $request->id;
			$wp = UserWorkProfile::where('user_id', $user_id);
			if (Auth::guard('api')->check()) {
				$company_id 	= Auth::guard('api')->user()->company_id;
				$blocked_by_users = BlockedCompanies::where('company_id', $company_id)->pluck('candidate_id')->toArray();
				if (in_array($user_id, $blocked_by_users)) {
					return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::COMPANY_BLOCKED_BY_USER, StringConstants::COMPANY_BLOCKED_BY_USER));
				}
			}

			if (!$wp->count() || $wp->first()->search_visibility == '0') {
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::WORKPROFILE_INCOMPLETE, StringConstants::WORKPROFILE_INCOMPLETE));
			}
			if (Auth::guard('api')->user()->roles[0]['name'] == config('access.users.candidate_role') && Auth::guard('api')->user()->id != $user_id) {
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::NOT_AUTHORIZE_TO_VIEW, StringConstants::NOT_AUTHORIZE_TO_VIEW));
			}

			$evaluation = true;
			$evaluation_validity = '';
			$chargeAmounts = SystemSettings::first();
			if (Auth::guard('api')->user()->roles[0]['name'] == config('access.users.company_admin_role') || Auth::guard('api')->user()->roles[0]['name'] == config('access.users.company_user_role')) {
				if ($chargeAmounts->profile_view_ttcash > 0) {

					// Check existing access, if any
					$existingTransaction = ProfileViewTransactions::where(['company_id' => Auth::guard('api')->user()->company_id, 'candidate_id' => $user_id, 'for' => 'profile'])->where('created_at', '>=', Carbon::now()->subDays(env('VIEWVALIDITYDAYS'))->toDateTimeString())->first();
					if (!$existingTransaction || $existingTransaction == null) {
						// Check View Balance
						$remaining = Auth::guard('api')->user()->remaining_views;
						if ($remaining == 0) {
							$no_view = true;
							return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::NO_TT_CASH_ERROR_MSG, StringConstants::NO_TT_CASH_ERROR_MSG));
						}

						// Charge View
						$pvt = ProfileViewTransactions::create([
							'company_id' => Auth::guard('api')->user()->companyDetails->id,
							'user_id' => Auth::guard('api')->user()->id,
							'type' => 'debit',
							'amount' => $chargeAmounts->profile_view_ttcash,
							'remaining' => $remaining - $chargeAmounts->profile_view_ttcash,
							'by' => Auth::guard('api')->user()->id,
							'candidate_id' => $user_id
						]);

						$evaluation_validity = Carbon::create($pvt->created_at)->addDays(config('app.VIEWVALIDITYDAYS'))->format('d-M-Y h:i:s');
					} else {
						$evaluation_validity = Carbon::create($existingTransaction->created_at)->addDays(config('app.VIEWVALIDITYDAYS'))->format('d-M-Y h:i:s');
					}

					// Check existing evaluation view transaction
					$existingEvaluationTransaction = ProfileViewTransactions::where(['user_id' => Auth::guard('api')->user()->id, 'candidate_id' => $user_id, 'for' => 'evaluation'])->where('created_at', '>=', Carbon::now()->subDays(env('VIEWVALIDITYDAYS'))->toDateTimeString())->first();
					// dd($existingEvaluationTransaction);
					if (!$existingEvaluationTransaction || $existingEvaluationTransaction == null) {
						$evaluation = false;
					}
				}
				$wp = UserWorkProfile::where('user_id', $user_id);
				if ($wp->count()) {
					$wp = $wp->first();
					$user = User::with('userWorkProfile', 'userWorkProfile.locationData')->with('userWorkProfileDetail')->find($user_id);
					$user->preferred_location = UserPrefferedData::where('user_id', $user_id)->where('type', 'locations')->pluck('data_id')->toArray();
					$user->preferred_job_types = UserPrefferedData::where('user_id', $user_id)->where('type', 'job_types')->pluck('data_id')->toArray();
					$user->preferred_gig_types = UserPrefferedData::where('user_id', $user_id)->where('type', 'gig_types')->pluck('data_id')->toArray();
					$preferred_location_data = MasterData::getNameFromArray($user->preferred_location)->pluck('name')->toArray();
					$preferred_job_type_data = MasterData::getNameFromArray($user->preferred_job_types)->pluck('name')->toArray();
					$user['evaluation'] = $evaluation;
					$user['evaluation_validity'] = $evaluation_validity;
					$user['chargeAmounts'] = $chargeAmounts;
					$user['preferred_location_data'] = implode(', ', $preferred_location_data);
					$user['preferred_job_type_data'] = implode(', ', $preferred_job_type_data);

					if ($user->userWorkProfile && $user->userWorkProfile->joining_preference_id != null && $user->userWorkProfile->joining_preference_id != '' ) {
						$joining_preference_name = MasterData::getMasterDataName($user->userWorkProfile->joining_preference_id);
						$user->userWorkProfile->joining_preference_name = $joining_preference_name->name;
					}


					if ($user->userWorkProfileDetail) {
						$user->userWorkProfileDetail = $user->userWorkProfileDetail->groupBy('type');
					}

					return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::PROFILE_SUCCESS_MSG, $user));
				} else {
					return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::WORKPROFILE_NOT_EXISTS, StringConstants::WORKPROFILE_NOT_EXISTS));
				}
			} else {
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::NOT_AUTHORIZE_TO_VIEW, StringConstants::NOT_AUTHORIZE_TO_VIEW));
			}
		} catch (Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function buyCandidateEvaluation($user_id = null)
	{
		try {
			$wp = UserWorkProfile::where('user_id', $user_id);
			if (!$wp->count()) {
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::ACCESS_DENIED_ERROR_MSG, StringConstants::ACCESS_DENIED_ERROR_MSG));
			}
			$evaluation_view_ttcash = SystemSettings::first('evaluation_view_ttcash')['evaluation_view_ttcash'];
			$remaining = Auth::guard('api')->user()->remaining_views;
			$existingProfileTransaction = ProfileViewTransactions::where(['user_id' => Auth::guard('api')->user()->id, 'candidate_id' => $user_id, 'for' => 'profile'])->where('created_at', '>=', Carbon::now()->subDays(env('VIEWVALIDITYDAYS'))->toDateTimeString())->first();
			ProfileViewTransactions::create([
				'company_id' => Auth::guard('api')->user()->companyDetails->id,
				'user_id' => Auth::guard('api')->user()->id,
				'type' => 'debit',
				'amount' => $evaluation_view_ttcash,
				'remaining' => $remaining - $evaluation_view_ttcash,
				'by' => Auth::guard('api')->user()->id,
				'candidate_id' => $user_id,
				'for' => 'evaluation',
				'created_at' => $existingProfileTransaction->created_at,
				'updated_at' => $existingProfileTransaction->updated_at,
			]);
			return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::PROFILE_SUCCESS_MSG, []));
		} catch (\Exception $e) {
			// Log::error('Company.buyEvaluation', ['error' => $e, 'candidate_id' => $user_id, 'user' => Auth::guard('api')->user(), 'company' => Auth::guard('api')->user()->company_id]);
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function dashboard(Request $request)
	{
		$get_first_job = Job::getFirstYear();
		$first_year = !empty($get_first_job) ? date("Y", strtotime($get_first_job->updated_at)) : date('Y');

		$data_year = date('Y');
		if ($request->has('year')) {
			$data_year = $request->year;
		}
		$yearStart = SiteHelper::yearStartDate($data_year);

		$yearEnd = SiteHelper::yearEndDate($data_year);

		$company_id = $company_id 	= Auth::guard('api')->user()->company_id;
		$data['first_year'] = $first_year;
		$data['data_year'] = $data_year;
		$backgrounds = ["rgb(255, 99, 132)", "rgb(75, 192, 192)", "rgb(53, 162, 235)", "rgb(255, 99, 132)", "rgb(75, 192, 192)"];

		$data['graph1'] = Job::getCompanyDashboardGraph1($yearStart, $yearEnd, $company_id, $backgrounds);
		$data['graph2'] = Job::getCompanyDashboardGraph2($company_id);
		$data['graph3'] = Job::getCompanyDashboardGraph3($company_id, $backgrounds);
		$data['graph4'] = Job::getCompanyDashboardGraph4($company_id);


		return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::DASHBOARD_DATA_SUCCESS_MSG, $data));
	}

	public function addEditCompany(Request $request)
	{
		try {

			$message = StringConstants::COMPANY_DETAIL_ADDED_SUCCESS_MSG;

			if (isset($request->id) && $request->id != null && $request->id != '') {

				$message = StringConstants::COMPANY_DETAIL_UPDATED_SUCCESS_MSG;
			}
			$company = Companies::addUpdatedata($request);
			$company_id = $company->id;
			if ($company_id != '') {

				CompanyDetail::where('company_id', $company_id)->where('type', 'links')->delete();
				if ($request->has('links') && count($request->links) > 0) {
					foreach ($request->links as $link) {
						$data = [];
						$data['company_id'] = $company_id;
						$data['type'] = 'links';
						$data['title'] = $link['title'];
						$data['value'] = $link['value'];
						CompanyDetail::addData($data);
					}
				}

				return Communicator::returnResponse(ResponseMessages::SUCCESS($message, []));
			} else {
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::SOMETHING_WRONG_MSG, StringConstants::SOMETHING_WRONG_MSG));
			}
		} catch (\Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function companyDetails($company_id)
	{
		try {
			$data = Companies::with('jobs', 'jobs.jobType', 'jobs.company', 'details', 'size', 'industry','gigs','gigs.type', 'gigs.locations', 'gigs.skills','gigs.company','gigs.engagementMode','gigs.user')->find($company_id);
			$recruiters = User::role(['company user','company admin'])->withCount(['jobs' => function($query)
                    {
                        $query->where('status', 'published');
                     
                    },'gigs' => function($query)
                    {
                        $query->where('status', 'published');
                     
                    }])->where('company_id',$company_id)->get();
			if ($data != null) {
				$data->progress = Companies::getCompanyProgress($data);
				if ($data->details) {
					$details = $data->details->groupBy('type');
					$data->links = isset($details['links']) ? $details['links'] : [];
					$data->medias = isset($details['medias']) ? CompanyDetail::getCompanyMediasObject($details['medias']) : [];
					$data->recruiters = isset($recruiters) ? $recruiters : [];
				}
				return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::COMPANY_DETAIL_SUCCESS_MSG, $data));
			} else {
				return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::SOMETHING_WRONG_MSG, StringConstants::SOMETHING_WRONG_MSG));
			}
		} catch (\Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING([], $e));
		}
	}


	public function placeOrder(Request $request)
	{
		try {

			$amount = $request->amount;
			$gst = AppConfig::getGST();

			$gst_amount = ($amount * $gst)/100;

			$amount = ($amount + $gst_amount)  * 100;

			$orderData = [
			    'amount'          => $amount,
			    'currency'        => 'INR'
			];
			$razorpayOrder = $this->razorpay->order->create($orderData);
			$data['key'] = $this->RZPkeys['key'];
			$data['order_id'] = $razorpayOrder->id;
			$data['amount'] = $razorpayOrder->amount;
			$data['currency'] = $razorpayOrder->currency;
			return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::SUCCESSS, $data));
		} catch (\Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function verifyPayment(Request $request)
	{
		try {

			try {
		        $attributes = array(
		            'razorpay_order_id' => $request->razorpay_order_id,
		            'razorpay_payment_id' => $request->razorpay_payment_id,
		            'razorpay_signature' => $request->razorpay_signature
		        );

		        $this->razorpay->utility->verifyPaymentSignature($attributes);
		        return $this->fetchPayment($request->razorpay_payment_id);
		    }
		    catch(SignatureVerificationError $e) {
		        return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		    }
		} catch (\Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}

	public function fetchPayment($payment_id)
	{
		try {

			$payment_details = $this->razorpay->payment->fetch($payment_id);
			$gst = AppConfig::getGST();

			$amount = $payment_details->amount / 100; 
			
			$credit_amount = ($amount * 100) / (100+$gst);

			$gst_amount = $amount - $credit_amount;

	        DB::beginTransaction();
	        $loggedin_user = Auth::guard('api')->user();
			PaymentTransactions::create([
				'company_id' => $loggedin_user->companyDetails->id,
				'user_id' => $loggedin_user->id,
				'amount' => $amount,
				'transaction_id' => $payment_details->id,
				'transaction_details' => json_encode($payment_details->toArray())
			]);
			ProfileViewTransactions::create([
				'company_id' => $loggedin_user->companyDetails->id,
				// 'user_id' => $loggedin_user->id,
				'type' => 'credit',
				'amount' => $credit_amount,
				'remaining' => $loggedin_user->companyDetails->remaining_views + $credit_amount,
				'by' => $loggedin_user->id
			]);
			DB::commit();

			Mail::send(new SendTransactionEmail($payment_details->id, $credit_amount, $gst_amount, $amount, $loggedin_user, AppConfig::getCurrency()));

			return Communicator::returnResponse(ResponseMessages::SUCCESS($credit_amount . StringConstants::ONLINE_PAYMENT_SUCCESS_MSG, null));
		} catch (\Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}


	public function exportTransactions(Request $request){
		$user = Auth::guard('api')->user();
		$user_id = Auth::guard('api')->user()->id;
		$company_id = Auth::guard('api')->user()->company_id;
		$email = Auth::guard('api')->user()->email;

		$start_date = date('Y-m-01 00:00:01');
		$end_date = date('Y-m-d 23:59:59');
		if ($request->has('start_date') && $request->start_date != null && $request->start_date != '') {
			$start_date = date("Y-m-d 00:00:01", strtotime($request->start_date));
		}

		if ($request->has('end_date') && $request->end_date != null && $request->end_date != '') {
			$end_date = date("Y-m-d 23:59:59", strtotime($request->end_date));
		}

		$diff = strtotime($end_date) - strtotime($start_date);

		$days = abs(round($diff / 86400));

		if ($user->isCompanyAdmin()) {
				
			$transactions = Companies::find($company_id)->view_transactions()
					->where('profile_view_transactions.user_id' ,null)
                    ->where('profile_view_transactions.created_at', '>=' ,$start_date)
                    ->where('profile_view_transactions.created_at', '<=' ,$end_date)
                    ->get();
		}else {
			$transactions = User::find($user_id)->view_transactions()
                    ->where('profile_view_transactions.created_at', '>=' ,$start_date)
                    ->where('profile_view_transactions.created_at', '<=' ,$end_date)
                    ->get();
		}
        $result = array();
        foreach($transactions as $record){
           $result[] = array(
              'candidate_id'=>$record->candidate_id ? $record->candidate_id : "N/A",
              'detail' => $record->for,
              'date' => $record->created_at,
              'transaction_by' => $record->transaction_by,
              'Amount' => $record->type == 'credit' ? '+'. $record->amount : '-'. $record->amount,
              'Balance' => '+'.$record->remaining
           );
        }
        if ($days > 30) {
        	
        	Mail::to($email)->send(new SendTransactions($result));
        	return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::TRANSACTIONS_EMAIL_SUCCESS_MSG, null));
        }else{

			Excel::store(new ExportTransactions($result), 'transactions.xlsx','public_uploads');
			$resp['file_url'] = url('/') . '/transactions.xlsx';
			return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::SUCCESSS, $resp));
        }
	}

	public function downloadWorkprofile(Request $request)
	{
		$user_id = $request->id;
		$user = User::with('userWorkProfile', 'userWorkProfile.locationData')->with('userWorkProfileDetail')->find($user_id);
		$user->preferred_location = UserPrefferedData::where('user_id', $user_id)->where('type', 'locations')->pluck('data_id')->toArray();
		$user->preferred_job_types = UserPrefferedData::where('user_id', $user_id)->where('type', 'job_types')->pluck('data_id')->toArray();
		$user->preferred_gig_types = UserPrefferedData::where('user_id', $user_id)->where('type', 'gig_types')->pluck('data_id')->toArray();
		$preferred_location_data = MasterData::getNameFromArray($user->preferred_location)->pluck('name')->toArray();
		$preferred_job_type_data = MasterData::getNameFromArray($user->preferred_job_types)->pluck('name')->toArray();

		$user['preferred_location_data'] = implode(', ', $preferred_location_data);
		$user['preferred_job_type_data'] = implode(', ', $preferred_job_type_data);

		if ($user->userWorkProfile && $user->userWorkProfile->joining_preference_id != null && $user->userWorkProfile->joining_preference_id != '' ) {
			$joining_preference_name = MasterData::getMasterDataName($user->userWorkProfile->joining_preference_id);
			$user->userWorkProfile->joining_preference_name = $joining_preference_name->name;
		}


		if ($user->userWorkProfileDetail) {
			$user->userWorkProfileDetail = $user->userWorkProfileDetail->groupBy('type');
		}
		
		$headings = [];
		$headings['Work Experience'] = isset($user->userWorkProfileDetail['experience']) ? $user->userWorkProfileDetail['experience'] : [];
		$headings['Education'] = isset($user->userWorkProfileDetail['degree']) ? $user->userWorkProfileDetail['degree'] : [];
		$headings['Certifications'] = isset($user->userWorkProfileDetail['certificate']) ? $user->userWorkProfileDetail['certificate'] : [];
		$headings['Awards and Accomplishments'] = isset($user->userWorkProfileDetail['award']) ? $user->userWorkProfileDetail['award'] : [];


		// return view('ttworkprofile',compact('user','headings'));
		$pdf = PDF::loadView('ttworkprofile',compact('user','headings'))->setPaper('a4', 'portrait');
        return $pdf->download('resume.pdf');
	}

	public function getRecruiterDetail($id)
	{
		try {
			$details = User::role(['company user', 'company admin'])->with(['jobs' => function($query)
                    {
                        $query->where('status', 'published');
                     
                    },'jobs.company_details','jobs.locations','gigs' => function($query)
                    {
                        $query->where('status', 'published');
                     
                    },'gigs.type', 'gigs.locations', 'gigs.skills','gigs.company','gigs.engagementMode','gigs.user','companyDetails'])->find($id);

			$data['recruiter_details'] = $details;

			return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::SUCCESSS, $data));
			
		} catch (\Exception $e) {
			return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
		}
	}
}
