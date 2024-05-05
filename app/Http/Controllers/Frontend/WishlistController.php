<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Facility;
use App\Models\Property;
use App\Models\Wishlist;
use App\Models\Amenities;
use App\Models\MultiImage;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function addWishList(Request $request, $property_id)
    {

        if(Auth::check()){

            $exists = Wishlist::where('user_id', Auth::id())->where('property_id',$property_id)->first();

            if(!$exists){
                Wishlist::insert([
                    'user_id' =>Auth::id(),
                    'property_id' =>$property_id,
                    'created_at'  => Carbon::now(),
                ]);
                return response()->json([
                    'success' => 'Successfully added this property on your Wishlist'
                ]);
            }else{
                return response()->json([
                    'error' => 'This Property has already in your Wishlist'
                ]); 
            }
        }else{
            return response()->json([
                'error' => 'At first login your account'
            ]); 
        }

    } //End Method

    public function userWishList()
    {
        $id = Auth::user()->id;
        $userData = User::find($id);

        return view('frontend.dashboard.wishlist', compact('userData'));

    } //End Method


    public function GetWishListProperty()
    {
        $wishlist = Wishlist::with('property')->where('user_id', Auth::id())->latest()->get();

        $wishListQty = Wishlist::count();

        return response()->json([
            'wishlist'    =>$wishlist,
            'wishListQty' =>$wishListQty,
        ]);

    } //End Method


    public function WishListRemove($id)
    {
        Wishlist::where('user_id', Auth::id())->where('id', $id)->delete();
        return response()->json([
            'success' =>'Property Removed successfully from wishlist'
        ]);

    } //End Method
}
