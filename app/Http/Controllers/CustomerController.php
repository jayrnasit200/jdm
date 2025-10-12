<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        // print_r("hello");
        // exit;
        return view('Customer.dashboard'); // create this Blade view
    }
}
