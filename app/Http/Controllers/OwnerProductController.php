<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class OwnerProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'subcategory')
            ->orderByRaw('
                CASE
                    WHEN categories_id = 0 AND subcategories_id = 0 THEN 0
                    ELSE 1
                END
            ')
            ->orderBy('categories_id')
            ->orderBy('subcategories_id')
            ->get();

        return view('owner.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $subcategories = Subcategory::orderBy('name')->get();

        return view('owner.products.create', compact('categories', 'subcategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_number'     => 'required|unique:products,model_number',
            'name'             => 'required|string|max:255',
            'categories_id'    => 'required|exists:categories,id',
            'subcategories_id' => 'nullable|exists:subcategories,id',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric',
            'vat'              => 'required|in:yes,no',
            'status'           => 'required|in:enable,disable',
            'special_offer'    => 'required|in:yes,no',
            'barcode'          => 'nullable|string|max:255',
            'image'            => 'required|image',
            'backimage'        => 'nullable|image',
            'nutritionimage'   => 'nullable|image',
        ]);

        $validated['image'] = $request->file('image')->store('products', 'public');

        if ($request->hasFile('backimage')) {
            $validated['backimage'] = $request->file('backimage')->store('products', 'public');
        }

        if ($request->hasFile('nutritionimage')) {
            $validated['nutritionimage'] = $request->file('nutritionimage')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()
            ->route('owner.products.index')
            ->with('success', 'Product added successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $subcategories = Subcategory::where('categories_id', $product->categories_id)
            ->orderBy('name')
            ->get();

        return view('owner.products.edit', compact('product', 'categories', 'subcategories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'model_number'     => 'required|unique:products,model_number,' . $product->id,
            'name'             => 'required|string|max:255',
            'categories_id'    => 'required|exists:categories,id',
            'subcategories_id' => 'nullable|exists:subcategories,id',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric',
            'vat'              => 'nullable|in:yes,no',
            'status'           => 'nullable|in:enable,disable',
            'special_offer'    => 'nullable|in:yes,no',
            'barcode'          => 'nullable|string|max:255',
            'image'            => 'nullable|image',
            'backimage'        => 'nullable|image',
            'nutritionimage'   => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        } else {
            unset($validated['image']);
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

        return redirect()
            ->route('owner.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('owner.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function bulkStatus(Request $request)
    {
        $data = $request->validate([
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'integer|exists:products,id',
            'status' => 'required|in:enable,disable',
        ]);

        $count = Product::whereIn('id', $data['ids'])->update([
            'status' => $data['status'],
        ]);

        $label = $data['status'] === 'enable' ? 'enabled' : 'disabled';

        return redirect()
            ->route('owner.products.index')
            ->with('success', $count.' product(s) '.$label.' successfully.');
    }

    public function getSubcategories($category_id)
    {
        $subcategories = Subcategory::where('categories_id', $category_id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($subcategories);
    }
}
