<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // return Product::orderBy('id', 'desc')->paginate(5);

        // $product = Product::query();

        // if(!request()->search) {
        //     return $product->orderBy('id', 'desc')->paginate(5);
        // }

        // return $product->where('name', 'like', request()->search. '%')->orderBy('id', 'desc')->paginate(5);

        return Product::when(request()->search, function($product) {
            $product->where('name', 'like', request()->search. '%')->orderBy('id', 'desc')->paginate(5);
        })->orderBy('id', 'desc')->paginate(5);
    }

    public function store(ProductStoreRequest $request)
    {
        $validated = $request->validated();
        $product = Product::create($validated);
        return $product;
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function update(ProductStoreRequest $request, Product $product)
    {
        $validated = $request->validated();
        $product->update($validated);
        return $product;
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return $product;
    }
}
