<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderProduct;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\ShopProductPrice;

class OrderController extends Controller
{
    public function index() {
        $shops = Shop::all();
        return view('shops.listshop', compact('shops'));
    }



        public function show($id)
        {
            // Load the shop and its orders only
            $shop = Shop::with('orders')->findOrFail($id);

            // Totals based on this shop's orders
            $totalSales = $shop->orders->sum('total');
            $pendingSales = $shop->orders->where('payment_status', 'Pending')->sum('total');
            $yearSales = $shop->orders
                ->whereBetween('created_at', [now()->startOfYear(), now()->endOfYear()])
                ->sum('total');

            return view('shops.show', compact('shop', 'totalSales', 'pendingSales', 'yearSales'));
        }


    // public function productorder($shopid)
    // {
    //     $products = Product::with('category')->get();
    //     return view('shops.order', compact('shopid','products'));
    // }
    public function productorder($shopid)
    {
        $products = Product::with('category')
            ->leftJoin('shop_product_prices', function ($join) use ($shopid) {
                $join->on('shop_product_prices.product_id', '=', 'products.id')
                     ->where('shop_product_prices.shop_id', '=', $shopid);
            })
            ->select(
                'products.*',
                DB::raw('COALESCE(shop_product_prices.price, products.price) as effective_price')
            )
            ->get();

        return view('shops.order', compact('shopid','products'));
    }
    public function placeOrder(Request $request, $shopid)
    {
        try {
            $comments = $request->input('comments_about_your_order');
            $cartData = $request->input('cart_data');

            // If you send JSON via fetch: cart_data is already an array
            // If you ever send JSON string instead, uncomment this:
            // if (is_string($cartData)) {
            //     $cartData = json_decode($cartData, true) ?? [];
            // }

            if (!$cartData || !is_array($cartData) || count($cartData) === 0) {
                return response()->json(['success' => false, 'message' => 'Cart is empty']);
            }

            $vatRate = sys_config('vat') ?? 0; // e.g. 20

            $orderTotal = 0;
            $orderItems = [];

            foreach ($cartData as $item) {
                // basic safety
                if (empty($item['id'])) {
                    continue;
                }

                $product = Product::find($item['id']);
                if (!$product) {
                    continue; // product deleted or invalid
                }

                $qty = (int)($item['quantity'] ?? 1);
                if ($qty < 1) {
                    $qty = 1;
                }

                // Base price (EX VAT) – use frontend edited price if present, else DB price
                $basePriceExVat = isset($item['price'])
                    ? (float)$item['price']
                    : (float)$product->price;

                // Check VAT from DB
                $vatFlag = ($product->vat === 'yes');

                if ($vatFlag) {
                    $unitVat   = $basePriceExVat * $vatRate / 100;
                    $unitGross = $basePriceExVat + $unitVat; // price including VAT
                } else {
                    $unitVat   = 0;
                    $unitGross = $basePriceExVat;            // same as ex-VAT
                }

                $lineTotal = $unitGross * $qty;
                $orderTotal += $lineTotal;

                // keep item data to insert after order is created
                $orderItems[] = [
                    'products_id' => $product->id,
                    'selling_price' => $unitGross,                 // 👈 unit price INCLUDING VAT
                    'quantity' => $qty,
                    'discount' => $item['discount'] ?? 0,          // if you send discount
                    // optional extra fields if your table has them:
                    // 'price_ex_vat' => $basePriceExVat,
                    // 'vat_rate' => $vatFlag ? $vatRate : 0,
                    // 'vat_amount_per_unit' => $unitVat,
                    // 'line_total' => $lineTotal,
                ];
            }

            if (empty($orderItems)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid products in cart',
                ]);
            }

            // Create order with TOTAL including VAT
            $order = Order::create([
                'sellar_id' => auth()->id(),
                'shop_id' => $shopid,
                'invoice_number' => 'INV-' . time(),
                'comments_about_your_order' => $comments,
                'total' => round($orderTotal, 2),      // 👈 total INCLUDING VAT
                'payment_status' => 'Pending',
            ]);

            // Insert order_products rows
            foreach ($orderItems as $row) {
                $row['orders_id'] = $order->id;
                OrderProduct::create($row);
            }

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
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

    public function exportOrder($id)
    {
        $order = Order::with('orderProducts.product', 'shop')->findOrFail($id);

        $shopRef = $order->shop->ref ?? 'shop';
        $dateTime = now()->format('Ymd_His');
        $fileName = $shopRef . '_' . $dateTime . '.xlsx';

        return Excel::download(new OrderExport($order), $fileName);
    }

    public function generateInvoice($id)
    {
        $order = Order::with(['orderProducts.product', 'shop'])->findOrFail($id);

        $pdf = Pdf::loadView('orders.invoice', [
            'order' => $order,
            'shop' => $order->shop,
            'orderProducts' => $order->orderProducts
        ])->setPaper('a4', 'portrait');

        $shopRef = $order->shop->ref ?? 'shop';
        $dateTime = now()->format('Ymd_His');
        $fileName = "order_{$shopRef}_{$dateTime}.pdf";

        return $pdf->download($fileName);
    }

    public function sendEmail($id)
    {
        $order = Order::with(['orderProducts.product', 'shop'])->findOrFail($id);

        $shopRef = $order->shop->ref ?? 'shop';
        $dateTime = now()->format('Ymd_His');

        $pdfFileName = "order_{$shopRef}_{$dateTime}.pdf";
        $excelFileName = "{$shopRef}_{$dateTime}.xlsx";

        // ---------------- PDF ----------------
        $pdf = Pdf::loadView('orders.invoice', [
            'order' => $order,
            'shop' => $order->shop,
            'orderProducts' => $order->orderProducts
        ])->setPaper('a4', 'portrait');

        // Ensure folder exists
        if (!is_dir(storage_path('app/public'))) {
            mkdir(storage_path('app/public'), 0755, true);
        }

        $pdfPath = storage_path("app/public/{$pdfFileName}");
        $pdf->save($pdfPath);

        // ---------------- Excel ----------------
        $excelPath = storage_path("app/public/{$excelFileName}");
        Excel::store(new OrderExport($order), $excelFileName, 'public');

        if (!file_exists($excelPath)) {
            return back()->with('error', "Excel file not found at: {$excelPath}");
        }

        // ---------------- Email ----------------
        // Send to your desired email address
        // $email = 'r9638527415@gmail.com';
        $emails = sys_config('email');

        Mail::raw('Please find your Order (PDF) and Excel attached.', function ($message) use ($emails, $pdfPath, $pdfFileName, $excelPath, $excelFileName) {
            $message->to($emails) // <- use the correct variable here
                ->subject('Your Order from JDM Distributors')
                ->attach($pdfPath, [
                    'as' => $pdfFileName,
                    'mime' => 'application/pdf'
                ])
                ->attach($excelPath, [
                    'as' => $excelFileName,
                    'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                ]);
        });


        return back()->with('success', "Order and Excel sent successfully !");
    }

// Show manage order page
public function manageOrder($id)
{
    $order = Order::with('orderProducts.product')->findOrFail($id);
    $products = Product::all(); // To allow adding new products
    return view('orders.manage', compact('order', 'products'));
}

// Update payment status
public function updatePaymentStatus(Request $request, $id)
{
    $order = Order::findOrFail($id);
    $order->payment_status = $request->payment_status;
    $order->save();
    return back()->with('success', 'Payment status updated!');
}

public function uploadInvoice(Request $request, $id)
{
    $request->validate([
        'invoice_image' => 'required|file|mimes:jpg,jpeg,png,webp,heic,pdf|max:5120', // 5 MB
    ]);

    $order = Order::findOrFail($id);

    // 🔁 Delete old invoice file if it exists
    if (!empty($order->invoice) && Storage::disk('public')->exists($order->invoice)) {
        Storage::disk('public')->delete($order->invoice);
    }

    if ($request->hasFile('invoice_image')) {

        $file      = $request->file('invoice_image');
        $extension = $file->getClientOriginalExtension();

        // Nice file name: INV_<invoice_number>_timestamp.ext
        $fileName = 'INV_' . $order->invoice_number . '_' . time() . '.' . $extension;

        // This returns a relative path like: invoices/INV_XXXX_1234567890.pdf
        $path = $file->storeAs('invoices', $fileName, 'public');

        // ✅ Save ONLY relative path in DB
        $order->invoice = $path;
        $order->save();
    }

    // If request is AJAX, return JSON with both path + usable URL
    if ($request->expectsJson()) {
        return response()->json([
            'success'      => true,
            'invoice_path' => $order->invoice,                        // e.g. invoices/INV_123.pdf
            'invoice_url'  => asset('storage/' . $order->invoice),    // full URL for frontend
            'message'      => 'Order uploaded successfully!',
        ]);
    }

    // Normal redirect
    return back()->with('success', 'Order uploaded successfully!');
}
public function addProduct(Request $request, $id)
{
    $data = $request->validate([
        'product_id' => 'required|integer|exists:products,id',
        'quantity'   => 'required|integer|min:1',
    ]);

    $order   = Order::findOrFail($id);
    $product = Product::findOrFail($data['product_id']);

    OrderProduct::create([
        'orders_id'     => $order->id,
        'products_id'   => $product->id,
        'selling_price' => $product->price,
        'discount'      => 0,
        'quantity'      => $data['quantity'],
    ]);

    $this->refreshOrderTotals($order);

    return back()->with('success', 'Product added to order.');
}


public function removeProductFromOrder(Request $request, Order $order, $productId)
{
    $line = OrderProduct::where('orders_id', $order->id)
        ->where('products_id', $productId)
        ->first();

    if ($line) {
        $line->delete();
        $this->refreshOrderTotals($order);
    }

    if ($request->expectsJson()) {
        return response()->json([
            'success'     => true,
            'order_total' => $order->total,
        ]);
    }

    return back()->with('success', 'Product removed from order.');
}

public function updateItem(Request $request, Order $order)
{
    $data = $request->validate([
        'product_id' => 'required|integer',
        'price'      => 'required|numeric|min:0',
        'quantity'   => 'required|integer|min:1',
    ]);

    // find the order line
    $line = OrderProduct::where('orders_id', $order->id)
        ->where('products_id', $data['product_id'])
        ->firstOrFail();

    $line->selling_price = $data['price'];
    $line->quantity      = $data['quantity'];
    $line->save();

    // recalc totals on the parent order
    $this->refreshOrderTotals($order);

    $lineTotal = $line->selling_price * $line->quantity;

    return response()->json([
        'success'     => true,
        'line_total'  => $lineTotal,
        'order_total' => $order->total,
    ]);
}

private function refreshOrderTotals(Order $order): void
{
    $order->load('orderProducts'); // ensure relation loaded

    $total = 0;
    $totalDiscount = 0;

    foreach ($order->orderProducts as $line) {
        $unitPrice    = (float) $line->selling_price;
        $unitDiscount = (float) ($line->discount ?? 0);
        $qty          = (int) ($line->quantity ?? 0);

        $lineNet = max($unitPrice - $unitDiscount, 0) * $qty;

        $totalDiscount += $unitDiscount * $qty;
        $total         += $lineNet;
    }

    $order->discount = $totalDiscount;
    $order->total    = $total;

    $order->save();
}

}
