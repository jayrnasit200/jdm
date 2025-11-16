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
            $comments = $request->input('comments_about_your_order');
            $cartData = $request->input('cart_data');

            if (!$cartData || count($cartData) === 0) {
                return response()->json(['success' => false, 'message' => 'Cart is empty']);
            }

            $order = Order::create([
                'sellar_id' => auth()->id(),
                'shop_id' => $shopid,
                'invoice_number' => 'INV-' . time(),
                'comments_about_your_order' => $comments,
                'total' => collect($cartData)->sum(fn($item) => $item['price'] * $item['quantity']),
                'payment_status' => 'padding',
            ]);

            foreach ($cartData as $item) {
                OrderProduct::create([
                    'orders_id' => $order->id,
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
        $fileName = "Invoice_{$shopRef}_{$dateTime}.pdf";

        return $pdf->download($fileName);
    }

    public function sendEmail($id)
    {
        $order = Order::with(['orderProducts.product', 'shop'])->findOrFail($id);

        $shopRef = $order->shop->ref ?? 'shop';
        $dateTime = now()->format('Ymd_His');

        $pdfFileName = "Invoice_{$shopRef}_{$dateTime}.pdf";
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

        Mail::raw('Please find your Invoice (PDF) and Excel attached.', function ($message) use ($emails, $pdfPath, $pdfFileName, $excelPath, $excelFileName) {
            $message->to($emails) // <- use the correct variable here
                ->subject('Your Invoice from JDM Distributors')
                ->attach($pdfPath, [
                    'as' => $pdfFileName,
                    'mime' => 'application/pdf'
                ])
                ->attach($excelPath, [
                    'as' => $excelFileName,
                    'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                ]);
        });


        return back()->with('success', "Invoice and Excel sent successfully !");
    }


}
