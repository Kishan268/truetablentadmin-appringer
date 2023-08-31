<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{

    public function index()
    {
        if(!auth()->user()->can('view_roles_and_permissions')){
            abort(403);
        }
        $roles = Role::whereNotIn('name', ['administrator','candidate','company admin','company user','evaluator'])->with('users')->get();
        $totalPermissions = Permission::count();
        // $ackageModules = ModuleSetting::all()->pluck('module_name')->toArray();
        $modulesData = Permission::all();
        $modulesData = $modulesData->groupBy('module');
        // dd($roles);

        return view('backend.auth.role-permission.index', compact('roles','totalPermissions','modulesData'));
    }

    public function store(Request $request)
    {
        if(!auth()->user()->can('add_roles_and_permissions')){
            abort(403);
        }
        $roleId = $request->roleId;
        $permissionId = $request->permissionId;

        $role = Role::find($roleId);
        $permission = Permission::find($permissionId);

        if ($request->assignPermission == 'yes') {
            $role->givePermissionTo($permission);
        } else {
            $role->revokePermissionTo($permission);
        }

        return ['status' => 'success'];
    }

    public function assignAllPermission(Request $request)
    {
        $roleId = $request->roleId;
        $permissions = Permission::all();

        $role = Role::findOrFail($roleId);
        $role->givePermissionTo($permissions);
        return ['status' => 'success'];
    }

    public function removeAllPermission(Request $request)
    {
        if(!auth()->user()->can('delete_roles_and_permissions')){
            abort(403);
        }
        $roleId = $request->roleId;

        $role = Role::findOrFail($roleId);
        $permissions = Permission::all();
        $role->revokePermissionTo($permissions);

        return ['status' => 'success'];
    }

    public function showMembers(Request $request)
    {
        if(!auth()->user()->can('view_roles_and_permissions')){
            abort(403);
        }
        $roles = Role::where('id',$request->id)->with('users')->first();
        $allUsers = Role::whereNotIn('name', ['administrator','candidate','company admin','company user','evaluator'])->where('id','!=',$request->id)->with('users')->first();

        return view('backend.auth.role-permission.manage-role-members',compact('roles','allUsers'));
    }

    public function storeRole(Request $request)
    {
        if(!auth()->user()->can('add_roles_and_permissions')){
            abort(403);
        }
        $request->validate([
            'name'=>'required|unique:roles'
        ]);
        $roleUser = new Role();
        $roleUser->name = $request->name;

        $roleUser->guard_name = 'web';
        $roleUser->save();
        return Reply::success(__('messages.roleCreated'));
    }

    public function assignRole(Request $request)
    {
        if(!auth()->user()->can('add_roles_and_permissions')){
            abort(403);
        }
        $employeeRole = Role::where('id', $request->role_id)->first();
      
        foreach ($request->user_id as $user) {
            $user = User::where('id', $user)->first();
            if (isset($user->roles)) {
                foreach ($user->roles as $key => $value) {
                    $user->removeRole($value->name);
                }
            }
            $user->assignRole($employeeRole->name);

        }
        return 'success';
    }

    public function deleteRoleMembers(Request $request)
    {
        if(!auth()->user()->can('delete_roles_and_permissions')){
            abort(403);
        }
        $user = User::where('id', $request->roleMemberId)->first();
        foreach ($user->roles as $key => $value) {
            $user->removeRole($value->name);
        }
        return 'success';
    }

    public function detachRole(Request $request)
    {
        $user = User::findOrFail($request->userId);
        $user->detachRole($request->roleId);
        return Reply::dataOnly(['status' => 'success']);
    }

    public function deleteRole(Request $request)
    {
        if(!auth()->user()->can('delete_roles_and_permissions')){
            abort(403);
        }
        $roleUsers = Role::whereId($request->roleId)->with('users')->first();
        if (count($roleUsers->users) == 0) {
            Role::whereId($request->roleId)->delete();
            return 'success';
        }else{
            return 'error';
        }
        // return Reply::dataOnly(['status' => 'success']);
    }

    public function create()
    {
         if(!auth()->user()->can('add_roles_and_permissions')){
            abort(403);
        }
        $roles = Role::all();
        return view('backend.auth.role-permission.create', compact('roles'));
    }

    public function update(UpdateRole $request, $id)
    {
        $roleUser = Role::findOrFail($id);
        $roleUser->name = $request->value;
        $roleUser->display_name = ucwords($request->value);
        $roleUser->save();

        return Reply::successWithData(__('messages.roleUpdated'), ['display_name' => $roleUser->display_name]);
    }
}
