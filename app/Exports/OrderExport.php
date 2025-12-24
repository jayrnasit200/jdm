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

        $shop = $this->order->shop;

        $data[] = ['ACCOUNTACCOUNT_REF', 'STOCK_CODE', 'QTY_ORDER'];

        foreach ($this->order->orderProducts as $item) {
            $data[] = [
                $shop->ref,
                $item->product->model_number ?? '',
                $item->quantity,
            ];
        }


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
