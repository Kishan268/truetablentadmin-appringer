<?php

namespace App\Http\Controllers\Backend\Auth\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Auth\User\ManageUserRequest;
use App\Models\Auth\User;
use App\Repositories\Backend\Auth\UserRepository;

/**
 * Class UserStatusController.
 */
class UserStatusController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function getDeactivated(ManageUserRequest $request)
    {
        return view('backend.auth.user.deactivated')
            ->withUsers($this->userRepository->getInactivePaginated(10, 'id', 'asc'));
    }

    /**
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function getDeleted(ManageUserRequest $request)
    {
        $type = '';
        if($request->has('type') && $request->input('type') != ''){
            $type = $request->input('type');
        }
        return view('backend.auth.user.deleted',compact('type'))
            ->withUsers($this->userRepository->getDeletedPaginated(10, 'id', 'asc'));
    }

    /**
     * @param ManageUserRequest $request
     * @param User              $user
     * @param                   $status
     *
     * @throws \App\Exceptions\GeneralException
     * @return mixed
     */
    public function mark(ManageUserRequest $request, User $user, $status)
    {
        $this->userRepository->mark($user, (int) $status);

        return redirect()->route(
            (int) $status === 1 ?
            'admin.auth.user.index' :
            'admin.auth.user.deactivated'
        )->withFlashSuccess(__('alerts.backend.users.updated'));
    }

    /**
     * @param ManageUserRequest $request
     * @param User              $deletedUser
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     * @return mixed
     */
    public function delete(ManageUserRequest $request, User $deletedUser)
    {
        $deletedUser->update([
            'email' => $deletedUser->email.'-deleted'
        ]);
        // $this->userRepository->forceDelete($deletedUser);
        $type = '';
        $status = '';
        if($request->has('status') && $request->input('status') != ''){
            $status = $request->input('status');
        }

        if($request->has('type') && $request->input('type') != ''){
            $type = $request->input('type');
        }

        return redirect()->route('admin.auth.user.index',['type' => $type,'status' => 'Deactivated'])->withFlashSuccess(__('alerts.backend.users.deleted_permanently'));
    }

    /**
     * @param ManageUserRequest $request
     * @param User              $deletedUser
     *
     * @throws \App\Exceptions\GeneralException
     * @return mixed
     */
    public function restore(ManageUserRequest $request, User $deletedUser)
    {
        $this->userRepository->restore($deletedUser);
        $type = '';
        if($request->has('type') && $request->input('type') != ''){
            $type = $request->input('type');
        }
        return redirect()->route('admin.auth.user.index',['type' => $type])->withFlashSuccess(__('alerts.backend.users.restored'));
    }
}
