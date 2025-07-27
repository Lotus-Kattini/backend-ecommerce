<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
        return response()->json([
            'data' => $products,
        ], 200);
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        if ($product) {
            // return response()->json([
            //     'data'=>$product,
            // ],200);
            return new ProductResource($product);
        } else {
            return response()->json([
                'message' => 'product not found',
            ], 404);
        }
    }



    public function store(StoreProductRequest $request)
    {
        $validatedData = $request->validated();
        unset($validatedData['images']); // prevent mass assignment of 'images'

        $product = Product::create($validatedData);


        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image){
                $path = $image->store('productsImages', 'public');
                $product->images()->create(['image' => $path]);
            }
        }

        return response()->json([
            'message' => 'product created successfully',
            'data' => $product->load('images'),
        ], 200);
    }


    public function update(StoreProductRequest $request, $id)
    {
        $product = Product::find($id);
        if ($product) {

            $validatedData = $request->validated();
            unset($validatedData['images']); // prevent mass assignment of 'images'

            $product->update($validatedData);

            if ($request->hasFile('images')) {
                // Delete all existing images
                $product->images()->delete();
                
                // Store new images
                foreach ($request->file('images') as $image){
                    $path = $image->store('productsImages', 'public');
                    $product->images()->create(['image' => $path]);

                }
            }

            return response()->json([
                'message' => 'product updated successfully',
                'data' => $product->load('images'),
            ], 200);
            
        } else {
            return response()->json([
                'message' => 'product not found ',
            ], 404);
        }
    }


    public function destroy($id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->delete();
            return response()->json([
                'message' => 'product deleted successfully ',
            ], 200);
        } else {
            return response()->json([
                'message' => 'product not found ',
            ], 404);
        }
    }




    public function search(Request $request)
    {
        $search = strtolower(trim($request->query('query')));
        if ($search) {

            $products = Product::with('category')
                ->where(function ($q) use ($search) {
                    $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                        ->orWhereRaw('LOWER(description) LIKE ?', ["%{$search}%"]);
                })->get();

            if (!$products->isEmpty()) {
                return response()->json($products, 200);
            } else {
                return response()->json([
                    'message' => 'no results found for the query'
                ], 404);
            }
        } else {
            return response()->json(['message' => 'Search query is required.'], 400);
        }
    }


    public function filter(Request $request){
        $categoryId=$request->query('category_id');
        if($categoryId){
            $products=Product::with('category')->where('category_id',$categoryId)->get();
            return response()->json($products, 200);

        }else{
            return response()->json(['message' => 'category id is not found.'], 404);
        }
    }
}
