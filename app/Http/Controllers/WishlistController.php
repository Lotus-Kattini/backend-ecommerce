<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index(){
        // $wishlistproducts = Auth::user()->wishlist()->with(['category', 'images'])->get(); this is also correct !
        $wishlistproducts = Auth::user()->wishlist;
        return response()->json([
            'data' => $wishlistproducts
        ], 200);
    }


    public function store($productId){
        $product=Product::find($productId);
        if($product){

            if(Auth::user()->wishlist()->where('product_id',$productId)->exists()){
                return response()->json([
                    'message'=>'product already exixts in wishlist'
                ],409);
            }

            Auth::user()->wishlist()->syncWithoutDetaching($productId);
                return response()->json([
                    'message'=>'Added to wishlist'
                ],200);
        }else{
                return response()->json([
                    'message'=>'product is not found'
                ],status: 404);
        }
    }



    public function destroy($productId){
        $product=Product::find($productId);
        if($product){
            Auth::user()->wishlist()->detach($productId);
            return response()->json([
                'message'=>'removed from wishlist'
            ],200);
        }else{
            return response()->json([
                'message'=>'product is not found'
            ],status: 404);
        }
    }







}
