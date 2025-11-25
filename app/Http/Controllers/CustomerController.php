<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TradeEnquiryMail;

class CustomerController extends Controller
{
    public function index()
    {
        // print_r("hello");
        // exit;
        return view('Customer.dashboard'); // create this Blade view
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'business_name' => 'required|string|max:255',
            'email'         => 'required|email',
            'phone'         => 'nullable|string|max:50',
            'message'       => 'required|string|max:5000',
        ]);

        // Send email to you
        Mail::to('jinfo@jdm-distributors.co.uk')->send(
            new TradeEnquiryMail($data)
        );

        return back()->with('success', 'Thank you – your enquiry has been sent. We will contact you shortly.');
    }
}
