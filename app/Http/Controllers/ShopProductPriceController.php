<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Product;
use App\Models\ShopProductPrice;
use Illuminate\Http\Request;

class ShopProductPriceController extends Controller
{

    public function create()
    {
        $shops    = Shop::orderBy('shopname')->get();
        $products = Product::orderBy('name')->get();

        // 🔹 Load existing special prices with relationships
        $prices = ShopProductPrice::with(['shop', 'product'])
            ->orderByDesc('id')
            ->get();

        return view('seller.shop_prices.create', compact('shops', 'products', 'prices'));
    }


    // Save / update price
    public function store(Request $request)
    {
        $data = $request->validate([
            'shop_id'    => 'required|exists:shops,id',
            'product_id' => 'required|exists:products,id',
            'price'      => 'required|numeric|min:0',
        ]);

        // If row exists, update; else create
        ShopProductPrice::updateOrCreate(
            [
                'shop_id'    => $data['shop_id'],
                'product_id' => $data['product_id'],
            ],
            [
                'price'      => $data['price'],
            ]
        );

        return back()->with('success', 'Special price saved for this shop & product.');
    }
}
