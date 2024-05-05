<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
    public function adminDashboard()
    {
        return view('admin.index');
    } //End Method


    public function adminLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message'    =>'Admin Logout Successfully',
            'alert-type' => 'success'
        );

        return redirect('/admin/login')->with($notification);
    } //End Method

    public function adminLogin()
    {
        return view('admin.admin_login');
    } //End Method


    public function adminProfile()
    {
        $id = Auth::user()->id;
        $profileData = User::find($id);

        return view('admin.admin_profile_view', compact('profileData')); 
    } //End Method

    public function adminProfileStore(Request $request)
    {
        $id = Auth::user()->id;
        $data = User::find($id);

        $data->username = $request->username;
        $data->name     = $request->name;
        $data->email    = $request->email;
        $data->phone    = $request->phone;
        $data->address  = $request->address;

        if($request->file('photo')){
            $file = $request->file('photo');
            @unlink(public_path('upload/admin/'.$data->photo));
            $fileName = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin'),$fileName);
            $data['photo'] = $fileName;
        }

        $data->save();

        $notification = array(
            'message'    =>'Admin Profile Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } //End Method

    public function adminChangePassword()
    {
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('admin.admin_change_password', compact('profileData'));

    } //End Method


    public function adminUpdatePassword(Request $request)
    {
        // Validation
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed'
        ]);

        //Match the old password

        if(!Hash::check($request->old_password, auth()->user()->password)){
  
        $notification = array(
            'message'    =>'Admin Old Password Does Not Match!',
            'alert-type' => 'error'
        );

         return back()->with($notification);

        }

        // Update The New Password

        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        $notification = array(
            'message'    =>'Admin  Password Change Successfully!',
            'alert-type' => 'success'
        );

         return back()->with($notification);

    } //End Method


    /////// All Agent user method /////////

    public function allAgent()
    {
        $allAgent  =User::where('role', 'agent')->get();
        return view('admin.agent_user.all_agent', compact('allAgent'));

    }  //End method

    public function addAgent()
    {
        return view('admin.agent_user.add_agent');
    } //End Method


    public function storeAgent(Request $request)
    {
        User::insert([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->address),
            'role' => 'agent',
            'status' => 'active',

        ]);

        $notification = array(
            'message'    =>'Agent  Created Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->route('all.agent')->with($notification);
    } //End method

    public function editAgent($id)
    {
        $allAgent = User::findOrFail($id);
        return view('admin.agent_user.edit_agent', compact('allAgent'));

    } //End method

    public function updateAgent(Request $request)
    {

        $user_id = $request->id;

        User::findOrFail($user_id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,

        ]);

        $notification = array(
            'message'    =>'Agent  Updated Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->route('all.agent')->with($notification);
    } //End method

    public function deleteAgent($id)
    {
        User::findOrFail($id)->delete();

        $notification = array(
            'message'    =>'Agent  Deleted Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->back()->with($notification);

    } //End method

    public function changeStatus(Request $request)
    {
        $user = User::find($request->user_id);
        $user->status = $request->status; 
        $user->save();

        return response()->json(['success'=>'status change successfully']);
    } //End method


    ////// Admin User all Method ////////////////
    public function allAdmin()
    {
        $allAdmin  = User::where('role', 'admin')->get();
        return view('admin.pages.admin.all_admin', compact('allAdmin'));
    } //End method

    public function addAdmin()
    {
        $roles = Role::all();
        return view('admin.pages.admin.add_admin', compact('roles'));
    } //End method

    public function storeAdmin(Request $request)
    {
        $user = new User();
        $user->username = $request->username;
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->phone    = $request->phone;
        $user->address  = $request->address;
        $user->password = Hash::make($request->password);
        $user->role     = 'admin';
        $user->status   = 'active';
        $user->save();

        ///////Assign Role during created new admin //////
        $assignRole = $request->roles;

        // Check if $assignRole is a string and convert it to an array
        if (is_string($assignRole)) {
            // Assuming roles are comma-separated in the string, you may need to adjust this based on your data format
            $assignRole = explode(',', $assignRole);
        }

        // Convert string IDs to integers
        $rolesIdsAsIntegers = array_map('intval', $assignRole);

        try {
            if ($rolesIdsAsIntegers) {
                $user->assignRole($rolesIdsAsIntegers);
                echo "Roles assigned successfully.";
            } else {
                echo "No valid role IDs found.";
            }
          } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
          }

        $notification = array(
            'message'    =>'Admin Created  Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->route('all.admin')->with($notification);

    } //End method


    public function editAdmin($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.pages.admin.edit_admin', compact('user', 'roles'));

    } //End method


    public function updateAdmin(Request $request, $id)
    {
        $user =  User::findOrFail($id);
        $user->username = $request->username;
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->phone    = $request->phone;
        $user->address  = $request->address;
        $user->role     = 'admin';
        $user->status   = 'active';
        $user->save();

        ///////Assign Role during created new admin //////
        $assignRole = $request->roles;

        // Check if $assignRole is a string and convert it to an array
        if (is_string($assignRole)) {
            // Assuming roles are comma-separated in the string, you may need to adjust this based on your data format
            $assignRole = explode(',', $assignRole);
        }

        // Convert string IDs to integers
        $rolesIdsAsIntegers = array_map('intval', $assignRole);

        $user->roles()->detach();

        try {
            if ($rolesIdsAsIntegers) {
                $user->assignRole($rolesIdsAsIntegers);
                echo "Roles assigned successfully.";
            } else {
                echo "No valid role IDs found.";
            }
          } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
          }

        $notification = array(
            'message'    =>'Admin Updated  Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->route('all.admin')->with($notification);

    } //End method


    public function deleteAdmin($id)
    {
        $user = User::findOrFail($id);

        if(!is_null($user)){
            $user->delete();
        }

        $notification = array(
            'message'    =>'Admin Deleted  Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->route('all.admin')->with($notification);

    } //End method
}
