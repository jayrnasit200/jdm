<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Product;

class OrderController extends Controller
{
    public function index() {
        $shops = Shop::all();

        return view('shops.listshop', compact('shops'));
    }
    public function show($id)
    {
        $shop = Shop::with('orders')->findOrFail($id);

        $totalSales = $shop->orders->sum('total');
        $pendingSales = $shop->orders->where('payment_status', 'padding')->sum('total');
        $yearSales = $shop->orders
            ->whereBetween('created_at', [now()->startOfYear(), now()->endOfYear()])
            ->sum('total');

        return view('shops.show', compact('shop', 'totalSales', 'pendingSales', 'yearSales'));
    }
    public function productorder($shopid)
    {
        $products = Product::with('category')->get();
        return view('shops.order', compact('shopid','products'));
    }


}
