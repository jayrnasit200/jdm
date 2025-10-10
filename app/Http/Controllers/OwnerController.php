<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function index()
    {
        return view('owner.dashboard'); // create this Blade view
    }
    
}
