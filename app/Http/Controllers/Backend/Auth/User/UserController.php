<?php

namespace App\Http\Controllers\Backend\Auth\User;

use App\Events\Backend\Auth\User\UserDeleted;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Auth\User\ManageUserRequest;
use App\Http\Requests\Backend\Auth\User\StoreUserRequest;
use App\Http\Requests\Backend\Auth\User\UpdateUserRequest;
use App\Http\Requests\Backend\Auth\User\BulkUserUploadRequest;
use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Repositories\Backend\Auth\PermissionRepository;
use App\Repositories\Backend\Auth\RoleRepository;
use App\Repositories\Backend\Auth\UserRepository;
use Illuminate\Http\Request;

use App\Models\UserWorkProfile;
use App\Models\UserWorkProfileDetail;
use App\Models\WorkProfileDetails;
use App\Models\Companies;
use App\Imports\UsersImport;
use ZipArchive;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportUsers;
use Response;

/**
 * Class UserController.
 */
class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * UserController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param ManageUserRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ManageUserRequest $request)
    {
        if (!auth()->user()->can('view_user')) {
            abort(403);
        }
        $type = '';
        $status = '';
        $page = 1;
        if ($request->has('status') && $request->input('status') != '') {
            $status = $request->input('status');
        }

        if ($request->has('type') && $request->input('type') != '') {
            $type = $request->input('type');
        } else {
            return redirect()->route('admin.auth.user.index', ['type' => 'candidate']);
        }

        if ($request->has('page') && $request->input('page') != '') {
            $page = $request->input('page');
        }
        return view('backend.auth.user.index', compact('type', 'page', 'status'));
    }

    public function userList(ManageUserRequest $request)
    {
        if (!auth()->user()->can('view_user')) {
            abort(403);
        }
        // dd(date('Y-m-d H:i:s', strtotime($request->to_date)) );
        $from_date = '';
        $to_date = '';
        $type = '';
        $status = '';
        if ($request->has('type') && $request->input('type') != '') {
            $type = $request->input('type');
            if ($type === 'cmp-admin') {
                $type = 'company admin';
            } elseif ($type === 'cmp-user') {
                $type = 'company user';
            }

            if ($type === 'support') {
                $query = User::role(['support'])->select('*');
            } else {
                $query = User::role($type)->select('*');
            }
        } else {
            $query = User::select('*');
        }
        if ($request->has('status') && $request->status == 'Deactivated') {
            $query->onlyTrashed();
            $query->where('email', 'not like', "deleted-%")
                ->where('email', 'not like', "%-deleted");
        }
        if ($request->has('q') && $request->input('q') != '') {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%')
                    ->orWhere('id', $search);
            });
        }
        if ($request->from_date && $request->to_date) {
            $from_date = date('Y-m-d 00:00:01', strtotime($request->from_date));
            $to_date = date('Y-m-d 23:59:59', strtotime($request->to_date));
            $query->whereBetween('created_at', [$from_date, $to_date]);
        }

        if ($request->has('column') && $request->input('column') != '' && $request->has('sort') && $request->input('sort') != '') {
            $query->orderBy($request->input('column'), $request->input('sort'));
        } else {
            $query->orderBy('updated_at', 'desc');
        }

        $users = $query->paginate(10);
        $table = view('backend.auth.user.user-table', compact('users', 'type'))->render();
        $pagination = view('backend.auth.user.user-table-pagination')->withUsers($users->appends($request->except('page')))->render();
        return response()->json([
            'status' => true,
            'table' => $table,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @param ManageUserRequest    $request
     * @param RoleRepository       $roleRepository
     * @param PermissionRepository $permissionRepository
     *
     * @return mixed
     */
    public function create(ManageUserRequest $request, RoleRepository $roleRepository, PermissionRepository $permissionRepository)
    {
        if (!auth()->user()->can('add_user')) {
            abort(403);
        }
        $type = '';
        $company_id = '';
        if ($request->has('type') && $request->input('type') != '') {
            $type = $request->input('type');
        } else {
            return redirect()->route('admin.auth.user.create', ['type' => 'candidate']);
        }
        if ($request->has('company_id') && $request->input('company_id') != '') {
            $company_id = $request->input('company_id');
        }

        $companies = Companies::all();
        return view('backend.auth.user.create')
            ->withRoles($roleRepository->with('permissions')->get(['id', 'name']))
            ->withPermissions($permissionRepository->get(['id', 'name']))
            ->withType($type)
            ->withCompanyId($company_id)
            ->withCompanies($companies);
    }

    /**
     * @param StoreUserRequest $request
     *
     * @throws \Throwable
     * @return mixed
     */
    public function store(StoreUserRequest $request)
    {
        if (!auth()->user()->can('add_user')) {
            abort(403);
        }
        $this->userRepository->create($request->only(
            'first_name',
            'last_name',
            'email',
            'password',
            'active',
            'confirmed',
            'confirmation_email',
            'roles',
            'permissions',
            'company_id'
        ));

        return redirect()->route('admin.auth.user.index', ['type' => $request->roles[0]])->withFlashSuccess(__('alerts.backend.users.created'));
    }

    /**
     * @param ManageUserRequest $request
     * @param User              $user
     *
     * @return mixed
     */
    public function show(ManageUserRequest $request, User $user)
    {
        if (!auth()->user()->can('view_user')) {
            abort(403);
        }
        return view('backend.auth.user.show')
            ->withUser($user);
    }

    /**
     * @param ManageUserRequest    $request
     * @param RoleRepository       $roleRepository
     * @param PermissionRepository $permissionRepository
     * @param User                 $user
     *
     * @return mixed
     */
    public function edit(ManageUserRequest $request, RoleRepository $roleRepository, PermissionRepository $permissionRepository, User $user)
    {
        if (!auth()->user()->can('update_user')) {
            abort(403);
        }
        return view('backend.auth.user.edit')
            ->withUser($user)
            ->withRoles($roleRepository->get())
            ->withUserRoles($user->roles->pluck('name')->all())
            ->withPermissions($permissionRepository->get(['id', 'name']))
            ->withUserPermissions($user->permissions->pluck('name')->all());
    }

    /**
     * @param ManageUserRequest    $request
     * @param RoleRepository       $roleRepository
     * @param PermissionRepository $permissionRepository
     * @param User                 $user
     *
     * @return mixed
     */
    public function rate(ManageUserRequest $request, RoleRepository $roleRepository, PermissionRepository $permissionRepository, User $user)
    {
        return view('backend.auth.user.rate')
            ->withUser($user)
            ->withWP(UserWorkProfileDetail::select('user_work_profile_details.*', 'master_data.name AS skill_name')->leftJoin('master_data', 'master_data.id', 'user_work_profile_details.skill_id')->leftJoin('user_work_profiles', 'user_work_profiles.id', 'user_work_profile_details.user_work_profile_id')->where('user_work_profile_details.type', 'skill')->where('user_work_profiles.user_id', $user->id)->get())
            ->withFeedback(UserWorkProfile::where('user_id', $user->id)->first())
            ->withRoles($roleRepository->get())
            ->withUserRoles($user->roles->pluck('name')->all())
            ->withPermissions($permissionRepository->get(['id', 'name']))
            ->withUserPermissions($user->permissions->pluck('name')->all());
    }

    /**
     * @param UpdateUserRequest $request
     * @param User              $user
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     * @return mixed
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if (!auth()->user()->can('update_user')) {
            abort(403);
        }
        $this->userRepository->update($user, $request->only(
            'first_name',
            'last_name',
            'email',
            'roles',
            'permissions'
        ));

        return redirect()->route('admin.auth.user.index')->withFlashSuccess(__('alerts.backend.users.updated'));
    }

    public function saveEvaluation(Request $request, User $user)
    {
        if ($request->has('ratings') && $request->has('feedback')) {
            UserWorkProfile::where('user_id', $user->id)->update(['evaluation_feedback' => $request->get('feedback')]);
            foreach ($request->get('ratings') as $id => $rating) {
                UserWorkProfileDetail::whereId($id)->update(['remarks' => $rating]);
            }
        }

        return redirect()->route('admin.auth.user.index')->withFlashSuccess(__('alerts.backend.users.candidate_evaluated'));
    }

    /**
     * @param ManageUserRequest $request
     * @param User              $user
     *
     * @throws \Exception
     view backend* @return mixed
     */
    public function destroy(ManageUserRequest $request, User $user)
    {
        if (!auth()->user()->can('delete_user')) {
            abort(403);
        }
        $this->userRepository->deleteById($user->id);

        event(new UserDeleted($user));

        return redirect()->back()->withFlashSuccess(__('alerts.backend.users.deactivated'));
    }

    public function import(ManageUserRequest $request, RoleRepository $roleRepository, PermissionRepository $permissionRepository)
    {
        return view('backend.auth.user.import')
            ->withRoles($roleRepository->with('permissions')->get(['id', 'name']))
            ->withPermissions($permissionRepository->get(['id', 'name']));
    }

    public function importFile(BulkUserUploadRequest $request)
    {
        $zip = new ZipArchive();
        if ($request->has("zip")) {
            $status = $zip->open($request->file("zip")->getRealPath());

            if ($status !== true) {
                return redirect()->back()->withError('Error Occured at zip file: ' . $status);
            } else {
                $storageDestinationPath = public_path("resumes/");

                if (!\File::exists($storageDestinationPath)) {
                    \File::makeDirectory($storageDestinationPath, 0755, true);
                }
                $zip->extractTo($storageDestinationPath);
                $zip->close();
            }
        }

        $import  = new UsersImport();
        \Excel::import($import, request()->file('file'));
        $response = $import->getResponse();
        return view('backend.auth.user.import', compact('response'));
    }

    public function cleanResumesDirectory(Request $request)
    {
        try {
            File::cleanDirectory(public_path("resumes/Processed/"));
            return redirect()->back()->withFlashSuccess('Resumes delete successfully');
        } catch (Exception $e) {
            return redirect()->back()->withError('Error Occured: ' . $e->getMessage());
        }
    }

    public function exportUsers(Request $request)
    {
        $from_date = date('Y-m-d 00:00:01', strtotime($request->from_date));
        $to_date = date('Y-m-d 23:59:59', strtotime($request->to_date));
        if ($request->has('status') && $request->status == 'Deactivated') {
            $users = User::role($request->type)->select('users.id', 'users.updated_at', 'users.avatar_location', 'users.first_name', 'users.last_name', 'users.email', 'users.created_at', 'users.added_from', 'user_work_profiles.contact_number', 'user_work_profiles.total_experience', 'user_work_profiles.location_name')
                ->leftJoin('user_work_profiles', 'user_work_profiles.user_id', '=', 'users.id')
                ->whereBetween('users.created_at', [$from_date, $to_date])
                ->onlyTrashed()
                ->get();
        } else {
            $users = User::role($request->type)->select('users.id', 'users.updated_at', 'users.avatar_location', 'users.first_name', 'users.last_name', 'users.email', 'users.created_at', 'users.added_from', 'user_work_profiles.contact_number', 'user_work_profiles.total_experience', 'user_work_profiles.location_name')
                ->leftJoin('user_work_profiles', 'user_work_profiles.user_id', '=', 'users.id')
                ->whereBetween('users.created_at', [$from_date, $to_date])
                ->get();
        }
        $result = array();
        foreach ($users as $key => $user) {
            $result[] = array(
                'name'                    => $user->full_name,
                'email'                   => $user->email,
                'phone'                   => $user->contact_number,
                'overall_experience'      => $user->total_experience,
                'date_of_registration'    => date("d/m/Y", strtotime($user->created_at)),
                'location'                => $user->location_name,
                'tt_profile_completion'   => User::getUserProfileProgress($user),
                'added_from'              => $user->added_from,
                'updated_at'              => $user->updated_at->diffForHumans(),
            );
        }
        $export = new ExportUsers($result);
        return Excel::download($export, 'users' . $from_date . '.xlsx');
    }
}
