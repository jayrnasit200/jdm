<x-app-layout>
    <div class="container py-4">

        <h2>Manage Order: {{ $order->invoice_number }}</h2>

        <!-- Payment Status -->
        <form action="{{ route('order.updateStatus', $order->id) }}" method="POST" class="mb-3">
            @csrf
            <label>Payment Status:</label>
            <select name="payment_status" class="form-control w-25 d-inline">
                <option value="success" {{ $order->payment_status == 'success' ? 'selected' : '' }}>Success</option>
                <option value="Pending" {{ $order->payment_status == 'Pending' ? 'selected' : '' }}>Pending</option>
            </select>
            <button class="btn btn-primary">Update</button>
        </form>

        <hr>

        <!-- Upload Invoice -->
        <form action="{{ route('order.uploadInvoice', $order->id) }}" method="POST" enctype="multipart/form-data" class="mb-3">
            @csrf
            <label>Upload Invoice:</label>
            <input type="file" name="invoice_image" required>
            <button class="btn btn-success">Upload</button>
        </form>

        @if($order->invoice_image)
            <p>Current Invoice:</p>
            <img src="{{ asset('storage/'.$order->invoice_image) }}" class="img-fluid" style="max-width:200px;">
        @endif

        <hr>

        <!-- Products in Order -->
        <h4>Products</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price (£)</th>
                    <th>Quantity</th>
                    <th>Total (£)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderProducts as $item)
                <tr data-id="{{ $item->product->id }}">
                    <td>{{ $item->product->name }}</td>
                    <td>
                        <input type="number" class="form-control price-input" value="{{ $item->selling_price }}" min="0" step="0.01">
                    </td>
                    <td>
                        <input type="number" class="form-control qty-input" value="{{ $item->quantity }}" min="1">
                    </td>
                    <td class="total-cell">{{ number_format($item->selling_price * $item->quantity, 2) }}</td>
                    <td>
                        <form action="{{ route('order.removeProductFromOrder', [$order->id, $item->product->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Remove</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h5>Total: £<span id="orderTotal">{{ number_format($order->total, 2) }}</span></h5>

        <hr>

        <!-- Add Product -->
        <form action="{{ route('order.addProduct', $order->id) }}" method="POST" class="row g-2">
            @csrf
            <div class="col-md-4">
                <select name="product_id" id="productSelect" class="form-control select2" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                            {{ $product->model_number }} | {{ $product->name }} (£{{ number_format($product->price,2) }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="quantity" class="form-control" min="1" value="1" required>
            </div>
            <div class="col-md-2">
                <button class="btn btn-success">Add Product</button>
            </div>
        </form>

    </div>

    <!-- Include jQuery & Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
    $(document).ready(function() {
        $('.select2').select2();

        // Update total and order via AJAX
        $('.price-input, .qty-input').on('input', function() {
            let row = $(this).closest('tr');
            let productId = row.data('id');
            let price = parseFloat(row.find('.price-input').val()) || 0;
            let qty = parseInt(row.find('.qty-input').val()) || 1;

            // Update row total immediately
            row.find('.total-cell').text((price * qty).toFixed(2));

            // Send AJAX to update DB
            $.post('{{ route("order.updateItem", $order->id) }}', {
                _token: '{{ csrf_token() }}',
                product_id: productId,
                price: price,
                quantity: qty
            }, function(data) {
                $('#orderTotal').text(data.order_total.toFixed(2));
            });
        });

        // Auto-fill price when product selected
        $('#productSelect').on('change', function() {
            let price = $(this).find(':selected').data('price') || 0;
            console.log('Selected Product Price:', price);
        });
    });
    </script>
</x-app-layout>
