<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\User\UpdatePasswordRequest;
use App\Repositories\Frontend\Auth\UserRepository;
use Illuminate\Support\Facades\Hash;
use App\Models\Auth\User;
use Auth;
/**
 * Class UpdatePasswordController.
 */
class UpdatePasswordController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * ChangePasswordController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UpdatePasswordRequest $request
     *
     * @throws \App\Exceptions\GeneralException
     * @return mixed
     */
    public function update(UpdatePasswordRequest $request)
    {
       $user = Auth::user();
        $request->validate([
            'old_password' => [
                            'required', function ($attribute, $value, $fail) {
                                if (!Hash::check($value, Auth::user()->password)) {
                                    $fail('Old Password didn\'t match');
                                }
                            },
                        ],
            'new_password' => 'required|string|min:6|same:password_confirmation|different:old_password',
            'password_confirmation' => 'required',
        ]);
       // dd($user);

         if (!Hash::check($request->get('old_password'), $user->password)) {
            // The passwords matches
            return redirect()->back()->with("old_password", "Your current password does not match with the password you provided. Please try again.");
        }
        if (strcmp($request->get('old_password'), $request->get('new_password')) == 0) {
            //Current password and new password are same
            return redirect()->back()->with("new_password", "New Password cannot be same as your current password. Please choose a different password.");
        }



        //Change Password
        $user->password = bcrypt($request->get('new_password'));
        $user->save();
        //Update password updation timestamp

        return redirect()->route('admin.dashboard')->withFlashSuccess(__('strings.frontend.user.password_updated'));
        dd($request->only('old_password', 'password'));
        $this->userRepository->updatePassword($request->only('old_password', 'password'));
    }
}