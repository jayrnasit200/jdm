<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    // Show all shops
    public function index()
    {
        $shops = Shop::all();
        return view('shops.index', compact('shops'));
    }

    // Show create form
    public function create()
    {
        return view('shops.create');
    }

    // Store new shop
    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'ref' => 'nullable|string|max:100',
            'shopname' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'postcode' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'Vat' => 'nullable|string|max:50',
            'Name_staff' => 'nullable|string|max:255',
            'Staffnumber1' => 'nullable|string|max:20',
            'Staffnumber2' => 'nullable|string|max:20',
        ]);

        Shop::create([
            'company_name' => $request->company_name,
            'ref' => $request->ref,
            'shopname' => $request->shopname,
            'address' => $request->address,
            'city' => $request->city,
            'postcode' => $request->postcode,
            'email' => $request->email,
            'phone' => $request->phone,
            'Vat' => $request->Vat,
            'Name_staff' => $request->Name_staff,
            'Staffnumber1' => $request->Staffnumber1,
            'Staffnumber2' => $request->Staffnumber2,
        ]);

        return redirect()->route('shops.index')->with('success', 'Shop added successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        $shop = Shop::findOrFail($id);
        return view('shops.edit', compact('shop'));
    }

    // Update shop
    public function update(Request $request, $id)
    {
        $shop = Shop::findOrFail($id);

        $request->validate([
            'company_name' => 'required|string|max:255',
            'ref' => 'nullable|string|max:100',
            'shopname' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'postcode' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'Vat' => 'nullable|string|max:50',
            'Name_staff' => 'nullable|string|max:255',
            'Staffnumber1' => 'nullable|string|max:20',
            'Staffnumber2' => 'nullable|string|max:20',
        ]);

        $shop->update([
            'company_name' => $request->company_name,
            'ref' => $request->ref,
            'shopname' => $request->shopname,
            'address' => $request->address,
            'city' => $request->city,
            'postcode' => $request->postcode,
            'email' => $request->email,
            'phone' => $request->phone,
            'Vat' => $request->Vat,
            'Name_staff' => $request->Name_staff,
            'Staffnumber1' => $request->Staffnumber1,
            'Staffnumber2' => $request->Staffnumber2,
        ]);

        return redirect()->route('shops.index')->with('success', 'Shop updated successfully!');
    }

    // Delete shop
    public function destroy(Shop $shop)
    {
        $shop->delete();
        return redirect()->route('shops.index')->with('success', 'Shop deleted successfully.');
    }
}
