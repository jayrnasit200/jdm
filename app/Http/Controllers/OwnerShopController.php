<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Shopaccess;
use App\Models\User;
use Illuminate\Http\Request;

class OwnerShopController extends Controller
{
    public function index()
    {
        $shops = Shop::with('sellers')
            ->orderBy('shopname')
            ->get();

        $sellers = User::where('role', 'seller')
            ->orderBy('name')
            ->get();

        return view('owner.shops.index', compact('shops', 'sellers'));
    }

    public function updateAccess(Request $request, Shop $shop)
    {
        $data = $request->validate([
            'seller_ids'   => 'nullable|array',
            'seller_ids.*' => 'integer|exists:users,id',
        ]);

        $sellerIds = User::where('role', 'seller')
            ->whereIn('id', $data['seller_ids'] ?? [])
            ->pluck('id');

        Shopaccess::where('shop_id', $shop->id)->delete();

        $now = now();
        $rows = $sellerIds->map(function ($sellerId) use ($shop, $now) {
            return [
                'shop_id'    => $shop->id,
                'seller_id'  => $sellerId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->all();

        if ($rows) {
            Shopaccess::insert($rows);
        }

        return redirect()
            ->route('owner.shops.index')
            ->with('success', 'Sellers updated for '.$shop->shopname.'.');
    }
}
