<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Order;
use App\Models\OrderProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SellerController extends Controller
{

    public function index()
    {
        $now = now();

        // 🗓 This Week
        $ordersWeek = Order::with('shop')
            ->whereBetween('created_at', [
                $now->copy()->startOfWeek(),
                $now->copy()->endOfWeek(),
            ])->get();

        // 🗓 This Month
        $ordersMonth = Order::with('shop')
            ->whereBetween('created_at', [
                $now->copy()->startOfMonth(),
                $now->copy()->endOfMonth(),
            ])->get();

        // 🗓 This Year
        $ordersYear = Order::with('shop')
            ->whereBetween('created_at', [
                $now->copy()->startOfYear(),
                $now->copy()->endOfYear(),
            ])->get();

        // 🔹 helper for stats
        $stats = function ($orders) {
            $count = $orders->count();
            $total = $orders->sum('total');

            return [
                'orders' => $count,
                'sales'  => $total,
                'avg'    => $count > 0 ? ($total / $count) : 0,
            ];
        };

        $weekStats  = $stats($ordersWeek);
        $monthStats = $stats($ordersMonth);
        $yearStats  = $stats($ordersYear);

        $totalShops = Shop::count();

        // 🔹 Sales chart data (week)
        $weekChartData = $ordersWeek
            ->groupBy(function ($o) {
                return $o->created_at->format('D d M');
            })
            ->map(fn($g) => $g->sum('total'))
            ->sortKeys();

        $weekChart = [
            'labels' => $weekChartData->keys()->values(),
            'data'   => $weekChartData->values(),
        ];

        // 🔹 Sales chart data (month)
        $monthChartData = $ordersMonth
            ->groupBy(fn($o) => $o->created_at->format('d M'))
            ->map(fn($g) => $g->sum('total'))
            ->sortKeys();

        $monthChart = [
            'labels' => $monthChartData->keys()->values(),
            'data'   => $monthChartData->values(),
        ];

        // 🔹 Sales chart data (year)
        $yearChartData = $ordersYear
            ->groupBy(fn($o) => $o->created_at->format('M Y'))
            ->map(fn($g) => $g->sum('total'))
            ->sortKeys();

        $yearChart = [
            'labels' => $yearChartData->keys()->values(),
            'data'   => $yearChartData->values(),
        ];

        // Default product report for THIS WEEK (for first load)
        $productReportWeek = OrderProduct::selectRaw('products_id, SUM(quantity) as total_qty, SUM(quantity * selling_price) as total_sales')
            ->whereHas('order', function ($q) use ($now) {
                $q->whereBetween('created_at', [
                    $now->copy()->startOfWeek(),
                    $now->copy()->endOfWeek(),
                ]);
            })
            ->with('product')
            ->groupBy('products_id')
            ->orderByDesc('total_qty')
            ->get();

        return view('seller.dashboard', compact(
            'totalShops',
            'ordersWeek',
            'ordersMonth',
            'ordersYear',
            'weekStats',
            'monthStats',
            'yearStats',
            'weekChart',
            'monthChart',
            'yearChart',
            'productReportWeek'
        ));
    }

    // 🔹 AJAX: Product selling report (week / month / year / custom)
    public function productReport(Request $request)
    {
        $range = $request->input('range', 'week');   // week|month|year|custom
        $start = $request->input('start_date');      // for custom
        $end   = $request->input('end_date');

        $now = now();
        $startDate = null;
        $endDate   = null;

        switch ($range) {
            case 'month':
                $startDate = $now->copy()->startOfMonth();
                $endDate   = $now->copy()->endOfMonth();
                break;

            case 'year':
                $startDate = $now->copy()->startOfYear();
                $endDate   = $now->copy()->endOfYear();
                break;

            case 'custom':
                $startDate = $start ? Carbon::parse($start)->startOfDay() : $now->copy()->startOfMonth();
                $endDate   = $end   ? Carbon::parse($end)->endOfDay()   : $now->copy()->endOfMonth();
                break;

            case 'week':
            default:
                $startDate = $now->copy()->startOfWeek();
                $endDate   = $now->copy()->endOfWeek();
                break;
        }

        $rows = OrderProduct::selectRaw('products_id, SUM(quantity) as total_qty, SUM(quantity * selling_price) as total_sales')
            ->whereHas('order', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->with('product')
            ->groupBy('products_id')
            ->orderByDesc('total_qty')
            ->get();

        $data = $rows->map(function ($row) {
            return [
                'product_id'   => $row->products_id,
                'product_name' => $row->product->name ?? 'Unknown Product',
                'model_number' => $row->product->model_number ?? '',
                'total_qty'    => (int) $row->total_qty,
                'total_sales'  => (float) $row->total_sales,
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }
function productsReportPdf(Request $request)
    {
        $range = $request->get('range', 'year');
        $now   = now();

        $titleSuffix = '';
        $start = null;
        $end   = null;

        switch ($range) {
            case 'week':
                $start = $now->copy()->startOfWeek();
                $end   = $now->copy()->endOfWeek();
                $titleSuffix = ' - This Week';
                break;

            case 'month':
                $start = $now->copy()->startOfMonth();
                $end   = $now->copy()->endOfMonth();
                $titleSuffix = ' - This Month';
                break;

            case 'year':
                $start = $now->copy()->startOfYear();
                $end   = $now->copy()->endOfYear();
                $titleSuffix = ' - This Year';
                break;

            case 'custom':
                $startInput = $request->input('start_date');
                $endInput   = $request->input('end_date');

                if ($startInput && $endInput) {
                    $start = Carbon::parse($startInput)->startOfDay();
                    $end   = Carbon::parse($endInput)->endOfDay();
                    $titleSuffix = ' - ' . $start->format('d M Y') . ' to ' . $end->format('d M Y');
                } else {
                    $range = 'all';
                    $titleSuffix = ' - All Time';
                }
                break;

            default:
                $range = 'all';
                $titleSuffix = ' - All Time';
                break;
        }

        // ✅ Get the real order products table name from the model
        $orderProductTable = (new OrderProduct)->getTable();   // e.g. "order_products"

        $productsQuery = Product::query()
            // Join order_products via alias "op"
            ->leftJoin($orderProductTable . ' as op', 'op.products_id', '=', 'products.id')
            // Join orders, but apply date filter INSIDE the join so unsold products still appear
            ->leftJoin('orders', function ($join) use ($start, $end, $range) {
                $join->on('orders.id', '=', 'op.orders_id');

                if ($range !== 'all' && $start && $end) {
                    $join->whereBetween('orders.created_at', [$start, $end]);
                }
            })
            ->select(
                'products.id',
                'products.model_number',
                'products.name',
                'products.price',
                'products.vat',
                'products.categories_id'
            )
            // If there are no matching orders in that period => SUM(NULL) => 0 via COALESCE
            ->selectRaw('COALESCE(SUM(op.quantity), 0) as total_sold')
            ->groupBy(
                'products.id',
                'products.model_number',
                'products.name',
                'products.price',
                'products.vat',
                'products.categories_id'
            )
            ->orderByDesc('total_sold')
            ->orderBy('products.name');

        $products = $productsQuery->get();

        $title = 'Product Sales Report' . $titleSuffix;

        $pdf = Pdf::loadView('seller.products_report_pdf', [
            'products' => $products,
            'range'    => $range,
            'title'    => $title,
            'start'    => $start,
            'end'      => $end,
        ])->setPaper('a4', 'portrait');

        $fileName = 'product-sales-' . $range . '-' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($fileName);
    }

}
