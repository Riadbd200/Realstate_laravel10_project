<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\State;
use App\Models\Facility;
use App\Models\Property;
use App\Models\Amenities;
use App\Models\MultiImage;
use App\Models\PackagePlan;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use App\Models\PropertyMessage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class PropertyController extends Controller
{
    public function allProperty()
    {
        $property = Property::latest()->get();
        return view('admin.property.all_property', compact('property'));

    } //End Method

    public function addProperty()
    {
        $data['propertyType'] = PropertyType::latest()->get();
        $data['propertyState'] = State::latest()->get();
        $data['amenities'] = Amenities::latest()->get();
        $data['agentActive'] = User::where('status','active')->where('role','agent')->latest()->get();

        return view('admin.property.add_property', $data);
    } //End Method


    public function storeProperty(Request $request)
    {
        //multiple amenities separate by comma
        $amenite = $request->amenities_id;
        $amenitis = implode(",", $amenite);

        //property code generate
        $pcode = IdGenerator::generate(['table'=>'properties', 'field'=>'property_code', 'length'=>5,'prefix'=>'PC']);

        $image = $request->file('property_thumbnail');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(370,250)->save('upload/property/thumbnail/'.$name_gen);
        $save_url = 'upload/property/thumbnail/'.$name_gen;

        $property_id = Property::insertGetId([
            'ptype_id'     =>$request->ptype_id,
            'amenities_id' =>$amenitis,
            'property_name' =>$request->property_name,
            'property_slug' =>strtolower(str_replace(' ', '-',$request->property_name)),
            'property_code' =>$pcode,
            'property_status' =>$request->property_status,

            'lowest_price' =>$request->lowest_price,
            'max_price' =>$request->max_price,
            'short_descrip' =>$request->short_descrip,
            'long_descrip' =>$request->long_descrip,
            'bedrooms' =>$request->bedrooms,
            'bathrooms' =>$request->bathrooms,
            'garage' =>$request->garage,
            'garage_size' =>$request->garage_size,

            'property_size' =>$request->property_size,
            'property_video' =>$request->property_video,
            'address' =>$request->address,
            'city' =>$request->city,
            'state' =>$request->state,
            'postal_code' =>$request->postal_code,

            'neighborhood' =>$request->neighborhood,
            'latitude' =>$request->latitude,
            'longitude' =>$request->longitude,
            'featured' =>$request->featured,
            'hot' =>$request->hot,
            'agent_id' =>$request->agent_id,
            'status' =>1,
            'property_thumbnail' =>$save_url,
            'created_at'     =>Carbon::now(),
        ]);

         //Multiple image upload from here
         $images = $request->file('multi_img');

         foreach($images as $img){
            $make_name = hexdec(uniqid()).'.'.$img->getClientOriginalExtension();
            Image::make($img)->resize(770,520)->save('upload/property/multi-image/'.$make_name);
            $upload_path = 'upload/property/multi-image/'.$make_name;

            MultiImage::insert([
                'property_id' =>$property_id,
                'photo_name' =>$upload_path,
                'created_at' =>Carbon::now(),

            ]);
         } //end foreach

         //Facility add from here
         $facilities = Count($request->facility_name);

         if($facilities !=NULL){
            for($i=0; $i<$facilities; $i++){
                $fcount= new Facility();
                $fcount->property_id = $property_id;
                $fcount->facility_name = $request->facility_name[$i];
                $fcount->distance = $request->distance[$i];
                $fcount->save();
            }
         }  //end foreach

         $notification = array(
            'message'    =>'Property Added Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->route('all.property')->with($notification);

    } //End Method

    public function editProperty($id)
    {
        $facilities = Facility::where('property_id',$id)->get();

        //Fetch  state 
        $propertyState = State::latest()->get();

        //Fetch amenities
        $property = Property::findOrFail($id);
        $type = $property->amenities_id;
        $amenitis = explode(',', $type);

        //for multi image
        $multiImage = MultiImage::where('property_id',$id)->get();


        $propertyType = PropertyType::latest()->get();
        $amenities = Amenities::latest()->get();
        $agentActive = User::where('status','active')->where('role','agent')->latest()->get();

        return view('admin.property.edit_property', compact('property','propertyType','amenities','agentActive', 'amenitis', 'multiImage', 'facilities', 'propertyState'));

    } //End Method

    public function updateProperty(Request $request)
    {

        //multiple amenities separate by comma
        $amenite = $request->amenities_id;
        $amenitis = implode(",", $amenite);


        $property_id = $request->id;
        Property::findOrFail($property_id)->update([

            'ptype_id'     =>$request->ptype_id,
            'amenities_id' =>$amenitis,
            'property_name' =>$request->property_name,
            'property_slug' =>strtolower(str_replace(' ', '-',$request->property_name)),
            'property_status' =>$request->property_status,

            'lowest_price' =>$request->lowest_price,
            'max_price' =>$request->max_price,
            'short_descrip' =>$request->short_descrip,
            'long_descrip' =>$request->long_descrip,
            'bedrooms' =>$request->bedrooms,
            'bathrooms' =>$request->bathrooms,
            'garage' =>$request->garage,
            'garage_size' =>$request->garage_size,

            'property_size' =>$request->property_size,
            'property_video' =>$request->property_video,
            'address' =>$request->address,
            'city' =>$request->city,
            'state' =>$request->state,
            'postal_code' =>$request->postal_code,

            'neighborhood' =>$request->neighborhood,
            'latitude' =>$request->latitude,
            'longitude' =>$request->longitude,
            'featured' =>$request->featured,
            'hot' =>$request->hot,
            'agent_id' =>$request->agent_id,
            'updated_at'  =>Carbon::now(),

        ]);

        $notification = array(
            'message'    =>'Property Updated Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->route('all.property')->with($notification);

    }  //End Method

    public function updatePropertyThumbnail(Request $request)
    {
        $property_id = $request->id;
        $oldImage = $request->old_img;

        $image = $request->file('property_thumbnail');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(370,250)->save('upload/property/thumbnail/'.$name_gen);
        $save_url = 'upload/property/thumbnail/'.$name_gen;

        if(file_exists($oldImage)){
            unlink($oldImage);
        }

        Property::findOrFail($property_id)->update([
            'property_thumbnail'  =>$save_url,
            'updated_at'          =>Carbon::now() 
        ]);

        $notification = array(
            'message'    =>'Property Thumbnail image Updated Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->back()->with($notification);


    } //End Method 

    public function updatePropertyMultiimage(Request $request)
    {
        // Check if images are selected
        if (!$request->has('photo_name')) {
            $notification = array(
                'message'    =>'Please select at least one image.',
                'alert-type' => 'error'
            );
    
             return redirect()->back()->with($notification);
        }


        $imgs = $request->multi_img;

        foreach($imgs as $id=>$img){
            $imgDel = MultiImage::findOrFail($id);
            unlink($imgDel->photo_name);

            $make_name = hexdec(uniqid()).'.'.$img->getClientOriginalExtension();
            Image::make($img)->resize(770,520)->save('upload/property/multi-image/'.$make_name);
            $upload_path = 'upload/property/multi-image/'.$make_name;

            MultiImage::where('id',$id)->update([
                'photo_name' =>$upload_path,
                'updated_at' =>Carbon::now(),
            ]);

        }  //End foreach

        $notification = array(
            'message'    =>'Property Multi image Updated Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->back()->with($notification);


    } //End Method 


    public function PropertyMultiimageDelete($id)
    {
        $oldImg = MultiImage::findOrFail($id);
        unlink($oldImg->photo_name);

        MultiImage::findOrFail($id)->delete();

        $notification = array(
            'message'    =>'Property Multi image Deleted Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->back()->with($notification);


    } //End Method 

    public function StoreNewMultiImage(Request $request)
    {
        $new_Multiimg = $request->imageid;
        $image = $request->file('multi_img');

        $make_name = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(770,520)->save('upload/property/multi-image/'.$make_name);
        $upload_path = 'upload/property/multi-image/'.$make_name;

        MultiImage::insert([
            'property_id' =>$new_Multiimg,
            'photo_name' =>$upload_path,
            'created_at' =>Carbon::now(),
        ]);

        $notification = array(
            'message'    =>'Property Multi image Added Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->back()->with($notification);



    } //End Method 


    public function UpdatePropertyFacility(Request $request)
    {
        $pid = $request->id;

        if($request->facility_name == NULL){
            return redirect()->back();
        }else{
            Facility::where('property_id', $pid)->delete();

            
         $facilities = Count($request->facility_name);

        
            for($i=0; $i<$facilities; $i++){
                $fcount= new Facility();
                $fcount->property_id = $pid;
                $fcount->facility_name = $request->facility_name[$i];
                $fcount->distance = $request->distance[$i];
                $fcount->save();
            }
        
        }

        $notification = array(
            'message'    =>'Property Facility Updated Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->back()->with($notification);

    } //End Method

    public function DeleteProperty($id)
    {
        $property = Property::findOrFail($id);
        unlink($property->property_thumbnail);

        Property::findOrFail($id)->delete();

        //Multi Image delete which is associate with property
        $image = MultiImage::where('property_id', $id)->get();

        foreach($image as $img){
            unlink($img->photo_name);
            MultiImage::where('property_id', $id)->delete();
        }

        //Facility delete which is associates with property

        $facilities = Facility::where('property_id', $id)->get();

        foreach($facilities as $facility){
            $facility->facility_name;
            Facility::where('property_id', $id)->delete();
        }

        $notification = array(
            'message'    =>'Property Deleted Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->back()->with($notification);

    } //End Method



    public function DetailsProperty($id)
    {
        $facilities = Facility::where('property_id',$id)->get();

        $property = Property::findOrFail($id);
        $type = $property->amenities_id;
        $amenitis = explode(',', $type);

        //for multi image
        $multiImage = MultiImage::where('property_id',$id)->get();


        $propertyType = PropertyType::latest()->get();
        $amenities = Amenities::latest()->get();
        $agentActive = User::where('status','active')->where('role','agent')->latest()->get();

        return view('admin.property.details_property', compact('property','propertyType','amenities','agentActive', 'amenitis', 'multiImage', 'facilities'));

    } //End Method

    public function InactiveProperty(Request $request)
    {
        $pid = $request->id;

        Property::findOrFail($pid)->update([
            'status' => 0
        ]);

        
        $notification = array(
            'message'    =>'Property Inactive Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->route('all.property')->with($notification);

    } //End Method

    public function ActiveProperty(Request $request)
    {
        $pid = $request->id;

        Property::findOrFail($pid)->update([
            'status' => 1
        ]);

        
        $notification = array(
            'message'    =>'Property Active Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->route('all.property')->with($notification);

    } //End Method

    public function AdminPackageHistory()
    {
        $packageHistory = PackagePlan::latest()->get();
        return view('admin.package.package_history', compact('packageHistory'));

    } //End Method


    public function PackageInvoice($id)
    {
        $packageHistory = PackagePlan::where('id',$id)->first();

        $pdf = Pdf::loadView('admin.package.package_history_invoice', compact('packageHistory'))
        ->setPaper('a4')->setOption([
            'tempDir' =>public_path(),
            'chroot'  =>public_path(),
        ]);
        return $pdf->download('invoice.pdf');

    } //End method

    public function adminPropertyMessage()
    {
       
        $userMsg = PropertyMessage::latest()->get();
        return view('admin.message.all_message', compact('userMsg'));

    } //End method

    public function adminMessageDetails($id)
    {
        $authUserId = Auth::user()->id;
        $userMsg = PropertyMessage::latest()->get();
        $msgDetails = PropertyMessage::findOrFail($id);

        return view('admin.message.message_details', compact('userMsg', 'msgDetails'));

    }


}
