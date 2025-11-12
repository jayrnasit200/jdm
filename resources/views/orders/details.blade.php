<x-app-layout>
    <main class="container py-5">

        <h2 class="mb-4">🧾 Order Details</h2>

        {{-- Success / Error messages --}}
        @if(session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger text-center">{{ session('error') }}</div>
        @endif

        <div class="card mb-4">
            <div class="card-body">
                <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                <p><strong>Invoice Number:</strong> {{ $order->invoice_number }}</p>
                <p><strong>Total:</strong> £{{ number_format($order->total, 2) }}</p>
                <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</p>
                <p><strong>Created At:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>

        <h4 class="mb-3">Products in this Order</h4>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price (£)</th>
                        <th>Quantity</th>
                        <th>Subtotal (£)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderProducts as $item)
                        <tr>
                            <td>{{ $item->product->name ?? 'Deleted Product' }}</td>
                            <td>£{{ number_format($item->selling_price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>£{{ number_format($item->selling_price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end fw-bold fs-5 mt-3">
            Grand Total: £{{ number_format($order->total, 2) }}
        </div>

        <div class="mt-4">
            <a href="{{ url('/') }}" class="btn btn-secondary rounded-pill">Back to Home</a>
        </div>

    </main>
</x-app-layout>
