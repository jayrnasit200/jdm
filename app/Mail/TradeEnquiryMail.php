<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TradeEnquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;

    /**
     * Create a new message instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this
            ->subject('New Trade Enquiry From Website')
            ->view('emails.trade_enquiry');
    }
}
