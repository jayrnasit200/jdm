<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
        }

        .report-container {
            background: #ffffff;
            padding: 20px 25px;
            width: 100%;
        }

        .header {
            width: 100%;
            margin-bottom: 15px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 10px;
        }

        .header-left {
            float: left;
            width: 60%;
        }

        .header-right {
            float: right;
            width: 40%;
            text-align: right;
        }

        .logo {
            width: 140px;
            margin-bottom: 6px;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .sub-title {
            font-size: 11px;
            color: #6b7280;
        }

        .clear {
            clear: both;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
        }

        thead {
            background: #e9efff;
        }

        th, td {
            padding: 6px 5px;
            border: 1px solid #d1d5db;
            font-size: 10px;
        }

        th {
            text-align: left;
            font-weight: 600;
        }

        td.text-right, th.text-right {
            text-align: right;
        }

        .footer {
            margin-top: 20px;
            font-size: 9px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
        }
    </style>
</head>
<body>
<div class="report-container">

    {{-- Header --}}
    <div class="header">
        <div class="header-left">
            <img class="logo" src="{{ public_path('assets/jdm_distributors_logo.jpeg') }}" alt="JDM Logo">
            <div class="title">{{ $title }}</div>
            @if($start && $end)
                <div class="sub-title">
                    Period: {{ $start->format('d M Y') }} – {{ $end->format('d M Y') }}
                </div>
            @else
                <div class="sub-title">
                    Period: All Time
                </div>
            @endif
        </div>
        <div class="header-right">
            <div style="font-weight:600; font-size:12px;">{{ sys_config('company_name') }}</div>
            <div style="font-size:10px;">
                {{ sys_config('address') }}<br>
                E: {{ sys_config('email') }} | T: {{ sys_config('phone') }}<br>
                www.jdmdistributors.co.uk
            </div>
            <div style="font-size:10px; margin-top:5px;">
                Generated: {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>
        <div class="clear"></div>
    </div>

    {{-- Table --}}
    <table>
        <thead>
        <tr>
            <th style="width:40px;">#</th>
            <th style="width:90px;">Product Code</th>
            <th>Product Name</th>
            {{-- <th class="text-right" style="width:60px;">Price (£)</th>
            <th style="width:50px;">VAT</th> --}}
            <th style="width:70px;" class="text-right">Qty Sold</th>
            {{-- <th style="width:80px;" class="text-right">Sales (£)</th> --}}
        </tr>
        </thead>
        <tbody>
        @php
            $grandQty   = 0;
            $grandSales = 0;
        @endphp
        @foreach($products as $index => $product)
            @php
                $qty   = (int) $product->total_sold;
                $price = (float) $product->price;
                $lineSales = $qty * $price;

                $grandQty   += $qty;
                $grandSales += $lineSales;
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $product->model_number }}</td>
                <td>{{ $product->name }}</td>
                {{-- <td class="text-right">{{ number_format($price, 2) }}</td> --}}
                {{-- <td>{{ $product->vat === 'yes' ? 'Yes' : 'No' }}</td> --}}
                <td class="text-right">{{ $qty }}</td>
                {{-- <td class="text-right">{{ number_format($lineSales, 2) }}</td> --}}
            </tr>
        @endforeach

        {{-- Totals row --}}
        {{-- <tr>
            <td colspan="5" class="text-right" style="font-weight:bold;">TOTAL</td>
            <td class="text-right" style="font-weight:bold;">{{ $grandQty }}</td>
            <td class="text-right" style="font-weight:bold;">£{{ number_format($grandSales, 2) }}</td>
        </tr> --}}
        </tbody>
    </table>

    <div class="footer">
        Report includes all products. Products with no sales in the selected period are shown with Qty Sold = 0.
    </div>
</div>
</body>
</html>
