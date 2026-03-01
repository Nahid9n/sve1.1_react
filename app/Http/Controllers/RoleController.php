<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->where('id', '!=', 1)->get()->except(8);

        return view('backEnd.admin.role.index', compact('roles'));
    }

    public function create()
    {
        // Get all permissions
        $permissions = Permission::all();

        // Group by first segment of dot notation
        $custom_permission = $permissions->groupBy(function ($perm) {
            return explode('.', $perm->name)[0];
        });

        return view('backEnd.admin.role.create', compact('custom_permission'));
    }

    public function store(Request $request)
    {
        // 1️⃣ Validate input
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array|min:1',
        ]);

        // 2️⃣ Create Role
        $role = Role::firstOrCreate(
            ['name' => $request->name],
            ['guard_name' => 'admin']
        );

        // 3️⃣ Assign selected permissions
        $permissions = $request->input('permissions', []);
        if (! empty($permissions)) {
            $role->syncPermissions($permissions); // sync prevents duplicates
        }

        // 4️⃣ Return with success message
        return redirect()->route('admin.role.index')->with('success', 'Role created successfully.');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $custom_permission = $permissions->groupBy(function ($perm) {
            return explode('.', $perm->name)[0];
        });
        // dd($custom_permission);
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('backEnd.admin.role.edit', compact('custom_permission', 'role', 'rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        // 1️⃣ Validate input
        $request->validate([
            'name' => 'required|unique:roles,name,'.$id, // ignore current role
            'permissions' => 'required|array|min:1',
        ]);

        // 2️⃣ Find the role
        $role = Role::findOrFail($id);

        // 3️⃣ Update role name
        $role->update([
            'name' => $request->name,
        ]);

        // 4️⃣ Sync permissions (assign only selected permissions)
        $permissions = $request->input('permissions', []);
        $role->syncPermissions($permissions);

        // 5️⃣ Return success
        return redirect()->route('admin.role.index')->with('success', 'Role updated successfully.');
    }

    public function delete($id)
    {
        $role = Role::findOrFail($id);
        $role->permissions()->detach();
        $role->delete();

        return back()->with('success', 'Role deleted successfully.');
    }
}
