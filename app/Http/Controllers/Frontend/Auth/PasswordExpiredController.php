<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\User\UpdatePasswordRequest;
use App\Repositories\Frontend\Auth\UserRepository;

/**
 * Class PasswordExpiredController.
 */
class PasswordExpiredController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function expired()
    {
        abort_unless(config('access.users.password_expires_days'), 404);

        return view('frontend.auth.passwords.expired');
        // return view('frontend.user.account.tabs.change-password');
    }

    /**
     * @param UpdatePasswordRequest $request
     * @param UserRepository        $userRepository
     *
     * @throws \App\Exceptions\GeneralException
     * @return mixed
     */
    public function update(UpdatePasswordRequest $request, UserRepository $userRepository)
    {
        abort_unless(config('access.users.password_expires_days'), 404);

        $userRepository->updatePassword($request->only('old_password', 'password'), true);
        return redirect()->route('admin.dashboard')->withFlashSuccess(__('strings.frontend.user.password_updated'));

        return redirect()->route('frontend.user.account')
            ->withFlashSuccess(__('strings.frontend.user.password_updated'));
    }
}