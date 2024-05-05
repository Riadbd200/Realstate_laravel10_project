<?php

namespace App\Http\Controllers\Agent;

use Carbon\Carbon;
use App\Models\User;
use App\Models\State;
use App\Models\Facility;
use App\Models\Property;
use App\Models\Schedule;
use App\Models\Amenities;
use App\Mail\ScheduleMail;
use App\Models\MultiImage;
use App\Models\PackagePlan;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use App\Models\PropertyMessage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class AgentPropertyController extends Controller
{
    public function AgentAllProperty()
    {
        $id = Auth::user()->id;
        $property = Property::where('agent_id',$id)->latest()->get();
        return view('agent.property.all_property', compact('property'));

    } //End Method


    public function AgentAddProperty()
    {
        $propertyType = PropertyType::latest()->get();
        $amenities = Amenities::latest()->get();

        $pstate = State::latest()->get();

        $id = Auth::user()->id;
        $property = User::where('role', 'agent')->where('id',$id)->first();
        $pcount = $property->credit;

        if($pcount == 1 || $pcount == 7){
            return redirect()->route('buy.package');
        }else{
         return view('agent.property.add_property', compact('propertyType','amenities', 'pstate'));
        }
        
    }  //End method

    public function AgentStoreProperty(Request $request)
    {

        $id  = Auth::user()->id;
        $uid = User::findOrFail($id);
        $nid = $uid->credit;
        
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
            'agent_id' =>Auth::user()->id,
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

         User::where('id', $id)->update([
            'credit' =>DB::raw('1 + '. $nid),

         ]);

         $notification = array(
            'message'    =>'Property Added Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->route('agent.all.property')->with($notification);

    }  //End method

    public function AgentEditProperty($id)
    {
        
        $facilities = Facility::where('property_id',$id)->get();

        $property = Property::findOrFail($id);
        $type = $property->amenities_id;
        $amenitis = explode(',', $type);

        //for multi image
        $multiImage = MultiImage::where('property_id',$id)->get();


        $propertyType = PropertyType::latest()->get();
        $amenities = Amenities::latest()->get();
        $pstate = State::latest()->get();

        return view('agent.property.edit_property', compact('property','propertyType','amenities', 'amenitis', 'multiImage', 'facilities', 'pstate'));
    } //End method


    public function AgentUpdateProperty(Request $request)
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
            'agent_id' =>Auth::user()->id,
            'updated_at'  =>Carbon::now(),

        ]);

        $notification = array(
            'message'    =>'Property Updated Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->route('agent.all.property')->with($notification);

    } //End method


    public function AgentUpdatePropertyThumbnail(Request $request)
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
    } //End method

    public function AgentUpdatePropertyMultiImage(Request $request)
    {
        //Check if images are selected
        if (!$request->has('multi_img') || empty($request->multi_img)) {

            $notification = array(
                'message'    =>'Please select at least one image.',
                'alert-type' => 'error'
            );
    
             return redirect()->back()->with($notification);
        }


        $imgs = $request->multi_img;

        foreach($imgs as $id=>$img){

            if($img && $img instanceof \Illuminate\Http\UploadedFile){
                $imgDel = MultiImage::findOrFail($id);
                unlink($imgDel->photo_name);
    
                $make_name = hexdec(uniqid()).'.'.$img->getClientOriginalExtension();
                Image::make($img)->resize(770,520)->save('upload/property/multi-image/'.$make_name);
                $upload_path = 'upload/property/multi-image/'.$make_name;
    
                MultiImage::where('id',$id)->update([
                    'photo_name' =>$upload_path,
                    'updated_at' =>Carbon::now(),
                ]);
            }
          

        }  //End foreach

        $notification = array(
            'message'    =>'Property Multi image Updated Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->back()->with($notification);

    }  //End method

    public function AgentDeletePropertyMultiimage($id)
    {
        $oldImg = MultiImage::findOrFail($id);
        unlink($oldImg->photo_name);

        MultiImage::findOrFail($id)->delete();

        $notification = array(
            'message'    =>'Property Multi image Deleted Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->back()->with($notification);

    }  //End method


    public function AgentStoreNewPropertyMultiImage(Request $request)
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

    }  //End method


    public function AgentUpdatePropertyFacility(Request $request)
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

    }  //End method


    public function AgentDetailsProperty($id)
    {
        
        $facilities = Facility::where('property_id',$id)->get();

        $property = Property::findOrFail($id);
        $type = $property->amenities_id;
        $amenitis = explode(',', $type);

        //for multi image
        $multiImage = MultiImage::where('property_id',$id)->get();


        $propertyType = PropertyType::latest()->get();
        $amenities = Amenities::latest()->get();
        $pstate = State::latest()->get();
       

        return view('agent.property.details_property', compact('property','propertyType','amenities', 'amenitis', 'multiImage', 'facilities', 'pstate'));
    } //End method


    public function AgentDeleteProperty($id)
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
    }  //End method


   
   
    public function BuyPackage()
    {
        return view('agent.package.buy_package');
    } //End method
   


    public function BuyBusinessPlan()
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        return view('agent.package.business_plan', compact('data'));

    } //End method

    public function StoreBusinessPlan(Request $request)
    {
        $id = Auth::user()->id;
        $uid = User::findOrFail($id);
        $nid = $uid->credit;

        PackagePlan::insert([
            'user_id'         =>$id,
            'package_name'    =>'Business',
            'invoice'         =>'ERS'.mt_rand(10000000,99999999),
            'package_credits' =>'3',
            'package_amount'  =>'20',
            'created_at'      =>Carbon::now()
        ]);

        User::where('id', $id)->update([
            'credit' =>DB::raw('3 + '. $nid),

         ]);


        $notification = array(
            'message'    =>'You Have Purchase Business package Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->route('agent.all.property')->with($notification);

    } //End method


    public function BuyProfessionalPlan()
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        return view('agent.package.professional_plan', compact('data'));

    } //End method

    public function StoreProfessionalPlan(Request $request)
    {
        $id = Auth::user()->id;
        $uid = User::findOrFail($id);
        $nid = $uid->credit;

        PackagePlan::insert([
            'user_id'         =>$id,
            'package_name'    =>'Professional',
            'invoice'         =>'ERS'.mt_rand(10000000,99999999),
            'package_credits' =>'10',
            'package_amount'  =>'50',
            'created_at'      =>Carbon::now()
        ]);

        User::where('id', $id)->update([
            'credit' =>DB::raw('10 + '. $nid),

         ]);


        $notification = array(
            'message'    =>'You Have Purchase Professional package Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->route('agent.all.property')->with($notification);

    } //End method

    public function PackageHistory()
    {
        $id = Auth::user()->id;
        $packageHistory = PackagePlan::where('user_id',$id)->get();

        return view('agent.package.package_history', compact('packageHistory'));

    } //End method

    public function AgentPackageInvoice($id)
    {
        $packageHistory = PackagePlan::where('id',$id)->first();

        $pdf = Pdf::loadView('agent.package.package_history_invoice', compact('packageHistory'))
        ->setPaper('a4')->setOption([
            'tempDir' =>public_path(),
            'chroot'  =>public_path(),
        ]);
        return $pdf->download('invoice.pdf');

    } //End method


    public function agentPropertyMessage()
    {
        $id = Auth::user()->id;
        $userMsg = PropertyMessage::where('agent_id', $id)->get();
        return view('agent.message.all_message', compact('userMsg'));

    } //End method

    public function agentMessageDetails($id)
    {
        $authUserId = Auth::user()->id;
        $userMsg = PropertyMessage::where('agent_id', $authUserId)->get();
        $msgDetails = PropertyMessage::findOrFail($id);

        return view('agent.message.message_details', compact('userMsg', 'msgDetails'));
        
    } //End method



    //Schedule Request Method
    public function agentScheduleRequest()
    {
        $id = Auth::user()->id;
        $userMsg = Schedule::where('agent_id', $id)->get();

        return view('agent.message.schedule_request', compact('userMsg'));
    } //End method

    public function agentScheduleDetails($id)
    {
        $schedule = Schedule::findOrFail($id);

        return view('agent.message.schedule_details', compact('schedule'));
    } //End method


    public function agentUpdateSchedule(Request $request)
    {

        $sid = $request->id;
        Schedule::findOrFail($sid)->update([
            'status' => '1'
        ]);

        //send mail 
        $sendMail = Schedule::findOrFail($sid);
        $data = [
            'tour_date' =>$sendMail->tour_date,
            'tour_time' =>$sendMail->tour_time,
        ];

        Mail::to($request->user()->email)
            ->cc('zakirtextknoter@gmail.com')
            ->bcc('zakirhossen082@gmail.com')
            ->send(new ScheduleMail($data));



        $notification = array(
            'message'    =>'You have confirm schedule Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->route('agent.schedule.request')->with($notification);

    } //End method
}
