<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PropertyType;
use App\Models\State;
use Intervention\Image\Facades\Image;

class StateController extends Controller
{
    public function allState()
    {
        $state = State::latest()->get();
        return view('admin.state.all_state', compact('state'));

    } //End Method


    public function addState()
    {
        return view('admin.state.add_state');
    } //End Method

    public function storeState(Request $request)
    {

        $image = $request->file('state_image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(370,275)->save('upload/state/'.$name_gen);
        $save_url = 'upload/state/'.$name_gen; 

        State::insert([
            'state_name'  => $request->state_name,
            'state_image' =>$save_url
        ]);

        $notification = array(
            'message'    =>'State Added Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->route('all.state')->with($notification);

    } //End Method

    public function editState($id)
    {
        $editState = State::findOrFail($id);
        return view('admin.state.edit_state', compact('editState'));

    } //End Method

    public function updateState(Request $request)
    {

        $stateId = $request->id; 

        if($request->file('state_image')){

            $image = $request->file('state_image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(370,275)->save('upload/state/'.$name_gen);
            $save_url = 'upload/state/'.$name_gen; 

            State::findOrFail($stateId )->update([
                'state_name'  => $request->state_name,
                'state_image' =>$save_url
            ]);

            $notification = array(
                'message'    =>'State Updated With Images Successfully!',
                'alert-type' => 'success'
            );

            return redirect()->route('all.state')->with($notification);

        }else{
            State::findOrFail($stateId )->update([
                'state_name'  => $request->state_name,
                
            ]);

            $notification = array(
                'message'    =>'State Updated Without Images Successfully!',
                'alert-type' => 'success'
            );

            return redirect()->route('all.state')->with($notification);
        } //End if 

    } //End Method

    public function deleteState($id)
    {
        $deleteState = State::findOrFail($id);
        $img = $deleteState->state_image;
        unlink($img);

        State::findOrFail($id)->delete();

        $notification = array(
            'message'    =>'State Deleted Successfully!',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } //End Method
}
