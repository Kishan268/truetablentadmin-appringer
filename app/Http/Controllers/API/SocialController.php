<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use App\Communicator\Communicator;
use App\Constants\ResponseMessages;
use App\Constants\StringConstants;
use App\Models\Auth\User;
use App\Models\UserPrefferedData;
use App\Models\Chat;
use App\Models\MasterData;
use App\Models\Companies;

class SocialController extends Controller
{
    public function redirectToAuth(): JsonResponse
    {

        try {
            $data['url'] = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();

            return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::REGISTER_SUCCESS_MSG, $data));
        } catch (Exception $e) {

            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }

        
    }

    public function handleAuthCallback(): JsonResponse
    {
        try {
            $socialiteUser = Socialite::driver('google')->stateless()->user();
        } catch (ClientException $e) {
            $error_msg = '';
            return Communicator::returnResponse(ResponseMessages::ERROR($error_msg, $error_msg));
        }

        $name = $socialiteUser->getName();
        $email = $socialiteUser->getEmail();

        $user_deactivated = User::withTrashed()->where('email',$email)->where('deleted_at','!=',null)->first();
        if($user_deactivated){
            return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::ACCOUNT_DEACTIVATE_ERROR_MSG, StringConstants::ACCOUNT_DEACTIVATE_ERROR_MSG));
        }
        $name_parts = explode(" ", $name);

        $first_name = $name_parts[0];
        $last_name = $last_name = array_slice($name_parts, -1)[0];

        $is_user_exists = true;
        $user = User::query()
            ->firstOrCreate(
                [
                    'email' => $socialiteUser->getEmail(),
                ],
                [
                    'email_verified_at' => now(),
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'provider_id' => $socialiteUser->getId(),
                    'provider_name' => 'google',
                    'password' => '',
                    // 'provider_json' => $socialiteUser,
                ]
            );

        if($user->wasRecentlyCreated)
        {
            $user->assignRole(config('access.users.candidate_role'));
            $is_user_exists = false;
        }

        if ($user->company_id == null || Companies::isCompanyActive($user->company_id)) {
            $data = User::getUserByEmail($user->email);
            $data['profile'] = User::with('userWorkProfile')->with('userWorkProfileDetail')->find($data->id);
            $user_id = $data->id;
            $data['is_profile_completed'] = true;
            $data['is_mobile_verified'] = true;
            if ($data['profile']->contact == null || $data['profile']->contact == '') {
                $data['is_profile_completed'] = false;
            }elseif($data['profile']->is_mobile_verified != '1'){

                $data['is_mobile_verified'] = false;
            }
            if ($is_user_exists && $data['is_profile_completed'] && $data['is_mobile_verified']) {
                
                $user->tokens->each(function ($token, $key) {
                    $token->delete();
                });
                $token = $user->createToken(env('APP_NAME'))->accessToken;
                
                if ($data->isAdmin()) {
                    return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::ACCESS_DENIED_ERROR_MSG, StringConstants::EMAIL_VERIFY_ERROR_MSG));
                }
                $data['token'] = $token;
            }
            
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
            $data['is_user_exists'] = $is_user_exists;
            
            return Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::LOGIN_SUCCESS_MSG, $data));
        } else {
            return Communicator::returnResponse(ResponseMessages::ERROR(StringConstants::COMPANY_DEACTIVATE_ERROR_MSG, StringConstants::COMPANY_DEACTIVATE_ERROR_MSG));
        }
    }

}
