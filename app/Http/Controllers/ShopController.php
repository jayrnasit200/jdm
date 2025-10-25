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
             'shopname' => 'required',
             'address' => 'required',
             'city' => 'required',
             'postcode' => 'required',
             'email' => 'required|email',
             'phone' => 'required',
         ]);
 
         Shop::create($request->all());
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
     
         $shop->update([
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
