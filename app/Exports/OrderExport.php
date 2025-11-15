<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class OrderExport implements FromArray, WithEvents
{
    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function array(): array
    {
        $data = [];

        // Shop details
        $shop = $this->order->shop;
        $data[] = ['Shop Name', $shop->name ?? ''];
        $data[] = ['Company Name', $shop->company_name ?? ''];
        $data[] = ['Reference', $shop->ref ?? ''];
        $data[] = []; // empty row

        // Order details
        $data[] = ['Order ID', '#'.$this->order->id];
        $data[] = ['Invoice Number', $this->order->invoice_number];
        $data[] = ['Total', '£'.number_format($this->order->total, 2)];
        $data[] = ['Payment Status', ucfirst($this->order->payment_status)];
        $data[] = ['Created At', $this->order->created_at->format('d M Y, H:i')];
        $data[] = []; // empty row

        // Product table header
        $data[] = ['Product', 'Model Number', 'Price (£)', 'Quantity', 'Subtotal (£)'];

        // Products
        foreach ($this->order->orderProducts as $item) {
            $data[] = [
                $item->product->name ?? 'Deleted Product',
                $item->product->model_number ?? '',
                $item->selling_price,
                $item->quantity,
                $item->selling_price * $item->quantity,
            ];
        }

        // Grand total at bottom
        $data[] = [];
        $data[] = ['Grand Total', '', '', '', $this->order->total];

        return $data;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Bold headers
                $event->sheet->getStyle('A10:E10')->getFont()->setBold(true); // Product header
                $event->sheet->getStyle('A1:A3')->getFont()->setBold(true);   // Shop details
                $event->sheet->getStyle('A5:A9')->getFont()->setBold(true);   // Order details
            },
        ];
    }
}
