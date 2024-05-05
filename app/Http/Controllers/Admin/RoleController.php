<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Exports\PermissionExport;
use App\Imports\PermissionImport;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;


class RoleController extends Controller
{
    public function allPermission()
    {
        $permissions = Permission::all();
        return view('admin.pages.permission.all_permission', compact('permissions'));

    } //End method

    public function addPermission()
    {
        return view('admin.pages.permission.add_permission');

    } //End method

    public function storePermission(Request $request)
    {
        $permission = Permission::create([
            'name'       => $request->name,
            'group_name' => $request->group_name,
        ]);

        $notification = array(
            'message'    =>'Permission Created Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->route('all.permission')->with($notification);
    } //End method

    public function editPermission($id)
    {
        $permission = Permission::findOrFail($id);
        return view('admin.pages.permission.edit_permission', compact('permission'));

    } //End method

    public function updatePermission(Request $request)
    {

        $id = $request->id;

         Permission::findOrFail($id)->update([
            'name'       => $request->name,
            'group_name' => $request->group_name,
        ]);

        $notification = array(
            'message'    =>'Permission Updated Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->route('all.permission')->with($notification);

    } //End method


    public function deletePermission($id)
    {

        Permission::findOrFail($id)->delete();

        $notification = array(
            'message'    =>'Permission Deleted Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->back()->with($notification);

    } //End method

    public function importPermission()
    {
        return view('admin.pages.permission.import_permission');

    } //End method

    public function export()
    {
        return Excel::download(new PermissionExport, 'permission.xlsx');
    } //End method

    public function import(Request $request)
    {

        Excel::import(new PermissionImport, $request->file('import_file'));

        $notification = array(
            'message'    =>'Permission Imported Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->back()->with($notification);

    } //End method


    ///////////// Roles All Methods ////////////////////////
    public function allRoles()
    {
        $roles= Role::all();
        return view('admin.pages.roles.all_roles', compact('roles'));
    } //End method

    public function addRoles()
    {
        return view('admin.pages.roles.add_roles');

    } //End method

    public function storeRoles(Request $request)
    {

        Role::create([
            'name'       => $request->name,
        ]);

        $notification = array(
            'message'    =>'Role Created Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->route('all.roles')->with($notification);

    } //End method


    public function editRoles($id)
    {
        $roles = Role::findOrFail($id);
        return view('admin.pages.roles.edit_roles', compact('roles'));

    } //End method


    public function updateRoles(Request $request)
    {
        $id = $request->id;

        Role::findOrFail($id)->update([
            'name'       => $request->name,
        ]);

        $notification = array(
            'message'    =>'Role Updated Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->route('all.roles')->with($notification);

    } //End method

    public function deleteRoles($id)
    {
        Role::findOrFail($id)->delete();

        $notification = array(
            'message'    =>'Role Deleted Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->back()->with($notification);

    } //End method

    ///////// Add Permission in Role ////////////

    public function addRolesPermission()
    {
        $roles  = Role::all();
        $permissions = Permission::all();
        $permission_groups = User::getPermissionGroups();

        $data = [
            'roles'       =>$roles,
            'permissions' =>$permissions,
            'permission_groups' =>$permission_groups
        ];

        return view('admin.pages.rolesetup.add_roles_permission', $data);

    } //End method


    public function storeRolesPermission(Request $request)
    {
        $data = array();
        $permissions  = $request->permission; 

        foreach($permissions as $key=> $item){
            $data['role_id'] = $request->role_id;
            $data['permission_id'] = $item;

            DB::table('role_has_permissions')->insert($data);
        } //end foreach

        $notification = array(
            'message'    =>'Permission Added For Role Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->route('all.role.permission')->with($notification);

    } //End method


    public function allRolesPermission()
    {
        $roles = Role::all();
        return view('admin.pages.rolesetup.all_roles_permission', compact('roles'));

    } //End method

    public function editRolesPermission($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $permission_groups = User::getPermissionGroups();

        $data = [
            'role'       =>$role,
            'permissions' =>$permissions,
            'permission_groups' =>$permission_groups
        ];

        return view('admin.pages.rolesetup.edit_roles_permission', $data);


    } //End method

    public function updateRolesPermission(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $permissions = $request->permission;

        // Convert string IDs to integers
        $permissionIdsAsIntegers = array_map('intval', $permissions);

        if(!empty($permissionIdsAsIntegers)){
            $role->syncPermissions($permissionIdsAsIntegers);
        }

        $notification = array(
            'message'    =>'Permission Updated For Role Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->route('all.role.permission')->with($notification);

    } //End method

    public function deleteRolesPermission($id)
    {
        $role = Role::findOrFail($id);

        if(!is_null($role)){
            $role->delete();
        }
        $notification = array(
            'message'    =>'Permission Deleted For Role Successfully!',
            'alert-type' => 'success'
        );

       
        return redirect()->back()->with($notification);

    } //End method
} 
