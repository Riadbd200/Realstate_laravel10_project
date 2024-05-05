<?php

namespace App\Http\Controllers;

use App\Models\Amenities;
use Illuminate\Http\Request;

class Amenitie extends Controller
{
    public function AllAmenitie()
    {
        $amenitie = Amenities::latest()->get();
        return view('admin.amenitie.all_amenitie', compact('amenitie'));
    } //End Method

    public function AddAmenitie()
    {
        return view('admin.amenitie.add_amenitie');
    } //End Method

    public function StoreAmenitie(Request $request)
    {
        // Validation
        // $request->validate([
        //     'amenitis_name' => 'required|unique:amenities|max:200',
        // ]);

        Amenities::insert([
            'amenitis_name' =>$request->amenitis_name,
        ]);

        $notification = array(
            'message'    =>'Amenitie  Added Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.amenitie')->with($notification);

    } //End Method

    public function EditAmenitie($id)
    {
        $amenitie = Amenities::findOrFail($id);
        return view('admin.amenitie.edit_amenitie', compact('amenitie'));
    }  //End Method

    public function UpdateAmenitie(Request $request)
    {
        
        $aid = $request->id;

        Amenities::findOrFail($aid)->update([
            'amenitis_name' =>$request->amenitis_name,
        ]);

        $notification = array(
            'message'    =>'Amenitie Updated Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.amenitie')->with($notification);

    } //End Method

    public function DeleteAmenitie($id)
    {

        Amenities::findOrFail($id)->delete();

        $notification = array(
            'message'    =>'Amenitie  Deleted Successfully',
            'alert-type' => 'success'
        );

         return redirect()->back()->with($notification);

    } //End Method
}
