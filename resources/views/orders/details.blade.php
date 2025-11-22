<x-app-layout>
    <style>
        body {
            background-color: #f3f4f6;
        }

        .order-page {
            /* background: radial-gradient(circle at top left, #e0ecff 0, #f9fafb 40%, #f3f4f6 100%); */
            min-height: 100vh;
        }

        .order-title {
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .order-title span.icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 999px;
            background: #e0ebff;
            font-size: 20px;
        }

        .order-card {
            border-radius: 1.1rem;
            border: 0;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.12);
            overflow: hidden;
        }

        .order-card-header {
            padding: 18px 22px;
        }

        /* 🇺🇸 Reusable US Flag Header */
        .usa-flag-header {
            background:
                linear-gradient(135deg, rgba(11, 60, 93, 0.92), rgba(30, 64, 175, 0.92)),
                url('https://upload.wikimedia.org/wikipedia/commons/a/a4/Flag_of_the_United_States.svg');
            background-size: cover;
            background-position: center right;
            color: #ffffff;
            position: relative;
        }

        .usa-flag-header h4,
        .usa-flag-header h5 {
            color: #ffffff;
            margin: 0;
        }

        .order-meta {
            font-size: 0.9rem;
            color: #e5e7eb;
        }

        .badge-status {
            border-radius: 999px;
            padding: 0.35rem 0.9rem;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-paid {
            background-color: #16a34a;
            color: #f9fafb;
        }

        .badge-pending {
            background-color: #f97316;
            color: #111827;
        }

        .badge-other {
            background-color: #6b7280;
            color: #f9fafb;
        }

        .order-section-title {
            font-size: 0.8rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #6b7280;
            font-weight: 600;
        }

        .order-value {
            font-weight: 600;
        }

        .order-total-highlight {
            font-size: 1.4rem;
            font-weight: 700;
            color: #111827;
        }

        .comments-box {
            background-color: #f9fafb;
            border-radius: 0.8rem;
            padding: 0.9rem 1rem;
            border: 1px dashed #d1d5db;
            font-size: 0.95rem;
        }

        .table thead {
            background: #e0ebff;
            color: #111827;
        }

        .table thead th {
            border-bottom: 2px solid #c4d5ff;
        }

        .table-hover tbody tr:hover {
            background-color: #f3f4ff;
        }

        .grand-total-bar {
            border-radius: 0.9rem;
            background: #0f172a;
            color: #f9fafb;
            padding: 0.85rem 1.2rem;
            display: inline-flex;
            align-items: center;
            gap: 0.7rem;
        }

        .grand-total-bar span.label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            opacity: 0.8;
        }

        .grand-total-bar span.amount {
            font-size: 1.3rem;
            font-weight: 700;
        }

        .btn-primary {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
            border-radius: 999px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #1e40af;
            border-color: #1e40af;
        }

        .btn-success {
            border-radius: 999px;
            font-weight: 600;
        }

        .btn-secondary {
            border-radius: 999px;
            font-weight: 600;
        }

        .actions-row .btn {
            min-width: 170px;
        }

        .alert-success,
        .alert-danger {
            border-radius: 0.7rem;
        }
    </style>

    <main class="container py-5 order-page">

        {{-- Title --}}
        <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h2 class="order-title mb-0">
                <span class="icon">🧾</span>
                <span>Order Details</span>
            </h2>
        </div>

        {{-- Success / Error messages --}}
        @if(session('success'))
            <div class="alert alert-success text-center mb-3">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger text-center mb-3">
                {{ session('error') }}
            </div>
        @endif

        {{-- Order Summary Card --}}
        <div class="card order-card mb-4">
            <div class="order-card-header usa-flag-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <div class="order-section-title mb-1 text-white-50">Order Summary</div>
                    <h4>#{{ $order->id }} — Invoice {{ $order->invoice_number }}</h4>
                    <div class="order-meta mt-1">
                        Created: {{ $order->created_at->format('d M Y, H:i') }}
                    </div>
                </div>

                {{-- Payment Status Badge --}}
                <div>
                    @php
                        $status = strtolower($order->payment_status);
                    @endphp
                    @if($status === 'success' || $status === 'paid')
                        <span class="badge-status badge-paid">Paid</span>
                    @elseif($status === 'pending')
                        <span class="badge-status badge-pending">Pending</span>
                    @else
                        <span class="badge-status badge-other">{{ ucfirst($order->payment_status) }}</span>
                    @endif
                </div>
            </div>

            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="order-section-title mb-1">Order ID</div>
                        <div class="order-value">#{{ $order->id }}</div>
                    </div>

                    <div class="col-md-4">
                        <div class="order-section-title mb-1">Invoice Number</div>
                        <div class="order-value">{{ $order->invoice_number }}</div>
                    </div>

                    <div class="col-md-4">
                        <div class="order-section-title mb-1">Payment Status</div>
                        <div class="order-value">{{ ucfirst($order->payment_status) }}</div>
                    </div>

                    <div class="col-md-4">
                        <div class="order-section-title mb-1">Order Date</div>
                        <div class="order-value">{{ $order->created_at->format('d M Y, H:i') }}</div>
                    </div>

                    <div class="col-md-4">
                        <div class="order-section-title mb-1">Total Amount</div>
                        <div class="order-total-highlight">
                            £{{ number_format($order->total, 2) }}
                        </div>
                    </div>

                    <div class="col-12">
                        <hr>
                        <div class="order-section-title mb-2">Comments About This Order</div>
                        <div class="comments-box">
                            {{ $order->comments_about_your_order ? $order->comments_about_your_order : 'No comments provided.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Products Table --}}
        <div class="card order-card mb-4">
            <div class="order-card-header usa-flag-header">
                <div class="order-section-title mb-1 text-white-50">Order Items</div>
                <h5 class="mb-0 text-white">Products In This Order</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Model Number</th>
                                <th>Product</th>
                                <th>Price (£)</th>
                                <th>Quantity</th>
                                <th>Subtotal (£)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orderProducts as $item)
                                <tr>
                                    <td>{{ $item->product->model_number ?? 'Deleted Product' }}</td>
                                    <td>{{ $item->product->name ?? 'Deleted Product' }}</td>
                                    <td>£{{ number_format($item->selling_price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>£{{ number_format($item->selling_price * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Grand Total --}}
                <div class="d-flex justify-content-end mt-4">
                    <div class="grand-total-bar">
                        <span class="label">Grand Total</span>
                        <span class="amount">£{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="mt-4 row g-3 actions-row">
            <div class="col-md-4 d-grid">
                <a href="{{ route('orders.invoice', $order->id) }}" class="btn btn-success">
                    Download Invoice PDF
                </a>
            </div>

            <div class="col-md-4 d-grid">
                <a href="{{ route('orders.export', $order->id) }}" class="btn btn-success">
                    Download Excel
                </a>
            </div>

            <div class="col-md-4 d-grid">
                <a href="{{ route('orders.sendEmail', $order->id) }}" class="btn btn-primary">
                    📧 Send to Email
                </a>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-start">
            <a href="{{ url('/') }}" class="btn btn-secondary">
                ← Back to Home
            </a>
        </div>

    </main>
</x-app-layout>
