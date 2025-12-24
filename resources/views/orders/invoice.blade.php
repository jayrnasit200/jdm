<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order #{{ $order->invoice_number }}</title>

    <style>
        /* TOP MARGIN REMAINS MINIMAL (4px from the very top edge) */
        @page { margin: 4px 18px 16px 18px; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
            color: #1f2937;
            background: #ffffff;
        }

        /* ===== HEADER (Margin Below Minimized to 1px) ===== */
        .header {
            width: 100%;
            border-radius: 8px;
            padding: 12px 15px;
            box-sizing: border-box;
            background: linear-gradient(90deg, #1e3a8a, #3b82f6);
            color: #ffffff;
            /* REDUCED MARGIN BELOW HEADER TO 1PX */
            margin-bottom: 1px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .header-table { width: 100%; border-collapse: collapse; }
        .h-title { width: 45%; vertical-align: middle; }
        .h-info { width: 55%; vertical-align: middle; text-align: right; font-size: 10px; line-height: 1.3; }

        .invoice-title {
            font-size: 20px;
            font-weight: 900;
            margin: 0;
            line-height: 1;
            letter-spacing: .5px;
        }
        .invoice-subtitle {
            margin-top: 5px;
            font-size: 11px;
            opacity: 0.95;
            line-height: 1.2;
        }

        .company-name { font-size: 12px; font-weight: 900; margin-bottom: 3px; }
        .company-line { line-height: 1.35; opacity: 0.95; }
        .vat-pill {
            display: inline-block;
            margin-top: 6px;
            padding: 4px 8px;
            border-radius: 999px;
            background: rgba(255,255,255,0.2);
            font-weight: 800;
            font-size: 9.5px;
        }

        /* ===== INFO CARDS (Space Above Minimized to 0) ===== */
        .row-table {
            width: 100%;
            border-collapse: collapse;
            /* REMOVED TOP MARGIN (only keeping 8px at the bottom) */
            margin-top: 0;
            margin-bottom: 8px;
        }
        .col { width: 50%; vertical-align: top; }
        .col-left { padding-right: 8px; }
        .col-right { padding-left: 8px; }

        .card {
            border: 1px solid #d1d5db;
            background: #f9fafb;
            border-radius: 8px;
            padding: 12px;
            box-sizing: border-box;
            min-height: 110px;
        }
        .card-title {
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 8px;
            color: #4b5563;
        }
        .billto strong { font-size: 13px; color: #1f2937; }
        .billto div { font-size: 11px; }

        .meta-table { width: 100%; border-collapse: collapse; font-size: 11px; }
        .meta-table td { padding: 3px 0; }
        .meta-label { color: #6b7280; }
        .meta-value { text-align: right; font-weight: 700; color: #1f2937; }

        /* ===== ITEMS TABLE (Refined) ===== */
        table.items {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
            margin-bottom: 15px;
        }
        table.items thead th {
            background: #1f2937;
            color: #ffffff;
            font-weight: 700;
            padding: 8px 7px;
            border: none;
            text-align: left;
            white-space: nowrap;
        }
        table.items tbody td {
            padding: 7px 7px;
            border-left: 1px solid #e5e7eb;
            border-right: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
            background: #ffffff;
        }
        table.items tbody tr:first-child td { border-top: 1px solid #e5e7eb; }
        table.items tbody tr:nth-child(even) td { background: #f8fafc; }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .qty-col { width: 6%; text-align: center; }
        .code-col { width: 12%; }
        .desc-col { width: 44%; }
        .money-col { width: 8%; text-align: right; }

        /* ===== TOTALS (Fixed and Styled - Logo and Name in Footer) ===== */
        .totals-wrap {
            width: 100%;
            margin-top: 15px;
            display: block;
        }

        .totals-logo-container {
            float: left;
            width: 280px;
            text-align: left;
            padding: 12px;
            box-sizing: border-box;
        }
        .totals-logo {
            width: 80px;
            height: auto;
            display: inline-block;
            margin-right: 8px;
            vertical-align: middle;
        }
        .totals-company-name {
            display: inline-block;
            font-size: 14px;
            font-weight: 900;
            color: #1e3a8a;
            vertical-align: middle;
            line-height: 1.2;
        }


        .totals {
            width: 300px;
            float: right;
            border: 1px solid #d1d5db;
            background: #f9fafb;
            border-radius: 8px;
            padding: 12px 14px;
            box-sizing: border-box;
            clear: none;
        }
        .totals table { width: 100%; border-collapse: collapse; font-size: 11px; }
        .totals td { padding: 4px 0; }
        .total-label { color: #4b5563; }
        .total-value { text-align: right; font-weight: 800; }

        .grand-total-row td {
            border-top: 1px dashed #cbd5e1;
            padding-top: 8px;
            font-size: 13px;
            font-weight: 900;
        }

        /* Footer/Notes */
        .notes {
            clear: both;
            margin-top: 25px;
            font-size: 9.5px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
            line-height: 1.5;
        }

        .comments-box {
            margin-top: 10px;
            font-size: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            background: #f9fafb;
        }
        .comments-title {
            font-weight: 900;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-size: 10px;
            color: #4b5563;
        }
    </style>
</head>

<body>
<div class="invoice-container">

    {{-- ===== HEADER (compact - Space below minimized to 1px) ===== --}}
    <div class="header">
        <table class="header-table">

        </table>
    </div>

    {{-- ===== BILL TO + META (Space above minimized to 0) ===== --}}
    <table class="row-table">
        <tr>
            <td class="col col-left">
                <div class="card billto">
                    <div class="card-title">Bill To / Deliver To</div>
                    <div style="line-height: 1.5;">
                        <strong>{{ $order->shop->company_name ?? 'N/A' }}</strong><br>
                        {{ $order->shop->shopname ?? '' }}<br>
                        {{ $order->shop->address ?? '' }}<br>
                        {{ $order->shop->city ?? '' }}<br>
                        {{ $order->shop->postcode ?? '' }}
                    </div>
                </div>
            </td>

            <td class="col col-right">
                <div class="card">
                    <div class="card-title">Order Information</div>
                    <table class="meta-table">
                        <tr>
                            <td class="meta-label">Order No</td>
                            <td class="meta-value">{{ $order->invoice_number }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Order Date</td>
                            <td class="meta-value">{{ $order->created_at->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">PO No</td>
                            <td class="meta-value">{{ $order->po_number ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Account Ref</td>
                            <td class="meta-value">{{ $order->shop->ref ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    {{-- PHP variables remain the same --}}
    @php
        $globalVatRate = (float) (sys_config('vat') ?? 0);
        $totalNet = 0;
        $totalVat = 0;
        $totalGross = 0;
    @endphp

    {{-- ===== ITEMS TABLE (No Change) ===== --}}
    <table class="items">
        <thead>
        <tr>
            <th class="qty-col">Qty</th>
            <th class="code-col">Code</th>
            <th class="desc-col">Description</th>
            <th class="money-col">Net</th>
            <th class="money-col">VAT%</th>
            <th class="money-col">VAT</th>
            <th class="money-col">Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->orderProducts as $item)
            @php
                $unitGross = (float) $item->selling_price;
                $unitDiscount = (float) ($item->discount ?? 0);
                $unitGrossAfterDiscount = max($unitGross - $unitDiscount, 0);
                $qty = (int) $item->quantity;

                $productVatFlag = optional($item->product)->vat === 'yes';

                if ($productVatFlag) {
                    $unitNet = $unitGrossAfterDiscount / (1 + $globalVatRate / 100);
                    $unitVat = $unitGrossAfterDiscount - $unitNet;
                    $vatPercent = $globalVatRate;
                } else {
                    $unitNet = $unitGrossAfterDiscount;
                    $unitVat = 0;
                    $vatPercent = 0;
                }

                $lineNet = $unitNet * $qty;
                $lineVat = $unitVat * $qty;
                $lineGross = $unitGrossAfterDiscount * $qty;

                $totalNet += $lineNet;
                $totalVat += $lineVat;
                $totalGross += $lineGross;
            @endphp

            <tr>
                <td class="qty-col text-center">{{ $qty }}</td>
                <td class="code-col">{{ optional($item->product)->model_number }}</td>
                <td class="desc-col">{{ optional($item->product)->name }}</td>
                <td class="money-col text-right">{{ number_format($lineNet, 2) }}</td>
                <td class="money-col text-right">{{ number_format($vatPercent, 2) }}</td>
                <td class="money-col text-right">{{ number_format($lineVat, 2) }}</td>
                <td class="money-col text-right">{{ number_format($lineGross, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{-- ===== TOTALS (Fixed and Styled - Logo and Name in Footer) ===== --}}
    <div class="totals-wrap">
        <div class="totals-logo-container">
            <img class="totals-logo" src="{{ public_path('assets/jdm_distributors_logo.jpeg') }}" alt="JDM Logo">
            <div class="totals-company-name">{{ sys_config('company_name') }}</div>
        </div>
        <div class="totals">
            <table>
                <tr>
                    <td class="total-label">Subtotal (Net)</td>
                    <td class="total-value">£ {{ number_format($totalNet, 2) }}</td>
                </tr>
                <tr>
                    <td class="total-label">Total VAT/Tax</td>
                    <td class="total-value">£ {{ number_format($totalVat, 2) }}</td>
                </tr>
                <tr class="grand-total-row">
                    <td class="total-label">Order Total (Gross)</td>
                    <td class="total-value">£ {{ number_format($totalGross, 2) }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- ===== NOTES & COMMENTS (Float cleared) ===== --}}
    <div class="notes">
        Title of goods on the invoice does not pass to the buyer until invoice is fully paid.<br>
        <strong>Important:</strong> Importers details &amp; nutritional/allergen info supplied for all products.
        You must place labels on all products sold to the final consumer.
    </div>

    @if(!empty($order->comments_about_your_order))
        <div class="comments-box">
            <div class="comments-title">Order Comments</div>
            <div>{{ $order->comments_about_your_order }}</div>
        </div>
    @endif

</div>
</body>
</html>