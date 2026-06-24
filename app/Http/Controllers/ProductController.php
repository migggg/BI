<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        if ($request->has('search')) {
            $query->where('productName', 'like', '%' . $request->search . '%');
        }
        $items = $query->get();
        return view('crud.products.index', compact('items'));
    }

    public function create()
    {
        return view('crud.products.create');
    }

    public function store(Request $request)
    {
        Product::create($request->all());
        return redirect()->route('dashboard', ['tab' => 'products'])->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        return view('crud.products.show', ['item' => $product]);
    }


    public function edit(Product $product)
    {
        return view('crud.products.edit', ['item' => $product]);
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->all());
        return redirect()->route('dashboard', ['tab' => 'products'])->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('dashboard', ['tab' => 'products'])->with('success', 'Product deleted successfully.');
    }
}
