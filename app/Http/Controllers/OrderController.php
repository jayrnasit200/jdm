<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderProduct;

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
    public function placeOrder(Request $request, $shopid)
    {
        try {
            // Decode cart_data properly (JSON)
            $cartData = $request->input('cart_data');

            if (!$cartData || count($cartData) === 0) {
                return response()->json(['success' => false, 'message' => 'Cart is empty']);
            }

            $order = Order::create([
                'sellar_id' => auth()->id(),
                'shop_id' => $shopid,
                'invoice_number' => 'INV-' . time(),
                'total' => collect($cartData)->sum(fn($item) => $item['price'] * $item['quantity']),
                'payment_status' => 'padding',
            ]);

            // This is fine
            $orderId = $order->id; // ✅ works now

            // Save products
            foreach ($cartData as $item) {
                OrderProduct::create([
                    'orders_id' => $orderId,
                    'products_id' => $item['id'],
                    'selling_price' => $item['price'],
                    'discount' => $item['discount'] ?? 0,
                    'quantity' => $item['quantity'] ?? 1,
                ]);
            }

            return response()->json([
                'success' => true,
                'order_id' => $order->id
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function orderDetails($id)
    {
        $order = Order::with('orderProducts.product')->find($id);

        if (!$order) {
            return redirect()->back()->with('error', 'Order not found!');
        }

        return view('orders.details', [
            'order' => $order,
            'orderProducts' => $order->orderProducts
        ]);
    }


}
