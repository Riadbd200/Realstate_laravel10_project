<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\User;
use App\Models\State;
use App\Models\Facility;
use App\Models\Property;
use App\Models\Amenities;
use App\Models\MultiImage;
use App\Models\PackagePlan;
use App\Models\PropertyType;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Models\PropertyMessage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function PropertyDetails($id, $slug)
    {
        $property = Property::findOrFail($id);

        //For Amenities display
        $amenities= $property->amenities_id;
        $property_amenities = explode(',',$amenities);

        $multiImage = MultiImage::where('property_id', $id)->get();
        $facility = Facility::where('property_id', $id)->get();

        $ptype_id =  $property->ptype_id;
        $relatedProperty = Property::where('ptype_id', $ptype_id)
                                    ->where('id', '!=', $id)
                                    ->orderBy('id', 'DESC')
                                    ->limit(3)->get();

        return view('frontend.property.property_details', compact('property','multiImage', 'property_amenities', 'facility', 'relatedProperty'));
    }  //End method


    public function PropertyMessage(Request $request)
    {

        $pid = $request->property_id;
        $aid = $request->agent_id;

        if(Auth::check()){

            PropertyMessage::insert([
                'user_id'     =>Auth::user()->id,
                'agent_id'    =>$aid,
                'property_id' =>$pid,
                'msg_name'    =>$request->msg_name,
                'msg_email'   =>$request->msg_email,
                'msg_phone'   =>$request->msg_phone,
                'message'     =>$request->message,
                'created_at'  =>Carbon::now(),
            ]);

            $notification = array(
                'message'    =>'Send Message Successfully!',
                'alert-type' => 'success'
            );
    
             return redirect()->back()->with($notification);

        }else{
            $notification = array(
                'message'    =>'Please Login Your Account First',
                'alert-type' => 'error'
            );
    
             return redirect()->back()->with($notification);
        }

    }  //End method

    public function agentDetails($id)
    {


        $data['agent'] = User::findOrFail($id);
        $data['property'] = Property::where('agent_id', $id)->get();
        $data['featured'] = Property::where('featured',1)->limit(3)->get();
        $data['rentProperty'] = Property::where('property_status', 'rent')->get();
        $data['buyProperty'] = Property::where('property_status', 'buy')->get();

       
        return view('frontend.agent.agent_details',$data);

    } //End method


    public function agentDetailsMessage(Request $request)
    {

        $aid = $request->agent_id;

        if(Auth::check()){

            PropertyMessage::insert([
                'user_id'     =>Auth::user()->id,
                'agent_id'    =>$aid,
                'msg_name'    =>$request->msg_name,
                'msg_email'   =>$request->msg_email,
                'msg_phone'   =>$request->msg_phone,
                'message'     =>$request->message,
                'created_at'  =>Carbon::now(),
            ]);

            $notification = array(
                'message'    =>'Send Message Successfully!',
                'alert-type' => 'success'
            );
    
             return redirect()->back()->with($notification);

        }else{
            $notification = array(
                'message'    =>'Please Login Your Account First',
                'alert-type' => 'error'
            );
    
             return redirect()->back()->with($notification);
        }

    }  //End method


    public function rentProperty()
    {

        $rentPropertyList = Property::where([
            'status' =>'1',
            'property_status' =>'rent'
        ])->paginate(2);

        return view('frontend.property.rent_property', compact('rentPropertyList'));


    } //End method


    public function buyProperty()
    {

        $buyPropertyList = Property::where([
            'status' =>'1',
            'property_status' =>'buy'
        ])->get();

        return view('frontend.property.buy_property', compact('buyPropertyList'));

    } //End method

    public function propertyType($id)
    {
        $data['property'] = Property::where([
            'status'    =>'1',
            'ptype_id'  =>$id
        ])->get();

        $data['ptypeBreadcum'] = PropertyType::where('id', $id)->first();

        return view('frontend.property.property_type',$data);

    } //End method

    public function stateDetails($id)
    {
        $property = Property::where([
            'status' =>'1',
            'state'  =>$id
        ])->get();

        $bstate = State::where('id', $id)->first();

        return view('frontend.property.state_property', compact('property', 'bstate'));

    } //End method

    public function buyPropertySearch(Request $request)
    {

        $request->validate(['search'=>'required']);
        $item = $request->search;
        $sstate = $request->state;
        $stype = $request->ptype_id;

        $property = Property::where('property_name', 'like', '%' .$item. '%')
                    ->where('property_status', 'buy')->with('type', 'pstate')
                    ->whereHas('pstate', function($q) use ($sstate){
                        $q->where('state_name', 'like', '%' .$sstate. '%');
                    })
                    ->whereHas('type', function($q) use ($stype){
                        $q->where('type_name', 'like', '%' .$stype. '%');
                    })->get();

        return view('frontend.property.property_search', compact('property'));

    } //End method


    public function rentPropertySearch(Request $request)
    {
        $request->validate(['search'=>'required']);
        $item = $request->search;
        $sstate = $request->state;
        $stype = $request->ptype_id;

        $property = Property::where('property_name', 'like', '%' .$item. '%')
                    ->where('property_status', 'rent')->with('type', 'pstate')
                    ->whereHas('pstate', function($q) use ($sstate){
                        $q->where('state_name', 'like', '%' .$sstate. '%');
                    })
                    ->whereHas('type', function($q) use ($stype){
                        $q->where('type_name', 'like', '%' .$stype. '%');
                    })->get();

        return view('frontend.property.property_search', compact('property'));
        
    } //End method

    public function allPropertySearch(Request $request)
    {
        
        $property_status = $request->property_status;
        $stype = $request->ptype_id;
        $sstate = $request->state;
        $bedrooms = $request->bedrooms;
        $bathrooms = $request->bathrooms;
       
       

        $property = Property::where([
                        'status' => '1',
                        'bedrooms' =>$bedrooms
                    ])->where('bathrooms', 'like', '%' . $bathrooms. '%')
                     ->where('property_status', $property_status)
                     ->with('type', 'pstate')
                     ->whereHas('pstate', function($q) use ($sstate){
                        $q->where('state_name', 'like', '%' .$sstate. '%');
                    })
                    ->whereHas('type', function($q) use ($stype){
                        $q->where('type_name', 'like', '%' .$stype. '%');
                    })->get();

        return view('frontend.property.property_search', compact('property'));
        
    } //End method

    public function storeSchedule(Request $request)
    {
        if(Auth::check()){
            $aid = $request->agent_id;
            $pid = $request->property_id;

            Schedule::insert([
                'user_id'     => Auth::user()->id,
                'property_id' => $pid,
                'agent_id'    =>$aid,
                'tour_date'   =>$request->tour_date,
                'tour_time'   =>$request->tour_time,
                'message'     =>$request->message,
                'created_at'  =>Carbon::now(),
            ]);

            $notification = array(
                'message'    =>'Tour Schedule Inserted',
                'alert-type' => 'success'
            );
    
             return redirect()->back()->with($notification);

        }else{

            $notification = array(
                'message'    =>'Please Login Your Account First',
                'alert-type' => 'error'
            );
    
             return redirect()->back()->with($notification);
        }

    } //End method

}
