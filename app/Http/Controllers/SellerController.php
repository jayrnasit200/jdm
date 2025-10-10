<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function index()
    {
        return view('Seller.dashboard'); // create this Blade view
    }
}
