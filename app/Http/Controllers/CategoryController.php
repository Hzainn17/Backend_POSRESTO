<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //index
    public function index()
    {
        $categories = \App\Models\Category::paginate(10);
        return view('pages.categories.index', compact('categories'));
    }

    //create
    public function create()
    {
        return view('pages.categories.create'); 
    }

    //store
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'required|string|max:7',
        ]);
        $category = new \App\Models\Category();
        $category->name = $validated['name'];
        $category->description = $validated['description'] ?? null;
        $category->color = $validated['color'] ?? null;
        $category->save();
        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    //show
    public function show($id)
    {
        $category = \App\Models\Category::find($id);
        return view('pages.categories.show', compact('category'));
    }

    //edit
    public function edit($id)
    {
        $category = \App\Models\Category::find($id);
        return view('pages.categories.edit', compact('category'));
    }

    //update
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
        ]);
        $category = \App\Models\Category::find($id);
        $category->name = $validated['name'];
        $category->description = $validated['description'] ?? null;
        $category->color = $validated['color'] ?? null;
        $category->save();
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    //destroy
    public function destroy($id)
    {
        $category = \App\Models\Category::find($id);
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
