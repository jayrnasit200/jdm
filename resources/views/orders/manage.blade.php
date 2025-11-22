<x-app-layout>

    <style>
        .order-page-wrapper {
            padding-top: 24px;
            padding-bottom: 24px;
        }
        .order-header-card {
            border-radius: 1rem;
            border: 0;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.12);
            overflow: hidden;
        }
        .order-header-top {
            background: linear-gradient(135deg, #0b3c5d, #1e40af);
            color: #ffffff;
            padding: 18px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .order-header-title {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 700;
        }
        .order-header-meta {
            font-size: 0.85rem;
            opacity: 0.9;
        }
        .order-header-bottom {
            padding: 14px 24px 6px 24px;
            background-color: #f9fafb;
        }
        .badge-status {
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.75rem;
        }
        .badge-status-success {
            background-color: #22c55e;
            color: #ffffff;
        }
        .badge-status-pending {
            background-color: #facc15;
            color: #1f2933;
        }
        .order-card-body {
            padding: 20px 24px 24px 24px;
        }
        .section-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: #111827;
        }
        .order-total-box {
            background-color: #0f172a;
            color: #f9fafb;
            border-radius: 0.75rem;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        .order-total-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        .order-total-value {
            font-size: 1.4rem;
            font-weight: 700;
        }
        .back-btn {
            border-radius: 999px;
        }
        .inline-form-btn {
            white-space: nowrap;
        }
    </style>

    <div class="container order-page-wrapper">

        {{-- Top Card: Title + Back --}}
        <div class="order-header-card mb-4">
            <div class="order-header-top">
                <div>
                    <h1 class="order-header-title">
                        Manage Order: {{ $order->invoice_number }}
                    </h1>
                    <div class="order-header-meta">
                        Created: {{ $order->created_at->format('d M Y, H:i') }} |
                        Order ID: #{{ $order->id }}
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge-status
                        {{ $order->payment_status === 'success'
                            ? 'badge-status-success'
                            : 'badge-status-pending' }}">
                        Payment: {{ ucfirst($order->payment_status) }}
                    </span>

                    <a href="{{ url()->previous() }}"
                       class="btn btn-outline-light btn-sm back-btn">
                        ← Back
                    </a>
                </div>
            </div>

            <div class="order-header-bottom">
                <span class="me-3">
                    Shop: <strong>{{ $order->shop->shopname ?? 'N/A' }}</strong>
                </span>
                <span class="me-3">
                    Ref: <strong>{{ $order->shop->ref ?? 'N/A' }}</strong>
                </span>
                <span>
                    Total Lines: <strong>{{ $order->orderProducts->count() }}</strong>
                </span>
            </div>

            <div class="order-card-body">

                {{-- Row: Payment status + Upload Invoice --}}
                <div class="row g-3 mb-3">

                    {{-- Payment Status --}}
                    <div class="col-md-6">
                        <h2 class="section-title">Payment Status</h2>
                        <form action="{{ route('order.updateStatus', $order->id) }}"
                              method="POST"
                              class="d-flex align-items-center gap-2">
                            @csrf
                            <select name="payment_status"
                                    class="form-control w-auto d-inline">
                                <option value="success" {{ $order->payment_status == 'success' ? 'selected' : '' }}>
                                    Success
                                </option>
                                <option value="Pending" {{ $order->payment_status == 'Pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                            </select>
                            <button class="btn btn-primary inline-form-btn">
                                Update
                            </button>
                        </form>
                    </div>

                    {{-- Upload Invoice + Preview --}}
                    <div class="col-md-6">
                        <h2 class="section-title">Invoice File</h2>
                        <form action="{{ route('order.uploadInvoice', $order->id) }}"
                              method="POST"
                              enctype="multipart/form-data"
                              class="d-flex align-items-center gap-2 flex-wrap">
                            @csrf
                            <input type="file"
                                   name="invoice_image"
                                   required
                                   class="form-control w-auto">
                            <button class="btn btn-success inline-form-btn">
                                Upload
                            </button>

                            @if($order->invoice)
                                @php
                                    $url   = asset('storage/' . $order->invoice);
                                    $isPdf = \Illuminate\Support\Str::endsWith(strtolower($order->invoice), '.pdf');
                                @endphp
                                <a href="{{ $url }}"
                                   target="_blank"
                                   class="btn btn-outline-secondary btn-sm">
                                    {{ $isPdf ? 'View Current PDF' : 'View Current File' }}
                                </a>
                            @endif
                        </form>

                        {{-- Small preview below (optional) --}}
                        @if($order->invoice)
                            @php
                                $url   = asset('storage/' . $order->invoice);
                                $isPdf = \Illuminate\Support\Str::endsWith(strtolower($order->invoice), '.pdf');
                            @endphp
                            <div class="mt-2">
                                @if($isPdf)
                                    <small class="text-muted">
                                        Current file: PDF (click "View Current PDF" to open)
                                    </small>
                                @else
                                    <a href="{{ $url }}" target="_blank">
                                        <img src="{{ $url }}" class="img-fluid border" style="max-width:180px;">
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <hr class="my-3">

                {{-- Products in Order --}}
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <h2 class="section-title mb-0">Products in this Order</h2>
                    <span class="text-muted small">
                        Edit price/quantity – totals update automatically.
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th style="width: 140px;">Price (£)</th>
                                <th style="width: 110px;">Quantity</th>
                                <th style="width: 130px;">Line Total (£)</th>
                                <th style="width: 150px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderProducts as $item)
                            <tr data-id="{{ $item->product->id }}">
                                <td>
                                    <div class="fw-semibold">{{ $item->product->name ?? 'Deleted product' }}</div>
                                    @if(!empty($item->product->model_number))
                                        <div class="text-muted small">
                                            Code: {{ $item->product->model_number }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <input type="number"
                                           class="form-control price-input"
                                           value="{{ $item->selling_price }}"
                                           min="0"
                                           step="0.01">
                                </td>

                                <td>
                                    <input type="number"
                                           class="form-control qty-input"
                                           value="{{ $item->quantity }}"
                                           min="1">
                                </td>

                                <td class="total-cell">
                                    {{ number_format($item->selling_price * $item->quantity, 2) }}
                                </td>

                                <td>
                                    <form action="{{ route('order.removeProductFromOrder', [$order->id, $item->product->id]) }}"
                                          method="POST"
                                          class="remove-item-form d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" type="submit">
                                            Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Total Box --}}
                <div class="order-total-box">
                    <div>
                        <div class="order-total-label">Order Total</div>
                        <div class="order-total-value">
                            £<span id="orderTotal">{{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="small text-gray-300">
                            Last updated: {{ now()->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>

                <hr class="my-3">

                {{-- Add Product --}}
                <h2 class="section-title">Add Product to Order</h2>
                <form action="{{ route('order.addProduct', $order->id) }}"
                      method="POST"
                      class="row g-3 align-items-end">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Product</label>
                        <select name="product_id"
                                id="productSelect"
                                class="form-control select2"
                                required>
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                    {{ $product->model_number }} | {{ $product->name }}
                                    (£{{ number_format($product->price,2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Quantity</label>
                        <input type="number"
                               name="quantity"
                               class="form-control"
                               min="1"
                               value="1"
                               required>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-success w-100">
                            Add Product
                        </button>
                    </div>
                </form>

                {{-- Bottom Back button --}}
                <div class="mt-4">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                        ← Back to Orders
                    </a>
                </div>

            </div>
        </div>
    </div>

    {{-- Select2 & jQuery (if not already loaded in layout) --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
    $(document).ready(function() {
        $('.select2').select2();

        // When price or qty changes -> update row + call backend + set order total
        $(document).on('input', '.price-input, .qty-input', function() {
            let row       = $(this).closest('tr');
            let productId = row.data('id');
            let price     = parseFloat(row.find('.price-input').val()) || 0;
            let qty       = parseInt(row.find('.qty-input').val()) || 1;

            // update line total in UI
            let lineTotal = (price * qty).toFixed(2);
            row.find('.total-cell').text(lineTotal);

            // update DB & order total
            $.post('{{ route("order.updateItem", $order->id) }}', {
                _token: '{{ csrf_token() }}',
                product_id: productId,
                price: price,
                quantity: qty
            })
            .done(function(data) {
                if (data.order_total !== undefined) {
                    $('#orderTotal').text(parseFloat(data.order_total).toFixed(2));
                }
            })
            .fail(function(xhr) {
                console.error('Error updating item:', xhr.responseText);
            });
        });

        // Remove product -> AJAX + update total
        $(document).on('submit', '.remove-item-form', function(e) {
            e.preventDefault();
            if (!confirm('Remove this product from the order?')) return;

            let form = $(this);
            let row  = form.closest('tr');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                headers: { 'Accept': 'application/json' },
                success: function(data) {
                    row.remove();
                    if (data.order_total !== undefined) {
                        $('#orderTotal').text(parseFloat(data.order_total).toFixed(2));
                    }
                },
                error: function(xhr) {
                    console.error('Error removing item:', xhr.responseText);
                    alert('Error removing item. Please try again.');
                }
            });
        });

        // Optional: log product price on select
        $('#productSelect').on('change', function() {
            let price = $(this).find(':selected').data('price') || 0;
            console.log('Selected Product Price:', price);
        });
    });
    </script>
</x-app-layout>
