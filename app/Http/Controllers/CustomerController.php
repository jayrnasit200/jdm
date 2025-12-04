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
            'message'       => 'required|string',
        ]);

        // 2) Where to send the enquiry
        //    - Prefer sys_config('email') if set
        //    - Fallback to your personal email
        $adminEmail = sys_config('email');

        // 3) Build plain-text email body
        $body = "New Trade Enquiry From Website:\n\n"
            . "Business Name: {$data['business_name']}\n"
            . "Email: {$data['email']}\n"
            . "Phone: {$data['phone']}\n\n"
            . "Message:\n{$data['message']}\n";

        // 4) Send email (NO attachments, no undefined variables)
        Mail::raw($body, function ($message) use ($adminEmail) {
            $message->to($adminEmail)
                ->subject('New Trade Enquiry - JDM Distributors');
        });

        // 5) Redirect back with success message
        return back()->with('success', 'Thank you — your enquiry has been sent. We will contact you shortly.');
    }
}
