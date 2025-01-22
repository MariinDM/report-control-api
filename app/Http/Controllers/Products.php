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

            $products = Product::with('user')
                ->where('user_id', $request->user()->id)
                ->get();
        } else {
            $products = Product::with('user')
                ->get();
        }

        foreach ($products as $product) {

            if (!$product->image) {
                $product->imageUrl = null;
                continue;
            }
        
            $product->image_url = url( 'storage/'.$product->image);
        }

        return response()->json([
            'message' => 'Productos Obtenidos',
            'data' => $products,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'price' => 'required',
        ]);

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
            'message' => 'Product Creado',
            'data' => null,
        ], 200);
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
        $request->validate([
            'name' => 'required',
            'price' => 'required',
        ]);

        if ($request->user()->role_id !== 1) {
            $product = Product::where('user_id', $request->user()->id)->find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Producto no encontrado',
                    'data' => null,
                ], 400);
            }
        } else {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Producto no encontrado',
                    'data' => null,
                ], 400);
            }
        }

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
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
            'message' => 'Producto actualizado',
            'data' => null,
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Producto no encontrado',
                'data' => null,
            ], 400);
        }

        $product->active = !$product->active;
        $product->save();

        return response()->json([
            'message' => 'Estado del producto actualizado',
            'data' => null,
        ], 200);
    }
}
