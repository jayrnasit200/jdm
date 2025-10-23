<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;

class ProductController extends Controller
{
    public function index()
{
    $products = Product::with('category', 'subcategory')->get();
    return view('products.productslist', compact('products'));
}
public function create()
{
    $categories = Category::all();
    $subcategories = Subcategory::all();
    return view('products.create', compact('categories', 'subcategories'));
}
public function store(Request $request)
{
    // Validate the incoming request
    $validated = $request->validate([
        'model_number'      => 'required|unique:products',
        'name'              => 'required|string|max:255',
        'categories_id'     => 'required|exists:categories,id',
        'subcategories_id'  => 'nullable|exists:subcategories,id',
        'description'       => 'nullable|string',
        'price'             => 'required|numeric',
        'vat'               => 'required|in:yes,no',
        'status'            => 'required|in:enable,disable',
        'special_offer'     => 'required|in:yes,no',
        'barcode'           => 'nullable|string|max:255',
        'image'             => 'required|image',
        'backimage'         => 'nullable|image',
        'nutritionimage'    => 'nullable|image',
    ]);

    // Handle file uploads
    $validated['image'] = $request->file('image')->store('products', 'public');

    if ($request->hasFile('backimage')) {
        $validated['backimage'] = $request->file('backimage')->store('products', 'public');
    }

    if ($request->hasFile('nutritionimage')) {
        $validated['nutritionimage'] = $request->file('nutritionimage')->store('products', 'public');
    }

    // Create the product
    Product::create($validated);

    return redirect()->route('products.index')->with('success', 'Product added successfully.');
}

public function edit($id)
{
    $categories = Category::all();
    $subcategories = Subcategory::all();
    $product = Product::findOrFail($id);
    return view('products.edit', compact('product', 'categories', 'subcategories'));
}
public function update(Request $request, Product $product)
{
    $validated = $request->validate([
        'model_number' => 'required',
        'name' => 'required|string|max:255',
        'categories_id' => 'required|exists:categories,id',
        'subcategories_id' => 'nullable|exists:subcategories,id',
        'price' => 'required|numeric',
        'vat' => 'nullable|in:yes,no',
        'status' => 'nullable|in:enable,disable',
        'special_offer' => 'nullable|in:yes,no',
        'image' => 'nullable|image',
        'backimage' => 'nullable|image',
        'nutritionimage' => 'nullable|image',
        'description' => 'nullable|string',
        'barcode' => 'nullable|string',
    ]);
    // dd($request->all());

    // print_r($request->id);
    // exit;
    // Handle file uploads, only replace if new file is uploaded
    if ($request->hasFile('image')) {
        $validated['image'] = $request->file('image')->store('products', 'public');
    } else {
        unset($validated['image']); // Keep old image
    }

    if ($request->hasFile('backimage')) {
        $validated['backimage'] = $request->file('backimage')->store('products', 'public');
    } else {
        unset($validated['backimage']);
    }

    if ($request->hasFile('nutritionimage')) {
        $validated['nutritionimage'] = $request->file('nutritionimage')->store('products', 'public');
    } else {
        unset($validated['nutritionimage']);
    }

    $product->update($validated);

    return redirect()->route('products.index')->with('success', 'Product updated successfully.');
}

public function destroy($id)
{
    $product = Product::findOrFail($id);
    $product->delete();

    return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
}
public function getSubcategories($category_id)
{
    $subcategories = \App\Models\Subcategory::where('categories_id', $category_id)->get(['id', 'name']);
    return response()->json($subcategories);
}


}
