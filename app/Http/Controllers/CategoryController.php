<?php

namespace App\Http\Controllers;
use App\Models\Category;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()

    {  $categories = Category::all();

        // Return the view with the data
        return view('Category.listcategory', compact('categories'));
        // return view('Category.listcategory');
    }
    public function create()
    {
        return view('Category.create'); // New page for adding category
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);
    
        Category::create($request->only('name', 'description'));
    
        return redirect("/category")->with('success', 'Category added successfully!');
    }
    public function edit($id)
{
    $category=Category::where('id',$id)->get()->first();
// print_r($category);
// exit;
    return view('Category.edit', compact('category'));
}

public function update(Request $request)
{
   
    $request->validate([
        'name' => 'required|string|max:255|unique:categories,name,' . $request->id,
        // 'description' => 'nullable|string|max:1000',
    ]);

    Category::where('id',$request->id)->update([
        'name' => $request->name,
        // 'description' => $request->id,
    ]);

    return redirect("/category")->with('success', 'Category updated successfully!');
}

public function destroy(Request $request)
{
    Category::where('id',$request->id)->delete();
    return redirect("/category")->with('success', 'Category deleted successfully!');
}

}
