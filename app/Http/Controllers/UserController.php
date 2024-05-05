<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        return view('frontend.index');
    } //End method

    public function UserProfile()
    {
        $id = Auth::user()->id;
        $userData = User::find($id);

        return view('frontend.dashboard.edit_profile', compact('userData'));

    } //End method

    public function UserProfileStore(Request $request)
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
            @unlink(public_path('upload/user_images/'.$data->photo));
            $fileName = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/user_images'),$fileName);
            $data['photo'] = $fileName;
        }

        $data->save();

        $notification = array(
            'message'    =>'User Profile Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } //End Method

    public function UserLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message'    =>'User Logout Successfully',
            'alert-type' => 'success'
        );

        return redirect('/login')->with($notification);

    } //End Method

    public function UserChangePassword()
    {
        return view('frontend.dashboard.change_password');
    } //End Method


    public function UserPasswordUpdate(Request $request)
    {
        // Validation
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed'
        ]);

        //Match the old password

        if(!Hash::check($request->old_password, auth()->user()->password)){
  
        $notification = array(
            'message'    =>'User Old Password Does Not Match!',
            'alert-type' => 'error'
        );

         return back()->with($notification);

        }

        // Update The New Password

        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        $notification = array(
            'message'    =>'Password Change Successfully!',
            'alert-type' => 'success'
        );

         return back()->with($notification);

    } //End Method


    public function userScheduleRequest()
    {

        $id = Auth::user()->id;
        $userData = User::find($id);

        $scheduleRequest = Schedule::where('user_id', $id)->get();

        return view('frontend.message.schedule_request', compact('userData', 'scheduleRequest'));

    } //End Method


    public function liveChat()
    {
        $id = Auth::user()->id;
        $userData = User::find($id);

        return view('frontend.dashboard.live_chat', compact('userData'));

    } //End Method
}
