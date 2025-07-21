<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $categories=Category::paginate(10);
        return response()->json([
            'data'=>$categories,
        ],200);
    }


    public function show($id)  {
        $category=Category::find($id);
        if($category){
            return response()->json([
                'data'=>$category,
            ],200);
        }else{
            return response()->json([
                'message'=>'categoty not found',
            ],404);
        }
    }

    public function store(Request $request){
        $category=$request->validate([
            'name'=>'required|string|max:255'
        ]);

        $createdCategory=Category::create($category);
        return response()->json([
            'message'=>'categoty created successfully',
            'data'=>$createdCategory,
        ],201);
    }


    public function update(Request $request, $id){
        $category=Category::find($id);
        if($category){
            $vaidatedData=$request->validate([
                'name'=>'required|string'
            ]);
            $category->update($vaidatedData);
            return response()->json([
                'message'=>'categoty updated successfully',
                'data'=>$category,
            ],200);
        }else{
            return response()->json([
                'message'=>'categoty not found',
            ],404);
        }
    }



    public function destroy($id){
        $category=Category::find($id);
        if($category){
            $category->delete();
            return response()->json([
                'message'=>'categoty deleted successfully',
            ],200);
        }else{
            return response()->json([
                'message'=>'categoty not found',
            ],404);
        }
    }




}
