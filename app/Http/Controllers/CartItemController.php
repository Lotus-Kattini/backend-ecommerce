<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartItemController extends Controller
{
    public function index(){
        $userId=Auth::user()->id;
        $cartItems=CartItem::with('product')->where('user_id',$userId)->get();
        return response()->json([
            'data'=>$cartItems,
        ], 200);
    }

    public function store(Request $request){
        $userId=Auth::user()->id;
        $validated=$request->validate([
            'product_id'=>'required|integer|exists:products,id',
            'quantity'=>'required|integer|min:1',
        ]);
        $item=CartItem::where('user_id',$userId)->where('product_id',$request->product_id)->first();

        if($item){

            $item->update([
                'quantity'=>$item->quantity + $request->quantity 
            ]);


        }else{
            $validated['user_id']=$userId;
            $item=CartItem::create($validated);

        }
            // return response()->json([
            //     'data'=>$item,
            // ], 201);
            return response()->json($item->load('product'), 201);
    }


    public function update(Request $request,$id){
        $request->validate([
            'quantity'=>'required|integer|min:1',
        ]);
        $item=CartItem::find($id);
        if($item){
            $item->update([
                'quantity'=>$request->quantity
            ]);
            return response()->json([
                'message'=>'updated successfully',
                'data'=>$item,
            ], 201);
        }else{
            return response()->json([
                'message'=>'item is not found',
            ], 404);
        }
    }


    public function destroy($id)  {
        $item=CartItem::find($id);
        if($item){
            $item->delete();
            return response()->json([
                'message'=>'item deleted successfully',
            ], 200);
        }else{
            return response()->json([
                'message'=>'item is not found',
            ], 404);
        }
    }


    public function clear(){
        $userId=Auth::user()->id;
        CartItem::where('user_id',$userId)->delete();
        
            return response()->json([
                'message'=>'cart cleared successfully',
            ], 200);
    }


}
