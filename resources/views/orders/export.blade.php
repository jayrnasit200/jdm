<table>
    <thead>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orderProducts as $item)
        <tr>
            <td>{{ $item->product->name }}</td>
            <td>{{ $item->selling_price }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ $item->selling_price * $item->quantity }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
