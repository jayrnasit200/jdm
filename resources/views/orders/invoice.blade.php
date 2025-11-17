<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->invoice_number }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
        }

        .invoice-container {
            background: #ffffff;
            padding: 25px;
            width: 100%;
        }

        .header {
            width: 100%;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header-left { float: left; width: 50%; }
        .header-right { float: right; width: 50%; text-align: right; }
        .logo { width: 150px; margin-bottom: 10px; }
        .invoice-title { font-size: 22px; font-weight: bold; margin-top: 5px; }
        .company-info { font-size: 11px; line-height: 1.4; }

        .clear { clear: both; }

        .details-row { width: 100%; margin-top: 25px; margin-bottom: 25px; }
        .details-box {
            width: 46%;
            border: 1px solid #ccc;
            padding: 10px;
            display: inline-block;
            vertical-align: top;
        }
        .box {
            /* width: 46%; */
            /* border: 1px solid #ccc; */
            padding: 10px;
            display: inline-block;
            vertical-align: top;
        }
        .details-box-right { float: right; }
        .details-table td { padding: 3px 0; }
        .bill-title { font-weight: bold; margin-bottom: 5px; }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 11px;
        }
        table.items th {
            background: #eaeaea;
            font-weight: bold;
            padding: 8px;
            border: 1px solid #ccc;
        }
        table.items td {
            padding: 8px;
            border: 1px solid #ccc;
        }

        .totals {
            width: 35%;
            float: right;
            margin-top: 20px;
        }
        .totals td { padding: 5px; }
        .totals .total-label { text-align: left; }
        .totals .total-value { text-align: right; font-weight: bold; }

        .grand-total td {
            font-size: 14px;
            border-top: 2px solid #000;
            padding-top: 10px;
        }

        .notes {
            margin-top: 40px;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>
</head>

<body>

<div class="invoice-container">

    <!-- HEADER -->
    <div class="header">
        <div class="header-left">
            <img class="logo" src="{{ public_path('assets/jdm_distributors_logo.jpeg') }}">
            <div class="invoice-title">INVOICE</div>
            <div style="font-size: 10px; color: #666;">Page 1 of 1</div>
        </div>

        <div class="header-right company-info">
            <div style="font-size: 14px; font-weight: bold;">{{ sys_config('company_name') }}</div>
            {{ sys_config('address') }}<<br>
            E:  {{ sys_config('email') }} | T:  {{ sys_config('phone') }}<br>
            www.jdmdistributors.co.uk<br>
            <strong>VAT Reg No:  {{ sys_config('vatnum') }}</strong>
        </div>

        <div class="clear"></div>
    </div>


    <!-- BILL TO + INVOICE DETAILS -->
    <div class="details-row">

        <div class="details-box">
            <div class="bill-title">Bill To:</div>
            <strong>{{ $order->shop->company_name ?? 'N/A' }}</strong><br>
            {{ $order->shop->shopname ?? '' }}<br>
            {{ $order->shop->address ?? '' }}<br>
            {{ $order->shop->city ?? '' }}<br>
            {{ $order->shop->postcode ?? '' }}


        </div>

        <div class="details-box details-box-right">
            <table class="details-table" width="100%">
                <tr><td><strong>Invoice No</strong></td><td align="right">{{ $order->invoice_number }}</td></tr>
                <tr><td><strong>Invoice Date</strong></td><td align="right">{{ $order->created_at->format('d/m/Y') }}</td></tr>
                <tr><td><strong>PO No</strong></td><td align="right">{{ $order->po_number ?? 'N/A' }}</td></tr>
                <tr><td><strong>Account Ref</strong></td><td align="right">{{ $order->shop->ref ?? 'N/A' }}</td></tr>
            </table>
        </div>

        <div class="clear"></div>
    </div>


    <!-- ITEMS TABLE -->
    <table class="items">
        <thead>
        <tr>
            <th>Quantity</th>
            <th>`code</th>
            <th>Description</th>
            <th>Unit Price</th>
            <th>Disc</th>
            <th>Net</th>
            <th>VAT%</th>
            <th>VAT</th>
        </tr>
        </thead>

        <tbody>
        @foreach($order->orderProducts as $item)
            @php
                $discount = $item->discount ?? 0;
                $net = ($item->selling_price - $discount) * $item->quantity;
                $vatPercent = $order->Vat ?? 20;
                $vatAmount = $net * ($vatPercent / 100);
            @endphp

            <tr>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->product->model_number }}</td>
                <td>{{ $item->product->name }}</td>

                <td align="right">{{ number_format($item->selling_price, 2) }}</td>
                <td align="right">{{ number_format($discount, 2) }}</td>
                <td align="right">{{ number_format($net, 2) }}</td>
                <td align="right">{{ number_format($vatPercent, 2) }}</td>
                <td align="right">{{ number_format($vatAmount, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>


    <!-- TOTALS -->
    <table class="totals">
        <tr>
            <td class="total-label">Total Discount</td>
            <td class="total-value">£ {{ number_format($order->discount, 2) }}</td>
        </tr>
        <tr>
            <td class="total-label">Total Net Amount</td>
            <td class="total-value">£ {{ number_format($order->net_total, 2) }}</td>
        </tr>
        <tr>
            <td class="total-label">Total Tax Amount</td>
            <td class="total-value">£ {{ number_format($order->Vat, 2) }}</td>
        </tr>

        <tr class="grand-total">
            <td class="total-label">Invoice Total</td>
            <td class="total-value">£ {{ number_format($order->total, 2) }}</td>
        </tr>
    </table>

    <div class="clear"></div>


    <!-- FOOTER NOTES -->
    <div class="notes">
        Title of goods on the invoice does not pass to the buyer until invoice is fully paid.<br>
        <strong>Important:</strong> Importers details & nutritional/allergen info supplied for all products.
        You must place labels on all products sold to the final consumer.
    </div>

</div>
<div class="box">
    <!-- ⭐ Added Comments Here -->
    @if(!empty($order->comments_about_your_order))
    <div style="margin-top: 10px; font-size: 11px;">
        <strong>Order Comments:</strong><br>
        {{ $order->comments_about_your_order }}
    </div>
@endif
</div>
</body>
</html>
