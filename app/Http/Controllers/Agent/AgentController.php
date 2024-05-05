<?php

namespace App\Http\Controllers\Agent;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use App\Providers\RouteServiceProvider;

class AgentController extends Controller
{
    public function agentDashboard()
    {
        return view('agent.index');
    } //End Method

    public function AgentLogin()
    {
        return view('agent.agent_login');

    }  //End Method

    public function agentLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message'    =>'Agent Logout Successfully',
            'alert-type' => 'success'
        );

        return redirect('/agent/login')->with($notification);
    } //End Method


    public function AgentRegister(Request $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     =>'agent',
            'status'   =>'inactive',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::AGENT);

    }  //End Method


    public function agentProfile()
    {
        $id = Auth::user()->id;
        $profileData = User::find($id);

        return view('agent.agent_profile_view', compact('profileData')); 
    } //End Method


    public function agentProfileStore(Request $request)
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
            @unlink(public_path('upload/agent/'.$data->photo));
            $fileName = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/agent'),$fileName);
            $data['photo'] = $fileName;
        }

        $data->save();

        $notification = array(
            'message'    =>'Agent Profile Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } //End Method


    public function agentChangePassword()
    {
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('agent.agent_change_password', compact('profileData'));

    } //End method


    public function agentUpdatePassword(Request $request)
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
            'message'    =>'Agent  Password Change Successfully!',
            'alert-type' => 'success'
        );

         return back()->with($notification);

    } //End Method


  

}
