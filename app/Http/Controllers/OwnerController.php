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


}
