<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return Product::all();
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required|string',
        'descripcion' => 'required|string',
        'precio' => 'required|numeric',
        'imagen' => 'nullable|image|max:2048',
        // 'restaurante_id' => 'required|exists:restaurantes,id' // si vas a relacionarlo
    ]);

    $data = $request->all();

    if ($request->hasFile('imagen')) {
        $data['imagen'] = $request->file('imagen')->store('productos', 'public');
    }

    $product = Product::create($data);
    return response()->json($product, 201);
}


    public function update(Request $request, Product $product)
{
    $data = $request->all();

    if ($request->hasFile('imagen')) {
        if ($product->imagen) {
            \Storage::disk('public')->delete($product->imagen);
        }

        $data['imagen'] = $request->file('imagen')->store('productos', 'public');
    }

    $product->update($data);
    return response()->json($product, 200);
}

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}