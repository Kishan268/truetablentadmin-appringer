<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\User\UpdateProfileRequest;
use App\Repositories\Frontend\Auth\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Auth\UserDetails;

/**
 * Class ProfileController.
 */
class ProfileController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * ProfileController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UpdateProfileRequest $request
     *
     * @throws \App\Exceptions\GeneralException
     * @return mixed
     */
    public function update(UpdateProfileRequest $request)
    {
        $output = $this->userRepository->update(
            $request->user()->id,
            $request->only('first_name', 'last_name', 'email', 'avatar_type', 'avatar_location'),
            $request->has('avatar_location') ? $request->file('avatar_location') : false
        );

        // E-mail address was updated, user has to reconfirm
        if (is_array($output) && $output['email_changed']) {
            auth()->logout();

            return redirect()->route('frontend.auth.login')->withFlashInfo(__('strings.frontend.user.email_changed_notice'));
        }

        return redirect()->route('frontend.user.account')->withFlashSuccess(__('strings.frontend.user.profile_updated'));
    }

    public function updatePrefs(Request $request){
        try{
            $user_id = Auth()->user()->id;
            $telecommute = false;
            $notification_new_jobs = false;
            $notification_profile_viewed = false;

            if($request->has('telecommute') && $request->get('telecommute') == 'on'){
                $telecommute = true;
            }

            if($request->has('notification_new_jobs') && $request->get('notification_new_jobs') == 'on'){
                $notification_new_jobs = true;
            }

            if($request->has('notification_profile_viewed') && $request->get('notification_profile_viewed') == 'on'){
                $notification_profile_viewed = true;
            }

            $data = $request->except(['_token', '_method', 'telecommute']);
            $data['telecommute'] = $telecommute;
            $data['notification_new_jobs'] = $notification_new_jobs;
            $data['notification_profile_viewed'] = $notification_profile_viewed;
            $data['job_type'] = implode('|', $data['job_type']);

            if(UserDetails::where('user_id', $user_id)->count() == 0){
                $data['user_id'] = $user_id;
                UserDetails::create($data);
            }else{
                UserDetails::where('user_id', $user_id)->update($data);
            }
            return redirect()->route('frontend.user.account')->withFlashSuccess('Your Preferences have been saved!');
        }catch(\Exception $e){
            Log::error('User.savePreferences', ['data' => $request->all(), 'user' => Auth()->user()]);
            return redirect()->back()->withFlashError('Preferences cannot be saved, please try-again or contact support!');
        }
    }
}
