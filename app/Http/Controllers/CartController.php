<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class CartController extends Controller
{
    // Add item to cart
    public function add(Request $request)
    {
        $shopid = auth()->id(); // Or $request->shopid
        $product = $request->only('id','name','price','quantity','attributes');

        Cart::session($shopid)->add([
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $product['quantity'],
            'attributes' => $product['attributes'] ?? []
        ]);

        return response()->json(['success' => true, 'message' => 'Added to cart!']);
    }

    // Update quantity
    public function update(Request $request)
    {
        $shopid = auth()->id(); // Or $request->shopid

        Cart::session($shopid)->update($request->id, [
            'quantity' => [
                'relative' => false,
                'value' => $request->quantity
            ]
        ]);

        return response()->json(['success' => true]);
    }

    // Remove item
    public function remove(Request $request)
    {
        $shopid = auth()->id(); // Or $request->shopid
        Cart::session($shopid)->remove($request->id);

        return response()->json(['success' => true]);
    }
}
