<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::get();
        if ($products->count() > 0) {
            return ProductResource::collection($products);
        }
        return response()->json(['message' => 'No Products Found'], 200);
    }
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|integer',
            'description' => 'required',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => $validated->errors(),
            ], 422);
        }

        $createdProduct = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description
        ]);

        return response()->json([
            'message' => 'Product Created Succesfully',
            'data' => new ProductResource($createdProduct)
        ], 200);
    }
    public function show(Product $product)
    {
        return new ProductResource($product);
    }
    public function update(Product $product, Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|integer',
            'description' => 'required',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => $validated->errors(),
            ], 422);
        }

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description
        ]);

        return response()->json([
            'message' => 'Product Updated Succesfully',
            'data' => new ProductResource($product)
        ], 200);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product Deleted Succesfully'], 200);
    }


}
