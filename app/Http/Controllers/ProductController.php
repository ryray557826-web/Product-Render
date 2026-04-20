<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Categories;

class ProductController extends Controller
{
    public function index() {
        $products = Product::with('category')->get();
        $categories = Categories::all();
        return view('Products.index', compact('products', 'categories'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);
        Product::create($request->all());
        return redirect()->route('products.index');
    }

    public function edit($id) {
        $product = Product::findOrFail($id);
        $categories = Categories::all();
        return view('Products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id) {
        $product = Product::findOrFail($id);
        $product->update($request->all());
        return redirect()->route('products.index');
    }

    public function destroy($id) {
        Product::findOrFail($id)->delete();
        return redirect()->route('products.index');
    }
}