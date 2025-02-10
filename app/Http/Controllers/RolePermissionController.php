<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    public function createRole(Request $request)
    {
        $role = Role::create(['name' => $request->name]);
        return response()->json(['message' => 'Role created successfully', 'role' => $role]);
    }

    public function assignRoleToUser(Request $request)
    {
        $user = User::find($request->user_id);
        $user->assignRole($request->role);
        return response()->json(['message' => 'Role assigned successfully']);
    }

    public function createPermission(Request $request)
    {
        $permission = Permission::create(['name' => $request->name]);
        return response()->json(['message' => 'Permission created successfully', 'permission' => $permission]);
    }

    public function assignPermissionToRole(Request $request)
    {
        $role = Role::findByName($request->role);
        $role->givePermissionTo($request->permission);
        return response()->json(['message' => 'Permission assigned to role successfully']);
    }
}
