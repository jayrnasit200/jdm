<x-app-layout>

    {{-- 🎨 Page Styling --}}
    <style>
        body {
            background-color: #f3f4f6;
        }

        .page-bg {
            /* background: radial-gradient(circle at top left, #e0ecff 0, #f9fafb 40%, #f3f4f6 100%); */
            min-height: 100vh;
        }

        /* Shop card header */
        .shop-card-header {
    background:
        linear-gradient(135deg, rgba(11,60,93,0.90), rgba(30,64,175,0.90)),
        url('https://upload.wikimedia.org/wikipedia/commons/a/a4/Flag_of_the_United_States.svg');
    background-size: cover;
    background-position: center right;
    color: #ffffff;
    border-top-left-radius: 1rem;
    border-top-right-radius: 1rem;
}


        .shop-card {
            border-radius: 1rem;
            border: 0;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
            overflow: hidden;
        }

        .badge-ref {
            background: rgba(255, 255, 255, 0.9);
            color: #111827;
            font-weight: 600;
        }

        .section-label {
            font-size: 0.8rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #6b7280;
            font-weight: 600;
        }

        /* Stats cards */
        .stat-card {
            border-radius: 1rem;
            border: 0;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.12);
            overflow: hidden;
            position: relative;
        }

        .stat-card::after {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.25), transparent 60%);
            opacity: 0.7;
            pointer-events: none;
        }

        .stat-card-body {
            position: relative;
            z-index: 1;
        }

        .stat-label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            opacity: 0.85;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            margin-top: 0.2rem;
        }

        .stat-icon {
            font-size: 2rem;
            opacity: 0.8;
        }

        .stat-year {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff;
        }

        .stat-alltime {
            background: linear-gradient(135deg, #16a34a, #15803d);
            color: #ffffff;
        }

        .stat-pending {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: #ffffff;
        }

        /* Orders card */
        .orders-card {
            border-radius: 1rem;
            border: 0;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.15);
            overflow: hidden;
        }

        .orders-header {
            background: linear-gradient(135deg, #111827, #020617);
            color: #ffffff;
            padding-top: 0.9rem;
            padding-bottom: 0.9rem;
        }

        .orders-header h4 {
            margin: 0;
            font-weight: 600;
        }

        .orders-header .btn-light {
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 999px;
        }

        .table thead.table-primary {
            background-color: #e0ebff !important;
            color: #1f2933 !important;
        }

        .table thead.table-primary th {
            border-bottom: 2px solid #c4d5ff;
        }

        .table-hover tbody tr:hover {
            background-color: #f3f4ff;
        }

        .badge-success {
            background-color: #16a34a !important;
        }

        .badge-warning {
            background-color: #facc15 !important;
        }

        .badge-secondary {
            background-color: #6b7280 !important;
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

        .btn-outline-light {
            border-radius: 999px;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            background-color: #e5e7eb;
            font-size: 0.8rem;
            color: #4b5563;
        }
    </style>

    <div class="py-4 page-bg">
        <div class="container">

            <!-- 🏪 Shop Info -->
            <div class="card shop-card mb-4">
                <div class="card-header shop-card-header d-flex justify-content-between align-items-center">
                    <div>
                        <div class="section-label mb-1 text-light">Shop Profile</div>
                        <h3 class="mb-0">{{ $shop->shopname }}</h3>
                    </div>
                    <span class="badge badge-ref px-3 py-2">
                        Ref: {{ $shop->ref }}
                    </span>
                </div>
                <div class="card-body row g-4">
                    <div class="col-md-6">
                        <h6 class="text-uppercase text-muted fw-semibold mb-2">Business Details</h6>
                        <p class="mb-1"><strong>Company Name:</strong> {{ $shop->company_name }}</p>
                        <p class="mb-1"><strong>Email:</strong> {{ $shop->email }}</p>
                        <p class="mb-1"><strong>Phone:</strong> {{ $shop->phone }}</p>
                        <p class="mb-0"><strong>VAT Number:</strong> {{ $shop->Vat ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-uppercase text-muted fw-semibold mb-2">Location</h6>
                        <p class="mb-1"><strong>Address:</strong> {{ $shop->address }}</p>
                        <p class="mb-1"><strong>City:</strong> {{ $shop->city }}</p>
                        <p class="mb-1"><strong>Postcode:</strong> {{ $shop->postcode }}</p>
                        <p class="mb-0"><strong>Created On:</strong> {{ $shop->created_at->format('d M Y') }}</p>
                    </div>

                    <div class="col-12">
                        <hr>
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h6 class="text-uppercase text-muted fw-semibold mb-2">Staff Details</h6>
                                <p class="mb-1"><strong>Staff Name:</strong> {{ $shop->Name_staff ?? 'N/A' }}</p>
                                <p class="mb-1"><strong>Staff Number 1:</strong> {{ $shop->Staffnumber1 ?? 'N/A' }}</p>
                                <p class="mb-0"><strong>Staff Number 2:</strong> {{ $shop->Staffnumber2 ?? 'N/A' }}</p>
                            </div>
                            <div class="chip">
                                <i class="fa fa-store"></i>
                                <span>US Product Partner</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 📊 Stats -->
            <div class="row g-3 mb-4">

                <div class="col-md-4">
                    <div class="card stat-card stat-year">
                        <div class="card-body stat-card-body text-center">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="stat-label">This Year</span>
                                <i class="fa fa-calendar-alt stat-icon"></i>
                            </div>
                            <h5 class="card-title mb-1">Total Sales</h5>
                            <div class="stat-value">
                                £{{ number_format($yearSales, 2) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card stat-card stat-alltime">
                        <div class="card-body stat-card-body text-center">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="stat-label">Lifetime</span>
                                <i class="fa fa-chart-line stat-icon"></i>
                            </div>
                            <h5 class="card-title mb-1">All-Time Sales</h5>
                            <div class="stat-value">
                                £{{ number_format($totalSales, 2) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card stat-card stat-pending">
                        <div class="card-body stat-card-body text-center">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="stat-label">Outstanding</span>
                                <i class="fa fa-clock stat-icon"></i>
                            </div>
                            <h5 class="card-title mb-1">Pending Payments</h5>
                            <div class="stat-value">
                                £{{ number_format($pendingSales, 2) }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- 📋 Orders Table -->
            <div class="card orders-card">
                <div class="card-header orders-header d-flex justify-content-between align-items-center">
                    <div>
                        <div class="section-label mb-1">Order History</div>
                        <h4 class="mb-0">Orders</h4>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('orders.create', ['shop_id' => $shop->id]) }}" class="btn btn-sm btn-light">
                            <i class="fa fa-plus me-1"></i> Add Order
                        </a>
                        <span class="chip">
                            <i class="fa fa-shopping-cart"></i>
                            <span>{{ $shop->orders->count() }} orders</span>
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="ordersTable" class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Invoice</th>
                                    {{-- <th>Comments</th> --}}
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th style="width: 80px;">View</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($shop->orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->created_at->format('d M Y') }}</td>
                                        <td>{{ $order->invoice_number }}</td>
                                        {{-- <td>{{ Str::limit($order->comments_about_your_order, 30) ?? '—' }}</td> --}}
                                        <td>
                                            @if ($order->payment_status === 'success')
                                                <span class="badge bg-success">Success</span>
                                            @elseif ($order->payment_status === 'Pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @else
                                                <span class="badge bg-secondary">Other</span>
                                            @endif
                                        </td>
                                        <td>£{{ number_format($order->total, 2) }}</td>
                                        <td class="text-end" style="white-space: nowrap;">

                                            {{-- View --}}
                                            <a href="{{ route('order.details', $order->id) }}"
                                               class="btn btn-primary btn-sm d-inline-block me-1"
                                               title="View Order">
                                                <i class="fa fa-arrow-right"></i>
                                            </a>

                                            {{-- Edit --}}
                                            <a href="{{ route('order.manage', $order->id) }}"
                                               class="btn btn-warning btn-sm d-inline-block me-1"
                                               title="Edit Order">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            {{-- Delete --}}
                                            <form action="{{ url('order.delete', $order->id) }}"
                                                  method="POST"
                                                  class="d-inline-block"
                                                  onsubmit="return confirm('Are you sure you want to delete this order?');">
                                                @csrf
                                                @method('DELETE')

                                                <button class="btn btn-danger btn-sm" title="Delete Order">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>

                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            No orders found for this shop.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
