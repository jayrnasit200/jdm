<x-app-layout>

    <div class="py-4">
        <div class="container">

            <!-- 🏪 Shop Info -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">{{ $shop->shopname }}</h3>
                    <span class="badge bg-light text-dark">Ref: {{ $shop->ref }}</span>
                </div>
                <div class="card-body row g-3">

                    <div class="col-md-6">
                        <p><strong>Company Name:</strong> {{ $shop->company_name }}</p>
                        <p><strong>Email:</strong> {{ $shop->email }}</p>
                        <p><strong>Phone:</strong> {{ $shop->phone }}</p>
                        <p><strong>VAT Number:</strong> {{ $shop->Vat ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-6">
                        <p><strong>Address:</strong> {{ $shop->address }}</p>
                        <p><strong>City:</strong> {{ $shop->city }}</p>
                        <p><strong>Postcode:</strong> {{ $shop->postcode }}</p>
                        <p><strong>Created On:</strong> {{ $shop->created_at->format('d M Y') }}</p>
                    </div>

                    <div class="col-12">
                        <hr>
                        <h5 class="text-secondary">Staff Details</h5>
                        <p><strong>Staff Name:</strong> {{ $shop->Name_staff ?? 'N/A' }}</p>
                        <p><strong>Staff Number 1:</strong> {{ $shop->Staffnumber1 ?? 'N/A' }}</p>
                        <p><strong>Staff Number 2:</strong> {{ $shop->Staffnumber2 ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- 📊 Stats -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card text-white bg-info shadow-sm border-0">
                        <div class="card-body text-center">
                            <h5 class="card-title">Total Sales (This Year)</h5>
                            <h3 class="fw-bold mt-2">£{{ number_format($yearSales, 2) }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-white bg-success shadow-sm border-0">
                        <div class="card-body text-center">
                            <h5 class="card-title">All-Time Sales</h5>
                            <h3 class="fw-bold mt-2">£{{ number_format($totalSales, 2) }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-white bg-danger shadow-sm border-0">
                        <div class="card-body text-center">
                            <h5 class="card-title">Pending Payments</h5>
                            <h3 class="fw-bold mt-2">£{{ number_format($pendingSales, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 📋 Orders Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Orders</h4>
                    <div>
                        <a href="{{ route('orders.create', ['shop_id' => $shop->id]) }}" class="btn btn-sm btn-light">
                            <i class="fa fa-plus me-1"></i> Add Order
                        </a>
                        <i class="fa fa-shopping-cart ms-2"></i>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="ordersTable" class="table table-striped table-hover align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Invoice</th>
                                    {{-- <th>Comments</th> --}}
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>View</th>
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
                                        <td> <a href="{{ route('order.details', $order->id) }}" class="btn btn-primary">
                                            <i class='fa fa-arrow-right'></i>
                                        </a>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No orders found for this shop.</td>
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
