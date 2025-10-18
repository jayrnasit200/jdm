<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subcategory;

class SubCustomerController extends Controller
{
    public function index($catid)
    {
        $subcategories = Subcategory::where('categories_id', $catid)->get();
        return view('subcategory.listsubcategory', compact('subcategories', 'catid'));
    }
    

    // Show create form
    public function create($catid)
    {
        return view('subcategory.create', compact('catid'));
    }

    // Store new subcategory
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'categories_id' => 'required|integer',
        ]);

        Subcategory::create([
            'name' => $request->name,
            'categories_id' => $request->categories_id,
        ]);

        return redirect()->route('subcatogory', $request->categories_id)->with('success', 'Subcategory created successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        $subcategory = Subcategory::findOrFail($id);
        return view('subcategory.edit', compact('subcategory'));
    }

    // Update subcategory
    public function update(Request $request, $id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $subcategory->update([
            'name' => $request->name,
        ]);

        return redirect()->route('subcatogory', $subcategory->categories_id)->with('success', 'Subcategory updated successfully.');
    }

    // Delete subcategory
    public function destroy($id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $catid = $subcategory->categories_id;
        $subcategory->delete();

        return redirect()->route('subcatogory', $catid)->with('success', 'Subcategory deleted successfully.');
    }
}
