<?php

namespace App\Http\Controllers;

use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\Models\ShopProductPrice;

use App\Models\Order;                          // 👈 ADD THIS
use Barryvdh\DomPDF\Facade\Pdf;               // 👈 make sure this is here
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;   // 👈 ADD THIS LINE

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
    // public function checkout($shopid)
    // {
    //     return view('shops.checkout', compact('shopid'));
    // }
    public function checkout($shopid)
    {
        // ... your existing logic (load shop, etc.)

        $specialPrices = ShopProductPrice::where('shop_id', $shopid)
            ->pluck('price', 'product_id'); // [product_id => price]

        return view('shops.checkout', [   // use your actual view name
            'shopid'        => $shopid,
            'specialPrices' => $specialPrices,
            // plus anything else you already pass
        ]);
    }
    public function sendToWhatsappGroup($id)
    {
        $order = Order::with('shop', 'orderProducts.product')->findOrFail($id);

        // 1) Generate PDF & store publicly accessible
        $pdf = Pdf::loadView('orders.invoice', [
            'order'         => $order,
            'shop'          => $order->shop,
            'orderProducts' => $order->orderProducts,
        ])->setPaper('a4', 'portrait');

        $fileName = 'Invoice_'.$order->invoice_number.'.pdf';
        $pdfPath  = 'invoices/'.$fileName;     // storage/app/public/invoices/...
        \Storage::disk('public')->put($pdfPath, $pdf->output());

        // Public URL for WhatsApp API (must be reachable from internet)
        $publicUrl = asset('storage/'.$pdfPath);

        // 2) WhatsApp Cloud API config
        $token          = config('services.whatsapp.token');          // put in .env
        $phoneNumberId  = config('services.whatsapp.phone_number_id'); // from Meta
        $groupTo        = config('services.whatsapp.group_id');        // group / phone target

        // 3) Send document message
        $response = Http::withToken($token)
            ->post("https://graph.facebook.com/v21.0/{$phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'to'                => $groupTo,
                'type'              => 'document',
                'document'          => [
                    'link'     => $publicUrl,
                    'filename' => $fileName,
                ],
            ]);

        if ($response->failed()) {
            return back()->with('error', 'Failed to send on WhatsApp: '.$response->body());
        }

        return back()->with('success', 'Invoice PDF sent to WhatsApp group!');
    }
}
