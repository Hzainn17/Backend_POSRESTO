<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    //index
    public function index(Request $request)
    {
        $query = \App\Models\Product::with('category');

        // search by name or description
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // filter category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->paginate(10);

        // categories
        $categories = \App\Models\Category::all();

        return view('pages.products.index', compact('products', 'categories'));
    }

    //create
    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('pages.products.create', compact('categories'));
    }

    //show
    public function show($id)
    {
        $product = \App\Models\Product::with('category')->find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product);
    }

    //store
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
            'stock' => 'required|integer',
        ]);

        //store request
        $product = \App\Models\Product::create($validated);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->is_active = $request->has('is_active') ? 1 : 0;
        $product->stock = $request->stock;
        $product->save();

        //save image
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $filename = $product->id . '.' . $image->getClientOriginalExtension();

            $image->storeAs('products', $filename, 'public');

            // FIX INI
            $product->image = 'products/' . $filename;

            $product->save();
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    //update
    public function update(Request $request, $id)
    {
        //validate
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
            'stock' => 'required|integer',
        ]);

        //update request
        $product = \App\Models\Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->is_active = $request->has('is_active') ? 1 : 0;
        $product->stock = $request->stock;
        $product->save();

        //save image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = $product->id . '.' . $image->getClientOriginalExtension();
            $image->storeAs('products', $filename, 'public');
            $product->image = 'products/' . $filename;

            $product->save();
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    //destroy
    public function destroy($id)
    {
        // delete the request...
        $product = \App\Models\Product::find($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }

    //edit
    public function edit($id)
    {
        $product = \App\Models\Product::find($id);
        $categories = \App\Models\Category::all();
        return view('pages.products.edit', compact('product', 'categories'));
    }
}
