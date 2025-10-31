<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class SellerController extends Controller
{
    public function index1()
    {
        return view('seller.dashboard'); // create this Blade view
    }
    public function index()
    {
         // Load all products with their category & subcategory
         $products = Product::with(['category', 'subcategory'])
         ->get()
         ->groupBy([
             fn($product) => $product->category->name ?? 'Uncategorized',
             fn($product) => $product->subcategory->name ?? 'Unsubcategorized',
         ]);

        return view('seller.dashboard', compact('products'));
    }
}
