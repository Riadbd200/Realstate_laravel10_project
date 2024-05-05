<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PropertyType;
use App\Models\State;
use Intervention\Image\Facades\Image;
use App\Models\Testimonial;

class TestimonialController extends Controller
{
    public function allTestimonial()
    {
        $testimonials = Testimonial::latest()->get();
        return view('admin.testimonials.all_testimonials', compact('testimonials'));

    } //End Method

    public function addTestimonial()
    {
        return view('admin.testimonials.add_testimonials');

    } //End Method

    public function storeTestimonial(Request $request)
    {

        $image = $request->file('image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(100,100)->save('upload/testimonial/'.$name_gen);
        $save_url = 'upload/testimonial/'.$name_gen; 

        Testimonial::insert([
            'name'      => $request->name,
            'position'  => $request->position,
            'message'   => $request->message,
            'image' =>$save_url
        ]);

        $notification = array(
            'message'    =>'Testimonial Added Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->route('all.testimonial')->with($notification);


    } //End Method


    public function editTestimonial($id)
    {
        $editTestinomial = Testimonial::findOrFail($id);
        return view('admin.testimonials.edit_testimonial', compact('editTestinomial'));

    } //End Method

    public function updateTestimonial(Request $request)
    {

        $testimonialId = $request->id; 

        if($request->file('image')){

            $image = $request->file('image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(370,275)->save('upload/testimonial/'.$name_gen);
            $save_url = 'upload/testimonial/'.$name_gen; 

            Testimonial::findOrFail($testimonialId )->update([
                'name'  => $request->name,
                'position'  => $request->position,
                'message'   => $request->message,
                'image' =>$save_url
            ]);

            $notification = array(
                'message'    =>'Testimonial Updated With Images Successfully!',
                'alert-type' => 'success'
            );

            return redirect()->route('all.testimonial')->with($notification);

        }else{
            Testimonial::findOrFail($testimonialId )->update([
                'name'      => $request->name,
                'position'  => $request->position,
                'message'   => $request->message,
                
            ]);

            $notification = array(
                'message'    =>'Testimonial Updated Without Images Successfully!',
                'alert-type' => 'success'
            );

            return redirect()->route('all.testimonial')->with($notification);
        } //End if 

    } //End Method


    public function deleteTestimonial($id)
    {

        $deleteTestimonial = Testimonial::findOrFail($id);
        $img = $deleteTestimonial->image;
        unlink($img);

        Testimonial::findOrFail($id)->delete();

        $notification = array(
            'message'    =>'Testimonial Deleted Successfully!',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } //End Method
}
