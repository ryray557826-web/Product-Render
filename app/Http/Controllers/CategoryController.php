<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;

class CategoryController extends Controller
{
    public function index() {
        $categories = Categories::all();
        return view('Categories.index', compact('categories'));
    }

    public function store(Request $request) {
        $request->validate(['category_name' => 'required']);
        Categories::create($request->all());
        return redirect()->route('categories.index');
    }

    public function edit($id)
    {
        $category = Categories::findOrFail($id);
        return view('Categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'required|unique:categories,category_name,' . $id
        ]);

        $category = Categories::findOrFail($id);
        $category->update($request->all());

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }

    public function destroy($id) {
        Categories::findOrFail($id)->delete();
        return redirect()->route('categories.index');
    }
}