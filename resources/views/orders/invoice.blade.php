<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->invoice_number }}</title>

    <style>
        /* ===== GLOBAL / PAGE ===== */
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
            background: #f1f5f9; /* light grey-blue */
        }

        .invoice-wrapper {
            width: 100%;
        }

        .invoice-container {
            background: #ffffff;
            padding: 24px 28px 32px 28px;
            margin: 10px auto;
            width: 100%;
            box-sizing: border-box;
        }

        .header-bar {
            width: 100%;
            padding: 10px 14px;
            margin-bottom: 18px;
            background: linear-gradient(90deg, #0b3c5d, #1d4ed8); /* deep blue -> royal blue */
            color: #ffffff;
            box-sizing: border-box;
        }

        .header-bar-left {
            float: left;
            width: 60%;
        }

        .header-bar-right {
            float: right;
            width: 40%;
            text-align: right;
            font-size: 10px;
        }

        .logo {
            width: 130px;
            margin-bottom: 4px;
        }

        .invoice-title {
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .invoice-subtitle {
            font-size: 10px;
            opacity: 0.9;
        }

        .company-name {
            font-size: 13px;
            font-weight: bold;
        }

        .company-info-line {
            line-height: 1.4;
        }

        .clear {
            clear: both;
        }

        /* ===== DETAILS SECTION ===== */
        .details-row {
            width: 100%;
            margin-top: 10px;
            margin-bottom: 18px;
        }

        .details-box {
            width: 46%;
            border: 1px solid #e5e7eb;
            padding: 10px 12px;
            display: inline-block;
            vertical-align: top;
            box-sizing: border-box;
            border-radius: 4px;
            background: #f9fafb;
        }

        .details-box-right {
            float: right;
        }

        .details-title {
            font-weight: bold;
            margin-bottom: 6px;
            font-size: 11px;
            color: #0f172a;
            text-transform: uppercase;
        }

        .details-text {
            font-size: 11px;
            line-height: 1.5;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .details-table td {
            padding: 2px 0;
        }

        .details-label {
            color: #6b7280;
        }

        .details-value {
            text-align: right;
            font-weight: 600;
            color: #111827;
        }

        /* ===== ITEMS TABLE ===== */
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
        }

        table.items thead th {
            background: #e5e7eb;
            font-weight: bold;
            padding: 7px 6px;
            border: 1px solid #d1d5db;
            text-align: left;
            white-space: nowrap;
        }

        table.items tbody td {
            padding: 6px 6px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
        }

        table.items tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .code-col {
            width: 12%;
        }

        .qty-col {
            width: 8%;
            text-align: center;
        }

        .desc-col {
            width: 40%;
        }

        .money-col {
            width: 12%;
            text-align: right;
        }

        /* ===== TOTALS BOX ===== */
        .totals-wrapper {
            width: 100%;
            margin-top: 16px;
        }

        .totals {
            width: 260px;
            float: right;
            border-radius: 4px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            padding: 8px 10px;
            box-sizing: border-box;
        }

        .totals table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .totals td {
            padding: 4px 0;
        }

        .totals .total-label {
            text-align: left;
            color: #4b5563;
        }

        .totals .total-value {
            text-align: right;
            font-weight: 600;
            color: #111827;
        }

        .grand-total-row td {
            border-top: 1px solid #9ca3af;
            padding-top: 6px;
            font-size: 12px;
            font-weight: bold;
        }

        /* ===== FOOTER NOTES ===== */
        .notes {
            margin-top: 30px;
            font-size: 10px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
            line-height: 1.4;
        }

        .comments-box {
            margin-top: 12px;
            font-size: 10px;
            padding: 8px 10px;
            border-radius: 4px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
        }

        .comments-title {
            font-weight: bold;
            margin-bottom: 4px;
            color: #111827;
        }
    </style>
</head>

<body>
<div class="invoice-wrapper">
<div class="invoice-container">

    {{-- ===== HEADER BAR ===== --}}
    <div class="header-bar">
        <div class="header-bar-left">
            <img class="logo" src="{{ public_path('assets/jdm_distributors_logo.jpeg') }}">
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-subtitle">Invoice #{{ $order->invoice_number }} &nbsp;|&nbsp; Page 1 of 1</div>
        </div>

        <div class="header-bar-right">
            <div class="company-name">{{ sys_config('company_name') }}</div>
            <div class="company-info-line">{{ sys_config('address') }}</div>
            <div class="company-info-line">
                E: {{ sys_config('email') }} &nbsp;|&nbsp; T: {{ sys_config('phone') }}
            </div>
            <div class="company-info-line">www.jdmdistributors.co.uk</div>
            <div class="company-info-line">
                <strong>VAT Reg No: {{ sys_config('vatnum') }}</strong>
            </div>
        </div>

        <div class="clear"></div>
    </div>


    {{-- ===== BILL TO & INVOICE META ===== --}}
    <div class="details-row">

        {{-- Bill To --}}
        <div class="details-box">
            <div class="details-title">Bill To</div>
            <div class="details-text">
                <strong>{{ $order->shop->company_name ?? 'N/A' }}</strong><br>
                {{ $order->shop->shopname ?? '' }}<br>
                {{ $order->shop->address ?? '' }}<br>
                {{ $order->shop->city ?? '' }}<br>
                {{ $order->shop->postcode ?? '' }}
            </div>
        </div>

        {{-- Invoice Details --}}
        <div class="details-box details-box-right">
            <div class="details-title">Invoice Details</div>
            <table class="details-table">
                <tr>
                    <td class="details-label">Invoice No</td>
                    <td class="details-value">{{ $order->invoice_number }}</td>
                </tr>
                <tr>
                    <td class="details-label">Invoice Date</td>
                    <td class="details-value">{{ $order->created_at->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td class="details-label">PO No</td>
                    <td class="details-value">{{ $order->po_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="details-label">Account Ref</td>
                    <td class="details-value">{{ $order->shop->ref ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <div class="clear"></div>
    </div>

    @php
        // Global VAT rate (for VATable products)
        $globalVatRate = sys_config('vat') ?? 0;

        $totalDiscount = 0;   // total discount (per-unit * qty)
        $totalNet      = 0;   // total ex-VAT
        $totalVat      = 0;   // total VAT
        $totalGross    = 0;   // total inc VAT
    @endphp

    {{-- ===== ITEMS TABLE ===== --}}
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
                // Per-unit selling price saved in DB (GROSS = inc VAT if applicable)
                $unitGross = (float) $item->selling_price;

                // Discount treated as per-unit (same basis as selling_price)
                $unitDiscount = (float) ($item->discount ?? 0);

                // Effective per-unit gross after discount
                $unitGrossAfterDiscount = max($unitGross - $unitDiscount, 0);

                $qty = (int) $item->quantity;

                // Check if this product is VATable
                $productVatFlag = optional($item->product)->vat === 'yes';

                if ($productVatFlag) {
                    // Extract net + VAT from gross
                    $unitNetExVat = $unitGrossAfterDiscount / (1 + $globalVatRate / 100);
                    $unitVat      = $unitGrossAfterDiscount - $unitNetExVat;
                    $vatPercent   = $globalVatRate;
                } else {
                    $unitNetExVat = $unitGrossAfterDiscount;
                    $unitVat      = 0;
                    $vatPercent   = 0;
                }

                $lineNet   = $unitNetExVat * $qty;
                $lineVat   = $unitVat * $qty;
                $lineGross = $unitGrossAfterDiscount * $qty;

                // accumulate totals
                $totalDiscount += $unitDiscount * $qty;
                $totalNet      += $lineNet;
                $totalVat      += $lineVat;
                $totalGross    += $lineGross;
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


    {{-- ===== TOTALS SECTION ===== --}}
    <div class="totals-wrapper">
        <div class="totals">
            <table>
                {{-- Uncomment if you want to show total discount --}}
                {{-- <tr>
                    <td class="total-label">Total Discount</td>
                    <td class="total-value">£ {{ number_format($totalDiscount, 2) }}</td>
                </tr> --}}
                <tr>
                    <td class="total-label">Total Net Amount</td>
                    <td class="total-value">£ {{ number_format($totalNet, 2) }}</td>
                </tr>
                <tr>
                    <td class="total-label">Total Tax Amount</td>
                    <td class="total-value">£ {{ number_format($totalVat, 2) }}</td>
                </tr>
                <tr class="grand-total-row">
                    <td class="total-label">Invoice Total</td>
                    <td class="total-value">£ {{ number_format($totalGross, 2) }}</td>
                </tr>
            </table>
        </div>
        <div class="clear"></div>
    </div>

    {{-- ===== FOOTER NOTES ===== --}}
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
</div>
</body>
</html>
