<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Storage;

class Products extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user()->role_id !== 1) {
            $products = Product::where('user_id', $request->user()->id)->get();
        } else {
            $products = Product::all();
        }

        foreach ($products as $product) {

            if (!$product->image) {
                $product->image_base64 = null;
                continue;
            }
        
            $imagePath = storage_path('app/public/' . $product->image);

            if (file_exists($imagePath)) {
                $imageContent = file_get_contents($imagePath);
                $product->image_base64 = base64_encode($imageContent);
            } else {
                $product->image_base64 = null;
            }
        }

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }
    
        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->image = $imagePath ?? null;
        $product->user_id = auth()->user()->id;
        $product->save();

        return response()->json([
            'message' => 'Product created successfully',
            'data' => null,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        if($request->user()->role_id !== 1) {
            $product = Product::where('user_id', $request->user()->id)->find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Product not found',
                    'data' => null,
                ]);
            }

        } else {
            $product = Product::find($id);
        }

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }
    
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->image = $imagePath ?? $product->image;
        $product->save();
        
        return response()->json([
            'message' => 'Product updated successfully',
            'data' => null,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        $product->active = !$product->active;
        return response()->json([
            'message' => 'Product deleted successfully',
            'data' => null,
        ]);
    }
}
