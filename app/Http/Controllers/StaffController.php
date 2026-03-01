<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Theme;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        // dd(activeTheme());
        $roles = Role::with('permissions')->get()->except(1);
        $staffs = Admin::with('roles')->orderBy('role_id', 'asc')->get()->except(1);
        $themes = Theme::pluck('path', 'id');
        // dd($themes);
        return view('backEnd.admin.staffs.index', compact('staffs', 'roles', 'themes'));
    }

    public function store(Request $request)
    {
        $role = Role::findOrFail($request->role_id);
        $input = array_merge($request->all(), [
            'is_order_assign' => $request->is_order_assign ?? 0,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'role' => $role->name,
        ]);

        $staff = Admin::create($input);
        $staff->assignRole($role->name);

        return back()->with('success', 'Staff Created Successfully');
    }

    public function update(Request $request)
    {
        //     if ($request->has('is_order_assign')) {
        //         $is_order_assign = 1;
        //     } else {
        //         $is_order_assign = 0;
        //     }
        //    dd($request->all());

        $staff = Admin::find($request->id);
        $role = Role::findOrFail($request->role_id);
        $input = array_merge($request->all(), [
            'is_order_assign' => $request->is_order_assign ?? 0,
            'role_id' => $request->role_id,
            'role' => $role->name,
        ]);
        // dd($input);
        $staff->update($input);
        $staff->syncRoles($role->name);

        return back()->with('success', 'Staff Updated Successfully');
    }

    public function status($id, $status)
    {
        Admin::find($id)->update([
            'status' => $status,
        ]);

        return back()->with('success', 'Staff Status Updated Successfully');
    }

    public function delete($id)
    {
        $admin = Admin::find($id);
        $admin->removeRole($admin->role);
        $admin->delete();

        return back()->with('success', 'Staff Deleted Successfully');
    }
}
