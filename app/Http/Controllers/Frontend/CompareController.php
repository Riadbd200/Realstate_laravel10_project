<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Property;
use App\Models\Compare;
use Illuminate\Support\Facades\Auth;

class CompareController extends Controller
{
    public function addCompare(Request $request, $property_id)
    {

        if(Auth::check()){

            $exists = Compare::where('user_id', Auth::id())->where('property_id',$property_id)->first();

            if(!$exists){
                Compare::insert([
                    'user_id' =>Auth::id(),
                    'property_id' =>$property_id,
                    'created_at'  => Carbon::now(),
                ]);
                return response()->json([
                    'success' => 'Successfully added this property on your CompareList'
                ]);
            }else{
                return response()->json([
                    'error' => 'This Property has already in your CompareList'
                ]); 
            }
        }else{
            return response()->json([
                'error' => 'At first login your account'
            ]); 
        }

    } //End Method

    public function userCompare()
    {
  
        return view('frontend.dashboard.compare');
    }  //End Method

    public function GetCompareProperty()
    {

        $compare = Compare::with('property')->where('user_id', Auth::id())->latest()->get();

        return response()->json($compare);

    } //End Method

    public function CompareRemove($id)
    {
        Compare::where('user_id', Auth::id())->where('id', $id)->delete();
        
        return response()->json([
            'success' =>'Property Removed successfully from Compare'
        ]);
        
    } //End Method
}
