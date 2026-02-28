<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Shop;
use App\Models\OrderProduct;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;
use Carbon\Carbon;

class OwnerController extends Controller
{
    // Owner dashboard – simple stats
    public function index()
    {
        // existing totals…
        $totalSellers    = User::where('role', 'seller')->count();
        $totalCustomers  = User::where('role', 'customer')->count();
        $totalOrders     = Order::count();
        $totalSales      = Order::sum('total');

        $totalShops      = Shop::count(); // if you have shops
        $salesThisMonth  = Order::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('total');
        $salesThisYear   = Order::whereYear('created_at', now()->year)->sum('total');
        $ordersThisMonth = Order::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();

        $salesToday      = Order::whereDate('created_at', today())->sum('total');
        $salesThisWeek   = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total');
        $avgOrderValue   = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        // Simple example monthly data for the chart (last 12 months)
        $salesByMonthLabels = [];
        $salesByMonthTotals = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $salesByMonthLabels[] = $date->format('M Y');
            $salesByMonthTotals[] = Order::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total');
        }

        return view('owner.dashboard', compact(
            'totalSellers',
            'totalCustomers',
            'totalOrders',
            'totalSales',
            'totalShops',
            'salesThisMonth',
            'salesThisYear',
            'ordersThisMonth',
            'salesToday',
            'salesThisWeek',
            'avgOrderValue',
            'salesByMonthLabels',
            'salesByMonthTotals',
        ));
    }

    // ===== SELLER MANAGEMENT =====

    // List sellers
    public function sellersIndex()
    {
        // Load sellers with permission
        $sellers = User::where('role', 'seller')
    ->with('permission')
    ->orderBy('name')
    ->get();


        return view('owner.sellers.index', compact('sellers'));
    }

    // Show "Add Seller" form
    public function sellersCreate()
    {
        return view('owner.sellers.create');
    }

    // Save new seller
    public function sellersStore(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'seller',
        ]);

        return redirect()
            ->route('owner.sellers.index')
            ->with('success', 'Seller created successfully.');
    }

    public function productReport(Request $request)
{
    $dateFrom = $request->input('date_from');
    $dateTo   = $request->input('date_to');

    $orderProductTable = (new OrderProduct())->getTable(); // uses model's $table
    $ordersTable       = 'orders';
    $productsTable     = 'products';

    $query = DB::table($orderProductTable)
        ->join($ordersTable, "$orderProductTable.orders_id", '=', "$ordersTable.id")
        ->join($productsTable, "$orderProductTable.products_id", '=', "$productsTable.id")
        ->selectRaw("
            $productsTable.id,
            $productsTable.name,
            $productsTable.model_number,
            SUM($orderProductTable.quantity) as total_qty,
            SUM(($orderProductTable.selling_price - IFNULL($orderProductTable.discount, 0)) * $orderProductTable.quantity) as total_earning,
            AVG($orderProductTable.selling_price - IFNULL($orderProductTable.discount, 0)) as avg_price
        ");

    if ($dateFrom) {
        $query->whereDate("$ordersTable.created_at", '>=', $dateFrom);
    }
    if ($dateTo) {
        $query->whereDate("$ordersTable.created_at", '<=', $dateTo);
    }

    $productsReport = $query
        ->groupBy("$productsTable.id", "$productsTable.name")
        ->groupBy('products.id', 'products.name', 'products.model_number')
        ->orderByDesc('total_earning')
        ->get();

    return view('owner.reports.products', compact('productsReport', 'dateFrom', 'dateTo'));
}

    public function updatePermissions(Request $request, User $seller)
    {
        $data = $request->validate([
            'permissions.shop'       => 'nullable|boolean',
            'permissions.products'   => 'nullable|boolean',
            'permissions.categories' => 'nullable|boolean',
            'permissions.discounts'  => 'nullable|boolean',
        ]);

        $perms = $data['permissions'] ?? [];

        $payload = [
            'user_id'    => $seller->id,
            'shop'       => isset($perms['shop']) ? 1 : 0,
            'products'   => isset($perms['products']) ? 1 : 0,
            'categories' => isset($perms['categories']) ? 1 : 0,
            'discounts'  => isset($perms['discounts']) ? 1 : 0,
        ];

        UserPermission::updateOrCreate(
            ['user_id' => $seller->id],
            $payload
        );

        return back()->with('success', 'Permissions updated for ' . $seller->name);
    }
    // public function shopBalanceReport()
    // {
    //     $shopsReport = DB::table('shops')
    //         ->leftJoin('orders', 'orders.shop_id', '=', 'shops.id')
    //         ->select(
    //             'shops.id as shop_id',
    //             'shops.company_name',
    //             'shops.shopname',
    //             'shops.ref',

    //             DB::raw('COUNT(orders.id) as total_orders'),

    //             DB::raw('SUM(COALESCE(orders.total,0)) as total_sales'),

    //             DB::raw("
    //                 SUM(
    //                     CASE
    //                         WHEN orders.payment_status = 'success'
    //                         THEN COALESCE(orders.total,0)
    //                         ELSE 0
    //                     END
    //                 ) as total_paid
    //             "),

    //             DB::raw("
    //                 SUM(
    //                     CASE
    //                         WHEN orders.payment_status != 'success'
    //                         THEN COALESCE(orders.total,0)
    //                         ELSE 0
    //                     END
    //                 ) as total_due
    //             "),

    //             DB::raw('MAX(orders.created_at) as last_order_date')
    //         )
    //         ->groupBy(
    //             'shops.id',
    //             'shops.company_name',
    //             'shops.shopname',
    //             'shops.ref'
    //         )
    //         ->orderByDesc('total_due')
    //         ->get();

    //     // Summary cards
    //     $grandTotalSales = $shopsReport->sum('total_sales');
    //     $grandTotalPaid  = $shopsReport->sum('total_paid');
    //     $grandTotalDue   = $shopsReport->sum('total_due');

    //     return view('owner.reports.shop-balance', compact(
    //         'shopsReport',
    //         'grandTotalSales',
    //         'grandTotalPaid',
    //         'grandTotalDue'
    //     ));
    // }
    // public function shopDetails(Shop $shop)
    // {
    //     // All orders for this shop
    //     $orders = DB::table('orders')
    //         ->where('shop_id', $shop->id)
    //         ->select('id', 'invoice_number', 'created_at', 'total', 'payment_status')
    //         ->orderByDesc('created_at')
    //         ->get();

    //     // totals (using your rule: paid only if payment_status=success)
    //     $totalSales = $orders->sum('total');
    //     $totalPaid  = $orders->where('payment_status', 'success')->sum('total');
    //     $totalDue   = $totalSales - $totalPaid;

    //     return view('owner.shops.details', compact(
    //         'shop',
    //         'orders',
    //         'totalSales',
    //         'totalPaid',
    //         'totalDue'
    //     ));
    // }

    /**
     * AJAX: return order items for modal
     */
    // public function orderItems(Order $order)
    // {
    //     // Safety: load shop to show info in modal if needed
    //     // Items table: YOU have `orders_products` (based on your query logs)
    //     $items = DB::table('orders_products')
    //         ->join('products', 'orders_products.products_id', '=', 'products.id')
    //         ->where('orders_products.orders_id', $order->id)
    //         ->select(
    //             'products.name as product_name',
    //             'products.model_number as model_number',
    //             'orders_products.quantity as quantity',
    //             'orders_products.selling_price as selling_price',
    //             'orders_products.discount as discount'
    //         )
    //         ->orderBy('products.name')
    //         ->get();

    //     // calculate totals for modal
    //     $rows = $items->map(function ($i) {
    //         $unit = (float)$i->selling_price - (float)($i->discount ?? 0);
    //         $qty  = (int)$i->quantity;
    //         $line = max($unit, 0) * $qty;

    //         return [
    //             'product_name' => $i->product_name,
    //             'model_number' => $i->model_number,
    //             'quantity'     => $qty,
    //             'unit_price'   => round(max($unit, 0), 2),
    //             'line_total'   => round($line, 2),
    //         ];
    //     });

    //     return response()->json([
    //         'order_id'        => $order->id,
    //         'invoice_number'  => $order->invoice_number,
    //         'created_at'      => optional($order->created_at)->format('d M Y, H:i'),
    //         'payment_status'  => $order->payment_status,
    //         'total'           => (float) $order->total,
    //         'items'           => $rows,
    //         'items_count'     => $rows->count(),
    //         'items_total'     => round($rows->sum('line_total'), 2),
    //     ]);
    // }
    public function shopBalanceReport(Request $request)
{
    // SHOW ALL SHOPS even if they have zero orders => LEFT JOIN
    $shopsReport = DB::table('shops')
        ->leftJoin('orders', 'orders.shop_id', '=', 'shops.id')
        ->select(
            'shops.id as shop_id',
            'shops.company_name',
            'shops.shopname',
            'shops.ref',

            DB::raw('COUNT(orders.id) as total_orders'),
            DB::raw('SUM(COALESCE(orders.total,0)) as total_sales'),

            // paid only if payment_status = success
            DB::raw("SUM(CASE WHEN orders.payment_status = 'success' THEN COALESCE(orders.total,0) ELSE 0 END) as total_paid"),

            // due = sales - paid
            DB::raw("SUM(COALESCE(orders.total,0)) - SUM(CASE WHEN orders.payment_status = 'success' THEN COALESCE(orders.total,0) ELSE 0 END) as total_due"),

            DB::raw('MAX(orders.created_at) as last_order_date')
        )
        ->groupBy('shops.id', 'shops.company_name', 'shops.shopname', 'shops.ref')
        ->orderByDesc('total_due')
        ->get();

    $grandTotalSales = $shopsReport->sum('total_sales');
    $grandTotalPaid  = $shopsReport->sum('total_paid');
    $grandTotalDue   = $shopsReport->sum('total_due');

    return view('owner.reports.shop-balance', compact(
        'shopsReport',
        'grandTotalSales',
        'grandTotalPaid',
        'grandTotalDue'
    ));
}

public function shopDetails(Shop $shop)
{
    // Orders list
    $orders = DB::table('orders')
        ->where('shop_id', $shop->id)
        ->select('id', 'invoice_number', 'created_at', 'total', 'payment_status')
        ->orderByDesc('created_at')
        ->get();

    // Totals
    $totalSales = $orders->sum('total');
    $totalPaid  = $orders->where('payment_status', 'success')->sum('total');
    $totalDue   = $totalSales - $totalPaid;

    /*
    |--------------------------------------------------------------------------
    | PRODUCTS SOLD SUMMARY (BY THIS SHOP)
    |--------------------------------------------------------------------------
    */
    $productsSummary = DB::table('orders_products as op')
        ->join('orders as o', 'o.id', '=', 'op.orders_id')
        ->join('products as p', 'p.id', '=', 'op.products_id')
        ->where('o.shop_id', $shop->id)
        ->select(
            'p.id',
            'p.name',
            'p.model_number',
            DB::raw('SUM(op.quantity) as total_qty'),
            DB::raw('SUM((op.selling_price - IFNULL(op.discount,0)) * op.quantity) as total_amount')
        )
        ->groupBy('p.id', 'p.name', 'p.model_number')
        ->orderByDesc('total_qty')
        ->get();

    return view('owner.shops.details', compact(
        'shop',
        'orders',
        'totalSales',
        'totalPaid',
        'totalDue',
        'productsSummary'
    ));
}
public function weekreport(Request $request)  {
     $from = $request->from_date
        ? Carbon::parse($request->from_date)->startOfDay()
        : Carbon::now()->startOfWeek();

    $to = $request->to_date
        ? Carbon::parse($request->to_date)->endOfDay()
        : Carbon::now()->endOfWeek();

    $weeklyOrders = Order::with(['shop','seller'])
        ->whereBetween('created_at', [$from, $to])
        ->orderBy('created_at','desc')
        ->get();

    $sellerSummary = $weeklyOrders
        ->groupBy(fn($o) => $o->seller->name ?? 'Unknown')
        ->map(fn($orders) => [
            'total_orders' => $orders->count(),
            'total_sales' => $orders->sum('total'),
        ]);
        return view('owner.weekreport', compact(
            'weeklyOrders','sellerSummary','from','to'
        ));

// return view('owner.weekreport',  compact('weeklyOrders', 'sellerSummary'));
}
public function weeklyOrders(Request $request)
{
    // Default = This Week
    $from = $request->from_date
        ? Carbon::parse($request->from_date)->startOfDay()
        : Carbon::now()->startOfWeek();

    $to = $request->to_date
        ? Carbon::parse($request->to_date)->endOfDay()
        : Carbon::now()->endOfWeek();

    // Get Orders Between Selected Dates
    $weeklyOrders = Order::with(['shop', 'seller'])
        ->whereBetween('created_at', [$from, $to])
        ->orderBy('created_at', 'desc')
        ->get();

    // Seller Summary
    $sellerSummary = $weeklyOrders
        ->groupBy(function ($order) {
            return $order->seller->name ?? 'Unknown';
        })
        ->map(function ($orders) {
            return [
                'total_orders' => $orders->count(),
                'total_sales' => $orders->sum('total'),
            ];
        });

    return view('owner.weekly-orders', compact(
        'weeklyOrders',
        'sellerSummary',
        'from',
        'to'
    ));
}
public function orderItems(Order $order)
{
    // FIX: order items table is orders_products
    $items = DB::table('orders_products as op')
        ->join('products as p', 'p.id', '=', 'op.products_id')
        ->where('op.orders_id', $order->id)
        ->select(
            'op.id',
            'p.name',
            'p.model_number',
            'op.quantity',
            DB::raw('COALESCE(op.selling_price,0) as selling_price'),
            DB::raw('COALESCE(op.discount,0) as discount'),
            DB::raw('((COALESCE(op.selling_price,0) - COALESCE(op.discount,0)) * op.quantity) as line_total')
        )
        ->orderBy('p.name')
        ->get();

    return response()->json([
        'ok' => true,
        'order_id' => $order->id,
        'invoice_number' => $order->invoice_number,
        'items' => $items,
    ]);
}
}
